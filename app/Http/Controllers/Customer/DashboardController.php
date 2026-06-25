<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\City;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bookings = Booking::where('customer_id', $user->id)
            ->with(['partner.partnerProfile', 'partner.city', 'review'])
            ->latest()
            ->get();

        $stats = [
            'total_bookings'     => $bookings->count(),
            'pending_bookings'   => $bookings->where('status', 'pending')->count(),
            'upcoming_bookings'  => $bookings->where('status', 'approved')->count(),
            'completed_bookings' => $bookings->where('status', 'completed')->count(),
            'reviews_count'      => Review::where('customer_id', $user->id)->count(),
            'favorites_count'    => $user->favorites()->count(),
        ];

        $cities = City::all();
        $unreadNotifs = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('customer.dashboard', compact('user', 'bookings', 'stats', 'cities', 'unreadNotifs'));
    }

    public function messages()
    {
        $user = Auth::user();
        $bookedPartners = Booking::where('customer_id', $user->id)
            ->with(['partner.partnerProfile', 'partner.city'])
            ->whereIn('status', ['approved', 'ongoing', 'completed'])
            ->latest()
            ->get()
            ->unique('partner_id');

        return view('customer.messages', compact('user', 'bookedPartners'));
    }

    public function wallet()
    {
        $user = Auth::user();
        $bookings = Booking::where('customer_id', $user->id)
            ->with('partner')
            ->latest()
            ->get();

        $totalSpent = $bookings->where('status', 'completed')->sum('total_amount');
        $totalSaved = $bookings->where('status', 'completed')->sum('discount_amount');

        return view('customer.wallet', compact('user', 'bookings', 'totalSpent', 'totalSaved'));
    }

    public function safety()
    {
        $user = Auth::user();
        $bookedPartners = Booking::where('customer_id', $user->id)
            ->with('partner')
            ->whereNotIn('status', ['cancelled'])
            ->latest()
            ->get()
            ->unique('partner_id');

        return view('customer.safety', compact('user', 'bookedPartners'));
    }

    public function settings()
    {
        $user = Auth::user()->load('city');
        $cities = City::all();
        return view('customer.settings', compact('user', 'cities'));
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('customer.notifications', compact('user', 'notifications'));
    }

    public function reviews()
    {
        $user = Auth::user();
        $reviews = Review::where('customer_id', $user->id)
            ->with(['partner.partnerProfile', 'booking'])
            ->latest()
            ->get();

        $pendingReviews = Booking::where('customer_id', $user->id)
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->with('partner')
            ->latest()
            ->get();

        return view('customer.reviews', compact('user', 'reviews', 'pendingReviews'));
    }

    public function favorites()
    {
        $user = Auth::user();
        $companions = $user->favorites()
            ->where('is_active', true)
            ->whereHas('partnerProfile', fn($q) => $q->where('kyc_status', 'approved'))
            ->with(['partnerProfile', 'city', 'services'])
            ->get();

        return view('customer.favorites', compact('user', 'companions'));
    }

    public function toggleFavorite(Request $request)
    {
        $request->validate([
            'companion_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $companionId = $request->companion_id;

        // Verify companion exists and is indeed a partner
        $companion = User::where('id', $companionId)
            ->where('role', 'partner')
            ->firstOrFail();

        $exists = $user->favorites()->where('companion_id', $companionId)->exists();

        if ($exists) {
            $user->favorites()->detach($companionId);
            return response()->json([
                'success' => true,
                'status' => 'removed',
                'message' => 'Removed from Favorites'
            ]);
        } else {
            $user->favorites()->attach($companionId);
            return response()->json([
                'success' => true,
                'status' => 'added',
                'message' => 'Added to Favorites'
            ]);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone'           => 'nullable|string|max:20',
            'gender'          => 'nullable|in:male,female,transgender,other',
            'city_id'         => 'required|exists:cities,id',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password'        => 'nullable|string|min:6|confirmed',
        ]);

        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->phone   = $request->phone;
        $user->gender  = $request->gender;
        $user->city_id = $request->city_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function book(Request $request, $partnerId)
    {
        $request->validate([
            'booking_date'     => 'required|date|after_or_equal:today',
            'start_time'       => 'required',
            'duration_hours'   => 'required|integer|min:1|max:24',
            'location_address' => 'required|string|max:255',
            'description'      => 'nullable|string|max:1000',
            'coupon_code'      => 'nullable|string',
        ]);

        $partner = User::where('id', $partnerId)
            ->where('role', 'partner')
            ->where('is_active', true)
            ->whereHas('partnerProfile', function ($q) {
                $q->where('kyc_status', 'approved');
            })
            ->firstOrFail();

        $hourlyRate = $partner->partnerProfile->hourly_rate;
        $subtotal = $hourlyRate * $request->duration_hours;
        $discountAmount = 0;
        $couponId = null;

        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\Coupon::where('code', strtoupper($request->coupon_code))->first();
            if ($coupon && $coupon->is_active && (!$coupon->expires_at || !$coupon->expires_at->isPast()) && ($coupon->uses_count < $coupon->max_uses)) {
                $couponId = $coupon->id;
                if ($coupon->type === 'percentage') {
                    $discountAmount = ($subtotal * $coupon->value) / 100;
                } elseif ($coupon->type === 'flat') {
                    $discountAmount = $coupon->value;
                } elseif ($coupon->type === 'cashback') {
                    // Cashback: No upfront discount — credit wallet after completion.
                    // We flag it for post-completion processing but apply no immediate discount.
                    $discountAmount = 0;
                } elseif ($coupon->type === 'referral') {
                    // Referral: Apply as flat benefit for the referred user
                    $discountAmount = $coupon->value;
                } else {
                    $discountAmount = $coupon->value;
                }
                if ($discountAmount > $subtotal) $discountAmount = $subtotal;
                $coupon->uses_count += 1;
                $coupon->save();
            }
        }

        $finalAmount = $subtotal - $discountAmount;

        $booking = Booking::create([
            'customer_id'      => Auth::id(),
            'partner_id'       => $partner->id,
            'booking_date'     => $request->booking_date,
            'start_time'       => date('H:i:s', strtotime($request->start_time)),
            'duration_hours'   => $request->duration_hours,
            'hourly_rate'      => $hourlyRate,
            'total_amount'     => $subtotal,
            'coupon_id'        => $couponId,
            'discount_amount'  => $discountAmount,
            'final_amount'     => $finalAmount,
            'status'           => 'pending_payment',
            'location_address' => $request->location_address,
            'description'      => $request->description,
        ]);

        return redirect()->route('customer.payment.checkout', $booking->id)->with('success', 'Booking created! Please complete payment to notify companion.');
    }

    public function paymentCheckout($booking_id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $booking_id)
            ->where('customer_id', $user->id)
            ->firstOrFail();

        if ($booking->status === 'paid') {
            return redirect()->route('customer.payment.receipt', $booking->id);
        }

        if ($booking->status !== 'pending_payment') {
            return redirect()->route('customer.dashboard')->with('warning', 'This booking is not pending payment.');
        }

        $partner = User::findOrFail($booking->partner_id);

        return view('customer.payment_checkout', compact('user', 'booking', 'partner'));
    }

    public function paymentProcess(Request $request, $booking_id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $booking_id)
            ->where('customer_id', $user->id)
            ->where('status', 'pending_payment')
            ->firstOrFail();

        $request->validate([
            'payment_method' => 'required|in:upi,card,net_banking,wallet',
            'upi_id' => 'required_if:payment_method,upi|nullable|string',
            'card_name' => 'required_if:payment_method,card|nullable|string',
            'card_number' => 'required_if:payment_method,card|nullable|string',
            'card_expiry' => 'required_if:payment_method,card|nullable|string',
            'card_cvv' => 'required_if:payment_method,card|nullable|string',
            'bank_name' => 'required_if:payment_method,net_banking|nullable|string',
            'wallet_provider' => 'required_if:payment_method,wallet|nullable|string',
        ]);

        $txnId = 'TXN_' . strtoupper(\Illuminate\Support\Str::random(12));

        $payment = \App\Models\Payment::create([
            'user_id' => $user->id,
            'amount' => $booking->final_amount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'completed',
            'transaction_id' => $txnId,
            'payable_type' => Booking::class,
            'payable_id' => $booking->id,
        ]);

        \App\Models\PaymentTransaction::create([
            'payment_id' => $payment->id,
            'payment_gateway' => 'MockGateway',
            'gateway_transaction_id' => $txnId,
            'response_payload' => json_encode($request->all()),
        ]);

        // Revenue Split: 20% platform commission, 80% partner earnings (custom commission override supported)
        $commissionPercent = 20.00;
        $customCommission = \App\Models\Commission::where('partner_id', $booking->partner_id)->first();
        if ($customCommission) {
            $commissionPercent = floatval($customCommission->commission_percentage);
        } else {
            $globalCommissionSetting = DB::table('settings')->where('key', 'platform_commission')->first();
            if ($globalCommissionSetting) {
                $commissionPercent = floatval($globalCommissionSetting->value);
            }
        }

        $totalAmount = $booking->final_amount;
        $commissionAmount = $totalAmount * ($commissionPercent / 100);
        $netAmount = $totalAmount - $commissionAmount;

        \App\Models\PartnerEarning::create([
            'booking_id' => $booking->id,
            'partner_id' => $booking->partner_id,
            'total_amount' => $totalAmount,
            'commission_amount' => $commissionAmount,
            'net_amount' => $netAmount,
            'status' => 'pending',
        ]);

        $booking->status = 'paid';
        $booking->save();

        // Notify companion
        $partner = User::findOrFail($booking->partner_id);
        DB::table('notifications')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\BookingRequest',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $partner->id,
            'data' => json_encode([
                'message' => 'New paid booking request from ' . $user->name,
                'booking_date' => $booking->booking_date->format('Y-m-d'),
                'total_amount' => $totalAmount,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('customer.payment.receipt', $booking->id)->with('success', 'Payment processed successfully!');
    }

    public function paymentReceipt($booking_id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $booking_id)
            ->where('customer_id', $user->id)
            ->with(['payments', 'partner.partnerProfile'])
            ->firstOrFail();

        $payment = $booking->payments->where('payment_status', 'completed')->first();
        if (!$payment) {
            $payment = $booking->payments->first();
        }

        return view('customer.payment_receipt', compact('user', 'booking', 'payment'));
    }

    public function cancelBooking($id)
    {
        $booking = Booking::where('id', $id)
            ->where('customer_id', Auth::id())
            ->whereIn('status', ['pending', 'approved', 'paid', 'confirmed', 'rescheduled', 'pending_payment'])
            ->firstOrFail();

        $oldStatus = $booking->status;
        $booking->status = 'cancelled';
        $booking->save();

        if (in_array($oldStatus, ['paid', 'approved', 'confirmed', 'rescheduled'])) {
            $payment = \App\Models\Payment::where('payable_type', Booking::class)
                ->where('payable_id', $booking->id)
                ->where('payment_status', 'completed')
                ->first();

            if ($payment) {
                $payment->payment_status = 'refunded';
                $payment->save();

                \App\Models\Refund::create([
                    'payment_id' => $payment->id,
                    'booking_id' => $booking->id,
                    'amount' => $payment->amount,
                    'refund_status' => 'completed',
                    'refund_transaction_id' => 'REF_' . strtoupper(\Illuminate\Support\Str::random(12)),
                    'reason' => 'Booking cancelled by Customer',
                ]);

                $booking->status = 'refunded';
                $booking->save();

                $earning = \App\Models\PartnerEarning::where('booking_id', $booking->id)->first();
                if ($earning) {
                    $earning->status = 'cancelled';
                    $earning->save();
                }
            }
        }

        DB::table('notifications')->insert([
            'id'             => \Illuminate\Support\Str::uuid(),
            'type'           => 'App\Notifications\BookingCancelled',
            'notifiable_type'=> 'App\Models\User',
            'notifiable_id'  => $booking->partner_id,
            'data'           => json_encode([
                'message'      => 'Booking cancelled by ' . Auth::user()->name,
                'booking_date' => $booking->booking_date->format('Y-m-d'),
            ]),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return back()->with('success', 'Booking cancelled successfully.' . ($booking->status === 'refunded' ? ' Refund has been initiated.' : ''));
    }

    public function submitReview(Request $request, $bookingId)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::where('id', $bookingId)
            ->where('customer_id', Auth::id())
            ->where('status', 'completed')
            ->firstOrFail();

        if (Review::where('booking_id', $bookingId)->exists()) {
            return back()->withErrors(['error' => 'Already reviewed this booking.']);
        }

        Review::create([
            'booking_id'  => $booking->id,
            'customer_id' => Auth::id(),
            'partner_id'  => $booking->partner_id,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
        ]);

        $avgRating = Review::where('partner_id', $booking->partner_id)->avg('rating');
        $pp = \App\Models\CompanionProfile::where('user_id', $booking->partner_id)->first();
        if ($pp) { $pp->rating = round($avgRating, 2); $pp->save(); }

        return back()->with('success', 'Review submitted successfully!');
    }
}

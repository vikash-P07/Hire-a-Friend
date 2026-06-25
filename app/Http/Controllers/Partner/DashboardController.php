<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\City;
use App\Models\Service;
use App\Models\Booking;
use App\Models\CompanionProfile;
use App\Models\DocumentVerification;
use App\Models\Availability;
use App\Models\PartnerEarning;
use App\Models\WithdrawalRequest;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ensure profile and verification exist
        $profile = $user->companionProfile ?? \App\Models\CompanionProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'bio' => 'No bio description provided yet.',
                'hourly_rate' => 0.00,
                'rating' => 0.00,
                'kyc_status' => 'pending',
                'experience_years' => 0,
            ]
        );

        $verification = $user->documentVerification ?? \App\Models\DocumentVerification::firstOrCreate(
            ['user_id' => $user->id],
            [
                'aadhaar_status' => 'pending',
                'pan_status' => 'pending',
                'selfie_status' => 'pending',
            ]
        );

        $bookings = Booking::where('partner_id', $user->id)
            ->with('customer')
            ->latest()
            ->get();

        // Calculate earnings
        $totalEarnings = PartnerEarning::where('partner_id', $user->id)->sum('net_amount');
        $clearedEarnings = PartnerEarning::where('partner_id', $user->id)->where('status', 'cleared')->sum('net_amount');
        $withdrawn = WithdrawalRequest::where('partner_id', $user->id)->whereIn('status', ['pending', 'approved'])->sum('amount');
        $withdrawable = $clearedEarnings - $withdrawn;
        
        $stats = [
            'total_bookings' => $bookings->count(),
            'pending_bookings' => $bookings->where('status', 'pending')->count(),
            'upcoming_bookings' => $bookings->where('status', 'approved')->count(),
            'completed_bookings' => $bookings->where('status', 'completed')->count(),
            'total_earnings' => $totalEarnings,
            'withdrawable' => max(0, $withdrawable),
            'rating' => $profile->rating ?? 0.00,
            'kyc_status' => $profile->kyc_status ?? 'pending',
            'profile_views' => 124 + ($bookings->count() * 8) + rand(5, 15), // Simulated view count
        ];

        $latestReviews = $user->reviewsAsPartner()->with('customer')->latest()->take(3)->get();
        $upcomingBookings = $bookings->whereIn('status', ['approved', 'rescheduled'])->sortBy('booking_date')->take(5);
        
        $completionPercentage = $user->profileCompletionPercentage();

        return view('partner.dashboard', compact('user', 'profile', 'bookings', 'stats', 'latestReviews', 'upcomingBookings', 'completionPercentage'));
    }

    public function profile()
    {
        $user = Auth::user();
        $profile = $user->companionProfile;
        $verification = $user->documentVerification;
        $cities = City::where('is_active', true)->get();
        $services = Service::with('category')->get();
        $selectedServices = $user->services->pluck('id')->toArray();

        // Default Mon-Sun slot data for view
        $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $availabilities = $user->availabilities->keyBy('day');

        return view('partner.profile', compact('user', 'profile', 'verification', 'cities', 'services', 'selectedServices', 'daysOfWeek', 'availabilities'));
    }

    public function saveOnboarding(Request $request)
    {
        $user = Auth::user();
        $profile = $user->companionProfile ?? new CompanionProfile(['user_id' => $user->id]);

        $request->validate([
            // Step 1: Documents & Bank details
            'aadhaar_front' => 'required_without:existing_aadhaar_front|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'aadhaar_back' => 'required_without:existing_aadhaar_back|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'pan_card' => 'required_without:existing_pan_card|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'selfie' => 'required_without:existing_selfie|file|mimes:jpeg,png,jpg|max:5120',
            'bank_holder_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:255',
            'bank_ifsc' => 'required|string|max:20',
            'bank_name' => 'required|string|max:255',

            // Step 2: Profile setup
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'bio' => 'required|string|min:10',
            'hourly_rate' => 'required|numeric|min:0',
            'experience_years' => 'required|integer|min:0',
            'languages' => 'required|array',
            'interests' => 'required|array',
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // GPS Location Details
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',

            // Step 3: Availability
            'availabilities' => 'required|array',
        ]);

        // Upload documents
        $verification = $user->documentVerification ?? new DocumentVerification(['user_id' => $user->id]);

        if ($request->hasFile('aadhaar_front')) {
            $verification->aadhaar_front = $request->file('aadhaar_front')->store('kyc', 'public');
            $verification->aadhaar_status = 'pending';
        }
        if ($request->hasFile('aadhaar_back')) {
            $verification->aadhaar_back = $request->file('aadhaar_back')->store('kyc', 'public');
            $verification->aadhaar_status = 'pending';
        }
        if ($request->hasFile('pan_card')) {
            $verification->pan_card = $request->file('pan_card')->store('kyc', 'public');
            $verification->pan_status = 'pending';
        }
        if ($request->hasFile('selfie')) {
            $verification->selfie = $request->file('selfie')->store('kyc', 'public');
            $verification->selfie_status = 'pending';
        }
        $verification->save();

        // Update User Model Info
        $user->name = $request->name;
        $user->city_id = $request->city_id;
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }
        $user->save();

        // Update Profile Info
        $profile->user_id = $user->id;
        $profile->bio = $request->bio;
        $profile->hourly_rate = $request->hourly_rate;
        $profile->experience_years = $request->experience_years;
        $profile->languages = $request->languages;
        $profile->interests = $request->interests;
        $profile->bank_holder_name = $request->bank_holder_name;
        $profile->bank_account_number = $request->bank_account_number;
        $profile->bank_ifsc = $request->bank_ifsc;
        $profile->bank_name = $request->bank_name;
        
        $profile->country = $request->country;
        $profile->state = $request->state;
        $profile->city = $request->city;
        $profile->area = $request->area;
        $profile->latitude = $request->latitude;
        $profile->longitude = $request->longitude;

        $profile->kyc_status = 'pending'; // KYC status is pending review
        $profile->save();

        // Sync Services
        $user->services()->sync($request->services);

        // Sync Availability Schedule
        $user->availabilities()->delete();
        foreach ($request->availabilities as $day => $slot) {
            if (isset($slot['is_available']) && $slot['is_available'] == 1) {
                Availability::create([
                    'user_id' => $user->id,
                    'day' => $day,
                    'start_time' => $slot['start_time'] ?? '09:00:00',
                    'end_time' => $slot['end_time'] ?? '17:00:00',
                    'is_available' => true,
                ]);
            }
        }

        return redirect()->route('partner.dashboard')->with('success', 'Onboarding setup updated successfully. Your profile is undergoing verification.');
    }

    public function updateProfile(Request $request)
    {
        // For already onboarded partners, allow updates via profile
        return $this->saveOnboarding($request);
    }

    public function bookings(Request $request)
    {
        $user = Auth::user();
        if (!$user->isOnboarded()) {
            return redirect()->route('partner.profile');
        }

        $query = Booking::where('partner_id', $user->id)->with('customer');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->get();

        return view('partner.bookings', compact('user', 'bookings'));
    }

    public function messages()
    {
        $user = Auth::user();
        if (!$user->isOnboarded()) {
            return redirect()->route('partner.profile');
        }
        
        $bookedCustomers = Booking::where('partner_id', $user->id)
            ->with('customer')
            ->whereIn('status', ['approved', 'ongoing', 'completed', 'paid', 'confirmed', 'rescheduled'])
            ->latest()
            ->get()
            ->unique('customer_id');

        return view('partner.messages', compact('user', 'bookedCustomers'));
    }

    public function handleBooking(Request $request, $id, $action)
    {
        $booking = Booking::where('id', $id)
            ->where('partner_id', Auth::id())
            ->firstOrFail();

        if ($action === 'accept') {
            if (!in_array($booking->status, ['pending', 'paid', 'rescheduled'])) {
                return back()->withErrors(['error' => 'Booking request cannot be accepted.']);
            }
            $booking->status = 'approved';
            $msg = 'Your booking request was approved by ' . Auth::user()->name;
            $notifyType = 'App\Notifications\BookingApproved';
        } elseif ($action === 'reject') {
            if (!in_array($booking->status, ['pending', 'paid', 'rescheduled'])) {
                return back()->withErrors(['error' => 'Booking request cannot be rejected.']);
            }
            
            $oldStatus = $booking->status;
            $booking->status = 'rejected';
            $booking->save();
            
            if (in_array($oldStatus, ['paid', 'rescheduled'])) {
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
                        'reason' => 'Booking rejected by Companion Partner',
                    ]);
                    
                    $booking->status = 'refunded';
                    $booking->save();
                    
                    $earning = PartnerEarning::where('booking_id', $booking->id)->first();
                    if ($earning) {
                        $earning->status = 'cancelled';
                        $earning->save();
                    }
                }
            }

            $msg = 'Your booking request was rejected by ' . Auth::user()->name;
            $notifyType = 'App\Notifications\BookingRejected';
        } elseif ($action === 'complete') {
            if ($booking->status !== 'approved') {
                return back()->withErrors(['error' => 'Booking can only be marked completed if approved.']);
            }
            $booking->status = 'completed';
            $msg = 'Your booking session with ' . Auth::user()->name . ' was marked as completed. Please review!';
            $notifyType = 'App\Notifications\BookingCompleted';

            $earning = PartnerEarning::where('booking_id', $booking->id)->first();
            if ($earning) {
                $earning->status = 'cleared';
                $earning->save();
            } else {
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

                PartnerEarning::create([
                    'booking_id' => $booking->id,
                    'partner_id' => Auth::id(),
                    'total_amount' => $totalAmount,
                    'commission_amount' => $commissionAmount,
                    'net_amount' => $netAmount,
                    'status' => 'cleared',
                ]);
            }
        } else {
            return back()->withErrors(['error' => 'Invalid action.']);
        }

        $booking->save();

        // Send System Notification to Customer
        DB::table('notifications')->insert([
            'id' => Str::uuid(),
            'type' => $notifyType,
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $booking->customer_id,
            'data' => json_encode([
                'booking_id' => $booking->id,
                'message' => $msg,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Booking updated successfully.');
    }

    public function rescheduleBooking(Request $request, $id)
    {
        $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
        ]);

        $booking = Booking::where('id', $id)
            ->where('partner_id', Auth::id())
            ->firstOrFail();

        $booking->booking_date = $request->booking_date;
        $booking->start_time = date('H:i:s', strtotime($request->start_time));
        $booking->status = 'rescheduled';
        $booking->save();

        // Notify customer
        DB::table('notifications')->insert([
            'id' => Str::uuid(),
            'type' => 'App\Notifications\BookingRescheduled',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $booking->customer_id,
            'data' => json_encode([
                'booking_id' => $booking->id,
                'message' => 'Your booking with companion ' . Auth::user()->name . ' has been rescheduled to ' . Carbon::parse($request->booking_date)->format('M d, Y') . ' at ' . date('h:i A', strtotime($request->start_time)),
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Booking rescheduled successfully. The customer has been notified.');
    }

    public function earnings(Request $request)
    {
        $user = Auth::user();
        if (!$user->isOnboarded()) {
            return redirect()->route('partner.profile');
        }

        $earnings = PartnerEarning::where('partner_id', $user->id)->with('booking.customer')->latest()->get();
        $withdrawals = WithdrawalRequest::where('partner_id', $user->id)->latest()->get();

        $clearedRevenue = $earnings->where('status', 'cleared')->sum('net_amount');
        $withdrawn = $withdrawals->where('status', 'approved')->sum('amount');
        $pendingPayouts = $withdrawals->where('status', 'pending')->sum('amount');
        $withdrawable = $clearedRevenue - ($withdrawn + $pendingPayouts);

        $stats = [
            'total_earnings' => $earnings->whereIn('status', ['cleared', 'pending'])->sum('net_amount'),
            'pending_earnings' => $earnings->where('status', 'pending')->sum('net_amount'),
            'withdrawable' => max(0, $withdrawable),
            'withdrawn' => $withdrawn,
        ];

        // 1. Chart Data: Monthly revenue (last 6 months)
        $months = [];
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $monthlyRevenue[] = PartnerEarning::where('partner_id', $user->id)
                ->where('status', 'cleared')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('net_amount');
        }

        // 2. Chart Data: Earnings Statuses
        $statusCounts = [
            'Cleared' => $earnings->where('status', 'cleared')->count(),
            'Pending' => $earnings->where('status', 'pending')->count(),
            'Cancelled' => $earnings->where('status', 'cancelled')->count(),
        ];

        return view('partner.earnings', compact('user', 'earnings', 'withdrawals', 'stats', 'months', 'monthlyRevenue', 'statusCounts'));
    }

    public function requestWithdrawal(Request $request)
    {
        $user = Auth::user();
        $profile = $user->companionProfile;

        $request->validate([
            'amount' => 'required|numeric|min:500',
            'payout_method' => 'required|in:bank_transfer,upi',
            'upi_id' => 'required_if:payout_method,upi|nullable|string',
        ]);

        // Calculate withdrawable balance
        $clearedEarnings = PartnerEarning::where('partner_id', $user->id)->where('status', 'cleared')->sum('net_amount');
        $withdrawn = WithdrawalRequest::where('partner_id', $user->id)->whereIn('status', ['pending', 'approved'])->sum('amount');
        $withdrawable = $clearedEarnings - $withdrawn;

        if ($request->amount > $withdrawable) {
            return back()->withErrors(['amount' => 'Insufficient withdrawable balance. You can withdraw up to ₹' . number_format($withdrawable, 2)]);
        }

        WithdrawalRequest::create([
            'partner_id' => $user->id,
            'amount' => $request->amount,
            'status' => 'pending',
            'payout_method' => $request->payout_method,
            'upi_id' => $request->payout_method === 'upi' ? $request->upi_id : null,
            'bank_holder_name' => $request->payout_method === 'bank_transfer' ? $profile->bank_holder_name : null,
            'bank_account_number' => $request->payout_method === 'bank_transfer' ? $profile->bank_account_number : null,
            'bank_ifsc' => $request->payout_method === 'bank_transfer' ? $profile->bank_ifsc : null,
            'bank_name' => $request->payout_method === 'bank_transfer' ? $profile->bank_name : null,
        ]);

        return back()->with('success', 'Withdrawal request of ₹' . number_format($request->amount, 2) . ' submitted successfully.');
    }

    public function availability()
    {
        $user = Auth::user();
        if (!$user->isOnboarded()) {
            return redirect()->route('partner.profile');
        }

        $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $availabilities = $user->availabilities->keyBy('day');
        $profile = $user->companionProfile;

        return view('partner.availability', compact('user', 'daysOfWeek', 'availabilities', 'profile'));
    }

    public function updateAvailability(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'availabilities' => 'required|array',
        ]);

        $user->availabilities()->delete();

        foreach ($request->availabilities as $day => $slot) {
            if (isset($slot['is_available']) && $slot['is_available'] == 1) {
                Availability::create([
                    'user_id' => $user->id,
                    'day' => $day,
                    'start_time' => $slot['start_time'] ?? '09:00:00',
                    'end_time' => $slot['end_time'] ?? '17:00:00',
                    'is_available' => true,
                ]);
            }
        }

        return back()->with('success', 'Availability schedule updated successfully.');
    }

    public function toggleVacationMode(Request $request)
    {
        $user = Auth::user();
        $profile = $user->companionProfile;

        $profile->vacation_mode = !$profile->vacation_mode;
        $profile->save();

        $status = $profile->vacation_mode ? 'enabled' : 'disabled';
        return back()->with('success', "Vacation mode {$status} successfully.");
    }

    public function analytics()
    {
        $user = Auth::user();
        if (!$user->isOnboarded()) {
            return redirect()->route('partner.profile');
        }

        // Stats summary
        $bookings = Booking::where('partner_id', $user->id)->get();
        $earnings = PartnerEarning::where('partner_id', $user->id)->get();

        $stats = [
            'views' => 124 + ($bookings->count() * 8) + rand(5, 15),
            'bookings_count' => $bookings->count(),
            'completed_count' => $bookings->where('status', 'completed')->count(),
            'total_revenue' => $earnings->sum('net_amount'),
            'average_rating' => $user->companionProfile->rating ?? 0.00,
        ];

        // 1. Chart Data: Monthly revenue (last 6 months)
        $months = [];
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $monthlyRevenue[] = PartnerEarning::where('partner_id', $user->id)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('net_amount');
        }

        // 2. Chart Data: Bookings by status
        $statusCounts = [
            'Pending' => $bookings->where('status', 'pending')->count(),
            'Accepted' => $bookings->where('status', 'approved')->count(),
            'Completed' => $bookings->where('status', 'completed')->count(),
            'Cancelled' => $bookings->where('status', 'cancelled')->count(),
        ];

        return view('partner.analytics', compact('user', 'stats', 'months', 'monthlyRevenue', 'statusCounts'));
    }

    public function subscription()
    {
        $user = Auth::user();
        if (!$user->isOnboarded()) {
            return redirect()->route('partner.profile');
        }

        $plans = Plan::where('is_active', true)->get();
        $activeSubscription = $user->activeSubscription;

        return view('partner.subscription', compact('user', 'plans', 'activeSubscription'));
    }

    public function subscribe(Request $request, $planId)
    {
        $user = Auth::user();
        $plan = Plan::findOrFail($planId);

        // Cancel previous active subscriptions
        Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'expired', 'ends_at' => now()]);

        // Create subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(), // Standard 30 days subscription duration
        ]);

        // Create transaction details
        Payment::create([
            'user_id' => $user->id,
            'amount' => $plan->price,
            'payment_method' => 'card',
            'payment_status' => 'completed',
            'transaction_id' => 'SUB_' . strtoupper(Str::random(12)),
            'payable_type' => Subscription::class,
            'payable_id' => $subscription->id,
        ]);

        // Make Featured status true if Plan is VIP or Pro
        $profile = $user->companionProfile;
        if ($plan->slug === 'pro-partner' || Str::contains(strtolower($plan->name), 'vip') || Str::contains(strtolower($plan->name), 'premium')) {
            $profile->is_featured = true;
        } else {
            $profile->is_featured = false;
        }
        $profile->save();

        return redirect()->route('partner.subscription')->with('success', 'Subscribed to plan ' . $plan->name . ' successfully!');
    }
}

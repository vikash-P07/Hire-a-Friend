<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\City;
use App\Models\Category;
use App\Models\Service;
use App\Models\Review;
use Illuminate\Http\Request;

class CompanionController extends Controller
{
    public function index(Request $request)
    {
        $cities = City::where('is_active', true)->get();
        $categories = Category::all();

        $userLocation = session('user_location');
        $showingNearbyFallback = false;

        $normCity = $userLocation && !empty($userLocation['city']) && strtolower(trim($userLocation['city'])) !== 'all locations' ? strtolower(trim($userLocation['city'])) : null;

        if (!$request->filled('city_id') && $normCity) {
            $exactCityCount = User::where('role', 'partner')
                ->where('users.is_active', true)
                ->whereHas('companionProfile', function ($q) use ($normCity) {
                    $q->where('kyc_status', 'approved')
                      ->whereRaw('TRIM(LOWER(city)) = ?', [$normCity]);
                })->count();

            if ($exactCityCount === 0) {
                $showingNearbyFallback = true;
            }
        }

        $query = User::where('role', 'partner')
            ->where('is_active', true)
            ->whereHas('companionProfile', function ($q) {
                $q->where('kyc_status', 'approved');
            })
            ->join('companion_profiles', 'users.id', '=', 'companion_profiles.user_id');

        // Apply Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('companion_profiles.bio', 'like', "%{$search}%");
            });
        }

        // Apply City/Location Filter
        $hasDistance = false;
        if ($request->filled('city_id')) {
            $query->where('users.city_id', $request->input('city_id'));
            $query->select('users.*', 'companion_profiles.hourly_rate', 'companion_profiles.rating', 'companion_profiles.experience_years', 'companion_profiles.bio', 'companion_profiles.is_featured');
        } elseif ($normCity) {
            if (isset($userLocation['latitude'], $userLocation['longitude']) && $userLocation['latitude'] && $userLocation['longitude']) {
                $lat = $userLocation['latitude'];
                $lng = $userLocation['longitude'];
                
                $query->selectRaw("users.*, companion_profiles.hourly_rate, companion_profiles.rating, companion_profiles.experience_years, companion_profiles.bio, companion_profiles.is_featured,
                    (6371 * acos(cos(radians(?)) * cos(radians(companion_profiles.latitude)) * cos(radians(companion_profiles.longitude) - radians(?)) + sin(radians(?)) * sin(radians(companion_profiles.latitude)))) AS distance",
                    [$lat, $lng, $lat]
                );
                $hasDistance = true;
            } else {
                $query->select('users.*', 'companion_profiles.hourly_rate', 'companion_profiles.rating', 'companion_profiles.experience_years', 'companion_profiles.bio', 'companion_profiles.is_featured');
                $query->orderByRaw("CASE 
                    WHEN TRIM(LOWER(companion_profiles.city)) = ? THEN 0 
                    WHEN TRIM(LOWER(companion_profiles.state)) = ? THEN 1 
                    ELSE 2 
                END", [$normCity, $userLocation['state'] ?? 'Madhya Pradesh']);
            }
        } else {
            $query->select('users.*', 'companion_profiles.hourly_rate', 'companion_profiles.rating', 'companion_profiles.experience_years', 'companion_profiles.bio', 'companion_profiles.is_featured');
        }

        // Apply Category Filter
        if ($request->filled('category_id')) {
            $categoryId = $request->input('category_id');
            $query->whereHas('services', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        // Apply Gender Filter
        if ($request->filled('gender')) {
            $query->where('users.gender', $request->input('gender'));
        }

        // Apply Price Range Filter
        if ($request->filled('min_price')) {
            $query->where('companion_profiles.hourly_rate', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('companion_profiles.hourly_rate', '<=', $request->input('max_price'));
        }

        // Apply Sorting
        $sort = $request->input('sort', 'rating_desc');
        
        // Prioritize VIP/Premium (is_featured = true) companions first!
        $query->orderBy('companion_profiles.is_featured', 'desc');

        if ($hasDistance) {
            $query->orderBy('distance', 'asc');
        }

        if ($sort === 'rating_desc') {
            $query->orderBy('companion_profiles.rating', 'desc');
        } elseif ($sort === 'price_asc') {
            $query->orderBy('companion_profiles.hourly_rate', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('companion_profiles.hourly_rate', 'desc');
        }

        $companions = $query->with(['city', 'services.category', 'partnerProfile'])->paginate(9);

        return view('companions.index', compact('companions', 'cities', 'categories', 'showingNearbyFallback'));
    }

    public function show($id)
    {
        $companion = User::where('role', 'partner')
            ->where('is_active', true)
            ->whereHas('companionProfile', function ($q) {
                $q->where('kyc_status', 'approved');
            })
            ->with(['city', 'companionProfile', 'services.category'])
            ->findOrFail($id);

        $reviews = Review::where('partner_id', $companion->id)
            ->with('customer')
            ->latest()
            ->get();

        return view('companions.show', compact('companion', 'reviews'));
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $coupon = \App\Models\Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Invalid coupon code.']);
        }

        if (!$coupon->is_active) {
            return response()->json(['valid' => false, 'message' => 'This coupon is no longer active.']);
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return response()->json(['valid' => false, 'message' => 'This coupon has expired.']);
        }

        if ($coupon->uses_count >= $coupon->max_uses) {
            return response()->json(['valid' => false, 'message' => 'This coupon has reached its usage limit.']);
        }

        $subtotal = $request->subtotal;
        $discount = 0;

        if ($coupon->type === 'percentage') {
            $discount = ($subtotal * $coupon->value) / 100;
        } else {
            $discount = $coupon->value;
        }

        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        return response()->json([
            'valid' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => round($discount, 2),
            'final_amount' => round($subtotal - $discount, 2),
            'coupon_id' => $coupon->id
        ]);
    }
}

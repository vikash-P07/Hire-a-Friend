<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\City;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\CompanionProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $cities = City::where('is_active', true)->get();
        $categories = Category::all();

        $userLocation = session('user_location');

        // Check if we are showing nearby fallback companions
        $showingNearbyFallback = false;
        $detectedCity = $userLocation['city'] ?? null;
        $detectedState = $userLocation['state'] ?? null;

        if ($detectedCity && strtolower(trim($detectedCity)) !== 'all locations') {
            $normCity = strtolower(trim($detectedCity));
            
            // Debug variables
            $totalCityCompanions = User::where('role', 'partner')
                ->whereHas('companionProfile', function ($q) use ($normCity) {
                    $q->whereRaw('TRIM(LOWER(city)) = ?', [$normCity]);
                })->count();

            $exactCityCount = User::where('role', 'partner')
                ->where('users.is_active', true)
                ->whereHas('companionProfile', function ($query) use ($normCity) {
                    $query->where('kyc_status', 'approved')
                          ->whereRaw('TRIM(LOWER(city)) = ?', [$normCity]);
                })->count();

            // Log Debug details as requested
            \Illuminate\Support\Facades\Log::info("Location Debug Info:", [
                'Detected City' => $detectedCity,
                'Detected State' => $detectedState,
                'Total City Companions Found' => $totalCityCompanions,
                'Total Active Approved Companions Found' => $exactCityCount
            ]);

            if ($exactCityCount === 0) {
                $showingNearbyFallback = true;
            }
        }

        // Fetch location-aware companions
        $recommendedCompanions = $this->getFilteredCompanions('recommended', $userLocation);
        $topCompanions = $this->getFilteredCompanions('top', $userLocation);
        $mosaicCompanions = $this->getFilteredCompanions('mosaic', null);

        return view('home', compact(
            'cities',
            'categories',
            'recommendedCompanions',
            'topCompanions',
            'mosaicCompanions',
            'showingNearbyFallback'
        ));
    }

    public function selectLocation(Request $request)
    {
        $request->validate([
            'city_id' => 'required'
        ]);

        if ($request->city_id === 'all') {
            session([
                'user_location' => [
                    'city' => 'All Locations',
                    'state' => '',
                    'country' => 'India',
                    'area' => '',
                    'latitude' => null,
                    'longitude' => null,
                    'manual' => true,
                    'city_id' => 'all'
                ]
            ]);
            return response()->json(['success' => true]);
        }

        $city = City::with('state')->find($request->city_id);
        if ($city) {
            session([
                'user_location' => [
                    'city' => $city->name,
                    'state' => $city->state->name ?? 'Madhya Pradesh',
                    'country' => 'India',
                    'area' => '',
                    'latitude' => null,
                    'longitude' => null,
                    'manual' => true,
                    'city_id' => $city->id
                ]
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function detectLocation(Request $request)
    {
        $request->validate([
            'city' => 'required|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'area' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        $city = $request->city;
        $dbCity = City::where('name', 'like', "%{$city}%")->first();
        $cityId = $dbCity ? $dbCity->id : null;

        session([
            'user_location' => [
                'city' => $city,
                'state' => $request->state ?? 'Madhya Pradesh',
                'country' => $request->country ?? 'India',
                'area' => $request->area ?? '',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'manual' => false,
                'city_id' => $cityId
            ]
        ]);

        \Illuminate\Support\Facades\Log::info("Location system - session location saved: " . json_encode(session('user_location')));

        return response()->json(['success' => true]);
    }

    public function detectIpLocation(Request $request)
    {
        \Illuminate\Support\Facades\Log::info("Location system - falling back to IP location detection for IP: " . request()->ip());
        $this->autoDetectIpLocation();
        return response()->json(['success' => true]);
    }

    public function logLocationError(Request $request)
    {
        $type = $request->input('type');
        $message = $request->input('message');
        \Illuminate\Support\Facades\Log::warning("Location system - error: Permission denied or location fetch failed. Type: {$type}, Message: {$message}");
        return response()->json(['success' => true]);
    }

    public function logLocationSuccess(Request $request)
    {
        $type = $request->input('type');
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');
        \Illuminate\Support\Facades\Log::info("Location system - success: Permission granted. Coordinates received: Lat: {$lat}, Lng: {$lng}. Type: {$type}");
        return response()->json(['success' => true]);
    }

    public function logGeocoding(Request $request)
    {
        $res = $request->input('response');
        \Illuminate\Support\Facades\Log::info("Location system - reverse geocoding response: " . json_encode($res));
        return response()->json(['success' => true]);
    }

    private function autoDetectIpLocation()
    {
        $ip = request()->ip();
        if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            $city = 'Bhopal';
            $state = 'Madhya Pradesh';
            $country = 'India';
            $lat = 23.2599;
            $lng = 77.4126;
            \Illuminate\Support\Facades\Log::info("Location system - local IP detected, using Bhopal default.");
        } else {
            try {
                $response = @file_get_contents("http://ip-api.com/json/{$ip}");
                $data = json_decode($response, true);
                if ($data && $data['status'] === 'success') {
                    $city = $data['city'] ?? 'Bhopal';
                    $state = $data['regionName'] ?? 'Madhya Pradesh';
                    $country = $data['country'] ?? 'India';
                    $lat = $data['lat'] ?? 23.2599;
                    $lng = $data['lon'] ?? 77.4126;
                    \Illuminate\Support\Facades\Log::info("Location system - success: IP Location detected. Details: City: {$city}, State: {$state}, Country: {$country}, Lat: {$lat}, Lng: {$lng}");
                } else {
                    \Illuminate\Support\Facades\Log::warning("Location system - IP Location API status not success. Falling back to Bhopal.");
                    $city = 'Bhopal'; $state = 'Madhya Pradesh'; $country = 'India'; $lat = 23.2599; $lng = 77.4126;
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Location system - error: IP Location fetch failed. Message: " . $e->getMessage() . ". Falling back to Bhopal.");
                $city = 'Bhopal'; $state = 'Madhya Pradesh'; $country = 'India'; $lat = 23.2599; $lng = 77.4126;
            }
        }

        $dbCity = City::where('name', 'like', "%{$city}%")->first();
        $cityId = $dbCity ? $dbCity->id : null;

        session([
            'user_location' => [
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'area' => '',
                'latitude' => $lat,
                'longitude' => $lng,
                'manual' => false,
                'city_id' => $cityId
            ]
        ]);

        \Illuminate\Support\Facades\Log::info("Location system - session location saved: " . json_encode(session('user_location')));
    }

    private function getFilteredCompanions($type, $userLocation)
    {
        $query = User::where('role', 'partner')
            ->where('users.is_active', true)
            ->whereHas('companionProfile', function ($q) {
                $q->where('kyc_status', 'approved');
            })
            ->with(['companionProfile', 'city', 'bookingsAsPartner', 'activeSubscription']);

        $normCity = $userLocation && !empty($userLocation['city']) && strtolower(trim($userLocation['city'])) !== 'all locations' ? strtolower(trim($userLocation['city'])) : null;

        if ($userLocation && strtolower(trim($userLocation['city'] ?? '')) === 'all locations') {
            $userLocation = null;
        }

        $exactCityCount = 0;
        if ($normCity) {
            $exactCityCount = User::where('role', 'partner')
                ->where('users.is_active', true)
                ->whereHas('companionProfile', function ($query) use ($normCity) {
                    $query->where('kyc_status', 'approved')
                          ->whereRaw('TRIM(LOWER(city)) = ?', [$normCity]);
                })->count();
        }

        // We do NOT add exact city filter here, so companions from nearby cities and the same state are retrieved and sorted by proximity priority!

        if ($type === 'recommended') {
            $query->whereHas('companionProfile', function ($q) {
                $q->where('is_recommended', true)->where('is_recommended_visible', true);
            });
        } elseif ($type === 'top') {
            $query->whereHas('companionProfile', function ($q) {
                $q->where('is_top_profile', true)->where('is_top_profile_visible', true);
            });
        }

        $companions = $query->get();

        if ($userLocation) {
            $userCity = $userLocation['city'] ?? null;
            $userArea = $userLocation['area'] ?? null;
            $userState = $userLocation['state'] ?? null;
            $userLat = $userLocation['latitude'] ?? null;
            $userLng = $userLocation['longitude'] ?? null;

            $companions = $companions->map(function ($companion) use ($userCity, $userArea, $userState, $userLat, $userLng) {
                $profile = $companion->companionProfile;
                
                $distance = null;
                if ($userLat && $userLng && $profile->latitude && $profile->longitude) {
                    $distance = $this->calculateDistance($userLat, $userLng, $profile->latitude, $profile->longitude);
                }

                $priority = 5;
                if ($profile->city && strtolower(trim($profile->city)) === strtolower(trim($userCity))) {
                    if ($userArea && $profile->area && strtolower(trim($profile->area)) === strtolower(trim($userArea))) {
                        $priority = 1;
                    } else {
                        $priority = 2;
                    }
                } elseif ($distance !== null && $distance < 100) {
                    $priority = 3;
                } elseif ($profile->state && strtolower(trim($profile->state)) === strtolower(trim($userState))) {
                    $priority = 4;
                }

                $companion->distance = $distance;
                $companion->priority = $priority;
                $companion->rating_score = $profile->rating ?? 0;
                $companion->bookings_count = $companion->bookingsAsPartner->where('status', 'completed')->count();
                
                $sub = $companion->activeSubscription;
                $companion->sub_score = 0;
                if ($sub && $sub->plan) {
                    $companion->sub_score = $sub->plan->price > 0 ? 2 : 1;
                }

                return $companion;
            });

            $companions = $companions->sort(function ($a, $b) {
                if ($a->priority !== $b->priority) {
                    return $a->priority <=> $b->priority;
                }
                if ($a->distance !== null && $b->distance !== null) {
                    if (abs($a->distance - $b->distance) > 0.1) {
                        return $a->distance <=> $b->distance;
                    }
                }
                if ($a->sub_score !== $b->sub_score) {
                    return $b->sub_score <=> $a->sub_score;
                }
                if (abs($a->rating_score - $b->rating_score) > 0.05) {
                    return $b->rating_score <=> $a->rating_score;
                }
                return $b->bookings_count <=> $a->bookings_count;
            });
        }

        $sorted = $companions->values();

        if ($type === 'mosaic') {
            return $sorted->take(21);
        }

        return $sorted;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos(min(max($dist, -1.0), 1.0));
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return $miles * 1.609344;
    }

    public function viewCmsPage($slug)
    {
        $page = CmsPage::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('cms', compact('page'));
    }
}
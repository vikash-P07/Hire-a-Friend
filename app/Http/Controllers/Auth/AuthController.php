<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\City;
use App\Models\CompanionProfile;
use App\Models\DocumentVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();
            
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been deactivated.']);
            }

            $request->session()->regenerate();
            
            // Set initial active role in session
            $user->getActiveRole();

            return $this->redirectUser($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        $cities = City::all();
        return view('auth.register', compact('cities'));
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:customer,partner',
            'phone' => 'nullable|string',
            'gender' => 'nullable|in:male,female,other',
            'city_id' => 'required|exists:cities,id',
        ];

        $validated = $request->validate($rules);

        // Create User
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'city_id' => $validated['city_id'],
            'is_active' => true,
        ]);

        if ($validated['role'] === 'partner') {

            // Create Companion Profile with default/pending values
            CompanionProfile::create([
                'user_id' => $user->id,
                'bio' => 'No bio description provided yet.',
                'hourly_rate' => 0.00,
                'rating' => 0.00,
                'kyc_status' => 'pending',
                'experience_years' => 0,
            ]);

            // Create initial placeholder document
            DocumentVerification::create([
                'user_id' => $user->id,
                'aadhaar_front' => null,
                'aadhaar_back' => null,
                'pan_card' => null,
                'selfie' => null,
                'aadhaar_status' => 'pending',
                'pan_status' => 'pending',
                'selfie_status' => 'pending',
            ]);
        }

        Auth::login($user);

        // Set active role session
        session(['active_role' => $validated['role']]);

        return $this->redirectUser($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        // Simulating email sent
        return back()->with('status', 'We have emailed your password reset link! (Simulation: Check logs or database)');
    }

    /**
     * Firebase Google Login — verifies ID token via Firebase REST API.
     * Supports local development (SSL bypass) and production.
     */
    public function googleFirebaseLogin(Request $request)
    {
        $request->validate([
            'firebase_token' => 'required|string',
            'role'           => 'nullable|in:customer,partner',
        ]);

        try {
            $apiKey = config('services.firebase.api_key');
            $isLocal = app()->environment('local');

            // Build HTTP client — bypass SSL only on local dev (XAMPP cURL issue)
            $http = Http::withHeaders(['Content-Type' => 'application/json'])
                        ->timeout(15);

            if ($isLocal) {
                $http = $http->withoutVerifying();
            }

            // Verify Firebase ID token via Firebase Identity Toolkit REST API
            $response = $http->post(
                "https://identitytoolkit.googleapis.com/v1/accounts:lookup?key={$apiKey}",
                ['idToken' => $request->firebase_token]
            );

            if (!$response->successful()) {
                $errMsg = $response->json('error.message') ?? 'Invalid token';
                Log::warning('Firebase token invalid: ' . $errMsg);
                return response()->json([
                    'error' => 'Google sign-in token is invalid or expired. Please try again.'
                ], 401);
            }

            $users = $response->json('users');
            if (empty($users)) {
                return response()->json(['error' => 'No user data returned from Google.'], 400);
            }

            $userData = $users[0];
            $email   = $userData['email'] ?? null;
            $name    = $userData['displayName'] ?? 'Google User';
            $picture = $userData['photoUrl'] ?? null;

            if (!$email) {
                return response()->json([
                    'error' => 'Your Google account does not have a verified email. Please use email login.'
                ], 400);
            }

            // Find existing or create new user
            $user      = User::where('email', $email)->first();
            $isNewUser = false;

            if (!$user) {
                $isNewUser   = true;
                $defaultCity = City::first();
                $role        = $request->input('role', 'customer');

                $user = User::create([
                    'name'            => $name,
                    'email'           => $email,
                    'password'        => Hash::make(\Illuminate\Support\Str::random(40)),
                    'role'            => $role,
                    'profile_picture' => $picture,
                    'is_active'       => true,
                    'city_id'         => $defaultCity?->id,
                ]);

                if ($role === 'partner') {
                    CompanionProfile::create([
                        'user_id' => $user->id,
                        'bio' => 'No bio description provided yet.',
                        'hourly_rate' => 0.00,
                        'rating' => 0.00,
                        'kyc_status' => 'pending',
                        'experience_years' => 0,
                    ]);

                    DocumentVerification::create([
                        'user_id' => $user->id,
                        'aadhaar_front' => null,
                        'aadhaar_back' => null,
                        'pan_card' => null,
                        'selfie' => null,
                        'aadhaar_status' => 'pending',
                        'pan_status' => 'pending',
                        'selfie_status' => 'pending',
                    ]);
                }
            }

            if (!$user->is_active) {
                return response()->json([
                    'error' => 'Your account has been deactivated. Please contact support.'
                ], 403);
            }

            // Sync Google profile picture if not already set
            if ($picture && !$user->profile_picture) {
                $user->profile_picture = $picture;
                $user->save();
            }

            Auth::login($user, true);
            $request->session()->regenerate();

            $redirectUrl = match ($user->role) {
                'admin'   => route('admin.dashboard'),
                'partner' => route('partner.dashboard'),
                default   => route('customer.dashboard'),
            };

            return response()->json([
                'success'      => true,
                'redirect_url' => $redirectUrl,
                'is_new_user'  => $isNewUser,
                'name'         => $user->name,
            ]);

        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('Firebase HTTP error: ' . $e->getMessage());
            return response()->json(['error' => 'Could not reach Google servers. Check your internet connection.'], 503);
        } catch (\Exception $e) {
            Log::error('Firebase Google Login error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return response()->json(['error' => 'Authentication failed: ' . $e->getMessage()], 500);
        }
    }

    protected function redirectUser($user)
    {
        $role = $user->getActiveRole();
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'partner') {
            return redirect()->route('partner.dashboard');
        }
        return redirect()->route('customer.dashboard');
    }
}

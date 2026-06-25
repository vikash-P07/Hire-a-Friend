<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        } else if (env('APP_ENV') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Pagination\Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\View::composer(['layouts.app', 'layouts.customer', 'layouts.partner'], function ($view) {
            if (auth()->check() && !session()->has('user_location')) {
                $user = auth()->user();
                if ($user->role === 'partner') {
                    $profile = $user->companionProfile;
                    if ($profile && $profile->city) {
                        session([
                            'user_location' => [
                                'city' => $profile->city,
                                'state' => $profile->state ?? 'Madhya Pradesh',
                                'country' => $profile->country ?? 'India',
                                'area' => $profile->area ?? '',
                                'latitude' => $profile->latitude,
                                'longitude' => $profile->longitude,
                                'manual' => false,
                                'city_id' => $user->city_id
                            ]
                        ]);
                        \Illuminate\Support\Facades\Log::info("Location system - initialized session for authenticated companion: " . json_encode(session('user_location')));
                    }
                } else {
                    $city = $user->city;
                    if ($city) {
                        session([
                            'user_location' => [
                                'city' => $city->name,
                                'state' => $city->state->name ?? 'Madhya Pradesh',
                                'country' => 'India',
                                'area' => '',
                                'latitude' => null,
                                'longitude' => null,
                                'manual' => false,
                                'city_id' => $city->id
                            ]
                        ]);
                        \Illuminate\Support\Facades\Log::info("Location system - initialized session for authenticated user: " . json_encode(session('user_location')));
                    }
                }
            }

            $view->with('globalCities', \App\Models\City::all());
            $view->with('globalCategories', \App\Models\Category::all());
        });
    }
}

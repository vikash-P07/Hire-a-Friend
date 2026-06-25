<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $cityCoordinates = [
            'indore'   => [22.7196, 75.8577],
            'bhopal'   => [23.2599, 77.4126],
            'jabalpur' => [23.1815, 79.9864],
            'gwalior'  => [26.2183, 78.1828],
            'ujjain'   => [23.1760, 75.7885],
            'sagar'    => [23.8388, 78.7378],
            'dewas'    => [22.9676, 76.0534],
            'satna'    => [24.5774, 80.8322],
            'ratlam'   => [23.3315, 75.0367],
            'rewa'     => [24.5362, 81.3037],
        ];

        $profiles = DB::table('companion_profiles')->get();
        foreach ($profiles as $profile) {
            $user = DB::table('users')->where('id', $profile->user_id)->first();
            if ($user && $user->city_id) {
                $city = DB::table('cities')->where('id', $user->city_id)->first();
                if ($city) {
                    $cityName = $city->name;
                    $stateName = 'Madhya Pradesh';
                    if ($city->state_id) {
                        $state = DB::table('states')->where('id', $city->state_id)->first();
                        if ($state) {
                            $stateName = $state->name;
                        }
                    }

                    $slug = strtolower($cityName);
                    $coords = $cityCoordinates[$slug] ?? [23.2599, 77.4126];

                    DB::table('companion_profiles')
                        ->where('id', $profile->id)
                        ->update([
                            'country' => 'India',
                            'state' => $stateName,
                            'city' => $cityName,
                            'area' => 'MP Nagar',
                            'latitude' => $coords[0],
                            'longitude' => $coords[1],
                        ]);
                }
            }
        }
    }

    public function down(): void
    {
        DB::table('companion_profiles')->update([
            'country' => null,
            'state' => null,
            'city' => null,
            'area' => null,
            'latitude' => null,
            'longitude' => null,
        ]);
    }
};

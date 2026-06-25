<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id',
    'bio',
    'hourly_rate',
    'rating',
    'kyc_status',
    'kyc_notes',
    'experience_years',
    'is_featured',
    'bank_holder_name',
    'bank_account_number',
    'bank_ifsc',
    'bank_name',
    'languages',
    'interests',
    'vacation_mode',
    'availability_status',
    'is_recommended',
    'is_top_profile',
    'recommended_order',
    'top_profile_order',
    'is_recommended_visible',
    'is_top_profile_visible',
    'country',
    'state',
    'city',
    'area',
    'latitude',
    'longitude'
])]
class CompanionProfile extends Model
{
    use HasFactory;

    protected $table = 'companion_profiles';

    protected function casts(): array
    {
        return [
            'vacation_mode' => 'boolean',
            'availability_status' => 'boolean',
            'languages' => 'json',
            'interests' => 'json',
            'hourly_rate' => 'float',
            'rating' => 'float',
            'experience_years' => 'integer',
            'is_featured' => 'boolean',
            'is_recommended' => 'boolean',
            'is_top_profile' => 'boolean',
            'recommended_order' => 'integer',
            'top_profile_order' => 'integer',
            'is_recommended_visible' => 'boolean',
            'is_top_profile_visible' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

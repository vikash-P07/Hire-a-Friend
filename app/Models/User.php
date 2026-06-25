<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'phone', 'gender', 'profile_picture', 'is_active', 'city_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function companionProfile()
    {
        return $this->hasOne(CompanionProfile::class);
    }

    public function partnerProfile()
    {
        return $this->hasOne(CompanionProfile::class);
    }

    public function documentVerification()
    {
        return $this->hasOne(DocumentVerification::class);
    }

    public function partnerDocuments()
    {
        return $this->hasMany(DocumentVerification::class);
    }

    public function bookingsAsCustomer()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function bookingsAsPartner()
    {
        return $this->hasMany(Booking::class, 'partner_id');
    }

    public function reviewsAsCustomer()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function reviewsAsPartner()
    {
        return $this->hasMany(Review::class, 'partner_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'companion_services', 'user_id', 'service_id');
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class, 'user_id');
    }

    public function partnerEarnings()
    {
        return $this->hasMany(PartnerEarning::class, 'partner_id');
    }

    public function earnings()
    {
        return $this->hasMany(PartnerEarning::class, 'partner_id');
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class, 'partner_id');
    }

    public function withdrawals()
    {
        return $this->hasMany(WithdrawalRequest::class, 'partner_id');
    }

    public function commission()
    {
        return $this->hasOne(Commission::class, 'partner_id');
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class, 'partner_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'user_id');
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class, 'user_id')
            ->where('status', 'active')
            ->where('ends_at', '>', now());
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites', 'user_id', 'companion_id')->withTimestamps();
    }

    public function isOnboarded(): bool
    {
        if ($this->role !== 'partner') {
            return true;
        }

        $profile = $this->companionProfile;
        if (!$profile) {
            return false;
        }

        // 1. Step 1 check (Documents & Bank details)
        $verification = $this->documentVerification;
        if (!$verification || !$verification->aadhaar_front || !$verification->aadhaar_back || !$verification->pan_card || !$verification->selfie) {
            return false;
        }
        if (!$profile->bank_holder_name || !$profile->bank_account_number || !$profile->bank_ifsc || !$profile->bank_name) {
            return false;
        }

        // 2. Step 2 check (Profile bio, rates, services, languages, interests)
        if (!$profile->bio || $profile->hourly_rate <= 0 || !$this->services()->exists()) {
            return false;
        }
        if (!$profile->languages || !$profile->interests) {
            return false;
        }

        // 3. Step 3 check (Weekly schedule)
        if (!$this->availabilities()->exists()) {
            return false;
        }

        return true;
    }

    public function profileCompletionPercentage(): int
    {
        if ($this->role !== 'partner') {
            return 100;
        }

        $percentage = 0;

        // 1. Profile Picture (10%)
        if ($this->profile_picture) {
            $percentage += 10;
        }

        $verification = $this->documentVerification;
        if ($verification) {
            // 2. Aadhaar (15%)
            if ($verification->aadhaar_front && $verification->aadhaar_back) {
                $percentage += 15;
            }
            // 3. PAN (10%)
            if ($verification->pan_card) {
                $percentage += 10;
            }
            // 4. Selfie (10%)
            if ($verification->selfie) {
                $percentage += 10;
            }
        }

        $profile = $this->companionProfile;
        if ($profile) {
            // 5. Bank details (15%)
            if ($profile->bank_holder_name && $profile->bank_account_number && $profile->bank_ifsc && $profile->bank_name) {
                $percentage += 15;
            }
            // 6. Bio (10%)
            if ($profile->bio && $profile->bio !== 'No bio description provided yet.') {
                $percentage += 10;
            }
            // 7. Hourly rate (10%)
            if ($profile->hourly_rate > 0) {
                $percentage += 10;
            }
            // 8. Languages & Interests (5%)
            if ($profile->languages && $profile->interests) {
                $percentage += 5;
            }
        }

        // 9. Services (10%)
        if ($this->services()->exists()) {
            $percentage += 10;
        }

        // 10. Availability (5%)
        if ($this->availabilities()->exists()) {
            $percentage += 5;
        }

        return min(100, $percentage);
    }

    /**
     * Role-Based Access Control (RBAC) Relationships & Helpers
     */
    public function getRolesAttribute()
    {
        $roleName = $this->role ?? 'customer';
        return collect([
            (object)[
                'name' => $roleName,
                'display_name' => ucfirst($roleName)
            ]
        ]);
    }

    public function hasRole(string $role): bool
    {
        return ($this->role ?? 'customer') === $role;
    }

    public function hasPermission(string $permission): bool
    {
        // Simple role-based permission fallback
        if (($this->role ?? 'customer') === 'admin') {
            return true;
        }
        return false;
    }

    public function assignRole(string $roleName): void
    {
        $this->role = $roleName;
        $this->save();
    }

    public function removeRole(string $roleName): void
    {
        if ($this->role === $roleName) {
            $this->role = 'customer';
            $this->save();
        }
    }

    public function getActiveRole(): string
    {
        return $this->role ?? 'customer';
    }

    /**
     * Accessor override to return the role column directly.
     */
    public function getRoleAttribute(): string
    {
        return $this->attributes['role'] ?? 'customer';
    }

    /**
     * Resolve profile picture to a usable URL:
     *  1. External http/https URL → return as-is
     *  2. Starts with 'images/' → served from public/ via asset()
     *  3. Otherwise → served from storage/app/public/ via asset('storage/...')
     */
    public function getProfilePictureUrlAttribute(): string
    {
        $pic = $this->profile_picture;
        if (!$pic) {
            return asset('images/default_avatar.png');
        }
        if (str_starts_with($pic, 'http://') || str_starts_with($pic, 'https://')) {
            return $pic;
        }
        if (str_starts_with($pic, 'images/')) {
            return asset($pic);
        }
        return asset('storage/' . $pic);
    }
}

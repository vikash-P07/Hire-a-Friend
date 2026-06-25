<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'customer_id',
    'partner_id',
    'booking_date',
    'start_time',
    'duration_hours',
    'hourly_rate',
    'total_amount',
    'status',
    'location_address',
    'description',
    'coupon_id',
    'discount_amount',
    'final_amount'
])]
class Booking extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function partnerEarning()
    {
        return $this->hasOne(PartnerEarning::class);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }
}

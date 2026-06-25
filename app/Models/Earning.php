<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'booking_id',
    'partner_id',
    'total_amount',
    'commission_amount',
    'net_amount',
    'status'
])]
class Earning extends Model
{
    use HasFactory;

    protected $table = 'earnings';

    protected function casts(): array
    {
        return [
            'total_amount' => 'float',
            'commission_amount' => 'float',
            'net_amount' => 'float',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}

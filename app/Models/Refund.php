<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'payment_id',
    'booking_id',
    'amount',
    'refund_status',
    'refund_transaction_id',
    'reason'
])]
class Refund extends Model
{
    use HasFactory;

    protected $table = 'refunds';

    protected function casts(): array
    {
        return [
            'amount' => 'float',
        ];
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

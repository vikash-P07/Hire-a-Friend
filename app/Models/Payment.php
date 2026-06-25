<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id',
    'amount',
    'payment_method',
    'payment_status',
    'transaction_id',
    'payable_type',
    'payable_id'
])]
class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected function casts(): array
    {
        return [
            'amount' => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payable()
    {
        return $this->morphTo();
    }

    public function transaction()
    {
        return $this->hasOne(PaymentTransaction::class);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }
}

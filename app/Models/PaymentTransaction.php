<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'payment_id',
    'payment_gateway',
    'gateway_transaction_id',
    'response_payload'
])]
class PaymentTransaction extends Model
{
    use HasFactory;

    protected $table = 'payment_transactions';

    protected function casts(): array
    {
        return [
            'response_payload' => 'array',
        ];
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}

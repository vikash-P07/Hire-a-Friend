<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'withdrawal_request_id',
    'partner_id',
    'amount',
    'payout_method',
    'bank_details',
    'upi_details',
    'status',
    'transaction_reference'
])]
class Payout extends Model
{
    use HasFactory;

    protected $table = 'payouts';

    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'bank_details' => 'array',
            'upi_details' => 'array',
        ];
    }

    public function withdrawalRequest()
    {
        return $this->belongsTo(WithdrawalRequest::class, 'withdrawal_request_id');
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}

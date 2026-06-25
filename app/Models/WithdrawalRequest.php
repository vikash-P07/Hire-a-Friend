<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'partner_id',
    'amount',
    'status',
    'payout_method',
    'upi_id',
    'bank_holder_name',
    'bank_account_number',
    'bank_ifsc',
    'bank_name',
    'notes',
    'processed_at'
])]
class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $table = 'withdrawal_requests';

    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'processed_at' => 'datetime',
        ];
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}

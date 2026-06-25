<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'partner_id',
    'amount',
    'status',
    'bank_holder_name',
    'bank_account_number',
    'bank_ifsc',
    'bank_name',
    'notes',
    'processed_at'
])]
class Withdrawal extends Model
{
    use HasFactory;

    protected $table = 'withdrawals';

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

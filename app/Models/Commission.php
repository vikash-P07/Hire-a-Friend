<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'partner_id',
    'commission_percentage'
])]
class Commission extends Model
{
    use HasFactory;

    protected $table = 'commissions';

    protected function casts(): array
    {
        return [
            'commission_percentage' => 'float',
        ];
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}

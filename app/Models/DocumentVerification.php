<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id',
    'aadhaar_front',
    'aadhaar_back',
    'pan_card',
    'selfie',
    'aadhaar_status',
    'pan_status',
    'selfie_status',
    'notes'
])]
class DocumentVerification extends Model
{
    use HasFactory;

    protected $table = 'documents_verification';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

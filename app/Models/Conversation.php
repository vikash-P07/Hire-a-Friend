<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'companion_id',
        'is_blocked',
        'blocked_by',
        'last_message_at',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
        'last_message_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function companion()
    {
        return $this->belongsTo(User::class, 'companion_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }
}

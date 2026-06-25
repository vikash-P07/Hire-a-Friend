<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'meta_title', 'meta_description', 'author_name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name', 'role', 'rating', 'comment', 'is_visible', 'ip',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'rating' => 'integer',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSubscriber extends Model
{
    protected $fillable = [
        'email', 'source', 'coupon_code', 'is_active', 'subscribed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
    ];
}

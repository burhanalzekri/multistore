<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsTemplate extends Model
{
    protected $fillable = [
        "shop_id",
        "event_key",
        "body",
        "is_active",
    ];

    protected $casts = [
        "is_active" => "boolean",
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}

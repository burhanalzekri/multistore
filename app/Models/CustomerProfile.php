<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model {
    protected $guarded = [];
    protected $casts = [
        'preferred_categories' => 'array',
        'preferred_price_range' => 'array',
        'top_viewed_products' => 'array',
        'last_activity_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
}

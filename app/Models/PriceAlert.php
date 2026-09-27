<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceAlert extends Model
{
    protected $guarded = [];
    protected $casts = ['notified' => 'bool', 'target_price' => 'float'];
    public function product() { return $this->belongsTo(Product::class); }
    public function user() { return $this->belongsTo(User::class); }
}

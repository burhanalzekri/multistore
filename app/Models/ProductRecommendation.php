<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRecommendation extends Model {
    protected $guarded = [];
    protected $casts = [
        'similar_product_ids' => 'array',
        'bought_together_ids' => 'array',
    ];

    public function product() { return $this->belongsTo(Product::class); }
}

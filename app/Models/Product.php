<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    use BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['is_active' => 'bool', 'images' => 'array'];

    public function category() { return $this->belongsTo(Category::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function shop() { return $this->belongsTo(Shop::class); }

    public function getAverageRatingAttribute() {
        return round($this->reviews()->where('is_approved', true)->avg('rating') ?? 0, 1);
    }

    public function getReviewsCountAttribute() {
        return $this->reviews()->where('is_approved', true)->count();
    }
}

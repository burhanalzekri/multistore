<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model {
    use BelongsToTenant;
    protected $guarded = [];
    public $timestamps = false;

    public function product() { return $this->belongsTo(Product::class); }
}

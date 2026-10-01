<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Review extends Model {
    use BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['is_approved' => 'bool'];

    public function product() { return $this->belongsTo(Product::class); }
}

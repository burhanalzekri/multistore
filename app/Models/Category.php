<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    use BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['is_active' => 'bool'];

    public function products() { return $this->hasMany(Product::class); }
}

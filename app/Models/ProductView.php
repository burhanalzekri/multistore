<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductView extends Model {
    protected $guarded = [];
    public $timestamps = false;
    public function product() { return $this->belongsTo(Product::class); }
}

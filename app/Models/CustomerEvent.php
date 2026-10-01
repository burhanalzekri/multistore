<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerEvent extends Model {
    protected $guarded = [];
    protected $casts = ['meta' => 'array', 'created_at' => 'datetime'];
}

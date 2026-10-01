<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model {
    protected $guarded = [];
    protected $casts = ['features' => 'array', 'limits' => 'array', 'is_active' => 'bool'];

    public function subscriptions() { return $this->hasMany(Subscription::class); }
}

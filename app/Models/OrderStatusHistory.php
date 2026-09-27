<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model {
    protected $guarded = [];
    protected $casts = ['created_at' => 'datetime'];

    public function order() { return $this->belongsTo(Order::class); }
    public function user() { return $this->belongsTo(User::class, 'changed_by'); }
}

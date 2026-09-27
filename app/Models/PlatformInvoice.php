<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformInvoice extends Model {
    protected $guarded = [];
    protected $casts = ['due_date' => 'datetime', 'paid_at' => 'datetime'];

    public function shop() { return $this->belongsTo(Shop::class); }
    public function subscription() { return $this->belongsTo(Subscription::class); }
}

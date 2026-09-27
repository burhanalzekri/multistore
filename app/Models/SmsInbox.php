<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class SmsInbox extends Model {
    use BelongsToTenant;

    protected $table = 'sms_inbox';
    protected $guarded = [];
    protected $casts = ['received_at' => 'datetime'];

    public function matchedOrder() {
        return $this->belongsTo(Order::class, 'matched_order_id');
    }
    public function shop() {
        return $this->belongsTo(Shop::class);
    }
    public function paymentTransaction() {
        return $this->hasOne(PaymentTransaction::class, 'sms_inbox_id');
    }
}

<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model {
    use BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['verified_at' => 'datetime'];

    // ⭐ العلاقات
    public function order()     { return $this->belongsTo(Order::class); }
    public function smsInbox()  { return $this->belongsTo(SmsInbox::class, 'sms_inbox_id'); }
    public function shop()      { return $this->belongsTo(Shop::class); }
}

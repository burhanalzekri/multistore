<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['paid_at'=>'datetime'];

    public function items() { return $this->hasMany(OrderItem::class); }
    public function shop()  { return $this->belongsTo(Shop::class); }

    public static function generateNumber(): string {
        return 'ORD-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -5));
    }
}

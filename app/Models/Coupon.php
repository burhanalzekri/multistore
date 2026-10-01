<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model {
    use BelongsToTenant;
    protected $guarded = [];
    protected $casts = [
        'expires_at' => 'datetime',
        'starts_at' => 'datetime',
        'is_active' => 'bool',
        'first_order_only' => 'bool',
        'value' => 'float',
        'min_order' => 'float',
        'max_discount' => 'float',
    ];

    /**
     * هل الكوبون صالح للاستخدام؟
     */
    public function isValid(): bool {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        return true;
    }

    /**
     * حساب قيمة الخصم
     */
    public function discount(float $total): float {
        if ($total < ($this->min_order ?? 0)) return 0;

        $discount = 0;
        if ($this->type === 'percentage') {
            $discount = $total * ($this->value / 100);
            // سقف الخصم
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = (float) $this->max_discount;
            }
        } else {
            $discount = (float) $this->value;
        }

        // لا يتجاوز الإجمالي
        if ($discount > $total) $discount = $total;

        return round($discount, 2);
    }

    /**
     * هل يمكن للمستخدم استخدام هذا الكوبون؟
     */
    public function canBeUsedBy(?int $userId): array {
        // المستخدم المسجل: فحص حد الاستخدام
        if ($userId && $this->per_user_limit) {
            $usedByUser = \App\Models\Order::where('user_id', $userId)
                ->where('coupon_code', $this->code)
                ->count();

            if ($usedByUser >= $this->per_user_limit) {
                return ['ok' => false, 'message' => 'لقد استخدمت هذا الكوبون من قبل'];
            }
        }

        // فحص الطلب الأول
        if ($this->first_order_only && $userId) {
            $hasOrders = \App\Models\Order::where('user_id', $userId)->exists();
            if ($hasOrders) {
                return ['ok' => false, 'message' => 'هذا الكوبون للطلب الأول فقط'];
            }
        }

        return ['ok' => true, 'message' => ''];
    }
}

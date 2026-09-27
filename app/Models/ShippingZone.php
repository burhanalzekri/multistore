<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $guarded = [];

    protected $casts = [
        'fee' => 'decimal:2',
        'free_over' => 'decimal:2',
        'min_order' => 'decimal:2',
        'max_order_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // ═══ العلاقات ═══
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // ═══ Scopes ═══
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForShop($query, $shopId)
    {
        return $query->where('shop_id', $shopId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // ═══ حساب الشحن (بسيط) ═══
    public function calculateFee(float $orderTotal): float
    {
        if ($this->free_over && $orderTotal >= (float) $this->free_over) {
            return 0;
        }
        return (float) $this->fee;
    }

    // ═══ هل يحتاج تسعير يدوي؟ ═══
    public function requiresQuote(float $orderTotal): bool
    {
        if (!$this->max_order_amount) {
            return false; // لا يوجد حد ← كل شيء تلقائي
        }
        return $orderTotal > (float) $this->max_order_amount;
    }

    // ═══ الحساب الذكي (يُرجع fee + status) ═══
    public function calculateFeeWithStatus(float $orderTotal): array
    {
        // 1) الطلبات الكبيرة ← تسعير يدوي
        if ($this->requiresQuote($orderTotal)) {
            return [
                'fee' => null,
                'status' => 'pending_quote',
                'message' => '📞 طلبك كبير — سيتواصل معك فريقنا لحساب تكلفة الشحن الإضافية',
                'requires_quote' => true,
            ];
        }

        // 2) الطلبات العادية ← تسعير تلقائي
        $fee = $this->calculateFee($orderTotal);
        $isFree = $fee === 0.0;

        return [
            'fee' => $fee,
            'status' => 'calculated',
            'message' => $isFree ? '🎁 شحن مجاني' : null,
            'requires_quote' => false,
            'free_shipping' => $isFree,
        ];
    }

    // ═══ هل يقبل الطلب؟ ═══
    public function acceptsOrder(float $orderTotal): bool
    {
        if (!$this->is_active) return false;
        if ($this->min_order && $orderTotal < (float) $this->min_order) return false;
        return true;
    }

    // ═══ النص الكامل للشحن ═══
    public function getFeeLabelAttribute(): string
    {
        if ((float) $this->fee === 0.0) {
            return 'مجاني';
        }
        return number_format((float) $this->fee) . ' ر.ي';
    }

    // ═══ نص الحد الأقصى ═══
    public function getMaxOrderLabelAttribute(): ?string
    {
        if (!$this->max_order_amount) {
            return null;
        }
        return 'حتى ' . number_format((float) $this->max_order_amount) . ' ر.ي';
    }
}

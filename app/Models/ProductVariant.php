<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'bool',
        'price' => 'float',
        'stock' => 'int',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function effectivePrice(): float
    {
        return $this->price ?? $this->product->price ?? 0;
    }

    public function inStock(): bool
    {
        return $this->stock > 0 && $this->is_active;
    }

    public function label(): string
    {
        $parts = array_filter([$this->size, $this->color]);
        return implode(' / ', $parts) ?: 'افتراضي';
    }

    /**
     * توليد SKU فريد
     */
    public static function generateSku(int $productId, ?string $size, ?string $color, ?string $colorHex = null): string
    {
        $parts = ['P' . str_pad((string) $productId, 5, '0', STR_PAD_LEFT)];
        if ($size) $parts[] = strtoupper(substr($size, 0, 6));
        if ($color) $parts[] = strtoupper(substr(preg_replace('/[^\\p{L}\\p{N}]/u', '', $color), 0, 6));
        $parts[] = strtoupper(substr(uniqid(), -4));
        return implode('-', $parts);
    }

    /**
     * توليد باركوود Code128 (يتوافق مع JsBarcode)
     */
    public static function generateBarcode(): string
    {
        // EAN-13 style: 13 رقم — لكن Code128 يقبل أي نص
        // نستخدم صيغة آمنة وقصيرة
        $prefix = 'MS'; // MultiStore
        $random = strtoupper(substr(md5(uniqid('', true)), 0, 10));
        return $prefix . $random;
    }
}

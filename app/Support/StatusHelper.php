<?php
namespace App\Support;

class StatusHelper
{
    private static array $map = [
        // حالة المتجر
        'active' => 'نشط',
        'suspended' => 'موقوف',
        'trial' => 'تجريبي',

        // حالة SMS
        'pending' => 'قيد الانتظار',
        'matched' => 'مُطابَقة',
        'review' => 'للمراجعة',
        'rejected' => 'مرفوضة',

        // حالة الدفع
        'confirmed' => 'مؤكد',
        'duplicate' => 'مكرر',

        // حالة الطلب
        'awaiting_payment' => 'بانتظار الدفع',
        'processing' => 'قيد المعالجة',
        'shipped' => 'تم الشحن',
        'delivered' => 'تم التوصيل',
        'cancelled' => 'ملغاة',

        // طرق الدفع
        'cod' => 'عند الاستلام',
        'wallet' => 'محفظة',
        'bank' => 'حوالة بنكية',

        // الموظفون
        'super_admin' => 'مدير عام',
        'shop_admin' => 'مدير متجر',
        'staff' => 'موظف',
        'customer' => 'عميل',

        // طرق التأكيد
        'auto' => 'آلي',
        'manual' => 'يدوي',
    ];

    public static function label(?string $key): string
    {
        if (!$key) return '—';
        return self::$map[$key] ?? $key;
    }

    public static function badge(?string $key): string
    {
        $colors = [
            'active' => 'bg-green-100 text-green-700',
            'confirmed' => 'bg-green-100 text-green-700',
            'matched' => 'bg-green-100 text-green-700',
            'delivered' => 'bg-green-100 text-green-700',
            'suspended' => 'bg-red-100 text-red-700',
            'rejected' => 'bg-red-100 text-red-700',
            'cancelled' => 'bg-red-100 text-red-700',
            'trial' => 'bg-blue-100 text-blue-700',
            'processing' => 'bg-blue-100 text-blue-700',
            'shipped' => 'bg-blue-100 text-blue-700',
            'pending' => 'bg-amber-100 text-amber-700',
            'awaiting_payment' => 'bg-amber-100 text-amber-700',
            'review' => 'bg-amber-100 text-amber-700',
        ];
        $color = $colors[$key] ?? 'bg-slate-100 text-slate-700';
        return $color;
    }
}

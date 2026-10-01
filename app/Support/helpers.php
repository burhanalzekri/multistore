<?php

use App\Models\SiteSetting;

if (!function_exists('setting')) {
    function setting(string $path, $default = null)
    {
        $all = SiteSetting::allCached();
        return $all[$path] ?? $default;
    }
}

if (!function_exists('setting_set')) {
    function setting_set(string $path, $value, string $type = 'text'): void
    {
        [$group, $key] = array_pad(explode('.', $path, 2), 2, 'general');

        $serialized = match ($type) {
            'boolean' => $value ? '1' : '0',
            'json'    => is_string($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE),
            default   => (string) $value,
        };

        SiteSetting::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $serialized, 'type' => $type]
        );
    }
}

if (!function_exists('live_stats')) {
    /**
     * 📊 إحصائيات Live — تعمل في وضعين:
     * - manual: أرقام يدوية من Dashboard
     * - real:   أرقام حقيقية من DB
     */
    function live_stats(): array
    {
        $mode = setting('stats.mode', 'manual');

        if ($mode === 'real') {
            return [
                'mode'   => 'real',
                'shops'  => [
                    'value' => \App\Models\Shop::where('status', 'active')->count(),
                    'label' => setting('stats.shop_label', 'متجر نشط'),
                ],
                'orders' => [
                    'value' => \App\Models\Order::withoutGlobalScope('tenant')->count(),
                    'label' => setting('stats.order_label', 'طلب مكتمل'),
                ],
                'sales'  => [
                    'value' => round(\App\Models\Order::withoutGlobalScope('tenant')
                        ->where('payment_status', 'confirmed')
                        ->sum('total') / 1000000, 1),
                    'label' => setting('stats.sales_label', 'مليون ريال مبيعات'),
                ],
                'users'  => [
                    'value' => \App\Models\User::whereDate('created_at', today())->count(),
                    'label' => setting('stats.user_label', 'مستخدم اليوم'),
                ],
            ];
        }

        // manual (الافتراضي)
        return [
            'mode'   => 'manual',
            'shops'  => [
                'value' => (int) setting('stats.shop_count', 547),
                'label' => setting('stats.shop_label', 'متجر نشط'),
            ],
            'orders' => [
                'value' => (int) setting('stats.order_count', 52430),
                'label' => setting('stats.order_label', 'طلب مكتمل'),
            ],
            'sales'  => [
                'value' => (float) setting('stats.sales_count', 8),
                'label' => setting('stats.sales_label', 'مليون ريال مبيعات'),
            ],
            'users'  => [
                'value' => (int) setting('stats.user_count', 1287),
                'label' => setting('stats.user_label', 'مستخدم اليوم'),
            ],
        ];
    }
}


if (!function_exists('cmpIcon')) {
    /**
     * 🎨 أيقونة حالة المقارنة
     * يقبل: yes/no/partial أو ✅/❌/🟡 أو نص حر
     */
    function cmpIcon($value): string
    {
        $v = strtolower(trim((string) $value));

        if (in_array($v, ['yes', '✅', 'true', '1'], true)) {
            return '<span class="compare-icon yes">✅</span>';
        }
        if (in_array($v, ['no', '❌', 'false', '0', ''], true)) {
            return '<span class="compare-icon no">❌</span>';
        }
        if (in_array($v, ['partial', '🟡', 'half'], true)) {
            return '<span class="compare-icon partial">🟡</span>';
        }
        if ($v === 'free' || $v === 'مجاناً') {
            return '<strong style="font-size:16px;color:#15803d">مجاناً</strong>'
                 . '<div class="compare-win-badge">الأرخص</div>';
        }
        return '<span style="font-size:13px;color:#475569">' . e($value) . '</span>';
    }
}

<?php

namespace App\Services;

use App\Models\Shop;

class ShopThemeDetector
{
    /**
     * كشف نوع المتجر بناءً على فئاته ومنتجاته
     */
    public static function detect(Shop $shop): array
    {
        // اجلب أسماء الفئات للمتجر
        $categoryNames = \App\Models\Category::where('shop_id', $shop->id)
            ->pluck('name')
            ->implode(' ');

        $productNames = \App\Models\Product::where('shop_id', $shop->id)
            ->take(20)
            ->pluck('name')
            ->implode(' ');

        $text = $categoryNames . ' ' . $productNames;

        // 🍯 عسل ومنتجات طبيعية
        if (preg_match('/(عسل|نحل|شمع|غذاء ملكات|حبة بركة|عكبر|حلبة|طبيعي)/u', $text)) {
            return self::honeyTheme();
        }

        // 👶 ملابس أطفال
        if (preg_match('/(مواليد|أطفال|طفل|بيبي|مخلاة|حفاظات|رضاعة|عربية|كرسي أطفال)/u', $text)) {
            return self::kidsTheme();
        }

        // 👗 ملابس نسائية
        if (preg_match('/(فستان|بلوزة|تنورة|نسائ|عباية|حجاب|كعب|مكياج|عطر نسائي)/u', $text)) {
            return self::womenTheme();
        }

        // 👔 ملابس رجالية
        if (preg_match('/(قميص|بنطال|بدلة|جاكيت|رجال|جينز|تيشيرت|ساعة رجالية)/u', $text)) {
            return self::menTheme();
        }

        // 💻 إلكترونيات
        if (preg_match('/(جوال|لابتوب|سماعة|شاحن|بطارية|كاميرا|ساعة ذكية|إلكتروني)/u', $text)) {
            return self::electronicsTheme();
        }

        // 💄 تجميل
        if (preg_match('/(عطر|مكياج|كريم|شامبو|عناية|جمال|أحمر شفاه)/u', $text)) {
            return self::beautyTheme();
        }

        // 🏠 منزل ومطبخ
        if (preg_match('/(مطبخ|منزل|أثاث|كرسي|طاولة|أدوات منزلية)/u', $text)) {
            return self::homeTheme();
        }

        // افتراضي — عام
        return self::defaultTheme();
    }

    private static function honeyTheme(): array
    {
        return [
            'type' => 'honey',
            'name' => '🍯 متجر العسل',
            'primary' => '#f59e0b',
            'primary_dark' => '#d97706',
            'gradient' => 'linear-gradient(135deg, #fbbf24, #f97316)',
            'header_color' => '#7c2d12',
            'accent_emoji' => '🍯',
            'hero_slides' => [
                ['tag' => '🍯 عسل طبيعي 100%', 'title' => 'عسل جبلي أصلي من قلب الجبال', 'desc' => 'جودة مضمونة وطعم لا يقاوم'],
                ['tag' => '🏔️ مباشرة من المناحل', 'title' => 'أجود أنواع العسل اليمني', 'desc' => 'عسل سدر، جبلي، شوكي، ملكي'],
                ['tag' => '🚚 توصيل سريع', 'title' => 'شحن مجاني للطلبات الكبيرة', 'desc' => 'اشترِ فوق 50,000 ريال'],
            ],
            'nav_categories' => ['عسل جبلي', 'عسل سدر', 'عسل ملكي', 'عسل شوكي', 'منتجات أخرى'],
            'icon_set' => ['🍯', '🐝', '🌿', '👑', '🏔️', '🌸'],
        ];
    }

    private static function kidsTheme(): array
    {
        return [
            'type' => 'kids',
            'name' => '👶 متجر الأطفال',
            'primary' => '#ec4899',
            'primary_dark' => '#be185d',
            'gradient' => 'linear-gradient(135deg, #f472b6, #ec4899)',
            'header_color' => '#831843',
            'accent_emoji' => '👶',
            'hero_slides' => [
                ['tag' => '👶 مستلزمات المواليد', 'title' => 'كل ما يحتاجه طفلك في مكان واحد', 'desc' => 'منتجات آمنة وعالية الجودة'],
                ['tag' => '🧸 ألعاب تعليمية', 'title' => 'نمو ذكي لطفلك', 'desc' => 'ألعاب خشبية وتعليمية'],
                ['tag' => '🎁 خصم 20%', 'title' => 'عروض خاصة على ملابس الأطفال', 'desc' => 'أطقم كاملة بأسعار مميزة'],
            ],
            'nav_categories' => ['ملابس مواليد', 'ألعاب', 'أحذية أطفال', 'مستلزمات الرضاعة', 'مخاليق'],
            'icon_set' => ['👶', '🧸', '🍼', '🎁', '👕', '🧦'],
        ];
    }

    private static function womenTheme(): array
    {
        return [
            'type' => 'women',
            'name' => '👗 متجر الأناقة',
            'primary' => '#ec4899',
            'primary_dark' => '#be185d',
            'gradient' => 'linear-gradient(135deg, #f472b6, #db2777)',
            'header_color' => '#831843',
            'accent_emoji' => '👗',
            'hero_slides' => [
                ['tag' => '👗 أزياء نسائية', 'title' => 'تألقي بأحدث صيحات الموضة', 'desc' => 'فساتين، بلوزات، عبايات'],
                ['tag' => '💄 عطور ومكياج', 'title' => 'أجمل العطور النسائية', 'desc' => 'عطور فرنسية أصلية'],
                ['tag' => '🎁 خصم 25%', 'title' => 'عروض حصرية على الفساتين', 'desc' => 'لموسم الأعراس'],
            ],
            'nav_categories' => ['فساتين', 'بلوزات', 'عبايات', 'عطور', 'مكياج', 'حقائب'],
            'icon_set' => ['👗', '💄', '👠', '👜', '💐', '🌸'],
        ];
    }

    private static function menTheme(): array
    {
        return [
            'type' => 'men',
            'name' => '👔 متجر الرجولة',
            'primary' => '#1e40af',
            'primary_dark' => '#1e3a8a',
            'gradient' => 'linear-gradient(135deg, #3b82f6, #1e40af)',
            'header_color' => '#0c1e3d',
            'accent_emoji' => '👔',
            'hero_slides' => [
                ['tag' => '👔 أزياء رجالية', 'title' => 'أناقة لا تقاوم', 'desc' => 'قمصان، بناطيل، بدلات'],
                ['tag' => '⌚ ساعات كلاسيكية', 'title' => 'ساعات بأشهر الماركات', 'desc' => 'تصاميم راقية'],
                ['tag' => '🎁 خصم 20%', 'title' => 'عروض على الملابس الرسمية', 'desc' => 'بدلات كاملة'],
            ],
            'nav_categories' => ['قمصان', 'بناطيل', 'بدلات', 'أحذية', 'ساعات', 'جاكيتات'],
            'icon_set' => ['👔', '👖', '🕴️', '⌚', '👞', '🧥'],
        ];
    }

    private static function electronicsTheme(): array
    {
        return [
            'type' => 'electronics',
            'name' => '💻 متجر التقنية',
            'primary' => '#06b6d4',
            'primary_dark' => '#0891b2',
            'gradient' => 'linear-gradient(135deg, #06b6d4, #0891b2)',
            'header_color' => '#0e2a3f',
            'accent_emoji' => '💻',
            'hero_slides' => [
                ['tag' => '💻 أحدث الأجهزة', 'title' => 'تقنية متطورة بأفضل الأسعار', 'desc' => 'جوالات، لابتوبات، سماعات'],
                ['tag' => '⚡ ضمان أصلي', 'title' => 'أجهزة أصلية 100%', 'desc' => 'مع ضمان الوكيل'],
                ['tag' => '🎁 خصم 15%', 'title' => 'عروض على الإلكترونيات', 'desc' => 'لفترة محدودة'],
            ],
            'nav_categories' => ['جوالات', 'لابتوبات', 'سماعات', 'شواحن', 'ساعات ذكية'],
            'icon_set' => ['📱', '💻', '🎧', '⌚', '🔋', '📷'],
        ];
    }

    private static function beautyTheme(): array
    {
        return [
            'type' => 'beauty',
            'name' => '💄 متجر الجمال',
            'primary' => '#a855f7',
            'primary_dark' => '#7c3aed',
            'gradient' => 'linear-gradient(135deg, #a855f7, #7c3aed)',
            'header_color' => '#3f1d5e',
            'accent_emoji' => '💄',
            'hero_slides' => [
                ['tag' => '💄 جمالك أولويتنا', 'title' => 'أفضل مستحضرات التجميل', 'desc' => 'ماركات عالمية'],
                ['tag' => '🌸 عطور فاخرة', 'title' => 'عطور شرقية وفرنسية', 'desc' => 'ثبات طويل'],
                ['tag' => '🎁 خصم 30%', 'title' => 'عروض على العناية', 'desc' => 'منتجات طبيعية'],
            ],
            'nav_categories' => ['عطور', 'مكياج', 'عناية بالبشرة', 'شامبو', 'أحمر شفاه'],
            'icon_set' => ['💄', '💅', '🌸', '🧴', '✨', '💐'],
        ];
    }

    private static function homeTheme(): array
    {
        return [
            'type' => 'home',
            'name' => '🏠 متجر المنزل',
            'primary' => '#8b5cf6',
            'primary_dark' => '#6d28d9',
            'gradient' => 'linear-gradient(135deg, #8b5cf6, #6d28d9)',
            'header_color' => '#3b1f5e',
            'accent_emoji' => '🏠',
            'hero_slides' => [
                ['tag' => '🏠 أثاث عصري', 'title' => 'أثاث لمنزل أنيق', 'desc' => 'تصاميم حديثة'],
                ['tag' => '🍽️ أدوات المطبخ', 'title' => 'كل ما يحتاجه مطبخك', 'desc' => 'أدوات عملية'],
                ['tag' => '🎁 خصم 20%', 'title' => 'عروض على الأدوات المنزلية', 'desc' => 'أدوات أساسية'],
            ],
            'nav_categories' => ['أثاث', 'أدوات مطبخ', 'ديكور', 'مفروشات', 'تنظيم'],
            'icon_set' => ['🏠', '🛋️', '🍽️', '🪑', '🖼️', '🛏️'],
        ];
    }

    private static function defaultTheme(): array
    {
        return [
            'type' => 'default',
            'name' => '🛍️ المتجر',
            'primary' => '#1a1a1a',
            'primary_dark' => '#000000',
            'gradient' => 'linear-gradient(135deg, #1a1a1a, #2d2d2d)',
            'header_color' => '#1a1a1a',
            'accent_emoji' => '🛍️',
            'hero_slides' => [
                ['tag' => '🛍️ تسوّق الآن', 'title' => 'أفضل المنتجات بأفضل الأسعار', 'desc' => 'جودة عالية وتوصيل سريع'],
                ['tag' => '🎁 عروض حصرية', 'title' => 'خصومات كبرى', 'desc' => 'لفترة محدودة'],
                ['tag' => '🚚 شحن سريع', 'title' => 'توصيل لجميع المناطق', 'desc' => 'اشترِ فوق 50,000 ريال'],
            ],
            'nav_categories' => ['الكل', 'جديد', 'عروض', 'الأكثر مبيعاً'],
            'icon_set' => ['🛍️', '🎁', '⚡', '✨', '🌟', '🔥'],
        ];
    }
}

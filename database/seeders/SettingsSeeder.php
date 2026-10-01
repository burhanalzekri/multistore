<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // ═══ Hero ═══
            ['hero', 'title',         'أنشئ متجرك الإلكتروني وابدأ البيع بثقة', 'text'],
            ['hero', 'subtitle',      'منصة MultiStore تمنحك الأدوات التي تحتاجها لإدارة المنتجات، الطلبات، العملاء، والمدفوعات — من لوحة تحكم واحدة، بواجهة بسيطة وسريعة ومتوافقة مع الجوال.', 'text'],
            ['hero', 'badge',         'منصة متاجر إلكترونية يمنية 🇾🇪', 'text'],
            ['hero', 'cta_primary',   'ابدأ متجرك مجاناً', 'text'],
            ['hero', 'cta_secondary', 'شاهد كيف يعمل', 'text'],
            ['hero', 'visible',       '1', 'boolean'],

            // ═══ Live Stats ═══
            ['stats', 'visible',     '1', 'boolean'],
            ['stats', 'mode',        'manual', 'text'],  // manual | real
            ['stats', 'shop_count',  '547', 'number'],
            ['stats', 'shop_label',  'متجر نشط', 'text'],
            ['stats', 'order_count', '52430', 'number'],
            ['stats', 'order_label', 'طلب مكتمل', 'text'],
            ['stats', 'sales_count', '8', 'number'],
            ['stats', 'sales_label', 'مليون ريال مبيعات', 'text'],
            ['stats', 'user_count',  '1287', 'number'],
            ['stats', 'user_label',  'مستخدم اليوم', 'text'],

            // ═══ Trust Logos ═══
            ['logos', 'visible', '1', 'boolean'],
            ['logos', 'title',   'يثق بنا أكثر من 500 متجر يمني', 'text'],
            ['logos', 'items',   json_encode([
                ['emoji' => '🍯', 'name' => 'متجر العسل',    'color' => '#fbbf24'],
                ['emoji' => '👗', 'name' => 'أزياء صنعاء',  'color' => '#ec4899'],
                ['emoji' => '📱', 'name' => 'إلكترونيات عدن', 'color' => '#3b82f6'],
                ['emoji' => '🍯', 'name' => 'عسل حضرموت',   'color' => '#22c55e'],
                ['emoji' => '👔', 'name' => 'ملابس رجالية', 'color' => '#a855f7'],
                ['emoji' => '🛒', 'name' => 'سوق تعز',      'color' => '#f97316'],
            ], JSON_UNESCAPED_UNICODE), 'json'],

            // ═══ Features ═══
            ['features', 'items',   '[{"icon": "store", "title": "متاجر متعددة", "desc": "أنشئ وأدر أكثر من متجر من منصة واحدة — كل متجر ببياناته ومنتجاته وعملائه."}, {"icon": "package", "title": "إدارة المنتجات", "desc": "أضف منتجاتك بصور احترافية، متغيرات، مخزون، وتصنيفات — بضغطة زر."}, {"icon": "shopping-bag", "title": "إدارة الطلبات", "desc": "تابع الطلبات، حالاتها، وتتبع الشحنات من لوحة تحكم واحدة شاملة."}, {"icon": "message-square", "title": "دفع SMS تلقائي", "desc": "النظام يقرأ رسائل التحويل البنكي ويؤكد الطلب تلقائياً في ثوانٍ."}, {"icon": "bar-chart-3", "title": "تقارير وتحليلات", "desc": "راقب أداء متجرك — مبيعات، أرباح، عملاء، وأفضل المنتجات لحظياً."}, {"icon": "shield-check", "title": "أمان وصلاحيات", "desc": "تحكم كامل بالصلاحيات، الأدوار، والوصول لكل قسم في النظام."}]', 'json'],

            // ═══ Comparison ═══
            ['comparison', 'title',    'قارن قبل أن تقرر 🧐', 'text'],
            ['comparison', 'subtitle', 'انظر إلى ما يميّزنا عن المنصات الأخرى — الميزات اليمنية الأصلية التي لن تجدها في أي مكان.', 'text'],
            ['comparison', 'rows',     '[{"feature": "💳 دفع SMS يمني تلقائي", "us": "yes", "salla": "no", "zid": "no"}, {"feature": "🏪 متاجر متعددة بحساب واحد", "us": "yes", "salla": "partial", "zid": "yes"}, {"feature": "🇾🇪 دعم فني يمني", "us": "yes", "salla": "no", "zid": "no"}, {"feature": "💰 سعر شهري", "us": "free", "salla": "~200 ر.س", "zid": "~150 ر.س"}, {"feature": "🌐 عربي أصلي + RTL", "us": "yes", "salla": "yes", "zid": "yes"}, {"feature": "📱 تطبيق PWA", "us": "yes", "salla": "partial", "zid": "partial"}, {"feature": "🚫 بدون عمولة على المبيعات", "us": "yes", "salla": "no", "zid": "no"}]', 'json'],

            // ═══ Videos ═══
            ['videos', 'title',    'اسمع من تجّارنا الحقيقيين', 'text'],
            ['videos', 'subtitle', 'قصص مباشرة من أصحاب متاجر يمنية — كيف بدأوا وكيف نمت متاجرهم مع MultiStore.', 'text'],
            ['videos', 'items',    '[{"name": "أحمد الحميري", "role": "صاحب متجر عسل 🍯", "avatar_letter": "أ", "avatar_color": "#fbbf24", "video_url": "", "poster_url": "/images/demo/shirt.jpg", "duration": "0:45", "is_new": "1"}, {"name": "سارة المقطري", "role": "صاحبة متجر أزياء 👗", "avatar_letter": "س", "avatar_color": "#ec4899", "video_url": "", "poster_url": "/images/demo/jacket.jpg", "duration": "1:20", "is_new": "0"}, {"name": "محمد الشرعبي", "role": "صاحب متجر إلكترونيات 📱", "avatar_letter": "م", "avatar_color": "#3b82f6", "video_url": "", "poster_url": "/images/demo/headphones.jpg", "duration": "0:30", "is_new": "0"}]', 'json'],

            // ═══ Stories ═══
            ['stories', 'title',    'عملاؤنا حقّقوا نتائج حقيقية', 'text'],
            ['stories', 'subtitle', 'أرقام فعلية من متاجر يمنية انطلقت مع MultiStore — خلال أشهر قليلة فقط.', 'text'],
            ['stories', 'items',    '[{"name": "متجر عسل حضرموت", "type": "متجر منتجات طبيعية", "emoji": "🍯", "color": "#fbbf24", "quote": "في 3 أشهر فقط، تضاعفت مبيعاتنا 4 مرات. الدفع عبر SMS وفّر علينا ساعات يومياً، والعملاء أصبحوا يثقون أكثر بسبب التأكيد الفوري.", "stat1_num": "+320%", "stat1_label": "نمو المبيعات", "stat2_num": "3 أشهر", "stat2_label": "مدة النمو", "tag": "✅ 98% تقييمات إيجابية"}, {"name": "أزياء صنعاء", "type": "متجر أزياء نسائية", "emoji": "👗", "color": "#ec4899", "quote": "كنت أدير كل شيء يدوياً عبر واتساب. اليوم لديّ لوحة تحكم كاملة — أتابع الطلبات، العملاء، والمخزون بضغطة زر. راحة نفسية حقيقية.", "stat1_num": "+180%", "stat1_label": "نمو العملاء", "stat2_num": "6 أشهر", "stat2_label": "مدة النمو", "tag": "✅ 1200+ عميل جديد"}, {"name": "إلكترونيات عدن", "type": "متجر أجهزة إلكترونية", "emoji": "📱", "color": "#3b82f6", "quote": "أسرع منصة جربتها. رفعت 200 منتج في يوم واحد، وربطت الدفع خلال ساعة. الفريق اليمني فهم احتياجنا من أول مكالمة.", "stat1_num": "+95%", "stat1_label": "نمو الطلبات", "stat2_num": "شهران", "stat2_label": "مدة النمو", "tag": "✅ 200 منتج مرفوع"}]', 'json'],

            // ═══ Marketing Showcase ═══
            ['marketing', 'title',       'شاهد قوة المنصة', 'text'],
            ['marketing', 'title_hl',    'في صور حقيقية', 'text'],
            ['marketing', 'subtitle',    'عرض ثلاثي الأبعاد، لوحة تحكم احترافية، ومتجر إلكتروني جاهز — كل ذلك في MultiStore.', 'text'],
            ['marketing', 'cta_text',    'شاهد المتجر التجريبي', 'text'],
            ['marketing', 'cta_link',    '/demo-shop', 'text'],
            ['marketing', 'items',       '[{"image": "/images/marketing/store-3d.webp", "badge_text": "3D EXPERIENCE", "badge_color": "orange", "title": "عرض المنتجات باحترافية", "desc": "تجربة بصرية حديثة تُبرز منتجاتك بأبهى صورة."}, {"image": "/images/marketing/dashboard.webp", "badge_text": "SMART DASHBOARD", "badge_color": "blue", "title": "إدارة متجرك من مكان واحد", "desc": "الطلبات، المنتجات، العملاء، والإحصائيات — بلوحة واحدة."}, {"image": "/images/marketing/store-marketing.webp", "badge_text": "ONLINE STORE", "badge_color": "green", "title": "متجر جاهز للعرض والبيع", "desc": "تصميم متجاوب وسريع — على الجوال والحاسوب."}]', 'json'],

            // ═══ Section Visibility ═══
            ['sections', 'features_visible',   '1', 'boolean'],
            ['sections', 'steps_visible',      '1', 'boolean'],
            ['sections', 'roi_visible',        '1', 'boolean'],
            ['sections', 'guarantee_visible',  '1', 'boolean'],
            ['sections', 'comparison_visible', '1', 'boolean'],
            ['sections', 'pricing_visible',    '1', 'boolean'],
            ['sections', 'reviews_visible',    '1', 'boolean'],
            ['sections', 'stories_visible',    '1', 'boolean'],
            ['sections', 'videos_visible',     '1', 'boolean'],
            ['sections', 'faq_visible',        '1', 'boolean'],
            ['sections', 'cta_visible',        '1', 'boolean'],

            // ═══ CTA ═══
            ['cta', 'title',    'جاهز لإنشاء متجرك؟', 'text'],
            ['cta', 'subtitle', 'انضم إلى التجار اليمنيين الذين بدأوا رحلتهم الرقمية مع MultiStore. ابدأ مجاناً — بدون بطاقة بنكية.', 'text'],
        ];

        $count = 0;
        foreach ($defaults as [$group, $key, $value, $type]) {
            \App\Models\SiteSetting::updateOrCreate(
                ['group' => $group, 'key' => $key],
                ['value' => $value, 'type' => $type]
            );
            $count++;
        }

        $this->command->info("✅ Seeded {$count} settings");
    }
}

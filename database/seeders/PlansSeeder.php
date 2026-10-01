<?php
namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'مجانية',
                'slug' => 'free',
                'description' => 'ابدأ متجرك مجانًا',
                'price' => 0,
                'interval' => 'monthly',
                'features' => ['50 منتج', 'نطاق فرعي', 'تحقق SMS أساسي'],
                'limits' => ['products' => 50, 'orders' => 100, 'staff' => 1],
                'sort_order' => 1,
            ],
            [
                'name' => 'احترافية',
                'slug' => 'pro',
                'description' => 'للأعمال النامية',
                'price' => 9900,
                'interval' => 'monthly',
                'features' => ['منتجات غير محدودة', 'نطاق مخصص', 'تقارير متقدمة', 'دعم أولوية'],
                'limits' => ['products' => -1, 'orders' => -1, 'staff' => 3],
                'sort_order' => 2,
            ],
            [
                'name' => 'أعمال',
                'slug' => 'business',
                'description' => 'للمتاجر الكبيرة',
                'price' => 29900,
                'interval' => 'monthly',
                'features' => ['كل مزايا الاحترافية', 'API كامل', 'Webhooks', 'مدير حساب مخصص'],
                'limits' => ['products' => -1, 'orders' => -1, 'staff' => 10],
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $p) {
            Plan::updateOrCreate(['slug' => $p['slug']], $p);
        }

        echo "✅ تم إنشاء " . count($plans) . " خطط" . PHP_EOL;
    }
}

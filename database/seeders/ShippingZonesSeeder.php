<?php

namespace Database\Seeders;

use App\Models\ShippingZone;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class ShippingZonesSeeder extends Seeder
{
    public function run(): void
    {
        $shops = Shop::all();

        foreach ($shops as $shop) {
            $existing = ShippingZone::where('shop_id', $shop->id)->count();
            if ($existing > 0) {
                continue; // لا نُكرر
            }

            $this->seedForShop($shop->id);
        }

        // إذا لا يوجد متاجر ← أنشئ للمتجر الأول افتراضياً
        if ($shops->isEmpty()) {
            $this->seedForShop(null);
        }
    }

    private function seedForShop(?int $shopId): void
    {
        $zones = [
            ['name' => 'صنعاء', 'code' => 'sanaa', 'fee' => 0, 'free_over' => null, 'eta' => '1 يوم', 'sort_order' => 1],
            ['name' => 'عدن', 'code' => 'aden', 'fee' => 3000, 'free_over' => null, 'eta' => '2-3 أيام', 'sort_order' => 2],
            ['name' => 'تعز', 'code' => 'taiz', 'fee' => 0, 'free_over' => null, 'eta' => '2 أيام', 'sort_order' => 3],
            ['name' => 'الحديدة', 'code' => 'hodeidah', 'fee' => 2500, 'free_over' => null, 'eta' => '2 أيام', 'sort_order' => 4],
            ['name' => 'إب', 'code' => 'ibb', 'fee' => 2000, 'free_over' => null, 'eta' => '1-2 يوم', 'sort_order' => 5],
            ['name' => 'حضرموت', 'code' => 'hadramout', 'fee' => 4000, 'free_over' => null, 'eta' => '3-4 أيام', 'sort_order' => 6],
            ['name' => 'مأرب', 'code' => 'marib', 'fee' => 3000, 'free_over' => null, 'eta' => '2-3 أيام', 'sort_order' => 7],
            ['name' => 'حجة', 'code' => 'hajjah', 'fee' => 2500, 'free_over' => null, 'eta' => '2 أيام', 'sort_order' => 8],
            ['name' => 'صعدة', 'code' => 'saada', 'fee' => 3500, 'free_over' => null, 'eta' => '3 أيام', 'sort_order' => 9],
            ['name' => 'لحج', 'code' => 'lahj', 'fee' => 3000, 'free_over' => null, 'eta' => '2-3 أيام', 'sort_order' => 10],
            ['name' => 'أبين', 'code' => 'abyan', 'fee' => 3500, 'free_over' => null, 'eta' => '3 أيام', 'sort_order' => 11],
            ['name' => 'شبوة', 'code' => 'shabwah', 'fee' => 4000, 'free_over' => null, 'eta' => '3-4 أيام', 'sort_order' => 12],
            ['name' => 'المهرة', 'code' => 'mahra', 'fee' => 5000, 'free_over' => null, 'eta' => '4-5 أيام', 'sort_order' => 13],
            ['name' => 'الضالع', 'code' => 'dhale', 'fee' => 3000, 'free_over' => null, 'eta' => '2-3 أيام', 'sort_order' => 14],
            ['name' => 'البيضاء', 'code' => 'bayda', 'fee' => 3000, 'free_over' => null, 'eta' => '2-3 أيام', 'sort_order' => 15],
            ['name' => 'الجوف', 'code' => 'jawf', 'fee' => 3500, 'free_over' => null, 'eta' => '3 أيام', 'sort_order' => 16],
            ['name' => 'سقطرى', 'code' => 'socotra', 'fee' => 15000, 'free_over' => null, 'eta' => '5-7 أيام', 'sort_order' => 17],
            ['name' => 'منطقة أخرى', 'code' => 'other', 'fee' => 4000, 'free_over' => null, 'eta' => 'حسب الموقع', 'sort_order' => 99],
        ];

        foreach ($zones as $zone) {
            ShippingZone::create(array_merge($zone, [
                'shop_id' => $shopId,
                'min_order' => 0,
                'max_order_amount' => null,
                'is_active' => true,
            ]));
        }

        echo "✅ تم إنشاء " . count($zones) . " منطقة شحن للمتجر: " . ($shopId ?? 'افتراضي') . "\n";
    }
}

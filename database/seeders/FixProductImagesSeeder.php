<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class FixProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            17 => '/images/demo/headphones.jpg',
            18 => '/images/demo/smartwatch.jpg',
            19 => '/images/demo/charger.jpg',
            20 => '/images/demo/shirt.jpg',
            21 => '/images/demo/powerbank.jpg',
            22 => '/images/demo/webcam.jpg',
            23 => '/images/demo/jacket.jpg',
            24 => '/images/demo/tshirt.jpg',
        ];

        $fixed = 0;
        foreach ($map as $id => $path) {
            $p = Product::find($id);
            if ($p && $p->image !== $path) {
                $p->update(['image' => $path]);
                $this->command->info("✅ ID {$id} → {$path}");
                $fixed++;
            }
        }

        if ($fixed === 0) {
            $this->command->info("ℹ️  كل الصور صحيحة");
        } else {
            $this->command->info("🎉 إصلاح {$fixed} صورة");
        }
    }
}

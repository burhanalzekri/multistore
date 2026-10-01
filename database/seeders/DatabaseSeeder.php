<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            \Database\Seeders\SuperAdminSeeder::class,
            \Database\Seeders\PlansSeeder::class,
            \Database\Seeders\SettingsSeeder::class,
            \Database\Seeders\ShippingZonesSeeder::class,
            \Database\Seeders\SmsPatternSeeder::class,
        ]);
    }
}

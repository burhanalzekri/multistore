<?php
namespace Database\Seeders;

use App\Models\SmsPattern;
use Illuminate\Database\Seeder;

class SmsPatternSeeder extends Seeder {
    public function run(): void {
        SmsPattern::create([
            'provider' => 'kuraimi',
            'label' => 'استلام مبلغ الكريمي',
            'amount_regex' => '/مبلغ\s*:?\s*([\d,\.]+)/u',
            'sender_regex' => '/(7\d{8})/u',
            'reference_regex' => '/(?:رقم العملية|المرجع)\s*:?\s*(\d{4,})/u',
            'keywords' => ['الكريمي'],
            'is_active' => true,
        ]);
        SmsPattern::create([
            'provider' => 'jawali',
            'label' => 'إيداع جوالي',
            'amount_regex' => '/(?:إيداع|مبلغ)\s*([\d,\.]+)/u',
            'sender_regex' => '/(7\d{8})/u',
            'reference_regex' => '/(\d{6,})/',
            'keywords' => ['جوالي'],
            'is_active' => true,
        ]);
    }
}

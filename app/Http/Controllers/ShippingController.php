<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public static function zones()
    {
        return [
            'sanaa' => ['name' => 'صنعاء', 'fee' => 0],
            'aden' => ['name' => 'عدن', 'fee' => 3000],
            'taiz' => ['name' => 'تعز', 'fee' => 2500],
            'hodeidah' => ['name' => 'الحديدة', 'fee' => 2500],
            'ibb' => ['name' => 'إب', 'fee' => 2000],
            'hadramout' => ['name' => 'حضرموت', 'fee' => 4000],
            'marib' => ['name' => 'مأرب', 'fee' => 3000],
            'hajjah' => ['name' => 'حجة', 'fee' => 2500],
            'saada' => ['name' => 'صعدة', 'fee' => 3500],
            'lahj' => ['name' => 'لحج', 'fee' => 3000],
            'abyan' => ['name' => 'أبين', 'fee' => 3500],
            'shabwah' => ['name' => 'شبوة', 'fee' => 4000],
            'mahra' => ['name' => 'المهرة', 'fee' => 5000],
            'socotra' => ['name' => 'سقطرى', 'fee' => 15000],
            'other' => ['name' => 'منطقة أخرى', 'fee' => 4000],
        ];
    }

    public function calculate(Request $request)
    {
        $zone = $request->input('zone', 'sanaa');
        $total = (float) $request->input('total', 0);
        $zones = self::zones();
        $fee = $zones[$zone]['fee'] ?? 0;

        if ($total >= 50000) $fee = 0;

        return response()->json([
            'zone' => $zone,
            'zone_name' => $zones[$zone]['name'] ?? 'غير معروف',
            'fee' => $fee,
            'free_shipping' => $total >= 50000,
            'total_with_shipping' => $total + $fee,
        ]);
    }
}

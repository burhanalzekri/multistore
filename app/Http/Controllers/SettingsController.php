<?php
namespace App\Http\Controllers;

use App\Models\Shop;
use App\Services\Tenant\TenantManager;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $shop = app(TenantManager::class)->get();
        if (!$shop) $shop = Shop::first();

        return view('dashboard.settings.index', compact('shop'));
    }

    public function update(Request $request)
    {
        $shop = app(TenantManager::class)->get();
        if (!$shop) $shop = Shop::first();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'primary_color' => 'nullable|string|max:10',
            'currency' => 'nullable|string|max:3',
            'variant_label_1' => 'nullable|string|max:50',
            'variant_label_2' => 'nullable|string|max:50',
            'variant_icon_1' => 'nullable|string|max:10',
            'variant_icon_2' => 'nullable|string|max:10',
        ]);

        // 🏷️ حفظ تسميات الـ Variants في settings (JSON)
        $settings = is_array($shop->settings) ? $shop->settings : [];
        $settings['variant_label_1'] = $data['variant_label_1'] ?? 'اللون';
        $settings['variant_label_2'] = $data['variant_label_2'] ?? 'المقاس';
        $settings['variant_icon_1']  = $data['variant_icon_1']  ?? '🎨';
        $settings['variant_icon_2']  = $data['variant_icon_2']  ?? '📏';

        // إزالة حقول التسميات من $data الأساسية
        unset($data['variant_label_1'], $data['variant_label_2'],
              $data['variant_icon_1'],  $data['variant_icon_2']);

        // تحديث الحقول الأساسية
        $shop->name = $data['name'];
        $shop->phone = $data['phone'] ?? null;
        $shop->whatsapp = $data['whatsapp'] ?? null;
        $shop->primary_color = $data['primary_color'] ?? null;
        $shop->currency = $data['currency'] ?? null;
        $shop->settings = $settings;
        $shop->save();

        return back()->with('success', 'تم حفظ الإعدادات');
    }
}

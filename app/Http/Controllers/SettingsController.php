<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Services\Tenant\TenantManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    // ═══ عرض الإعدادات ═══
    public function index()
    {
        $shop = app(TenantManager::class)->get();
        if (!$shop) abort(404, 'لا يوجد متجر محدد لهذا الطلب.');

        return view('dashboard.settings.index', compact('shop'));
    }

    // ═══ تحديث الإعدادات ═══
    public function update(Request $request)
    {
        $shop = app(TenantManager::class)->get();
        if (!$shop) abort(404, 'لا يوجد متجر محدد لهذا الطلب.');

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

        return back()->with('success', '✅ تم حفظ الإعدادات');
    }

    // ═══ 🎨 رفع شعار المتجر ═══
    public function uploadLogo(Request $request)
    {
        $shop = app(TenantManager::class)->get();
        if (!$shop) abort(404, 'لا يوجد متجر محدد لهذا الطلب.');

        $request->validate([
            'logo' => 'required|file|mimes:png,jpg,jpeg,svg,webp|max:2048', // 2MB
        ], [
            'logo.required' => 'يرجى اختيار صورة الشعار',
            'logo.mimes' => 'الملف يجب أن يكون PNG أو JPG أو SVG أو WebP',
            'logo.max' => 'حجم الصورة يجب أن يكون أقل من 2MB',
        ]);

        try {
            // احذف الشعار القديم إذا موجود
            if ($shop->logo && !str_starts_with($shop->logo, 'http')) {
                if (Storage::disk('public')->exists($shop->logo)) {
                    Storage::disk('public')->delete($shop->logo);
                }
            }

            // احفظ الشعار الجديد
            $file = $request->file('logo');
            $ext = $file->getClientOriginalExtension();
            $filename = 'logo-' . $shop->id . '-' . time() . '.' . $ext;
            $path = $file->storeAs('logos', $filename, 'public');

            $shop->logo = $path;
            $shop->save();

            return back()->with('success', '✅ تم رفع الشعار بنجاح');

        } catch (\Throwable $e) {
            \Log::error('Logo upload failed: ' . $e->getMessage());
            return back()->with('error', '⚠️ تعذر رفع الشعار: ' . $e->getMessage());
        }
    }

    // ═══ 🗑️ حذف شعار المتجر ═══
    public function deleteLogo()
    {
        $shop = app(TenantManager::class)->get();
        if (!$shop) abort(404, 'لا يوجد متجر محدد لهذا الطلب.');

        try {
            if ($shop->logo && !str_starts_with($shop->logo, 'http')) {
                if (Storage::disk('public')->exists($shop->logo)) {
                    Storage::disk('public')->delete($shop->logo);
                }
            }

            $shop->logo = null;
            $shop->save();

            return back()->with('success', '✅ تم حذف الشعار');
        } catch (\Throwable $e) {
            return back()->with('error', '⚠️ تعذر حذف الشعار');
        }
    }
}

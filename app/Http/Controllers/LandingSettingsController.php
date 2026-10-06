<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LandingSettingsController extends Controller
{
    /**
     * 🎨 صفحة إعدادات Landing
     */
    public function index()
    {
        $groups = [
            'hero'       => $this->groupData('hero'),
            'stats'      => $this->groupData('stats'),
            'cta'        => $this->groupData('cta'),
            'sections'   => $this->groupData('sections'),
            'logos'      => $this->groupData('logos'),
            'features'   => $this->groupData('features'),
            'comparison' => $this->groupData('comparison'),
            'videos'     => $this->groupData('videos'),
            'stories'    => $this->groupData('stories'),
            'marketing'  => $this->groupData('marketing'),
        ];

        return view('dashboard.landing-settings.index', compact('groups'));
    }

    /**
     * 💾 حفظ الإعدادات
     */
    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        $updated = 0;

        foreach ($data as $path => $value) {
            // استثناء الـpresets
            if (str_contains($path, '_preset_')) continue;

            [$group, $key] = array_pad(explode('.', $path, 2), 2, null);
            if (!$group || !$key) continue;

            // استنتاج النوع من الـrequest
            $type = $this->inferType($value, $group, $key);
            $serialized = $this->serialize($value, $type);

            SiteSetting::updateOrCreate(
                ['group' => $group, 'key' => $key],
                ['value' => $serialized, 'type' => $type]
            );
            $updated++;
        }

        Cache::forget('site_settings');

        return back()->with('success', "✅ تم حفظ {$updated} إعداد");
    }

    /**
     * 🔄 حفظ إعداد واحد (AJAX Toggle)
     */
    public function toggle(Request $request)
    {
        $data = $request->validate([
            'path'  => 'required|string',
            'value' => 'required',
        ]);

        [$group, $key] = array_pad(explode('.', $data['path'], 2), 2, null);
        if (!$group || !$key) {
            return response()->json(['ok' => false, 'message' => 'مسار غير صحيح'], 400);
        }

        $type = $this->inferType($data['value'], $group, $key);
        $serialized = $this->serialize($data['value'], $type);

        SiteSetting::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $serialized, 'type' => $type]
        );

        Cache::forget('site_settings');

        return response()->json(['ok' => true]);
    }

    /**
     * 📤 رفع صورة
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png,webp,gif,heic,heif|max:5120',
        ]);

        try {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // الحفظ في public/images/landing/ (يُشحن مع git)
            // ملاحظة: على Render Free، لن يبقى بعد deploy
            $fileSize = $file->getSize();
        $path = $file->move(public_path('images/landing'), $filename);

            $url = '/images/landing/' . $filename;

            return response()->json([
                'ok' => true,
                'url' => $url,
                'filename' => $filename,
                'size' => round($fileSize / 1024, 1) . ' KB',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'فشل الرفع: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 📦 جلب بيانات مجموعة (مع defaults)
     */
    protected function groupData(string $group): array
    {
        return SiteSetting::where('group', $group)
            ->get()
            ->mapWithKeys(fn($s) => [$s->key => $s->value])
            ->toArray();
    }

    /**
     * 🔍 استنتاج نوع الحقل
     */
    protected function inferType($value, string $group, string $key): string
    {
        // Bool من الـcheckbox
        if (in_array($key, ['visible', 'active']) || str_starts_with($key, 'is_')) {
            return 'boolean';
        }
        if (str_ends_with($key, '_visible')) {
            return 'boolean';
        }
        if (str_contains($key, 'count') || in_array($key, ['shop_count', 'order_count', 'sales_count', 'user_count'])) {
            return 'number';
        }
        return 'text';
    }

    /**
     * 💾 تسلسل القيمة
     */
    protected function serialize($value, string $type): string
    {
        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'number'  => (string) (float) $value,
            default   => (string) $value,
        };
    }
}

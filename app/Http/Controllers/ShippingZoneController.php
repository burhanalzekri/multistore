<?php

namespace App\Http\Controllers;

use App\Models\ShippingZone;
use App\Models\Shop;
use App\Services\Tenant\TenantManager;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    // ═══ عرض قائمة المناطق ═══
    public function index()
    {
        $shop = app(TenantManager::class)->currentOrFallback();
        if (!$shop) abort(404);

        $zones = ShippingZone::forShop($shop->id)
            ->ordered()
            ->get();

        $stats = [
            'total' => $zones->count(),
            'active' => $zones->where('is_active', true)->count(),
            'free' => $zones->where('fee', 0)->count(),
            'avg_fee' => $zones->where('fee', '>', 0)->avg('fee') ?? 0,
            'with_quote' => $zones->whereNotNull('max_order_amount')->count(),
        ];

        return view('dashboard.shipping-zones.index', compact('zones', 'stats', 'shop'));
    }

    // ═══ نموذج الإضافة ═══
    public function create()
    {
        $shop = app(TenantManager::class)->currentOrFallback();
        if (!$shop) abort(404);

        return view('dashboard.shipping-zones.create', compact('shop'));
    }

    // ═══ حفظ منطقة جديدة ═══
    public function store(Request $request)
    {
        $shop = app(TenantManager::class)->currentOrFallback();
        if (!$shop) abort(404);

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'fee' => 'required|numeric|min:0',
            'free_over' => 'nullable|numeric|min:0',
            'max_order_amount' => 'nullable|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'eta' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['shop_id'] = $shop->id;
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 999;

        ShippingZone::create($data);

        return redirect('/dashboard/shipping-zones')
            ->with('status', '✅ تمت إضافة المنطقة بنجاح');
    }

    // ═══ نموذج التعديل ═══
    public function edit($id)
    {
        $shop = app(TenantManager::class)->currentOrFallback();
        if (!$shop) abort(404);

        $zone = ShippingZone::forShop($shop->id)->findOrFail($id);

        return view('dashboard.shipping-zones.edit', compact('zone', 'shop'));
    }

    // ═══ تحديث منطقة ═══
    public function update(Request $request, $id)
    {
        $shop = app(TenantManager::class)->currentOrFallback();
        if (!$shop) abort(404);

        $zone = ShippingZone::forShop($shop->id)->findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'fee' => 'required|numeric|min:0',
            'free_over' => 'nullable|numeric|min:0',
            'max_order_amount' => 'nullable|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'eta' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 999;

        $zone->update($data);

        return redirect('/dashboard/shipping-zones')
            ->with('status', '✅ تم تحديث المنطقة بنجاح');
    }

    // ═══ حذف منطقة ═══
    public function destroy($id)
    {
        $shop = app(TenantManager::class)->currentOrFallback();
        if (!$shop) abort(404);

        $zone = ShippingZone::forShop($shop->id)->findOrFail($id);
        $zone->delete();

        return redirect('/dashboard/shipping-zones')
            ->with('status', '✅ تم حذف المنطقة');
    }

    // ═══ تبديل الحالة ═══
    public function toggle($id)
    {
        $shop = app(TenantManager::class)->currentOrFallback();
        if (!$shop) abort(404);

        $zone = ShippingZone::forShop($shop->id)->findOrFail($id);
        $zone->update(['is_active' => !$zone->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $zone->is_active,
            'message' => $zone->is_active ? 'تم التفعيل' : 'تم التعطيل',
        ]);
    }

    // ═══ API: قائمة المناطق (للـ Checkout) ═══
    public function apiList()
    {
        $shop = app(TenantManager::class)->currentOrFallback();
        if (!$shop) {
            return response()->json(['zones' => []]);
        }

        $zones = ShippingZone::forShop($shop->id)
            ->active()
            ->ordered()
            ->get(['id', 'name', 'code', 'fee', 'free_over', 'eta']);

        return response()->json(['zones' => $zones]);
    }

    // ═══ API: حساب الشحن (ذكي) ═══
    public function apiCalculate(Request $request)
    {
        $shop = app(TenantManager::class)->currentOrFallback();
        if (!$shop) {
            return response()->json(['success' => false, 'message' => 'لا يوجد متجر']);
        }

        $request->validate([
            'zone_id' => 'required|integer',
            'total' => 'required|numeric|min:0',
        ]);

        $zone = ShippingZone::forShop($shop->id)->find($request->zone_id);

        if (!$zone) {
            return response()->json(['success' => false, 'message' => 'منطقة غير موجودة']);
        }

        if (!$zone->is_active) {
            return response()->json(['success' => false, 'message' => 'المنطقة غير مفعلة']);
        }

        $total = (float) $request->total;

        if (!$zone->acceptsOrder($total)) {
            return response()->json([
                'success' => false,
                'message' => 'الحد الأدنى للطلب: ' . number_format((float) $zone->min_order) . ' ر.ي',
            ]);
        }

        // ═══ الحساب الذكي ═══
        $result = $zone->calculateFeeWithStatus($total);

        // 1) الطلبات الكبيرة ← يحتاج تسعير يدوي
        if (!empty($result['requires_quote'])) {
            return response()->json([
                'success' => true,
                'requires_quote' => true,
                'fee' => null,
                'fee_label' => 'يُحدد لاحقاً',
                'message' => $result['message'],
                'zone_name' => $zone->name,
                'eta' => $zone->eta,
                'total' => $total,
            ]);
        }

        // 2) الطلبات العادية ← تسعير تلقائي
        $fee = (float) $result['fee'];

        return response()->json([
            'success' => true,
            'requires_quote' => false,
            'fee' => $fee,
            'fee_label' => $fee > 0 ? number_format($fee) . ' ر.ي' : 'مجاني',
            'free_shipping' => $fee === 0.0,
            'total_with_shipping' => $total + $fee,
            'eta' => $zone->eta,
            'zone_name' => $zone->name,
            'message' => $result['message'] ?? null,
        ]);
    }
}

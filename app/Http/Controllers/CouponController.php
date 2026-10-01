<?php
namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    // ═══════════════════════════════════════════
    // 📋 قائمة الكوبونات
    // ═══════════════════════════════════════════
    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);

        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::where('is_active', true)->count(),
            'used' => Coupon::sum('used_count'),
            'expired' => Coupon::whereNotNull('expires_at')->where('expires_at', '<', now())->count(),
        ];

        return view('dashboard.coupons.index', compact('coupons', 'stats'));
    }

    public function create()
    {
        $shopId = $this->currentShopId();
        $products = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shopId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $categories = Category::withoutGlobalScope('tenant')
            ->where('shop_id', $shopId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('dashboard.coupons.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateCoupon($request);

        $data['code'] = strtoupper(trim($data['code']));
        $data['shop_id'] = $this->currentShopId();

        // قيم افتراضية
        $data['used_count'] = 0;
        $data['is_active'] = $request->boolean('is_active', true);
        $data['first_order_only'] = $request->boolean('first_order_only');
        $data['applies_to'] = $data['applies_to'] ?? 'all';

        Coupon::create($data);

        return redirect('/dashboard/coupons')->with('success', '✅ تم إنشاء الكوبون بنجاح');
    }

    public function edit(Coupon $coupon)
    {
        $shopId = $this->currentShopId();
        $products = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shopId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $categories = Category::withoutGlobalScope('tenant')
            ->where('shop_id', $shopId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('dashboard.coupons.edit', compact('coupon', 'products', 'categories'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $this->validateCoupon($request, $coupon->id);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active');
        $data['first_order_only'] = $request->boolean('first_order_only');

        $coupon->update($data);

        return redirect('/dashboard/coupons')->with('success', '✅ تم تحديث الكوبون');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', '🗑️ تم حذف الكوبون');
    }

    // ═══════════════════════════════════════════
    // ✅ التحقق من الكوبون (يُستدعى من Checkout)
    // ═══════════════════════════════════════════
    public function validate_coupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'total' => 'nullable|numeric|min:0',
        ]);

        $code = strtoupper(trim($request->input('code')));
        $total = (float) $request->input('total', 0);

        $coupon = Coupon::withoutGlobalScope('tenant')
            ->where('code', $code)
            ->where('shop_id', $this->currentShopId())
            ->first();

        if (!$coupon) {
            return response()->json(['ok' => false, 'message' => 'الكوبون غير موجود'], 404);
        }

        if (!$coupon->isValid()) {
            $msg = 'الكوبون غير صالح';
            if ($coupon->expires_at && $coupon->expires_at->isPast()) $msg = 'الكوبون منتهي الصلاحية';
            elseif (!$coupon->is_active) $msg = 'الكوبون غير مُفعَّل';
            elseif ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) $msg = 'تم استنفاد هذا الكوبون';
            return response()->json(['ok' => false, 'message' => $msg], 422);
        }

        // فحص المستخدم
        $userId = auth()->id();
        $userCheck = $coupon->canBeUsedBy($userId);
        if (!$userCheck['ok']) {
            return response()->json(['ok' => false, 'message' => $userCheck['message']], 422);
        }

        // الحد الأدنى للطلب
        if ($total > 0 && $total < ($coupon->min_order ?? 0)) {
            return response()->json([
                'ok' => false,
                'message' => 'الحد الأدنى للطلب ' . number_format($coupon->min_order) . ' ر.ي'
            ], 422);
        }

        $discount = $total > 0 ? $coupon->discount($total) : 0;

        return response()->json([
            'ok' => true,
            'message' => '✅ تم تطبيق الكوبون',
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => (float) $coupon->value,
            'discount' => $discount,
            'new_total' => max(0, $total - $discount),
        ]);
    }

    // ═══════════════════════════════════════════
    // 🛡️ التحقق من البيانات
    // ═══════════════════════════════════════════
    protected function validateCoupon(Request $request, ?int $ignoreId = null): array
    {
        $rules = [
            'code' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'min_qty' => 'nullable|integer|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'first_order_only' => 'nullable|boolean',
            'applies_to' => 'nullable|in:all,product,category',
            'applies_to_id' => 'nullable|integer',
            'expires_at' => 'nullable|date',
            'starts_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ];

        $data = $request->validate($rules);

        // فحص تكرار الكود
        $query = Coupon::withoutGlobalScope('tenant')->where('code', strtoupper($data['code']));
        if ($ignoreId) $query->where('id', '!=', $ignoreId);

        if ($query->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'code' => 'هذا الكود مستخدم مسبقاً'
            ]);
        }

        // فحص نوع الخصم
        if ($data['type'] === 'percentage' && $data['value'] > 100) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'value' => 'النسبة يجب ألا تتجاوز 100%'
            ]);
        }

        // إزالة applies_to_id إذا كان applies_to = all
        if (($data['applies_to'] ?? 'all') === 'all') {
            $data['applies_to_id'] = null;
        }

        // 🧹 تحويل النصوص الفارغة إلى null (مهم للتواريخ والأرقام)
        foreach (['starts_at', 'expires_at', 'max_uses', 'per_user_limit', 'max_discount', 'min_order', 'min_qty', 'applies_to_id'] as $field) {
            if (isset($data[$field]) && $data[$field] === '') {
                $data[$field] = null;
            }
        }

        // تأكيد قيم افتراضية للأرقام
        if (!isset($data['min_order']) || $data['min_order'] === null) $data['min_order'] = 0;
        if (!isset($data['min_qty']) || $data['min_qty'] === null) $data['min_qty'] = 0;

        return $data;
    }

    protected function currentShopId(): int
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        return $shop ? $shop->id : 1;
    }
}

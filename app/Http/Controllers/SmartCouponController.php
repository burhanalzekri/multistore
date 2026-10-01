<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CustomerEvent;
use App\Models\Order;
use App\Services\Tenant\TenantManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SmartCouponController extends Controller
{
    /**
     * إرجاع المتجر الحالي فقط.
     *
     * لا نستخدم Shop::first() أبداً هنا.
     */
    private function currentShop()
    {
        $shop = app(TenantManager::class)->get();

        if (!$shop) {
            abort(404, 'لا يوجد متجر محدد لهذا الطلب.');
        }

        return $shop;
    }

    /**
     * كوبون ترحيبي لزائر جديد
     */
    public function welcome()
    {
        $shop = $this->currentShop();

        $sessionId = session()->getId();

        // هل استخدم من قبل؟
        $existing = session('welcome_coupon');

        if ($existing) {
            return response()->json([
                'coupon' => $existing,
            ]);
        }

        $code = 'WELCOME' . strtoupper(Str::random(6));

        Coupon::withoutGlobalScope('tenant')->create([
            'shop_id' => $shop->id,
            'code' => $code,
            'type' => 'percentage',
            'value' => 10,
            'min_order' => 0,
            'max_uses' => 1,
            'used_count' => 0,
            'is_active' => true,
            'expires_at' => now()->addDays(7),
        ]);

        session(['welcome_coupon' => $code]);

        return response()->json([
            'coupon' => $code,
            'message' => '🎁 كوبون خصم 10% بانتظارك!',
        ]);
    }

    /**
     * كوبون "أكمل مشترياتك"
     * للعميل الذي شاهد ولم يشترِ
     */
    public function nudge()
    {
        if (!Auth::check()) {
            return response()->json([
                'coupon' => null,
            ]);
        }

        $shop = $this->currentShop();
        $userId = Auth::id();

        /*
         * مهم:
         * فحص الطلبات يجب أن يكون داخل المتجر الحالي.
         */
        $hasOrders = Order::withoutGlobalScope('tenant')
            ->where('shop_id', $shop->id)
            ->where('user_id', $userId)
            ->exists();

        if ($hasOrders) {
            return response()->json([
                'coupon' => null,
            ]);
        }

        /*
         * وكذلك الأحداث يجب أن تكون خاصة بالمتجر الحالي
         * إذا كان الجدول يحتوي shop_id.
         */
        $viewsQuery = CustomerEvent::where('user_id', $userId)
            ->where('event_type', 'view');

        if (\Schema::hasColumn('customer_events', 'shop_id')) {
            $viewsQuery->where('shop_id', $shop->id);
        }

        $views = $viewsQuery->count();

        if ($views < 5) {
            return response()->json([
                'coupon' => null,
            ]);
        }

        // هل حصل عليه؟
        $existing = session('nudge_coupon');

        if ($existing) {
            return response()->json([
                'coupon' => $existing,
            ]);
        }

        $code = 'COMEBACK' . strtoupper(Str::random(5));

        Coupon::withoutGlobalScope('tenant')->create([
            'shop_id' => $shop->id,
            'code' => $code,
            'type' => 'percentage',
            'value' => 15,
            'min_order' => 0,
            'max_uses' => 1,
            'used_count' => 0,
            'is_active' => true,
            'expires_at' => now()->addDays(3),
        ]);

        session(['nudge_coupon' => $code]);

        return response()->json([
            'coupon' => $code,
            'message' => '🎁 خصم 15% خصيصًا لك — لأنك تصفحت منتجاتنا!',
        ]);
    }
}

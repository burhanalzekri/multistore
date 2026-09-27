<?php
namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CustomerEvent;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SmartCouponController extends Controller
{
    /**
     * كوبون ترحيبي لزائر جديد
     */
    public function welcome()
    {
        $sessionId = session()->getId();

        // هل استخدم من قبل؟
        $existing = session('welcome_coupon');
        if ($existing) {
            return response()->json(['coupon' => $existing]);
        }

        $code = 'WELCOME' . strtoupper(Str::random(6));

        Coupon::withoutGlobalScope('tenant')->create([
            'shop_id' => \App\Models\Shop::first()->id,
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
     * كوبون "أكمل مشترياتك" — للعميل الذي شاهد ولم يشتر
     */
    public function nudge()
    {
        if (!Auth::check()) return response()->json(['coupon' => null]);

        $userId = Auth::id();
        $shop = \App\Models\Shop::first();

        // هل اشترى من قبل؟
        $hasOrders = Order::withoutGlobalScope('tenant')
            ->where('user_id', $userId)
            ->exists();

        if ($hasOrders) return response()->json(['coupon' => null]);

        // هل شاهد 5 منتجات على الأقل؟
        $views = CustomerEvent::where('user_id', $userId)
            ->where('event_type', 'view')
            ->count();

        if ($views < 5) return response()->json(['coupon' => null]);

        // هل حصل عليه؟
        $existing = session('nudge_coupon');
        if ($existing) return response()->json(['coupon' => $existing]);

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

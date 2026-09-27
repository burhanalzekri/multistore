<?php
namespace App\Http\Controllers;

use App\Models\PriceAlert;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WishlistController extends Controller
{
    private function sessionId(): string
    {
        // 1) من الجلسة الحالية
        $sid = session('wishlist_id');
        if ($sid && strlen($sid) >= 20) return $sid;

        // 2) من Cookie (يعيش سنة)
        $cookie = request()->cookie('wishlist_uid');
        if ($cookie && strlen($cookie) >= 20) {
            session(['wishlist_id' => $cookie]);
            return $cookie;
        }

        // 3) من قاعدة البيانات
        $existing = \App\Models\Wishlist::withoutGlobalScope('tenant')
            ->whereNotNull('session_id')
            ->orderByDesc('id')
            ->value('session_id');
        if ($existing && strlen($existing) >= 20) {
            session(['wishlist_id' => $existing]);
            cookie()->queue('wishlist_uid', $existing, 60 * 24 * 365);
            return $existing;
        }

        // 4) إنشاء جديد
        $id = \Illuminate\Support\Str::random(32);
        session(['wishlist_id' => $id]);
        cookie()->queue('wishlist_uid', $id, 60 * 24 * 365);
        return $id;
    }

    private function currentShop()
    {
        return app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
    }

    public function index()
    {
        if (Auth::check() && Auth::user()->role === 'customer') {
            return redirect('/account/wishlist');
        }

        $shop = $this->currentShop();
        if (!$shop) {
            return view('storefront.wishlist', [
                'products' => collect(),
                'shop' => null,
            ]);
        }

        $ids = Wishlist::withoutGlobalScope('tenant')
            ->where('session_id', $this->sessionId())
            ->pluck('product_id');

        $products = Product::withoutGlobalScope('tenant')
            ->whereIn('id', $ids)
            ->where('is_active', true)
            ->get();

        return view('storefront.wishlist', compact('products', 'shop'));
    }

    public function toggle(Request $request, $id)
    {
        $sid = $this->sessionId();
        $product = Product::withoutGlobalScope('tenant')->findOrFail($id);
        $user = Auth::user();
        $userId = ($user && $user->role === 'customer') ? $user->id : null;

        $existing = Wishlist::withoutGlobalScope('tenant')
            ->where('product_id', $id)
            ->where(function ($q) use ($sid, $userId) {
                if ($userId) $q->where('user_id', $userId);
                else $q->where('session_id', $sid);
            })
            ->first();

        $inWishlist = false;
        if ($existing) {
            $existing->delete();
            $message = 'أُزيل من المفضلة';
        } else {
            Wishlist::withoutGlobalScope('tenant')->create([
                'shop_id'    => $product->shop_id,
                'product_id' => $id,
                'session_id' => $sid,
                'user_id'    => $userId,
            ]);
            $inWishlist = true;
            $message = 'أُضيف للمفضلة ❤️';
        }

        if ($request->expectsJson() || $request->ajax()) {
            $count = Wishlist::withoutGlobalScope('tenant')
                ->where('session_id', $sid)
                ->count();

            return response()->json([
                'ok' => true,
                'in_wishlist' => $inWishlist,
                'count' => $count,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    public function clear(Request $request)
    {
        $sid = $this->sessionId();
        $shop = $this->currentShop();

        Wishlist::withoutGlobalScope('tenant')
            ->where('session_id', $sid)
            ->where('shop_id', $shop?->id)
            ->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'message' => 'تم إفراغ المفضلة']);
        }

        return back()->with('success', 'تم إفراغ المفضلة');
    }

    public function alert(Request $request, $id)
    {
        $request->validate([
            'target_price' => 'required|numeric|min:0',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:30',
        ]);

        $product = Product::withoutGlobalScope('tenant')->findOrFail($id);
        $user = Auth::user();

        PriceAlert::updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id' => $user?->id,
                'email' => $request->email ?? $user?->email,
            ],
            [
                'phone' => $request->phone,
                'target_price' => $request->target_price,
                'notified' => false,
            ]
        );

        return response()->json([
            'ok' => true,
            'message' => '🔔 سنُعلمك عند وصول السعر إلى ' . number_format($request->target_price) . ' ر.ي',
        ]);
    }
}

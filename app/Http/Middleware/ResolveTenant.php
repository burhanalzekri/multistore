<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use App\Services\Tenant\TenantManager;
use Closure;
use Illuminate\Http\Request;

class ResolveTenant
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $base = config('app.domain', 'localhost');
        $shop = null;

        // 🥇 1) الجلسة (أعلى أولوية — يسمح بالتبديل بين المتاجر)
        if (session()->has('preferred_shop_id')) {
            $shop = Shop::find(session('preferred_shop_id'));
            // تجاهل إن لم يكن نشطاً
            if ($shop && $shop->status !== 'active') {
                session()->forget('preferred_shop_id');
                $shop = null;
            }
        }

        // 🥈 2) نطاق مخصص
        if (!$shop) {
            $shop = Shop::where('custom_domain', $host)->where('status', 'active')->first();
        }

        // 🥉 3) نطاق فرعي
        if (!$shop && str_ends_with($host, '.' . $base)) {
            $slug = str_replace('.' . $base, '', $host);
            $shop = Shop::where('slug', $slug)->where('status', 'active')->first();
        }

        // 4) من المستخدم المسجّل (fallback)
        if (!$shop && $request->user()?->shop_id) {
            $shop = Shop::find($request->user()->shop_id);
            if ($shop) {
                session(['preferred_shop_id' => $shop->id]);
            }
        }

        // 5) fallback للتطوير
        if (!$shop && (app()->environment('local') || $host === '127.0.0.1' || $host === 'localhost')) {
            $shop = Shop::where('status', 'active')->orderBy('id')->first();
        }

        if ($shop) {
            app(TenantManager::class)->set($shop);
            view()->share('currentShop', $shop);
        }

        return $next($request);
    }
}

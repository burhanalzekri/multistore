<?php
namespace App\Http\Middleware;

use App\Models\Shop;
use App\Services\Tenant\TenantManager;
use Closure;
use Illuminate\Http\Request;

class ResolveTenant {
    public function handle(Request $request, Closure $next) {
        $host = $request->getHost();
        $base = config('app.domain', 'localhost');

        // 1) نطاق مخصص
        $shop = Shop::where('custom_domain', $host)->where('status', 'active')->first();

        // 2) نطاق فرعي
        if (!$shop && str_ends_with($host, '.' . $base)) {
            $slug = str_replace('.' . $base, '', $host);
            $shop = Shop::where('slug', $slug)->where('status', 'active')->first();
        }

        // 3) من المستخدم المسجّل
        if (!$shop && $request->user()?->shop_id) {
            $shop = Shop::find($request->user()->shop_id);
        }

        // 4) ⭐ Fallback للتطوير: أول متجر نشط
        if (!$shop && (app()->environment('local') || $host === '127.0.0.1' || $host === 'localhost')) {
            $shop = Shop::where('status', 'active')->first();
        }

        if ($shop) {
            app(TenantManager::class)->set($shop);
            view()->share('currentShop', $shop);
        }

        return $next($request);
    }
}

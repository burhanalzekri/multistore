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
        $tenantManager = app(TenantManager::class);

        /*
        |--------------------------------------------------------------------------
        | بداية كل Request
        |--------------------------------------------------------------------------
        | لا نسمح بتسرب Tenant من Request سابق.
        */
        $tenantManager->clear();

        $host = $request->getHost();
        $base = config('app.domain', 'localhost');
        $shop = null;

        /*
        |--------------------------------------------------------------------------
        | 1) Super Admin
        |--------------------------------------------------------------------------
        | إذا لم يختر Super Admin متجراً صراحةً، يبقى خارج Tenant scope.
        | هذا ضروري حتى يستطيع إدارة جميع المتاجر.
        */
        if (
            $request->user()?->role === 'super_admin'
            && !session()->has('preferred_shop_id')
        ) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | 2) المتجر المحدد صراحةً من الجلسة
        |--------------------------------------------------------------------------
        */
        if (session()->has('preferred_shop_id')) {
            $preferredShopId = session('preferred_shop_id');

            if (!empty($preferredShopId)) {
                $shop = Shop::find($preferredShopId);
            }

            /*
             * إذا كان المتجر غير موجود أو غير نشط،
             * لا ننتقل إلى متجر آخر تلقائياً.
             */
            if ($shop && $shop->status !== 'active') {
                session()->forget('preferred_shop_id');
                $shop = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 3) النطاق المخصص
        |--------------------------------------------------------------------------
        */
        if (!$shop) {
            $shop = Shop::where('custom_domain', $host)
                ->where('status', 'active')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | 4) النطاق الفرعي
        |--------------------------------------------------------------------------
        */
        if (!$shop && str_ends_with($host, '.' . $base)) {
            $slug = substr($host, 0, -strlen('.' . $base));

            if (!empty($slug)) {
                $shop = Shop::where('slug', $slug)
                    ->where('status', 'active')
                    ->first();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 5) متجر المستخدم المسجل
        |--------------------------------------------------------------------------
        | يستخدم فقط عندما لا يوجد اختيار صريح في الجلسة.
        */
        if (!$shop && $request->user()?->shop_id) {
            $userShop = Shop::find($request->user()->shop_id);

            if ($userShop && $userShop->status === 'active') {
                $shop = $userShop;
                session(['preferred_shop_id' => $shop->id]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | مهم جداً:
        |--------------------------------------------------------------------------
        | لا يوجد هنا:
        |
        | Shop::where('status', 'active')->orderBy('id')->first()
        |
        | لأن ذلك يربط Request بمتجر عشوائي ويهدد عزل بيانات المتاجر.
        |
        | في حالة عدم وجود Tenant:
        | TenantManager يبقى فارغاً.
        */

        if ($shop) {
            $tenantManager->set($shop);

            view()->share('currentShop', $shop);
        } else {
            /*
             * حتى لا تبقى قيمة currentShop من سياق سابق.
             */
            view()->share('currentShop', null);
        }

        return $next($request);
    }
}

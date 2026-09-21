<?php
namespace App\Models\Concerns;

use App\Services\Tenant\TenantManager;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant {
    public static function bootBelongsToTenant(): void {
        // Global Scope
        static::addGlobalScope('tenant', function (Builder $b) {
            $t = app(TenantManager::class);
            if ($t->check() && !app()->bound('tenant.bypass')) {
                $b->where($b->getModel()->getTable().'.shop_id', $t->id());
            }
        });

        // Auto set shop_id عند الإنشاء
        static::creating(function ($m) {
            // إذا موجود، لا نغير
            if (!empty($m->shop_id)) return;

            // 1) من TenantManager
            $t = app(TenantManager::class);
            if ($t->check()) {
                $m->shop_id = $t->id();
                return;
            }

            // 2) Fallback: من الجلسة
            if (session()->has('shop_id')) {
                $m->shop_id = session('shop_id');
                return;
            }

            // 3) Fallback أخير: أول متجر نشط
            $shop = \App\Models\Shop::where('status', 'active')->first();
            if ($shop) {
                $m->shop_id = $shop->id;
            }
        });
    }
}

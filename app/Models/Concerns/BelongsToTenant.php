<?php

namespace App\Models\Concerns;

use App\Services\Tenant\TenantManager;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Global Tenant Scope
        |--------------------------------------------------------------------------
        */
        static::addGlobalScope('tenant', function (Builder $builder) {
            $tenantManager = app(TenantManager::class);

            /*
             * إذا كان هناك Tenant محدد،
             * يتم تقييد الاستعلام عليه.
             *
             * tenant.bypass يسمح للعمليات المركزية
             * مثل Super Admin بتجاوز الـ scope صراحةً.
             */
            if (
                $tenantManager->check()
                && !app()->bound('tenant.bypass')
            ) {
                $builder->where(
                    $builder->getModel()->getTable() . '.shop_id',
                    $tenantManager->id()
                );
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Auto Assign Tenant عند الإنشاء
        |--------------------------------------------------------------------------
        */
        static::creating(function ($model) {
            /*
             * إذا تم تحديد shop_id بشكل صريح،
             * لا نغيره.
             *
             * هذا مفيد للعمليات المركزية التي تنشئ
             * سجلاً لمتجر محدد بشكل صريح.
             */
            if (!empty($model->shop_id)) {
                return;
            }

            $tenantManager = app(TenantManager::class);

            /*
             * المصدر الوحيد التلقائي للـ Tenant
             * هو TenantManager.
             */
            if ($tenantManager->check()) {
                $model->shop_id = $tenantManager->id();
                return;
            }

            /*
             * لا يوجد Tenant:
             * لا نختار أول متجر ولا أي متجر عشوائي.
             */
            throw new RuntimeException(
                'Tenant context is required to create this record.'
            );
        });
    }
}

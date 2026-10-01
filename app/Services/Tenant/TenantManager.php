<?php

namespace App\Services\Tenant;

use App\Models\Shop;

class TenantManager
{
    private static ?Shop $shop = null;

    public function set(Shop $shop): void
    {
        self::$shop = $shop;
    }

    public function get(): ?Shop
    {
        return self::$shop;
    }

    public function id(): ?int
    {
        return self::$shop?->id;
    }

    public function check(): bool
    {
        return self::$shop !== null;
    }

    public function clear(): void
    {
        self::$shop = null;
    }

    /**
     * إرجاع المتجر الحالي فقط.
     *
     * لا نختار أول متجر نشط تلقائياً، لأن ذلك قد يؤدي
     * إلى تسريب بيانات Tenant إلى Tenant آخر.
     */
    public function currentOrFallback(): ?Shop
    {
        return $this->get();
    }
}

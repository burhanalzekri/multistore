<?php
namespace App\Services\Tenant;

use App\Models\Shop;

class TenantManager {
    private static ?Shop $shop = null;

    public function set(Shop $shop): void { self::$shop = $shop; }
    public function get(): ?Shop { return self::$shop; }
    public function id(): ?int { return self::$shop?->id; }
    public function check(): bool { return self::$shop !== null; }
    public function clear(): void { self::$shop = null; }

    /**
     * إرجاع المتجر الحالي، أو أول متجر نشط كـ fallback
     */
    public function currentOrFallback(): ?\App\Models\Shop
    {
        return $this->get() 
            ?? \App\Models\Shop::where('status', 'active')->first();
    }
}

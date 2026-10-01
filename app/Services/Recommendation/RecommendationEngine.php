<?php
namespace App\Services\Recommendation;

use App\Models\CustomerProfile;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RecommendationEngine
{
    public static function forCustomer(int $limit = 8)
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        if (!$shop) return collect();

        $userId = Auth::id();
        $sessionId = Session::getId();

        if ($userId) {
            $profile = CustomerProfile::where('shop_id', $shop->id)
                ->where('user_id', $userId)->first();
            if ($profile) return self::basedOnProfile($shop->id, $profile, $limit);
        }

        $profile = CustomerProfile::where('shop_id', $shop->id)
            ->where('session_id', $sessionId)->first();
        if ($profile) return self::basedOnProfile($shop->id, $profile, $limit);

        return self::bestSellers($shop->id, $limit);
    }

    private static function basedOnProfile($shopId, $profile, $limit)
    {
        $categoryIds = $profile->preferred_categories ?? [];

        $query = Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shopId)
            ->where('is_active', true)
            ->where('stock', '>', 0);

        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        $products = (clone $query)
            ->orderByDesc('sold_count')
            ->orderByDesc('stock')
            ->take($limit)
            ->get();

        if ($products->count() < $limit) {
            $more = self::bestSellers($shopId, $limit - $products->count());
            $products = $products->merge($more)->unique('id')->take($limit);
        }

        return $products;
    }

    public static function bestSellers($shopId, $limit = 8)
    {
        return Product::withoutGlobalScope('tenant')
            ->where('shop_id', $shopId)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->orderByDesc('sold_count')
            ->take($limit)
            ->get();
    }

    public static function customersAlsoBought($productId, $limit = 4)
    {
        $orderIds = DB::table('order_items')
            ->where('product_id', $productId)
            ->pluck('order_id');

        if ($orderIds->isEmpty()) {
            return self::similarProducts($productId, $limit);
        }

        $productIds = DB::table('order_items')
            ->whereIn('order_id', $orderIds)
            ->where('product_id', '!=', $productId)
            ->selectRaw('product_id, COUNT(*) as count')
            ->groupBy('product_id')
            ->orderByDesc('count')
            ->take($limit)
            ->pluck('product_id');

        $products = Product::withoutGlobalScope('tenant')
            ->whereIn('id', $productIds)
            ->where('is_active', true)
            ->get()
            ->sortBy(fn($p) => $productIds->search($p->id))
            ->values();

        if ($products->count() < $limit) {
            $similar = self::similarProducts($productId, $limit - $products->count());
            $products = $products->merge($similar)->unique('id');
        }

        return $products->take($limit);
    }

    public static function similarProducts($productId, $limit = 4)
    {
        $product = Product::withoutGlobalScope('tenant')->find($productId);
        if (!$product) return collect();

        return Product::withoutGlobalScope('tenant')
            ->where('id', '!=', $productId)
            ->where('shop_id', $product->shop_id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where(function ($q) use ($product) {
                if ($product->category_id) $q->where('category_id', $product->category_id);
                $q->orWhereBetween('price', [$product->price * 0.5, $product->price * 1.8]);
            })
            ->orderByDesc('sold_count')
            ->take($limit)
            ->get();
    }
}

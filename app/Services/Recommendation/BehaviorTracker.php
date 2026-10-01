<?php
namespace App\Services\Recommendation;

use App\Models\CustomerEvent;
use App\Models\CustomerProfile;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BehaviorTracker
{
    public static function track(string $eventType, array $data = [])
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        if (!$shop) return;

        $userId = Auth::id();
        $sessionId = Session::getId();

        CustomerEvent::create([
            'shop_id' => $shop->id,
            'user_id' => $userId,
            'session_id' => $sessionId,
            'event_type' => $eventType,
            'product_id' => $data['product_id'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'price_at_event' => $data['price'] ?? null,
            'search_query' => $data['query'] ?? null,
            'duration_seconds' => $data['duration'] ?? null,
            'meta' => $data['meta'] ?? null,
        ]);

        self::updateProfile($shop->id, $userId, $sessionId);
    }

    public static function updateProfile($shopId, $userId, $sessionId)
    {
        $query = CustomerProfile::where('shop_id', $shopId);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        $profile = $query->first();

        if (!$profile) {
            $profile = CustomerProfile::create([
                'shop_id' => $shopId,
                'user_id' => $userId,
                'session_id' => $sessionId,
            ]);
        }

        $events = CustomerEvent::where('shop_id', $shopId);
        if ($userId) $events->where('user_id', $userId);
        else $events->where('session_id', $sessionId);

        $profile->total_views = (clone $events)->where('event_type', 'view')->count();
        $profile->total_cart_adds = (clone $events)->where('event_type', 'add_to_cart')->count();
        $profile->total_purchases = (clone $events)->where('event_type', 'purchase')->count();
        $profile->last_activity_at = now();

        $categoryIds = (clone $events)
            ->whereNotNull('category_id')
            ->whereIn('event_type', ['view', 'add_to_cart', 'purchase'])
            ->selectRaw('category_id, COUNT(*) as count')
            ->groupBy('category_id')
            ->orderByDesc('count')
            ->take(5)
            ->pluck('category_id')
            ->toArray();
        $profile->preferred_categories = $categoryIds;

        $topViewed = (clone $events)
            ->where('event_type', 'view')
            ->whereNotNull('product_id')
            ->selectRaw('product_id, COUNT(*) as count')
            ->groupBy('product_id')
            ->orderByDesc('count')
            ->take(10)
            ->pluck('product_id')
            ->toArray();
        $profile->top_viewed_products = $topViewed;

        $prices = (clone $events)
            ->whereNotNull('price_at_event')
            ->pluck('price_at_event')
            ->filter()
            ->toArray();

        if (count($prices) > 0) {
            $avg = array_sum($prices) / count($prices);
            $profile->preferred_price_range = [
                'min' => round($avg * 0.5),
                'max' => round($avg * 1.8),
                'avg' => round($avg),
            ];
        }

        $profile->save();
    }
}

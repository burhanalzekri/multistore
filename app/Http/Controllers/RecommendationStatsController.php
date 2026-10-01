<?php
namespace App\Http\Controllers;

use App\Models\CustomerEvent;
use App\Models\CustomerProfile;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class RecommendationStatsController extends Controller
{
    public function index()
    {
        $stats = [
            'total_events' => CustomerEvent::count(),
            'views' => CustomerEvent::where('event_type', 'view')->count(),
            'cart_adds' => CustomerEvent::where('event_type', 'add_to_cart')->count(),
            'purchases' => CustomerEvent::where('event_type', 'purchase')->count(),
            'searches' => CustomerEvent::where('event_type', 'search')->count(),
            'wishlists' => CustomerEvent::where('event_type', 'wishlist')->count(),
            'profiles' => CustomerProfile::count(),
            'registered' => CustomerProfile::whereNotNull('user_id')->count(),
            'guest' => CustomerProfile::whereNull('user_id')->count(),
        ];

        // أكثر 10 منتجات مشاهدة
        $topViewed = CustomerEvent::where('event_type', 'view')
            ->whereNotNull('product_id')
            ->selectRaw('product_id, COUNT(*) as count')
            ->groupBy('product_id')
            ->orderByDesc('count')
            ->take(10)
            ->get()
            ->map(function ($row) {
                $p = Product::withoutGlobalScope('tenant')->find($row->product_id);
                return [
                    'name' => $p?->name ?? '—',
                    'views' => $row->count,
                    'price' => $p?->price ?? 0,
                    'url' => $p ? '/product/' . $p->id : '#',
                ];
            });

        // أكثر الكلمات بحثًا
        $topSearches = CustomerEvent::where('event_type', 'search')
            ->whereNotNull('search_query')
            ->selectRaw('search_query, COUNT(*) as count')
            ->groupBy('search_query')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        // آخر 30 حدث
        $recentEvents = CustomerEvent::latest()->take(30)->get();

        // الأعلى تفاعلًا
        $topCustomers = CustomerProfile::orderByDesc('total_views')
            ->take(10)
            ->get();

        return view('dashboard.recommendations.index', compact(
            'stats', 'topViewed', 'topSearches', 'recentEvents', 'topCustomers'
        ));
    }
}

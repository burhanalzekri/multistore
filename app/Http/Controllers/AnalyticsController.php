<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        $shopId = $shop?->id;

        $stats = $this->getStats($shopId);

        return view('dashboard.analytics.index', compact('stats', 'shop'));
    }

    private function getStats($shopId): array
    {
        $now = now();
        $thisMonth = $now->copy()->startOfMonth();
        $lastMonth = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // مبيعات هذا الشهر
        $thisMonthSales = Order::withoutGlobalScope('tenant')
            ->where('shop_id', $shopId)
            ->whereBetween('created_at', [$thisMonth, $now])
            ->sum('total');

        // مبيعات الشهر السابق
        $lastMonthSales = Order::withoutGlobalScope('tenant')
            ->where('shop_id', $shopId)
            ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
            ->sum('total');

        $growth = $lastMonthSales > 0
            ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100
            : 0;

        // عدد الطلبات
        $thisMonthOrders = Order::withoutGlobalScope('tenant')
            ->where('shop_id', $shopId)
            ->whereBetween('created_at', [$thisMonth, $now])
            ->count();

        // عملاء جدد
        $newCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$thisMonth, $now])
            ->count();

        // متوسط قيمة الطلب
        $avgOrder = $thisMonthOrders > 0 ? $thisMonthSales / $thisMonthOrders : 0;

        // مبيعات آخر 7 أيام
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i);
            $sales = Order::withoutGlobalScope('tenant')
                ->where('shop_id', $shopId)
                ->whereDate('created_at', $day->toDateString())
                ->sum('total');
            $chartData[] = [
                'label' => $day->locale('ar')->isoFormat('ddd'),
                'date' => $day->format('Y-m-d'),
                'value' => (float) $sales,
            ];
        }

        // أفضل 5 منتجات — بدون علاقة (نستخدم order_ids مباشرة)
        $orderIds = Order::withoutGlobalScope('tenant')
            ->where('shop_id', $shopId)
            ->where('created_at', '>=', $thisMonth)
            ->pluck('id');

        $topProducts = OrderItem::query()
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(line_total) as total_revenue'))
            ->whereIn('order_id', $orderIds)
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return [
            'sales' => (float) $thisMonthSales,
            'last_sales' => (float) $lastMonthSales,
            'growth' => round($growth, 1),
            'orders' => $thisMonthOrders,
            'customers' => $newCustomers,
            'avg_order' => (float) $avgOrder,
            'chart' => $chartData,
            'top_products' => $topProducts,
        ];
    }
}

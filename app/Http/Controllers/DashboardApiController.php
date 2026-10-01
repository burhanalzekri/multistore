<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\SmsInbox;
use Carbon\Carbon;

class DashboardApiController extends Controller
{
    public function stats()
    {
        $shop = \app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();

        return response()->json([
            'success' => true,
            'data' => [
                'sales' => (float) Order::where('payment_status', 'confirmed')->sum('total'),
                'orders' => Order::count(),
                'pending' => Order::where('status', 'awaiting_payment')->count(),
                'sms' => SmsInbox::count(),
                'today_sales' => (float) Order::where('payment_status', 'confirmed')->whereDate('created_at', today())->sum('total'),
                'today_orders' => Order::whereDate('created_at', today())->count(),
                'low_stock' => Product::withoutGlobalScope('tenant')->where('is_active', true)->whereBetween('stock', [1, 10])->count(),
                'out_of_stock' => Product::withoutGlobalScope('tenant')->where('is_active', true)->where('stock', 0)->count(),
                'review_sms' => SmsInbox::where('status', 'review')->count(),
            ],
            'timestamp' => now()->timestamp,
        ]);
    }

    public function recentOrders()
    {
        $orders = Order::latest()->take(5)->get(['id', 'order_number', 'customer_name', 'total', 'status', 'created_at']);
        return response()->json(['success' => true, 'orders' => $orders]);
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SmsInbox;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PlatformReportController extends Controller
{
    public function index()
    {
        // ═══ إحصائيات عامة ═══
        $shops = Shop::all();
        $totalSales = Order::withoutGlobalScope('tenant')->where('payment_status', 'confirmed')->sum('total');

        // ═══ ترتيب المتاجر حسب المبيعات ═══
        $shopStats = [];
        foreach ($shops as $shop) {
            $sales = Order::withoutGlobalScope('tenant')
                ->where('shop_id', $shop->id)
                ->where('payment_status', 'confirmed')
                ->sum('total');

            $orders = Order::withoutGlobalScope('tenant')->where('shop_id', $shop->id)->count();
            $products = Product::withoutGlobalScope('tenant')->where('shop_id', $shop->id)->count();
            $users = User::where('shop_id', $shop->id)->count();

            $shopStats[] = [
                'shop' => $shop,
                'sales' => $sales,
                'orders' => $orders,
                'products' => $products,
                'users' => $users,
            ];
        }

        usort($shopStats, fn($a, $b) => $b['sales'] <=> $a['sales']);

        // ═══ مبيعات آخر 7 أيام ═══
        $chart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chart[] = [
                'date' => $date->format('m-d'),
                'day' => $date->translatedFormat('D'),
                'sales' => (float) Order::withoutGlobalScope('tenant')
                    ->where('payment_status', 'confirmed')
                    ->whereDate('created_at', $date)
                    ->sum('total'),
            ];
        }

        // ═══ إجماليات ═══
        $totals = [
            'sales' => $totalSales,
            'orders' => Order::withoutGlobalScope('tenant')->count(),
            'shops' => Shop::count(),
            'products' => Product::withoutGlobalScope('tenant')->count(),
            'users' => User::count(),
            'sms' => SmsInbox::withoutGlobalScope('tenant')->count(),
        ];

        return view('super-admin.reports', compact('shopStats', 'chart', 'totals'));
    }
}

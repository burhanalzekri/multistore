<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\SmsInbox;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index() {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();

        $stats = [
            'today' => [
                'sales' => Order::where('payment_status', 'confirmed')->whereDate('created_at', $today)->sum('total'),
                'orders' => Order::whereDate('created_at', $today)->count(),
            ],
            'week' => [
                'sales' => Order::where('payment_status', 'confirmed')->where('created_at', '>=', $weekStart)->sum('total'),
                'orders' => Order::where('created_at', '>=', $weekStart)->count(),
            ],
            'month' => [
                'sales' => Order::where('payment_status', 'confirmed')->where('created_at', '>=', $monthStart)->sum('total'),
                'orders' => Order::where('created_at', '>=', $monthStart)->count(),
            ],
            'total' => [
                'sales' => Order::where('payment_status', 'confirmed')->sum('total'),
                'orders' => Order::count(),
                'products' => Product::count(),
                'sms' => SmsInbox::count(),
            ],
        ];

        // Sales last 7 days
        $chart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chart[] = [
                'date' => $date->format('m-d'),
                'sales' => (float) Order::where('payment_status', 'confirmed')->whereDate('created_at', $date)->sum('total'),
            ];
        }

        $topProducts = Product::orderBy('stock', 'desc')->take(5)->get();

        return view('dashboard.reports.index', compact('stats', 'chart', 'topProducts'));
    }
}

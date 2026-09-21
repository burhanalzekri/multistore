<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shop;
use App\Models\SmsInbox;

class DashboardController extends Controller {
    public function index() {
        return view('dashboard.index', [
            'stats' => [
                'sales'    => Order::where('payment_status', 'confirmed')->sum('total'),
                'orders'   => Order::count(),
                'pending'  => Order::where('status', 'awaiting_payment')->count(),
                'sms'      => SmsInbox::count(),
            ],
            'recentOrders' => Order::latest()->take(5)->get(),
            'smsList'      => SmsInbox::latest()->take(5)->get(),
            'shops'        => Shop::latest()->take(10)->get(),
        ]);
    }
}

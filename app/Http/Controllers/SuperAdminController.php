<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SmsInbox;
use App\Models\User;

class SuperAdminController extends Controller
{
    public function index()
    {
        $shops = Shop::withCount(['products', 'orders', 'users'])->get();

        $stats = [
            'total_shops' => Shop::count(),
            'active_shops' => Shop::where('status', 'active')->count(),
            'total_users' => User::count(),
            'total_products' => Product::withoutGlobalScope('tenant')->count(),
            'total_orders' => Order::withoutGlobalScope('tenant')->count(),
            'total_sales' => Order::withoutGlobalScope('tenant')->where('payment_status', 'confirmed')->sum('total'),
            'total_sms' => SmsInbox::withoutGlobalScope('tenant')->count(),
        ];

        return view('super-admin.index', compact('shops', 'stats'));
    }

    public function toggleStatus(Shop $shop)
    {
        $shop->update([
            'status' => $shop->status === 'active' ? 'suspended' : 'active'
        ]);
        return back()->with('success', 'تم تحديث حالة المتجر');
    }

    public function show(Shop $shop)
    {
        $stats = [
            'products' => Product::withoutGlobalScope('tenant')->where('shop_id', $shop->id)->count(),
            'orders' => Order::withoutGlobalScope('tenant')->where('shop_id', $shop->id)->count(),
            'sales' => Order::withoutGlobalScope('tenant')->where('shop_id', $shop->id)->where('payment_status', 'confirmed')->sum('total'),
            'sms' => SmsInbox::withoutGlobalScope('tenant')->where('shop_id', $shop->id)->count(),
        ];

        $users = User::where('shop_id', $shop->id)->get();

        return view('super-admin.show', compact('shop', 'stats', 'users'));
    }
}

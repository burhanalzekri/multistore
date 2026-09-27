<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shop;
use App\Models\PaymentWallet;

class InvoiceController extends Controller
{
    public function show($id)
    {
        $order = Order::withoutGlobalScope('tenant')->with('items')->findOrFail($id);
        $shop = Shop::find($order->shop_id);
        $wallet = PaymentWallet::withoutGlobalScope('tenant')
            ->where('shop_id', $order->shop_id)
            ->where('is_active', true)
            ->first();

        return view('invoices.order', compact('order', 'shop', 'wallet'));
    }
}

<?php
namespace App\Http\Controllers;

use App\Mail\OrderConfirmed;
use App\Models\Order;
use App\Models\Shop;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    public function send()
    {
        $order = Order::withoutGlobalScope('tenant')->with('items')->latest()->first();
        if (!$order) {
            return 'لا يوجد طلبات';
        }

        $shop = Shop::find($order->shop_id);
        $to = request('to', 'burhanalzekri77@gmail.com');

        try {
            Mail::to($to)->send(new OrderConfirmed($order, $shop));
            return '✅ تم إرسال الإيميل إلى ' . $to;
        } catch (\Exception $e) {
            return '❌ فشل: ' . $e->getMessage();
        }
    }
}

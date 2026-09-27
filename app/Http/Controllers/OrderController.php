<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(20);

        return view('dashboard.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('dashboard.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:awaiting_payment,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $data['status'];

        $order->update(['status' => $newStatus]);

        // 📱 إشعارات تغيير الحالة
        if ($oldStatus !== $newStatus) {
            try {
                $shop = \App\Models\Shop::find($order->shop_id);
                $notifier = app(\App\Services\Notifications\NotificationService::class);

                if ($shop) {
                    match ($newStatus) {
                        'shipped' => $notifier->orderShipped($order, $shop, $order->tracking_number),
                        'delivered' => $notifier->orderDelivered($order, $shop),
                        'cancelled' => $notifier->orderCancelled($order, $shop),
                        default => null,
                    };
                }
            } catch (\Throwable $e) {
                \Log::warning('Status notification failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'تم تحديث حالة الطلب');
    }
}

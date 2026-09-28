<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Services\Sms\SmsSender;
use App\Services\Sms\SmsTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderTrackingController extends Controller
{
    // ═══ صفحة البحث العامة ═══
    public function publicTrackForm()
    {
        return view('storefront.track-form');
    }

    // ═══ البحث ═══
    public function search(Request $request)
    {
        $q = trim($request->input('query', ''));
        if (strlen($q) < 4) {
            return back()->with('error', 'أدخل 4 أحرف على الأقل')->withInput();
        }

        $orders = Order::withoutGlobalScope('tenant')
            ->where(function ($query) use ($q) {
                $query->where('order_number', 'LIKE', "%{$q}%")
                    ->orWhere('customer_phone', 'LIKE', "%{$q}%");
            })
            ->latest()
            ->take(10)
            ->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'لم نجد طلبًا بهذا الرقم')->withInput();
        }

        if ($orders->count() === 1) {
            return redirect('/track/' . $orders->first()->order_number);
        }

        return view('storefront.track-results', compact('orders', 'q'));
    }

    // ═══ عرض الطلب ═══
    public function show($orderNumber)
    {
        $order = Order::withoutGlobalScope('tenant')
            ->where('order_number', $orderNumber)
            ->with(['items', 'statusHistory', 'shop'])
            ->firstOrFail();

        return view('storefront.track-show', compact('order'));
    }

    // ═══ تحويل الحالة ═══
    public function updateStatus(Request $request, Order $order)
    {
        $user = Auth::user();

        // التحقق من الصلاحية
        $newStatus = $request->input('status');
        $permMap = [
            'awaiting_payment' => 'orders.edit',
            'processing' => 'orders.edit',
            'shipped' => 'orders.ship',
            'delivered' => 'orders.deliver',
            'cancelled' => 'orders.cancel',
        ];

        $requiredPerm = $permMap[$newStatus] ?? 'orders.edit';

        if ($user->role === 'staff') {
            $perms = $user->permissions ?? [];
            if (!in_array($requiredPerm, $perms) && !in_array('orders.edit', $perms)) {
                abort(403, 'ليس لديك صلاحية تغيير الحالة إلى ' . $newStatus);
            }
        }

        $data = $request->validate([
            'status' => 'required|in:awaiting_payment,processing,shipped,delivered,cancelled',
            'note' => 'nullable|string|max:500',
            'tracking_number' => 'nullable|string|max:100',
            'carrier' => 'nullable|string|max:100',
        ]);

        $oldStatus = $order->status;
        if ($oldStatus === $data['status']) {
            return back()->with('info', 'الحالة نفسها');
        }

        $updates = ['status' => $data['status']];

        if ($data['status'] === 'shipped') {
            $updates['shipped_at'] = now();
            if ($data['tracking_number']) $updates['tracking_number'] = $data['tracking_number'];
            if ($data['carrier']) $updates['carrier'] = $data['carrier'];
        }

        if ($data['status'] === 'delivered') {
            $updates['delivered_at'] = now();
        }

        $order->update($updates);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'shop_id' => $order->shop_id,
            'from_status' => $oldStatus,
            'to_status' => $data['status'],
            'note' => $data['note'] ?? null,
            'changed_by' => Auth::id(),
            'changed_by_name' => Auth::user()->name,
        ]);

        // SMS للعميل
        $this->notifyCustomer($order, $data['status'], $data['note'] ?? null);

        return back()->with('success', '✅ تم تحديث الحالة');
    }

    private function notifyCustomer(Order $order, $status, $note)
    {
        // ربط حالة الطلب بمفتاح الحدث في القوالب
        $eventMap = [
            'processing' => 'order_processing',
            'shipped' => 'order_shipped',
            'delivered' => 'order_delivered',
            'cancelled' => 'order_cancelled',
            'confirmed' => 'order_confirmed',
        ];

        if (!isset($eventMap[$status])) return;

        $eventKey = $eventMap[$status];
        $shopId = $order->shop_id;

        // استخدام قالب المتجر
        $svc = app(SmsTemplateService::class);
        $vars = $svc->varsFromOrder($order);
        $message = $svc->build($shopId, $eventKey, $vars);

        if ($message === null || $message === '') return;

        // إضافة الملاحظة إن وُجدت
        if ($note && in_array($status, ['shipped', 'processing'], true)) {
            $message .= " — {$note}";
        }

        try {
            app(SmsSender::class)->send($order->customer_phone, $message);
        } catch (\Exception $e) {
            \Log::error('SMS: ' . $e->getMessage());
        }
    }
}

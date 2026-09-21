<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SmsInbox;
use App\Services\Payment\PaymentMatcher;
use App\Services\Tenant\TenantManager;
use Illuminate\Http\Request;

class SmsInboxController extends Controller
{
    public function index(Request $request)
    {
        $query = SmsInbox::latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $smsList = $query->paginate(20);

        return view('dashboard.sms.index', compact('smsList'));
    }

    public function show(SmsInbox $sms)
    {
        $orders = Order::whereIn('status', ['awaiting_payment', 'processing'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.sms.show', compact('sms', 'orders'));
    }

    public function confirm(Request $request, SmsInbox $sms)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::find($data['order_id']);

        if ($order->payment_status === 'confirmed') {
            return back()->with('error', 'هذا الطلب مؤكد مسبقًا');
        }

        app(TenantManager::class)->set($order->shop);

        $matcher = app(PaymentMatcher::class);
        $matcher->confirm($order, $sms, [
            'provider' => $sms->provider ?? 'manual',
            'amount' => $sms->parsed_amount,
            'sender' => $sms->parsed_sender,
            'reference' => $sms->parsed_reference,
        ], 'manual');

        return redirect('/dashboard/sms')->with('success', 'تم تأكيد الدفع يدويًا');
    }

    public function reject(SmsInbox $sms)
    {
        $sms->update(['status' => 'rejected']);
        return back()->with('success', 'تم رفض الرسالة');
    }
}

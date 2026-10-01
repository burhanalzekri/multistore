<?php
namespace App\Services\Payment;

use App\Jobs\NotifyPaymentConfirmed;
use App\Mail\OrderConfirmed;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\SmsInbox;

class PaymentMatcher {
    public function match(SmsInbox $sms, array $p): void {
        $candidates = Order::withoutGlobalScope('tenant')
            ->where('shop_id', $sms->shop_id)
            ->where('status', 'awaiting_payment')
            ->whereBetween('total', [$p['amount'] - 2, $p['amount'] + 2])
            ->where('created_at', '>=', now()->subHour())
            ->get();

        if ($candidates->isEmpty()) {
            $sms->update(['status' => 'review', 'confidence' => 0]);
            return;
        }

        $scored = $candidates->map(function ($o) use ($p) {
            $s = 0;
            if ((float)$o->total === (float)$p['amount']) $s += 50;
            if ($o->customer_phone === $p['sender']) $s += 30;
            if ($o->payment_reference && $o->payment_reference === $p['reference']) $s += 40;
            $s += max(0, 20 - $o->created_at->diffInMinutes(now()) / 3);
            return ['o' => $o, 'score' => (int) $s];
        })->sortByDesc('score');

        $best = $scored->first();

        if ($best['score'] >= 80) {
            $this->confirm($best['o'], $sms, $p, 'auto');
        } else {
            $sms->update([
                'status' => 'review',
                'confidence' => $best['score'],
                'matched_order_id' => $best['o']->id,
            ]);
        }
    }

    public function confirm(Order $order, SmsInbox $sms, array $p, string $by): void {
        if (!empty($p['reference']) &&
            PaymentTransaction::where('reference_number', $p['reference'])->exists()) {
            $sms->update(['status' => 'rejected']);
            return;
        }

        PaymentTransaction::create([
            'shop_id' => $order->shop_id,
            'order_id' => $order->id,
            'sms_inbox_id' => $sms->id,
            'provider' => $p['provider'],
            'amount' => $p['amount'],
            'sender_phone' => $p['sender'],
            'reference_number' => $p['reference'] ?? null,
            'status' => 'confirmed',
            'verified_by' => $by,
            'verified_at' => now(),
        ]);

        $order->update([
            'payment_status' => 'confirmed',
            'status' => 'processing',
            'paid_at' => now(),
        ]);

        $sms->update([
            'status' => 'matched',
            'confidence' => 100,
            'matched_order_id' => $order->id,
        ]);

        NotifyPaymentConfirmed::dispatch($order);

        // إرسال الإيميل إن وُجد
        if ($order->customer_phone) {
            try {
                $shop = \App\Models\Shop::find($order->shop_id);
                if ($shop && $shop->users()->first()?->email) {
                    // الإيميل للعميل لو كان مسجّلًا
                }
            } catch (\Exception $e) {
                \Log::error("Email failed: " . $e->getMessage());
            }
        }
    }
}

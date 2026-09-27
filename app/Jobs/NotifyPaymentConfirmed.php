<?php
namespace App\Jobs;

use App\Mail\OrderConfirmed;
use App\Models\Order;
use App\Models\Shop;
use App\Services\Mail\TenantMailer;
use App\Services\Sms\SmsSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyPaymentConfirmed implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function handle(SmsSender $sms): void
    {
        $shop = Shop::find($this->order->shop_id);
        $trackUrl = url('/track/' . $this->order->order_number);

        // 1. SMS للعميل
        $sms->send(
            $this->order->customer_phone,
            "✅ تم تأكيد دفعتك للطلب {$this->order->order_number}\nتتبعه: {$trackUrl}"
        );

        // 2. البريد الإلكتروني (باستخدام SMTP المتجر)
        if ($this->order->customer_email ?? null) {
            try {
                // ⭐ اضبط SMTP المتجر
                TenantMailer::configure($shop);

                Mail::to($this->order->customer_email)
                    ->send(new OrderConfirmed($this->order, $shop));

                \Log::info("✅ Email sent via {$shop->name} SMTP to {$this->order->customer_email}");
            } catch (\Exception $e) {
                \Log::error('Email failed: ' . $e->getMessage());
            }
        }

        // 3. إشعار للمدير
        if ($admin = $shop->users()->where('role', 'shop_admin')->first()) {
            $sms->send($admin->phone, "🔔 طلب مدفوع: {$this->order->order_number}");
        }
    }
}

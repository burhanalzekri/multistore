<?php
namespace App\Jobs;

use App\Models\Order;
use App\Services\Sms\SmsSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifyPaymentConfirmed implements ShouldQueue {
    use Dispatchable, Queueable, SerializesModels;
    public function __construct(public Order $order) {}
    public function handle(SmsSender $sms): void {
        $sms->send(
            $this->order->customer_phone,
            "تم تأكيد دفعتك للطلب {$this->order->order_number}. شكرًا لك!"
        );
    }
}

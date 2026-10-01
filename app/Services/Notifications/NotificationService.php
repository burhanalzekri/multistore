<?php
namespace App\Services\Notifications;

use App\Mail\OrderConfirmed;
use App\Models\AdminNotification;
use App\Models\Order;
use App\Models\Shop;
use App\Services\Sms\SmsSender;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    protected SmsSender $sms;

    public function __construct(SmsSender $sms)
    {
        $this->sms = $sms;
    }

    /**
     * 📦 إشعارات عند إنشاء طلب جديد
     */
    public function orderPlaced(Order $order, Shop $shop): void
    {
        // 1) SMS للعميل
        $this->sendSmsToCustomer(
            $order,
            "✅ تم استلام طلبك {$order->order_number} بنجاح. الإجمالي: " . number_format($order->total) . " ر.ي. سنتواصل معك قريباً."
        );

        // 2) SMS للتاجر (رقم المتجر)
        if ($shop->phone) {
            $this->sendSmsToShop(
                $shop,
                "🔔 طلب جديد {$order->order_number} من {$order->customer_name}. الإجمالي: " . number_format($order->total) . " ر.ي"
            );
        }

        // 3) إشعار داخلي للتاجر
        $this->adminNotification(
            $shop->id,
            'order',
            '🛒 طلب جديد',
            "{$order->customer_name} — " . number_format($order->total) . " ر.ي",
            '/dashboard/orders/' . $order->id
        );

        // 4) إيميل للعميل (إن وُجد)
        if ($order->customer_email ?? false) {
            $this->sendEmail($order->customer_email, new OrderConfirmed($order, $shop));
        }

        // 5) Push Notification للتاجر
        $this->sendPushToShop(
            $shop->id,
            '🛒 طلب جديد',
            "{$order->customer_name} — " . number_format($order->total) . ' ر.ي',
            '/dashboard/orders/' . $order->id
        );

        // 6) Push للعميل (إن كان مسجّلاً)
        if (!empty($order->user_id)) {
            $this->sendPushToUser(
                $order->user_id,
                '✅ تم استلام طلبك',
                "{$order->order_number} — " . number_format($order->total) . ' ر.ي',
                '/order-success/' . $order->id
            );
        }
    }

    /**
     * 💰 إشعار عند تأكيد الدفع
     */
    public function paymentConfirmed(Order $order, Shop $shop): void
    {
        $this->sendSmsToCustomer(
            $order,
            "💰 تم تأكيد دفعة طلبك {$order->order_number}. سنبدأ بتجهيزه قريباً."
        );

        $this->adminNotification(
            $shop->id,
            'payment',
            '💰 تم تأكيد دفع',
            "طلب {$order->order_number} — " . number_format($order->total) . " ر.ي",
            '/dashboard/orders/' . $order->id
        );
    }

    /**
     * 🚚 إشعار عند الشحن
     */
    public function orderShipped(Order $order, Shop $shop, ?string $trackingNumber = null): void
    {
        $msg = "🚚 تم شحن طلبك {$order->order_number}.";
        if ($trackingNumber) {
            $msg .= " رقم التتبع: {$trackingNumber}.";
        }
        $msg .= " شكراً لثقتك بنا.";

        $this->sendSmsToCustomer($order, $msg);

        $this->adminNotification(
            $shop->id,
            'shipping',
            '🚚 تم الشحن',
            "طلب {$order->order_number}",
            '/dashboard/orders/' . $order->id
        );

        if (!empty($order->user_id)) {
            $this->sendPushToUser(
                $order->user_id,
                '🚚 طلبك في الطريق',
                "{$order->order_number} تم شحنه" . ($trackingNumber ? " — تتبع: {$trackingNumber}" : ''),
                '/track-order?number=' . urlencode($order->order_number)
            );
        }
    }

    /**
     * ✅ إشعار عند التسليم
     */
    public function orderDelivered(Order $order, Shop $shop): void
    {
        $this->sendSmsToCustomer(
            $order,
            "✅ تم تسليم طلبك {$order->order_number}. نتمنى أن تكون التجربة ممتازة! شاركنا تقييمك."
        );

        $this->adminNotification(
            $shop->id,
            'delivered',
            '✅ تم التسليم',
            "طلب {$order->order_number}",
            '/dashboard/orders/' . $order->id
        );

        if (!empty($order->user_id)) {
            $this->sendPushToUser(
                $order->user_id,
                '✅ تم التسليم',
                "نتمنى أن تكون التجربة ممتازة!",
                '/product/0'
            );
        }
    }

    /**
     * ❌ إشعار عند إلغاء الطلب
     */
    public function orderCancelled(Order $order, Shop $shop, ?string $reason = null): void
    {
        $msg = "❌ تم إلغاء طلبك {$order->order_number}.";
        if ($reason) $msg .= " السبب: {$reason}";
        $msg .= " للاستفسار تواصل معنا.";

        $this->sendSmsToCustomer($order, $msg);

        $this->adminNotification(
            $shop->id,
            'cancelled',
            '❌ إلغاء طلب',
            "طلب {$order->order_number}",
            '/dashboard/orders/' . $order->id
        );
    }

    /**
     * 📉 إشعار انخفاض سعر (للتنبيهات)
     */
    public function priceDropped(\App\Models\PriceAlert $alert, \App\Models\Product $product): void
    {
        $msg = "📉 انخفض سعر \"{$product->name}\" إلى " . number_format($product->price) . " ر.ي!";
        
        if ($alert->phone) {
            try {
                $this->sms->send($alert->phone, $msg);
            } catch (\Throwable $e) {
                Log::warning('Price alert SMS failed: ' . $e->getMessage());
            }
        }
        if ($alert->email) {
            // يمكن إضافة إيميل مخصص لاحقاً
            Log::info("Price alert email: {$alert->email} — {$msg}");
        }

        $alert->update(['notified' => true]);
    }

    /**
     * 🎁 إشعار عند استبدال نقاط الولاء
     */
    public function pointsRedeemed(int $userId, int $points, float $value): void
    {
        $user = \App\Models\User::find($userId);
        if (!$user) return;

        $msg = "🎁 تم استبدال {$points} نقطة ولاء بقيمة " . number_format($value) . " ر.ي. رصيدك الجديد انخفض.";

        try {
            $tg = new \App\Services\Telegram\TelegramSender();
            $tg->send("👤 <b>{$user->name}</b>\n\n" . $msg);
        } catch (\Throwable $e) {
            Log::warning('Loyalty Telegram failed: ' . $e->getMessage());
        }

        if ($user->phone) {
            try {
                $this->sms->send($user->phone, $msg);
            } catch (\Throwable $e) {
                Log::warning('Loyalty SMS failed: ' . $e->getMessage());
            }
        }
    }

    // ═══════════════════════════════════════
    // مساعدات داخلية
    // ═══════════════════════════════════════

    protected function sendSmsToCustomer(Order $order, string $message): void
    {
        // ✈️ Telegram (إن وُجد)
        try {
            $tg = new \App\Services\Telegram\TelegramSender();
            $tg->send("📦 <b>طلب {$order->order_number}</b>\n\n" . $message . "\n\n👤 {$order->customer_name}");
        } catch (\Throwable $e) {
            Log::warning('Telegram failed: ' . $e->getMessage());
        }

        // 📱 SMS (إن وُجد رقم)
        if (!$order->customer_phone) return;
        try {
            $this->sms->send($order->customer_phone, $message);
            Log::info("📱 SMS → {$order->customer_phone}: {$message}");
        } catch (\Throwable $e) {
            Log::warning('Customer SMS failed: ' . $e->getMessage());
        }
    }

    protected function sendSmsToShop(Shop $shop, string $message): void
    {
        // ✈️ Telegram للتاجر
        try {
            $tg = new \App\Services\Telegram\TelegramSender();
            $tg->send("🏪 <b>{$shop->name}</b>\n\n" . $message);
        } catch (\Throwable $e) {
            Log::warning('Shop Telegram failed: ' . $e->getMessage());
        }

        // 📱 SMS (إن وُجد رقم)
        if (!$shop->phone) return;
        try {
            $this->sms->send($shop->phone, $message);
        } catch (\Throwable $e) {
            Log::warning('Shop SMS failed: ' . $e->getMessage());
        }
    }

    protected function sendEmail(string $email, $mailable): void
    {
        try {
            Mail::to($email)->send($mailable);
        } catch (\Throwable $e) {
            Log::warning('Email failed: ' . $e->getMessage());
        }
    }

    protected function adminNotification(
        int $shopId,
        string $type,
        string $title,
        string $body,
        ?string $link = null
    ): void {
        try {
            AdminNotification::create([
                'type' => $type,
                'title' => $title,
                'message' => $body,
                'data' => [
                    'shop_id' => $shopId,
                    'link' => $link,
                ],
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Admin notification failed: ' . $e->getMessage());
        }
    }

    protected function sendPushToShop(int $shopId, string $title, string $body, ?string $url = null): void
    {
        try {
            app(\App\Services\Push\WebPushService::class)
                ->send($title, $body, $url, null, null, $shopId);
        } catch (\Throwable $e) {
            \Log::warning('Push to shop failed: ' . $e->getMessage());
        }
    }

    protected function sendPushToUser(int $userId, string $title, string $body, ?string $url = null): void
    {
        try {
            app(\App\Services\Push\WebPushService::class)
                ->send($title, $body, $url, null, $userId, null);
        } catch (\Throwable $e) {
            \Log::warning('Push to user failed: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Services\Sms;

use App\Models\Order;
use App\Models\SmsTemplate;

class SmsTemplateService
{
    public const EVENTS = [
        "order_confirmed" => [
            "label" => "تأكيد الطلب",
            "default" => "✅ تم استلام طلبك {order_number} بمبلغ {total} ر.ي. سنتواصل معك قريباً.",
        ],
        "order_processing" => [
            "label" => "قيد التحضير",
            "default" => "🔄 طلبك {order_number} قيد التحضير.",
        ],
        "order_shipped" => [
            "label" => "تم الشحن",
            "default" => "📦 تم شحن طلبك {order_number}. شكراً لثقتك!",
        ],
        "order_delivered" => [
            "label" => "تم التوصيل",
            "default" => "✅ تم توصيل طلبك {order_number} — شكراً!",
        ],
        "order_cancelled" => [
            "label" => "إلغاء الطلب",
            "default" => "❌ تم إلغاء طلبك {order_number}.",
        ],
        "payment_confirmed" => [
            "label" => "تأكيد الدفع",
            "default" => "💳 تم استلام دفعتك بمبلغ {total} ر.ي لطلب {order_number}.",
        ],
        "loyalty_points" => [
            "label" => "نقاط الولاء",
            "default" => "🎁 رصيدك من نقاط الولاء: {points} نقطة.",
        ],
    ];

    public function get(int $shopId, string $eventKey): ?SmsTemplate
    {
        return SmsTemplate::where("shop_id", $shopId)
            ->where("event_key", $eventKey)
            ->first();
    }

    public function body(int $shopId, string $eventKey): string
    {
        $tpl = $this->get($shopId, $eventKey);
        if ($tpl && $tpl->is_active) {
            return $tpl->body;
        }
        return self::EVENTS[$eventKey]["default"] ?? "";
    }

    public function render(string $template, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $template = str_replace("{" . $key . "}", (string) $value, $template);
        }
        return $template;
    }

    public function build(int $shopId, string $eventKey, array $vars): ?string
    {
        $body = $this->body($shopId, $eventKey);
        if ($body === "") return null;
        return $this->render($body, $vars);
    }

    public function varsFromOrder(Order $order): array
    {
        return [
            "order_number"   => $order->order_number ?? "",
            "customer_name"  => $order->customer_name ?? "",
            "customer_phone" => $order->customer_phone ?? "",
            "total"          => number_format((float) ($order->total ?? 0)),
            "shop_name"      => $order->shop->name ?? "",
            "status"         => $order->status ?? "",
        ];
    }

    public function isCustom(int $shopId, string $eventKey): bool
    {
        return $this->get($shopId, $eventKey) !== null;
    }

    public static function availableVars(): array
    {
        return [
            "order_number"   => "رقم الطلب",
            "customer_name"  => "اسم العميل",
            "customer_phone" => "رقم العميل",
            "total"          => "الإجمالي",
            "shop_name"      => "اسم المتجر",
            "status"         => "الحالة",
            "points"         => "النقاط (لنقاط الولاء فقط)",
        ];
    }
}

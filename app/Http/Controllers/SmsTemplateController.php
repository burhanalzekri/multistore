<?php

namespace App\Http\Controllers;

use App\Models\SmsTemplate;
use App\Models\Shop;
use App\Services\Sms\SmsTemplateService;
use Illuminate\Http\Request;

class SmsTemplateController extends Controller
{
    public function index()
    {
        $shop = $this->currentShop();
        $templates = SmsTemplate::where("shop_id", $shop->id)
            ->get()
            ->keyBy("event_key");

        return view("dashboard.sms-templates.index", [
            "shop" => $shop,
            "events" => SmsTemplateService::EVENTS,
            "templates" => $templates,
            "vars" => SmsTemplateService::availableVars(),
        ]);
    }

    public function update(Request $request, string $eventKey)
    {
        $shop = $this->currentShop();

        if (!array_key_exists($eventKey, SmsTemplateService::EVENTS)) {
            abort(404, "حدث غير مدعوم");
        }

        $data = $request->validate([
            "body" => "required|string|max:500",
            "is_active" => "nullable|boolean",
        ]);

        SmsTemplate::updateOrCreate(
            ["shop_id" => $shop->id, "event_key" => $eventKey],
            [
                "body" => $data["body"],
                "is_active" => $request->boolean("is_active", true),
            ]
        );

        return back()->with("success", "✅ تم حفظ القالب");
    }

    public function reset(string $eventKey)
    {
        $shop = $this->currentShop();

        SmsTemplate::where("shop_id", $shop->id)
            ->where("event_key", $eventKey)
            ->delete();

        return back()->with("success", "🔄 تم استعادة القالب الافتراضي");
    }

    public function preview(Request $request)
    {
        $data = $request->validate([
            "body" => "required|string|max:500",
        ]);

        $svc = new SmsTemplateService();
        $sample = [
            "order_number"   => "ORD-1024",
            "customer_name"  => "محمد أحمد",
            "customer_phone" => "777123456",
            "total"          => "24,850",
            "shop_name"      => $this->currentShop()->name,
            "status"         => "shipped",
            "points"         => "150",
        ];

        return response()->json([
            "result" => $svc->render($data["body"], $sample),
        ]);
    }

    protected function currentShop(): Shop
    {
        $shop = auth()->user()?->shop
            ?? app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();

        if (!$shop) {
            abort(403, "لا يوجد متجر مرتبط بحسابك");
        }

        return $shop;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SmsInbox;
use App\Models\SmsLog;
use App\Models\SmsTemplate;
use App\Services\Sms\SmsSender;
use App\Services\Sms\SmsTemplateService;
use Illuminate\Http\Request;

class SmsCenterController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get("tab", "inbox");
        if (!in_array($tab, ["inbox", "logs", "templates"], true)) {
            $tab = "inbox";
        }

        $shop = $this->currentShop();

        // ═══ شارات النافذة الجانبية ═══
        $inboxCount = SmsInbox::where("status", "review")->count();
        $logsCount = SmsLog::whereDate("created_at", today())->count();
        $templatesCount = SmsTemplate::where("shop_id", $shop->id)
            ->where("is_active", true)
            ->count();

        // ═══ بيانات التبويب النشط ═══
        $data = [
            "tab" => $tab,
            "shop" => $shop,
            "inboxCount" => $inboxCount,
            "logsCount" => $logsCount,
            "templatesCount" => $templatesCount,
        ];

        if ($tab === "inbox") {
            $q = SmsInbox::latest();
            if ($request->status) {
                $q->where("status", $request->status);
            }
            $data["smsList"] = $q->paginate(20)->withQueryString();
        }

        if ($tab === "logs") {
            $q = SmsLog::query()->latest();
            if ($s = $request->get("status")) $q->where("status", $s);
            if ($s = $request->get("q")) {
                $q->where(function ($x) use ($s) {
                    $x->where("to", "like", "%{$s}%")
                      ->orWhere("message", "like", "%{$s}%");
                });
            }
            $data["logs"] = $q->paginate(30)->withQueryString();
            $data["stats"] = (new SmsSender())->stats();
            $data["provider"] = config("services.sms.provider", "log");
        }

        if ($tab === "templates") {
            $data["events"] = SmsTemplateService::EVENTS;
            $data["templates"] = SmsTemplate::where("shop_id", $shop->id)
                ->get()
                ->keyBy("event_key");
            $data["vars"] = SmsTemplateService::availableVars();
        }

        return view("dashboard.sms-center.index", $data);
    }

    protected function currentShop()
    {
        $shop = auth()->user()?->shop
            ?? app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();

        if (!$shop) abort(403, "لا يوجد متجر");
        return $shop;
    }
}

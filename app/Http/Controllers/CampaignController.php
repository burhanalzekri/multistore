<?php

namespace App\Http\Controllers;

use App\Models\EmailCampaign;
use App\Models\EmailCampaignRecipient;
use App\Models\User;
use App\Models\LoyaltyPoint;
use App\Models\Order;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    // ═══════════════════════════════════════════
    // 📋 قائمة الحملات
    // ═══════════════════════════════════════════
    public function index(\Illuminate\Http\Request $request)
    {
        $shopId = $this->currentShopId();

        $query = EmailCampaign::where('shop_id', $shopId);

        // ═══ فلتر الفترة ═══
        $period = $request->get('period', 'all');
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'yesterday':
                $query->whereDate('created_at', today()->subDay());
                break;
            case 'week':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case 'month':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case 'year':
                $query->where('created_at', '>=', now()->subYear());
                break;
            case 'custom':
                if ($request->filled('from')) {
                    $query->where('created_at', '>=', $request->from);
                }
                if ($request->filled('to')) {
                    $query->where('created_at', '<=', $request->to . ' 23:59:59');
                }
                break;
        }

        // ═══ فلتر الحالة ═══
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // جلب الحملات
        $campaigns = $query->latest()->paginate(15)->withQueryString();

        // إذا لا توجد بـ shop_id، نعرض الجميع (fallback)
        if ($campaigns->total() === 0 && $period === 'all' && !$request->get('status')) {
            $campaigns = EmailCampaign::latest()->paginate(15);
        }

        // الإحصائيات
        $baseQuery = EmailCampaign::where('shop_id', $shopId);
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'sent' => (clone $baseQuery)->where('status', 'sent')->count(),
            'draft' => (clone $baseQuery)->where('status', 'draft')->count(),
            'total_sent_emails' => (clone $baseQuery)->sum('sent_count'),
        ];

        return view('dashboard.campaigns.index', compact('campaigns', 'stats', 'period'));
    }

    // ═══════════════════════════════════════════
    // 📝 صفحة الإنشاء
    // ═══════════════════════════════════════════
    public function create()
    {
        $shopId = $this->currentShopId();

        $customersCount = User::where('shop_id', $shopId)->count();
        $tiersCount = [
            'bronze' => 0, 'silver' => 0, 'gold' => 0, 'platinum' => 0,
        ];

        return view('dashboard.campaigns.create', compact('customersCount', 'tiersCount'));
    }

    // ═══════════════════════════════════════════
    // 💾 حفظ الحملة
    // ═══════════════════════════════════════════
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'subject' => 'required|string|max:200',
            'body' => 'required|string',
            'template' => 'nullable|string|max:50',
            'target_type' => 'required|in:all,tier,orders,points',
            'target_value' => 'nullable|string|max:100',
            'scheduled_at' => 'nullable|date',
            'action' => 'required|in:draft,send_now,schedule',
        ]);

        $shopId = $this->currentShopId();

        $campaign = EmailCampaign::create([
            'shop_id' => $shopId,
            'name' => $data['name'],
            'subject' => $data['subject'],
            'body' => $data['body'],
            'template' => $data['template'] ?? 'default',
            'target_type' => $data['target_type'],
            'target_value' => $data['target_value'] ?? null,
            'status' => 'draft',
            'scheduled_at' => $data['scheduled_at'] ?? null,
        ]);

        // تحديد المستلمين
        $recipients = $this->buildRecipientsList($campaign, $shopId);
        $campaign->update(['recipients_count' => count($recipients)]);

        // إنشاء سجلات المستلمين
        foreach ($recipients as $r) {
            EmailCampaignRecipient::create([
                'campaign_id' => $campaign->id,
                'user_id' => $r['user_id'] ?? null,
                'email' => $r['email'],
                'name' => $r['name'] ?? null,
                'status' => 'pending',
            ]);
        }

        // تنفيذ الإجراء
        if ($data['action'] === 'send_now') {
            $campaign->update(['status' => 'sending']);
            // إرسال مباشر (اختبار)
            $this->sendCampaignNow($campaign);
            return redirect('/dashboard/campaigns/' . $campaign->id)->with('success', '✅ تم إرسال الحملة');
        }

        if ($data['action'] === 'schedule') {
            $campaign->update(['status' => 'scheduled']);
            return redirect('/dashboard/campaigns')->with('success', '📅 تم جدولة الحملة');
        }

        return redirect('/dashboard/campaigns/' . $campaign->id)->with('success', '💾 تم حفظ الحملة كمسودة');
    }

    // ═══════════════════════════════════════════
    // 👁️ تفاصيل الحملة
    // ═══════════════════════════════════════════
    public function show(EmailCampaign $campaign)
    {
        $this->authorizeShop($campaign);

        $recipients = $campaign->recipients()->latest()->paginate(30);

        $stats = [
            'total' => $campaign->recipients_count,
            'sent' => $campaign->sent_count,
            'failed' => $campaign->failed_count,
            'pending' => $campaign->recipients()->where('status', 'pending')->count(),
            'opens' => $campaign->opens_count,
            'clicks' => $campaign->clicks_count,
        ];

        return view('dashboard.campaigns.show', compact('campaign', 'recipients', 'stats'));
    }

    // ═══════════════════════════════════════════
    // 🚀 إرسال الحملة يدوياً
    // ═══════════════════════════════════════════
    public function send(EmailCampaign $campaign)
    {
        $this->authorizeShop($campaign);

        if ($campaign->status === 'sent') {
            return back()->with('error', '⚠️ تم إرسال الحملة مسبقاً');
        }

        $campaign->update(['status' => 'sending']);
        $this->sendCampaignNow($campaign);

        return back()->with('success', '✅ تم إرسال الحملة');
    }

    // ═══════════════════════════════════════════
    // 🗑️ حذف الحملة
    // ═══════════════════════════════════════════
    public function destroy(EmailCampaign $campaign)
    {
        $this->authorizeShop($campaign);
        $campaign->recipients()->delete();
        $campaign->delete();
        return redirect('/dashboard/campaigns')->with('success', '🗑️ تم حذف الحملة');
    }

    // ═══════════════════════════════════════════
    // 🛡️ Helpers
    // ═══════════════════════════════════════════
    protected function currentShopId(): int
    {
        // 1) حاول من TenantManager
        try {
            $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
            if ($shop && $shop->id) return (int) $shop->id;
        } catch (\Throwable $e) {}

        // 2) حاول من المستخدم الحالي
        $user = auth()->user();
        if ($user && $user->shop_id) return (int) $user->shop_id;

        // 3) آخر حل: أول متجر
        $firstShop = \App\Models\Shop::first();
        return $firstShop ? (int) $firstShop->id : 1;
    }

    protected function authorizeShop(EmailCampaign $campaign): void
    {
        if ((int) $campaign->shop_id !== (int) $this->currentShopId()) {
            abort(403);
        }
    }

    // ═══════════════════════════════════════════
    // 🎯 بناء قائمة المستلمين حسب الاستهداف
    // ═══════════════════════════════════════════
    protected function buildRecipientsList(EmailCampaign $campaign, int $shopId): array
    {
        $recipients = [];
        $query = User::where('shop_id', $shopId);

        switch ($campaign->target_type) {
            case 'tier':
                $tier = $campaign->target_value;
                $tiers = \App\Services\Loyalty\LoyaltyService::TIERS;
                $minPoints = $tiers[$tier]['min'] ?? 0;
                $userIds = LoyaltyPoint::where('shop_id', $shopId)
                    ->where('balance', '>=', $minPoints)
                    ->pluck('user_id')
                    ->toArray();
                $query->whereIn('id', $userIds);
                break;

            case 'orders':
                $minOrders = (int) ($campaign->target_value ?? 1);
                $userIds = Order::where('shop_id', $shopId)
                    ->selectRaw('user_id, COUNT(*) as cnt')
                    ->whereNotNull('user_id')
                    ->groupBy('user_id')
                    ->havingRaw('COUNT(*) >= ?', [$minOrders])
                    ->pluck('user_id')
                    ->toArray();
                $query->whereIn('id', $userIds);
                break;

            case 'points':
                $minPoints = (int) ($campaign->target_value ?? 100);
                $userIds = LoyaltyPoint::where('shop_id', $shopId)
                    ->where('balance', '>=', $minPoints)
                    ->pluck('user_id')
                    ->toArray();
                $query->whereIn('id', $userIds);
                break;

            case 'all':
            default:
                // كل العملاء
                break;
        }

        foreach ($query->get() as $u) {
            if (!empty($u->email)) {
                $recipients[] = [
                    'user_id' => $u->id,
                    'email' => $u->email,
                    'name' => $u->name,
                ];
            }
        }

        return $recipients;
    }

    // ═══════════════════════════════════════════
    // 📤 الإرسال الفعلي (مع Log Driver للاختبار)
    // ═══════════════════════════════════════════
    protected function sendCampaignNow(EmailCampaign $campaign): void
    {
        $sent = 0;
        $failed = 0;

        foreach ($campaign->recipients()->where('status', 'pending')->get() as $r) {
            try {
                \Mail::raw($campaign->body, function ($m) use ($campaign, $r) {
                    $m->to($r->email, $r->name ?? '')
                      ->subject($campaign->subject);
                });

                $r->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
                $sent++;
            } catch (\Throwable $e) {
                $r->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
                $failed++;
            }
        }

        $campaign->update([
            'status' => 'sent',
            'sent_at' => now(),
            'sent_count' => $sent,
            'failed_count' => $failed,
        ]);

        \Log::info('Campaign sent', [
            'campaign_id' => $campaign->id,
            'sent' => $sent,
            'failed' => $failed,
        ]);
    }
}

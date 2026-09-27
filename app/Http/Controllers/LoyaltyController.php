<?php
namespace App\Http\Controllers;

use App\Services\Loyalty\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    protected LoyaltyService $loyalty;

    public function __construct(LoyaltyService $loyalty)
    {
        $this->loyalty = $loyalty;
    }

    /**
     * لوحة الولاء للعميل
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/account/login')->with('error', 'سجّل الدخول لعرض نقاطك');
        }

        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        $shopId = $shop?->id;

        $points = $this->loyalty->getBalance($user->id, $shopId);
        $tier = $this->loyalty->getTier($points->balance);
        $nextTier = $this->loyalty->getNextTier($points->balance);
        $history = $this->loyalty->getHistory($user->id, 15);

        return view('customer.loyalty', compact('points', 'tier', 'nextTier', 'history', 'shop'));
    }

    /**
     * سجل النقاط
     */
    public function history()
    {
        $user = auth()->user();
        if (!$user) return redirect('/account/login');

        $history = $this->loyalty->getHistory($user->id, 50);
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();

        return view('customer.loyalty-history', compact('history', 'shop'));
    }

    /**
     * استبدال النقاط
     */
    public function redeem(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['ok' => false, 'message' => 'سجّل الدخول أولاً'], 401);
        }

        $request->validate(['points' => 'required|integer|min:100']);

        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        $result = $this->loyalty->redeem($user->id, (int) $request->points, $shop?->id);

        return response()->json($result, $result['ok'] ? 200 : 422);
    }

    /**
     * AJAX: رصيد النقاط
     */
    public function balance()
    {
        $user = auth()->user();
        if (!$user) return response()->json(['balance' => 0]);

        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        $points = $this->loyalty->getBalance($user->id, $shop?->id);
        $tier = $this->loyalty->getTier($points->balance);

        return response()->json([
            'balance' => $points->balance,
            'tier' => $tier['name'],
            'discount' => $tier['discount'],
        ]);
    }

    // ═══ لوحة التاجر ═══

    public function dashboard()
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        $shopId = $shop?->id;

        $stats = [
            'members' => \App\Models\LoyaltyPoint::where('shop_id', $shopId)->count(),
            'total_issued' => \App\Models\LoyaltyTransaction::where('type', 'earn')->sum('points'),
            'total_redeemed' => \App\Models\LoyaltyTransaction::where('type', 'redeem')->sum('points'),
            'top_members' => \App\Models\LoyaltyPoint::where('shop_id', $shopId)
                ->with('user')
                ->orderByDesc('balance')
                ->take(10)
                ->get(),
        ];

        $tiers = LoyaltyService::TIERS;
        $settings = [
            'points_per_currency' => LoyaltyService::POINTS_PER_CURRENCY,
            'points_per_redeem' => LoyaltyService::POINTS_PER_REDEEM,
            'redeem_value' => LoyaltyService::REDEEM_VALUE,
        ];

        return view('dashboard.loyalty.index', compact('stats', 'tiers', 'settings', 'shop'));
    }

    /**
     * 🎁 منح نقاط يدوياً من المدير
     */
    public function grantPoints(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'points' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        if (!$shop) {
            return response()->json(['ok' => false, 'message' => 'لا يوجد متجر نشط'], 400);
        }

        try {
            $this->loyalty->award(
                $data['user_id'],
                0, // مبلغ 0 (لأن النقاط مباشرة)
                $shop->id,
                $data['reason'] ?? 'منح يدوي من المدير',
                null
            );

            // نقاط مباشرة (تجاوز الحساب التلقائي)
            $loyalty = \App\Models\LoyaltyPoint::firstOrCreate(
                ['user_id' => $data['user_id'], 'shop_id' => $shop->id],
                ['balance' => 0, 'total_earned' => 0, 'total_redeemed' => 0]
            );
            $loyalty->increment('balance', $data['points']);
            $loyalty->increment('total_earned', $data['points']);

            // تسجيل الحركة
            \App\Models\LoyaltyTransaction::create([
                'user_id' => $data['user_id'],
                'points' => $data['points'],
                'type' => 'earn',
                'reason' => $data['reason'] ?? 'منح يدوي من المدير',
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'تم منح ' . $data['points'] . ' نقطة بنجاح',
                'balance' => $loyalty->fresh()->balance,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Grant points failed: ' . $e->getMessage());
            return response()->json(['ok' => false, 'message' => 'خطأ: ' . $e->getMessage()], 500);
        }
    }

}

<?php
namespace App\Services\Loyalty;
use App\Models\LoyaltyPoint;
use App\Models\LoyaltyTransaction;

class LoyaltyService
{
    const POINTS_PER_CURRENCY = 1;
    const POINTS_PER_REDEEM = 100;
    const REDEEM_VALUE = 10;

    const TIERS = [
        'bronze' => ['name' => 'برونزي', 'min' => 0, 'discount' => 5, 'color' => '#cd7f32'],
        'silver' => ['name' => 'فضي', 'min' => 1000, 'discount' => 10, 'color' => '#94a3b8'],
        'gold' => ['name' => 'ذهبي', 'min' => 5000, 'discount' => 15, 'color' => '#f59e0b'],
        'platinum' => ['name' => 'بلاتيني', 'min' => 20000, 'discount' => 20, 'color' => '#8b5cf6'],
    ];

    public function getBalance(int $userId, ?int $shopId = null): LoyaltyPoint
    {
        return LoyaltyPoint::firstOrCreate(
            ['user_id' => $userId, 'shop_id' => $shopId],
            ['balance' => 0, 'total_earned' => 0, 'total_redeemed' => 0]
        );
    }

    public function award(int $userId, float $amount, ?int $shopId, ?string $reason = null, ?int $referenceId = null): int
    {
        $points = (int) floor($amount * self::POINTS_PER_CURRENCY);
        if ($points <= 0) return 0;
        $loyalty = $this->getBalance($userId, $shopId);
        $loyalty->increment('balance', $points);
        $loyalty->increment('total_earned', $points);
        LoyaltyTransaction::create([
            'user_id' => $userId, 'points' => $points,
            'type' => 'earn', 'reason' => $reason ?? 'نقاط من الطلب',
            'reference_id' => $referenceId,
        ]);
        return $points;
    }

    public function redeem(int $userId, int $points, ?int $shopId): array
    {
        $loyalty = $this->getBalance($userId, $shopId);
        if ($loyalty->balance < $points) {
            return ['ok' => false, 'message' => 'رصيد النقاط غير كافٍ'];
        }
        if ($points % self::POINTS_PER_REDEEM !== 0) {
            return ['ok' => false, 'message' => 'يجب أن تكون النقاط من مضاعفات ' . self::POINTS_PER_REDEEM];
        }
        $value = ($points / self::POINTS_PER_REDEEM) * self::REDEEM_VALUE;
        $loyalty->decrement('balance', $points);
        $loyalty->increment('total_redeemed', $points);
        LoyaltyTransaction::create([
            'user_id' => $userId, 'points' => -$points, 'type' => 'redeem',
            'reason' => 'استبدال ' . $points . ' نقطة = ' . $value . ' ريال',
        ]);
        return ['ok' => true, 'value' => $value, 'message' => 'تم استبدال ' . $points . ' نقطة'];
    }

    public function getTier(int $points): array
    {
        $current = array_merge(self::TIERS['bronze'], ['key' => 'bronze']);
        foreach (self::TIERS as $key => $tier) {
            if ($points >= $tier['min']) {
                $current = array_merge($tier, ['key' => $key]);
            }
        }
        return $current;
    }

    public function getNextTier(int $points): ?array
    {
        foreach (self::TIERS as $key => $tier) {
            if ($points < $tier['min']) {
                return array_merge($tier, ['key' => $key, 'needed' => $tier['min'] - $points]);
            }
        }
        return null;
    }

    public function getHistory(int $userId, int $limit = 20)
    {
        return LoyaltyTransaction::where('user_id', $userId)->latest()->take($limit)->get();
    }
}

<?php
namespace App\Services\Push;

use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class WebPushService
{
    protected string $publicKey;
    protected string $privateKey;
    protected string $subject;

    public function __construct()
    {
        $this->publicKey = config('services.push.public_key', '');
        $this->privateKey = config('services.push.private_key', '');
        $this->subject = config('services.push.subject', 'mailto:admin@example.com');
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function subscribe(array $data, ?int $userId = null, ?int $shopId = null): PushSubscription
    {
        return PushSubscription::updateOrCreate(
            ['endpoint' => $data['endpoint']],
            [
                'user_id' => $userId,
                'shop_id' => $shopId,
                'public_key' => $data['keys']['p256dh'] ?? null,
                'auth_token' => $data['keys']['auth'] ?? null,
                'content_encoding' => $data['contentEncoding'] ?? 'aesgcm',
                'user_agent' => request()->userAgent(),
                'last_used_at' => now(),
            ]
        );
    }

    protected function makeWebPush(): WebPush
    {
        $auth = [
            'VAPID' => [
                'subject' => $this->subject,
                'publicKey' => $this->publicKey,
                'privateKey' => $this->privateKey,
            ],
        ];
        return new WebPush($auth);
    }

    public function send(string $title, string $body, ?string $url = null, ?string $icon = null, ?int $userId = null, ?int $shopId = null): int
    {
        $query = PushSubscription::query();
        if ($userId) $query->where('user_id', $userId);
        if ($shopId) $query->where('shop_id', $shopId);

        $subs = $query->get();
        if ($subs->isEmpty()) {
            Log::info('Push: no subscriptions matched');
            return 0;
        }

        $webPush = $this->makeWebPush();
        $payload = json_encode([
            'title' => $title,
            'body' => $body,
            'url' => $url ?? '/',
            'icon' => $icon ?? '/favicon.ico',
            'badge' => '/favicon.ico',
            'timestamp' => now()->timestamp * 1000,
        ], JSON_UNESCAPED_UNICODE);

        $sent = 0;
        foreach ($subs as $sub) {
            try {
                if (!$sub->public_key || !$sub->auth_token) {
                    $sub->delete();
                    continue;
                }

                $subscription = Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'publicKey' => $sub->public_key,
                    'authToken' => $sub->auth_token,
                    'contentEncoding' => $sub->content_encoding ?: 'aesgcm',
                ]);

                $report = $webPush->sendOneNotification($subscription, $payload);

                if ($report->isSuccess()) {
                    $sub->update(['last_used_at' => now()]);
                    $sent++;
                } else {
                    Log::warning('Push failed for sub ' . $sub->id . ': ' . $report->getReason());
                    if ($report->isSubscriptionExpired()) {
                        $sub->delete();
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Push exception: ' . $e->getMessage());
            }
        }

        Log::info("Push sent: $sent/" . $subs->count());
        return $sent;
    }

    public function stats(): array
    {
        return [
            'total' => PushSubscription::count(),
            'active_today' => PushSubscription::whereDate('last_used_at', today())->count(),
            'users' => PushSubscription::whereNotNull('user_id')->distinct('user_id')->count('user_id'),
        ];
    }

    public function testSend(?int $userId = null): int
    {
        return $this->send(
            'اختبار الإشعارات',
            'كل شيء يعمل بشكل صحيح!',
            '/',
            null,
            $userId
        );
    }
}

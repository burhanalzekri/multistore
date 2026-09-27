<?php
namespace App\Services\OneSignal;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneSignalService
{
    protected ?string $appId;
    protected ?string $apiKey;
    protected string $baseUrl = 'https://onesignal.com/api/v1';

    public function __construct()
    {
        $this->appId = config('services.onesignal.app_id');
        $this->apiKey = config('services.onesignal.rest_api_key');
    }

    public function isConfigured(): bool
    {
        return !empty($this->appId) && !empty($this->apiKey);
    }

    /**
     * إرسال إشعار لكل المشتركين في متجر
     */
    public function sendToShop(
        int $shopId,
        string $title,
        string $body,
        ?string $url = null,
        ?string $imageUrl = null
    ): array {
        return $this->send([
            'included_segments' => ['All'],
            'filters' => [
                ['field' => 'tag', 'key' => 'shop_id', 'relation' => '=', 'value' => (string) $shopId],
            ],
            'headings' => ['ar' => $title, 'en' => $title],
            'contents' => ['ar' => $body, 'en' => $body],
            'url' => $url ?: url('/'),
            'chrome_web_image' => $imageUrl,
            'chrome_web_icon' => url('/favicon.ico'),
        ]);
    }

    /**
     * إرسال لمستخدم محدد
     */
    public function sendToUser(
        int $userId,
        string $title,
        string $body,
        ?string $url = null
    ): array {
        return $this->send([
            'include_external_user_ids' => [(string) $userId],
            'headings' => ['ar' => $title, 'en' => $title],
            'contents' => ['ar' => $body, 'en' => $body],
            'url' => $url ?: url('/'),
            'chrome_web_icon' => url('/favicon.ico'),
        ]);
    }

    /**
     * إرسال لكل المشتركين
     */
    public function sendToAll(string $title, string $body, ?string $url = null): array
    {
        return $this->send([
            'included_segments' => ['All'],
            'headings' => ['ar' => $title, 'en' => $title],
            'contents' => ['ar' => $body, 'en' => $body],
            'url' => $url ?: url('/'),
        ]);
    }

    /**
     * إرسال عبر External User ID
     */
    public function sendToSubscriptions(array $subscriptionIds, string $title, string $body, ?string $url = null): array
    {
        return $this->send([
            'include_subscription_ids' => $subscriptionIds,
            'headings' => ['ar' => $title, 'en' => $title],
            'contents' => ['ar' => $body, 'en' => $body],
            'url' => $url ?: url('/'),
        ]);
    }

    /**
     * الإرسال الفعلي
     */
    public function send(array $payload): array
    {
        if (!$this->isConfigured()) {
            return ['ok' => false, 'error' => 'OneSignal غير مُهيأ'];
        }

        try {
            $resp = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->apiKey,
                'Content-Type' => 'application/json; charset=utf-8',
            ])->timeout(15)->post($this->baseUrl . '/notifications', array_merge([
                'app_id' => $this->appId,
            ], $payload));

            $json = $resp->json();

            if (!$resp->successful() || !empty($json['errors'])) {
                Log::warning('OneSignal send failed', [
                    'status' => $resp->status(),
                    'body' => $resp->body(),
                ]);
                return ['ok' => false, 'error' => $json['errors'] ?? $resp->body()];
            }

            Log::info('🔔 OneSignal sent', [
                'id' => $json['id'] ?? null,
                'recipients' => $json['recipients'] ?? 0,
            ]);

            return [
                'ok' => true,
                'id' => $json['id'] ?? null,
                'recipients' => $json['recipients'] ?? 0,
            ];
        } catch (\Throwable $e) {
            Log::warning('OneSignal exception: ' . $e->getMessage());
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * اختبار
     */
    public function test(int $shopId = 1): array
    {
        return $this->sendToShop(
            $shopId,
            '🧪 اختبار OneSignal',
            '✅ الإشعارات تعمل بشكل صحيح! ' . now()->format('H:i:s'),
            url('/'),
            null
        );
    }
}

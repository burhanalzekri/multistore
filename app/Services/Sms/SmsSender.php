<?php
namespace App\Services\Sms;

use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsSender
{
    protected string $provider;
    protected ?int $shopId;

    public function __construct(?int $shopId = null)
    {
        $this->provider = config('services.sms.provider', 'log');
        $this->shopId = $shopId;
    }

    public function send(string $to, string $message, ?int $shopId = null): array
    {
        $shopId = $shopId ?? $this->shopId;

        // سجّل أولاً
        $log = SmsLog::create([
            'shop_id' => $shopId,
            'to' => $to,
            'message' => $message,
            'provider' => $this->provider,
            'status' => 'pending',
        ]);

        try {
            $result = match ($this->provider) {
                'twilio' => $this->sendViaTwilio($to, $message),
                'yemen_mobile' => $this->sendViaYemenMobile($to, $message),
                'custom_http' => $this->sendViaCustomHttp($to, $message),
                default => $this->sendViaLog($to, $message),
            };

            $log->update([
                'status' => 'sent',
                'external_id' => $result['id'] ?? null,
                'sent_at' => now(),
                'meta' => $result,
            ]);

            Log::info("📱 SMS [{$this->provider}] → {$to}: {$message}");
            return ['ok' => true, 'id' => $result['id'] ?? null];
        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);

            Log::warning("SMS failed [{$this->provider}] → {$to}: " . $e->getMessage());
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    // ═══ مزودات ═══

    protected function sendViaLog(string $to, string $message): array
    {
        Log::info("SMS to {$to}: {$message}");
        return ['id' => 'log-' . time(), 'provider' => 'log'];
    }

    protected function sendViaTwilio(string $to, string $message): array
    {
        $sid = config('services.sms.twilio_sid');
        $token = config('services.sms.twilio_token');
        $from = config('services.sms.twilio_from');

        if (!$sid || !$token || !$from) {
            throw new \RuntimeException('Twilio config ناقص');
        }

        $resp = Http::withBasicAuth($sid, $token)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                'From' => $from,
                'To' => $to,
                'Body' => $message,
            ]);

        if (!$resp->successful()) {
            throw new \RuntimeException('Twilio: ' . $resp->body());
        }

        return [
            'id' => $resp->json('sid'),
            'provider' => 'twilio',
        ];
    }

    protected function sendViaYemenMobile(string $to, string $message): array
    {
        $url = config('services.sms.ym_url');
        $key = config('services.sms.ym_key');
        $sender = config('services.sms.ym_sender', 'MultiStore');

        if (!$url || !$key) {
            throw new \RuntimeException('YemenMobile config ناقص');
        }

        $resp = Http::post($url, [
            'api_key' => $key,
            'sender' => $sender,
            'to' => $to,
            'message' => $message,
        ]);

        if (!$resp->successful()) {
            throw new \RuntimeException('YM: ' . $resp->body());
        }

        return [
            'id' => $resp->json('message_id') ?? 'ym-' . time(),
            'provider' => 'yemen_mobile',
        ];
    }

    protected function sendViaCustomHttp(string $to, string $message): array
    {
        $url = config('services.sms.custom_url');
        $method = config('services.sms.custom_method', 'POST');

        if (!$url) {
            throw new \RuntimeException('Custom SMS URL ناقص');
        }

        $payload = [
            'to' => $to,
            'message' => $message,
            'api_key' => config('services.sms.custom_key'),
        ];

        $resp = $method === 'GET'
            ? Http::get($url, $payload)
            : Http::post($url, $payload);

        if (!$resp->successful()) {
            throw new \RuntimeException('Custom: ' . $resp->body());
        }

        return ['id' => 'custom-' . time(), 'provider' => 'custom'];
    }

    // ═══ إحصائيات ═══

    public function stats(?int $shopId = null): array
    {
        $q = SmsLog::query();
        if ($shopId) $q->where('shop_id', $shopId);

        return [
            'total' => (clone $q)->count(),
            'sent' => (clone $q)->where('status', 'sent')->count(),
            'failed' => (clone $q)->where('status', 'failed')->count(),
            'pending' => (clone $q)->where('status', 'pending')->count(),
            'today' => (clone $q)->whereDate('created_at', today())->count(),
        ];
    }

    public function recent(int $limit = 20, ?int $shopId = null)
    {
        $q = SmsLog::query()->latest();
        if ($shopId) $q->where('shop_id', $shopId);
        return $q->take($limit)->get();
    }

    public function retry(int $logId): bool
    {
        $log = SmsLog::findOrFail($logId);
        if ($log->status === 'sent') return false;

        $result = $this->send($log->to, $log->message, $log->shop_id);
        return $result['ok'] ?? false;
    }
}

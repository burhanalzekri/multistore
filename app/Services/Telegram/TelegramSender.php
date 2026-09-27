<?php
namespace App\Services\Telegram;

use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramSender
{
    protected ?string $token;
    protected ?string $chatId;

    public function __construct(?string $token = null, ?string $chatId = null)
    {
        $this->token = $token ?? config('services.telegram.bot_token');
        $this->chatId = $chatId ?? config('services.telegram.chat_id');
    }

    public function send(string $message, ?string $chatId = null, ?int $shopId = null): array
    {
        $chatId = $chatId ?? $this->chatId;

        if (!$this->token || !$chatId) {
            Log::warning('Telegram: Token or Chat ID مفقود');
            return ['ok' => false, 'error' => 'config missing'];
        }

        // سجّل
        $log = SmsLog::create([
            'shop_id' => $shopId,
            'to' => 'tg:' . $chatId,
            'message' => $message,
            'provider' => 'telegram',
            'status' => 'pending',
        ]);

        try {
            $resp = Http::timeout(15)
                ->post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]);

            if (!$resp->successful() || !$resp->json('ok')) {
                throw new \RuntimeException('Telegram: ' . $resp->body());
            }

            $messageId = $resp->json('result.message_id');

            $log->update([
                'status' => 'sent',
                'external_id' => (string) $messageId,
                'sent_at' => now(),
                'meta' => ['message_id' => $messageId],
            ]);

            Log::info("✈️ Telegram → {$chatId}: " . substr($message, 0, 60));
            return ['ok' => true, 'id' => $messageId];
        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
            Log::warning("Telegram failed → {$chatId}: " . $e->getMessage());
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    public function test(): array
    {
        return $this->send("🧪 <b>اختبار Telegram</b>\n\n✅ البوت يعمل بشكل صحيح!\n🕐 " . now()->format('Y-m-d H:i'));
    }
}

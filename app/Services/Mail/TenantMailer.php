<?php

namespace App\Services\Mail;

use App\Models\Shop;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class TenantMailer
{
    public static function configure(?Shop $shop = null): void
    {
        $shop = $shop ?? app(\App\Services\Tenant\TenantManager::class)->get();

        if (!$shop || empty($shop->smtp_settings)) {
            return;
        }

        $smtp = is_array($shop->smtp_settings)
            ? $shop->smtp_settings
            : json_decode($shop->smtp_settings, true);

        if (empty($smtp['host'])) {
            return;
        }

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $smtp['host'] ?? '');
        Config::set('mail.mailers.smtp.port', $smtp['port'] ?? 587);
        Config::set('mail.mailers.smtp.username', $smtp['username'] ?? '');
        Config::set('mail.mailers.smtp.password', $smtp['password'] ?? '');
        Config::set('mail.mailers.smtp.encryption', $smtp['encryption'] ?? 'tls');
        Config::set('mail.from.address', $smtp['from_address'] ?? $smtp['username'] ?? 'noreply@multistore.local');
        Config::set('mail.from.name', $smtp['from_name'] ?? $shop->name);

        app()->forgetInstance('mail.manager');
        app()->forgetInstance('mailer');
    }

    public static function send(string $to, string $subject, string $body, ?Shop $shop = null): bool
    {
        try {
            self::configure($shop);
            Mail::raw($body, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });
            return true;
        } catch (\Throwable $e) {
            \Log::error('TenantMailer: فشل الإرسال', ['to' => $to, 'error' => $e->getMessage()]);
            return false;
        }
    }
}

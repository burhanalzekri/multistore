<?php
namespace App\Services\Sms;

class SmsSender {
    public function send(string $to, string $message): void {
        logger()->info("SMS to {$to}: {$message}");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public static function notifySuperAdmins(string $type, string $title, string $message, array $data = []): void
    {
        // سجل في قاعدة البيانات
        self::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);

        // أرسل بريداً للمشرف
        try {
            \Illuminate\Support\Facades\Mail::raw(
                "🔔 إشعار جديد:\n\n{$title}\n\n{$message}\n\n" . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                function ($mail) {
                    $mail->to('burhanalzekri77@gmail.com')
                         ->subject('🔔 ' . $title . ' — MultiStore');
                }
            );
        } catch (\Throwable $e) {
            \Log::error('فشل إرسال الإشعار البريدي', ['error' => $e->getMessage()]);
        }
    }
}

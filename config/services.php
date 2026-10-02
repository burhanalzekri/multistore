<?php

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // ═══ 📱 SMS Providers ═══
    // ═══ ✈️ Telegram Bot ═══
    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'chat_id' => env('TELEGRAM_CHAT_ID'),
    ],

    // ═══ 🔔 Web Push (VAPID) ═══
    // ═══ 🔔 OneSignal ═══
    'onesignal' => [
        'app_id' => env('ONESIGNAL_APP_ID'),
        'rest_api_key' => env('ONESIGNAL_REST_API_KEY'),
    ],

    'push' => [
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
        'subject' => env('VAPID_SUBJECT', 'mailto:admin@example.com'),
    ],

    'sms' => [
        'provider' => env('SMS_PROVIDER', 'log'),
        'twilio_sid' => env('TWILIO_SID'),
        'twilio_token' => env('TWILIO_TOKEN'),
        'twilio_from' => env('TWILIO_FROM'),
        'ym_url' => env('YM_SMS_URL'),
        'ym_key' => env('YM_SMS_KEY'),
        'ym_sender' => env('YM_SMS_SENDER', 'MultiStore'),
        'custom_url' => env('CUSTOM_SMS_URL'),
        'custom_method' => env('CUSTOM_SMS_METHOD', 'POST'),
        'custom_key' => env('CUSTOM_SMS_KEY'),
    ],


'cloudinary' => [
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key'    => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),
    'folder'     => env('CLOUDINARY_FOLDER', 'multistore'),
],



];

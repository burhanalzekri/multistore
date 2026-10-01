<?php

namespace App\Http\Controllers;

use App\Models\EmailSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmailSubscriberController extends Controller
{
    /**
     * 📧 اشتراك من Exit Intent Popup
     */
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:190',
            'source' => 'nullable|string|max:60',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
        ]);

        // كود خصم فريد
        $couponCode = 'WELCOME' . strtoupper(Str::random(6));

        $existing = EmailSubscriber::where('email', $data['email'])->first();

        if ($existing) {
            return response()->json([
                'ok' => true,
                'message' => 'أنت مشترك مسبقاً! 🎉',
                'coupon' => $existing->coupon_code,
                'existing' => true,
            ]);
        }

        $subscriber = EmailSubscriber::create([
            'email' => $data['email'],
            'source' => $data['source'] ?? 'exit_popup',
            'coupon_code' => $couponCode,
            'subscribed_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'تم الاشتراك بنجاح! 🎉',
            'coupon' => $subscriber->coupon_code,
            'existing' => false,
        ]);
    }
}

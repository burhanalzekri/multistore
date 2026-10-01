<?php
namespace App\Http\Controllers;

use App\Models\Shop;
use App\Services\Mail\TenantMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SmtpSettingsController extends Controller
{
    public function update(Request $request)
    {
        $shop = $this->currentShop();

        $data = $request->validate([
            'provider' => 'required|string',
            'host' => 'required|string',
            'port' => 'required|integer',
            'encryption' => 'required|in:tls,ssl',
            'username' => 'required|email',
            'password' => 'required|string',
            'from_name' => 'nullable|string',
            'from_address' => 'nullable|email',
        ]);

        $shop->smtp_settings = $data;
        $shop->save();

        return back()->with('smtp_success', 'تم حفظ إعدادات البريد');
    }

    public function test(Request $request)
    {
        $shop = $this->currentShop();

        if (!$shop->smtp_settings) {
            return back()->with('smtp_error', 'احفظ الإعدادات أولاً');
        }

        try {
            // اضبط SMTP المتجر
            TenantMailer::configure($shop);

            // أرسل رسالة اختبار
            $to = $shop->smtp_settings['username'];

            Mail::raw(
                "🎉 مرحبًا!\n\n" .
                "هذه رسالة اختبار من متجر: {$shop->name}\n" .
                "إذا وصلت، فإعدادات SMTP تعمل بشكل صحيح.\n\n" .
                "شكرًا لك.",
                function ($msg) use ($to) {
                    $msg->to($to)->subject('📧 اختبار إعدادات البريد');
                }
            );

            return back()->with('smtp_success', '✅ تم إرسال رسالة اختبار إلى ' . $to);
        } catch (\Exception $e) {
            return back()->with('smtp_error', '❌ فشل الإرسال: ' . $e->getMessage());
        }
    }

    private function currentShop()
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->get();
        if (!$shop) abort(404, 'لا يوجد متجر محدد لهذا الطلب.');
        return $shop;
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\SmsInbox;
use App\Services\Payment\PaymentMatcher;
use App\Services\Sms\SmsParser;
use App\Services\Tenant\TenantManager;
use Illuminate\Http\Request;

class SmsWebhookController extends Controller {
    public function handle(Request $request, string $token, SmsParser $parser, PaymentMatcher $matcher) {
        $shop = Shop::where('webhook_token', $token)->firstOrFail();

        $data = $request->validate([
            'sender' => 'required|string|max:30',
            'message' => 'required|string',
            'time' => 'nullable',
        ]);

        $sms = SmsInbox::withoutGlobalScope('tenant')->create([
            'shop_id' => $shop->id,
            'sender_phone' => $data['sender'],
            'raw_body' => $data['message'],
            'received_at' => now(),
            'status' => 'pending',
        ]);

        $parsed = $parser->parse($sms->raw_body);

        if (!$parsed) {
            $sms->update(['status' => 'review']);
            return response()->json(['status' => 'unparsed', 'sms_id' => $sms->id]);
        }

        $sms->update([
            'parsed_amount' => $parsed['amount'],
            'parsed_sender' => $parsed['sender'],
            'parsed_reference' => $parsed['reference'],
            'provider' => $parsed['provider'],
        ]);

        app(TenantManager::class)->set($shop);
        $matcher->match($sms, $parsed);

        return response()->json([
            'status' => 'ok',
            'sms_id' => $sms->id,
            'parsed' => $parsed,
        ]);
    }
}

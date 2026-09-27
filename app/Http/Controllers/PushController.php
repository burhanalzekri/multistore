<?php
namespace App\Http\Controllers;

use App\Services\Push\WebPushService;
use Illuminate\Http\Request;

class PushController extends Controller
{
    protected WebPushService $push;

    public function __construct(WebPushService $push)
    {
        $this->push = $push;
    }

    public function publicKey()
    {
        return response()->json(['publicKey' => $this->push->getPublicKey()]);
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'nullable|string',
            'keys.auth' => 'nullable|string',
        ]);

        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();

        $sub = $this->push->subscribe(
            $request->all(),
            auth()->id(),
            $shop?->id
        );

        return response()->json(['ok' => true, 'id' => $sub->id]);
    }

    public function unsubscribe(Request $request)
    {
        $endpoint = $request->input('endpoint');
        if ($endpoint) {
            \App\Models\PushSubscription::where('endpoint', $endpoint)->delete();
        }
        return response()->json(['ok' => true]);
    }

    public function test()
    {
        $sent = $this->push->testSend(auth()->id());
        return back()->with('success', "تم إرسال $sent إشعار");
    }
}

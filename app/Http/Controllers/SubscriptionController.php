<?php
namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Shop;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['shop', 'plan'])->latest()->paginate(20);
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        $stats = [
            'total' => Subscription::count(),
            'active' => Subscription::where('status', 'active')->count(),
            'expired' => Subscription::where('status', 'expired')->count(),
            'trial' => Subscription::where('status', 'trial')->count(),
            'revenue' => Subscription::where('status', 'active')->sum('amount_paid'),
        ];

        return view('super-admin.subscriptions.index', compact('subscriptions', 'plans', 'stats'));
    }

    public function assign(Request $request)
    {
        $data = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'plan_id' => 'required|exists:plans,id',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'status' => 'required|in:active,expired,cancelled,trial',
            'amount_paid' => 'nullable|numeric|min:0',
        ]);

        Subscription::create($data);

        return back()->with('success', 'تم تعيين الاشتراك');
    }

    public function cancel(Subscription $subscription)
    {
        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'تم إلغاء الاشتراك');
    }

    public function plans()
    {
        $plans = Plan::orderBy('sort_order')->get();
        return view('super-admin.subscriptions.plans', compact('plans'));
    }
}

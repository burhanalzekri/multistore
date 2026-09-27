<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SmsInbox;

class NotificationController extends Controller
{
    public function check()
    {
        return response()->json([
            'new_orders' => Order::where('created_at', '>=', now()->subSeconds(30))->count(),
            'review_sms' => SmsInbox::where('status', 'review')->count(),
            'pending_orders' => Order::where('status', 'awaiting_payment')->count(),
            'recent_orders' => Order::latest()->take(3)->get(['id', 'order_number', 'customer_name', 'total']),
        ]);
    }
}

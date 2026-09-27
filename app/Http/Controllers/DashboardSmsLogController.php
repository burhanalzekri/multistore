<?php
namespace App\Http\Controllers;

use App\Models\SmsLog;
use App\Services\Sms\SmsSender;
use Illuminate\Http\Request;

class DashboardSmsLogController extends Controller
{
    public function index(Request $request)
    {
        $query = SmsLog::query()->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('to', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(30)->withQueryString();
        $sender = new SmsSender();
        $stats = $sender->stats();
        $provider = config('services.sms.provider', 'log');

        return view('dashboard.sms-logs.index', compact('logs', 'stats', 'provider'));
    }

    public function retry($id)
    {
        $log = SmsLog::findOrFail($id);
        $sender = new SmsSender();
        $ok = $sender->retry($log->id);
        return back()->with($ok ? 'success' : 'error', $ok ? '✅ أُعيد الإرسال' : '❌ فشل الإرسال');
    }

    public function destroy($id)
    {
        SmsLog::findOrFail($id)->delete();
        return back()->with('success', 'تم الحذف');
    }

    public function clearFailed()
    {
        $count = SmsLog::where('status', 'failed')->count();
        SmsLog::where('status', 'failed')->delete();
        return back()->with('success', "حُذف $count سجل فاشل");
    }
}

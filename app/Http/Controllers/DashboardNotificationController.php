<?php
namespace App\Http\Controllers;

use App\Models\AdminNotification;
use Illuminate\Http\Request;

class DashboardNotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminNotification::query()->latest();

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($request->get('filter') === 'unread') {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate(20)->withQueryString();
        $unreadCount = AdminNotification::where('is_read', false)->count();
        $types = AdminNotification::query()
            ->select('type')
            ->distinct()
            ->pluck('type');

        return view('dashboard.notifications.index', compact('notifications', 'unreadCount', 'types'));
    }

    public function markRead($id)
    {
        $n = AdminNotification::findOrFail($id);
        $n->update(['is_read' => true, 'read_at' => now()]);
        return back()->with('success', 'تم وضع علامة مقروء');
    }

    public function markAllRead()
    {
        AdminNotification::where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        return back()->with('success', 'تم وضع علامة مقروء على الكل');
    }

    public function destroy($id)
    {
        AdminNotification::findOrFail($id)->delete();
        return back()->with('success', 'تم الحذف');
    }

    public function clearAll()
    {
        AdminNotification::where('is_read', true)->delete();
        return back()->with('success', 'تم حذف الإشعارات المقروءة');
    }

    // AJAX: عدد الإشعارات غير المقروءة
    public function count()
    {
        return response()->json([
            'unread' => AdminNotification::where('is_read', false)->count(),
            'latest' => AdminNotification::latest()->take(5)->get()->map(function ($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'message' => $n->message,
                    'is_read' => $n->is_read,
                    'created_at' => $n->created_at->diffForHumans(),
                    'link' => $n->data['link'] ?? null,
                ];
            }),
        ]);
    }
}

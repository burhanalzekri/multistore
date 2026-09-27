<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('user')->latest()->paginate(20);
        $stats = [
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'overdue' => Task::where('status', '!=', 'completed')
                ->whereNotNull('due_date')
                ->where('due_date', '<', now())
                ->count(),
        ];
        return view('dashboard.tasks.index', compact('tasks', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
        ]);

        $data['user_id'] = auth()->id();
        $task = Task::create($data);

        ActivityLog::log('task.created', 'مهمة جديدة: ' . $task->title, 'Task', $task->id);

        return back()->with('success', '✅ تم إضافة المهمة');
    }

    public function toggle(Task $task)
    {
        $task->update([
            'status' => $task->status === 'completed' ? 'pending' : 'completed',
            'completed_at' => $task->status === 'completed' ? null : now(),
        ]);
        return back()->with('success', 'تم التحديث');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('success', 'تم الحذف');
    }
}

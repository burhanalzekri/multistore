<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::latest()->paginate(50);
        return view('dashboard.activity-logs.index', compact('logs'));
    }
}

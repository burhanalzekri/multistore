<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    private function file()
    {
        return storage_path('framework/maintenance.json');
    }

    public function status()
    {
        if (!file_exists($this->file())) {
            return response()->json(['active' => false]);
        }
        return response()->json(json_decode(file_get_contents($this->file()), true));
    }

    public function toggle(Request $request)
    {
        $active = $request->boolean('active');

        $data = [
            'active' => $active,
            'message' => $request->input('message', 'نعمل على تحسينات — سنعود قريبًا'),
            'ends_at' => $request->input('ends_at'),
            'activated_by' => auth()->user()->name ?? 'System',
            'activated_at' => now()->toDateTimeString(),
        ];

        file_put_contents($this->file(), json_encode($data, JSON_UNESCAPED_UNICODE));

        return response()->json(['success' => true, 'data' => $data]);
    }
}

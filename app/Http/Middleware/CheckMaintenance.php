<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMaintenance
{
    public function handle(Request $request, Closure $next)
    {
        // تجاهل مسارات لوحة التحكم والـ webhooks
        if ($request->is('dashboard*') || $request->is('webhooks*') || $request->is('api*')) {
            return $next($request);
        }

        $maintenanceFile = storage_path('framework/maintenance.json');

        if (file_exists($maintenanceFile)) {
            $data = json_decode(file_get_contents($maintenanceFile), true);

            if ($data['active'] ?? false) {
                // إذا كان المدير مسجلًا — اسمح له
                if (auth()->check() && in_array(auth()->user()->role, ['super_admin', 'shop_admin'])) {
                    return $next($request);
                }

                return response()->view('maintenance', [
                    'message' => $data['message'] ?? 'الموقع تحت الصيانة',
                    'ends_at' => $data['ends_at'] ?? null,
                ], 503);
            }
        }

        return $next($request);
    }
}

<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = $request->user();
        if (!$user) return redirect('/login');

        // Super Admin → كل الصلاحيات
        if ($user->role === 'super_admin') return $next($request);

        // Shop Admin → كل صلاحيات متجره
        if ($user->role === 'shop_admin') return $next($request);

        // Staff → فحص الصلاحيات
        if ($user->role === 'staff') {
            $perms = $user->permissions ?? [];
            if (in_array($permission, $perms)) return $next($request);
        }

        abort(403, 'ليس لديك صلاحية للقيام بهذا الإجراء');
    }
}

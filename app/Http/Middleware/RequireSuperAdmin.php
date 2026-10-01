<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // 🚪 ضيف غير مسجّل → توجيه إلى صفحة الدخول
        if (!$user) {
            return $request->expectsJson()
                ? response()->json(['error' => 'Unauthenticated'], 401)
                : redirect()->guest('/login');
        }

        // 🚫 مسجّل لكن ليس Super Admin → 403
        if ($user->role !== 'super_admin') {
            abort(403, 'غير مصرح لك بالوصول');
        }

        return $next($request);
    }
}

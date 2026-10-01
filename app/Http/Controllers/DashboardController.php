<?php
namespace App\Http\Controllers;

class DashboardController extends Controller {
    public function index() {
        $user = auth()->user();

        // 🎯 super_admin ينتقل إلى لوحة المنصة
        if ($user?->role === 'super_admin') {
            return redirect('/super-admin');
        }

        return app(SmartDashboardController::class)->index();
    }
}

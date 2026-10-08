<?php

namespace App\Http\Controllers;

use App\Services\Tenant\TenantManager;

class LandingController extends Controller
{
    /**
     * 🏠 الصفحة التسويقية الرئيسية
     */
    public function index()
    {
        // ═══ خدمة landing.html الجديدة إذا وُجدت ═══
        $landingFile = public_path('landing.html');
        if (file_exists($landingFile)) {
            return response()->file($landingFile);
        }
        
        // ═══ fallback: الكود القديم ═══

        return view('landing.index', [
            'reviews' => \App\Models\Review::where('is_approved', true)->latest()->take(6)->get(),
        ]);
    }
}

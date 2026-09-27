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
        return view('landing.index');
    }
}

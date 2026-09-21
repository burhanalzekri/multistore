<?php
namespace App\Http\Controllers;

use App\Models\Shop;
use App\Services\Tenant\TenantManager;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $shop = app(TenantManager::class)->get();
        if (!$shop) $shop = Shop::first();

        return view('dashboard.settings.index', compact('shop'));
    }

    public function update(Request $request)
    {
        $shop = app(TenantManager::class)->get();
        if (!$shop) $shop = Shop::first();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'primary_color' => 'nullable|string|max:10',
            'currency' => 'nullable|string|max:3',
        ]);

        $shop->update($data);

        return back()->with('success', 'تم حفظ الإعدادات');
    }
}

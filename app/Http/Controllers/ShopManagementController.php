<?php
namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ShopManagementController extends Controller
{
    public function index()
    {
        $shops = Shop::withCount(['products', 'orders', 'users'])->latest()->paginate(20);
        return view('super-admin.shops.index', compact('shops'));
    }

    public function create()
    {
        return view('super-admin.shops.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'shop_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:30',
            'password' => 'required|string|min:6',
        ]);

        $shop = Shop::create([
            'name' => $data['shop_name'],
            'slug' => Str::slug($data['shop_name']) . '-' . Str::random(4),
            'webhook_token' => Str::random(64),
            'phone' => $data['phone'],
            'status' => 'active',
        ]);

        User::create([
            'shop_id' => $shop->id,
            'name' => $data['owner_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'shop_admin',
        ]);

        return redirect('/super-admin/shops')->with('success', '✅ تم إنشاء المتجر');
    }

    public function destroy(Shop $shop)
    {
        if ($shop->products()->count() > 0 || $shop->orders()->count() > 0) {
            return back()->with('error', 'المتجر يحتوي على بيانات — لا يمكن الحذف');
        }
        $shop->delete();
        return back()->with('success', 'تم حذف المتجر');
    }
}

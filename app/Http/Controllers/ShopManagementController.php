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

    public function edit(Shop $shop)
    {
        return view('super-admin.shops.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255|unique:shops,slug,' . $shop->id,
            'phone'         => 'nullable|string|max:30',
            'whatsapp'      => 'nullable|string|max:30',
            'email'         => 'nullable|email|max:255',
            'address'       => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:100',
            'country'       => 'nullable|string|max:100',
            'description'   => 'nullable|string|max:1000',
            'primary_color' => 'nullable|string|max:20',
            'status'        => 'required|in:active,trial,suspended',
            'logo'          => 'nullable|file|mimetypes:image/*|max:5120',
        ]);

        // معالجة الشعار
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('shops', 'public');
            $data['logo'] = $path;
        }

        // تجنّب تغيير الحالة إلى active يُلغي trial_ends_at
        if (isset($data['status']) && $data['status'] === 'active') {
            $data['trial_ends_at'] = null;
        }

        $shop->update($data);

        return redirect()->route('super-admin.shops')
            ->with('success', "✅ تم تحديث متجر: {$shop->name}");
    }
}
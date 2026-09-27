<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OwnerShopsController extends Controller
{
    public function __construct()
    {
        // صلاحية للمشرف العام فقط
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || Auth::user()->role !== 'super_admin') {
                abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
            }
            return $next($request);
        });
    }

    /**
     * عرض كل المتاجر
     */
    public function index()
    {
        $shops = Shop::withCount(['products', 'orders'])
            ->orderBy('created_at', 'desc')
            ->get();

        // إحصائيات عامة
        $stats = [
            'total_shops'      => $shops->count(),
            'active_shops'     => $shops->where('status', 'active')->count(),
            'trial_shops'      => $shops->where('status', 'trial')->count(),
            'suspended_shops'  => $shops->where('status', 'suspended')->count(),
            'total_products'   => Product::count(),
            'total_orders'     => Order::count(),
            'total_revenue'    => Order::where('payment_status', 'confirmed')->sum('total'),
            'total_users'      => User::count(),
        ];

        return view('owner.shops.index', compact('shops', 'stats'));
    }

    /**
     * صفحة إضافة متجر جديد
     */
    public function create()
    {
        return view('owner.shops.create');
    }

    /**
     * حفظ متجر جديد
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:shops,slug',
            'phone'       => 'required|string|max:30',
            'whatsapp'    => 'nullable|string|max:30',
            'email'       => 'required|email',
            'address'     => 'required|string|max:255',
            'city'        => 'required|string|max:100',
            'country'     => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'status'      => 'required|in:active,trial,suspended',
            'currency'    => 'nullable|string|max:10',
            'logo'        => 'nullable|image|max:2048',

            // بيانات المالك
            'owner_name'     => 'required|string|max:255',
            'owner_email'    => 'required|email|unique:users,email',
            'owner_phone'    => 'nullable|string|max:30',
            'owner_password' => 'required|string|min:6',
        ]);

        // إنشاء المتجر
        $shop = Shop::create([
            'name'          => $data['name'],
            'slug'          => $data['slug'] ?: Str::slug($data['name']) . '-' . Str::lower(Str::random(5)),
            'phone'         => $data['phone'],
            'whatsapp'      => $data['whatsapp'] ?? $data['phone'],
            'email'         => $data['email'],
            'address'       => $data['address'],
            'city'          => $data['city'],
            'country'       => $data['country'] ?? 'اليمن',
            'description'   => $data['description'] ?? null,
            'currency'      => $data['currency'] ?? 'YER',
            'status'        => $data['status'],
            'webhook_token' => Str::random(64),
            'trial_ends_at' => $data['status'] === 'trial' ? now()->addDays(30) : null,
        ]);

        // معالجة اللوجو
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('shops', 'public');
            $shop->update(['logo' => $path]);
        }

        // إنشاء مالك المتجر
        User::create([
            'shop_id'  => $shop->id,
            'name'     => $data['owner_name'],
            'email'    => $data['owner_email'],
            'phone'    => $data['owner_phone'] ?? null,
            'password' => Hash::make($data['owner_password']),
            'role'     => 'shop_admin',
        ]);

        return redirect()->route('owner.shops.index')
            ->with('success', "✅ تم إنشاء متجر: {$shop->name}");
    }

    /**
     * عرض تفاصيل متجر
     */
    public function show($id)
    {
        $shop = Shop::withCount(['products', 'orders'])->findOrFail($id);

        // إحصائيات المتجر
        $stats = [
            'products'      => Product::where('shop_id', $id)->count(),
            'orders'        => Order::where('shop_id', $id)->count(),
            'revenue'       => Order::where('shop_id', $id)->where('payment_status', 'confirmed')->sum('total'),
            'pending'       => Order::where('shop_id', $id)->where('status', 'awaiting_payment')->count(),
            'staff'         => User::where('shop_id', $id)->count(),
        ];

        // آخر 10 طلبات
        $recentOrders = Order::where('shop_id', $id)
            ->latest()
            ->take(10)
            ->get();

        // آخر 10 منتجات
        $recentProducts = Product::where('shop_id', $id)
            ->latest()
            ->take(10)
            ->get();

        // موظفو المتجر
        $staff = User::where('shop_id', $id)->get();

        return view('owner.shops.show', compact('shop', 'stats', 'recentOrders', 'recentProducts', 'staff'));
    }

    /**
     * صفحة تعديل متجر
     */
    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        $owner = User::where('shop_id', $id)->where('role', 'shop_admin')->first();
        return view('owner.shops.edit', compact('shop', 'owner'));
    }

    /**
     * تحديث بيانات متجر
     */
    public function update(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:shops,slug,' . $id,
            'phone'       => 'required|string|max:30',
            'whatsapp'    => 'nullable|string|max:30',
            'email'       => 'required|email',
            'address'     => 'required|string|max:255',
            'city'        => 'required|string|max:100',
            'country'     => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'status'      => 'required|in:active,trial,suspended',
            'currency'    => 'nullable|string|max:10',
            'logo'        => 'nullable|image|max:2048',
        ]);

        // تحديث اللوجو
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('shops', 'public');
        }

        // إذا تغيرت الحالة إلى active، ألغِ تاريخ الانتهاء
        if ($data['status'] === 'active') {
            $data['trial_ends_at'] = null;
        }

        $shop->update($data);

        return redirect()->route('owner.shops.show', $shop->id)
            ->with('success', "✅ تم تحديث بيانات: {$shop->name}");
    }

    /**
     * حذف متجر
     */
    public function destroy(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        // تحقق من الحماية — لا يمكن حذف متجر بـ orders مسجلة
        $ordersCount = Order::where('shop_id', $id)->count();
        if ($ordersCount > 0 && !$request->boolean('force')) {
            return back()->with('error', "⚠️ المتجر يحتوي على {$ordersCount} طلب — استخدم خيار الحذف القسري");
        }

        $shopName = $shop->name;

        // حذف المنتجات
        Product::where('shop_id', $id)->delete();

        // حذف الطلبات المرتبطة
        Order::where('shop_id', $id)->delete();

        // حذف المستخدمين
        User::where('shop_id', $id)->delete();

        // حذف المتجر
        $shop->delete();

        return redirect()->route('owner.shops.index')
            ->with('success', "🗑️ تم حذف متجر: {$shopName}");
    }

    /**
     * تغيير حالة المتجر (تفعيل/تعطيل)
     */
    public function toggleStatus(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $newStatus = match ($shop->status) {
            'active' => 'suspended',
            'suspended' => 'active',
            'trial' => 'active',
            default => 'active',
        };

        $shop->update(['status' => $newStatus]);

        $label = $newStatus === 'active' ? 'مُفعّل' : 'معطّل';

        return back()->with('success', "تم {$label} متجر: {$shop->name}");
    }

    /**
     * تمديد التجربة
     */
    public function extendTrial(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $days = (int) $request->input('days', 30);

        $shop->update([
            'status' => 'trial',
            'trial_ends_at' => now()->addDays($days),
        ]);

        return back()->with('success', "تم تمديد تجربة {$shop->name} لمدة {$days} يوم");
    }
}

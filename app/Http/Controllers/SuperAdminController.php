<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SmsInbox;
use App\Models\User;

class SuperAdminController extends Controller
{
    public function index()
    {
        $shops = Shop::withCount(['products', 'orders', 'users'])->get();

        $stats = [
            'total_shops' => Shop::count(),
            'active_shops' => Shop::where('status', 'active')->count(),
            'total_users' => User::count(),
            'total_products' => Product::withoutGlobalScope('tenant')->count(),
            'total_orders' => Order::withoutGlobalScope('tenant')->count(),
            'total_sales' => Order::withoutGlobalScope('tenant')->where('payment_status', 'confirmed')->sum('total'),
            'total_sms' => SmsInbox::withoutGlobalScope('tenant')->count(),
        ];

        return view('super-admin.index', compact('shops', 'stats'));
    }

    public function toggleStatus(Shop $shop)
    {
        $shop->update([
            'status' => $shop->status === 'active' ? 'suspended' : 'active'
        ]);
        return back()->with('success', 'تم تحديث حالة المتجر');
    }

    public function show(Shop $shop)
    {
        $stats = [
            'products' => Product::withoutGlobalScope('tenant')->where('shop_id', $shop->id)->count(),
            'orders' => Order::withoutGlobalScope('tenant')->where('shop_id', $shop->id)->count(),
            'sales' => Order::withoutGlobalScope('tenant')->where('shop_id', $shop->id)->where('payment_status', 'confirmed')->sum('total'),
            'sms' => SmsInbox::withoutGlobalScope('tenant')->where('shop_id', $shop->id)->count(),
        ];

        $users = User::where('shop_id', $shop->id)->get();

        return view('super-admin.show', compact('shop', 'stats', 'users'));
    }

    /**
     * 🎭 دخول كتاجر (Impersonate)
     */
    public function impersonate(Shop $shop)
    {
        $admin = auth()->user();

        if (!$admin || $admin->role !== 'super_admin') {
            abort(403);
        }

        // ابحث عن مدير المتجر (متوافق مع SQLite + PostgreSQL)
        $targetUser = User::where('shop_id', $shop->id)
            ->whereIn('role', ['shop_admin', 'owner', 'staff'])
            ->get()
            ->sortBy(function ($u) {
                $priority = ['shop_admin' => 1, 'owner' => 2, 'staff' => 3];
                return $priority[$u->role] ?? 99;
            })
            ->first();

        if (!$targetUser) {
            return back()->with('error', "لا يوجد مستخدم مرتبط بمتجر: {$shop->name}");
        }

        // 🎭 سجّل الدخول كالتاجر (بطريقة موثوقة)
        $adminId = auth()->id();
        $adminName = auth()->user()->name;

        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        auth()->login($targetUser);
        request()->session()->regenerate();

        // خزّن بيانات المشرف للعودة
        session([
            'impersonate' => [
                'admin_id'   => $adminId,
                'admin_name' => $adminName,
                'shop_id'    => $shop->id,
                'shop_name'  => $shop->name,
                'started_at' => now()->toIso8601String(),
            ],
            'preferred_shop_id' => $shop->id,
        ]);

        return redirect('/dashboard')
            ->with('success', "🎭 أنت الآن تتصفح كـ: {$shop->name}");
    }

    /**
     * 🚪 الخروج من وضع Impersonate
     */
    public function stopImpersonating()
    {
        $data = session('impersonate');

        if (!$data || !isset($data['admin_id'])) {
            return redirect('/dashboard');
        }

        $admin = User::find($data['admin_id']);

        if (!$admin) {
            session()->forget('impersonate');
            return redirect('/login');
        }

        // 🚪 خروج كامل من جلسة التاجر
        auth()->logout();

        // 🔐 جلسة جديدة نظيفة
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        // 👑 دخول كمشرف
        auth()->login($admin);
        request()->session()->regenerate();

        // مسح كل شيء متعلق بـimpersonate
        session()->forget(['impersonate', 'preferred_shop_id']);

        return redirect('/super-admin')
            ->with('success', "✅ عدت إلى لوحة مدير المنصة");
    }

    /**
     * 👥 إدارة كل مستخدمي المنصة
     */
    public function users(\Illuminate\Http\Request $request)
    {
        $query = User::with('shop')->latest();

        // 🔍 بحث
        if ($q = $request->get('q')) {
            $query->where(function ($x) use ($q) {
                $x->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        // 🎭 فلتر الدور
        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        // 🏪 فلتر المتجر
        if ($shopId = $request->get('shop_id')) {
            $query->where('shop_id', $shopId);
        }

        $users = $query->paginate(25)->withQueryString();

        // 📊 إحصائيات
        $stats = [
            'total'        => User::count(),
            'super_admin'  => User::where('role', 'super_admin')->count(),
            'shop_admin'   => User::where('role', 'shop_admin')->count(),
            'staff'        => User::where('role', 'staff')->count(),
        ];

        // 🎯 قوائم الفلاتر
        $roles = User::select('role')->distinct()->pluck('role')->filter()->values();
        $shops = Shop::orderBy('name')->get();

        return view('super-admin.users.index', compact('users', 'stats', 'roles', 'shops'));
    }

    /**
     * 👤 عرض تفاصيل مستخدم
     */
    public function showUser(User $user)
    {
        // 🏪 المتجر (إن وُجد)
        $shop = $user->shop;

        // 📊 إحصائيات المستخدم
        $stats = [
            'orders_count'   => \App\Models\Order::withoutGlobalScope('tenant')
                                    ->where('customer_phone', $user->phone)
                                    ->count(),
            'orders_total'   => \App\Models\Order::withoutGlobalScope('tenant')
                                    ->where('customer_phone', $user->phone)
                                    ->where('payment_status', 'confirmed')
                                    ->sum('total'),
        ];

        // 📦 آخر الطلبات (إن كان عميلاً)
        $recentOrders = \App\Models\Order::withoutGlobalScope('tenant')
            ->where('customer_phone', $user->phone)
            ->latest()
            ->take(10)
            ->get();

        // 🎯 آخر نشاطات المستخدم (إن كان موظفاً)
        $activityLogs = \App\Models\ActivityLog::where('user_id', $user->id)
            ->latest()
            ->take(15)
            ->get();

        return view('super-admin.users.show', compact('user', 'shop', 'stats', 'recentOrders', 'activityLogs'));
    }

    /**
     * ➕ نموذج إضافة مستخدم
     */
    public function createUser()
    {
        $shops = \App\Models\Shop::orderBy('name')->get();
        return view('super-admin.users.create', compact('shops'));
    }

    /**
     * 💾 حفظ مستخدم جديد
     */
    public function storeUser(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:30',
            'role'     => 'required|in:super_admin,shop_admin,staff',
            'shop_id'  => 'nullable|exists:shops,id',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($data['role'] === 'shop_admin' || $data['role'] === 'staff') {
            if (empty($data['shop_id'])) {
                return back()->withErrors(['shop_id' => 'يجب اختيار متجر لهذا الدور'])->withInput();
            }
        }

        \App\Models\User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'role'     => $data['role'],
            'shop_id'  => $data['role'] === 'super_admin' ? null : $data['shop_id'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
        ]);

        return redirect('/super-admin/users')->with('success', "✅ تم إنشاء المستخدم: {$data['name']}");
    }

    /**
     * 🔑 نموذج إعادة تعيين كلمة المرور
     */
    public function showResetPassword(\App\Models\User $user)
    {
        return view('super-admin.users.reset-password', compact('user'));
    }

    /**
     * 🔄 إعادة تعيين كلمة المرور
     */
    public function resetPassword(\Illuminate\Http\Request $request, \App\Models\User $user)
    {
        $data = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
        ]);

        return redirect('/super-admin/users/' . $user->id)
            ->with('success', "🔑 تم تحديث كلمة مرور: {$user->name}");
    }
}
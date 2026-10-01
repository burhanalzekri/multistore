<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shop;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    // ═══════════════════════════════════
    // تسجيل / دخول / خروج
    // ═══════════════════════════════════
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->role === 'customer') {
            return redirect('/account');
        }
        return view('customer.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($data, $request->boolean('remember'))) {
            $user = Auth::user();
            // إذا كان المدير يدخل من صفحة العملاء — نعيده للوحة التحكم
            if (in_array($user->role, ['shop_admin', 'super_admin', 'staff'])) {
                return redirect('/dashboard');
            }
            $user->update(['last_login_at' => now()]);
            $request->session()->regenerate();
            return redirect('/account');
        }

        return back()->withErrors(['email' => 'البريد أو كلمة المرور غير صحيحة'])->withInput();
    }

    public function showRegister()
    {
        if (Auth::check() && Auth::user()->role === 'customer') {
            return redirect('/account');
        }
        return view('customer.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:30',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();

        $user = \App\Models\User::create([
            'shop_id' => $shop?->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
        ]);

        Auth::login($user);

        return redirect('/account')->with('success', 'مرحبًا بك! تم إنشاء حسابك 🎉');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/shop');
    }

    // ═══════════════════════════════════
    // لوحة الحساب
    // ═══════════════════════════════════
    public function dashboard()
    {
        $user = Auth::user();
        if ($user->role !== 'customer') return redirect('/dashboard');

        $orders = Order::withoutGlobalScope('tenant')
            ->where('user_id', $user->id)
            ->orWhere(function($q) use ($user) {
                $q->whereNull('user_id')->where('customer_phone', $user->phone);
            })
            ->with('items')
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'orders' => Order::withoutGlobalScope('tenant')
                ->where(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('customer_phone', $user->phone);
                })->count(),
            'wishlist' => Wishlist::withoutGlobalScope('tenant')
                ->where(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('session_id', session('wishlist_id'));
                })->count(),
            'pending' => Order::withoutGlobalScope('tenant')
                ->where(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('customer_phone', $user->phone);
                })
                ->where('payment_status', 'pending')
                ->count(),
            'total_spent' => Order::withoutGlobalScope('tenant')
                ->where(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('customer_phone', $user->phone);
                })
                ->where('payment_status', 'confirmed')
                ->sum('total'),
        ];

        return view('customer.dashboard', compact('user', 'orders', 'stats'));
    }

    public function orders(Request $request)
    {
        $query = \App\Models\Order::where('user_id', auth()->id());

        // فلترة بالفترة
        $period = $request->get('period', 'all');
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'yesterday':
                $query->whereDate('created_at', today()->subDay());
                break;
            case 'week':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case 'month':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case 'year':
                $query->where('created_at', '>=', now()->subYear());
                break;
            case 'custom':
                if ($request->filled('from')) {
                    $query->where('created_at', '>=', $request->from);
                }
                if ($request->filled('to')) {
                    $query->where('created_at', '<=', $request->to . ' 23:59:59');
                }
                break;
        }

        // فلترة بالحالة
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // الإحصائيات
        $stats = [
            'total' => (clone $query)->count(),
            'sum' => (clone $query)->sum('total'),
            'pending' => (clone $query)->where('status', 'awaiting_payment')->count(),
            'delivered' => (clone $query)->where('status', 'delivered')->count(),
        ];

        $orders = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('storefront.orders', compact('orders', 'stats', 'period'));
    }

    public function orderDetail($id)
    {
        $user = Auth::user();
        $order = Order::withoutGlobalScope('tenant')
            ->with('items')
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_phone', $user->phone);
            })
            ->findOrFail($id);

        return view('customer.order-detail', compact('order'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // تغيير كلمة المرور
        if ($data['password'] ?? null) {
            if (!$data['current_password'] || !Hash::check($data['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة'])->withInput();
            }
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        unset($data['current_password']);

        $user->update($data);

        return back()->with('success', '✅ تم حفظ التعديلات');
    }

    public function wishlist()
    {
        $user = Auth::user();
        $ids = Wishlist::withoutGlobalScope('tenant')
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('session_id', session('wishlist_id'));
            })
            ->pluck('product_id');

        $products = \App\Models\Product::withoutGlobalScope('tenant')
            ->whereIn('id', $ids)
            ->get();

        return view('customer.wishlist', compact('products'));
    }
}

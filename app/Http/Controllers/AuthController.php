<?php
namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($data, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // ⭐ ربط الدخول بالمتجر: خزّن shop_id في الجلسة
            if ($user->shop_id) {
                session(['preferred_shop_id' => $user->shop_id]);
            } else {
                // super_admin ليس له متجر - يُختار الأول افتراضياً
                if ($user->role === 'super_admin') {
                    $firstShop = Shop::where('status', 'active')->orderBy('id')->first();
                    if ($firstShop) {
                        session(['preferred_shop_id' => $firstShop->id]);
                    }
                }
            }

            // 🎯 توجيه ذكي حسب الدور
            if ($user->role === 'super_admin') {
                return redirect()->intended('/dashboard');
            } elseif ($user->role === 'shop_admin') {
                return redirect()->intended('/dashboard');
            } elseif ($user->role === 'staff') {
                return redirect()->intended('/dashboard');
            } else {
                return redirect()->intended('/shop');
            }
        }

        return back()->withErrors(['email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'])->withInput();
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'phone'         => 'required|string|max:30',
            'whatsapp'      => 'nullable|string|max:30',
            'shop_name'     => 'required|string|max:255',
            'description'   => 'nullable|string|max:500',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'country'       => 'nullable|string|max:100',
            'password'      => 'required|string|min:6|confirmed',
            'logo'          => 'nullable|file|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ], [
            'logo.mimes' => 'الشعار يجب أن يكون PNG أو JPG أو SVG أو WebP',
            'logo.max'   => 'حجم الشعار يجب أن يكون أقل من 2MB',
        ]);

        // 1) أنشئ المتجر
        $shop = Shop::create([
            'name'          => $data['shop_name'],
            'slug'          => Str::slug($data['shop_name']) . '-' . Str::lower(Str::random(5)),
            'webhook_token' => Str::random(64),
            'phone'         => $data['phone'],
            'whatsapp'      => $data['whatsapp'] ?? $data['phone'],
            'email'         => $data['email'],
            'address'       => $data['address'],
            'city'          => $data['city'],
            'country'       => $data['country'] ?? 'اليمن',
            'description'   => $data['description'] ?? null,
            'status'        => 'trial',
            'trial_ends_at' => now()->addDays(14),
        ]);

        // 1.5) 🎨 حفظ الشعار إذا رُفع
        if ($request->hasFile('logo')) {
            try {
                $logoFile = $request->file('logo');
                $logoName = 'shop-' . $shop->id . '-' . time() . '.' . $logoFile->getClientOriginalExtension();
                $logoPath = $logoFile->storeAs('logos', $logoName, 'public');

                $shop->logo = $logoPath;
                $shop->save();

                \Log::info('✅ تم رفع شعار المتجر: ' . $shop->name . ' — ' . $logoPath);
            } catch (\Throwable $e) {
                \Log::warning('⚠️ فشل رفع الشعار: ' . $e->getMessage());
                // لا نُوقف التسجيل
            }
        }

        // 2) أنشئ المستخدم (مدير المتجر)
        $user = User::create([
            'shop_id'  => $shop->id,
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'password' => Hash::make($data['password']),
            'role'     => 'shop_admin',
        ]);

        // 3) سجّل الدخول
        Auth::login($user);
        $request->session()->regenerate();

        // 4) اختر المتجر الجديد
        session(['preferred_shop_id' => $shop->id]);

        // 5) 🔔 أرسل إشعاراً للمشرف العام
        try {
            \App\Models\AdminNotification::notifySuperAdmins(
                'new_shop',
                '🏪 متجر جديد تم تسجيله!',
                "متجر: {$shop->name}\nصاحب المتجر: {$user->name}\nالبريد: {$user->email}\nالهاتف: {$user->phone}\nالمدينة: {$shop->city}",
                [
                    'shop_id' => $shop->id,
                    'shop_name' => $shop->name,
                    'owner_name' => $user->name,
                    'owner_email' => $user->email,
                    'owner_phone' => $user->phone,
                    'city' => $shop->city,
                    'address' => $shop->address,
                ]
            );
        } catch (\Throwable $e) {
            \Log::error('فشل إرسال إشعار التسجيل', ['error' => $e->getMessage()]);
        }

        return redirect('/dashboard')->with('celebrate', '🎉 مبروك! تم إنشاء متجرك: ' . $shop->name);
    }

    /**
     * تبديل المتجر (للمشرف العام فقط أو التاجر)
     */
    public function switchShop(Request $request, $shopId)
    {
        $shop = Shop::findOrFail($shopId);
        $user = Auth::user();

        // تحقق من الصلاحية
        if (!$user) {
            return redirect('/login');
        }

        // super_admin: يبدّل لأي متجر
        // shop_admin/staff: فقط لمتجره
        if ($user->role !== 'super_admin' && $user->shop_id !== $shop->id) {
            abort(403, 'غير مصرح لك بالتبديل لهذا المتجر');
        }

        session(['preferred_shop_id' => $shop->id]);

        return redirect('/shop')->with('success', 'تم التبديل إلى: ' . $shop->name);
    }

    /**
     * عرض قائمة المتاجر (للمشرف العام)
     */
    public function listShops()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        // super_admin يرى كل المتاجر
        if ($user->role === 'super_admin') {
            $shops = Shop::orderBy('name')->get();
        } else {
            // باقي المستخدمين يرون المتاجر التي يملكونها/يعملون بها
            $shops = Shop::where('id', $user->shop_id)
                ->orWhereHas('users', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                })
                ->orderBy('name')
                ->get();

            // إن لم يكن مرتبطاً بأي متجر — نعرض المتجر الذي يملكه (shop_id)
            if ($shops->isEmpty() && $user->shop_id) {
                $shops = Shop::where('id', $user->shop_id)->get();
            }
        }

        return view('auth.shops', compact('shops'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

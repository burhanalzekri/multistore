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
            return redirect()->intended('/dashboard');
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'shop_name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // أنشئ المتجر
        $shop = Shop::create([
            'name' => $data['shop_name'],
            'slug' => Str::slug($data['shop_name']) . '-' . Str::random(4),
            'webhook_token' => Str::random(64),
            'phone' => $data['phone'] ?? null,
            'status' => 'trial',
            'trial_ends_at' => now()->addDays(14),
        ]);

        // أنشئ المستخدم
        $user = User::create([
            'shop_id' => $shop->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'shop_admin',
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'مرحبًا بك! تم إنشاء متجرك بنجاح');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

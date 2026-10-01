<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * 🔒 صفحة تغيير كلمة المرور
     */
    public function showPasswordForm()
    {
        return view('dashboard.profile.password');
    }

    /**
     * 🔄 تحديث كلمة المرور (يتحقق من القديمة)
     */
    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'password.required'         => 'كلمة المرور الجديدة مطلوبة',
            'password.min'              => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
            'password.confirmed'        => 'تأكيد كلمة المرور غير مطابق',
        ]);

        $user = auth()->user();

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => '⚠️ كلمة المرور الحالية غير صحيحة'
            ]);
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', '✅ تم تحديث كلمة المرور بنجاح');
    }
}

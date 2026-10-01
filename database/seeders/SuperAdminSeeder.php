<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * 🔑 إنشاء / تحديث حساب Super Admin تلقائياً
     *
     * Idempotent — يمكن تشغيله عند كل Deploy بأمان
     */
    public function run(): void
    {
        $email    = env('SUPER_ADMIN_EMAIL', 'super@admin.com');
        $password = env('SUPER_ADMIN_PASSWORD', 'admin123');
        $name     = env('SUPER_ADMIN_NAME', 'Super Admin');

        $user = User::where('email', $email)->first();

        if (!$user) {
            User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make($password),
                'role'     => 'super_admin',
                'shop_id'  => null,
            ]);
            $this->command->info("✅ Super Admin created: {$email}");
        } else {
            // تأكد من أن الدور صحيح
            if ($user->role !== 'super_admin' || $user->shop_id !== null) {
                $user->update([
                    'role'    => 'super_admin',
                    'shop_id' => null,
                ]);
                $this->command->info("✅ Super Admin role updated: {$email}");
            } else {
                $this->command->info("ℹ️  Super Admin already exists: {$email}");
            }

            // حدّث كلمة المرور فقط إن كانت env مختلفة عن الافتراضية
            if (env('SUPER_ADMIN_FORCE_PASSWORD_RESET', false)) {
                $user->update(['password' => Hash::make($password)]);
                $this->command->info("🔑 Password reset: {$email}");
            }
        }
    }
}

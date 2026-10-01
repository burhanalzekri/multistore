<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // إضافة حقول للعملاء
        if (!Schema::hasColumn('users', 'address')) {
            Schema::table('users', function (Blueprint $t) {
                $t->string('address')->nullable();
                $t->string('city')->nullable();
                $t->string('avatar')->nullable();
                $t->timestamp('last_login_at')->nullable();
            });
        }

        // ربط المفضلة بحساب العميل
        if (!Schema::hasColumn('wishlists', 'user_id')) {
            Schema::table('wishlists', function (Blueprint $t) {
                $t->foreignId('user_id')->nullable()->after('shop_id')->constrained()->cascadeOnDelete();
            });
        }

        // ربط الطلبات بحساب العميل
        if (!Schema::hasColumn('orders', 'user_id')) {
            Schema::table('orders', function (Blueprint $t) {
                $t->foreignId('user_id')->nullable()->after('shop_id')->constrained()->nullOnDelete();
            });
        }
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn(['address', 'city', 'avatar', 'last_login_at']);
        });
        Schema::table('wishlists', function (Blueprint $t) {
            $t->dropForeign(['user_id']);
            $t->dropColumn('user_id');
        });
        Schema::table('orders', function (Blueprint $t) {
            $t->dropForeign(['user_id']);
            $t->dropColumn('user_id');
        });
    }
};

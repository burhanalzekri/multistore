<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // ⚡ عروض فلاش
        if (!Schema::hasTable('flash_sales')) {
            Schema::create('flash_sales', function (Blueprint $t) {
                $t->id();
                $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
                $t->foreignId('product_id')->constrained()->cascadeOnDelete();
                $t->decimal('discount_price', 12, 2);
                $t->integer('max_qty')->default(0);
                $t->integer('sold_qty')->default(0);
                $t->timestamp('starts_at');
                $t->timestamp('ends_at');
                $t->boolean('is_active')->default(true);
                $t->timestamps();
            });
        }

        // 🎁 نقاط الولاء
        if (!Schema::hasTable('loyalty_points')) {
            Schema::create('loyalty_points', function (Blueprint $t) {
                $t->id();
                $t->foreignId('user_id')->constrained()->cascadeOnDelete();
                $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
                $t->integer('balance')->default(0);
                $t->integer('total_earned')->default(0);
                $t->integer('total_redeemed')->default(0);
                $t->timestamps();
            });

            Schema::create('loyalty_transactions', function (Blueprint $t) {
                $t->id();
                $t->foreignId('user_id')->constrained()->cascadeOnDelete();
                $t->integer('points');
                $t->string('type'); // earned, redeemed
                $t->string('reason');
                $t->unsignedBigInteger('reference_id')->nullable();
                $t->timestamps();
            });
        }

        // ⭐ صور التقييمات
        if (!Schema::hasColumn('reviews', 'image')) {
            Schema::table('reviews', function (Blueprint $t) {
                $t->string('image')->nullable();
                $t->integer('helpful_count')->default(0);
            });
        }

        // 📍 دفتر العناوين
        if (!Schema::hasTable('customer_addresses')) {
            Schema::create('customer_addresses', function (Blueprint $t) {
                $t->id();
                $t->foreignId('user_id')->constrained()->cascadeOnDelete();
                $t->string('label'); // المنزل، العمل
                $t->string('name');
                $t->string('phone');
                $t->string('city');
                $t->string('area');
                $t->text('address');
                $t->boolean('is_default')->default(false);
                $t->timestamps();
            });
        }

        // 🔥 الأكثر مبيعًا
        if (!Schema::hasColumn('products', 'sold_count')) {
            Schema::table('products', function (Blueprint $t) {
                $t->integer('sold_count')->default(0);
                $t->integer('views_count')->default(0);
            });
        }

        // 👁️ شوهدت مؤخرًا
        if (!Schema::hasTable('product_views')) {
            Schema::create('product_views', function (Blueprint $t) {
                $t->id();
                $t->foreignId('product_id')->constrained()->cascadeOnDelete();
                $t->string('session_id');
                $t->timestamps();
            });
        }

        // 💝 تنبيهات الأسعار
        if (!Schema::hasTable('price_alerts')) {
            Schema::create('price_alerts', function (Blueprint $t) {
                $t->id();
                $t->foreignId('product_id')->constrained()->cascadeOnDelete();
                $t->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
                $t->string('email')->nullable();
                $t->string('phone')->nullable();
                $t->decimal('target_price', 12, 2);
                $t->boolean('notified')->default(false);
                $t->timestamps();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('price_alerts');
        Schema::dropIfExists('product_views');
        Schema::dropIfExists('customer_addresses');
        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('loyalty_points');
        Schema::dropIfExists('flash_sales');
    }
};

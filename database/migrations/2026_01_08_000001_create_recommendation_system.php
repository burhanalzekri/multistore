<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // تتبع سلوك العميل
        if (!Schema::hasTable('customer_events')) {
            Schema::create('customer_events', function (Blueprint $t) {
                $t->id();
                $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
                $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $t->string('session_id')->nullable();
                $t->string('event_type'); // view, add_to_cart, remove_from_cart, purchase, search, wishlist
                $t->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
                $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
                $t->decimal('price_at_event', 12, 2)->nullable();
                $t->string('search_query')->nullable();
                $t->integer('duration_seconds')->nullable();
                $t->json('meta')->nullable();
                $t->timestamps();
                $t->index(['shop_id', 'user_id', 'event_type']);
                $t->index(['shop_id', 'product_id']);
                $t->index('created_at');
            });
        }

        // ملف تعريف العميل
        if (!Schema::hasTable('customer_profiles')) {
            Schema::create('customer_profiles', function (Blueprint $t) {
                $t->id();
                $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
                $t->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
                $t->string('session_id')->nullable();
                $t->json('preferred_categories')->nullable(); // التصنيفات المفضلة
                $t->json('preferred_price_range')->nullable(); // نطاق السعر
                $t->json('top_viewed_products')->nullable();
                $t->integer('total_views')->default(0);
                $t->integer('total_cart_adds')->default(0);
                $t->integer('total_purchases')->default(0);
                $t->decimal('total_spent', 12, 2)->default(0);
                $t->timestamp('last_activity_at')->nullable();
                $t->timestamps();
                $t->unique(['shop_id', 'user_id']);
                $t->index(['shop_id', 'session_id']);
            });
        }

        // توصيات محسوبة مسبقًا
        if (!Schema::hasTable('product_recommendations')) {
            Schema::create('product_recommendations', function (Blueprint $t) {
                $t->id();
                $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
                $t->foreignId('product_id')->constrained()->cascadeOnDelete();
                $t->json('similar_product_ids')->nullable();
                $t->json('bought_together_ids')->nullable();
                $t->integer('view_count')->default(0);
                $t->integer('cart_count')->default(0);
                $t->integer('purchase_count')->default(0);
                $t->decimal('conversion_rate', 5, 2)->default(0);
                $t->timestamps();
                $t->unique(['shop_id', 'product_id']);
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('product_recommendations');
        Schema::dropIfExists('customer_profiles');
        Schema::dropIfExists('customer_events');
    }
};

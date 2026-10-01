<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // تعديل جدول categories
        if (!Schema::hasColumn('categories', 'icon')) {
            Schema::table('categories', function (Blueprint $t) {
                $t->string('icon')->nullable();
                $t->text('description')->nullable();
                $t->boolean('is_active')->default(true);
            });
        }

        // جدول التقييمات
        Schema::create('reviews', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $t->string('customer_name');
            $t->string('customer_phone')->nullable();
            $t->unsignedTinyInteger('rating');
            $t->text('comment')->nullable();
            $t->boolean('is_approved')->default(false);
            $t->timestamps();
        });

        // جدول المفضلة
        Schema::create('wishlists', function (Blueprint $t) {
            $t->id();
            $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('session_id');
            $t->timestamps();
            $t->index(['session_id', 'product_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('reviews');
    }
};

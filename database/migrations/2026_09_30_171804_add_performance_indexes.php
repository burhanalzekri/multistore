<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 🎯 Phase 1 — High-Impact Performance Indexes
 * 
 * Added based on:
 * - Sentry production observations
 * - Real query patterns in MultiStore
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─── orders ───────────────────────────────────
        Schema::table('orders', function (Blueprint $table) {
            $table->index('shop_id', 'orders_shop_id_index');
            $table->index(['shop_id', 'status'], 'orders_shop_id_status_index');
            $table->index(['shop_id', 'created_at'], 'orders_shop_id_created_at_index');
        });

        // ─── products ─────────────────────────────────
        Schema::table('products', function (Blueprint $table) {
            $table->index(['shop_id', 'is_active'], 'products_shop_id_is_active_index');
            $table->index(['shop_id', 'category_id'], 'products_shop_id_category_id_index');
        });

        // ─── reviews ──────────────────────────────────
        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['product_id', 'is_approved'], 'reviews_product_id_is_approved_index');
            $table->index(['shop_id', 'is_approved'], 'reviews_shop_id_is_approved_index');
        });

        // ─── sms_inbox ────────────────────────────────
        Schema::table('sms_inbox', function (Blueprint $table) {
            $table->index(['shop_id', 'status'], 'sms_inbox_shop_id_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_shop_id_index');
            $table->dropIndex('orders_shop_id_status_index');
            $table->dropIndex('orders_shop_id_created_at_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_shop_id_is_active_index');
            $table->dropIndex('products_shop_id_category_id_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_product_id_is_approved_index');
            $table->dropIndex('reviews_shop_id_is_approved_index');
        });

        Schema::table('sms_inbox', function (Blueprint $table) {
            $table->dropIndex('sms_inbox_shop_id_status_index');
        });
    }
};

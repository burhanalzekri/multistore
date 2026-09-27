<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'variant_id')) {
                $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
            }
            if (!Schema::hasColumn('order_items', 'color')) {
                $table->string('color', 50)->nullable()->after('variant_id');
            }
            if (!Schema::hasColumn('order_items', 'color_hex')) {
                $table->string('color_hex', 20)->nullable()->after('color');
            }
            if (!Schema::hasColumn('order_items', 'size')) {
                $table->string('size', 50)->nullable()->after('color_hex');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            foreach (['variant_id','color','color_hex','size'] as $col) {
                if (Schema::hasColumn('order_items', $col)) $table->dropColumn($col);
            }
        });
    }
};

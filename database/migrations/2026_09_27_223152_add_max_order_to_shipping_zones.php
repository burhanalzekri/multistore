<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_zones', function (Blueprint $table) {
            if (!Schema::hasColumn('shipping_zones', 'max_order_amount')) {
                $table->decimal('max_order_amount', 12, 2)->nullable()->after('free_over');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shipping_zones', function (Blueprint $table) {
            if (Schema::hasColumn('shipping_zones', 'max_order_amount')) {
                $table->dropColumn('max_order_amount');
            }
        });
    }
};

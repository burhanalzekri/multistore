<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'points_used')) {
                $table->integer('points_used')->default(0)->after('discount');
            }
            if (!Schema::hasColumn('orders', 'points_value')) {
                $table->decimal('points_value', 10, 2)->default(0)->after('points_used');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            foreach (['points_used', 'points_value'] as $col) {
                if (Schema::hasColumn('orders', $col)) $table->dropColumn($col);
            }
        });
    }
};

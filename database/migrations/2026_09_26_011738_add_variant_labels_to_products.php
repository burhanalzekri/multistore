<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'variant_label_1')) {
                $table->string('variant_label_1', 50)->nullable()->after('colors');
            }
            if (!Schema::hasColumn('products', 'variant_label_2')) {
                $table->string('variant_label_2', 50)->nullable()->after('variant_label_1');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'variant_label_1')) $table->dropColumn('variant_label_1');
            if (Schema::hasColumn('products', 'variant_label_2')) $table->dropColumn('variant_label_2');
        });
    }
};

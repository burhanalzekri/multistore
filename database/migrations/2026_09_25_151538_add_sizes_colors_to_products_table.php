<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'sizes')) {
                $table->json('sizes')->nullable()->after('stock');
            }
            if (!Schema::hasColumn('products', 'colors')) {
                $table->json('colors')->nullable()->after('sizes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sizes', 'colors']);
        });
    }
};

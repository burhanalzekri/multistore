<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'shipping_status')) {
                $table->string('shipping_status')->default('calculated')->after('shipping');
            }
            if (!Schema::hasColumn('orders', 'shipping_note')) {
                $table->text('shipping_note')->nullable()->after('shipping_status');
            }
            if (!Schema::hasColumn('orders', 'shipping_quoted_at')) {
                $table->timestamp('shipping_quoted_at')->nullable()->after('shipping_note');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = ['shipping_status', 'shipping_note', 'shipping_quoted_at'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('coupons', 'max_discount')) {
                $table->decimal('max_discount', 10, 2)->nullable()->after('value');
            }
            if (!Schema::hasColumn('coupons', 'per_user_limit')) {
                $table->integer('per_user_limit')->nullable()->after('max_uses');
            }
            if (!Schema::hasColumn('coupons', 'first_order_only')) {
                $table->boolean('first_order_only')->default(false)->after('per_user_limit');
            }
            if (!Schema::hasColumn('coupons', 'applies_to')) {
                $table->string('applies_to', 20)->default('all')->after('first_order_only'); // all | product | category
            }
            if (!Schema::hasColumn('coupons', 'applies_to_id')) {
                $table->unsignedBigInteger('applies_to_id')->nullable()->after('applies_to');
            }
            if (!Schema::hasColumn('coupons', 'starts_at')) {
                $table->datetime('starts_at')->nullable()->after('expires_at');
            }
            if (!Schema::hasColumn('coupons', 'description')) {
                $table->string('description', 255)->nullable()->after('code');
            }
            if (!Schema::hasColumn('coupons', 'min_qty')) {
                $table->integer('min_qty')->default(0)->after('min_order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            foreach (['max_discount','per_user_limit','first_order_only','applies_to','applies_to_id','starts_at','description','min_qty'] as $col) {
                if (Schema::hasColumn('coupons', $col)) $table->dropColumn($col);
            }
        });
    }
};

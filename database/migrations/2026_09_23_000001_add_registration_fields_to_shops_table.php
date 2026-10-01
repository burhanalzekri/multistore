<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            if (!Schema::hasColumn('shops', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('shops', 'address')) {
                $table->text('address')->nullable()->after('email');
            }
            if (!Schema::hasColumn('shops', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('shops', 'country')) {
                $table->string('country')->default('اليمن')->after('city');
            }
            if (!Schema::hasColumn('shops', 'description')) {
                $table->text('description')->nullable()->after('country');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['email', 'address', 'city', 'country', 'description']);
        });
    }
};

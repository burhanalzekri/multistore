<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('shops', 'smtp_settings')) {
            Schema::table('shops', function (Blueprint $t) {
                $t->json('smtp_settings')->nullable()->after('settings');
            });
        }
    }

    public function down(): void {
        Schema::table('shops', function (Blueprint $t) {
            $t->dropColumn('smtp_settings');
        });
    }
};

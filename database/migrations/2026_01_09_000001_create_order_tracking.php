<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('order_status_history')) {
            Schema::create('order_status_history', function (Blueprint $t) {
                $t->id();
                $t->foreignId('order_id')->constrained()->cascadeOnDelete();
                $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
                $t->string('from_status')->nullable();
                $t->string('to_status');
                $t->text('note')->nullable();
                $t->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
                $t->string('changed_by_name')->nullable();
                $t->timestamps();
                $t->index(['order_id', 'created_at']);
            });
        }

        if (!Schema::hasColumn('orders', 'tracking_number')) {
            Schema::table('orders', function (Blueprint $t) {
                $t->string('tracking_number')->nullable();
                $t->string('tracking_url')->nullable();
                $t->string('carrier')->nullable();
                $t->timestamp('shipped_at')->nullable();
                $t->timestamp('delivered_at')->nullable();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('order_status_history');
        Schema::table('orders', function (Blueprint $t) {
            $t->dropColumn(['tracking_number', 'tracking_url', 'carrier', 'shipped_at', 'delivered_at']);
        });
    }
};

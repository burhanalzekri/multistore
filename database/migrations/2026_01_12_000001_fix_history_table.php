<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // إذا الجدول المفرد موجود والجمع غير موجود
        if (Schema::hasTable('order_status_history') && !Schema::hasTable('order_status_histories')) {
            DB::statement('ALTER TABLE order_status_history RENAME TO order_status_histories');
            echo "✅ أُعيدت التسمية\n";
        }

        // إذا لا يوجد أي منهما — أنشئ الجمع
        if (!Schema::hasTable('order_status_histories')) {
            Schema::create('order_status_histories', function (Blueprint $t) {
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
            echo "✅ تم إنشاء الجدول\n";
        }
    }

    public function down(): void {
        Schema::dropIfExists('order_status_histories');
    }
};

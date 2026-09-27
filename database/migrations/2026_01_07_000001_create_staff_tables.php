<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('users', 'permissions')) {
            Schema::table('users', function (Blueprint $t) {
                $t->json('permissions')->nullable();
                $t->foreignId('created_by')->nullable()->after('permissions')->constrained('users')->nullOnDelete();
                $t->boolean('is_active')->default(true);
            });
        }

        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $t) {
                $t->id();
                $t->foreignId('shop_id')->nullable()->constrained()->cascadeOnDelete();
                $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $t->string('user_name')->nullable();
                $t->string('action');
                $t->string('subject_type')->nullable();
                $t->unsignedBigInteger('subject_id')->nullable();
                $t->string('description')->nullable();
                $t->json('meta')->nullable();
                $t->string('ip_address', 45)->nullable();
                $t->timestamps();
                $t->index(['shop_id', 'created_at']);
            });
        }

        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $t) {
                $t->id();
                $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
                $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $t->string('title');
                $t->text('description')->nullable();
                $t->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
                $t->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
                $t->timestamp('due_date')->nullable();
                $t->timestamp('completed_at')->nullable();
                $t->timestamps();
                $t->index(['shop_id', 'status']);
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('activity_logs');
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn(['permissions', 'created_by', 'is_active']);
        });
    }
};

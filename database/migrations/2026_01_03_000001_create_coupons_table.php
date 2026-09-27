<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('coupons', function (Blueprint $t) {
            $t->id();
            $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $t->string('code')->unique();
            $t->enum('type', ['percentage', 'fixed'])->default('percentage');
            $t->decimal('value', 12, 2);
            $t->decimal('min_order', 12, 2)->default(0);
            $t->integer('max_uses')->nullable();
            $t->integer('used_count')->default(0);
            $t->timestamp('expires_at')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('coupons'); }
};

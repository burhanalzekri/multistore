<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // ═══ خطط الاشتراك ═══
        Schema::create('plans', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            $t->decimal('price', 12, 2)->default(0);
            $t->enum('interval', ['monthly', 'yearly'])->default('monthly');
            $t->json('features')->nullable();
            $t->json('limits')->nullable();
            $t->boolean('is_active')->default(true);
            $t->integer('sort_order')->default(0);
            $t->timestamps();
        });

        // ═══ اشتراكات المتاجر ═══
        Schema::create('subscriptions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $t->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $t->enum('status', ['active', 'expired', 'cancelled', 'trial'])->default('trial');
            $t->timestamp('starts_at');
            $t->timestamp('ends_at')->nullable();
            $t->timestamp('trial_ends_at')->nullable();
            $t->timestamp('cancelled_at')->nullable();
            $t->string('payment_method')->nullable();
            $t->decimal('amount_paid', 12, 2)->default(0);
            $t->timestamps();
        });

        // ═══ فواتير المنصة ═══
        Schema::create('platform_invoices', function (Blueprint $t) {
            $t->id();
            $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $t->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            $t->string('invoice_number')->unique();
            $t->decimal('amount', 12, 2);
            $t->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
            $t->timestamp('due_date');
            $t->timestamp('paid_at')->nullable();
            $t->string('payment_reference')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('platform_invoices');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
    }
};

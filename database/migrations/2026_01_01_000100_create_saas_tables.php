<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shops', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('custom_domain')->nullable()->unique();
            $t->string('logo')->nullable();
            $t->string('primary_color')->default('#f59e0b');
            $t->string('phone')->nullable();
            $t->string('whatsapp')->nullable();
            $t->string('currency', 3)->default('YER');
            $t->string('locale', 5)->default('ar');
            $t->string('webhook_token', 64)->unique();
            $t->enum('status', ['active','suspended','trial'])->default('trial');
            $t->timestamp('trial_ends_at')->nullable();
            $t->json('settings')->nullable();
            $t->timestamps();
        });

        Schema::table('users', function (Blueprint $t) {
            $t->foreignId('shop_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $t->enum('role', ['super_admin','shop_admin','staff','customer'])->default('customer')->after('password');
            $t->string('phone')->nullable()->after('email');
        });

        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('slug');
            $t->timestamps();
            $t->unique(['shop_id','slug']);
        });

        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name');
            $t->string('slug');
            $t->text('description')->nullable();
            $t->decimal('price', 12, 2);
            $t->decimal('compare_price', 12, 2)->nullable();
            $t->integer('stock')->default(0);
            $t->string('image')->nullable();
            $t->json('images')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('payment_wallets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $t->string('provider');
            $t->string('wallet_number');
            $t->string('holder_name');
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $t->string('order_number')->unique();
            $t->string('customer_name');
            $t->string('customer_phone');
            $t->string('customer_address')->nullable();
            $t->text('notes')->nullable();
            $t->decimal('subtotal', 12, 2);
            $t->decimal('shipping', 12, 2)->default(0);
            $t->decimal('total', 12, 2);
            $t->string('currency', 3)->default('YER');
            $t->enum('payment_method', ['cod','wallet','bank'])->default('wallet');
            $t->enum('payment_status', ['pending','confirmed','rejected'])->default('pending');
            $t->enum('status', ['awaiting_payment','processing','shipped','delivered','cancelled'])->default('awaiting_payment');
            $t->string('payment_reference')->nullable();
            $t->timestamp('paid_at')->nullable();
            $t->timestamps();
        });

        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $t->string('product_name');
            $t->decimal('unit_price', 12, 2);
            $t->integer('quantity');
            $t->decimal('line_total', 12, 2);
        });

        Schema::create('sms_inbox', function (Blueprint $t) {
            $t->id();
            $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $t->string('sender_phone');
            $t->text('raw_body');
            $t->decimal('parsed_amount', 12, 2)->nullable();
            $t->string('parsed_sender')->nullable();
            $t->string('parsed_reference')->nullable();
            $t->string('provider')->nullable();
            $t->unsignedTinyInteger('confidence')->default(0);
            $t->foreignId('matched_order_id')->nullable()->constrained('orders')->nullOnDelete();
            $t->enum('status', ['pending','matched','review','rejected'])->default('pending');
            $t->timestamp('received_at');
            $t->timestamps();
        });

        Schema::create('payment_transactions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $t->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $t->unsignedBigInteger('sms_inbox_id')->nullable();
            $t->string('provider');
            $t->decimal('amount', 12, 2);
            $t->string('sender_phone');
            $t->string('reference_number')->nullable()->unique();
            $t->enum('status', ['pending','confirmed','rejected','duplicate'])->default('pending');
            $t->enum('verified_by', ['auto','manual'])->nullable();
            $t->timestamp('verified_at')->nullable();
            $t->timestamps();
        });

        Schema::create('sms_patterns', function (Blueprint $t) {
            $t->id();
            $t->string('provider')->index();
            $t->string('label');
            $t->string('amount_regex');
            $t->string('sender_regex');
            $t->string('reference_regex')->nullable();
            $t->json('keywords');
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        echo "  ✓ shops\n  ✓ users (updated)\n  ✓ products\n  ✓ orders\n  ✓ sms_inbox\n  ✓ payment_transactions\n  ✓ sms_patterns\n";
    }

    public function down(): void {
        Schema::dropIfExists('sms_patterns');
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('sms_inbox');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('payment_wallets');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::table('users', function (Blueprint $t) {
            $t->dropForeign(['shop_id']);
            $t->dropColumn(['shop_id','role','phone']);
        });
        Schema::dropIfExists('shops');
    }
};

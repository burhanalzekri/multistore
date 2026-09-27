<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');              // اسم المنطقة (صنعاء، عدن، ...)
            $table->string('code')->nullable();  // رمز (sanaa، aden، ...)
            $table->decimal('fee', 12, 2)->default(0);       // تكلفة الشحن
            $table->decimal('free_over', 12, 2)->nullable(); // شحن مجاني عند تجاوز المبلغ
            $table->decimal('min_order', 12, 2)->nullable(); // أدنى مبلغ للطلب
            $table->string('eta')->nullable();    // المدة المتوقعة (1-2 يوم)
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['shop_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_zones');
    }
};

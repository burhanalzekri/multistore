<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->nullable()->constrained('shops')->nullOnDelete();
            $table->string('name', 120);
            $table->string('email', 190)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('subject', 200)->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index(['shop_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};

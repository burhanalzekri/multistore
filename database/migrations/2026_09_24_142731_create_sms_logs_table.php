<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->nullable()->index();
            $table->string('to', 30);
            $table->text('message');
            $table->string('provider', 30)->default('log');
            $table->string('status', 20)->default('pending');
            $table->string('external_id')->nullable();
            $table->text('error')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};

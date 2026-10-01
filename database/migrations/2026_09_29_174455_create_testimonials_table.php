<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('role', 120)->nullable();
            $table->tinyInteger('rating')->default(5);
            $table->text('comment');
            $table->boolean('is_visible')->default(false);
            $table->string('ip', 45)->nullable();
            $table->timestamps();
            $table->index(['is_visible', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};

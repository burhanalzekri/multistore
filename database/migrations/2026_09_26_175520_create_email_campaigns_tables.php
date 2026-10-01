<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ═══ جدول الحملات ═══
        if (!Schema::hasTable('email_campaigns')) {
            Schema::create('email_campaigns', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('shop_id');
                $table->string('name', 150);
                $table->string('subject', 200);
                $table->text('body');
                $table->string('template', 50)->default('default');
                $table->string('status', 20)->default('draft'); // draft | scheduled | sending | sent | failed
                $table->string('target_type', 30)->default('all'); // all | tier | orders | points
                $table->string('target_value', 100)->nullable(); // tier name or value
                $table->integer('recipients_count')->default(0);
                $table->integer('sent_count')->default(0);
                $table->integer('failed_count')->default(0);
                $table->integer('opens_count')->default(0);
                $table->integer('clicks_count')->default(0);
                $table->datetime('scheduled_at')->nullable();
                $table->datetime('sent_at')->nullable();
                $table->timestamps();

                $table->index('shop_id');
                $table->index('status');
            });
        }

        // ═══ جدول المستلمين ═══
        if (!Schema::hasTable('email_campaign_recipients')) {
            Schema::create('email_campaign_recipients', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('campaign_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('email', 200);
                $table->string('name', 150)->nullable();
                $table->string('status', 20)->default('pending'); // pending | sent | failed | opened | clicked
                $table->text('error_message')->nullable();
                $table->datetime('sent_at')->nullable();
                $table->datetime('opened_at')->nullable();
                $table->datetime('clicked_at')->nullable();
                $table->timestamps();

                $table->index('campaign_id');
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('email_campaign_recipients');
        Schema::dropIfExists('email_campaigns');
    }
};

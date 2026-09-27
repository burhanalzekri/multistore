<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ✅ إصلاح: إزالة FK من sms_inbox.shop_id
     * السبب: PostgreSQL يرفض FK إلى جدول غير موجود أو يأخذ وقتاً طويلاً
     */
    public function up(): void
    {
        if (!Schema::hasTable('sms_inbox')) {
            return;
        }

        // في PostgreSQL — نحاول إزالة FK إذا موجود
        try {
            Schema::table('sms_inbox', function (Blueprint $table) {
                // نحاول حذف FK الموجود
                $table->dropForeign(['shop_id']);
            });
        } catch (\Throwable $e) {
            // إذا لم يوجد FK ← لا مشكلة
        }

        // نُعدّل العمود ليكون nullable بدون FK
        try {
            Schema::table('sms_inbox', function (Blueprint $table) {
                $table->unsignedBigInteger('shop_id')->nullable()->change();
            });
        } catch (\Throwable $e) {
            // إذا فشل ← نُكمل
        }
    }

    /**
     * Down — لا نفعل شيء
     */
    public function down(): void
    {
        // لا شيء
    }
};

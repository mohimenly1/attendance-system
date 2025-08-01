<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sessions', function ($table) {
            $table->string('id')->primary(); // معرف الجلسة (Primary Key)
            $table->foreignId('user_id')->nullable()->index(); // مرتبط بجدول users (إذا كان المستخدم مسجل الدخول)
            $table->string('ip_address', 45)->nullable(); // عنوان IP
            $table->text('user_agent')->nullable(); // معلومات متصفح/جهاز المستخدم
            $table->longText('payload'); // محتوى الجلسة (مشفّر)
            $table->integer('last_activity')->index(); // timestamp لآخر نشاط
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

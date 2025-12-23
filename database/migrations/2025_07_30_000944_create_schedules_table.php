<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تعطيل القيود الأجنبية مؤقتًا
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // إنشاء جدول schedules
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            // العلاقة مع المادة
            $table->foreignId('course_id')->constrained()->onDelete('cascade');

            // علاقة بالمدرس (teacher)
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');

            // علاقة بالقاعة الدراسية
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onDelete('set null');

            // اليوم والوقت
            $table->enum('day_of_week', ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday']);
            $table->time('start_time');
            $table->time('end_time');

            // ✅ إضافة الملاحظات (سبب الخطأ)
            $table->text('notes')->nullable();

            // حالة تفعيل الحصة
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        // إعادة تفعيل القيود الأجنبية بعد إنشاء الجداول
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

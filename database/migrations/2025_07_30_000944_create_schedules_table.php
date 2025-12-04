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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            // العلاقة مع المادة
            $table->foreignId('course_id')->constrained()->onDelete('cascade');

            // ✅ التعديلات الجديدة
            // علاقة بالمدرس (teacher)
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');

            // علاقة بالفصل الدراسي (semester)
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');

            // علاقة بالقاعة الدراسية
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onDelete('set null');

            // اليوم والوقت (المحتوى الأصلي محفوظ)
            $table->enum('day_of_week', ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday']);
            $table->time('start_time');
            $table->time('end_time');

            // ✅ حالة تفعيل الحصة
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

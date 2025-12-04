<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // إضافة أعمدة العلاقات إن لم تكن موجودة (بدون semester_id)
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'teacher_id')) {
                $table->foreignId('teacher_id')
                      ->nullable()
                      ->constrained('users')   // users.id
                      ->nullOnDelete()         // on delete set null
                      ->after('course_id');
            }

            if (!Schema::hasColumn('schedules', 'classroom_id')) {
                $table->foreignId('classroom_id')
                      ->nullable()
                      ->constrained('classrooms')
                      ->nullOnDelete()
                      ->after('teacher_id');   // لاحظ: بعد teacher_id مباشرة
            }

            if (!Schema::hasColumn('schedules', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('end_time');
            }
        });

        // إعادة تعريف اليوم كـ ENUM بدون Friday
        Schema::table('schedules', function (Blueprint $table) {
            $table->enum('day_of_week', [
                'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'
            ])->change();
        });
    }

    public function down(): void
    {
        // إرجاع التغييرات قدر الإمكان
        Schema::table('schedules', function (Blueprint $table) {
            // إسقاط العلاقات/الأعمدة إن كانت موجودة
            if (Schema::hasColumn('schedules', 'classroom_id')) {
                // يسقط FK والعمود معًا إن كان متاحًا
                try {
                    $table->dropConstrainedForeignId('classroom_id');
                } catch (\Throwable $e) {
                    // fallback لو dropConstrainedForeignId غير متوفر
                    try { $table->dropForeign('schedules_classroom_id_foreign'); } catch (\Throwable $e2) {}
                    $table->dropColumn('classroom_id');
                }
            }

            if (Schema::hasColumn('schedules', 'teacher_id')) {
                try {
                    $table->dropConstrainedForeignId('teacher_id');
                } catch (\Throwable $e) {
                    try { $table->dropForeign('schedules_teacher_id_foreign'); } catch (\Throwable $e2) {}
                    $table->dropColumn('teacher_id');
                }
            }

            if (Schema::hasColumn('schedules', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });

        // إعادة Friday للـ ENUM في حال الرجوع
        Schema::table('schedules', function (Blueprint $table) {
            $table->enum('day_of_week', [
                'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'
            ])->change();
        });
    }
};

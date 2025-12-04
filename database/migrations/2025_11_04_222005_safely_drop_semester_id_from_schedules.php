<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // لو العمود مش موجود، ما في داعي نكمل
        if (! Schema::hasColumn('schedules', 'semester_id')) {
            return;
        }

        // 1) ابحث عن اسم القيد الحقيقي (FK) إن وُجد
        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'schedules'
              AND COLUMN_NAME = 'semester_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");

        // 2) احذف الـFK لو موجود بأي اسم
        if ($fk && isset($fk->CONSTRAINT_NAME)) {
            DB::statement("ALTER TABLE `schedules` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`;");
        }

        // 3) الآن احذف العمود نفسه بأمان
        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'semester_id')) {
                $table->dropColumn('semester_id');
            }
        });
    }

    public function down(): void
    {
        // رجّع العمود (بدون إضافة FK)
        Schema::table('schedules', function (Blueprint $table) {
            if (! Schema::hasColumn('schedules', 'semester_id')) {
                $table->unsignedBigInteger('semester_id')->nullable()->after('classroom_id');
            }
        });

        // (اختياري) لو تريد إعادة FK، عدّل اسم الجدول المرجعي الصحيح وضعه هنا:
        // DB::statement("ALTER TABLE `schedules` ADD CONSTRAINT `schedules_semester_id_foreign`
        //     FOREIGN KEY (`semester_id`) REFERENCES `semesters`(`id`) ON DELETE SET NULL;");
    }
};

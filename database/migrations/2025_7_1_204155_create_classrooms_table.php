<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateClassroomsTable extends Migration
{
    public function up(): void
    {
        // تعطيل القيود الأجنبية مؤقتًا
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // إنشاء جدول classrooms
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();        // اسم القاعة
            $table->unsignedSmallInteger('capacity')->nullable(); // السعة
            $table->timestamps();
        });

        // إعادة تفعيل القيود الأجنبية بعد إنشاء الجدول
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        // حذف الجدول
        Schema::dropIfExists('classrooms');
    }
}

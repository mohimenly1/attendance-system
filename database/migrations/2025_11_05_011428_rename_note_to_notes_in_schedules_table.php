<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // لو عمود notes غير موجود، أضفه
        if (! Schema::hasColumn('schedules', 'notes')) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->text('notes')->nullable()->after('end_time');
            });
        }

        // لو كان عندك عمود note قديم، انقل البيانات منه ثم احذفه
        if (Schema::hasColumn('schedules', 'note')) {
            // انقل البيانات من note إلى notes
            DB::statement('UPDATE `schedules` SET `notes` = `note` WHERE `notes` IS NULL');

            // احذف العمود القديم
            Schema::table('schedules', function (Blueprint $table) {
                $table->dropColumn('note');
            });
        }
    }

    public function down(): void
    {
        // رجّع العكس: أنشئ note من جديد وانقل البيانات إليه ثم احذف notes
        if (! Schema::hasColumn('schedules', 'note')) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->text('note')->nullable()->after('end_time');
            });
        }

        if (Schema::hasColumn('schedules', 'notes')) {
            DB::statement('UPDATE `schedules` SET `note` = `notes` WHERE `note` IS NULL');

            Schema::table('schedules', function (Blueprint $table) {
                $table->dropColumn('notes');
            });
        }
    }
};

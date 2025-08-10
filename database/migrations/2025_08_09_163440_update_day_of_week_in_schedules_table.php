<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum column to include all days of the week
        DB::statement("ALTER TABLE schedules CHANGE COLUMN day_of_week day_of_week ENUM('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the old definition if needed
        DB::statement("ALTER TABLE schedules CHANGE COLUMN day_of_week day_of_week ENUM('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday') NOT NULL");
    }
};
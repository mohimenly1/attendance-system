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
        Schema::table('student_photos', function (Blueprint $table) {
            // Step 1: Drop the foreign key constraint first
            $table->dropForeign(['student_id']);
    
            // Step 2: Now drop the unique index
            $table->dropUnique(['student_id']);
    
            // Step 3: Re-add the foreign key constraint without the unique property
            $table->foreign('student_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_photos', function (Blueprint $table) {
            // Revert the changes in reverse order
            $table->dropForeign(['student_id']);
            $table->unique('student_id');
            $table->foreign('student_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};

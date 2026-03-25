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
        Schema::create('elective_group_courses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('elective_group_id')
                  ->constrained('elective_groups')
                  ->cascadeOnDelete();

            $table->foreignId('course_master_id')
                  ->constrained('course_masters')
                  ->cascadeOnDelete();

            $table->timestamps();

            // Prevent duplicate entries
            $table->unique(['elective_group_id', 'course_master_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elective_group_courses');
    }
};

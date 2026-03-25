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
        Schema::create('course_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_master_id')
                  ->constrained('course_masters')
                  ->cascadeOnDelete();

            $table->foreignId('department_id')
                  ->constrained('departments')
                  ->cascadeOnDelete();

            $table->foreignId('expert_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('moderator_id')
                  ->nullable() // in case role removed later
                  ->constrained('users')
                  ->nullOnDelete();

            $table->foreignId('assigned_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();

            // Prevent duplicate assignment
            $table->unique(['course_master_id', 'department_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_assignments');
    }
};

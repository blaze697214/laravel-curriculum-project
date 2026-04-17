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
        Schema::create('question_paper_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('syllabus_id')
                ->constrained('syllabus')
                ->cascadeOnDelete();

            // Unit + CO mapping (summary row)
            $table->foreignId('syllabus_unit_id')
                ->after('syllabus_id')
                ->constrained('syllabus_units')
                ->cascadeOnDelete();

            $table->foreignId('course_outcome_id')
            ->constrained('course_outcomes')
            ->cascadeOnDelete();

            // Marks
            $table->unsignedTinyInteger('marks_per_unit');
            $table->unsignedTinyInteger('adjusted_marks'); // (multipler)x value

            // Question-wise marks (fixed structure)
            $table->unsignedTinyInteger('q1_marks')->nullable();
            $table->unsignedTinyInteger('q2_marks')->nullable();
            $table->unsignedTinyInteger('q3_marks')->nullable();
            $table->unsignedTinyInteger('q4_marks')->nullable();
            $table->unsignedTinyInteger('q5_marks')->nullable();
            $table->unsignedTinyInteger('q6_marks')->nullable();

            $table->unsignedTinyInteger('order_no');

            $table->timestamps();

            // Prevent duplicate row per unit + CO
            $table->unique(['syllabus_id', 'unit_id']);
            // $table->unique(['syllabus_id', 'course_outcome_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_paper_profiles');
    }
};

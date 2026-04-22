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
        Schema::create('question_bits', function (Blueprint $table) {
             $table->id();

            $table->foreignId('syllabus_id')
                  ->constrained('syllabus')
                  ->cascadeOnDelete();

            // Unit + CO mapping
            $table->foreignId('syllabus_unit_id')
      ->constrained('syllabus_units')
      ->cascadeOnDelete();

            $table->foreignId('course_outcome_id')
                  ->constrained('course_outcomes')
                  ->cascadeOnDelete();

            // Question number (Q1–Q6)
            $table->unsignedTinyInteger('question_no');

            // Bit (a, b, c, d...)
            $table->string('bit_label'); // 'a', 'b', 'c', ...

            // Marks for this bit
            $table->unsignedTinyInteger('marks');

            $table->unsignedTinyInteger('order_no');

            $table->timestamps();

            // Prevent duplicate bit
            // $table->unique([
            //     'syllabus_id',
            //     'question_no',
            //     'bit_label'
            // ]);
            // $table->unique([
            //     'syllabus_id',
            //     'unit_id',
            //     'course_outcome_id'
            // ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_bits');
    }
};

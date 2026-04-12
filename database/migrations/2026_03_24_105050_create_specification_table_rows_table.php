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
        Schema::create('specification_table_rows', function (Blueprint $table) {
            $table->id();

            $table->foreignId('syllabus_id')
                ->constrained('syllabus')
                ->cascadeOnDelete();

            $table->foreignId('syllabus_unit_id')
                ->constrained('syllabus_units')
                ->cascadeOnDelete();

            $table->foreignId('course_outcome_id')
                ->nullable()
                ->constrained('course_outcomes')
                ->nullOnDelete();

            // Marks distribution (Bloom's taxonomy)
            $table->unsignedTinyInteger('remember_marks')->default(0);
            $table->unsignedTinyInteger('understand_marks')->default(0);
            $table->unsignedTinyInteger('apply_marks')->default(0);

            $table->unsignedTinyInteger('total_marks');

            $table->unsignedTinyInteger('order_no');

            $table->timestamps();

            // Prevent duplicate mapping
            $table->unique([
                'syllabus_id',
                'syllabus_unit_id',
            ]);
            $table->unique([
                'syllabus_id',
                'course_outcome_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specification_table_rows');
    }
};

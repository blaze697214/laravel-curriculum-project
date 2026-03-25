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
        Schema::create('co_po_pso_mappings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('syllabus_id')
                ->constrained('syllabus')
                ->cascadeOnDelete();

            $table->foreignId('course_outcome_id')
                ->constrained('course_outcomes')
                ->cascadeOnDelete();

            $table->foreignId('programme_outcome_id')
                ->constrained('programme_outcomes')
                ->cascadeOnDelete();

            // Mapping strength
            $table->unsignedTinyInteger('level');
            // e.g. 1 = low, 2 = medium, 3 = high

            $table->timestamps();

            // Prevent duplicate mapping
            $table->unique([
                'course_outcome_id',
                'programme_outcome_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('co_po_pso_mappings');
    }
};

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
        Schema::create('self_learnings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('section_id')
                  ->constrained('syllabus_sections')
                  ->cascadeOnDelete();

            $table->text('point_text');

            $table->unsignedTinyInteger('order_no');

            $table->timestamps();

            // One self-learning content per section
            $table->unique('section_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('self_learnings');
    }
};

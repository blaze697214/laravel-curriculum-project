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
        Schema::create('syllabus_sections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('syllabus_id')
                ->constrained('syllabus')
                ->cascadeOnDelete();

            $table->foreignId('section_template_id')
                ->constrained('section_templates')
                ->cascadeOnDelete();

            // Allow custom title override
            $table->string('title')->nullable();

            $table->unsignedTinyInteger('order_no');

            $table->timestamps();

            // Prevent duplicate template per syllabus (optional but good)
            $table->unique(['syllabus_id', 'section_template_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabus_sections');
    }
};

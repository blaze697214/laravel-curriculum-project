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
        Schema::create('practical_task_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practical_task_id')
                  ->constrained('practical_tasks')
                  ->cascadeOnDelete();

            $table->foreignId('unit_id')
                  ->constrained('syllabus_units')
                  ->cascadeOnDelete();

            $table->unique(['practical_task_id', 'unit_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practical_task_units');
    }
};

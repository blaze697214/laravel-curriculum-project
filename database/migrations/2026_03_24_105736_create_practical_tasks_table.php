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
        Schema::create('practical_tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('syllabus_id')
                ->constrained('syllabus')
                ->cascadeOnDelete();

            $table->foreignId('unit_id')
                ->nullable()
                ->constrained('syllabus_units')
                ->nullOnDelete();

            $table->text('lab_learning_outcome')->nullable();

            $table->text('exercise'); // experiment / practical description

            $table->unsignedTinyInteger('hours')->nullable();

            $table->unsignedTinyInteger('order_no');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practical_tasks');
    }
};

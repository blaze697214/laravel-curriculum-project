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
        Schema::create('syllabus_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('syllabus_id')
                  ->constrained('syllabus')
                  ->cascadeOnDelete();

            $table->unsignedTinyInteger('unit_no'); // Unit 1, 2, 3...

            $table->string('title');

            $table->unsignedTinyInteger('hours');

            $table->unsignedTinyInteger('order_no'); // for ordering

            $table->timestamps();

            // Prevent duplicate unit numbers
            $table->unique(['syllabus_id', 'unit_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabus_units');
    }
};

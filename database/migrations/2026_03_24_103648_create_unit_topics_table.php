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
        Schema::create('unit_topics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('syllabus_unit_id')
                  ->constrained('syllabus_units')
                  ->cascadeOnDelete();

            $table->enum('type', ['topic', 'learning_outcome']);

            $table->text('content');

            $table->unsignedTinyInteger('order_no');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_topics');
    }
};

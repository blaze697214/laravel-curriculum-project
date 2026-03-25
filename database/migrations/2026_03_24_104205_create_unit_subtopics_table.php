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
        Schema::create('unit_subtopics', function (Blueprint $table) {
             $table->id();

            $table->foreignId('unit_topic_id')
                  ->constrained('unit_topics')
                  ->cascadeOnDelete();

            $table->text('subtopic');

            $table->unsignedTinyInteger('order_no');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_subtopics');
    }
};

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
        Schema::create('websites', function (Blueprint $table) {
            $table->id();

            $table->foreignId('syllabus_id')
                  ->constrained('syllabus')
                  ->cascadeOnDelete();

            $table->string('url');

            $table->unsignedTinyInteger('order_no');

            $table->text('description');

            $table->timestamps();

            // Optional: prevent duplicate URLs per syllabus
            $table->unique(['syllabus_id', 'url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};

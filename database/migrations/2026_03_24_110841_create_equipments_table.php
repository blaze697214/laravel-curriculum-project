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
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('syllabus_id')
                  ->constrained('syllabus')
                  ->cascadeOnDelete();

            $table->string('equipment_name');

            $table->unsignedTinyInteger('experiment_no')->nullable();

            $table->unsignedTinyInteger('order_no');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};

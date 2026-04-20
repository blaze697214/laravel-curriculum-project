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
        Schema::create('syllabus_remarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('syllabus_id')
                  ->constrained('syllabus')
                  ->cascadeOnDelete();

            $table->text('remark');

            $table->foreignId('given_by')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabus_remarks');
    }
};

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
        Schema::create('class_award_rules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('scheme_id')
                  ->constrained('schemes')
                  ->cascadeOnDelete();

            $table->unsignedTinyInteger('total_subjects_required');

            $table->unsignedSmallInteger('total_marks_required');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_award_rules');
    }
};

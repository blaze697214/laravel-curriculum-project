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
        Schema::create('award_elective_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('award_config_id')
                ->constrained('class_award_configurations')
                ->cascadeOnDelete();

            $table->foreignId('elective_group_id')
                ->constrained('elective_groups')
                ->cascadeOnDelete();

            $table->timestamps();

            // Prevent duplicate mapping
            $table->unique(['award_config_id', 'elective_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('award_elective_groups');
    }
};

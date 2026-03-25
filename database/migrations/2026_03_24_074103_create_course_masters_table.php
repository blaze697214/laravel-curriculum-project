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
        Schema::create('course_masters', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('abbreviation');

            $table->foreignId('scheme_id')
                  ->constrained('schemes')
                  ->cascadeOnDelete();

            $table->foreignId('course_category_id')
                  ->constrained('course_categories')
                  ->cascadeOnDelete();

            // Hours
            $table->unsignedTinyInteger('iks_hours');
            $table->unsignedTinyInteger('cl_hours');
            $table->unsignedTinyInteger('tl_hours');
            $table->unsignedTinyInteger('ll_hours');
            $table->unsignedTinyInteger('sla_hours');

            // Credits & Duration
            $table->unsignedTinyInteger('credits');
            $table->unsignedTinyInteger('paper_duration');

            // Marks
            $table->unsignedTinyInteger('fa_th');
            $table->unsignedTinyInteger('sa_th');
            $table->unsignedTinyInteger('fa_pr');
            $table->unsignedTinyInteger('sa_pr');
            $table->unsignedTinyInteger('sla_marks');

            $table->unsignedSmallInteger('total_marks');

            $table->boolean('is_common')->default(false);

            $table->foreignId('owner_department_id')
                  ->constrained('departments')
                  ->cascadeOnDelete();

            $table->boolean('locked')->default(false);

            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Assigned later by CDC
            $table->string('course_code')->nullable();

            $table->timestamps();

            // Prevent duplicate courses
            $table->unique(['scheme_id', 'title', 'abbreviation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_masters');
    }
};

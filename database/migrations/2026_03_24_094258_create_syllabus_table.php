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
        Schema::create('syllabus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_master_id')
                ->constrained('course_masters')
                ->cascadeOnDelete();

            $table->text('rationale')->default(null);

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('status', [
                'draft',
                'submitted',
                'rejected',
                // 'moderator_approved',
                // 'moderator_rejected',
                'hod_approved',
            ])->default('draft');

            $table->decimal('question_multiplier',4,2)->nullable()->default(null);

            $table->timestamps();

            // One syllabus per course
            $table->unique('course_master_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabus');
    }
};

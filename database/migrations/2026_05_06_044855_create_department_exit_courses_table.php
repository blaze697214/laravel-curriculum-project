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
        Schema::create('department_exit_courses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('department_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('scheme_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            // Exit Course 1

            $table->unsignedTinyInteger('courses_offered')->nullable();

            $table->unsignedTinyInteger('courses_to_complete');

            $table->unsignedTinyInteger('th_hrs')->default(0);

            $table->unsignedTinyInteger('tu_hrs')->default(0);

            $table->unsignedTinyInteger('pr_hrs')->default(0);

            $table->unsignedTinyInteger('credits');

            $table->unsignedSmallInteger('marks');

            $table->unsignedTinyInteger('order_no');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_exit_courses');
    }
};

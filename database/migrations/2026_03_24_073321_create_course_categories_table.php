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
        Schema::create('course_categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('scheme_id')
                  ->constrained('schemes')
                  ->cascadeOnDelete();

            $table->string('name');
            // e.g. Basic Science, Core, Elective, IKS

            $table->string('abbreviation');

            $table->boolean('is_elective')->default(false);

            $table->unsignedTinyInteger('order_no');
            // 🔥 used in course code (4th digit)

            $table->timestamps();

            // Optional but recommended
            $table->unique(['scheme_id', 'order_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_categories');
    }
};

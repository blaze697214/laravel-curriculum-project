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
        Schema::create('elective_groups', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->foreignId('department_id')
                ->constrained('departments')
                ->cascadeOnDelete();

            $table->foreignId('scheme_id')
                ->constrained('schemes')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('semester_no'); // which semester this group belongs to

            $table->unsignedTinyInteger('min_select_count');
            $table->unsignedTinyInteger('max_select_count');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elective_groups');
    }
};

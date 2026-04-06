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
        Schema::create('schemes', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->year('year_start');
            $table->year('year_end');

            $table->unsignedSmallInteger('total_credits');
            $table->unsignedSmallInteger('total_marks');

            $table->boolean('is_active')->default(false);
            $table->boolean('is_locked')->default(false);

            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schemes');
    }
};

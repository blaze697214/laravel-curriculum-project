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
        Schema::create('section_templates', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            // e.g. Tutorial, Self Learning, Assignment

            $table->enum('section_type', [
                'tutorial',
                'self_learning',
                'text'
            ]);

            $table->text('intro_text')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_templates');
    }
};

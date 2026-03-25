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
        Schema::create('programme_outcomes', function (Blueprint $table) {
           $table->id();

            $table->foreignId('scheme_id')
                  ->constrained('schemes')
                  ->cascadeOnDelete();

            $table->foreignId('department_id')
                  ->constrained('departments')
                  ->cascadeOnDelete();

            $table->string('po_code'); // PO1, PO2, PSO1, etc.

            $table->text('description');

            $table->unsignedTinyInteger('order_no');

            $table->timestamps();

            // Prevent duplicate PO per dept + scheme
            $table->unique(['scheme_id', 'department_id', 'po_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programme_outcomes');
    }
};

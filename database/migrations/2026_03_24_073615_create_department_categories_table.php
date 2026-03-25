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
        Schema::create('department_categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_category_id')
                ->constrained('course_categories')
                ->cascadeOnDelete();

            $table->foreignId('department_id')
                ->constrained('departments')
                ->cascadeOnDelete();

            // Distribution details (flexible but structured)
            $table->unsignedTinyInteger('course_offered');
            $table->unsignedTinyInteger('compulsory_to_complete');
            $table->unsignedTinyInteger('elective_to_complete');

            $table->unsignedTinyInteger('th_hrs');
            $table->unsignedTinyInteger('tu_hrs');
            $table->unsignedTinyInteger('pr_hrs');

            $table->unsignedTinyInteger('credits');
            $table->unsignedSmallInteger('marks');

            $table->boolean('is_configured')->default(false);

            $table->timestamps();

            // Prevent duplicate category per department
            $table->unique(['course_category_id', 'department_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_categories');
    }
};

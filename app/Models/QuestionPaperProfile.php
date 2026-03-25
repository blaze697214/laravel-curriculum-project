<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionPaperProfile extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionPaperProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'unit_no',
        'course_outcome_id',
        'marks_per_unit',
        'adjusted_marks',
        'q1_marks',
        'q2_marks',
        'q3_marks',
        'q4_marks',
        'q5_marks',
        'q6_marks',
        'actual_distribution',
        'order_no'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class);
    }

    public function courseOutcome()
    {
        return $this->belongsTo(CourseOutcome::class);
    }
}

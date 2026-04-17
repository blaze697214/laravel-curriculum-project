<?php

namespace App\Models;

use Database\Factories\QuestionPaperProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionPaperProfile extends Model
{
    /** @use HasFactory<QuestionPaperProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'syllabus_unit_id',
        'course_outcome_id',
        'marks_per_unit',
        'adjusted_marks',
        'q1_marks',
        'q2_marks',
        'q3_marks',
        'q4_marks',
        'q5_marks',
        'q6_marks',
        'order_no',
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

    public function syllabusUnit()
    {
        return $this->belongsTo(SyllabusUnit::class);
    }
}

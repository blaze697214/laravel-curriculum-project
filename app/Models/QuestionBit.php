<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBit extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionBitFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'unit_no',
        'course_outcome_id',
        'question_no',
        'bit_label',
        'marks',
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

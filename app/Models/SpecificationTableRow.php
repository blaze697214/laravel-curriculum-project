<?php

namespace App\Models;

use Database\Factories\SpecificationTableRowFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificationTableRow extends Model
{
    /** @use HasFactory<SpecificationTableRowFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'syllabus_unit_id',
        // 'course_outcome_id',
        'remember_marks',
        'understand_marks',
        'apply_marks',
        'total_marks',
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

    public function unit()
    {
        return $this->belongsTo(SyllabusUnit::class, 'syllabus_unit_id');
    }

    public function courseOutcome()
    {
        return $this->belongsTo(CourseOutcome::class);
    }
}

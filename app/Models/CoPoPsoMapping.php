<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoPoPsoMapping extends Model
{
    /** @use HasFactory<\Database\Factories\CoPoPsoMappingFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'course_outcome_id',
        'programme_outcome_id',
        'level'
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

    public function programmeOutcome()
    {
        return $this->belongsTo(ProgrammeOutcome::class);
    }
}

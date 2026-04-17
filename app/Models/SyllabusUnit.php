<?php

namespace App\Models;

use Database\Factories\SyllabusUnitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusUnit extends Model
{
    /** @use HasFactory<SyllabusUnitFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'unit_no',
        'title',
        'hours',
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

    // Topics / Learning outcomes
    public function topics()
    {
        return $this->hasMany(UnitTopic::class);
    }

    // Specification table rows
    public function specificationRows()
    {
        return $this->hasMany(SpecificationTableRow::class);
    }

    public function questionProfiles()
{
    return $this->hasMany(QuestionPaperProfile::class, 'unit_id');
}

public function questionBits()
{
    return $this->hasMany(QuestionBit::class, 'unit_id');
}

    public function practicalTasks()
    {
        return $this->belongsToMany(
            PracticalTask::class,
            'practical_task_units',
            'unit_id',
            'practical_task_id'
        );
    }
}

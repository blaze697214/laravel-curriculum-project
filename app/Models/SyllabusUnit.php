<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusUnit extends Model
{
    /** @use HasFactory<\Database\Factories\SyllabusUnitFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'unit_no',
        'title',
        'hours',
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

    // Topics / Learning outcomes
    public function topics()
    {
        return $this->hasMany(UnitTopic::class);
    }

    // Practical tasks (if linked)
    public function practicalTasks()
    {
        return $this->hasMany(PracticalTask::class, 'unit_id');
    }

    // Specification table rows
    public function specificationRows()
    {
        return $this->hasMany(SpecificationTableRow::class);
    }
}

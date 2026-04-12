<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticalTask extends Model
{
    /** @use HasFactory<\Database\Factories\PracticalTaskFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'lab_learning_outcome',
        'exercise',
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

    public function units()
{
    return $this->belongsToMany(
        SyllabusUnit::class,
        'practical_task_units',
        'practical_task_id',
        'unit_id'
    );
}
}

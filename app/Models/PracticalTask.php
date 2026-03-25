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
        'unit_id',
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

    public function unit()
    {
        return $this->belongsTo(SyllabusUnit::class, 'unit_id');
    }
}

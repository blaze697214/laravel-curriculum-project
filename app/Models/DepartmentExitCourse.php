<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentExitCourse extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentExitCourseFactory> */
    use HasFactory;

    protected $fillable = [
        'department_id',
        'scheme_id',

        'title',

        'courses_offered',
        'courses_to_complete',

        'th_hrs',
        'tu_hrs',
        'pr_hrs',

        'total_hours',

        'credits',
        'marks',

        'order_no',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
}

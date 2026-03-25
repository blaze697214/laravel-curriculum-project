<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseOffering extends Model
{
    /** @use HasFactory<\Database\Factories\CourseOfferingFactory> */
    use HasFactory;

    protected $fillable = [
        'course_master_id',
        'department_id',
        'semester_no',
        'is_elective',
        'created_by'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function course()
    {
        return $this->belongsTo(CourseMaster::class, 'course_master_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

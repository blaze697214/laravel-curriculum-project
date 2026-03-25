<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAssignment extends Model
{
    /** @use HasFactory<\Database\Factories\CourseAssignmentFactory> */
    use HasFactory;

    protected $fillable = [
        'course_master_id',
        'department_id',
        'expert_id',
        'moderator_id',
        'assigned_by'
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

    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}

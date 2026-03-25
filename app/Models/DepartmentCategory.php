<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentCategory extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'course_category_id',
        'department_id',
        'course_offered',
        'compulsory_to_complete',
        'elective_to_complete',
        'th_hrs',
        'tu_hrs',
        'pr_hrs',
        'credits',
        'marks',
        'is_configured'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function courseCategory()
    {
        return $this->belongsTo(CourseCategory::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}

<?php

namespace App\Models;

use Database\Factories\CourseMasterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMaster extends Model
{
    /** @use HasFactory<CourseMasterFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'abbreviation',
        'scheme_id',
        'course_category_id',
        'iks_hours',
        'cl_hours',
        'tl_hours',
        'll_hours',
        'sla_hours',
        'credits',
        'paper_duration',
        'fa_th',
        'sa_th',
        'fa_pr',
        'sa_pr',
        'sla_marks',
        'total_marks',
        'is_common',
        'owner_department_id',
        'locked',
        'created_by',
        'course_code',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function ownerDepartment()
    {
        return $this->belongsTo(Department::class, 'owner_department_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Departments using this course
    public function usages()
    {
        return $this->hasMany(CourseDepartmentUsage::class);
    }

    // Department-wise offerings
    public function offerings()
    {
        return $this->hasMany(CourseOffering::class);
    }

    // Elective groups
    public function electiveGroups()
    {
        return $this->belongsToMany(
            ElectiveGroup::class,
            'elective_group_courses'
        );
    }

    // Class award compulsory mapping
    public function classAwardConfigurations()
    {
        return $this->belongsToMany(
            ClassAwardConfiguration::class,
            'class_award_compulsory_courses'
        );
    }

    // Syllabus
    public function syllabus()
    {
        return $this->hasOne(Syllabus::class);
    }

    // Assignments
    public function assignments()
    {
        return $this->hasMany(CourseAssignment::class);
    }
}

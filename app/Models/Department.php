<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory;


    protected $fillable = [
        'name',
        'order_no',
        'abbreviation',
        'type',
        'created_by'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */


    // Users in this department
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Courses owned by this department
    public function ownedCourses()
    {
        return $this->hasMany(CourseMaster::class, 'owner_department_id');
    }

    // Course usages
    public function courseUsages()
    {
        return $this->hasMany(CourseDepartmentUsage::class);
    }

    // Course offerings
    public function courseOfferings()
    {
        return $this->hasMany(CourseOffering::class);
    }

    // Elective groups
    public function electiveGroups()
    {
        return $this->hasMany(ElectiveGroup::class);
    }

    // Class awards
    public function classAwardConfigurations()
    {
        return $this->hasMany(ClassAwardConfiguration::class);
    }
}

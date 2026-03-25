<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassAwardConfiguration extends Model
{
    /** @use HasFactory<\Database\Factories\ClassAwardConfigurationFactory> */
    use HasFactory;

    protected $fillable = [
        'department_id',
        'scheme_id',
        'total_courses_required',
        'created_by'
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Compulsory courses
    public function compulsoryCourses()
    {
        return $this->belongsToMany(
            CourseMaster::class,
            'class_award_compulsory_courses'
        );
    }

    // Elective groups
    public function electiveGroups()
    {
        return $this->belongsToMany(
            ElectiveGroup::class,
            'class_award_elective_groups'
        );
    }
}

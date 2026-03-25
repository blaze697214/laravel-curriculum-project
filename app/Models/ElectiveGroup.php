<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectiveGroup extends Model
{
    /** @use HasFactory<\Database\Factories\ElectiveGroupFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'department_id',
        'scheme_id',
        'semester_no',
        'min_select_count',
        'max_select_count'
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

    // Courses in this group
    public function courses()
    {
        return $this->belongsToMany(
            CourseMaster::class,
            'elective_group_courses'
        );
    }

    // Class award mapping
    public function classAwardConfigurations()
    {
        return $this->belongsToMany(
            ClassAwardConfiguration::class,
            'class_award_elective_groups'
        );
    }
}

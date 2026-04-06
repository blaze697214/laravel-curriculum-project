<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    /** @use HasFactory<\Database\Factories\CourseCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'scheme_id',
        'name',
        'order_no',
        'abbreviation',
        'is_elective'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Belongs to scheme
    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    // Courses in this category
    public function courses()
    {
        return $this->hasMany(CourseMaster::class);
    }

    // Department category distribution
    public function departmentCategories()
    {
        return $this->hasMany(DepartmentCategory::class);
    }
}

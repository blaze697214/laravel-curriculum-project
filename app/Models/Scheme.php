<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    /** @use HasFactory<\Database\Factories\SchemeFactory> */
    use HasFactory;

    protected $fillable = [
        'year_start',
        'year_end',
        'total_credits',
        'total_marks',
        'is_active',
        'is_locked',
        'created_by'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Creator (CDC)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Course categories (types/verticals)
    public function courseCategories()
    {
        return $this->hasMany(CourseCategory::class);
    }

    // Courses under this scheme
    public function courses()
    {
        return $this->hasMany(CourseMaster::class);
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

    // Programme outcomes
    public function programmeOutcomes()
    {
        return $this->hasMany(ProgrammeOutcome::class);
    }
}

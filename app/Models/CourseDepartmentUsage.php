<?php

namespace App\Models;

use Database\Factories\CourseDepartmentUsageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDepartmentUsage extends Model
{
    /** @use HasFactory<CourseDepartmentUsageFactory> */
    use HasFactory;

    protected $fillable = [
        'course_master_id',
        'department_id',
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
}

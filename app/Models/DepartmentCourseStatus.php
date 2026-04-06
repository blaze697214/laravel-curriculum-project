<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentCourseStatus extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentCourseStatusFactory> */
    use HasFactory;

    protected $fillable = [
        'department_id',
        'scheme_id',
        'is_submitted_to_cdc'
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
}

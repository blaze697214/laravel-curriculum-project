<?php

namespace App\Models;

use Database\Factories\CourseOutcomeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseOutcome extends Model
{
    /** @use HasFactory<CourseOutcomeFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'co_code',
        'description',
        'order_no',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class);
    }

    // CO → PO/PSO mapping
    public function mappings()
    {
        return $this->hasMany(CoPoPsoMapping::class);
    }

    // Specification table
    public function specificationRows()
    {
        return $this->hasMany(SpecificationTableRow::class);
    }
}

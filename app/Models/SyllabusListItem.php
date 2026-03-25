<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusListItem extends Model
{
    /** @use HasFactory<\Database\Factories\SyllabusListItemFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'type',
        'content',
        'order_no'
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

    /*
    |--------------------------------------------------------------------------
    | Scopes (VERY USEFUL)
    |--------------------------------------------------------------------------
    */

    public function scopeObjectives($query)
    {
        return $query->where('type', 'objective');
    }

    public function scopeStudentActivities($query)
    {
        return $query->where('type', 'student_activity');
    }

    public function scopeInstructionalActivities($query)
    {
        return $query->where('type', 'instructional_activity');
    }
}

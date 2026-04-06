<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassAwardRule extends Model
{
    /** @use HasFactory<\Database\Factories\ClassAwardRuleFactory> */
    use HasFactory;

    protected $fillable = [
        'scheme_id',
        'total_subjects_required',
        'total_marks_required'
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
}

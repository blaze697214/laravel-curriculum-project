<?php

namespace App\Models;

use Database\Factories\SyllabusSectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusSection extends Model
{
    /** @use HasFactory<SyllabusSectionFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'section_template_id',
        'title',
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

    public function template()
    {
        return $this->belongsTo(SectionTemplate::class, 'section_template_id');
    }

    // Tutorial points
    public function tutorialPoints()
    {
        return $this->hasMany(Tutorial::class, 'section_id');
    }

    // Self learning content
    public function selfLearning()
    {
        return $this->hasOne(SelfLearning::class, 'section_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionTemplate extends Model
{
    /** @use HasFactory<\Database\Factories\SectionTemplateFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'section_type',
        'intro_text'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function syllabusSections()
    {
        return $this->hasMany(SyllabusSection::class);
    }
}

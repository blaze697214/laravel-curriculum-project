<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelfLearning extends Model
{
    /** @use HasFactory<\Database\Factories\SelfLearningFactory> */
    use HasFactory;

    protected $fillable = [
        'section_id',
        'point_text',
        'order_no'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function section()
    {
        return $this->belongsTo(SyllabusSection::class, 'section_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitTopic extends Model
{
    /** @use HasFactory<\Database\Factories\UnitTopicFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_unit_id',
        'type',
        'content',
        'order_no'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function unit()
    {
        return $this->belongsTo(SyllabusUnit::class, 'syllabus_unit_id');
    }

    // Subtopics (only for type = topic)
    public function subtopics()
    {
        return $this->hasMany(UnitSubtopic::class, 'unit_topic_id');
    }
}

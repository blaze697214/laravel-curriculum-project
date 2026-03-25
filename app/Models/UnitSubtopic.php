<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitSubtopic extends Model
{
    /** @use HasFactory<\Database\Factories\UnitSubtopicFactory> */
    use HasFactory;

     protected $fillable = [
        'unit_topic_id',
        'subtopic',
        'order_no'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function topic()
    {
        return $this->belongsTo(UnitTopic::class, 'unit_topic_id');
    }
}

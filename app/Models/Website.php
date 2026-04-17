<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    /** @use HasFactory<\Database\Factories\WebsiteFactory> */
    use HasFactory;

     protected $fillable = [
        'syllabus_id',
        'url',
        'order_no',
        'description'
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
}

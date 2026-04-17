<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusRemark extends Model
{
    /** @use HasFactory<\Database\Factories\SyllabusRemarkFactory> */
    use HasFactory;

    protected $fillable = [
        'syllabus_id',
        'remark',
        'given_by'
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

    public function givenBy()
    {
        return $this->belongsTo(User::class, 'given_by');
    }
}

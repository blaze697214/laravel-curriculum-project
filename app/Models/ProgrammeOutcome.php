<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeOutcome extends Model
{
    /** @use HasFactory<\Database\Factories\ProgrammeOutcomeFactory> */
    use HasFactory;

    protected $fillable = [
        'scheme_id',
        'department_id',
        'po_code',
        'description',
        'order_no'
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

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // CO → PO mappings
    public function mappings()
    {
        return $this->hasMany(CoPoPsoMapping::class);
    }
}

<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Syllabus extends Model
{

    /** @use HasFactory<\Database\Factories\SyllabusFactory> */
    use HasFactory;
    protected $fillable = [
        'course_master_id',
        'rationale',
        'created_by',
        'status',
        'question_multipli
        er'
    ];

    protected $table = 'syllabus';

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function course()
    {
        return $this->belongsTo(CourseMaster::class, 'course_master_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Units
    public function units()
    {
        return $this->hasMany(SyllabusUnit::class);
    }

    // Course outcomes
    public function courseOutcomes()
    {
        return $this->hasMany(CourseOutcome::class);
    }

    // Specification table
    public function specificationRows()
    {
        return $this->hasMany(SpecificationTableRow::class);
    }

    // Practical tasks
    public function practicalTasks()
    {
        return $this->hasMany(PracticalTask::class);
    }

    // Books
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    // Websites
    public function websites()
    {
        return $this->hasMany(Website::class);
    }

    // Equipments
    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }

    // Sections (dynamic)
    public function sections()
    {
        return $this->hasMany(SyllabusSection::class);
    }

    // Question paper
    public function questionProfiles()
    {
        return $this->hasMany(QuestionPaperProfile::class);
    }

    public function questionBits()
    {
        return $this->hasMany(QuestionBit::class);
    }

    // CO-PO mapping
    public function mappings()
    {
        return $this->hasMany(CoPoPsoMapping::class);
    }

    // List items (objectives, activities)
    public function listItems()
    {
        return $this->hasMany(SyllabusListItem::class);
    }

    public function remarks()
{
    return $this->hasMany(SyllabusRemark::class);
}
}

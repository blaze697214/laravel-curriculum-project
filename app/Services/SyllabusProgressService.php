<?php

namespace App\Services;

use App\Models\Book;
use App\Models\CoPoPsoMapping;
use App\Models\CourseOutcome;
use App\Models\Equipment;
use App\Models\PracticalTask;
use App\Models\QuestionBit;
use App\Models\QuestionPaperProfile;
use App\Models\SpecificationTableRow;
use App\Models\SyllabusListItem;
use App\Models\SyllabusUnit;
use App\Models\UnitTopic;
use App\Models\Website;

class SyllabusProgressService
{
    protected $syllabus;

    protected $course;

    public function __construct($syllabus, $course)
    {
        $this->syllabus = $syllabus;
        $this->course = $course;
    }

    // =============================
    // MAIN METHODS
    // =============================

    public function getProgress()
    {
        $checks = $this->getAllChecks();

        $filtered = collect($checks)->filter(fn ($v) => ! is_null($v));

        $total = $filtered->count();
        $completed = $filtered->filter()->count();

        return round(($completed / $total) * 100);
    }

    public function isComplete()
    {
return collect($this->getAllChecks())
    ->filter(fn($v) => !is_null($v))
    ->every(fn($v) => $v === true);    }

    public function getAllChecks()
    {
        return [

            'rationale' => $this->checkRationale(),
            'industrial_outcomes' => $this->checkIndustrialOutcomes(),
            'course_outcomes' => $this->checkCourseOutcomes(),
            'course_details' => $this->checkCourseDetails(),
            'specification_table' => $this->checkSpecification(),
            'practicals' => $this->checkPracticals(),
            'self_learning' => $this->course->sla_marks != 0
                ? $this->checkListType('self_learning')
                : null,
            'tutorial' => $this->checkListType('tutorial'),
            'instruction' => $this->checkListType('instructional_activity'),
            'books' => $this->checkBooks(),
            'websites' => $this->checkWebsites(),
            'equipments' => $this->checkEquipments(),
            'qpp' => $this->checkQPP(),
            'qb' => $this->checkQB(),
            'mapping' => $this->checkMapping(),
        ];
    }

    // =============================
    // SECTION CHECKS
    // =============================

    protected function checkRationale()
    {
        return ! empty(trim($this->syllabus->rationale ?? ''));
    }

    protected function checkIndustrialOutcomes()
    {
        return SyllabusListItem::where('syllabus_id', $this->syllabus->id ?? '')
            ->where('type', 'industrial_outcome')
            ->exists();
    }

    protected function checkCourseOutcomes()
    {
        return CourseOutcome::where('syllabus_id', $this->syllabus->id ?? '')->exists();
    }

    protected function checkCourseDetails()
    {
        $units = SyllabusUnit::where('syllabus_id', $this->syllabus->id ?? '')->get();

        if ($units->isEmpty()) {
            return false;
        }

        foreach ($units as $unit) {

            $topics = UnitTopic::where('syllabus_unit_id', $unit->id)
                ->where('type', 'topic')
                ->exists();

            if (! $topics) {
                return false;
            }
        }

        return true;
    }

    protected function checkSpecification()
    {
        return SpecificationTableRow::where('syllabus_id', $this->syllabus->id ?? '')->exists();
    }

    protected function checkPracticals()
    {
        return PracticalTask::where('syllabus_id', $this->syllabus->id ?? '')->exists();
    }

    protected function checkListType($type)
    {
        return SyllabusListItem::where('syllabus_id', $this->syllabus->id ?? '')
            ->where('type', $type)
            ->exists();
    }

    protected function checkBooks()
    {
        return Book::where('syllabus_id', $this->syllabus->id ?? '')->exists();
    }

    protected function checkWebsites()
    {
        return Website::where('syllabus_id', $this->syllabus->id ?? '')->exists();
    }

    protected function checkEquipments()
    {
        return Equipment::where('syllabus_id', $this->syllabus->id ?? '')->exists();
    }

    protected function checkQPP()
    {
        return QuestionPaperProfile::where('syllabus_id', $this->syllabus->id ?? '')->exists();
    }

    protected function checkQB()
    {
        return QuestionBit::where('syllabus_id', $this->syllabus->id ?? '')->exists();
    }

    protected function checkMapping()
    {
        return CoPoPsoMapping::where('syllabus_id', $this->syllabus->id ?? '')->exists();
    }

    // =============================
    // PREVIEW SECTIONS (DYNAMIC)
    // =============================

    public function getAvailableSections()
    {
        $checks = $this->getAllChecks();

        $sections = [];
        $index = 1;

        foreach ($checks as $key => $status) {

    if (is_null($status)) continue;

    if ($status) {
        $sections[$key] = $index++;
    }
}

        return $sections;
    }
}

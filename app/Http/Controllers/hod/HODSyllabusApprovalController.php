<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\CoPoPsoMapping;
use App\Models\CourseAssignment;
use App\Models\CourseMaster;
use App\Models\CourseOffering;
use App\Models\CourseOutcome;
use App\Models\Equipment;
use App\Models\PracticalTask;
use App\Models\ProgrammeOutcome;
use App\Models\QuestionBit;
use App\Models\QuestionPaperProfile;
use App\Models\Scheme;
use App\Models\SpecificationTableRow;
use App\Models\Syllabus;
use App\Models\SyllabusListItem;
use App\Models\SyllabusRemark;
use App\Models\SyllabusUnit;
use App\Models\Website;
use App\Services\SyllabusProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HODSyllabusApprovalController extends Controller
{

public function syllabusIndex()
{
    $department = Auth::user()->department;
    $scheme     = Scheme::where('is_active', true)->firstOrFail();

    // Get ALL owned courses (not just those with a syllabus)
    if ($department->type === 'service') {
        $courseMasterIds = CourseMaster::where('owner_department_id', $department->id)
            ->where('scheme_id', $scheme->id)
            ->pluck('id');
    } else {
        $courseMasterIds = CourseOffering::whereHas('courseMaster', function ($q) use ($department, $scheme) {
                $q->where('owner_department_id', $department->id)
                  ->where('scheme_id', $scheme->id);
            })
            ->where('department_id', $department->id)
            ->pluck('course_master_id');
    }

    $assignments = CourseAssignment::with(['courseMaster', 'expert'])
        ->whereIn('course_master_id', $courseMasterIds)
        ->get()
        ->keyBy('course_master_id');

    $grouped = [];

    foreach ($courseMasterIds as $cmId) {
        $course     = CourseMaster::find($cmId);
        $assignment = $assignments[$cmId] ?? null;
        $syllabus   = Syllabus::where('course_master_id', $cmId)->first();

        $service  = new SyllabusProgressService($syllabus, $course);
        $progress = $syllabus ? $service->getProgress() : 0;
        $status   = $syllabus ? $syllabus->status : 'not_started';

        if($status == 'not_started'){
            continue;
        }

        $item = [
            'course'    => $course,
            'expert'    => $assignment?->expert,
            'syllabus'  => $syllabus,
            'progress'  => $progress,
            'status'    => $status,
        ];

        if ($department->type === 'service') {
            $grouped['all'][] = $item;
        } else {
            $offering   = CourseOffering::where('course_master_id', $cmId)
                            ->where('department_id', $department->id)
                            ->first();
            $semesterNo = $offering?->semester_no ?? 0;
            $grouped[$semesterNo][] = $item;
        }
    }

    ksort($grouped);

    return view('hod.syllabus.index', compact('grouped', 'scheme'));
}

public function approve(Syllabus $syllabus)
{
    if ($syllabus->status !== 'moderator_approved') {
        return back()->with('error', 'Invalid action');
    }

    $syllabus->update([
        'status' => 'hod_approved'
    ]);

    return back()->with('success', 'Syllabus approved by HOD');
}

public function preview($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);

        $syllabus = Syllabus::firstOrCreate(
            ['course_master_id' => $courseId],
            ['rationale' => '', 'status' => 'draft', 'created_by' => Auth::id()]
        );

        // =========================
        // PROGRAMMES
        // =========================
        $offerings = CourseOffering::with('department')
            ->where('course_master_id', $course->id)
            ->get();

        $programmes = $offerings->pluck('department.abbreviation')
            ->unique()
            ->implode(' / ');

        // =========================
        // SERVICE (for dynamic sections)
        // =========================
        $service = new SyllabusProgressService($syllabus, $course);
        $sections = $service->getAvailableSections();

        // =========================
        // FETCH ALL DATA
        // =========================

        $rationale = $syllabus->rationale;

        $industrialOutcomes = SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'industrial_outcome')
            ->orderBy('order_no')
            ->get();

        $courseOutcomes = CourseOutcome::where('syllabus_id', $syllabus->id)
            ->orderBy('order_no')
            ->get();

        $units = SyllabusUnit::with(['topics.subtopics'])
            ->where('syllabus_id', $syllabus->id)
            ->orderBy('unit_no')
            ->get();

        $specRows = SpecificationTableRow::where('syllabus_id', $syllabus->id)
            ->orderBy('order_no')
            ->get();

        $practicals = PracticalTask::with('units')
            ->where('syllabus_id', $syllabus->id)
            ->orderBy('order_no')
            ->get();

        $selfLearning = SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'self_learning')
            ->get();

        $tutorial = SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'tutorial')
            ->get();

        $instruction = SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'instructional_activity')
            ->get();

        $books = Book::where('syllabus_id', $syllabus->id)->get();
        $websites = Website::where('syllabus_id', $syllabus->id)->get();
        $equipments = Equipment::where('syllabus_id', $syllabus->id)->get();

        $qpp = QuestionPaperProfile::where('syllabus_id', $syllabus->id)->get();
        $qb = QuestionBit::where('syllabus_id', $syllabus->id)->get();

        $cos = $courseOutcomes;

        $pos = ProgrammeOutcome::where('scheme_id', $course->scheme_id)
            ->whereNull('department_id')
            ->where('type', 'po')
            ->get();

        $psos = ProgrammeOutcome::where('scheme_id', $course->scheme_id)
            ->where('type', 'pso')
            ->get();

        $mapping = CoPoPsoMapping::where('syllabus_id', $syllabus->id)
            ->get()
            ->keyBy(fn ($m) => $m->course_outcome_id.'_'.$m->programme_outcome_id);

        return view('hod.syllabus.preview', compact(
            'course',
            'programmes',
            'sections',
            'rationale',
            'industrialOutcomes',
            'courseOutcomes',
            'units',
            'specRows',
            'practicals',
            'selfLearning',
            'tutorial',
            'instruction',
            'books',
            'websites',
            'equipments',
            'qpp',
            'qb',
            'cos',
            'pos',
            'psos',
            'mapping'
        ));
    }
}

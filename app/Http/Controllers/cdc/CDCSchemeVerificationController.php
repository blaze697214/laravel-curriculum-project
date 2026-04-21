<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Scheme;
use App\Models\CourseMaster;
use App\Models\Syllabus;
use App\Services\SchemeVerificationService;
use App\Models\Book;
use App\Models\CoPoPsoMapping;
use App\Models\CourseAssignment;
use App\Models\CourseOffering;
use App\Models\CourseOutcome;
use App\Models\Equipment;
use App\Models\PracticalTask;
use App\Models\ProgrammeOutcome;
use App\Models\QuestionBit;
use App\Models\QuestionPaperProfile;
use App\Models\SpecificationTableRow;
use App\Models\SyllabusListItem;
use App\Models\SyllabusRemark;
use App\Models\SyllabusUnit;
use App\Models\Website;
use App\Services\SyllabusProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CDCSchemeVerificationController extends Controller
{
    public function index()
    {
        $schemes = Scheme::latest()->get();

        return view('cdc.schemes.verify.index', compact('schemes'));
    }

    public function departments(Scheme $scheme, SchemeVerificationService $service)
    {
        $departments = Department::where('type', 'programme')
            ->orderBy('order_no')
            ->get();

        $statuses = [];

        foreach ($departments as $dept) {
            $result = $service->getDepartmentStatus($scheme->id, $dept->id);

            $statuses[$dept->id] = $result['is_complete'] ? 'Ready' : 'Incomplete';
        }

        return view('cdc.schemes.verify.departments', compact('scheme', 'departments', 'statuses'));
    }

    public function departmentDetail(Scheme $scheme, Department $department, SchemeVerificationService $service)
    {
        $status = $service->getDepartmentStatus($scheme->id, $department->id);

        return view('cdc.schemes.verify.detail', compact('scheme', 'department', 'status'));
    }

    public function semesterList(Scheme $scheme, Department $department, SchemeVerificationService $service)
    {
        $status = $service->getDepartmentStatus($scheme->id, $department->id);

        $semesters = [];

        for ($i = 1; $i <= 6; $i++) {
            $semesters[] = [
                'number' => $i,
                'configured' => $status['semesters'][$i] ?? false,
            ];
        }

        return view('cdc.schemes.verify.semesters.index', compact('scheme', 'department', 'semesters'));
    }

    public function semesterPreview($schemeId, $departmentId, $semesterNo)
{
    $scheme = Scheme::findOrFail($schemeId);
    $department = Department::findOrFail($departmentId);

    $courses = CourseOffering::with('courseMaster')
        ->where('department_id', $departmentId)
        ->where('semester_no', $semesterNo)
        ->get();

    // totals
    $totals = [
        'th_hours' => 0,
        'pr_hours' => 0,
        'credits' => 0,
        'marks' => 0
    ];

    foreach ($courses as $c) {

        $cm = $c->courseMaster;

        $totals['th_hours'] += $cm->th_hours ?? 0;
        $totals['pr_hours'] += $cm->pr_hours ?? 0;
        $totals['credits'] += $cm->credits ?? 0;
        $totals['marks'] += $cm->total_marks ?? 0;
    }

    return view('cdc.schemes.verify.semesters.preview', compact(
        'scheme',
        'department',
        'semesterNo',
        'courses',
        'totals'
    ));
}

public function classAwardPreview($schemeId, $departmentId)
{
    $scheme = Scheme::findOrFail($schemeId);
    $department = Department::findOrFail($departmentId);

    $courses = CourseOffering::with('courseMaster')
        ->where('department_id', $departmentId)
        ->orderBy('semester_no')
        ->get();

    // Separate elective groups
    $compulsory = $courses->where('is_elective', 0);
    $electives = $courses->where('is_elective', 1);

    return view('cdc.schemes.verify.class_award.preview', compact(
        'scheme',
        'department',
        'compulsory',
        'electives'
    ));
}


public function syllabus(Scheme $scheme, Department $department)
{
    // Only courses offered to this specific department
    $offeringsByDept = CourseOffering::with('courseMaster.ownerDepartment')
        ->where('department_id', $department->id)
        ->whereHas('courseMaster', fn($q) => $q->where('scheme_id', $scheme->id))
        ->get()
        ->groupBy('semester_no');

    $courseIds = $offeringsByDept->flatten()->pluck('course_master_id')->unique();

    $syllabi = Syllabus::whereIn('course_master_id', $courseIds)
        ->get()
        ->keyBy('course_master_id');

    $grouped = [];

    foreach ($offeringsByDept as $semesterNo => $offerings) {
        foreach ($offerings as $offering) {
            $course   = $offering->courseMaster;
            $syllabus = $syllabi[$course->id] ?? null;

            $grouped[$semesterNo][] = [
                'course'   => $course,
                'syllabus' => $syllabus,
                'status'   => $syllabus->status ?? 'not_created',
            ];
        }
    }

    ksort($grouped);

    return view('cdc.schemes.verify.syllabus.index', compact('scheme', 'department', 'grouped'));
}

public function preview($schemeId,$department,$courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
    $scheme = Scheme::findOrFail($schemeId);

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

        return view('cdc.schemes.verify.syllabus.preview', compact(
            'scheme',  
            'department',  
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

public function print($schemeId,$department,$courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
    $scheme = Scheme::findOrFail($schemeId);

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

        return view('cdc.schemes.verify.syllabus.print', compact(
            'scheme',  
            'department',  
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

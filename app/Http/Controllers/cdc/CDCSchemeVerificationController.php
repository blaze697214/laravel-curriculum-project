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

        return view('cdc.schemes.verify.semesters', compact('scheme', 'department', 'semesters'));
    }


public function syllabus($schemeId)
{
    $scheme = Scheme::findOrFail($schemeId);

    $courses = CourseMaster::with(['ownerDepartment'])
        ->where('scheme_id', $schemeId)
        ->get();

    $syllabi = Syllabus::whereIn('course_master_id', $courses->pluck('id'))
        ->get()
        ->keyBy('course_master_id');

    // Group by ALL offerings so service-owned common courses appear
    // in the correct semester tables
    $offerings = CourseOffering::whereIn('course_master_id', $courses->pluck('id'))
        ->get()
        ->groupBy('course_master_id');

    $grouped = [];

    foreach ($courses as $course) {

        $syllabus = $syllabi[$course->id] ?? null;

        $item = [
            'course'  => $course,
            'syllabus' => $syllabus,
            'status'  => $syllabus->status ?? 'not_created',
        ];

        $courseOfferings = $offerings[$course->id] ?? collect();

        if ($courseOfferings->isNotEmpty()) {
            foreach ($courseOfferings as $offering) {
                $grouped[$offering->semester_no][] = $item;
            }
        } else {
            $grouped['all'][] = $item;
        }
    }

    ksort($grouped);

    return view('cdc.schemes.verify.syllabus.index', compact('scheme', 'grouped'));
}

public function preview($schemeId,$courseId)
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

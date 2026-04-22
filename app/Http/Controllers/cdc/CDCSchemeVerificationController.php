<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ClassAwardConfiguration;
use App\Models\CoPoPsoMapping;
use App\Models\CourseMaster;
use App\Models\CourseOffering;
use App\Models\CourseOutcome;
use App\Models\Department;
use App\Models\ElectiveGroup;
use App\Models\Equipment;
use App\Models\PracticalTask;
use App\Models\ProgrammeOutcome;
use App\Models\QuestionBit;
use App\Models\QuestionPaperProfile;
use App\Models\Scheme;
use App\Models\SpecificationTableRow;
use App\Models\Syllabus;
use App\Models\SyllabusListItem;
use App\Models\SyllabusUnit;
use App\Models\Website;
use App\Services\SchemeVerificationService;
use App\Services\SyllabusProgressService;
use Illuminate\Support\Facades\Auth;

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

        // All offerings for this dept + semester
        $offerings = CourseOffering::with(['courseMaster.category', 'courseMaster.ownerDepartment'])
            ->where('department_id', $departmentId)
            ->where('semester_no', $semesterNo)
            ->get();

        // Pull elective groups that belong to this dept, scheme, semester
        $electiveGroups = ElectiveGroup::with('courses.category')
            ->where('department_id', $departmentId)
            ->where('scheme_id', $schemeId)
            ->where('semester_no', $semesterNo)
            ->get();

        // Build a set of course_master IDs that are inside an elective group
        $electiveCourseIds = $electiveGroups->flatMap(fn ($g) => $g->courses->pluck('id'))->unique()->toArray();

        // ── Group courses for display ──────────────────────────────────────────
        // Key = 'compulsory' or 'elective:<GroupName>'
        $grouped = collect();

        // Compulsory (non-elective) courses
        $compulsory = $offerings->filter(
            fn ($o) => ! in_array($o->course_master_id, $electiveCourseIds)
        );
        if ($compulsory->isNotEmpty()) {
            $grouped->put('compulsory', $compulsory);
        }

        // Elective groups
        foreach ($electiveGroups as $group) {
            $groupOfferings = $offerings->filter(
                fn ($o) => in_array($o->course_master_id, $group->courses->pluck('id')->toArray())
            );

            // If offerings found, show those; otherwise build fake wrappers from the group's courses
            if ($groupOfferings->isNotEmpty()) {
                $grouped->put('elective:'.$group->name, $groupOfferings);
            } else {
                // Build lightweight wrappers so the view has ->courseMaster
                $fakeOfferings = $group->courses->map(function ($cm) use ($departmentId, $semesterNo) {
                    $o = new CourseOffering;
                    $o->course_master_id = $cm->id;
                    $o->department_id = $departmentId;
                    $o->semester_no = $semesterNo;
                    $o->is_elective = true;
                    $o->setRelation('courseMaster', $cm);

                    return $o;
                });
                $grouped->put('elective:'.$group->name, $fakeOfferings);
            }
        }

        // ── Course category counts (for footer) ───────────────────────────────
        $categoryCounts = [];
        foreach ($offerings as $o) {
            $abbr = $o->courseMaster->category?->abbreviation ?? 'Unknown';
            $categoryCounts[$abbr] = ($categoryCounts[$abbr] ?? 0) + 1;
        }

        return view('cdc.schemes.verify.semesters.preview', compact(
            'scheme',
            'department',
            'semesterNo',
            'grouped',
            'categoryCounts'
        ));
    }
    public function semesterPrint($schemeId, $departmentId, $semesterNo)
    {
        $scheme = Scheme::findOrFail($schemeId);
        $department = Department::findOrFail($departmentId);

        // All offerings for this dept + semester
        $offerings = CourseOffering::with(['courseMaster.category', 'courseMaster.ownerDepartment'])
            ->where('department_id', $departmentId)
            ->where('semester_no', $semesterNo)
            ->get();

        // Pull elective groups that belong to this dept, scheme, semester
        $electiveGroups = ElectiveGroup::with('courses.category')
            ->where('department_id', $departmentId)
            ->where('scheme_id', $schemeId)
            ->where('semester_no', $semesterNo)
            ->get();

        // Build a set of course_master IDs that are inside an elective group
        $electiveCourseIds = $electiveGroups->flatMap(fn ($g) => $g->courses->pluck('id'))->unique()->toArray();

        // ── Group courses for display ──────────────────────────────────────────
        // Key = 'compulsory' or 'elective:<GroupName>'
        $grouped = collect();

        // Compulsory (non-elective) courses
        $compulsory = $offerings->filter(
            fn ($o) => ! in_array($o->course_master_id, $electiveCourseIds)
        );
        if ($compulsory->isNotEmpty()) {
            $grouped->put('compulsory', $compulsory);
        }

        // Elective groups
        foreach ($electiveGroups as $group) {
            $groupOfferings = $offerings->filter(
                fn ($o) => in_array($o->course_master_id, $group->courses->pluck('id')->toArray())
            );

            // If offerings found, show those; otherwise build fake wrappers from the group's courses
            if ($groupOfferings->isNotEmpty()) {
                $grouped->put('elective:'.$group->name, $groupOfferings);
            } else {
                // Build lightweight wrappers so the view has ->courseMaster
                $fakeOfferings = $group->courses->map(function ($cm) use ($departmentId, $semesterNo) {
                    $o = new CourseOffering;
                    $o->course_master_id = $cm->id;
                    $o->department_id = $departmentId;
                    $o->semester_no = $semesterNo;
                    $o->is_elective = true;
                    $o->setRelation('courseMaster', $cm);

                    return $o;
                });
                $grouped->put('elective:'.$group->name, $fakeOfferings);
            }
        }

        // ── Course category counts (for footer) ───────────────────────────────
        $categoryCounts = [];
        foreach ($offerings as $o) {
            $abbr = $o->courseMaster->category?->abbreviation ?? 'Unknown';
            $categoryCounts[$abbr] = ($categoryCounts[$abbr] ?? 0) + 1;
        }

        return view('cdc.schemes.verify.semesters.print', compact(
            'scheme',
            'department',
            'semesterNo',
            'grouped',
            'categoryCounts'
        ));
    }

    public function classAwardPreview($schemeId, $departmentId)
    {
        $scheme = Scheme::findOrFail($schemeId);
        $department = Department::findOrFail($departmentId);

        // Load the ClassAwardConfiguration for this dept+scheme
        $config = ClassAwardConfiguration::where('department_id', $departmentId)
            ->where('scheme_id', $schemeId)
            ->with([
                'compulsoryCourses.category',
                'electiveGroups.courses.category',
            ])
            ->first();

        if (! $config) {
            // Nothing configured yet — pass empty collections so the view still renders
            return view('cdc.schemes.verify.class_award.preview', [
                'scheme' => $scheme,
                'department' => $department,
                'compulsoryCourses' => collect(),
                'electiveGroups' => collect(),
            ]);
        }

        // Compulsory courses come directly from the pivot
        // We need CourseOffering wrappers so the blade can do $offering->courseMaster
        // But class award stores CourseMaster IDs directly, so we just use CourseMaster models.
        $compulsoryCourses = $config->compulsoryCourses->map(function ($cm) use ($departmentId) {
            // Wrap in a simple offering-like object so the blade ->courseMaster works
            $o = new CourseOffering;
            $o->course_master_id = $cm->id;
            $o->department_id = $departmentId;
            $o->setRelation('courseMaster', $cm);

            return $o;
        });

        // Elective groups — each group has ->courses (CourseMaster collection)
        $electiveGroups = $config->electiveGroups->map(fn ($g) => [
            'name' => $g->name,
            'courses' => $g->courses,         // Collection of CourseMaster models
        ]);

        return view('cdc.schemes.verify.class_award.preview', compact(
            'scheme',
            'department',
            'compulsoryCourses',
            'electiveGroups'
        ));
    }
    public function classAwardPrint($schemeId, $departmentId)
    {
        $scheme = Scheme::findOrFail($schemeId);
        $department = Department::findOrFail($departmentId);

        // Load the ClassAwardConfiguration for this dept+scheme
        $config = ClassAwardConfiguration::where('department_id', $departmentId)
            ->where('scheme_id', $schemeId)
            ->with([
                'compulsoryCourses.category',
                'electiveGroups.courses.category',
            ])
            ->first();

        if (! $config) {
            // Nothing configured yet — pass empty collections so the view still renders
            return view('cdc.schemes.verify.class_award.print', [
                'scheme' => $scheme,
                'department' => $department,
                'compulsoryCourses' => collect(),
                'electiveGroups' => collect(),
            ]);
        }

        // Compulsory courses come directly from the pivot
        // We need CourseOffering wrappers so the blade can do $offering->courseMaster
        // But class award stores CourseMaster IDs directly, so we just use CourseMaster models.
        $compulsoryCourses = $config->compulsoryCourses->map(function ($cm) use ($departmentId) {
            // Wrap in a simple offering-like object so the blade ->courseMaster works
            $o = new CourseOffering;
            $o->course_master_id = $cm->id;
            $o->department_id = $departmentId;
            $o->setRelation('courseMaster', $cm);

            return $o;
        });

        // Elective groups — each group has ->courses (CourseMaster collection)
        $electiveGroups = $config->electiveGroups->map(fn ($g) => [
            'name' => $g->name,
            'courses' => $g->courses,         // Collection of CourseMaster models
        ]);

        return view('cdc.schemes.verify.class_award.print', compact(
            'scheme',
            'department',
            'compulsoryCourses',
            'electiveGroups'
        ));
    }

    public function syllabus(Scheme $scheme, Department $department)
    {
        // Only courses offered to this specific department
        $offeringsByDept = CourseOffering::with('courseMaster.ownerDepartment')
            ->where('department_id', $department->id)
            ->whereHas('courseMaster', fn ($q) => $q->where('scheme_id', $scheme->id))
            ->get()
            ->groupBy('semester_no');

        $courseIds = $offeringsByDept->flatten()->pluck('course_master_id')->unique();

        $syllabi = Syllabus::whereIn('course_master_id', $courseIds)
            ->get()
            ->keyBy('course_master_id');

        $grouped = [];

        foreach ($offeringsByDept as $semesterNo => $offerings) {
            foreach ($offerings as $offering) {
                $course = $offering->courseMaster;
                $syllabus = $syllabi[$course->id] ?? null;

                $grouped[$semesterNo][] = [
                    'course' => $course,
                    'syllabus' => $syllabus,
                    'status' => $syllabus->status ?? 'not_created',
                ];
            }
        }

        ksort($grouped);

        return view('cdc.schemes.verify.syllabus.index', compact('scheme', 'department', 'grouped'));
    }

    public function preview($schemeId, $department, $courseId)
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

    public function print($schemeId, $department, $courseId)
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

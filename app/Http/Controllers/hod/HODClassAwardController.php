<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\ClassAwardConfiguration;
use App\Models\ClassAwardRule;
use App\Models\CourseMaster;
use App\Models\CourseOffering;
use App\Models\ElectiveGroup;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\SchemeVerificationService;


class HODClassAwardController extends Controller
{
    public function index(SchemeVerificationService $service)
    {
        $department = Auth::user()->department;

        $scheme = Scheme::where('is_active', true)->firstOrFail();
$status = $service->getDepartmentStatus($scheme->id, $department->id);
        // =========================
        // RULES (CDC defined)
        // =========================
        $rule = ClassAwardRule::where('scheme_id', $scheme->id)->first();

        // =========================
        // COURSES
        // =========================
        $courses = CourseOffering::with('courseMaster')
            ->where('department_id', $department->id)
            ->get();

        $compulsoryCourses = $courses->where('is_elective', false);

        // =========================
        // ELECTIVE GROUPS
        // =========================
        $groups = ElectiveGroup::with('courses')
            ->where('department_id', $department->id)
            ->where('scheme_id', $scheme->id)
            ->get();

        // =========================
        // EXISTING CONFIG (for old() + DB)
        // =========================
        $config = ClassAwardConfiguration::where('department_id', $department->id)
            ->where('scheme_id', $scheme->id)
            ->first();

        $selectedCourses = [];
        $selectedGroups = [];

        if ($config) {
            $selectedCourses = $config->compulsoryCourses->pluck('id')->toArray();
            $selectedGroups = $config->electiveGroups->pluck('id')->toArray();
        }

        return view('hod.class_award.index', compact(
            'scheme',
            'rule',
            'status',
            'compulsoryCourses',
            'groups',
            'selectedCourses',
            'selectedGroups'
        ));
    }

    public function store(Request $request)
    {
        $department = Auth::user()->department;
        $scheme = Scheme::where('is_active', true)->first();

        $rule = ClassAwardRule::where('scheme_id', $scheme->id)->first();

        $compulsory = $request->input('compulsory_courses', []);
        $groups = $request->input('elective_groups', []);

        // =========================
        // CALCULATE TOTALS
        // =========================
        $courseCount = count($compulsory);

        $groupModels = ElectiveGroup::whereIn('id', $groups)->get();

        $groupCount = $groupModels->sum('min_select_count');

        $totalSubjects = $courseCount + $groupCount;

        // =========================
        // CALCULATE MARKS
        // =========================
        $courseMarks = CourseMaster::whereIn('id', $compulsory)->sum('total_marks');

        $groupMarks = $groupModels->sum(function ($g) {
            return $g->courses->sum('total_marks') / max($g->courses->count(), 1) * $g->min_select_count;
        });

        $totalMarks = $courseMarks + $groupMarks;

        // =========================
        // VALIDATION
        // =========================
        if ($totalSubjects != $rule->total_subjects_required ||
            $totalMarks != $rule->total_marks_required) {

            return back()->withErrors([
                'validation' => 'Selection does not match required rules',
            ])->withInput();
        }

        DB::transaction(function () use ($department, $scheme, $compulsory, $groups) {

            $config = ClassAwardConfiguration::updateOrCreate(
                [
                    'department_id' => $department->id,
                    'scheme_id' => $scheme->id,
                ]
            );

            // sync compulsory
            $config->compulsoryCourses()->sync($compulsory);

            // sync groups
            $config->electiveGroups()->sync($groups);
        });

        return back()->with('success', 'Class award saved successfully');
    }

    public function preview()
    {
        $department = Auth::user()->department;
        $scheme = Scheme::where('is_active', true)->first();
        $departmentId = $department->id;

        // Load the ClassAwardConfiguration for this dept+scheme
        $config = ClassAwardConfiguration::where('department_id', $department->id)
            ->where('scheme_id',$scheme->id)
            ->with([
                'compulsoryCourses.category',
                'electiveGroups.courses.category',
            ])
            ->first();

        if (! $config) {
            // Nothing configured yet — pass empty collections so the view still renders
            return view('hod.class_award.preview', [
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

        return view('hod.class_award.preview', compact(
            'scheme',
            'department',
            'compulsoryCourses',
            'electiveGroups'
        ));
    }
    public function print()
    {
        $department = Auth::user()->department;
        $scheme = Scheme::where('is_active', true)->first();
        $departmentId = $department->id;

        // Load the ClassAwardConfiguration for this dept+scheme
        $config = ClassAwardConfiguration::where('department_id', $department->id)
            ->where('scheme_id',$scheme->id)
            ->with([
                'compulsoryCourses.category',
                'electiveGroups.courses.category',
            ])
            ->first();

        if (! $config) {
            // Nothing configured yet — pass empty collections so the view still renders
            return view('hod.class_award.preview', [
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

        return view('hod.class_award.print', compact(
            'scheme',
            'department',
            'compulsoryCourses',
            'electiveGroups'
        ));
    }
}

<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\CourseAssignment;
use App\Models\CourseMaster;
use App\Models\CourseOffering;
use App\Models\Role;
use App\Models\Scheme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HODAssignCourseController extends Controller
{
    public function index()
    {
        $department = Auth::user()->department;
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        // =========================
        // OWNED COURSES
        // =========================

        if ($department->type === 'service') {
            // service: get owned course_masters directly, wrap in collection to keep blade consistent
            $courses = CourseMaster::where('owner_department_id', $department->id)
                ->where('scheme_id', $scheme->id)
                ->get()
                ->map(function ($cm) {
                    // fake an offering-like object so blade works uniformly
                    $cm->semester_no = null;
                    $cm->courseMaster = $cm; // self-reference

                    return $cm;
                });
        } else {
            $courses = CourseOffering::with('courseMaster')
                ->whereHas('courseMaster', function ($q) use ($department) {
                    $q->where('owner_department_id', $department->id);
                })
                ->where('department_id', $department->id)
                ->orderBy('semester_no')
                ->get();
        }

        // =========================
        // EXPERT USERS
        // =========================
        $roles = Role::all();

        $expert = User::with(['roles', 'department'])
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['expert']);
            })
            ->where('department_id', $department->id)
            ->get();

        $moderator = User::with(['roles', 'department'])
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['moderator']);
            })
            ->where('department_id', $department->id)
            ->get();

        // =========================
        // EXISTING ASSIGNMENTS
        // =========================
        $assignments = CourseAssignment::where('department_id', $department->id)
            ->get()
            ->keyBy('course_master_id');

        // =========================
        // EXPERT-WISE GROUPING
        // =========================
        $expertAssignments = CourseAssignment::with('courseMaster', 'expert')
            ->where('department_id', $department->id)
            ->get()
            ->groupBy('expert_id');
        $moderatorAssignments = CourseAssignment::with('courseMaster', 'moderator')
            ->where('department_id', $department->id)
            ->get()
            ->groupBy('moderator_id');

        return view('hod.assign.index', compact(
            'courses',
            'scheme',
            'expert',
            'moderator',
            'assignments',
            'expertAssignments',
            'moderatorAssignments',
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_master_id' => 'required|exists:course_masters,id',
            'expert_id' => 'required|exists:users,id',
            'moderator_id' => 'required|exists:users,id',
        ]);

        $department = Auth::user()->department;

        // =========================
        // VALIDATION (IMPORTANT)
        // =========================

        // course must belong to department
        $course = CourseMaster::where('id', $request->course_master_id)
            ->where('owner_department_id', $department->id)
            ->firstOrFail();

        // expert must belong to department
        $expert = User::where('id', $request->expert_id)
            ->where('department_id', $department->id)
            ->firstOrFail();

        // =========================
        // ASSIGN / UPDATE
        // =========================
        CourseAssignment::updateOrCreate(
            [
                'course_master_id' => $course->id,
                'department_id' => $department->id,
            ],
            [
                'moderator_id' => $request->moderator_id,

                'expert_id' => $expert->id,
                'assigned_by' => Auth::id(),
            ]
        );

        return back()->with('success', 'Course assigned successfully');
    }
}

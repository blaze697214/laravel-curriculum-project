<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\ElectiveGroup;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HODElectiveGroupController extends Controller
{
    public function index()
    {
        $department = Auth::user()->department;
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $courses = CourseOffering::with('courseMaster')
            ->where('department_id', $department->id)
            ->get();

        $electiveCourses = $courses->where('is_elective', true);

        $groups = ElectiveGroup::with('courses')
            ->where('department_id', $department->id)
            ->where('scheme_id', $scheme->id)
            ->get();

        $alreadyGroupedIds = $groups->flatMap(fn ($g) => $g->courses->pluck('id'))->unique();

        // filter elective courses to only those not yet grouped
        $availableCourses = $electiveCourses->filter(
            fn ($offering) => ! $alreadyGroupedIds->contains($offering->course_master_id)
        );

        $totalElectives = $electiveCourses->count();

        $groupedElectives = $groups->sum(fn ($g) => $g->courses->count());

        $remainingElectives = $totalElectives - $groupedElectives;

        return view('hod.elective.index', compact(
            'groups',
            'availableCourses',
            'totalElectives',
            'groupedElectives',
            'remainingElectives',
            'scheme'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'semester_no' => 'required|integer|min:1|max:6',
            'min_select_count' => 'required|integer|min:1',
            'max_select_count' => 'required|integer|min:1',
            'courses' => 'required|array',
        ]);
        if ($request->max_select_count < $request->min_select_count) {
            return back()->withErrors(['max_select_count' => 'Max must be >= Min'])->withInput();
        }
        $department = Auth::user()->department;
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        // count available (ungrouped) elective courses for this dept in this semester
        $alreadyGroupedIds = ElectiveGroup::where('department_id', $department->id)
            ->where('scheme_id', $scheme->id)
            ->with('courses')
            ->get()
            ->flatMap(fn ($g) => $g->courses->pluck('id'))
            ->unique();

        $availableCount = CourseOffering::where('department_id', $department->id)
            ->where('is_elective', true)
            ->where('semester_no', $request->semester_no)
            ->whereNotIn('course_master_id', $alreadyGroupedIds)
            ->count();

        if ($request->max_select_count > $availableCount) {
            return back()->withErrors(['max_select_count' => "Max ($request->max_select_count) cannot exceed available courses ($availableCount)."])->withInput();
        }

        $selectedCourses = count($request->courses);

        if ($selectedCourses != $request->max_select_count) {

            return back()->withErrors([
                'courses' => 'Number of selected courses must be equal to maximum selection.',
            ])->withInput();

        }

        if ($request->max_select_count < $request->min_select_count) {
            return back()->withErrors('Max must be >= Min');
        }

        $department = Auth::user()->department;

        $scheme = Scheme::where('is_active', true)->first();

        // =========================
        // CREATE GROUP
        // =========================
        $group = ElectiveGroup::create([
            'name' => $request->name,
            'semester_no' => $request->semester_no,
            'min_select_count' => $request->min_select_count,
            'max_select_count' => $request->max_select_count,
            'department_id' => $department->id,
            'scheme_id' => $scheme->id,
        ]);

        // =========================
        // INSERT INTO PIVOT
        // =========================
        $group->courses()->sync($request->courses);

        return back()->with('success', 'Group created successfully');
    }

    public function destroy(ElectiveGroup $group)
    {
        $group->courses()->detach();

        $group->delete();

        return back()->with('success', 'Group deleted');
    }
}

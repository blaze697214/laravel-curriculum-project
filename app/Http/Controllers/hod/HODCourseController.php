<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use App\Models\CourseDepartmentUsage;
use App\Models\CourseMaster;
use App\Models\CourseOffering;
use App\Models\Department;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HODCourseController extends Controller
{
    public function create()
    {
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $categories = CourseCategory::where('scheme_id', $scheme->id)->get();

        $departments = Department::orderBy('name')->get();

        $electiveCategories = CourseCategory::where('scheme_id', $scheme->id)
            ->where('is_elective', true)  // assuming this column exists
            ->pluck('id');

        return view('hod.courses.create', compact('scheme', 'categories', 'departments', 'electiveCategories'));
    }

    public function searchCommon(Request $request)
    {
        if (! $request->title && ! $request->abbreviation) {
            return response()->json([]);
        }

        $scheme = Scheme::where('is_active', true)->first();

        $courses = CourseMaster::with('ownerDepartment')  // eager load
            ->where('scheme_id', $scheme->id)
            ->where('is_common', true)
            ->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->title.'%')
                    ->orWhere('abbreviation', 'like', '%'.$request->abbreviation.'%');
            })
            ->limit(10)
            ->get()
            ->map(fn ($c) => [           // shape the response
                'id' => $c->id,
                'title' => $c->title,
                'abbreviation' => $c->abbreviation,
                'owner_dept_name' => $c->ownerDepartment?->name ?? 'Unknown',
            ]);

        return response()->json($courses);
    }

    public function getCourseDetails($id)
    {
        $course = CourseMaster::findOrFail($id);

        return response()->json($course);
    }

    public function store(Request $request)
    {
        $department = Auth::user()->department;

        $validated = $request->validate([
            'title' => 'required|string',
            'abbreviation' => 'required|string',
            'category_id' => 'required',
            'semester_no' => 'required|integer|min:1|max:6',

            'credits' => 'required|integer',
            'total_marks' => 'required|integer',

            'owner_department_id' => 'required_if:is_common,1|exists:departments,id',
        ]);

        DB::transaction(function () use ($request, $department) {

            // =========================================
            // CASE 1: EXISTING COMMON COURSE SELECTED
            // =========================================
            if ($request->is_common && $request->existing_course_id) {
                $course = CourseMaster::findOrFail($request->existing_course_id);
            } else {

                // =========================================
                // CASE 2: NEW COURSE CREATION
                // =========================================
                $course = CourseMaster::create([

                    'title' => $request->title,
                    'abbreviation' => $request->abbreviation,
                    'scheme_id' => $request->scheme_id,
                    'course_category_id' => $request->category_id,

                    'iks_hours' => $request->iks_hours ?? 0,
                    'cl_hours' => $request->cl_hours ?? 0,
                    'tl_hours' => $request->tl_hours ?? 0,
                    'll_hours' => $request->ll_hours ?? 0,
                    'sla_hours' => $request->sla_hours ?? 0,

                    'credits' => $request->credits,
                    'paper_duration' => $request->paper_duration ?? 0,

                    'fa_th' => $request->fa_th ?? 0,
                    'sa_th' => $request->sa_th ?? 0,
                    'fa_pr' => $request->fa_pr ?? 0,
                    'sa_pr' => $request->sa_pr ?? 0,
                    'sla_marks' => $request->sla_marks ?? 0,
                    'total_marks' => $request->total_marks,

                    'is_common' => $request->is_common ? 1 : 0,

                    'owner_department_id' => $request->is_common
                        ? $request->owner_department_id
                        : $department->id,

                    'locked' => 0,
                    'created_by' => Auth::id(),
                ]);
            }

            // =========================================
            // PREVENT DUPLICATE OFFERING
            // =========================================
            $exists = CourseOffering::where('course_master_id', $course->id)
                ->where('department_id', $department->id)
                ->where('semester_no', $request->semester_no)
                ->exists();

            if (! $exists) {

                CourseOffering::create([
                    'course_master_id' => $course->id,
                    'department_id' => $department->id,
                    'semester_no' => $request->semester_no,
                    'is_elective' => $request->is_elective ? 1 : 0,
                    'created_by' => Auth::id(),
                ]);
            }

            // =========================================
            // USAGE ENTRY (FOR COMMON COURSES)
            // =========================================
            if ($course->is_common) {

                CourseDepartmentUsage::firstOrCreate([
                    'course_master_id' => $course->id,
                    'department_id' => $department->id,
                ]);
            }

        });

        return back()->with('success', 'Course saved successfully');
    }

    // public function view()
    // {
    //     $scheme = Scheme::where('is_active', true)->firstOrFail();
    //     $department = Auth::user()->department;

    //     $offerings = CourseOffering::with('courseMaster')
    //         ->where('department_id', $department->id)
    //         ->get()
    //         ->groupBy('semester_no');

    //     return view('hod.courses.view', compact('offerings', 'scheme'));
    // }

    public function view()
{
    $scheme = Scheme::where('is_active', true)->firstOrFail();
    $department = Auth::user()->department;

    if ($department->type === 'service') {

        $ownedCourses = CourseMaster::where('owner_department_id', $department->id)
            ->where('scheme_id', $scheme->id)
            ->get();

        return view('hod.courses.view', compact('ownedCourses', 'scheme'));

    } else {

        $offerings = CourseOffering::with('courseMaster')
            ->where('department_id', $department->id)
            ->get()
            ->groupBy('semester_no');

        return view('hod.courses.view', compact('offerings', 'scheme'));
    }
}

    public function destroy(CourseOffering $offering)
    {
        $department = Auth::user()->department;
        $course = $offering->courseMaster;

        $isOwner = $course->owner_department_id == $department->id;

        // =========================
        // OWNER DELETE
        // =========================
        if ($isOwner) {

            // delete all usages
            CourseDepartmentUsage::where('course_master_id', $course->id)->delete();

            // delete all offerings
            CourseOffering::where('course_master_id', $course->id)->delete();

            // delete master
            $course->delete();

        } else {

            // =========================
            // NON OWNER DELETE
            // =========================

            // delete only this offering
            $offering->delete();

            // remove usage
            CourseDepartmentUsage::where('course_master_id', $course->id)
                ->where('department_id', $department->id)
                ->delete();
        }

        return back()->with('success', 'Course deleted successfully');
    }

    public function edit(CourseOffering $offering)
    {
        $scheme = Scheme::where('is_active', true)->firstOrFail();
        $course = $offering->courseMaster;

        $department = Auth::user()->department;

        $isOwner = $course->owner_department_id == $department->id;

        $electiveCategories = CourseCategory::where('scheme_id', $scheme->id)
            ->where('is_elective', true)  // assuming this column exists
            ->pluck('id');

        $categories = CourseCategory::where('scheme_id', $course->scheme_id)->get();

        return view('hod.courses.edit', compact(
            'scheme',
            'offering',
            'course',
            'categories',
            'isOwner',
            'electiveCategories'
        ));
    }

    public function update(Request $request, CourseOffering $offering)
    {
        $course = $offering->courseMaster;
        $department = Auth::user()->department;

        $isOwner = $course->owner_department_id == $department->id;

        // =========================
        // NON OWNER → LIMITED UPDATE
        // =========================
        if (! $isOwner && $course->is_common) {

            $request->validate([
                'semester_no' => 'required|integer|min:1|max:6',
            ]);

            $offering->update([
                'semester_no' => $request->semester_no,
                'is_elective' => $request->is_elective ? 1 : 0,
            ]);

            return redirect()->route('hod.courses.view')
                ->with('success', 'Updated successfully');
        }

        // =========================
        // OWNER → FULL UPDATE
        // =========================
        $validated = $request->validate([
            'title' => 'required|string',
            'abbreviation' => 'required|string',
            'category_id' => 'required',

            'credits' => 'required|integer',
            'total_marks' => 'required|integer',
            'semester_no' => 'required|integer|min:1|max:6',
        ]);

        // update course master
        $course->update([
            'title' => $request->title,
            'abbreviation' => $request->abbreviation,
            'course_category_id' => $request->category_id,

            'iks_hours' => $request->iks_hours,
            'cl_hours' => $request->cl_hours,
            'tl_hours' => $request->tl_hours,
            'll_hours' => $request->ll_hours,
            'sla_hours' => $request->sla_hours,

            'credits' => $request->credits,
            'paper_duration' => $request->paper_duration,

            'fa_th' => $request->fa_th,
            'sa_th' => $request->sa_th,
            'fa_pr' => $request->fa_pr,
            'sa_pr' => $request->sa_pr,
            'sla_marks' => $request->sla_marks,
            'total_marks' => $request->total_marks,
        ]);

        // update offering
        $offering->update([
            'semester_no' => $request->semester_no,
            'is_elective' => $request->is_elective ? 1 : 0,
        ]);

        return redirect()->route('hod.courses.view')
            ->with('success', 'Course updated successfully');
    }

    public function commonEdit(CourseMaster $course)
{
    $scheme = Scheme::where('is_active', true)->firstOrFail();

    $categories = CourseCategory::where('scheme_id', $course->scheme_id)->get();

    $electiveCategories = CourseCategory::where('scheme_id', $scheme->id)
        ->where('is_elective', true)
        ->pluck('id');

    $isOwner = true;

    return view('hod.courses.edit', compact(
        'scheme', 'course', 'categories', 'electiveCategories','isOwner'
    ));
}

public function commonUpdate(Request $request, CourseMaster $course)
{
    $request->validate([
        'title'        => 'required|string',
        'abbreviation' => 'required|string',
        'category_id'  => 'required',
        'credits'      => 'required|integer',
        'total_marks'  => 'required|integer',
    ]);

    $course->update([
        'title'             => $request->title,
        'abbreviation'      => $request->abbreviation,
        'course_category_id'=> $request->category_id,
        'iks_hours'         => $request->iks_hours ?? 0,
        'cl_hours'          => $request->cl_hours ?? 0,
        'tl_hours'          => $request->tl_hours ?? 0,
        'll_hours'          => $request->ll_hours ?? 0,
        'sla_hours'         => $request->sla_hours ?? 0,
        'credits'           => $request->credits,
        'paper_duration'    => $request->paper_duration ?? 0,
        'fa_th'             => $request->fa_th ?? 0,
        'sa_th'             => $request->sa_th ?? 0,
        'fa_pr'             => $request->fa_pr ?? 0,
        'sa_pr'             => $request->sa_pr ?? 0,
        'sla_marks'         => $request->sla_marks ?? 0,
        'total_marks'       => $request->total_marks,
    ]);

    return redirect()->route('hod.courses.view')->with('success', 'Course updated successfully');
}

public function commonDestroy(CourseMaster $course)
{
    // delete all usages and offerings first, then master
    CourseDepartmentUsage::where('course_master_id', $course->id)->delete();
    CourseOffering::where('course_master_id', $course->id)->delete();
    $course->delete();

    return redirect()->route('hod.courses.view')->with('success', 'Course deleted successfully');
}
}

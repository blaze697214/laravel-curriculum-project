<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use App\Models\DepartmentCategory;
use App\Models\DepartmentExitCourse;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HODDepartmentCategoriesController extends Controller
{
    public function index()
{
    $department = Auth::user()->department;

    $scheme = Scheme::where('is_active', 1)->firstOrFail();

    $categories = DepartmentCategory::with('courseCategory')
    ->where('department_id', $department->id)
    ->whereHas('courseCategory', function ($q) use ($scheme) {

        $q->where('scheme_id', $scheme->id);

    })
    ->get();

    $exitCourses = DepartmentExitCourse::where([
        'scheme_id' => $scheme->id,
        'department_id' => $department->id
    ])->get();

    $configured = $categories->count() > 0;

    return view(
        'hod.department_categories.index',
        compact(
            'scheme',
            'department',
            'categories',
            'exitCourses',
            'configured'
        )
    );
}

public function edit()
{
    $department = Auth::user()->department;

    $scheme = Scheme::where('is_active', 1)->firstOrFail();

    $courseCategories = CourseCategory::where('scheme_id', $scheme->id)
        ->orderBy('order_no')
        ->get();

    $departmentCategories = DepartmentCategory::where('department_id', $department->id)
        ->whereHas('courseCategory', function ($q) use ($scheme) {
            $q->where('scheme_id', $scheme->id);
        })
        ->get()
        ->keyBy('course_category_id');

    $exitCourses = DepartmentExitCourse::where([
        'department_id' => $department->id,
        'scheme_id' => $scheme->id
    ])->get();

    return view('hod.department_categories.edit', compact(
        'scheme',
        'department',
        'courseCategories',
        'departmentCategories',
        'exitCourses'
    ));
}

    public function update(Request $request)
{
    $department = Auth::user()->department;

    $scheme = Scheme::where('is_active', 1)->firstOrFail();

    $courseCategories = CourseCategory::where('scheme_id', $scheme->id)->get();

    $request->validate([
        'categories' => 'required|array',
        'exit_courses' => 'nullable|array',
    ]);

    $totalCredits = 0;
    $totalMarks = 0;

    foreach ($request->categories as $courseCategoryId => $row) {

        $totalCredits += (int) ($row['total_credits'] ?? 0);
        $totalMarks += (int) ($row['total_marks'] ?? 0);
    }

    //  VALIDATION AGAINST SCHEME
    if ($totalCredits != $scheme->total_credits) {

        return back()
            ->withInput()
            ->withErrors([
                'credits' => 'Total credits must equal '.$scheme->total_credits
            ]);
    }

    if ($totalMarks != $scheme->total_marks) {

        return back()
            ->withInput()
            ->withErrors([
                'marks' => 'Total marks must equal '.$scheme->total_marks
            ]);
    }

    DB::transaction(function () use (
        $request,
        $department,
        $scheme,
        $courseCategories
    ) {

        foreach ($courseCategories as $category) {

            $row = $request->categories[$category->id] ?? [];

            DepartmentCategory::updateOrCreate(
                [
                    'department_id' => $department->id,
                    'course_category_id' => $category->id,
                ],
                [
                    'total_offered' => $row['total_offered'] ?? 0,
                    'to_be_completed' => $row['to_be_completed'] ?? 0,
                    'th' => $row['th'] ?? 0,
                    'tu' => $row['tu'] ?? 0,
                    'pr' => $row['pr'] ?? 0,
                    'total_hours' => $row['total_hours'] ?? 0,
                    'total_credits' => $row['total_credits'] ?? 0,
                    'total_marks' => $row['total_marks'] ?? 0,
                ]
            );
        }

        // EXIT COURSES
        DepartmentExitCourse::where([
            'department_id' => $department->id,
            'scheme_id' => $scheme->id
        ])->delete();

        if ($request->exit_courses) {

            foreach ($request->exit_courses as $row) {

                if (
                    empty($row['exit_name']) &&
                    empty($row['total_credits']) &&
                    empty($row['total_marks'])
                ) {
                    continue;
                }

                DepartmentExitCourse::create([
                    'department_id' => $department->id,
                    'scheme_id' => $scheme->id,
                    'exit_name' => $row['exit_name'],
                    'total_credits' => $row['total_credits'] ?? 0,
                    'total_marks' => $row['total_marks'] ?? 0,
                ]);
            }
        }
    });

    return redirect()
        ->route('hod.scheme.index')
        ->with('success', 'Scheme At Glance updated successfully');
}

    // public function store(Request $request, $schemeId, $departmentId)
    // {

    //     $scheme = CurriculumYears::findOrFail($schemeId);

    //     $totalCredits = 0;
    //     $totalMarks = 0;

    //     foreach ($request->input('levels') as $levelId) {

    //         $level = Levels::findOrFail($levelId);

    //         if ($level->is_audit) {

    //             $credits = 0;
    //             $marks = 0;

    //         } else {

    //             $credits = $request->credits[$levelId] ?? 0;
    //             $marks = $request->marks[$levelId] ?? 0;

    //         }

    //         if (! $level->is_audit) {

    //             $totalCredits += $credits;
    //             $totalMarks += $marks;

    //         }

    //         $offered = $request->courses_offered[$levelId] ?? 0;

    //         $compulsory = $request->compulsory[$levelId] ?? 0;
    //         $elective = $request->elective[$levelId] ?? 0;

    //         $toBeCompleted = $compulsory + $elective;

    //         if ($offered < $toBeCompleted) {

    //             return back()->withErrors([
    //                 'offered' => $level->name.
    //                 ': Course offered must be > Course to be completed(compulsory+elective)',
    //             ])->withInput();

    //         }

    //     }

    //     /* Validate grand totals */

    //     if ($totalCredits != $scheme->total_credits) {

    //         return back()->withErrors([
    //             'credits' => 'Total credits must equal '.$scheme->total_credits,
    //         ])->withInput();

    //     }

    //     if ($totalMarks != $scheme->total_marks) {

    //         return back()->withErrors([
    //             'marks' => 'Total marks must equal '.$scheme->total_marks,
    //         ])->withInput();

    //     }

    //     /* Save data */

    //     foreach ($request->levels as $levelId) {

    //         DepartmentLevelDetail::updateOrCreate(

    //             [
    //                 'department_id' => $departmentId,
    //                 'level_id' => $levelId,
    //             ],

    //             [
    //                 'courses_offered' => $request->courses_offered[$levelId],
    //                 'compulsory_to_complete' => $request->compulsory[$levelId],
    //                 'elective_to_complete' => $request->elective[$levelId] ?? 0,
    //                 'th_hrs' => $request->th[$levelId] ?? 0,
    //                 'tu_hrs' => $request->tu[$levelId] ?? 0,
    //                 'pr_hrs' => $request->pr[$levelId] ?? 0,
    //                 'credits' => $request->credits[$levelId] ?? 0,
    //                 'marks' => $request->marks[$levelId] ?? 0,
    //                 'is_configured' => 1,
    //             ]

    //         );

    //     }

    //     return redirect()->route(
    //         'cdc.schemes.departmentLevels.preview',
    //         [$schemeId, $departmentId]
    //     );

    // }
}

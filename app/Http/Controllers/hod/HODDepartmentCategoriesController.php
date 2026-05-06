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
            'department_id' => $department->id,
        ])->orderBy('order_no')->get();

        $configured = $categories->count() > 0;

        return view('hod.department_categories.index', compact(
            'scheme',
            'department',
            'categories',
            'exitCourses',
            'configured'
        ));
    }

    public function edit()
    {
        $department = Auth::user()->department;

        $scheme = Scheme::where('is_active', 1)->firstOrFail();

        $courseCategories = CourseCategory::where('scheme_id', $scheme->id)
            ->orderBy('order_no')
            ->get();

        // Keyed by course_category_id for easy lookup in blade
        $departmentCategories = DepartmentCategory::where('department_id', $department->id)
            ->whereHas('courseCategory', function ($q) use ($scheme) {
                $q->where('scheme_id', $scheme->id);
            })
            ->get()
            ->keyBy('course_category_id');

        $exitCourses = DepartmentExitCourse::where([
            'department_id' => $department->id,
            'scheme_id' => $scheme->id,
        ])->orderBy('order_no')->get();

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

        // Tally credits & marks to validate against scheme totals
        $totalCredits = 0;
        $totalMarks = 0;

        foreach ($request->categories as $row) {
            $totalCredits += (int) ($row['credits'] ?? 0);
            $totalMarks += (int) ($row['marks'] ?? 0);
        }

        if ($totalCredits != $scheme->total_credits) {
            return back()->withInput()->withErrors([
                'credits' => 'Total credits must equal '.$scheme->total_credits,
            ]);
        }

        if ($totalMarks != $scheme->total_marks) {
            return back()->withInput()->withErrors([
                'marks' => 'Total marks must equal '.$scheme->total_marks,
            ]);
        }

        DB::transaction(function () use ($request, $department, $scheme, $courseCategories) {

            foreach ($courseCategories as $category) {

                $row = $request->categories[$category->id] ?? [];

                // Column names match the migration exactly
                DepartmentCategory::updateOrCreate(
                    [
                        'department_id' => $department->id,
                        'course_category_id' => $category->id,
                    ],
                    [
                        'courses_offered' => $row['courses_offered'] ?? 0,
                        'courses_to_complete' => $row['courses_to_complete'] ?? 0,
                        'th_hrs' => $row['th_hrs'] ?? 0,
                        'tu_hrs' => $row['tu_hrs'] ?? 0,
                        'pr_hrs' => $row['pr_hrs'] ?? 0,
                        'credits' => $row['credits'] ?? 0,
                        'marks' => $row['marks'] ?? 0,
                        'is_configured' => true,
                    ]
                );
            }

            // ── EXIT COURSES ──────────────────────────────────────────────────
            DepartmentExitCourse::where([
                'department_id' => $department->id,
                'scheme_id' => $scheme->id,
            ])->delete();

            if ($request->exit_courses) {
                $order = 1;
                foreach ($request->exit_courses as $row) {
                    // Skip completely empty rows
                    if (empty(trim($row['title'] ?? ''))) {
                        continue;
                    }

                    DepartmentExitCourse::create([
                        'department_id' => $department->id,
                        'scheme_id' => $scheme->id,
                        'title' => $row['title'],
                        'courses_offered' => $row['courses_offered'] ?? 0,
                        'courses_to_complete' => $row['courses_to_complete'] ?? 0,
                        'th_hrs' => $row['th_hrs'] ?? 0,
                        'tu_hrs' => $row['tu_hrs'] ?? 0,
                        'pr_hrs' => $row['pr_hrs'] ?? 0,
                        'credits' => $row['credits'] ?? 0,
                        'marks' => $row['marks'] ?? 0,
                        'order_no' => $order++,
                    ]);
                }
            }
        });

        return redirect()->route('hod.scheme.index')
            ->with('success', 'Scheme At Glance updated successfully');
    }

    public function preview()
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
            'department_id' => $department->id,
            'scheme_id' => $scheme->id,
        ])
            ->orderBy('order_no')
            ->get();

        // =========================
        // TOTALS
        // =========================

        $totals = [
            'offered' => 0,
            'completed' => 0,
            'th' => 0,
            'tu' => 0,
            'pr' => 0,
            'hours' => 0,
            'credits' => 0,
            'marks' => 0,
        ];

        $compulsoryCompleted = 0;
        $electiveCompleted = 0;

        foreach ($categories as $row) {

            $totals['offered'] += $row->courses_offered;
            $totals['completed'] += $row->courses_to_complete;

            $totals['th'] += $row->th_hrs;
            $totals['tu'] += $row->tu_hrs;
            $totals['pr'] += $row->pr_hrs;

            $totals['hours'] += ($row->th_hrs + $row->tu_hrs + $row->pr_hrs);
            $totals['credits'] += $row->credits;
            $totals['marks'] += $row->marks;

            // ELECTIVE
            if ($row->courseCategory->is_elective) {

                $electiveCompleted += $row->courses_to_complete;

            } else {

                $compulsoryCompleted += $row->courses_to_complete;
            }
        }

        // =========================
        // GRAND TOTALS
        // =========================

        $grand = $totals;

        foreach ($exitCourses as $row) {

            $grand['offered'] += $row->courses_offered ?? 0;
            $grand['completed'] += $row->courses_to_complete ?? 0;

            $grand['th'] += $row->th_hrs ?? 0;
            $grand['tu'] += $row->tu_hrs ?? 0;
            $grand['pr'] += $row->pr_hrs ?? 0;

            $grand['hours'] += ($row->th_hrs + $row->tu_hrs + $row->pr_hrs);
            $grand['credits'] += $row->credits ?? 0;
            $grand['marks'] += $row->marks ?? 0;
        }

        return view(
            'hod.department_categories.preview',
            compact(
                'scheme',
                'department',
                'categories',
                'exitCourses',
                'totals',
                'grand',
                'compulsoryCompleted',
                'electiveCompleted'
            )
        );
    }

    public function print()
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
            'department_id' => $department->id,
            'scheme_id' => $scheme->id,
        ])
            ->orderBy('order_no')
            ->get();

        // =========================
        // TOTALS
        // =========================

        $totals = [
            'offered' => 0,
            'completed' => 0,
            'th' => 0,
            'tu' => 0,
            'pr' => 0,
            'hours' => 0,
            'credits' => 0,
            'marks' => 0,
        ];

        $compulsoryCompleted = 0;
        $electiveCompleted = 0;

        foreach ($categories as $row) {

            $totals['offered'] += $row->courses_offered;
            $totals['completed'] += $row->courses_to_complete;

            $totals['th'] += $row->th_hrs;
            $totals['tu'] += $row->tu_hrs;
            $totals['pr'] += $row->pr_hrs;

            $totals['hours'] += ($row->th_hrs + $row->tu_hrs + $row->pr_hrs);
            $totals['credits'] += $row->credits;
            $totals['marks'] += $row->marks;

            // ELECTIVE
            if ($row->courseCategory->is_elective) {

                $electiveCompleted += $row->courses_to_complete;

            } else {

                $compulsoryCompleted += $row->courses_to_complete;
            }
        }

        // =========================
        // GRAND TOTALS
        // =========================

        $grand = $totals;

        foreach ($exitCourses as $row) {

            $grand['offered'] += $row->courses_offered ?? 0;
            $grand['completed'] += $row->courses_to_complete ?? 0;

            $grand['th'] += $row->th_hrs ?? 0;
            $grand['tu'] += $row->tu_hrs ?? 0;
            $grand['pr'] += $row->pr_hrs ?? 0;

            $grand['hours'] += ($row->th_hrs + $row->tu_hrs + $row->pr_hrs);
            $grand['credits'] += $row->credits ?? 0;
            $grand['marks'] += $row->marks ?? 0;
        }

        return view(
            'hod.department_categories.print',
            compact(
                'scheme',
                'department',
                'categories',
                'exitCourses',
                'totals',
                'grand',
                'compulsoryCompleted',
                'electiveCompleted'
            )
        );
    }
}

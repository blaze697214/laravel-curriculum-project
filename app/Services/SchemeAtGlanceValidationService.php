<?php

namespace App\Services;

use App\Models\CourseOffering;
use App\Models\DepartmentCategory;

class SchemeAtGlanceValidationService
{
    public function validate($schemeId, $departmentId)
    {
        // =========================
        // TARGETS (from scheme-at-glance config)
        // =========================

        $categories = DepartmentCategory::with('courseCategory')
            ->where('department_id', $departmentId)
            ->whereHas('courseCategory', function ($q) use ($schemeId) {
                $q->where('scheme_id', $schemeId);
            })
            ->get();

        // =========================
        // ACTUAL COURSES (offered by this dept, filtered by scheme)
        // =========================

        $offerings = CourseOffering::with('courseMaster')
            ->where('department_id', $departmentId)
            ->whereHas('courseMaster', function ($q) use ($schemeId) {
                $q->where('scheme_id', $schemeId);
            })
            ->get();

        // Group offerings by their course category
        $groupedOfferings = $offerings->groupBy(function ($o) {
            return $o->courseMaster->course_category_id;
        });

        $categoryStats = [];

        // =========================
        // PER-CATEGORY VALIDATION
        // =========================

        foreach ($categories as $category) {

            $items = $groupedOfferings[$category->course_category_id] ?? collect();

            $actual = [
                // Number of courses actually added
                'courses' => $items->count(),

                // Sum of individual subject credits
                'credits' => $items->sum(fn ($o) => $o->courseMaster->credits ?? 0),

                // Sum of individual subject total marks
                'marks' => $items->sum(fn ($o) => $o->courseMaster->total_marks ?? 0),

                // Sum of individual subject contact hours (th+tu+pr → cl+tl+ll in course_master)
                'hours' => $items->sum(function ($o) {
                    $c = $o->courseMaster;
                    return ($c->cl_hours ?? 0) + ($c->tl_hours ?? 0) + ($c->ll_hours ?? 0);
                }),
            ];

            // Targets come from DepartmentCategory (scheme-at-glance table)
            $target = [
                'courses'  => $category->courses_offered,       // how many courses should be offered
                'credits'  => $category->credits,
                'marks'    => $category->marks,
                'hours'    => $category->th_hrs + $category->tu_hrs + $category->pr_hrs,
            ];

            $validations = [
                'courses' => $actual['courses'] == $target['courses'],
                'credits' => $actual['credits'] == $target['credits'],
                'marks'   => $actual['marks']   == $target['marks'],
                'hours'   => $actual['hours']   == $target['hours'],
            ];

            $categoryStats[] = [
                'category'    => $category,
                'actual'      => $actual,
                'target'      => $target,
                'validations' => $validations,
                'is_valid'    => collect($validations)->every(fn ($v) => $v),
            ];
        }

        // =========================
        // OVERALL TOTALS
        // =========================

        $overallActual = [
            'courses' => collect($categoryStats)->sum('actual.courses'),
            'credits' => collect($categoryStats)->sum('actual.credits'),
            'marks'   => collect($categoryStats)->sum('actual.marks'),
            'hours'   => collect($categoryStats)->sum('actual.hours'),
        ];

        $overallTarget = [
            'courses' => collect($categoryStats)->sum('target.courses'),
            'credits' => collect($categoryStats)->sum('target.credits'),
            'marks'   => collect($categoryStats)->sum('target.marks'),
            'hours'   => collect($categoryStats)->sum('target.hours'),
        ];

        $overallValidations = [
            'courses' => $overallActual['courses'] == $overallTarget['courses'],
            'credits' => $overallActual['credits'] == $overallTarget['credits'],
            'marks'   => $overallActual['marks']   == $overallTarget['marks'],
            'hours'   => $overallActual['hours']   == $overallTarget['hours'],
        ];

        return [
            'categories' => $categoryStats,
            'overall'    => [
                'actual'      => $overallActual,
                'target'      => $overallTarget,
                'validations' => $overallValidations,
                'is_valid'    => collect($overallValidations)->every(fn ($v) => $v),
            ],
        ];
    }
}

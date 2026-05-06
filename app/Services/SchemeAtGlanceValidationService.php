<?php

namespace App\Services;

use App\Models\CourseOffering;
use App\Models\DepartmentCategory;

class SchemeAtGlanceValidationService
{
    public function validate($schemeId, $departmentId)
    {
        // =========================
        // TARGETS
        // =========================

        $categories = DepartmentCategory::with('courseCategory')
            ->where('department_id', $departmentId)
            ->whereHas('courseCategory', function ($q) use ($schemeId) {

                $q->where('scheme_id', $schemeId);

            })
            ->get();

        // =========================
        // ACTUAL COURSES
        // =========================

        $offerings = CourseOffering::with('courseMaster')
            ->where('department_id', $departmentId)
            ->get();

        $groupedOfferings = $offerings->groupBy(function ($o) {

            return $o->courseMaster->course_category_id;

        });

        $categoryStats = [];

        // =========================
        // CATEGORY VALIDATION
        // =========================

        foreach ($categories as $category) {

            $items = $groupedOfferings[$category->course_category_id]
                ?? collect();

            $actual = [

                'courses' => $items->count(),

                'credits' => $items->sum(function ($o) {
                    return $o->courseMaster->credits;
                }),

                'marks' => $items->sum(function ($o) {
                    return $o->courseMaster->total_marks;
                }),

                'hours' => $items->sum(function ($o) {

                    $c = $o->courseMaster;

                    return
                        ($c->cl ?? 0) +
                        ($c->tl ?? 0) +
                        ($c->ll ?? 0);
                }),
            ];

            $target = [

                'courses' => $category->total_offered,
                'credits' => $category->total_credits,
                'marks' => $category->total_marks,
                'hours' => $category->total_hours,
            ];

            $validations = [

                'courses' =>
                    $actual['courses'] ==
                    $target['courses'],

                'credits' =>
                    $actual['credits'] ==
                    $target['credits'],

                'marks' =>
                    $actual['marks'] ==
                    $target['marks'],

                'hours' =>
                    $actual['hours'] ==
                    $target['hours'],
            ];

            $categoryStats[] = [

                'category' => $category,
                'actual' => $actual,
                'target' => $target,
                'validations' => $validations,

                'is_valid' =>
                    collect($validations)
                        ->every(fn ($v) => $v),
            ];
        }

        // =========================
        // OVERALL TOTALS
        // =========================

        $overallActual = [
            'courses' => collect($categoryStats)
                ->sum('actual.courses'),

            'credits' => collect($categoryStats)
                ->sum('actual.credits'),

            'marks' => collect($categoryStats)
                ->sum('actual.marks'),

            'hours' => collect($categoryStats)
                ->sum('actual.hours'),
        ];

        $overallTarget = [
            'courses' => collect($categoryStats)
                ->sum('target.courses'),

            'credits' => collect($categoryStats)
                ->sum('target.credits'),

            'marks' => collect($categoryStats)
                ->sum('target.marks'),

            'hours' => collect($categoryStats)
                ->sum('target.hours'),
        ];

        $overallValidations = [

            'courses' =>
                $overallActual['courses']
                == $overallTarget['courses'],

            'credits' =>
                $overallActual['credits']
                == $overallTarget['credits'],

            'marks' =>
                $overallActual['marks']
                == $overallTarget['marks'],

            'hours' =>
                $overallActual['hours']
                == $overallTarget['hours'],
        ];

        return [

            'categories' => $categoryStats,

            'overall' => [

                'actual' => $overallActual,
                'target' => $overallTarget,

                'validations' => $overallValidations,

                'is_valid' =>
                    collect($overallValidations)
                        ->every(fn ($v) => $v),
            ]
        ];
    }
}
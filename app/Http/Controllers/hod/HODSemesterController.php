<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use App\Models\CourseOffering;
use App\Models\ElectiveGroup;
use App\Services\SchemeVerificationService;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class HODSemesterController extends Controller
{
    public function index(SchemeVerificationService $service)
{
    $department = Auth::user()->department;
    $scheme = Scheme::where('is_active', true)->firstOrFail();

    // reuse service (good design)
    $status = $service->getDepartmentStatus($scheme->id, $department->id);

    $semesters = [];

    for ($i = 1; $i <= 6; $i++) {
        $semesters[] = [
            'number' => $i,
            'configured' => $status['semesters'][$i] ?? false,
        ];
    }

    return view('hod.semesters.index', compact(
        'scheme',
        'department',
        'semesters'
    ));
}

public function preview($semesterNo)
    {
        $department = Auth::user()->department;
        $scheme = Scheme::where('is_active', true)->firstOrFail();
        $departmentId = $department->id;
        $schemeId = $scheme->id;

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

        return view('hod.semesters.preview', compact(
            'scheme',
            'department',
            'semesterNo',
            'grouped',
            'categoryCounts'
        ));
    }
    public function print($semesterNo)
    {
        $department = Auth::user()->department;
    $scheme = Scheme::where('is_active', true)->firstOrFail();
    $departmentId = $department->id;
        $schemeId = $scheme->id;

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

        return view('hod.semesters.print', compact(
            'scheme',
            'department',
            'semesterNo',
            'grouped',
            'categoryCounts'
        ));
    }
}

<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\CourseAssignment;
use App\Models\CourseOffering;
use App\Models\CourseMaster;
use App\Models\ElectiveGroup;
use App\Models\ClassAwardConfiguration;
use App\Models\ProgrammeOutcome;
use App\Models\Scheme;
use App\Models\Syllabus;
use App\Models\User;
use App\Services\SyllabusProgressService;
use Illuminate\Support\Facades\Auth;

class HODDashBoardController extends Controller
{
    public function dashboard()
    {
        $department = Auth::user()->department;
        $scheme     = Scheme::where('is_active', true)->first();

        if (!$scheme) {
            return view('hod.dashboard', [
                'scheme'          => null,
                'noActiveScheme'  => true,
            ]);
        }

        $isProgramme = $department->type === 'programme';

        // ── COURSES ──────────────────────────────────────────────────────────
        if ($isProgramme) {
            $totalCourses = CourseOffering::where('department_id', $department->id)->count();

            $ownedCourseIds = CourseOffering::whereHas('courseMaster', function ($q) use ($department) {
                    $q->where('owner_department_id', $department->id);
                })
                ->where('department_id', $department->id)
                ->pluck('course_master_id');
        } else {
            $ownedCourseIds = CourseMaster::where('owner_department_id', $department->id)
                ->where('scheme_id', $scheme->id)
                ->pluck('id');
            $totalCourses = $ownedCourseIds->count();
        }

        // ── ASSIGNMENTS ───────────────────────────────────────────────────────
        $assignments = CourseAssignment::where('department_id', $department->id)->get();
        $assignedCount   = $assignments->count();
        $unassignedCount = $ownedCourseIds->count() - $assignedCount;
        $unassignedCount = max(0, $unassignedCount);

        // ── USERS ─────────────────────────────────────────────────────────────
        $expertCount    = User::where('department_id', $department->id)
            ->whereHas('roles', fn($q) => $q->where('name', 'expert'))
            ->count();
        $moderatorCount = User::where('department_id', $department->id)
            ->whereHas('roles', fn($q) => $q->where('name', 'moderator'))
            ->count();

        // ── SYLLABUS STATUS ───────────────────────────────────────────────────
        $syllabusStats = [
            'not_started'        => 0,
            'draft'              => 0,
            'submitted'          => 0,
            'moderator_rejected' => 0,
            'moderator_approved' => 0,
            'hod_approved'       => 0,
        ];

        $pendingApprovalItems = [];

        foreach ($ownedCourseIds as $cmId) {
            $course   = CourseMaster::find($cmId);
            $syllabus = Syllabus::where('course_master_id', $cmId)->first();

            if (!$syllabus) {
                $syllabusStats['not_started']++;
                continue;
            }

            $status = $syllabus->status ?? 'not_started';
            if (isset($syllabusStats[$status])) {
                $syllabusStats[$status]++;
            }

            if ($status === 'moderator_approved') {
                $assignment = CourseAssignment::where('course_master_id', $cmId)->first();
                $service    = new SyllabusProgressService($syllabus, $course);

                $pendingApprovalItems[] = [
                    'course'   => $course,
                    'syllabus' => $syllabus,
                    'expert'   => $assignment?->expert,
                    'progress' => $service->getProgress(),
                ];
            }
        }

        // ── PROGRAMME-SPECIFIC ────────────────────────────────────────────────
        $electiveGroupCount    = 0;
        $classAwardConfigured  = false;
        $psoCount              = 0;

        if ($isProgramme) {
            $electiveGroupCount   = ElectiveGroup::where('department_id', $department->id)
                ->where('scheme_id', $scheme->id)
                ->count();

            $classAwardConfigured = ClassAwardConfiguration::where('department_id', $department->id)
                ->where('scheme_id', $scheme->id)
                ->exists();

            $psoCount = ProgrammeOutcome::where('scheme_id', $scheme->id)
                ->where('department_id', $department->id)
                ->where('type', 'pso')
                ->count();
        }

        return view('hod.dashboard', compact(
            'scheme',
            'department',
            'isProgramme',
            'totalCourses',
            'assignedCount',
            'unassignedCount',
            'expertCount',
            'moderatorCount',
            'syllabusStats',
            'pendingApprovalItems',
            'electiveGroupCount',
            'classAwardConfigured',
            'psoCount'
        ));
    }
}
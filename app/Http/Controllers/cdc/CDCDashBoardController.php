<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\ClassAwardRule;
use App\Models\CourseAssignment;
use App\Models\CourseMaster;
use App\Models\Department;
use App\Models\ProgrammeOutcome;
use App\Models\Scheme;
use App\Models\Syllabus;
use App\Models\User;
use App\Services\SchemeVerificationService;
use Illuminate\Support\Facades\Auth;

class CDCDashBoardController extends Controller
{
    public function dashboard()
    {
        // ── SCHEME ────────────────────────────────────────────────────────────
        $activeScheme = Scheme::where('is_active', true)->first();
        $totalSchemes = Scheme::count();
        $lockedSchemes = Scheme::where('is_locked', true)->count();

        // ── DEPARTMENTS ───────────────────────────────────────────────────────
        $totalDepartments    = Department::count();
        $programmeDepts      = Department::where('type', 'programme')->count();
        $serviceDepts        = Department::where('type', 'service')->count();

        // ── USERS ─────────────────────────────────────────────────────────────
        $hodCount = User::whereHas('roles', fn($q) => $q->where('name', 'hod'))->count();

        // ── ACTIVE SCHEME STATS ───────────────────────────────────────────────
        $schemeStats = null;

        if ($activeScheme) {

            $programmeDeptModels = Department::where('type', 'programme')->get();
            $service = new SchemeVerificationService();

            $deptStatuses = $programmeDeptModels->map(function ($dept) use ($service, $activeScheme) {
                $result = $service->getDepartmentStatus($activeScheme->id, $dept->id);
                return [
                    'dept'        => $dept,
                    'is_complete' => $result['is_complete'],
                    'status'      => $result,
                ];
            });

            $readyCount    = $deptStatuses->where('is_complete', true)->count();
            $incompleteCount = $deptStatuses->where('is_complete', false)->count();

            // Syllabus overview across all courses in scheme
            $allSyllabuses = Syllabus::whereHas('course', fn($q) => $q->where('scheme_id', $activeScheme->id))->get();
            $syllabusStats = [
                'total'              => $allSyllabuses->count(),
                'draft'              => $allSyllabuses->where('status', 'draft')->count(),
                'submitted'          => $allSyllabuses->where('status', 'submitted')->count(),
                'moderator_approved' => $allSyllabuses->where('status', 'moderator_approved')->count(),
                'moderator_rejected' => $allSyllabuses->where('status', 'moderator_rejected')->count(),
                'hod_approved'       => $allSyllabuses->where('status', 'hod_approved')->count(),
            ];

            // Course count
            $totalCourses = CourseMaster::where('scheme_id', $activeScheme->id)->count();
            $assignedCourses = CourseAssignment::whereHas('courseMaster', fn($q) => $q->where('scheme_id', $activeScheme->id))->count();

            // Award rule configured?
            $awardRuleSet = ClassAwardRule::where('scheme_id', $activeScheme->id)->exists();

            // POs configured?
            $poCount = ProgrammeOutcome::where('scheme_id', $activeScheme->id)
                ->whereNull('department_id')
                ->where('type', 'po')
                ->count();

            $schemeStats = compact(
                'deptStatuses',
                'readyCount',
                'incompleteCount',
                'syllabusStats',
                'totalCourses',
                'assignedCourses',
                'awardRuleSet',
                'poCount'
            );
        }

        return view('cdc.dashboard', compact(
            'activeScheme',
            'totalSchemes',
            'lockedSchemes',
            'totalDepartments',
            'programmeDepts',
            'serviceDepts',
            'hodCount',
            'schemeStats'
        ));
    }
}
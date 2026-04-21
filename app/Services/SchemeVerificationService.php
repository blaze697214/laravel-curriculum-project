<?php

namespace App\Services;

use App\Models\ClassAwardConfiguration;
use App\Models\CourseCategory;
use App\Models\CourseMaster;
use App\Models\CourseOffering;
use App\Models\Syllabus;
use App\Models\DepartmentCategory;
use Illuminate\Support\Facades\DB;

class SchemeVerificationService
{
    // MAIN METHOD
    public function getDepartmentStatus($schemeId, $departmentId)
    {
        $schemeAtGlance = $this->checkSchemeAtGlance($schemeId, $departmentId);

        $semesters = $this->checkSemesters($schemeId, $departmentId);

        $classAward = $this->checkClassAward($schemeId, $departmentId);

        $syllabus = $this->checkSyllabus($schemeId);

        return [
            'scheme_at_glance' => $schemeAtGlance,

            'semesters' => $semesters['details'],

            'all_semesters_configured' => $semesters['complete'],

            'class_award' => $classAward,

            'is_complete' => $schemeAtGlance &&
                $semesters['complete'] &&
                $classAward,

            'syllabus' => $syllabus,
        ];
    }

    // =========================
    // 1. SCHEME AT GLANCE
    // =========================
    private function checkSchemeAtGlance($schemeId, $departmentId)
    {
        $categories = CourseCategory::where('scheme_id', $schemeId)->count();

        if ($categories == 0) {
            return false;
        }

        $configured = DepartmentCategory::where('department_id', $departmentId)
            ->where('is_configured', true)
            ->exists();

        return $configured;
    }

    // =========================
    // 2. SEMESTERS (1–6)
    // =========================
    private function checkSemesters($schemeId, $departmentId)
    {
        $semesters = [];

        $allComplete = true;

        for ($i = 1; $i <= 6; $i++) {

            $exists = CourseOffering::where('department_id', $departmentId)
                ->where('semester_no', $i)
                ->whereHas('courseMaster', function ($q) use ($schemeId) {
                    $q->where('scheme_id', $schemeId);
                })
                ->exists();

            $semesters[$i] = $exists;

            if (! $exists) {
                $allComplete = false;
            }
        }

        return [
            'details' => $semesters,
            'complete' => $allComplete,
        ];
    }

    // =========================
    // 3. CLASS AWARD
    // =========================
    private function checkClassAward($schemeId, $departmentId)
    {
        $config = ClassAwardConfiguration::where('scheme_id', $schemeId)
            ->where('department_id', $departmentId)
            ->first();

        if (! $config) {
            return false;
        }

        $hasCompulsory = DB::table('award_compulsory_courses')
            ->where('award_config_id', $config->id)
            ->exists();

        $hasElectives = DB::table('award_elective_groups')
            ->where('award_config_id', $config->id)
            ->exists();

        return $hasCompulsory || $hasElectives;
    }

    private function checkSyllabus($schemeId)
{
    $courses = CourseMaster::where('scheme_id', $schemeId)->get();

    if ($courses->isEmpty()) return false;

    foreach ($courses as $course) {

        $syllabus = Syllabus::where('course_master_id', $course->id)->first();

        if (!$syllabus || $syllabus->status !== 'hod_approved') {
            return false;
        }
    }

    return true;
}
}

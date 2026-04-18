<?php

namespace App\Http\Controllers\expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseAssignment;
use App\Models\CourseMaster;
use App\Models\CourseOffering;
use App\Models\Syllabus; // or whatever your syllabus table is
use App\Services\SyllabusProgressService;
use Illuminate\Support\Facades\Auth;

class EXPERTDashBoardController extends Controller
{


public function index()
{
    $user = Auth::user();

    // =========================
    // ASSIGNED COURSES
    // =========================
    $assignments = CourseAssignment::with('courseMaster')
        ->where('expert_id', $user->id) // you renamed to expert, but column still expert_id
        ->get();

    // =========================
    // SYLLABUS STATUS (ASSUMED TABLE)
    // =========================
    $courses = $assignments->map(function ($a) {

        $course = $a->courseMaster;

        // get syllabus record (adjust if different table)
        $syllabus = Syllabus::where('course_master_id', $course->id)->first();
        $service = new SyllabusProgressService($syllabus,$course);
        $status = 'Not Started';
        $progress = $service->getProgress();

        if ($syllabus) {

            if ($syllabus->is_submitted) {
                $status = 'Submitted';
            } elseif ($syllabus->is_completed) {
                $status = 'Completed';
            } else {
                $status = 'Draft';
            }
        }

        return [
            'course' => $course,
            'status' => $status,
            'progress' => $progress
        ];
    });

    // =========================
    // COUNTS
    // =========================
    $total = $courses->count();

    $completed = $courses->where('status', 'Completed')->count();
    $draft = $courses->where('status', 'Draft')->count();
    $pending = $courses->where('status', 'Not Started')->count();

    return view('expert.dashboard', compact(
        'courses',
        'total',
        'completed',
        'draft',
        'pending'
    ));
}
}

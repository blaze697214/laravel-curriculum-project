<?php

namespace App\Http\Controllers\expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseAssignment;
use App\Models\CourseMaster;
use App\Models\CourseOffering;
use App\Models\Syllabus; // or whatever your syllabus table is
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

        $status = 'Not Started';
        $progress = 0;

        if ($syllabus) {

            if ($syllabus->is_submitted) {
                $status = 'Submitted';
                $progress = 100;
            } elseif ($syllabus->is_completed) {
                $status = 'Completed';
                $progress = 100;
            } else {
                $status = 'Draft';
                $progress = $syllabus->progress ?? 30; // placeholder
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

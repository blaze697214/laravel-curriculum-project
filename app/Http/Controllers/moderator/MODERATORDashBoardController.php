<?php

namespace App\Http\Controllers\moderator;

use App\Http\Controllers\Controller;
use App\Models\CourseAssignment;
use App\Models\Syllabus;
use App\Services\SyllabusProgressService;
use Illuminate\Support\Facades\Auth;

class MODERATORDashBoardController extends Controller
{
    public function dashboard()
    {
        $moderatorId = Auth::id();

        $assignments = CourseAssignment::with(['courseMaster', 'expert'])
            ->where('moderator_id', $moderatorId)
            ->get();

        $courses = $assignments->map(function ($a) {

            $course   = $a->courseMaster;
            $syllabus = Syllabus::where('course_master_id', $course->id)->first();
            $service  = new SyllabusProgressService($syllabus, $course);
            $progress = $syllabus ? $service->getProgress() : 0;

            return [
                'course'     => $course,
                'expert'     => $a->expert,
                'syllabus'   => $syllabus,
                'raw_status' => $syllabus?->status ?? 'not_started',
                'progress'   => $progress,
            ];
        });

        // Counts
        $total      = $courses->count();
        $submitted  = $courses->where('raw_status', 'submitted')->count();
        $rejected   = $courses->where('raw_status', 'moderator_rejected')->count();
        $approved   = $courses->whereIn('raw_status', ['moderator_approved', 'hod_approved'])->count();
        $notStarted = $courses->whereIn('raw_status', ['draft', 'not_started'])->count();

        // Pending review is the priority list (submitted only)
        $pendingCourses = $courses->where('raw_status', 'submitted')->values();

        return view('moderator.dashboard', compact(
            'courses',
            'pendingCourses',
            'total',
            'submitted',
            'rejected',
            'approved',
            'notStarted'
        ));
    }
}
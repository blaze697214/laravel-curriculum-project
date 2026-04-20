<?php

namespace App\Http\Controllers\expert;

use App\Http\Controllers\Controller;
use App\Models\CourseAssignment;
use App\Models\Syllabus;
use App\Models\SyllabusRemark;
use App\Services\SyllabusProgressService;
use Illuminate\Support\Facades\Auth;

class EXPERTDashBoardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $assignments = CourseAssignment::with('courseMaster')
            ->where('expert_id', $user->id)
            ->get();

        $courses = $assignments->map(function ($a) {

            $course   = $a->courseMaster;
            $syllabus = Syllabus::where('course_master_id', $course->id)->first();
            $service  = new SyllabusProgressService($syllabus, $course);
            $progress = $syllabus ? $service->getProgress() : 0;

            // Map actual DB status enum to display status
            $status = match($syllabus?->status) {
                'draft'               => 'Draft',
                'submitted'           => 'Submitted',
                'moderator_approved'  => 'Moderator Approved',
                'moderator_rejected'  => 'Rejected',
                'hod_approved'        => 'Approved',
                default               => 'Not Started',
            };

            // Is the syllabus locked for editing?
            $isLocked = $syllabus && in_array($syllabus->status, [
                'submitted',
                'moderator_approved',
                'hod_approved',
            ]);

            // Latest remark if rejected
            $latestRemark = null;
            if ($syllabus?->status === 'moderator_rejected') {
                $latestRemark = SyllabusRemark::where('syllabus_id', $syllabus->id)
                    ->latest()
                    ->first();
            }

            return [
                'course'        => $course,
                'syllabus'      => $syllabus,
                'status'        => $status,
                'raw_status'    => $syllabus?->status ?? 'not_started',
                'progress'      => $progress,
                'is_locked'     => $isLocked,
                'latest_remark' => $latestRemark,
            ];
        });

        $total     = $courses->count();
        $approved  = $courses->whereIn('raw_status', ['hod_approved'])->count();
        $inReview  = $courses->whereIn('raw_status', ['submitted', 'moderator_approved'])->count();
        $draft     = $courses->whereIn('raw_status', ['draft', 'moderator_rejected'])->count();
        $pending   = $courses->where('raw_status', 'not_started')->count();

        return view('expert.dashboard', compact(
            'courses',
            'total',
            'approved',
            'inReview',
            'draft',
            'pending'
        ));
    }
}
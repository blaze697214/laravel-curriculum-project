<?php

namespace App\Http\Controllers\expert;

use App\Http\Controllers\Controller;
use App\Models\CourseAssignment;
use App\Models\CourseMaster;
use App\Models\Scheme;
use App\Models\Syllabus;
use App\Models\SyllabusRemark;
use App\Services\SyllabusProgressService;
use Illuminate\Support\Facades\Auth;

class EXPERTSyllabusSubmissionController extends Controller
{
    public function expertIndex()
    {
        $expertId = Auth::id();
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        // Courses assigned to this expert
        $courses = CourseAssignment::with('courseMaster')
            ->where('expert_id', $expertId)
            ->get()
            ->pluck('courseMaster');

        $data = [];

        foreach ($courses as $course) {

            $syllabus = Syllabus::firstOrCreate(
                ['course_master_id' => $course->id],
                ['created_by' => $expertId, 'status' => 'draft']
            );

            $service = new SyllabusProgressService($syllabus, $course);

            $progress = $service->getProgress();

            $remarks = SyllabusRemark::where('syllabus_id', $syllabus->id)
                ->with('givenBy')
                ->latest()
                ->get();

            $data[] = [
                'course' => $course,
                'syllabus' => $syllabus,
                'progress' => $progress,
                'remarks' => $remarks,
            ];
        }

        return view('expert.submission.index', compact('data', 'scheme'));
    }

    public function submit($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);

        $syllabus = Syllabus::where('course_master_id', $courseId)->firstOrFail();

        $service = new SyllabusProgressService($syllabus, $course);

        if ($service->getProgress() < 100) {
            return back()->withErrors('Complete all sections before submitting');
        }

        if (! in_array($syllabus->status, ['draft', 'rejected'])) {
            return back()->withErrors('Syllabus cannot be submitted');
        }

        $syllabus->update([
            'status' => 'submitted',
        ]);

        return back()->with('success', 'Syllabus submitted successfully');
    }
}

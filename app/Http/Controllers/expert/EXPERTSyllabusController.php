<?php

namespace App\Http\Controllers\expert;

use App\Http\Controllers\Controller;
use App\Models\CourseMaster;
use App\Models\CourseOffering;
use App\Models\CourseOutcome;
use App\Models\Scheme;
use App\Models\Syllabus;
use App\Models\SyllabusListItem;
use App\Models\SyllabusUnit;
use App\Models\UnitSubtopic;
use App\Models\UnitTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EXPERTSyllabusController extends Controller
{
    public function preview($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);

        // syllabus
        $syllabus = Syllabus::firstOrCreate(
            ['course_master_id' => $courseId],
            ['rationale' => '', 'status' => 'draft', 'created_by' => Auth::id()]
        );

        // programmes
        $offerings = CourseOffering::with('department')
            ->where('course_master_id', $course->id)
            ->get();

        $programmes = $offerings->pluck('department.abbreviation')->unique()->implode(' / ');

        // =========================
        // RATIONALE
        // =========================
        $rationale = $syllabus->rationale ?? '';

        // =========================
        // INDUSTRIAL OUTCOME
        // =========================
        $industrialOutcomes = SyllabusListItem::where('syllabus_id', $syllabus->id ?? 0)
            ->where('type', 'industrial_outcome')
            ->orderBy('order_no')
            ->get();

        // =========================
        // COURSE OUTCOMES
        // =========================
        $courseOutcomes = CourseOutcome::where('syllabus_id', $syllabus->id ?? 0)
            ->orderBy('order_no')
            ->get();

        return view('expert.syllabus.preview', compact(
            'course',
            'programmes',
            'rationale',
            'industrialOutcomes',
            'courseOutcomes'
        ));
    }

    public function rationale($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        // just fetch — don't create here anymore
        $syllabus = Syllabus::where('course_master_id', $courseId)->firstOrFail();

        return view('expert.syllabus.rationale', compact('course', 'syllabus', 'scheme'));
    }

    public function saveRationale(Request $request, $courseId)
    {
        $request->validate([
            'rationale' => 'nullable|string',
        ]);

        $syllabus = Syllabus::where('course_master_id', $courseId)->firstOrFail();

        $syllabus->update([
            'rationale' => $request->rationale,
        ]);

        return back()->with('success', 'Rationale saved successfully');
    }

    public function industrialOutcome($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        // get or create syllabus
        $syllabus = Syllabus::firstOrCreate([
            'course_master_id' => $courseId,
        ]);

        // fetch outcomes
        $outcomes = SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'industrial_outcome')
            ->orderBy('order_no')
            ->get();

        return view('expert.syllabus.industrial_outcome', compact(
            'course',
            'syllabus',
            'outcomes',
            'scheme'
        ));
    }

    public function saveIndustrialOutcome(Request $request, $courseId)
    {
        $syllabus = Syllabus::firstOrCreate([
            'course_master_id' => $courseId,
        ]);

        $items = $request->input('outcomes', []);

        // delete old entries
        SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'industrial_outcome')
            ->delete();

        // insert new
        foreach ($items as $index => $content) {

            if (trim($content)) {

                SyllabusListItem::create([
                    'syllabus_id' => $syllabus->id,
                    'type' => 'industrial_outcome',
                    'content' => $content,
                    'order_no' => $index + 1,
                ]);
            }
        }

        return back()->with('success', 'Industrial Outcomes saved successfully');
    }

    public function courseOutcome($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $syllabus = Syllabus::firstOrCreate([
            'course_master_id' => $courseId,
        ]);

        $outcomes = CourseOutcome::where('syllabus_id', $syllabus->id)
            ->orderBy('order_no')
            ->get();

        return view('expert.syllabus.course_outcome', compact(
            'course',
            'syllabus',
            'outcomes',
            'scheme'
        ));
    }

    public function saveCourseOutcome(Request $request, $courseId)
    {
        $syllabus = Syllabus::firstOrCreate([
            'course_master_id' => $courseId,
        ]);

        $items = $request->input('outcomes', []);

        // delete old
        CourseOutcome::where('syllabus_id', $syllabus->id)->delete();

        // insert new
        foreach ($items as $index => $content) {

            if (trim($content)) {

                CourseOutcome::create([
                    'syllabus_id' => $syllabus->id,
                    'co_code' => 'CO'.($index + 1),
                    'description' => $content,
                    'order_no' => $index + 1,
                ]);
            }
        }

        return back()->with('success', 'Course Outcomes saved successfully');
    }

    public function courseDetails($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $syllabus = Syllabus::firstOrCreate([
            'course_master_id' => $courseId,
        ]);

        
        $units = SyllabusUnit::where('syllabus_id', $syllabus->id)
    ->orderBy('order_no')
    ->with([
        'topics' => function($q) {
            $q->orderBy('order_no');
        },
        'topics.subtopics' => function($q) {
            $q->orderBy('order_no');
        }
    ])
    ->get();

// then separate outcomes and topics in blade directly
// $unit->topics->where('type', 'outcome')
// $unit->topics->where('type', 'topic')

        foreach ($units as $unit) {

            $unit->topics = UnitTopic::where('syllabus_unit_id', $unit->id)
                ->where('type', 'outcome')
                ->orderBy('order')
                ->get();

            $unit->topics = UnitTopic::where('syllabus_unit_id', $unit->id)
                ->where('type', 'topic')
                ->orderBy('order')
                ->get();

            foreach ($unit->topics as $topic) {
                $topic->subtopics = UnitSubtopic::where('unit_topic_id', $topic->id)
                    ->orderBy('order')
                    ->get();
            }
        }

        return view('expert.syllabus.course_details', compact('course', 'syllabus', 'units', 'scheme'));
    }

    public function saveCourseDetails(Request $request, $courseId)
    {
        $syllabus = Syllabus::firstOrCreate([
            'course_master_id' => $courseId,
        ]);

        $units = $request->units ?? [];

        // delete everything cleanly
        $unitIds = SyllabusUnit::where('syllabus_id', $syllabus->id)->pluck('id');

        $topicIds = UnitTopic::whereIn('syllabus_unit_id', $unitIds)->pluck('id');

        UnitSubtopic::whereIn('unit_topic_id', $topicIds)->delete();
        UnitTopic::whereIn('syllabus_unit_id', $unitIds)->delete();
        SyllabusUnit::where('syllabus_id', $syllabus->id)->delete();

        foreach ($units as $uIndex => $unit) {

            if (! trim($unit['title'] ?? '')) {
                continue;
            }

            $unitModel = SyllabusUnit::create([
                'syllabus_id' => $syllabus->id,
                'unit_no' => $uIndex + 1,
                'title' => $unit['title'],
                'hours' => $unit['hours'] ?? 0,
                'order' => $uIndex + 1,
            ]);

            // outcomes
            foreach ($unit['outcomes'] ?? [] as $oIndex => $outcome) {

                if (! trim($outcome)) {
                    continue;
                }

                UnitTopic::create([
                    'syllabus_unit_id' => $unitModel->id,
                    'type' => 'outcome',
                    'content' => $outcome,
                    'order' => $oIndex + 1,
                ]);
            }

            // topics
            foreach ($unit['topics'] ?? [] as $tIndex => $topic) {

                if (! trim($topic['title'] ?? '')) {
                    continue;
                }

                $topicModel = UnitTopic::create([
                    'syllabus_unit_id' => $unitModel->id,
                    'type' => 'topic',
                    'content' => $topic['title'],
                    'order' => $tIndex + 1,
                ]);

                // subtopics
                foreach ($topic['subtopics'] ?? [] as $sIndex => $sub) {

                    if (! trim($sub)) {
                        continue;
                    }

                    UnitSubtopic::create([
                        'unit_topic_id' => $topicModel->id,
                        'subtopic' => $sub,
                        'order' => $sIndex + 1,
                    ]);
                }
            }
        }

        return back()->with('success', 'Course Details saved successfully');
    }
}

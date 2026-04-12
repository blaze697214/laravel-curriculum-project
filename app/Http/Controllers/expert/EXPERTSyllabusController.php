<?php

namespace App\Http\Controllers\expert;

use App\Http\Controllers\Controller;
use App\Models\CourseMaster;
use App\Models\CourseOffering;
use App\Models\CourseOutcome;
use App\Models\Scheme;
use App\Models\SpecificationTableRow;
use App\Models\Syllabus;
use App\Models\SyllabusListItem;
use App\Models\SyllabusUnit;
use App\Models\UnitSubtopic;
use App\Models\UnitTopic;
use App\Models\PracticalTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // ── Eager load topics + subtopics in one query ──────────────────────
        $units = SyllabusUnit::where('syllabus_id', $syllabus->id)
            ->orderBy('order_no')
            ->with([
                'topics' => fn ($q) => $q->orderBy('order_no'),
                'topics.subtopics' => fn ($q) => $q->orderBy('order_no'),
            ])
            ->get();
        // In blade, filter with:
        //   $unit->topics->where('type', 'outcome')
        //   $unit->topics->where('type', 'topic')
        // ────────────────────────────────────────────────────────────────────

        return view('expert.syllabus.course_details', compact('course', 'syllabus', 'units', 'scheme'));
    }

    public function saveCourseDetails(Request $request, $courseId)
    {
        $syllabus = Syllabus::firstOrCreate([
            'course_master_id' => $courseId,
        ]);

        $units = $request->input('units', []);

        // ── Delete old data cleanly ──────────────────────────────────────────
        $unitIds = SyllabusUnit::where('syllabus_id', $syllabus->id)->pluck('id');
        $topicIds = UnitTopic::whereIn('syllabus_unit_id', $unitIds)->pluck('id');

        UnitSubtopic::whereIn('unit_topic_id', $topicIds)->delete();
        UnitTopic::whereIn('syllabus_unit_id', $unitIds)->delete();
        SyllabusUnit::where('syllabus_id', $syllabus->id)->delete();
        // ─────────────────────────────────────────────────────────────────────

        foreach ($units as $uIndex => $unit) {

            if (! trim($unit['title'] ?? '')) {
                continue;
            }

            $unitModel = SyllabusUnit::create([
                'syllabus_id' => $syllabus->id,
                'unit_no' => $uIndex + 1,
                'title' => $unit['title'],
                'hours' => $unit['hours'] ?? 0,
                'order_no' => $uIndex + 1,
            ]);

            // ── Save outcomes ────────────────────────────────────────────────
            foreach ($unit['outcomes'] ?? [] as $oIndex => $outcome) {
                if (! trim($outcome)) {
                    continue;
                }

                UnitTopic::create([
                    'syllabus_unit_id' => $unitModel->id,
                    'type' => 'learning_outcome',
                    'content' => $outcome,
                    'order_no' => $oIndex + 1,
                ]);
            }

            // ── Save topics + subtopics ──────────────────────────────────────
            foreach ($unit['topics'] ?? [] as $tIndex => $topic) {
                if (! trim($topic['title'] ?? '')) {
                    continue;
                }

                $topicModel = UnitTopic::create([
                    'syllabus_unit_id' => $unitModel->id,
                    'type' => 'topic',
                    'content' => $topic['title'],
                    'order_no' => $tIndex + 1,
                ]);

                foreach ($topic['subtopics'] ?? [] as $sIndex => $sub) {
                    if (! trim($sub)) {
                        continue;
                    }

                    UnitSubtopic::create([
                        'unit_topic_id' => $topicModel->id,
                        'subtopic' => $sub,
                        'order_no' => $sIndex + 1,
                    ]);
                }
            }
        }

        return back()->with('success', 'Course Details saved successfully');
    }

    public function specification($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
                $scheme = Scheme::where('is_active', true)->firstOrFail();


        $syllabus = Syllabus::firstOrCreate([
            'course_master_id' => $courseId,
        ]);

        // all units
        $units = SyllabusUnit::where('syllabus_id', $syllabus->id)
            ->orderBy('order_no')
            ->get();

        // existing rows
        $rows = SpecificationTableRow::where('syllabus_id', $syllabus->id)
            ->get()
            ->keyBy('syllabus_unit_id');

        return view('expert.syllabus.specification', compact(
            'course',
            'syllabus',
            'units',
            'rows',
            'scheme'

        ));
    }

    public function saveSpecification(Request $request, $courseId)
    {
        $syllabus = Syllabus::firstOrCreate([
            'course_master_id' => $courseId,
        ]);

        $data = $request->rows ?? [];

        foreach ($data as $unitId => $row) {

            $r = (int) ($row['r'] ?? 0);
            $u = (int) ($row['u'] ?? 0);
            $a = (int) ($row['a'] ?? 0);

            SpecificationTableRow::updateOrCreate(
                [
                    'syllabus_id' => $syllabus->id,
                    'syllabus_unit_id' => $unitId,
                ],
                [
                    'course_outcome_id' => null, // ignored for now
                    'remember_marks' => $r,
                    'understand_marks' => $u,
                    'apply_marks' => $a,
                    'total_marks' => $r + $u + $a,
                    'order_no' => 1,
                ]
            );
        }

        return back()->with('success', 'Specification Table saved');
    }




public function practicals($courseId)
{
    $course   = CourseMaster::findOrFail($courseId);
    $scheme   = Scheme::where('is_active', true)->firstOrFail();

    $syllabus = Syllabus::firstOrCreate([
        'course_master_id' => $courseId,
    ]);

    $units = SyllabusUnit::where('syllabus_id', $syllabus->id)
        ->orderBy('order_no')
        ->get();

    $tasks = PracticalTask::with('units')
        ->where('syllabus_id', $syllabus->id)
        ->orderBy('order_no')
        ->get();

    return view('expert.syllabus.practicals', compact(
        'course',
        'syllabus',
        'units',
        'tasks',
        'scheme'
    ));
}

public function savePracticals(Request $request, $courseId)
{
    $syllabus = Syllabus::firstOrCreate([
        'course_master_id' => $courseId,
    ]);

    DB::transaction(function () use ($request, $syllabus) {

        // Detach pivot rows first, then delete tasks
        // (avoids orphaned pivot rows if DB cascade is not set)
        $oldTasks = PracticalTask::where('syllabus_id', $syllabus->id)->get();
        foreach ($oldTasks as $old) {
            $old->units()->detach();
        }
        PracticalTask::where('syllabus_id', $syllabus->id)->delete();

        foreach ($request->input('tasks', []) as $index => $taskData) {

            // Skip completely empty rows
            if (empty(trim($taskData['exercise'] ?? '')) && empty(trim($taskData['outcome'] ?? ''))) {
                continue;
            }

            $task = PracticalTask::create([
                'syllabus_id'          => $syllabus->id,
                'lab_learning_outcome' => $taskData['outcome']   ?? null,
                'exercise'             => $taskData['exercise']  ?? '',
                'hours'                => $taskData['hours']     ?? 0,
                'order_no'             => $index + 1,
            ]);

            // Sync units — filter to integers to prevent 'on' slipping through
            $unitIds = array_filter(
                array_map('intval', $taskData['units'] ?? []),
                fn($id) => $id > 0
            );

            if (!empty($unitIds)) {
                $task->units()->sync($unitIds);
            }
        }
    });

    return back()->with('success', 'Practical tasks saved');
}
}

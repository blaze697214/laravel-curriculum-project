<?php

namespace App\Http\Controllers\expert;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\CoPoPsoMapping;
use App\Models\CourseMaster;
use App\Models\CourseOffering;
use App\Models\CourseOutcome;
use App\Models\Equipment;
use App\Models\PracticalTask;
use App\Models\ProgrammeOutcome;
use App\Models\QuestionBit;
use App\Models\QuestionPaperProfile;
use App\Models\Scheme;
use App\Models\SpecificationTableRow;
use App\Models\Syllabus;
use App\Models\SyllabusListItem;
use App\Models\SyllabusUnit;
use App\Models\UnitSubtopic;
use App\Models\UnitTopic;
use App\Models\Website;
use App\Services\SyllabusProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EXPERTSyllabusController extends Controller
{
    public function preview($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);

        $syllabus = Syllabus::firstOrCreate(
            ['course_master_id' => $courseId],
            ['rationale' => '', 'status' => 'draft', 'created_by' => Auth::id()]
        );

        // =========================
        // PROGRAMMES
        // =========================
        $offerings = CourseOffering::with('department')
            ->where('course_master_id', $course->id)
            ->get();

        $programmes = $offerings->pluck('department.abbreviation')
            ->unique()
            ->implode(' / ');

        // =========================
        // SERVICE (for dynamic sections)
        // =========================
        $service = new SyllabusProgressService($syllabus);
        $sections = $service->getAvailableSections();

        // =========================
        // FETCH ALL DATA
        // =========================

        $rationale = $syllabus->rationale;

        $industrialOutcomes = SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'industrial_outcome')
            ->orderBy('order_no')
            ->get();

        $courseOutcomes = CourseOutcome::where('syllabus_id', $syllabus->id)
            ->orderBy('order_no')
            ->get();

        $units = SyllabusUnit::with(['topics.subtopics'])
            ->where('syllabus_id', $syllabus->id)
            ->orderBy('unit_no')
            ->get();

        $specRows = SpecificationTableRow::where('syllabus_id', $syllabus->id)
            ->orderBy('order_no')
            ->get();

        $practicals = PracticalTask::with('units')
            ->where('syllabus_id', $syllabus->id)
            ->orderBy('order_no')
            ->get();

        $selfLearning = SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'self_learning')
            ->get();

        $tutorial = SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'tutorial')
            ->get();

        $instruction = SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'instruction_strategy')
            ->get();

        $books = Book::where('syllabus_id', $syllabus->id)->get();
        $websites = Website::where('syllabus_id', $syllabus->id)->get();
        $equipments = Equipment::where('syllabus_id', $syllabus->id)->get();

        $qpp = QuestionPaperProfile::where('syllabus_id', $syllabus->id)->get();
        $qb = QuestionBit::where('syllabus_id', $syllabus->id)->get();

        $cos = $courseOutcomes;

        $pos = ProgrammeOutcome::where('scheme_id', $course->scheme_id)
            ->whereNull('department_id')
            ->where('type', 'po')
            ->get();

        $psos = ProgrammeOutcome::where('scheme_id', $course->scheme_id)
            ->where('type', 'pso')
            ->get();

        $mapping = CoPoPsoMapping::where('syllabus_id', $syllabus->id)
            ->get()
            ->keyBy(fn ($m) => $m->course_outcome_id.'_'.$m->programme_outcome_id);

        return view('expert.syllabus.preview', compact(
            'course',
            'programmes',
            'sections',

            'rationale',
            'industrialOutcomes',
            'courseOutcomes',
            'units',
            'specRows',
            'practicals',
            'selfLearning',
            'tutorial',
            'instruction',
            'books',
            'websites',
            'equipments',
            'qpp',
            'qb',
            'cos',
            'pos',
            'psos',
            'mapping'
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

        CourseOutcome::where('syllabus_id', $syllabus->id)->delete();

        $order = 1;
        foreach ($items as $item) {

            $description = trim($item['description'] ?? '');
            $coCode = trim($item['co_code'] ?? '');

            if (! $description) {
                continue;
            }

            CourseOutcome::create([
                'syllabus_id' => $syllabus->id,
                'co_code' => $coCode ?: 'CO'.$order,   // fallback if left blank
                'description' => $description,
                'order_no' => $order,
            ]);

            $order++;
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
        $oldTasks = SyllabusUnit::where('syllabus_id', $syllabus->id)->get();
        foreach ($oldTasks as $old) {
            $old->practicalTasks()->detach();
        }
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

        $course = CourseMaster::findOrFail($courseId);

        $data = $request->rows ?? [];
        $total = 0;

        foreach ($data as $row) {

            $r = (int) ($row['r'] ?? 0);
            $u = (int) ($row['u'] ?? 0);
            $a = (int) ($row['a'] ?? 0);

            $total += ($r + $u + $a);
        }

        if ($total != $course->sa_th) {
            return back()
                ->withInput()
                ->withErrors('The Total Theory must be equal to '.$course->sa_th);
        }

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
        $course = CourseMaster::findOrFail($courseId);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

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
                    'syllabus_id' => $syllabus->id,
                    'lab_learning_outcome' => $taskData['outcome'] ?? null,
                    'exercise' => $taskData['exercise'] ?? '',
                    'hours' => $taskData['hours'] ?? 0,
                    'order_no' => $index + 1,
                ]);

                // Sync units — filter to integers to prevent 'on' slipping through
                $unitIds = array_filter(
                    array_map('intval', $taskData['units'] ?? []),
                    fn ($id) => $id > 0
                );

                if (! empty($unitIds)) {
                    $task->units()->sync($unitIds);
                }
            }
        });

        return back()->with('success', 'Practical tasks saved');
    }

    public function selfLearning($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $syllabus = Syllabus::firstOrCreate([
            'course_master_id' => $courseId,
        ]);

        $items = SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'self_learning')
            ->orderBy('order_no')
            ->get();

        return view('expert.syllabus.self_learning', compact(
            'course', 'syllabus', 'items', 'scheme'
        ));
    }

    public function saveSelfLearning(Request $request, $courseId)
    {
        $syllabus = Syllabus::firstOrCreate([   // consistent with GET
            'course_master_id' => $courseId,
        ]);

        SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'self_learning')
            ->delete();

        foreach ($request->input('items', []) as $index => $item) {  // null-safe
            if (! empty(trim($item['content'] ?? ''))) {
                SyllabusListItem::create([
                    'syllabus_id' => $syllabus->id,
                    'type' => 'self_learning',
                    'content' => $item['content'],
                    'order_no' => $index + 1,
                ]);
            }
        }

        return back()->with('success', 'Self Learning saved');
    }

    // ── In EXPERTSyllabusController ──────────────────────────────────────────────

    public function tutorial($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $syllabus = Syllabus::firstOrCreate(
            ['course_master_id' => $courseId]
        );

        $items = SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'tutorial')
            ->orderBy('order_no')
            ->get();

        return view('expert.syllabus.tutorial', compact('course', 'syllabus', 'items', 'scheme'));
    }

    public function saveTutorial(Request $request, $courseId)
    {
        // Removed hard validation so saving an empty list (all removed) still works
        $syllabus = Syllabus::firstOrCreate([   // consistent with GET, won't 404
            'course_master_id' => $courseId,
        ]);

        SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'tutorial')
            ->delete();

        foreach ($request->input('items', []) as $index => $item) {  // null-safe
            if (! empty(trim($item['content'] ?? ''))) {
                SyllabusListItem::create([
                    'syllabus_id' => $syllabus->id,
                    'type' => 'tutorial',
                    'content' => $item['content'],
                    'order_no' => $index + 1,
                ]);
            }
        }

        return back()->with('success', 'Tutorial items saved');
    }

    // ── In EXPERTSyllabusController ──────────────────────────────────────────────

    public function instruction($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $syllabus = Syllabus::firstOrCreate([
            'course_master_id' => $courseId,
        ]);

        $items = SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'instructional_activity')
            ->orderBy('order_no')
            ->get();

        return view('expert.syllabus.instruction', compact('course', 'syllabus', 'items', 'scheme'));
    }

    public function saveInstruction(Request $request, $courseId)
    {
        $syllabus = Syllabus::firstOrCreate([    // consistent with GET, won't 404
            'course_master_id' => $courseId,
        ]);

        SyllabusListItem::where('syllabus_id', $syllabus->id)
            ->where('type', 'instructional_activity')
            ->delete();

        foreach ($request->input('items', []) as $index => $item) {  // null-safe
            if (! empty(trim($item['content'] ?? ''))) {
                SyllabusListItem::create([
                    'syllabus_id' => $syllabus->id,
                    'type' => 'instructional_activity',
                    'content' => $item['content'],
                    'order_no' => $index + 1,
                ]);
            }
        }

        return back()->with('success', 'Instruction strategies saved');
    }

    public function books($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $syllabus = Syllabus::firstOrCreate([
            'course_master_id' => $courseId,
        ]);

        $books = Book::where('syllabus_id', $syllabus->id)
            ->orderBy('order_no')
            ->get();

        return view('expert.syllabus.books', compact('course', 'syllabus', 'books', 'scheme'));
    }

    public function saveBooks(Request $request, $courseId)
    {
        $syllabus = Syllabus::firstOrCreate([    // consistent with GET, won't 404
            'course_master_id' => $courseId,
        ]);

        Book::where('syllabus_id', $syllabus->id)->delete();

        foreach ($request->input('books', []) as $index => $book) {  // null-safe
            if (! empty(trim($book['title'] ?? ''))) {
                Book::create([
                    'syllabus_id' => $syllabus->id,
                    'title' => $book['title'],
                    'author' => $book['author'] ?? null,
                    'publication' => $book['publication'] ?? null,
                    'order_no' => $index + 1,
                ]);
            }
        }

        return back()->with('success', 'Books saved');
    }

    public function websites($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $syllabus = Syllabus::firstOrCreate(
            ['course_master_id' => $courseId]
        );

        $websites = Website::where('syllabus_id', $syllabus->id)
            ->orderBy('order_no')
            ->get();

        return view('expert.syllabus.websites', compact('course', 'syllabus', 'websites', 'scheme'));
    }

    public function saveWebsites(Request $request, $courseId)
    {

        $syllabus = Syllabus::where('course_master_id', $courseId)->firstOrFail();

        // delete old
        Website::where('syllabus_id', $syllabus->id)->delete();

        foreach ($request->input('items', []) as $index => $item) {

            if (! empty(trim($item['url'] ?? ''))) {
                Website::create([
                    'syllabus_id' => $syllabus->id,
                    'url' => $item['url'],
                    'description' => $item['description'] ?? '',
                    'order_no' => $index + 1,
                ]);
            }
        }

        return back()->with('success', 'Websites saved');
    }

    public function equipments($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $syllabus = Syllabus::firstOrCreate(
            ['course_master_id' => $courseId]
        );

        $equipments = Equipment::where('syllabus_id', $syllabus->id)
            ->orderBy('order_no')
            ->get();

        return view('expert.syllabus.equipments', compact('course', 'syllabus', 'equipments', 'scheme'));
    }

    public function saveEquipments(Request $request, $courseId)
    {

        $syllabus = Syllabus::where('course_master_id', $courseId)->firstOrFail();

        Equipment::where('syllabus_id', $syllabus->id)->delete();

        foreach ($request->input('items', []) as $index => $item) {

            if (! empty(trim($item['equipment_name'] ?? ''))) {
                Equipment::create([
                    'syllabus_id' => $syllabus->id,
                    'equipment_name' => $item['equipment_name'],
                    'specification' => $item['specification'] ?? null,
                    'order_no' => $index + 1,
                ]);
            }
        }

        return back()->with('success', 'Equipments saved');
    }

    public function questionPaperProfile($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $syllabus = Syllabus::firstOrCreate([
            'course_master_id' => $courseId,
        ]);

        $units = SyllabusUnit::where('syllabus_id', $syllabus->id)
            ->orderBy('unit_no')
            ->get();

        $specRows = SpecificationTableRow::where('syllabus_id', $syllabus->id)
            ->get()
            ->keyBy('syllabus_unit_id');

        $courseOutcomes = CourseOutcome::where('syllabus_id', $syllabus->id)
            ->orderBy('order_no')
            ->get()
            ->values();

        // ── key by unit_id now ──
        $rows = QuestionPaperProfile::where('syllabus_id', $syllabus->id)
            ->get()
            ->keyBy('syllabus_unit_id');

        return view('expert.syllabus.qpp', compact(
            'course',
            'syllabus',
            'units',
            'specRows',
            'courseOutcomes',
            'rows',
            'scheme'
        ));
    }

    public function saveQuestionPaperProfile(Request $request, $courseId)
    {
        $syllabus = Syllabus::where('course_master_id', $courseId)->firstOrFail();
        $syllabus->update(['question_multiplier' => $request->multiplier ?? null]);

        $data = $request->rows ?? [];

        // ── Validate totals ──
        $totalAdjusted = 0;
        $totalActual = 0;

        foreach ($data as $unitId => $row) {
            $totalAdjusted += (int) ($row['adjusted_marks'] ?? 0);
            $totalActual += (int) ($row['q1'] ?? 0)
                            + (int) ($row['q2'] ?? 0)
                            + (int) ($row['q3'] ?? 0)
                            + (int) ($row['q4'] ?? 0)
                            + (int) ($row['q5'] ?? 0)
                            + (int) ($row['q6'] ?? 0);
        }

        if ($totalAdjusted !== $totalActual) {
            return back()
                ->withInput()
                ->withErrors('The total of '.$syllabus->question_multiplier.' Times Marks column and Actual Distribution column must be equal.');
        }

        // ── Persist ──
        foreach ($data as $unitId => $row) {

            $spec = SpecificationTableRow::where('syllabus_id', $syllabus->id)
                ->where('syllabus_unit_id', $unitId)
                ->first();

            $marksPerUnit = $spec->total_marks ?? 0;

            // get unit_no for reference (optional, keep if still on model)
            $unit = SyllabusUnit::find($unitId);

            QuestionPaperProfile::updateOrCreate(
                [
                    'syllabus_id' => $syllabus->id,
                    'syllabus_unit_id' => $unitId,           // ── FK now
                ],
                [
                    'course_outcome_id' => $row['co_id'],
                    'marks_per_unit' => $marksPerUnit,
                    'adjusted_marks' => $row['adjusted_marks'] ?? 0,
                    'q1_marks' => $row['q1'] ?? null,
                    'q2_marks' => $row['q2'] ?? null,
                    'q3_marks' => $row['q3'] ?? null,
                    'q4_marks' => $row['q4'] ?? null,
                    'q5_marks' => $row['q5'] ?? null,
                    'q6_marks' => $row['q6'] ?? null,
                    'order_no' => $unit->unit_no ?? $unitId,
                ]
            );
        }

        return back()->with('success', 'Question Paper Profile saved');
    }

    public function questionBits($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $syllabus = Syllabus::where('course_master_id', $courseId)->firstOrFail();

        $qppRows = QuestionPaperProfile::where('syllabus_id', $syllabus->id)
            ->orderBy('syllabus_unit_id')
            ->with('syllabusUnit', 'courseOutcome')   // eager load to avoid N+1
            ->get();

        $bits = QuestionBit::where('syllabus_id', $syllabus->id)
            ->get()
            ->groupBy(['syllabus_unit_id', 'question_no', 'bit_label']);

        return view('expert.syllabus.question_bits', compact(
            'course',
            'syllabus',
            'qppRows',
            'bits',
            'scheme'
        ));
    }

    public function saveQuestionBits(Request $request, $courseId)
    {
        $syllabus = Syllabus::where('course_master_id', $courseId)->firstOrFail();
        $data = $request->bits ?? [];   // bits[unitId][qNo][bitLabel] = marks
        $errors = [];

        foreach ($data as $unitId => $questions) {

            $qpp = QuestionPaperProfile::where('syllabus_id', $syllabus->id)
                ->where('syllabus_unit_id', $unitId)
                ->first();

            if (! $qpp) {
                continue;
            }

            $unitLabel = $qpp->syllabusUnit->unit_no ?? $unitId;

            // Expected per-Q marks from QPP
            $qExpected = [];
            for ($q = 1; $q <= 6; $q++) {
                $qExpected[$q] = (int) ($qpp->{'q'.$q.'_marks'} ?? 0);
            }

            // Actual distribution = sum of all Q marks from QPP
            $actualDistribution = array_sum($qExpected);

            // ── Validate vertical totals (per Q across all bits) ──────────────────
            for ($q = 1; $q <= 6; $q++) {

                if (! isset($questions[$q])) {
                    continue;
                }

                $qTotal = 0;
                foreach ($questions[$q] as $bitLabel => $marks) {
                    $qTotal += (int) $marks;
                }

                $expectedQ = $qExpected[$q];

                if ($expectedQ > 0 && $qTotal !== $expectedQ) {
                    $errors[] = "Unit {$unitLabel} → Q{$q}: got {$qTotal}, expected {$expectedQ}";
                }
            }

            // ── Validate horizontal total (all bits across all Qs for this unit) ──
            $unitTotal = 0;
            foreach ($questions as $qNo => $bitsArr) {
                foreach ($bitsArr as $bitLabel => $marks) {
                    $unitTotal += (int) $marks;
                }
            }

            if ($unitTotal !== $actualDistribution) {
                $errors[] = "Unit {$unitLabel} sub-total: got {$unitTotal}, expected {$actualDistribution}";
            }
        }

        if (! empty($errors)) {
            return back()
                ->withInput()
                ->withErrors($errors);
        }

        DB::transaction(function () use ($data, $syllabus) {

            QuestionBit::where('syllabus_id', $syllabus->id)->delete();

            foreach ($data as $unitId => $questions) {

                $qpp = QuestionPaperProfile::where('syllabus_id', $syllabus->id)
                    ->where('syllabus_unit_id', $unitId)
                    ->first();

                if (! $qpp) {
                    continue;
                }

                foreach ($questions as $qNo => $bitsArr) {
                    foreach ($bitsArr as $bitLabel => $marks) {

                        $marks = (int) $marks;
                        if ($marks <= 0) {
                            continue;
                        }

                        QuestionBit::create([
                            'syllabus_id' => $syllabus->id,
                            'syllabus_unit_id' => $unitId,
                            'course_outcome_id' => $qpp->course_outcome_id,
                            'question_no' => $qNo,
                            'bit_label' => $bitLabel,
                            'marks' => $marks,
                            'order_no' => 1,
                        ]);
                    }
                }
            }
        });

        return back()->with('success', 'Question bits saved successfully');
    }

    public function mapping($courseId)
    {
        $course = CourseMaster::findOrFail($courseId);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $syllabus = Syllabus::where('course_master_id', $courseId)->firstOrFail();

        $departmentId = Auth::user()->department_id;

        // COs
        $cos = CourseOutcome::where('syllabus_id', $syllabus->id)
            ->orderBy('order_no')
            ->get();

        // POs (global)
        $pos = ProgrammeOutcome::where('scheme_id', $course->scheme_id)
            ->whereNull('department_id')
            ->where('type', 'po')
            ->orderBy('order_no')
            ->get();

        // PSOs (only if programme dept)
        $psos = ProgrammeOutcome::where('scheme_id', $course->scheme_id)
            ->where('department_id', $departmentId)
            ->where('type', 'pso')
            ->orderBy('order_no')
            ->get();

        $mappings = CoPoPsoMapping::where('syllabus_id', $syllabus->id)
            ->get()
            ->keyBy(fn ($m) => $m->course_outcome_id.'_'.$m->programme_outcome_id);

        return view('expert.syllabus.mapping', compact(
            'course',
            'syllabus',
            'cos',
            'pos',
            'psos',
            'mappings',
            'scheme'
        ));
    }

    public function saveMapping(Request $request, $courseId)
    {
        $syllabus = Syllabus::where('course_master_id', $courseId)->firstOrFail();

        $data = $request->mapping ?? [];

        DB::transaction(function () use ($data, $syllabus) {

            CoPoPsoMapping::where('syllabus_id', $syllabus->id)->delete();

            foreach ($data as $coId => $poList) {

                foreach ($poList as $poId => $level) {

                    if (! $level) {
                        continue;
                    }

                    CoPoPsoMapping::create([
                        'syllabus_id' => $syllabus->id,
                        'course_outcome_id' => $coId,
                        'programme_outcome_id' => $poId,
                        'level' => $level,
                    ]);
                }
            }
        });

        return back()->with('success', 'Mapping saved successfully');
    }
}

<?php

namespace App\Http\Controllers\moderator;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\CoPoPsoMapping;
use App\Models\CourseAssignment;
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
use App\Models\SyllabusRemark;
use App\Models\SyllabusUnit;
use App\Models\Website;
use App\Services\SyllabusProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MODERATORSyllabusApprovalController extends Controller
{
    public function index()
    {
        $moderatorId = Auth::id();
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $assignments = CourseAssignment::with(['courseMaster', 'expert'])
            ->where('moderator_id', $moderatorId)
            ->get();

        $data = [];

        foreach ($assignments as $a) {

            $course = $a->courseMaster;

            $syllabus = Syllabus::where('course_master_id', $course->id)->first();

            if (! $syllabus) {
                continue;
            }

            // show only relevant ones
            if (! in_array($syllabus->status, ['submitted', 'moderator_rejected'])) {
                continue;
            }

            $service = new SyllabusProgressService($syllabus, $course);

            $data[] = [
                'course' => $course,
                'expert' => $a->expert,
                'syllabus' => $syllabus,
                'progress' => $service->getProgress(),
            ];
        }

        return view('moderator.syllabus.index', compact('data', 'scheme'));
    }

    public function approve(Syllabus $syllabus)
    {
        if ($syllabus->status !== 'submitted') {
            return back()->with('error', 'Invalid action');
        }

        $syllabus->update([
            'status' => 'moderator_approved',
        ]);

        return back()->with('success', 'Syllabus approved by moderator');
    }

    public function reject(Request $request, Syllabus $syllabus)
    {
        $request->validate([
            'remark' => 'required|string',
        ]);

        if ($syllabus->status !== 'submitted') {
            return back()->withErrors('Invalid action');
        }


        DB::transaction(function () use ($request, $syllabus) {

            $syllabus->update([
                'status' => 'moderator_rejected',
            ]);
        // dd($syllabus->id);

            SyllabusRemark::create([
                'syllabus_id' => Syllabus::findOrFail($syllabus->id)->id,
                'remark' => $request->remark,
                'given_by' => Auth::id(),
            ]);
        });

        return back()->with('success', 'Syllabus rejected with remarks');
    }

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
        $service = new SyllabusProgressService($syllabus, $course);
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
            ->where('type', 'instructional_activity')
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

        return view('moderator.syllabus.preview', compact(
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
}

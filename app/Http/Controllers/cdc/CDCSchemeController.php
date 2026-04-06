<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
    use App\Models\ClassAwardRule;
use App\Models\CourseCategory;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CDCSchemeController extends Controller
{
    // SHOW CREATE FORM
    public function create()
    {
        $schemes = Scheme::latest()->get();

        return view('cdc.schemes.create.index', compact('schemes'));
    }

    // STORE SCHEME
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'year_start' => 'required|digits:4',
            'year_end' => 'required|digits:4|gt:year_start',
            'total_credits' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
        ]);

        $scheme = Scheme::create([
            'name' => $request->name,
            'year_start' => $request->year_start,
            'year_end' => $request->year_end,
            'total_credits' => $request->total_credits,
            'total_marks' => $request->total_marks,
            'created_by' => Auth::id(),
            'is_active' => false,   // default
            'is_locked' => false,   // default
        ]);

        // 👉 REDIRECT TO PAGE 2 (course categories)
        return redirect()->route('cdc.schemes.categories.create', $scheme->id)
            ->with('success', 'Scheme created. Now add course categories.');
    }



    public function editIndex()
    {
        $schemes = Scheme::latest()->get();

        return view('cdc.schemes.edit.index', compact('schemes'));
    }

    public function edit(Scheme $scheme)
    {
        // safety check
        if ($scheme->is_locked) {
            return redirect()->route('cdc.schemes.edit.index')
                ->withErrors('Locked schemes cannot be edited');
        }

        return view('cdc.schemes.edit.edit', compact('scheme'));
    }

    public function update(Request $request, Scheme $scheme)
    {
        if ($scheme->is_locked) {
            return back()->withErrors('Cannot edit locked scheme');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'year_start' => 'required|digits:4',
            'year_end' => 'required|digits:4|gt:year_start',
            'total_credits' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
        ]);

        $scheme->update([
            'name' => $request->name,
            'year_start' => $request->year_start,
            'year_end' => $request->year_end,
            'total_credits' => $request->total_credits,
            'total_marks' => $request->total_marks,
        ]);

        return back()->with('success', 'Scheme updated successfully');
    }

    public function editCategoriesRedirect(Scheme $scheme)
    {
        return redirect()->route('cdc.schemes.categories.create', $scheme->id);
    }

    public function categories($schemeId)
    {
        $scheme = Scheme::findOrFail($schemeId);

        $categories = CourseCategory::where('scheme_id', $scheme->id)
            ->orderBy('order_no')
            ->get();

        return view('cdc.schemes.edit.categories', compact('scheme', 'categories'));
    }

    public function storeCategory(Request $request, $schemeId)
    {
        $request->validate([
            'name' => 'required',
            'abbreviation' => 'required',
        ]);

        $order = CourseCategory::where('scheme_id', $schemeId)->max('order_no') + 1;

        CourseCategory::create([
            'scheme_id' => $schemeId,
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
            'order_no' => $order,
            'is_elective' => $request->is_elective ? 1 : 0
        ]);

        return redirect()
            ->route('cdc.schemes.edit.categories', $schemeId)
            ->with('success', 'Category added');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = CourseCategory::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'abbreviation' => 'required',
            'order_no' => 'required'
        ]);

        $category->update([
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
            'order_no' => $request->order_no,
            'is_elective' => $request->is_elective ? 1 : 0
        ]);

        return back()->with('success', 'Category updated');
    }

    public function destroyCategory($id)
    {
        $category = CourseCategory::findOrFail($id);

        $schemeId = $category->scheme_id;

        $category->delete();

        return redirect()
            ->route('cdc.schemes.edit.categories', $schemeId)
            ->with('success', 'Category deleted');
    }

    public function nextAfterCategories($schemeId)
    {
        return redirect()->route('cdc.schemes.edit.award', $schemeId);
    }

    public function createAwardRules(Scheme $scheme)
{
    $rule = ClassAwardRule::where('scheme_id', $scheme->id)->first();

    return view('cdc.schemes.create.award_rules', compact('scheme', 'rule'));
}

public function storeAwardRules(Request $request, Scheme $scheme)
{
    if ($scheme->is_locked) {
        return back()->withErrors('Locked scheme cannot be modified');
    }

    if($request->input('total_marks') > $scheme->total_marks){
        return back()->withErrors('Total class award marks must be less than '.$scheme->total_marks)->withInput();
    }

    $validated = $request->validate([
        'total_subjects' => 'required|integer|min:1',
        'total_marks' => 'required|integer|min:1',
    ]);

    ClassAwardRule::updateOrCreate(
        ['scheme_id' => $scheme->id],
        [
            'total_subjects_required' => $validated['total_subjects'],
            'total_marks_required' => $validated['total_marks'],
        ]
    );

    return redirect()->route('cdc.schemes.create')
        ->with('success', 'Scheme created successfully...');
}

    public function editAwardRules(Scheme $scheme)
{
    $rule = ClassAwardRule::where('scheme_id', $scheme->id)->first();

    return view('cdc.schemes.edit.award_rules', compact('scheme', 'rule'));
}

    public function updateAwardRules(Request $request, Scheme $scheme)
{
    if ($scheme->is_locked) {
        return back()->withErrors('Locked scheme cannot be edited');
    }

    $validated = $request->validate([
        'total_subjects' => 'required|integer|min:1',
        'total_marks' => 'required|integer|min:1',
    ]);

    ClassAwardRule::updateOrCreate(
        ['scheme_id' => $scheme->id],
        [
            'total_subjects_required' => $validated['total_subjects'],
            'total_marks_required' => $validated['total_marks'],
        ]
    );

    return redirect()->route('cdc.schemes.edit.index')
        ->with('success', 'Class award rules updated successfully');
}

    public function manage()
    {
        $schemes = Scheme::latest()->get();

        return view('cdc.schemes.manage', compact('schemes'));
    }

    public function toggleActive(Scheme $scheme)
    {
        $scheme = Scheme::findOrFail($scheme->id);

        if ($scheme->is_locked) {

            return back()->withErrors([
                'scheme' => 'Locked scheme cannot be activated',
            ]);

        }

        /* deactivate other schemes */

        Scheme::where('is_active', 1)->update([
            'is_active' => 0,
        ]);

        /* activate selected */

        $scheme->update([
            'is_active' => ! $scheme->is_active,
        ]);

        return back()->with('success', 'Active scheme updated');
    }

    public function destroy(Scheme $scheme)
    {
        if ($scheme->is_locked) {
            return back()->withErrors('Locked scheme cannot be deleted');
        }

        $scheme->delete();

        return back()->with('success', 'Scheme deleted');
    }

    public function toggleLock(Scheme $scheme)
    {
        $scheme = Scheme::findOrFail($scheme->id);

        /* if locking, deactivate */

        if (! $scheme->is_locked) {

            $scheme->update([
                'is_locked' => 1,
                'is_active' => 0,
            ]);

        } else {

            $scheme->update([
                'is_locked' => 0,
            ]);

        }

        return back()->with('success', 'Scheme lock status updated');
    }




}

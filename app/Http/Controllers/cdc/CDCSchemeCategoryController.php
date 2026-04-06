<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use App\Models\Scheme;
use Illuminate\Http\Request;

class CDCSchemeCategoryController extends Controller
{
    public function create(Scheme $scheme)
    {
        $categories = CourseCategory::where('scheme_id', $scheme->id)
            ->orderBy('order_no')
            ->get();

        return view('cdc.schemes.create.categories', compact('scheme', 'categories'));
    }

    public function store(Request $request, Scheme $scheme)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order_no' => 'required|numeric',
            'abbreviation' => 'required|string|max:50',
        ]);

        CourseCategory::create([
            'scheme_id' => $scheme->id,
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
            'order_no' => $request->order_no,
            'is_elective' => $request->is_elective ? 1 : 0
        ]);

        return back()->with('success', 'Category added');
    }

    public function update(Request $request, CourseCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order_no' => 'required|numeric',
            'abbreviation' => 'required|string|max:50',
        ]);

        $category->update([
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
            'order_no' => $request->order_no,
            'is_elective' => $request->is_elective ? 1 : 0

        ]);

        return back()->with('success', 'Category updated');
    }

    public function destroy(CourseCategory $category)
    {
        $category->delete();

        return back()->with('success', 'Category deleted');
    }

    public function next(Scheme $scheme)
    {
        // optional validation
        if ($scheme->courseCategories()->count() == 0) {
            return back()->withErrors('Add at least one category');
        }

        return redirect()->route('cdc.schemes.award.create',$scheme->id);
    }
}

<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class CDCDepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('order_no')->get();

        return view('cdc.department.index',compact('departments'));
    }

    public function store(Request $request)
    {
        Department::create([
            'name'=>$request->input('name'),
            'abbreviation'=>$request->input('abbreviation'),
            'type'=>$request->input('type'),
            'order_no'=>$request->input('order_no')
        ]);

        return redirect()->back();
    }

    public function update(Request $request,$id)
    {
        $department = Department::find($id);

        $department->update([
            'name'=>$request->input('name'),
            'abbreviation'=>$request->input('abbreviation'),
            'order_no'=>$request->input('order_no')

        ]);

        return redirect()->back()->with('success','Department updated successfully');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);

        // prevent deletion if users exist
        if ($department->users()->exists()) {
            return back()->with('error','Department has assigned users');
        }

        $department->delete();

        return back()->with('success','Department deleted successfully');
    }
}

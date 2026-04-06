<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CDCUserController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        $departments = Department::orderBy('order_no')->get();

        $users = User::with(['roles', 'department'])
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['cdc', 'hod',/*'cdc-dept'*/]);
            })
            ->get();

        return view('cdc.user.index', compact(
            'roles',
            'departments',
            'users'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create([

            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'department_id' => $request->input('department_id'),
            'created_by' => Auth::id(),

        ]);

        $user->roles()->attach($request->input('role_id'));

        return back()->with('success', 'User created');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([

            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'department_id' => $request->input('department_id'),

        ]);

        return back()->with('success', 'User updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return back()->with('success', 'User deleted');
    }
}

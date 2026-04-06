<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Role;
use App\Models\Scheme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HODUserController extends Controller
{
    // public function moderatorIndex()
    // {
    //     $roles = Role::all();

    //     $departments = Department::all();

    //     $users = User::with(['roles', 'department'])
    //         ->whereHas('roles', function ($q) {
    //             $q->whereIn('name', ['moderator']);
    //         })
    //         ->get();

    //     return view('hod.users.moderator', compact(
    //         'roles',
    //         'departments',
    //         'users'
    //     ));
    // }

    public function expertIndex()
    {
        $roles = Role::all();
        $scheme = Scheme::where('is_active', true)->firstOrFail();
        $departments = Department::all();

        $users = User::with(['roles', 'department'])
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['expert']);
            })
            ->get();

        return view('hod.users.expert', compact(
            'roles',
            'scheme',
            'departments',
            'users'
        ));
    }


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

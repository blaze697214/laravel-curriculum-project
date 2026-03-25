<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email','password');

        if(Auth::attempt($credentials))
        {
            $user = Auth::user();

            $request->session()->regenerate();

            if($user->roles->contains('name','cdc')){
                return redirect()->intended('/cdc/dashboard');
            }

            if($user->roles->contains('name','cdc-dept')){
                return redirect()->intended('/cdc-dept/dashboard');
            }

            if($user->roles->contains('name','hod')){
                return redirect()->intended('/hod/dashboard');
            }

            if($user->roles->contains('name','moderator')){
                return redirect()->intended('/moderator/dashboard');
            }

            if($user->roles->contains('name','expert')){
                return redirect()->intended('/expert/dashboard');
            }

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Invalid Email',
            'password' => 'Invalid Password'
        ]);

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        // $request->session()->regenerateToken();
        return redirect('/login');
    }
}

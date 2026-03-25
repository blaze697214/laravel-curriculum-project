<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HODDashBoardController extends Controller
{
    public function dashboard()
    {
        return view('hod.dashboard');
    }
}

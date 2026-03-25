<?php

namespace App\Http\Controllers\expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EXPERTDashBoardController extends Controller
{
    public function dashboard()
    {
        return view('expert.dashboard');
    }
}

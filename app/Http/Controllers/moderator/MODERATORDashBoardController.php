<?php

namespace App\Http\Controllers\moderator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MODERATORDashBoardController extends Controller
{
    public function dashboard()
    {
        return view('moderator.dashboard');
    }
}

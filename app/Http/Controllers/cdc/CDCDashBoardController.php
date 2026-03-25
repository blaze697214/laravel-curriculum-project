<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CDCDashBoardController extends Controller
{
    public function dashboard()
    {
        return view('cdc.dashboard');
    }
}

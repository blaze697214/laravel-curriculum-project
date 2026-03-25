<?php

namespace App\Http\Controllers\cdc_dept;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CDCDEPTDashBoardController extends Controller
{
    public function dashboard()
    {
        return view('cdc_dept.dashboard');
    }
}

<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\ProgrammeOutcome;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HODPSOController extends Controller
{
    public function pso()
    {
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $departmentId = Auth::user()->department_id;

        $psos = ProgrammeOutcome::where('scheme_id', $scheme->id)
            ->where('department_id', $departmentId)
            ->where('type', 'pso')
            ->orderBy('order_no')
            ->get();

        return view('hod.pso.index', compact('scheme', 'psos'));
    }

    public function savePso(Request $request)
    {
        $request->validate([
            'psos' => 'required|array',
            'psos.*.po_code' => 'required|string',
            'psos.*.description' => 'required|string',
        ]);
        $scheme = Scheme::where('is_active', true)->firstOrFail();

        $departmentId = Auth::user()->department_id;

        // delete old
        ProgrammeOutcome::where('scheme_id', $scheme->id)
            ->where('department_id', $departmentId)
            ->where('type', 'pso')
            ->delete();

        foreach ($request->psos as $index => $pso) {

            if (! trim($pso['po_code'])) {
                continue;
            }

            ProgrammeOutcome::create([
                'scheme_id' => $scheme->id,
                'department_id' => $departmentId,
                'type' => 'pso',
                'po_code' => $pso['po_code'], // PSO1, PSO2
                'description' => $pso['description'],
                'order_no' => $index + 1,
            ]);
        }

        return back()->with('success', 'PSO saved successfully');
    }
}

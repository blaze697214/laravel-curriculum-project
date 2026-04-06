<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Scheme;
use App\Services\SchemeVerificationService;

class CDCSchemeVerificationController extends Controller
{
    public function index()
    {
        $schemes = Scheme::latest()->get();

        return view('cdc.schemes.verify.index', compact('schemes'));
    }

    public function departments(Scheme $scheme, SchemeVerificationService $service)
    {
        $departments = Department::where('type', 'programme')
            ->orderBy('order_no')
            ->get();

        $statuses = [];

        foreach ($departments as $dept) {
            $result = $service->getDepartmentStatus($scheme->id, $dept->id);

            $statuses[$dept->id] = $result['is_complete'] ? 'Ready' : 'Incomplete';
        }

        return view('cdc.schemes.verify.departments', compact('scheme', 'departments', 'statuses'));
    }

    public function departmentDetail(Scheme $scheme, Department $department, SchemeVerificationService $service)
    {
        $status = $service->getDepartmentStatus($scheme->id, $department->id);

        return view('cdc.schemes.verify.detail', compact('scheme', 'department', 'status'));
    }

    public function semesterList(Scheme $scheme, Department $department, SchemeVerificationService $service)
    {
        $status = $service->getDepartmentStatus($scheme->id, $department->id);

        $semesters = [];

        for ($i = 1; $i <= 6; $i++) {
            $semesters[] = [
                'number' => $i,
                'configured' => $status['semesters'][$i] ?? false,
            ];
        }

        return view('cdc.schemes.verify.semesters', compact('scheme', 'department', 'semesters'));
    }
}

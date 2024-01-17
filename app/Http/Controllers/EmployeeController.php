<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employee.index');
    }

    public function store(Request $request)
    {
        $employeeId = $request->input('id');

        $employee = Employee::updateOrCreate(
            ['id' => $employeeId],
            [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'adress' => $request->input('adress'),
            ]
            );
        return Response()->json($employee);
    }
}

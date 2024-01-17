<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Employee::select('*'))
                ->addColumn('action', 'employee.action')
                ->rawColumns(['action'])
                ->make(true);
        }
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

    public function edit(Request $request)
    {
        $employee = Employee::find($request->input('id'));
        return Response()->json($employee);
    }

    public function destroy(Request $request)
    {
        $employee = Employee::find($request->input('id'));
        $employee->delete();
        return Response()->json($employee);
    }
}

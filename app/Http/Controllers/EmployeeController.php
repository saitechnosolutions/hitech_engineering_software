<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\DataTables\EmployeeDataTable;

class EmployeeController extends Controller
{
    public function index(EmployeeDataTable $dataTable)
    {
        return $dataTable->render('pages.employees.index');
    }

    public function store(Request $request)
    {
        $employee = new Employee();
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->mobile_number = $request->mobile_number;
        $employee->address = $request->address;
        $employee->team_id = $request->team_id;
        $employee->save();

        return response()->json([
            "status" => 'success',
            "message" => 'EMployee Created Successfully',
            "redirectTo" => '/employees'
        ]);
    }

    public function edit($id)
    {
        $employee = Employee::find($id);

        return view('pages.employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $employee->update([
            "name" => $request->name,
            "email" => $request->email,
            "mobile_number" => $request->mobile_number,
            "address" => $request->address,
            "team_id" => $request->team_id,
        ]);


        return response()->json([
            "status" => 'success',
            "message" => 'Employee Updated Successfully',
            "redirectTo" => '/employees'
        ]);
    }

    public function delete($id)
    {
        $employee = Employee::find($id);

        $employee->delete();

        return response()->json([
            "status" => 'success',
            "message" => 'Employee Deleted Successfully',
            "redirectTo" => '/employees'
        ]);
    }
}

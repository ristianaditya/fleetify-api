<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $employees = Employee::with('departement')->paginate($perPage);
        return response()->json($employees, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:50|unique:employees,employee_id',
            'departement_id' => 'required|exists:departements,id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        $employee = Employee::create($validated);
        return response()->json($employee, 201);
    }

    public function show($id)
    {
        $employee = Employee::with('departement')->findOrFail($id);
        return response()->json($employee, 200);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|string|max:50|unique:employees,employee_id,' . $employee->id,
            'departement_id' => 'required|exists:departements,id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        $employee->update($validated);
        return response()->json($employee, 200);
    }

    public function destroy($id)
    {
        Employee::destroy($id);
        return response()->json(null, 204);
    }
}

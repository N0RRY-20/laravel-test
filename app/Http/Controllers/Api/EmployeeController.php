<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return response()->json(Employee::with('user')->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nip' => 'required|unique:employees,nip',
            'position' => 'required|string',
            'status' => 'required|string',
            'base_salary' => 'required|numeric',
        ]);

        $employee = Employee::create($validated);
        return response()->json($employee, 201);
    }

    public function show(string $id)
    {
        return response()->json(Employee::with('user')->findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);
        $validated = $request->validate([
            'position' => 'string',
            'status' => 'string',
            'base_salary' => 'numeric',
        ]);
        $employee->update($validated);
        return response()->json($employee);
    }

    public function destroy(string $id)
    {
        Employee::destroy($id);
        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('employee.user');
        if ($request->has('month') && $request->has('year')) {
            $query->where('month', $request->month)->where('year', $request->year);
        }
        return response()->json($query->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
            'base_salary' => 'required|numeric',
            'allowances' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
        ]);

        $allowances = $validated['allowances'] ?? 0;
        $deductions = $validated['deductions'] ?? 0;
        $total = $validated['base_salary'] + $allowances - $deductions;

        $payroll = Payroll::create(array_merge($validated, [
            'total_amount' => $total,
            'status' => 'pending'
        ]));

        return response()->json($payroll, 201);
    }

    public function show($id)
    {
        return response()->json(Payroll::with('employee.user')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        // Update status to paid
        $payroll = Payroll::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,paid']);
        $payroll->update(['status' => $request->status]);
        return response()->json($payroll);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee.user');
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }
        return response()->json($query->paginate(10));
    }

    public function clockIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'status' => 'required|in:present,late,permit,sick',
            'note' => 'nullable|string',
        ]);

        // Check if already clocked in today
        $exists = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', Carbon::today())
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Already clocked in'], 400);
        }

        $attendance = Attendance::create([
            'employee_id' => $request->employee_id,
            'date' => Carbon::today(),
            'clock_in' => Carbon::now()->format('H:i:s'),
            'status' => $request->status,
            'note' => $request->note,
        ]);

        return response()->json($attendance, 201);
    }

    public function clockOut(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', Carbon::today())
            ->first();

        if (!$attendance) {
            return response()->json(['message' => 'Attendance not found for today'], 404);
        }

        $attendance->update([
            'clock_out' => Carbon::now()->format('H:i:s'),
        ]);

        return response()->json($attendance);
    }
}

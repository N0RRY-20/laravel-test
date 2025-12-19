<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BehaviorRecord;
use Illuminate\Http\Request;

class BehaviorRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = BehaviorRecord::with('student');
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        return response()->json($query->orderBy('date', 'desc')->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:violation,achievement',
            'points' => 'required|integer',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $record = BehaviorRecord::create($validated);
        return response()->json($record, 201);
    }

    public function show($id)
    {
        return response()->json(BehaviorRecord::with('student')->findOrFail($id));
    }
}

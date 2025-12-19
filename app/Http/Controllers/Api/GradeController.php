<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $query = Grade::with(['student', 'subject']);
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->has('classroom_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('classroom_id', $request->classroom_id);
            });
        }
        return response()->json($query->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'type' => 'required|in:harian,uts,uas',
            'semester' => 'required|integer',
            'score' => 'required|numeric|between:0,100',
        ]);

        $grade = Grade::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'subject_id' => $validated['subject_id'],
                'type' => $validated['type'],
                'semester' => $validated['semester'],
            ],
            ['score' => $validated['score']]
        );

        return response()->json($grade, 201);
    }

    public function show($id)
    {
        return response()->json(Grade::with(['student', 'subject'])->findOrFail($id));
    }
}

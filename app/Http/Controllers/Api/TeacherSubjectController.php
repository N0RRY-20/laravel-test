<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;

class TeacherSubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = TeacherSubject::with(['teacher.user', 'subject', 'classroom']);

        if ($request->has('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }
        if ($request->has('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }
        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        return response()->json($query->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:employees,id',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'academic_year' => 'required|string',
        ]);

        // Check for duplicates
        $exists = TeacherSubject::where('subject_id', $validated['subject_id'])
            ->where('classroom_id', $validated['classroom_id'])
            ->where('academic_year', $validated['academic_year'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Subject already assigned for this class and year'], 400);
        }

        $assignment = TeacherSubject::create($validated);
        return response()->json($assignment, 201);
    }

    public function show($id)
    {
        return response()->json(TeacherSubject::with(['teacher.user', 'subject', 'classroom'])->findOrFail($id));
    }

    public function destroy($id)
    {
        TeacherSubject::destroy($id);
        return response()->json(null, 204);
    }
}

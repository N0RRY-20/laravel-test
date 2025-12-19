<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['parent', 'classroom']);
        if ($request->has('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }
        return response()->json($query->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'required|exists:parents,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'nis' => 'required|unique:students,nis',
            'name' => 'required|string',
        ]);

        $student = Student::create($validated);
        return response()->json($student, 201);
    }

    public function show($id)
    {
        return response()->json(Student::with(['parent', 'classroom'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $validated = $request->validate([
            'classroom_id' => 'nullable|exists:classrooms,id',
            'name' => 'string',
            'nis' => 'string|unique:students,nis,' . $id,
        ]);
        $student->update($validated);
        return response()->json($student);
    }

    public function destroy($id)
    {
        Student::destroy($id);
        return response()->json(null, 204);
    }
}

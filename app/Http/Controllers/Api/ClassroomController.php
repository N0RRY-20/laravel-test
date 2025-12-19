<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        return response()->json(Classroom::with('homeroomTeacher')->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'academic_year' => 'required|string',
            'homeroom_teacher_id' => 'nullable|exists:employees,id',
        ]);

        $classroom = Classroom::create($validated);
        return response()->json($classroom, 201);
    }

    public function show($id)
    {
        return response()->json(Classroom::with(['homeroomTeacher', 'students'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string',
            'academic_year' => 'string',
            'homeroom_teacher_id' => 'nullable|exists:employees,id',
        ]);
        $classroom->update($validated);
        return response()->json($classroom);
    }

    public function destroy($id)
    {
        Classroom::destroy($id);
        return response()->json(null, 204);
    }
}

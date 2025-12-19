<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return response()->json(Subject::paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'code' => 'required|unique:subjects,code',
        ]);

        $subject = Subject::create($validated);
        return response()->json($subject, 201);
    }

    public function show($id)
    {
        return response()->json(Subject::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string',
            'code' => 'string|unique:subjects,code,' . $id,
        ]);
        $subject->update($validated);
        return response()->json($subject);
    }

    public function destroy($id)
    {
        Subject::destroy($id);
        return response()->json(null, 204);
    }
}

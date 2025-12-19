<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TahfidzRecord;
use Illuminate\Http\Request;

class TahfidzRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = TahfidzRecord::with('student');
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        return response()->json($query->orderBy('date', 'desc')->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:ziyadah,murajaah',
            'surah' => 'required|string',
            'verse_start' => 'nullable|integer',
            'verse_end' => 'nullable|integer',
            'rating' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $record = TahfidzRecord::create($validated);
        return response()->json($record, 201);
    }

    public function show($id)
    {
        return response()->json(TahfidzRecord::with('student')->findOrFail($id));
    }
}

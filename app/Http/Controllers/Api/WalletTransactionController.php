<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = WalletTransaction::with('student');
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        return response()->json($query->orderBy('created_at', 'desc')->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            $student = Student::lockForUpdate()->findOrFail($validated['student_id']);

            if ($validated['type'] === 'debit') {
                if ($student->wallet_balance < $validated['amount']) {
                    return response()->json(['message' => 'Insufficient balance'], 400);
                }
                $student->wallet_balance -= $validated['amount'];
            } else {
                $student->wallet_balance += $validated['amount'];
            }
            $student->save();

            $transaction = WalletTransaction::create($validated);

            return response()->json($transaction, 201);
        });
    }

    public function show($id)
    {
        return response()->json(WalletTransaction::findOrFail($id));
    }
}

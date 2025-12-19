<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\BillingPayment;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $query = Billing::with('student');
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        return response()->json($query->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string',
            'amount' => 'required|numeric',
            'due_date' => 'nullable|date',
        ]);

        $billing = Billing::create($validated);
        return response()->json($billing, 201);
    }

    public function show($id)
    {
        return response()->json(Billing::with(['student', 'payments'])->findOrFail($id));
    }

    public function pay(Request $request, $id)
    {
        $billing = Billing::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric',
            'method' => 'required|string',
        ]);

        if ($billing->status === 'paid') {
            return response()->json(['message' => 'Bill already paid'], 400);
        }

        $payment = BillingPayment::create([
            'billing_id' => $id,
            'amount' => $validated['amount'],
            'method' => $validated['method'],
        ]);

        // Recalculate total paid
        $totalPaid = $billing->payments()->sum('amount');

        if ($totalPaid >= $billing->amount) {
            $billing->update(['status' => 'paid']);
        }

        return response()->json($payment, 201);
    }
}

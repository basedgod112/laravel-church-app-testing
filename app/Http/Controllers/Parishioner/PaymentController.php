<?php

namespace App\Http\Controllers\Parishioner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = auth()->user()->payments()->latest()->paginate(10);
        return view('parishioner.payments.index', compact('payments'));
    }

    public function create()
    {
        return view('parishioner.payments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'payment_method' => 'required|string|max:255',
        ]);

        Payment::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'description' => $request->description,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
        ]);

        return redirect()->route('parishioner.payments.index')->with('success', 'Payment submitted successfully.');
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        return view('parishioner.payments.show', compact('payment'));
    }
}

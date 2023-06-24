<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Payment;
use Payment as GlobalPayment;
use Transaction as GlobalTransaction;

class TransactionController extends Controller
{
    // Method to create a new transaction
    public function create(Request $request)
    {
        // Handle transaction creation logic here
    }

    // Method to show a specific transaction
    public function show($id)
    {
        $transaction = GlobalTransaction::find($id);
        return view('transactions.show', ['transaction' => $transaction]);
    }

    // Method to process a transaction payment
    public function processPayment(Request $request, $id)
    {
        $transaction = GlobalTransaction::find($id);
        // Handle payment processing logic here

        // Create a new payment record
        $payment = new GlobalPayment;
        $payment->transaction_id = $transaction->id;
        $payment->status = 'completed';
        $payment->save();

        return redirect()->route('transactions.show', ['id' => $transaction->id])->with('success', 'Payment processed successfully!');
    }

    // Method to cancel a transaction
    public function cancel($id)
    {
        $transaction = GlobalTransaction::find($id);
        // Handle transaction cancellation logic here

        // Update the payment status
        $payment = $transaction->payment;
        $payment->status = 'cancelled';
        $payment->save();

        return redirect()->route('transactions.show', ['id' => $transaction->id])->with('success', 'Transaction cancelled successfully!');
    }
}

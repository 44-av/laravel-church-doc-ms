<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Show the table of transactions.
     */
    public function showTable()
    {
        // Fetch donations and payments
        $donations = Donation::all(); // Fetch all donations
        $payments = Payment::all(); // Fetch all payments

        // Create collections from donations and payments
        $donationTransactions = $donations->map(function ($donation) {
            return [
                'full_name' => $donation->donor_name,
                'amount' => $donation->amount,
                'date_time' => $donation->donation_date,
                'transaction_type' => 'Donation',
                'transaction_id' => $donation->transaction_id,
            ];
        });

        $paymentTransactions = $payments->map(function ($payment) {
            return [
                'full_name' => $payment->payer_name,
                'amount' => $payment->amount,
                'date_time' => $payment->payment_date,
                'transaction_type' => 'Payment',
                'transaction_id' => $payment->transaction_id,
            ];
        });

        // Merge the collections
        $transactions = $donationTransactions->concat($paymentTransactions);

        // Pass transactions to the view
        return view('admin.payment', ['transactions' => $transactions]);
    }

    /**
     * Display the index view of transactions.
     */
    public function index(Request $request)
{
    $filter = $request->query('filter'); // Check if filter is set

    if ($filter === 'monthly') {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $payments = Payment::whereBetween('payment_date', [$startOfMonth, $endOfMonth])->get();
    } else {
        $payments = Payment::all(); // Default: show all payments
    }

    $transactions = $payments->map(function ($payment) {
        return [
            'full_name' => $payment->name,
            'amount' => $payment->amount,
            'date_time' => $payment->payment_date,
            'transaction_type' => 'Payment',
            'transaction_id' => $payment->transaction_id,
        ];
    });

    return view('admin.payment', compact('transactions'));
}

}

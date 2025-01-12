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
        $payments = Payment::all(); // Fetch all donations

        // Combine donations and payments into one collection
        $transactions = $donations->map(function ($donation) {
            return [
                'full_name' => $donation->donor_name,
                'amount' => $donation->amount,
                'date_time' => $donation->donation_date,
                'transaction_type' => 'Donation',
                'transaction_id' => $donation->transaction_id,
            ];
        })->merge($payments->map(function ($payment) {
            return [
                'full_name' => $payment->payer_name,
                'amount' => $payment->amount,
                'date_time' => $payment->payment_date,
                'transaction_type' => 'Payment',
                'transaction_id' => $payment->transaction_id,
            ];
        }));

        // Debugging: Uncomment the line below if you want to inspect the data
        // dd($transactions);

        // Pass transactions to the view
        return view('admin.payment', ['transactions' => $transactions]);
    }

    /**
     * Display the index view of transactions.
     */
    public function index()
    {
        // Fetch donations and payments
        $donations = Donation::all(); // Fetch all donations
        $payments = Payment::all(); // Fetch all donations

        // Combine donations and payments into one collection
        $transactions = $donations->map(function ($donation) {
            return [
                'full_name' => $donation->donor_name,
                'amount' => $donation->amount,
                'date_time' => $donation->donation_date,
                'transaction_type' => 'Donation',
                'transaction_id' => $donation->transaction_id,
            ];
        })->merge($payments->map(function ($payment) {
            return [
                'full_name' => $payment->payer_name,
                'amount' => $payment->amount,
                'date_time' => $payment->payment_date,
                'transaction_type' => 'Payment',
                'transaction_id' => $payment->transaction_id,
            ];
        }));

        // Debugging: Uncomment the line below if you want to inspect the data
        // dd($transactions);

        // Pass transactions to the view
        return view('admin.payment', ['transactions' => $transactions]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function showTable() {
        $donations = Donation::all();  // Fetch all donations
        $payments = Payment::all();    // Fetch all payments
    
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
    
        dd($transactions); // To ensure transactions data is available
        return view('admin.payment', ['transactions' => $transactions]);
    }

    public function index() {
        $donations = Donation::all();
        $payments = Payment::all();

        return view('admin.payment', compact('donations', 'payments'));
    }
}

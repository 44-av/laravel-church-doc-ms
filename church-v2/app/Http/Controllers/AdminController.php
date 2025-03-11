<?php

namespace App\Http\Controllers;

use App\Constant\MyConstant;
use App\Models\Document;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\Mail;
use App\Models\Priest;
use App\Models\Request;
use App\Services\RequestService;
use App\Services\useValidator;
use Illuminate\Http\Request as RequestFacades;

class AdminController extends Controller
{
    public function index()
    {
        // Count of various entities
        $documents = Document::count();
        $donations = Donation::count();
        $payment = Payment::count();
        $mails = Mail::count();
        $priests = Priest::count();
        $requests = Request::all();

        // Status counts for requests
        $pending = $requests->where('status', 'Pending')->count();
        $approved = $requests->where('status', 'Approved')->count();
        $declined = $requests->where('status', 'Decline')->count();

        // Calculate the monthly total donation amount
        $monthlyTotal = Donation::whereMonth('donation_date', now()->month)
                                 ->whereYear('donation_date', now()->year)
                                 ->sum('amount'); // Ensure 'amount' is the correct column name for donations

        //  Calculate the monthly total payment amount
        $monthlyPayment = Payment::whereMonth('payment_date', now()->month)
                                 ->whereYear('payment_date', now()->year)
                                 ->sum('amount'); // Ensure 'amount' is the correct column name for payment

        // Pass all the necessary data to the view
        return view('admin.dashboard', compact(
            'documents', 'donations', 'mails', 'priests', 'requests', 
            'pending', 'approved', 'declined', 'monthlyTotal', 'monthlyPayment' // Pass $monthlyTotal to the view
        ));
    }

    public function requestBaptismal(RequestFacades $request)
    {
        $result = (new RequestService(new useValidator))
            ->requestBaptismal($request);

        if ($result['error_code'] !== MyConstant::SUCCESS_CODE) {
            return response()->json([
                'error_code' => $result['error_code'],
                'message' => $result['message'],
            ], $result['status_code']);
        }

        return redirect()->back()->with([
            'error_code' => $result['error_code'],
            'message' => $result['message'],
        ]);
    }
}

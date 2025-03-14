<?php

namespace App\Http\Controllers;

use App\Constant\MyConstant;
use App\Http\Requests\DonationRequest;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\Notification;
use App\Services\DonationService;
use Illuminate\Support\Facades\Auth;
use App\Services\useValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class DonationController extends Controller
{
    protected $useValidator;

    public function __construct(useValidator $useValidator)
    {
        $this->useValidator = $useValidator;
    }

    public function index(Request $request)
{
    $search = $request->query('search');
    $filter = $request->query('filter'); // Check if filter is set

    $donations = Donation::query()
        ->when($filter === 'monthly', function ($query) {
            $query->whereBetween('donation_date', [now()->startOfMonth(), now()->endOfMonth()]);
        })
        ->when($search, function ($query, $search) {
            return $query->where('donor_name', 'like', '%' . $search . '%')
                         ->orWhere('amount', 'like', '%' . $search . '%')
                         ->orWhere('donation_date', 'like', '%' . $search . '%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('admin.donation', compact('donations'));
}


    public function parishionerIndex()
    {
        $search = request('search');
        $donations = Donation::query()
            ->when($search, function ($query, $search) {
                return $query->where('donor_name', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('date', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('parishioner.donation', compact('donations'));
    }
    // public function showDonations()
    // {
    //     $startOfMonth = now()->startOfMonth();
    //     $endOfMonth = now()->endOfMonth();

    //     $monthlyTotal = Donation::whereBetween('donation_date', [$startOfMonth, $endOfMonth])
    //         ->sum('amount');

    //     return view('admin.payment', compact('monthlyTotal'));
    // }

    public function showDonations()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Calculate Monthly Total
        // $monthlyTotal = Donation::whereBetween('donation_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $monthlyTotal = Donation::whereMonth('donation_date', now()->month)
                                 ->whereYear('donation_date', now()->year)
                                 ->sum('amount');

        // Fetch donations within the month
        $donations = Donation::whereBetween('donation_date', [$startOfMonth, $endOfMonth])->get();
        

        // Create transactions collection
        $transactions = $donations->map(function ($donation) {
            return [
                'full_name' => $donation->donor_name,
                'amount' => $donation->amount,
                'date_time' => $donation->donation_date,
                'transaction_type' => 'Donation',
                'transaction_id' => $donation->transaction_id,
            ];
        });

        // $transactions = $donationTransactions;
        return view('admin.payment', compact('monthlyTotal', 'transactions'));
    }
    public function showPayment()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Calculate Monthly Total
        // $monthlyPayment = Payment::whereBetween('payment_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $monthlyPayment = Payment::whereMonth('payment_date', now()->month)
                                 ->whereYear('payment_date', now()->year)
                                 ->sum('amount'); // Ensure 'amount' is the correct column name for payment
        

        // Fetch payments within the month
        $payments = Payment::whereBetween('payment_date', [$startOfMonth, $endOfMonth])->get();

        $transactions = $payments->map(function ($payment) {
            return [
                'full_name' => $payment->name,
                'amount' => $payment->amount,
                'date_time' => $payment->payment_date,
                'transaction_type' => 'Payment',
                'transaction_id' => $payment->transaction_id,
            ];
        });

        // $transactions = $paymentTransactions;
        return view('admin.payment', compact('monthlyPayment', 'transactions', 'payments'));
    }


    public function store(Request $request)
    {
        $result = (new DonationService(new useValidator))
            ->store($request);

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

    // Debug store function

    // public function store(Request $request)
    // {
    //     // Process the request with DonationService
    //     $result = (new DonationService(new useValidator))->store($request);

    //     if ($result['error_code'] !== MyConstant::SUCCESS_CODE) {
    //         return response()->json([
    //             'error_code' => $result['error_code'],
    //             'message' => $result['message'],
    //         ], $result['status_code']);
    //     }

    //     return redirect()->back()->with([
    //         'error_code' => $result['error_code'],
    //         'message' => $result['message'],
    //     ]);
    // }
// STORE BEFORE PRIEST
//     public function store(Request $request)
// {
//     // Validate request
//     $request->validate([
//         'transaction_id' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//     ]);

//     // Check if file exists and process it
//     if ($request->hasFile('transaction_id')) {
//         $file = $request->file('transaction_id');
//         $filename = 'transaction_' . time() . '.' . $file->getClientOriginalExtension();
//         $file->move(public_path('assets/transactions'), $filename);

//         // Add the filename to request before passing it to DonationService
//         $request->merge(['transaction_id' => $filename]);
//     }

//     // Pass the updated request to DonationService
//     $result = (new DonationService(new UseValidator()))->store($request);

//     if ($result['error_code'] !== MyConstant::SUCCESS_CODE) {
//         return response()->json([
//             'error_code' => $result['error_code'],
//             'message' => $result['message'],
//         ], $result['status_code']);
//     }

//     return redirect()->back()->with([
//         'error_code' => $result['error_code'],
//         'message' => $result['message'],
//     ]);
    
// }


//     public function store(Request $request)
// {
//     // Pass the updated request to DonationService
//     $result = (new DonationService(new UseValidator()))->store($request);

//     if ($result['error_code'] !== MyConstant::SUCCESS_CODE) {
//         return response()->json([
//             'error_code' => $result['error_code'],
//             'message' => $result['message'],
//         ], $result['status_code']);
//     }

//     return redirect()->back()->with([
//         'error_code' => $result['error_code'],
//         'message' => $result['message'],
//     ]);
    
// }



    public function update(Request $request, $id)
    {
        $result = (new DonationService(new useValidator))
            ->update($request, $id);

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
    // update status donation
    public function updateStatus(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);
        
        // Validate the request
        $request->validate([
            'status' => 'required|string|max:255',
        ]);

        // Update only the status field
        $donation->update([
            'status' => 'Received',
        ]);

        Notification::create([
            'type' => 'Donation',
            'message' => 'A donation was received by ' . Auth::user()->name,
            'is_read' => '0',
        ]);

        return redirect()->back()->with('success', 'Donation status updated successfully.');
    }
    
    public function destroy($id)
    {
        $result = (new DonationService(new useValidator))
            ->destroy($id);

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

<?php

namespace App\Http\Controllers;

use App\Constant\MyConstant;
use App\Http\Requests\DonationRequest;
use App\Models\Donation;
use App\Services\DonationService;
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

    public function index()
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
    public function showDonations()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $monthlyTotal = Donation::whereBetween('donation_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        return view('parishioner.dashboard', compact('monthlyTotal'));
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

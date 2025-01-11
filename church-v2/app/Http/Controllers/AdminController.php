<?php

namespace App\Http\Controllers;

use App\Constant\MyConstant;
use App\Models\Document;
use App\Models\Donation;
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
        $mails = Mail::count();
        $priests = Priest::count();
        $requests = Request::all();

        // Status counts for requests
        $pending = $requests->where('status', 'Pending')->count();
        $approved = $requests->where('status', 'Approved')->count();
        $declined = $requests->where('status', 'Decline')->count();

        // Calculate the monthly total donation amount
        $monthlyTotal = Donation::whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year)
                                 ->sum('amount'); // Ensure 'amount' is the correct column name for donations

        // Pass all the necessary data to the view
        return view('admin.dashboard', compact(
            'documents', 'donations', 'mails', 'priests', 'requests', 
            'pending', 'approved', 'declined', 'monthlyTotal' // Pass $monthlyTotal to the view
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

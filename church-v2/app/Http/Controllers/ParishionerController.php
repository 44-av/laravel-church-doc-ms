<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Donation;
use App\Models\Mail;
use App\Models\Priest;
use App\Models\Request;

class ParishionerController extends Controller
{
    public function index()
    {
        $documents = Document::count();
        $donations = Donation::count();
        $mails = Mail::count();
        $priests = Priest::count();
        $requests = Request::all();

        $pending = $requests->where('status', 'Pending')->count();
        $approved = $requests->where('status', 'Approved')->count();
        $declined = $requests->where('status', 'Decline')->count();

        // Calculate the monthly total donation amount
        $monthlyTotal = Donation::whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year)
                                 ->sum('amount'); // Ensure 'amount' is the correct column name for donations

      // Pass all the necessary data to the view
      return view('parishioner.dashboard', compact(
        'documents', 'donations', 'mails', 'priests', 'requests', 
        'pending', 'approved', 'declined', 'monthlyTotal' // Pass $monthlyTotal to the view
    ));
}

}
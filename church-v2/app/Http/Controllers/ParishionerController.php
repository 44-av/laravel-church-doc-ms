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

        return view('parishioner.dashboard', compact('documents', 'donations', 'mails', 'priests', 'requests', 'pending', 'approved', 'declined'));
    }
}

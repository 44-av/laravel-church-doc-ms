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
        $documents = Document::count();
        $donations = Donation::count();
        $mails = Mail::count();
        $priests = Priest::count();
        $requests = Request::all();

        $pending = $requests->where('status', 'Pending')->count();
        $approved = $requests->where('status', 'Approved')->count();
        $declined = $requests->where('status', 'Decline')->count();

        return view('admin.dashboard', compact('documents', 'donations', 'mails', 'priests', 'requests', 'pending', 'approved', 'declined'));
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

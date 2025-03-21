<?php

namespace App\Http\Controllers;

use App\Constant\MyConstant;
use App\Models\CertificateType;
use App\Models\Request;
use App\Models\User;
use App\Models\Notification;
use App\Services\RequestService;
use App\Services\useValidator;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $status = request('status');

        $requests = Request::query()
            ->where('requested_by', Auth::user()->id)
            ->when($search, function ($query, $search) {
                return $query->where('document_type', 'like', '%' . $search . '%')
                    ->orWhere('requested_by', 'like', '%' . $search . '%')
                    ->orWhere('approved_by', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('is_paid', 'like', '%' . $search . '%');
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $certificate_types = CertificateType::all();
        $users = User::all();
        return view('parishioner.request', compact('requests', 'certificate_types', 'users'));
    }

    public function approval_request(HttpRequest $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $requests = Request::query()
            ->when($search, function ($query, $search) {
                return $query->where('document_type', 'like', '%' . $search . '%')
                    ->orWhere('requested_by', 'like', '%' . $search . '%')
                    ->orWhere('approved_by', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('is_paid', 'like', '%' . $search . '%');
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.request', compact('requests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HttpRequest $request)
    {
        $result = (new RequestService(new useValidator))
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

    public function update(HttpRequest $request, $id)
    {
        $result = (new RequestService(new useValidator))
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

    public function destroy($id)
    {
        $result = (new RequestService(new useValidator))
            ->destroy($id);

        if ($result['error_code'] !== MyConstant::SUCCESS_CODE) {
            return response()->json([
                'error_code' => $result['error_code'],
                'message' => $result['message'],
            ], $result['status_code']);
        }

        Notification::create([
            'type' => 'Request',
            'message' => 'A request has been deleted by ' . Auth::user()->name,
            'is_read' => false,
        ]);

        return redirect()->back()->with([
            'error_code' => $result['error_code'],
            'message' => $result['message'],
        ]);
    }

    public function approve_request(HttpRequest $request, $id)
    {
        $result = (new RequestService(new useValidator))
            ->approve_request($request, $id);

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

    // public function updatePayment(HttpRequest $request, $id)
    // {
    //     $amount = $request->to_pay;
    //     $result = (new RequestService(new useValidator))
    //         ->updatePayment($request, $id, $amount);

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
    public function updatePayment(HttpRequest $request, $id)
{
    // Validate request including the file
    $request->validate([
        'transaction_id' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Restrict file types and size
        'to_pay' => 'required|numeric',
    ]);

    // Handle the file upload
    if ($request->hasFile('transaction_id')) {
        $file = $request->file('transaction_id');
        $filename = time() . '_' . $file->getClientOriginalName(); // Generate unique filename

        $destinationPath = public_path('assets/payment'); // Absolute path
        $file->move($destinationPath, $filename); // Move the file
        // $file->storeAs('transaction_images', $filename); // Store in 'storage/app/public/transaction_images'
    } else {
        return redirect()->back()->with('error', 'Transaction ID image is required.');
    }

    $amount = $request->to_pay; // Match input name in the form

    $result = (new RequestService(new useValidator))
        ->updatePayment($request, $id, $amount, $filename); // Pass only filename

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


    public function showDeletedRequests()
    {
        $deletedCount = Request::where('isDeleted', 1)->count();
        return view('profile', compact('deletedCount'));
    }
}

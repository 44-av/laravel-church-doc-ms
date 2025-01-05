<?php

namespace App\Http\Controllers;

use App\Constant\MyConstant;
use App\Http\Requests\DonationRequest;
use App\Models\Donation;
use App\Services\DonationService;
use App\Services\useValidator;
use Illuminate\Http\Request;

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

<?php

namespace App\Http\Controllers;

use App\Constant\MyConstant;
use App\Models\Priest;
use App\Services\PriestService;
use App\Services\useValidator;
use Illuminate\Http\Request;

class PriestController extends Controller
{
    public function index()
    {
        $search = request('search');
        $priests = Priest::query()
            ->when($search, function ($query, $search) {
                return $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('middle_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('title', 'like', '%' . $search . '%')
                    ->orWhere('date_of_birth', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('email_address', 'like', '%' . $search . '%')
                    ->orWhere('ordination_date', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.priest', compact('priests'));
    }

    public function store(Request $request)
    {
        $result = (new PriestService(new useValidator))
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
        $result = (new PriestService(new useValidator))
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
        $result = (new PriestService(new useValidator))
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
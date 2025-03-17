<?php

namespace App\Services;

use App\Constant\MyConstant;
// use App\Jobs\DonationJob;
use App\Models\Donation;
use App\Models\Notification;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonationService
{
    private $validator;

    public function __construct(useValidator $validator)
    {
        $this->validator = $validator;
    }

    public function store(Request $request)
    {
        try {
            // Handle File Upload
            $transactionId = null;

            if ($request->hasFile('transaction_id')) {
                $file = $request->file('transaction_id');
                $extension = $file->getClientOriginalExtension();
                $filename ='donations_' . uniqid() . '.' . $extension;
            
                // Move to folder
                $file->move(public_path('assets/transactions'), $filename);
            
                // file name in database
                $data['transaction_id'] = 'assets/transactions/' . $filename;

            }
            // Save to Database
            $donation = Donation::create([
                'donor_name' => $request->donor_name,
                'donor_email' => $request->donor_email,
                'donor_phone' => $request->donor_phone,
                'donation_date' => $request->donation_date,
                'amount' => $request->amount,
                'note' => $request->note,
                'transaction_id' => $filename,
                'status' => 'Pending',
            ]);

            Notification::create([
                'type' => 'Donation',
                'message' => 'A new donation has been made by ' . $request->donor_name,
                'is_read' => '0',
            ]);

            session()->flash('success', 'Donation created successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Donation created successfully',
            ];
        } catch (QueryException $e) {
            session()->flash('error', 'Internal server error');
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => 'Internal server error',
            ];
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->validator->donationValidator());

        if ($validator->fails()) {
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::BAD_REQUEST,
                'message' => $validator->errors()->first(),
            ];
        }

        try {
            $donations = Donation::find($id);
            $donations->update([
                'donor_name' => $request->donor_name,
                'donor_email' => $request->donor_email,
                'donor_phone' => $request->donor_phone,
                'amount' => $request->amount,
                'note' => $request->note,
                // 'transaction_id' => $request->transaction_id,
                'status' => $request->status,
            ]);

            if (!$donations) {
                return [
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::NOT_FOUND,
                    'message' => 'Donation not found',
                ];
            }

            $data = $validator->validated();
            $donations->update($data);

            session()->flash('success', 'Donation updated successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Donation updated successfully',
            ];
        } catch (QueryException $e) {
            session()->flash('error', 'Internal server error');
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => 'Internal server error',
            ];
        }
    }

    public function destroy($id)
    {
        try {
            $donations = Donation::find($id);

            if (!$donations) {
                return [
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::NOT_FOUND,
                    'message' => 'Donation not found',
                ];
            }

            $donations->delete();

            Notification::create([
                'type' => 'Donation',
                'message' => 'A new donation has been deleted by ' . Auth::user()->name,
                'is_read' => false,
            ]);

            session()->flash('success', 'Donation deleted successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Donation deleted successfully',
            ];
        } catch (QueryException $e) {
            session()->flash('error', 'Internal server error');
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => 'Internal server error',
            ];
        }
    }
}
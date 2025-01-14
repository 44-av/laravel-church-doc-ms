<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function update(HttpRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            // Find the request
            $userRequest = Request::findOrFail($id);
            
            // Validate the input data
            $validatedData = $request->validate([
                'transaction_id' => 'required|string|max:255',
                'to_pay' => 'required|numeric',
            ]);
            
            // Call the service to create or update the payment
            if ($userRequest->payment) {
                // If there's an existing payment, update it
                $payment = $userRequest->payment;
                $this->paymentService->updatePayment($userRequest, $payment, $validatedData);
            } else {
                // If no payment exists, create a new one
                $this->paymentService->createPayment($userRequest, $validatedData);
            }
            
            DB::commit();

            return response()->json(['message' => 'Request updated and payment recorded successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}

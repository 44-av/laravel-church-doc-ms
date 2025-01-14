<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Services\PaymentService;
use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
=======
use App\Models\Payment;
use Illuminate\Http\Request;
>>>>>>> 3f191608379b8e0b83ed65478a88a3d8a15fcd9c
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
<<<<<<< HEAD
=======
        return view('admin.payment', compact('payments'));
    }

    public function update(Request $request, $id)
    {
            DB::beginTransaction();
            try {
                $userRequest = Request::findOrFail($id);
                $validatedData = $request->validate([
                    'transaction_id' => 'required|string|max:255',
                    'to_pay' => 'required|numeric',
        
                ]);
        
                $userRequest->update($validatedData);
                $payment = new Payment();
        
                $payment->request_id = $userRequest->id; 
                $payment->amount = $validatedData['to_pay'];
                $payment->payment_date = now();
                $payment->payment_method = "GCash"; // Or whatever method you are using
                $payment->payment_status = "Paid"; // Or whatever status you want to set
                $payment->transaction_id = $validatedData['transaction_id'];
                $payment->save();
        
                DB::commit();
        
        
                return response()->json(['message' => 'Request updated and payment recorded successfully'], 200);
        
            } catch (\Exception $e) {
        
                DB::rollback();
        
                return response()->json(['message' => 'Could not update request and add payment: ' . $e->getMessage()], 500);
        
            }
    }

    public function destroy($id)
    {
        $payment = Payment::find($id);
        $payment->delete();
        return redirect()->route('payment')->with('success', 'Payment deleted successfully');
>>>>>>> 3f191608379b8e0b83ed65478a88a3d8a15fcd9c
    }
}

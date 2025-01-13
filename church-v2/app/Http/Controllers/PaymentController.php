<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        $search = request('search');
        if ($search) {
            $payments = Payment::where('transaction_id', 'like', '%' . $search . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
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
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

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
        $payment = Payment::find($id);
        $payment->update($request->all());
        return redirect()->route('payment')->with('success', 'Payment updated successfully');
    }

    public function destroy($id)
    {
        $payment = Payment::find($id);
        $payment->delete();
        return redirect()->route('payment')->with('success', 'Payment deleted successfully');
    }
}

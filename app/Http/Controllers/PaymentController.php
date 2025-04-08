<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Rental $rental)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:deposit,rental,late_fee,damage,other',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit_card,bank_transfer,other',
            'transaction_id' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create payment
            $payment = Payment::create([
                'rental_id' => $rental->id,
                'type' => $request->type,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'notes' => $request->notes,
                'status' => 'completed',
            ]);

            // Calculate total amount paid
            $totalPaid = $rental->payments()->sum('amount');

            // Update rental status if fully paid
            if ($totalPaid >= $rental->total_amount) {
                $rental->update(['status' => 'active']);
            }

            DB::commit();

            return redirect()->route('rentals.show', $rental)
                ->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while recording the payment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        if ($payment->status !== 'completed') {
            return redirect()->route('rentals.show', $payment->rental)
                ->with('error', 'Only completed payments can be deleted.');
        }

        try {
            DB::beginTransaction();

            $rental = $payment->rental;

            // Delete payment
            $payment->delete();

            // Recalculate total amount paid
            $totalPaid = $rental->payments()->sum('amount');

            // Update rental status if not fully paid
            if ($totalPaid < $rental->total_amount) {
                $rental->update(['status' => 'pending']);
            }

            DB::commit();

            return redirect()->route('rentals.show', $rental)
                ->with('success', 'Payment deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('rentals.show', $payment->rental)
                ->with('error', 'An error occurred while deleting the payment: ' . $e->getMessage());
        }
    }
} 
<?php

namespace App\Http\Controllers;

use App\Models\PaymentOption;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'paymentOptions' => PaymentOption::all(),
        ];

        return Inertia::render('Admin/PaymentOptions', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentOption $paymentOption)
    {
        $request->validate([
            'payment' => 'required|unique:payment_options,payment,' . $paymentOption->id,
            'number' => ['required', 'regex:/^9\d{9}$/'],
            'account' => 'required|min:5',
            'description' => 'required|min:5',
            'status' => 'required|in:active,inactive',
        ]);

        $paymentOption->update($request->all());

        return redirect()->route('admin.payment-options.index')->with('success', 'Payment option updated successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        $bills = Bill::with(['patient', 'doctor'])->latest()->paginate(15);
        return view('bills.index', compact('bills'));
    }

    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        $doctors  = Doctor::orderBy('name')->get();
        return view('bills.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bill_id'      => 'required|string|unique:bills,bill_id',
            'patient_id'   => 'required|exists:patients,id',
            'doctor_id'    => 'required|exists:doctors,id',
            'bill_date'    => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount'  => 'required|numeric|min:0',
            'status'       => 'required|in:Pending,Partial,Paid',
            'description'  => 'nullable|string',
        ]);

        Bill::create($request->only([
            'bill_id', 'patient_id', 'doctor_id', 'bill_date', 'total_amount', 'paid_amount', 'status', 'description'
        ]));

        return redirect()->route('bills.index')->with('success', 'Bill created successfully.');
    }

    public function show(Bill $bill)
    {
        $bill->load(['patient', 'doctor']);
        return view('bills.show', compact('bill'));
    }

    public function edit(Bill $bill)
    {
        $patients = Patient::orderBy('name')->get();
        $doctors  = Doctor::orderBy('name')->get();
        return view('bills.edit', compact('bill', 'patients', 'doctors'));
    }

    public function update(Request $request, Bill $bill)
    {
        $request->validate([
            'bill_id'      => 'required|string|unique:bills,bill_id,' . $bill->id,
            'patient_id'   => 'required|exists:patients,id',
            'doctor_id'    => 'required|exists:doctors,id',
            'bill_date'    => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount'  => 'required|numeric|min:0',
            'status'       => 'required|in:Pending,Partial,Paid',
            'description'  => 'nullable|string',
        ]);

        $bill->update($request->only([
            'bill_id', 'patient_id', 'doctor_id', 'bill_date', 'total_amount', 'paid_amount', 'status', 'description'
        ]));

        return redirect()->route('bills.index')->with('success', 'Bill updated successfully.');
    }

    public function destroy(Bill $bill)
    {
        $bill->delete();
        return redirect()->route('bills.index')->with('success', 'Bill deleted successfully.');
    }
}

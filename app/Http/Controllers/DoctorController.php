<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::latest()->paginate(15);
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id'        => 'required|string|unique:doctors,doctor_id',
            'name'             => 'required|string|max:255',
            'specialization'   => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'email'            => 'required|email|max:255|unique:doctors,email',
            'consultation_fee' => 'required|numeric|min:0',
        ]);

        Doctor::create($request->only([
            'doctor_id', 'name', 'specialization', 'phone', 'email', 'consultation_fee'
        ]));

        return redirect()->route('doctors.index')->with('success', 'Doctor added successfully.');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['appointments.patient', 'bills.patient']);
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'doctor_id'        => 'required|string|unique:doctors,doctor_id,' . $doctor->id,
            'name'             => 'required|string|max:255',
            'specialization'   => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'email'            => 'required|email|max:255|unique:doctors,email,' . $doctor->id,
            'consultation_fee' => 'required|numeric|min:0',
        ]);

        $doctor->update($request->only([
            'doctor_id', 'name', 'specialization', 'phone', 'email', 'consultation_fee'
        ]));

        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $patients = Patient::query()
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%$s%")
                ->orWhere('patient_id', 'like', "%$s%")
                ->orWhere('phone', 'like', "%$s%"))
            ->when($request->gender, fn($q, $g) => $q->where('gender', $g))
            ->latest()->paginate(15)->withQueryString();

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'  => 'required|string|unique:patients,patient_id',
            'name'        => 'required|string|max:255',
            'age'         => 'required|integer|min:0|max:150',
            'gender'      => 'required|in:Male,Female,Other',
            'phone'       => 'required|string|max:20',
            'email'       => 'nullable|email|max:255',
            'address'     => 'required|string',
            'blood_group' => 'nullable|string|max:5',
        ]);

        Patient::create($request->only([
            'patient_id', 'name', 'age', 'gender', 'phone', 'email', 'address', 'blood_group'
        ]));

        return redirect()->route('patients.index')->with('success', 'Patient added successfully.');
    }

    public function show(Patient $patient)
    {
        $patient->load(['appointments.doctor', 'bills', 'bed']);
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'patient_id'  => 'required|string|unique:patients,patient_id,' . $patient->id,
            'name'        => 'required|string|max:255',
            'age'         => 'required|integer|min:0|max:150',
            'gender'      => 'required|in:Male,Female,Other',
            'phone'       => 'required|string|max:20',
            'email'       => 'nullable|email|max:255',
            'address'     => 'required|string',
            'blood_group' => 'nullable|string|max:5',
        ]);

        $patient->update($request->only([
            'patient_id', 'name', 'age', 'gender', 'phone', 'email', 'address', 'blood_group'
        ]));

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }
}

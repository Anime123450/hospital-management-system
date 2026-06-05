<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\Patient;
use Illuminate\Http\Request;

class BedController extends Controller
{
    public function index()
    {
        $beds = Bed::with('patient')->latest()->paginate(15);
        return view('beds.index', compact('beds'));
    }

    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        return view('beds.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bed_number' => 'required|string|unique:beds,bed_number',
            'ward'       => 'required|string|max:255',
            'bed_type'   => 'required|in:General,Semi-Private,Private,ICU',
            'status'     => 'required|in:Available,Occupied,Maintenance',
            'patient_id' => 'nullable|exists:patients,id',
        ]);

        Bed::create($request->only(['bed_number', 'ward', 'bed_type', 'status', 'patient_id']));

        return redirect()->route('beds.index')->with('success', 'Bed added successfully.');
    }

    public function show(Bed $bed)
    {
        $bed->load('patient');
        return view('beds.show', compact('bed'));
    }

    public function edit(Bed $bed)
    {
        $patients = Patient::orderBy('name')->get();
        return view('beds.edit', compact('bed', 'patients'));
    }

    public function update(Request $request, Bed $bed)
    {
        $request->validate([
            'bed_number' => 'required|string|unique:beds,bed_number,' . $bed->id,
            'ward'       => 'required|string|max:255',
            'bed_type'   => 'required|in:General,Semi-Private,Private,ICU',
            'status'     => 'required|in:Available,Occupied,Maintenance',
            'patient_id' => 'nullable|exists:patients,id',
        ]);

        $bed->update($request->only(['bed_number', 'ward', 'bed_type', 'status', 'patient_id']));

        return redirect()->route('beds.index')->with('success', 'Bed updated successfully.');
    }

    public function destroy(Bed $bed)
    {
        $bed->delete();
        return redirect()->route('beds.index')->with('success', 'Bed deleted successfully.');
    }
}

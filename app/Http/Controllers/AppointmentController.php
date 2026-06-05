<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15)->withQueryString();

        $statusCounts = Appointment::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')->pluck('total', 'status');

        return view('appointments.index', compact('appointments', 'statusCounts'));
    }

    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        $doctors  = Doctor::orderBy('name')->get();
        return view('appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id'   => 'required|string|unique:appointments,appointment_id',
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'time_slot'        => 'required|string|max:50',
            'reason'           => 'nullable|string',
            'status'           => 'required|in:Pending,Confirmed,Completed,Cancelled',
        ]);

        Appointment::create($request->only([
            'appointment_id', 'patient_id', 'doctor_id', 'appointment_date', 'time_slot', 'reason', 'status'
        ]));

        return redirect()->route('appointments.index')->with('success', 'Appointment scheduled successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::orderBy('name')->get();
        $doctors  = Doctor::orderBy('name')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'appointment_id'   => 'required|string|unique:appointments,appointment_id,' . $appointment->id,
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'time_slot'        => 'required|string|max:50',
            'reason'           => 'nullable|string',
            'status'           => 'required|in:Pending,Confirmed,Completed,Cancelled',
        ]);

        $appointment->update($request->only([
            'appointment_id', 'patient_id', 'doctor_id', 'appointment_date', 'time_slot', 'reason', 'status'
        ]));

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
    }
}

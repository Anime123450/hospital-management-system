<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\BillController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $patientCount     = \App\Models\Patient::count();
        $doctorCount      = \App\Models\Doctor::count();
        $apptCount        = \App\Models\Appointment::count();
        $todayAppts       = \App\Models\Appointment::whereDate('appointment_date', today())->count();
        $availableBeds    = \App\Models\Bed::where('status', 'Available')->count();
        $totalBeds        = \App\Models\Bed::count();
        $pendingBills     = \App\Models\Bill::whereIn('status', ['Pending', 'Partial'])->count();
        $revenue          = \App\Models\Bill::where('status', 'Paid')->sum('paid_amount');

        $recentPatients   = \App\Models\Patient::latest()->take(5)->get();
        $upcomingAppts    = \App\Models\Appointment::with(['patient', 'doctor'])
            ->whereIn('status', ['Pending', 'Confirmed'])
            ->orderBy('appointment_date')->take(6)->get();

        // Chart data
        $apptByStatus = \App\Models\Appointment::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')->pluck('total', 'status');

        $bedStats = [
            'available'   => \App\Models\Bed::where('status', 'Available')->count(),
            'occupied'    => \App\Models\Bed::where('status', 'Occupied')->count(),
            'maintenance' => \App\Models\Bed::where('status', 'Maintenance')->count(),
        ];

        return view('dashboard', compact(
            'patientCount', 'doctorCount', 'apptCount', 'todayAppts',
            'availableBeds', 'totalBeds', 'pendingBills', 'revenue',
            'recentPatients', 'upcomingAppts', 'apptByStatus', 'bedStats'
        ));
    })->name('dashboard');

    Route::resource('patients', PatientController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('beds', BedController::class);
    Route::resource('bills', BillController::class);
});

require __DIR__.'/auth.php';

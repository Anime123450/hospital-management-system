<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dr. {{ $doctor->name }}</h2>
            <div class="space-x-2">
                <a href="{{ route('doctors.edit', $doctor) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium px-4 py-2 rounded-lg">Edit</a>
                <a href="{{ route('doctors.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium px-4 py-2 rounded-lg">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Doctor Information</h3>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="font-medium text-gray-500">Doctor ID</dt><dd class="text-gray-900 dark:text-gray-100">{{ $doctor->doctor_id }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Full Name</dt><dd class="text-gray-900 dark:text-gray-100">{{ $doctor->name }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Specialization</dt><dd class="text-gray-900 dark:text-gray-100">{{ $doctor->specialization }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Phone</dt><dd class="text-gray-900 dark:text-gray-100">{{ $doctor->phone }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Email</dt><dd class="text-gray-900 dark:text-gray-100">{{ $doctor->email }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Consultation Fee</dt><dd class="text-gray-900 dark:text-gray-100">₹{{ number_format($doctor->consultation_fee, 2) }}</dd></div>
                </dl>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Recent Appointments</h3>
                @forelse($doctor->appointments->take(10) as $appt)
                    <div class="flex justify-between py-2 border-b dark:border-gray-700 text-sm">
                        <span>{{ $appt->appointment_date->format('d M Y') }} — {{ $appt->time_slot }}</span>
                        <span>{{ $appt->patient->name }}</span>
                        <span class="px-2 py-0.5 rounded text-xs
                            @if($appt->status === 'Completed') bg-green-100 text-green-800
                            @elseif($appt->status === 'Cancelled') bg-red-100 text-red-800
                            @elseif($appt->status === 'Confirmed') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $appt->status }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No appointments.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>

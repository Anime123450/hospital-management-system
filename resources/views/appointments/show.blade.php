<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Appointment: {{ $appointment->appointment_id }}</h2>
            <div class="space-x-2">
                <a href="{{ route('appointments.edit', $appointment) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium px-4 py-2 rounded-lg">Edit</a>
                <a href="{{ route('appointments.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium px-4 py-2 rounded-lg">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="font-medium text-gray-500">Appointment ID</dt><dd class="text-gray-900 dark:text-gray-100">{{ $appointment->appointment_id }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Status</dt>
                        <dd><span class="px-2 py-1 text-xs rounded-full
                            @if($appointment->status === 'Completed') bg-green-100 text-green-800
                            @elseif($appointment->status === 'Cancelled') bg-red-100 text-red-800
                            @elseif($appointment->status === 'Confirmed') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $appointment->status }}</span></dd>
                    </div>
                    <div><dt class="font-medium text-gray-500">Patient</dt><dd class="text-gray-900 dark:text-gray-100">{{ $appointment->patient->name }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Doctor</dt><dd class="text-gray-900 dark:text-gray-100">Dr. {{ $appointment->doctor->name }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Date</dt><dd class="text-gray-900 dark:text-gray-100">{{ $appointment->appointment_date->format('d M Y') }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Time Slot</dt><dd class="text-gray-900 dark:text-gray-100">{{ $appointment->time_slot }}</dd></div>
                    <div class="col-span-2"><dt class="font-medium text-gray-500">Reason</dt><dd class="text-gray-900 dark:text-gray-100">{{ $appointment->reason ?? '-' }}</dd></div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>

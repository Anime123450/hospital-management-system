<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Bill: {{ $bill->bill_id }}</h2>
            <div class="space-x-2">
                <a href="{{ route('bills.edit', $bill) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium px-4 py-2 rounded-lg">Edit</a>
                <a href="{{ route('bills.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium px-4 py-2 rounded-lg">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="font-medium text-gray-500">Bill ID</dt><dd class="text-gray-900 dark:text-gray-100">{{ $bill->bill_id }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Bill Date</dt><dd class="text-gray-900 dark:text-gray-100">{{ $bill->bill_date->format('d M Y') }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Patient</dt><dd class="text-gray-900 dark:text-gray-100">{{ $bill->patient->name }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Doctor</dt><dd class="text-gray-900 dark:text-gray-100">Dr. {{ $bill->doctor->name }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Total Amount</dt><dd class="text-gray-900 dark:text-gray-100 font-semibold">₹{{ number_format($bill->total_amount, 2) }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Paid Amount</dt><dd class="text-gray-900 dark:text-gray-100">₹{{ number_format($bill->paid_amount, 2) }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Balance</dt><dd class="text-gray-900 dark:text-gray-100 font-semibold text-red-600">₹{{ number_format($bill->total_amount - $bill->paid_amount, 2) }}</dd></div>
                    <div><dt class="font-medium text-gray-500">Status</dt>
                        <dd><span class="px-2 py-1 text-xs rounded-full
                            @if($bill->status === 'Paid') bg-green-100 text-green-800
                            @elseif($bill->status === 'Partial') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $bill->status }}</span></dd>
                    </div>
                    @if($bill->description)
                        <div class="col-span-2"><dt class="font-medium text-gray-500">Description</dt><dd class="text-gray-900 dark:text-gray-100">{{ $bill->description }}</dd></div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <h2>Appointments</h2>
                <div style="font-size:12px;color:var(--text-muted);margin-top:2px">Schedule and manage patient appointments</div>
            </div>
            <a href="{{ route('appointments.create') }}" class="hms-btn hms-btn-primary">
                <i class="fa-solid fa-calendar-plus"></i> Schedule Appointment
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="hms-alert hms-alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Status summary chips --}}
    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px">
        @foreach(['Pending','Confirmed','Completed','Cancelled'] as $s)
            <a href="{{ route('appointments.index', ['status' => $s]) }}"
               class="hms-badge badge-{{ strtolower($s) }}"
               style="font-size:12px;padding:5px 14px;text-decoration:none;{{ request('status') === $s ? 'box-shadow:0 0 0 2px currentColor;' : '' }}">
                {{ $s }} ({{ $statusCounts->get($s, 0) }})
            </a>
        @endforeach
        @if(request('status'))
            <a href="{{ route('appointments.index') }}" class="hms-badge" style="background:var(--page-bg);color:var(--text-muted);font-size:12px;padding:5px 14px;text-decoration:none">
                <i class="fa-solid fa-xmark"></i> Clear filter
            </a>
        @endif
    </div>

    <div class="hms-card">
        <div class="hms-filter-bar">
            <div class="hms-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="apptSearch" placeholder="Search patient or doctor…" oninput="filterTable(this,'apptTable')"/>
            </div>
        </div>

        <div class="hms-table-wrap">
            <table class="hms-table" id="apptTable">
                <thead>
                    <tr>
                        <th>Appt. ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Time Slot</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $a)
                        <tr>
                            <td><code style="font-size:12px;background:var(--primary-light);color:var(--primary);padding:2px 7px;border-radius:4px">{{ $a->appointment_id }}</code></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div class="hms-mini-av" style="width:28px;height:28px;font-size:10px">{{ strtoupper(substr($a->patient->name,0,2)) }}</div>
                                    <span style="font-weight:600">{{ $a->patient->name }}</span>
                                </div>
                            </td>
                            <td>Dr. {{ $a->doctor->name }}</td>
                            <td style="font-weight:500">{{ $a->appointment_date->format('d M Y') }}</td>
                            <td>
                                <span style="background:var(--page-bg);padding:3px 8px;border-radius:6px;font-size:12px">
                                    <i class="fa-regular fa-clock" style="color:var(--text-muted)"></i> {{ $a->time_slot }}
                                </span>
                            </td>
                            <td style="color:var(--text-muted);font-size:12px;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                {{ $a->reason ?? '—' }}
                            </td>
                            <td><span class="hms-badge badge-{{ strtolower($a->status) }}">{{ $a->status }}</span></td>
                            <td style="text-align:right;white-space:nowrap">
                                <a href="{{ route('appointments.show', $a) }}" class="hms-btn hms-btn-outline hms-btn-sm">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('appointments.edit', $a) }}" class="hms-btn hms-btn-outline hms-btn-sm" style="margin-left:4px">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('appointments.destroy', $a) }}" class="inline" onsubmit="return confirm('Delete this appointment?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="hms-btn hms-btn-danger hms-btn-sm" style="margin-left:4px">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">
                                <i class="fa-solid fa-calendar-days" style="font-size:28px;margin-bottom:8px;display:block;opacity:.3"></i>
                                No appointments found.
                                <a href="{{ route('appointments.create') }}" style="color:var(--primary);display:block;margin-top:6px">Schedule one →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($appointments->hasPages())
            <div style="padding:14px 18px;border-top:1px solid var(--border)">{{ $appointments->links() }}</div>
        @endif
    </div>

@push('scripts')
<script>
function filterTable(input, tableId) {
    const val = input.value.toLowerCase();
    document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
}
</script>
@endpush

</x-app-layout>

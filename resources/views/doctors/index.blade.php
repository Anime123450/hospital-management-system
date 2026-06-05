<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <h2>Doctors</h2>
                <div style="font-size:12px;color:var(--text-muted);margin-top:2px">Medical staff directory</div>
            </div>
            <a href="{{ route('doctors.create') }}" class="hms-btn hms-btn-primary">
                <i class="fa-solid fa-user-plus"></i> Add Doctor
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="hms-alert hms-alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="hms-card">
        <div class="hms-filter-bar">
            <div class="hms-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="docSearch" placeholder="Search by name or specialization…" oninput="filterTable(this,'doctorTable')"/>
            </div>
        </div>

        <div class="hms-table-wrap">
            <table class="hms-table" id="doctorTable">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>ID</th>
                        <th>Specialization</th>
                        <th>Phone</th>
                        <th>Consultation Fee</th>
                        <th>Joined</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $d)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div class="hms-mini-av" style="background:#dcfce7;color:#16a34a">{{ strtoupper(substr($d->name,0,2)) }}</div>
                                    <div>
                                        <div style="font-weight:600">{{ $d->name }}</div>
                                        <div style="font-size:11px;color:var(--text-muted)">{{ $d->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><code style="font-size:12px;background:#dcfce7;color:#16a34a;padding:2px 7px;border-radius:4px">{{ $d->doctor_id }}</code></td>
                            <td>
                                <span class="hms-badge" style="background:#E8F2FF;color:#185FA5">{{ $d->specialization }}</span>
                            </td>
                            <td>{{ $d->phone }}</td>
                            <td style="font-weight:600">₹{{ number_format($d->consultation_fee, 2) }}</td>
                            <td style="color:var(--text-muted);font-size:12px">{{ $d->created_at->format('d M Y') }}</td>
                            <td style="text-align:right;white-space:nowrap">
                                <a href="{{ route('doctors.show', $d) }}" class="hms-btn hms-btn-outline hms-btn-sm">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('doctors.edit', $d) }}" class="hms-btn hms-btn-outline hms-btn-sm" style="margin-left:4px">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('doctors.destroy', $d) }}" class="inline" onsubmit="return confirm('Remove {{ $d->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="hms-btn hms-btn-danger hms-btn-sm" style="margin-left:4px">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">
                                <i class="fa-solid fa-user-doctor" style="font-size:28px;margin-bottom:8px;display:block;opacity:.3"></i>
                                No doctors found.
                                <a href="{{ route('doctors.create') }}" style="color:var(--primary);display:block;margin-top:6px">Add your first doctor →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($doctors->hasPages())
            <div style="padding:14px 18px;border-top:1px solid var(--border)">{{ $doctors->links() }}</div>
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

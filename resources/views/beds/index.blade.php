<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <h2>Wards &amp; Beds</h2>
                <div style="font-size:12px;color:var(--text-muted);margin-top:2px">Monitor bed availability and occupancy</div>
            </div>
            <a href="{{ route('beds.create') }}" class="hms-btn hms-btn-primary">
                <i class="fa-solid fa-plus"></i> Add Bed
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="hms-alert hms-alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Summary cards --}}
    @php
        $total       = $beds->total();
        $available   = $beds->getCollection()->where('status','Available')->count();
        $occupied    = $beds->getCollection()->where('status','Occupied')->count();
        $maintenance = $beds->getCollection()->where('status','Maintenance')->count();
    @endphp

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px">
        <div class="hms-card" style="padding:16px 20px">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted);margin-bottom:8px">Total Beds</div>
            <div style="font-size:28px;font-weight:700;color:var(--text)">{{ $total }}</div>
        </div>
        <div class="hms-card" style="padding:16px 20px;border-color:#86efac">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--success);margin-bottom:8px">Available</div>
            <div style="font-size:28px;font-weight:700;color:var(--success)">{{ $available }}</div>
        </div>
        <div class="hms-card" style="padding:16px 20px;border-color:#93c5fd">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--primary);margin-bottom:8px">Occupied</div>
            <div style="font-size:28px;font-weight:700;color:var(--primary)">{{ $occupied }}</div>
        </div>
        <div class="hms-card" style="padding:16px 20px;border-color:#fcd34d">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--warning);margin-bottom:8px">Maintenance</div>
            <div style="font-size:28px;font-weight:700;color:var(--warning)">{{ $maintenance }}</div>
        </div>
    </div>

    <div class="hms-card">
        <div class="hms-filter-bar">
            <div class="hms-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="bedSearch" placeholder="Search by bed number or ward…" oninput="filterTable(this,'bedTable')"/>
            </div>
            <select id="bedStatus" class="hms-filter-select" onchange="filterByStatus()">
                <option value="">All Status</option>
                <option value="Available">Available</option>
                <option value="Occupied">Occupied</option>
                <option value="Maintenance">Maintenance</option>
            </select>
        </div>

        <div class="hms-table-wrap">
            <table class="hms-table" id="bedTable">
                <thead>
                    <tr>
                        <th>Bed Number</th>
                        <th>Ward</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Assigned Patient</th>
                        <th>Since</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beds as $b)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div style="width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:12px;
                                        background:{{ $b->status === 'Available' ? 'var(--success-light)' : ($b->status === 'Occupied' ? 'var(--primary-light)' : 'var(--warning-light)') }};
                                        color:{{ $b->status === 'Available' ? 'var(--success)' : ($b->status === 'Occupied' ? 'var(--primary)' : 'var(--warning)') }}">
                                        <i class="fa-solid fa-bed"></i>
                                    </div>
                                    <span style="font-weight:600">{{ $b->bed_number }}</span>
                                </div>
                            </td>
                            <td>{{ $b->ward }}</td>
                            <td><span class="hms-badge" style="background:var(--purple-light);color:var(--purple)">{{ $b->bed_type }}</span></td>
                            <td><span class="hms-badge badge-{{ strtolower($b->status) }}">{{ $b->status }}</span></td>
                            <td>
                                @if($b->patient)
                                    <div style="display:flex;align-items:center;gap:6px">
                                        <div class="hms-mini-av" style="width:24px;height:24px;font-size:9px">{{ strtoupper(substr($b->patient->name,0,2)) }}</div>
                                        <span style="font-size:13px">{{ $b->patient->name }}</span>
                                    </div>
                                @else
                                    <span style="color:var(--text-light)">—</span>
                                @endif
                            </td>
                            <td style="color:var(--text-muted);font-size:12px">{{ $b->created_at->format('d M Y') }}</td>
                            <td style="text-align:right;white-space:nowrap">
                                <a href="{{ route('beds.show', $b) }}" class="hms-btn hms-btn-outline hms-btn-sm">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('beds.edit', $b) }}" class="hms-btn hms-btn-outline hms-btn-sm" style="margin-left:4px">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('beds.destroy', $b) }}" class="inline" onsubmit="return confirm('Delete bed {{ $b->bed_number }}?')">
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
                                <i class="fa-solid fa-bed" style="font-size:28px;margin-bottom:8px;display:block;opacity:.3"></i>
                                No beds configured yet.
                                <a href="{{ route('beds.create') }}" style="color:var(--primary);display:block;margin-top:6px">Add your first bed →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($beds->hasPages())
            <div style="padding:14px 18px;border-top:1px solid var(--border)">{{ $beds->links() }}</div>
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
function filterByStatus() {
    const val = document.getElementById('bedStatus').value.toLowerCase();
    document.querySelectorAll('#bedTable tbody tr').forEach(row => {
        row.style.display = !val || row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
}
</script>
@endpush

</x-app-layout>

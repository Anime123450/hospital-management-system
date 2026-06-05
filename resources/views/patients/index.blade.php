<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <h2>Patients</h2>
                <div style="font-size:12px;color:var(--text-muted);margin-top:2px">
                    Manage all registered patients
                </div>
            </div>
            <a href="{{ route('patients.create') }}" class="hms-btn hms-btn-primary">
                <i class="fa-solid fa-user-plus"></i> Add Patient
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="hms-alert hms-alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="hms-card">
        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('patients.index') }}">
            <div class="hms-filter-bar">
                <div class="hms-search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" placeholder="Search by name, ID, or phone…" value="{{ request('search') }}"/>
                </div>
                <select name="gender" class="hms-filter-select">
                    <option value="">All Genders</option>
                    @foreach(['Male','Female','Other'] as $g)
                        <option value="{{ $g }}" {{ request('gender') == $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
                <button type="submit" class="hms-btn hms-btn-outline" style="height:38px">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search','gender']))
                    <a href="{{ route('patients.index') }}" class="hms-btn hms-btn-outline" style="height:38px">
                        <i class="fa-solid fa-xmark"></i> Clear
                    </a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="hms-table-wrap">
            <table class="hms-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>ID</th>
                        <th>Age / Gender</th>
                        <th>Phone</th>
                        <th>Blood</th>
                        <th>Registered</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $p)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div class="hms-mini-av">{{ strtoupper(substr($p->name,0,2)) }}</div>
                                    <div>
                                        <div style="font-weight:600">{{ $p->name }}</div>
                                        <div style="font-size:11px;color:var(--text-muted)">{{ $p->email ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><code style="font-size:12px;background:var(--primary-light);color:var(--primary);padding:2px 7px;border-radius:4px">{{ $p->patient_id }}</code></td>
                            <td>{{ $p->age }} / {{ $p->gender }}</td>
                            <td>{{ $p->phone }}</td>
                            <td>
                                @if($p->blood_group)
                                    <span class="hms-badge" style="background:#fee2e2;color:#dc2626">{{ $p->blood_group }}</span>
                                @else
                                    <span style="color:var(--text-light)">—</span>
                                @endif
                            </td>
                            <td style="color:var(--text-muted);font-size:12px">{{ $p->created_at->format('d M Y') }}</td>
                            <td style="text-align:right;white-space:nowrap">
                                <a href="{{ route('patients.show', $p) }}" class="hms-btn hms-btn-outline hms-btn-sm" title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('patients.edit', $p) }}" class="hms-btn hms-btn-outline hms-btn-sm" title="Edit" style="margin-left:4px">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('patients.destroy', $p) }}" class="inline" onsubmit="return confirm('Delete {{ $p->name }}? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="hms-btn hms-btn-danger hms-btn-sm" style="margin-left:4px" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">
                                <i class="fa-solid fa-users" style="font-size:28px;margin-bottom:8px;display:block;opacity:.3"></i>
                                No patients found.
                                <a href="{{ route('patients.create') }}" style="color:var(--primary);display:block;margin-top:6px">Add your first patient →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($patients->hasPages())
            <div style="padding:14px 18px;border-top:1px solid var(--border)">{{ $patients->links() }}</div>
        @endif
    </div>

</x-app-layout>

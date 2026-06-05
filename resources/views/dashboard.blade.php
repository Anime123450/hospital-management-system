<x-app-layout>

<div class="hms-content" style="padding:22px">

    {{-- ── Welcome strip ─────────────────────────────── --}}
    <div style="margin-bottom:20px">
        <div style="font-size:20px;font-weight:700;color:var(--text)">Hospital Management Dashboard</div>
        <div style="font-size:13px;color:var(--text-muted);margin-top:2px">
            Welcome back, <strong>{{ Auth::user()->name }}</strong> — here's what's happening today.
        </div>
    </div>

    {{-- ── Quick Actions ───────────────────────────────── --}}
    <div class="hms-qa-grid">
        <a href="{{ route('patients.create') }}" class="hms-qa-btn"
           style="background:linear-gradient(135deg,#185FA5,#2480d0)">
            <i class="fa-solid fa-user-plus"></i>
            <span class="hms-qa-label">New Patient</span>
        </a>
        <a href="{{ route('appointments.create') }}" class="hms-qa-btn"
           style="background:linear-gradient(135deg,#16a34a,#22c55e)">
            <i class="fa-solid fa-calendar-plus"></i>
            <span class="hms-qa-label">Book Appointment</span>
        </a>
        <a href="{{ route('bills.create') }}" class="hms-qa-btn"
           style="background:linear-gradient(135deg,#d97706,#f59e0b)">
            <i class="fa-solid fa-file-invoice"></i>
            <span class="hms-qa-label">Generate Bill</span>
        </a>
        <a href="{{ route('beds.index') }}" class="hms-qa-btn"
           style="background:linear-gradient(135deg,#7c3aed,#a855f7)">
            <i class="fa-solid fa-bed"></i>
            <span class="hms-qa-label">Manage Beds</span>
        </a>
    </div>

    {{-- ── Stat Cards ──────────────────────────────────── --}}
    <div class="hms-stats-grid">
        <a href="{{ route('patients.index') }}" class="hms-stat-card">
            <div class="hms-stat-header">
                <div class="hms-stat-label">Total Patients</div>
                <div class="hms-stat-icon" style="background:#E8F2FF;color:#185FA5">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="hms-stat-value">{{ $patientCount }}</div>
            <div class="hms-stat-delta">
                <i class="fa-solid fa-circle" style="font-size:6px;color:#185FA5"></i>
                All registered patients
            </div>
        </a>

        <a href="{{ route('doctors.index') }}" class="hms-stat-card">
            <div class="hms-stat-header">
                <div class="hms-stat-label">Active Doctors</div>
                <div class="hms-stat-icon" style="background:#dcfce7;color:#16a34a">
                    <i class="fa-solid fa-stethoscope"></i>
                </div>
            </div>
            <div class="hms-stat-value">{{ $doctorCount }}</div>
            <div class="hms-stat-delta">
                <i class="fa-solid fa-circle" style="font-size:6px;color:#16a34a"></i>
                Medical professionals
            </div>
        </a>

        <a href="{{ route('appointments.index') }}" class="hms-stat-card">
            <div class="hms-stat-header">
                <div class="hms-stat-label">Today's Appointments</div>
                <div class="hms-stat-icon" style="background:#fef3c7;color:#d97706">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
            </div>
            <div class="hms-stat-value">{{ $todayAppts }}</div>
            <div class="hms-stat-delta">
                <i class="fa-solid fa-circle" style="font-size:6px;color:#d97706"></i>
                {{ $apptCount }} total scheduled
            </div>
        </a>

        <a href="{{ route('beds.index') }}" class="hms-stat-card">
            <div class="hms-stat-header">
                <div class="hms-stat-label">Beds Available</div>
                <div class="hms-stat-icon" style="background:#ede9fe;color:#7c3aed">
                    <i class="fa-solid fa-bed"></i>
                </div>
            </div>
            <div class="hms-stat-value">{{ $availableBeds }}</div>
            <div class="hms-stat-delta">
                @if($totalBeds > 0)
                    <i class="fa-solid fa-arrow-trend-up" style="color:#16a34a"></i>
                    <span class="hms-delta-up">{{ $totalBeds > 0 ? round(($availableBeds/$totalBeds)*100) : 0 }}% free</span>
                    &nbsp;of {{ $totalBeds }} beds
                @else
                    <i class="fa-solid fa-circle" style="font-size:6px;color:#7c3aed"></i>
                    No beds configured yet
                @endif
            </div>
        </a>

        <a href="{{ route('bills.index') }}" class="hms-stat-card">
            <div class="hms-stat-header">
                <div class="hms-stat-label">Pending Bills</div>
                <div class="hms-stat-icon" style="background:#fee2e2;color:#dc2626">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
            </div>
            <div class="hms-stat-value">{{ $pendingBills }}</div>
            <div class="hms-stat-delta">
                @if($pendingBills > 0)
                    <i class="fa-solid fa-arrow-trend-down" style="color:#dc2626"></i>
                    <span class="hms-delta-down">Requires attention</span>
                @else
                    <i class="fa-solid fa-check-circle" style="color:#16a34a"></i>
                    <span class="hms-delta-up">All settled</span>
                @endif
            </div>
        </a>
    </div>

    {{-- ── Charts + Lists ──────────────────────────────── --}}
    <div class="hms-grid-2">

        {{-- Recent Patients --}}
        <div class="hms-card">
            <div class="hms-section-hd">
                <span class="hms-section-title"><i class="fa-solid fa-users" style="color:var(--primary);margin-right:6px"></i>Recent Patients</span>
                <a href="{{ route('patients.index') }}" class="hms-view-all">View all →</a>
            </div>
            @forelse($recentPatients as $p)
                <div class="hms-mini-row">
                    <div class="hms-mini-av">{{ strtoupper(substr($p->name, 0, 2)) }}</div>
                    <div style="flex:1;min-width:0">
                        <div class="hms-mini-name">{{ $p->name }}</div>
                        <div class="hms-mini-sub">{{ $p->patient_id }} · {{ $p->gender }}, {{ $p->age }} yrs</div>
                    </div>
                    <span class="hms-badge" style="background:var(--primary-light);color:var(--primary)">
                        {{ $p->blood_group ?? 'N/A' }}
                    </span>
                </div>
            @empty
                <div style="padding:28px 20px;text-align:center;color:var(--text-muted);font-size:13px">
                    <i class="fa-solid fa-user-plus" style="font-size:22px;margin-bottom:6px;display:block;opacity:.4"></i>
                    No patients yet. <a href="{{ route('patients.create') }}" style="color:var(--primary)">Add one →</a>
                </div>
            @endforelse
        </div>

        {{-- Appointments Status Chart --}}
        <div class="hms-card">
            <div class="hms-section-hd">
                <span class="hms-section-title"><i class="fa-solid fa-chart-pie" style="color:var(--warning);margin-right:6px"></i>Appointments by Status</span>
                <a href="{{ route('appointments.index') }}" class="hms-view-all">View all →</a>
            </div>
            <div class="hms-chart-wrap" style="display:flex;align-items:center;justify-content:center;min-height:180px">
                @if($apptCount > 0)
                    <canvas id="apptChart" height="160"></canvas>
                @else
                    <div style="text-align:center;color:var(--text-muted);font-size:13px">
                        <i class="fa-solid fa-calendar-days" style="font-size:24px;margin-bottom:8px;display:block;opacity:.35"></i>
                        No appointments yet.<br>
                        <a href="{{ route('appointments.create') }}" style="color:var(--primary)">Schedule one →</a>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ── Upcoming Appointments ───────────────────────── --}}
    <div class="hms-card" style="margin-bottom:0">
        <div class="hms-section-hd">
            <span class="hms-section-title"><i class="fa-solid fa-calendar-days" style="color:var(--success);margin-right:6px"></i>Upcoming Appointments</span>
            <a href="{{ route('appointments.index') }}" class="hms-view-all">View all →</a>
        </div>
        <div class="hms-table-wrap">
            <table class="hms-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Time Slot</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingAppts as $a)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div class="hms-mini-av" style="width:28px;height:28px;font-size:10px">{{ strtoupper(substr($a->patient->name, 0, 2)) }}</div>
                                    <span style="font-weight:600">{{ $a->patient->name }}</span>
                                </div>
                            </td>
                            <td>Dr. {{ $a->doctor->name }}</td>
                            <td>{{ $a->appointment_date->format('d M Y') }}</td>
                            <td>{{ $a->time_slot }}</td>
                            <td>
                                <span class="hms-badge badge-{{ strtolower($a->status) }}">{{ $a->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:28px;color:var(--text-muted);font-size:13px">
                                No upcoming appointments.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Bed Summary (if beds exist) ─────────────────── --}}
    @if($totalBeds > 0)
    <div style="margin-top:20px">
        <div class="hms-card">
            <div class="hms-section-hd">
                <span class="hms-section-title"><i class="fa-solid fa-bed" style="color:var(--purple);margin-right:6px"></i>Bed Occupancy</span>
                <a href="{{ route('beds.index') }}" class="hms-view-all">Manage →</a>
            </div>
            <div style="padding:16px 20px;display:flex;gap:24px;align-items:center;flex-wrap:wrap">
                <div style="flex:1;min-width:200px">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                        <div style="width:80px;font-size:12px;color:var(--text-muted)">Available</div>
                        <div style="flex:1;background:var(--page-bg);border-radius:4px;height:20px;overflow:hidden">
                            <div style="height:100%;background:var(--success);border-radius:4px;width:{{ $totalBeds ? round(($bedStats['available']/$totalBeds)*100) : 0 }}%;transition:width .5s"></div>
                        </div>
                        <div style="font-size:12px;font-weight:600;width:28px">{{ $bedStats['available'] }}</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                        <div style="width:80px;font-size:12px;color:var(--text-muted)">Occupied</div>
                        <div style="flex:1;background:var(--page-bg);border-radius:4px;height:20px;overflow:hidden">
                            <div style="height:100%;background:var(--primary);border-radius:4px;width:{{ $totalBeds ? round(($bedStats['occupied']/$totalBeds)*100) : 0 }}%;transition:width .5s"></div>
                        </div>
                        <div style="font-size:12px;font-weight:600;width:28px">{{ $bedStats['occupied'] }}</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px">
                        <div style="width:80px;font-size:12px;color:var(--text-muted)">Maintenance</div>
                        <div style="flex:1;background:var(--page-bg);border-radius:4px;height:20px;overflow:hidden">
                            <div style="height:100%;background:var(--warning);border-radius:4px;width:{{ $totalBeds ? round(($bedStats['maintenance']/$totalBeds)*100) : 0 }}%;transition:width .5s"></div>
                        </div>
                        <div style="font-size:12px;font-weight:600;width:28px">{{ $bedStats['maintenance'] }}</div>
                    </div>
                </div>
                <div style="display:flex;gap:16px;flex-wrap:wrap">
                    <div style="text-align:center;padding:14px 20px;background:var(--success-light);border-radius:10px">
                        <div style="font-size:24px;font-weight:700;color:var(--success)">{{ $bedStats['available'] }}</div>
                        <div style="font-size:11px;color:var(--success);font-weight:500">Free</div>
                    </div>
                    <div style="text-align:center;padding:14px 20px;background:var(--primary-light);border-radius:10px">
                        <div style="font-size:24px;font-weight:700;color:var(--primary)">{{ $bedStats['occupied'] }}</div>
                        <div style="font-size:11px;color:var(--primary);font-weight:500">Occupied</div>
                    </div>
                    <div style="text-align:center;padding:14px 20px;background:var(--warning-light);border-radius:10px">
                        <div style="font-size:24px;font-weight:700;color:var(--warning)">{{ $bedStats['maintenance'] }}</div>
                        <div style="font-size:11px;color:var(--warning);font-weight:500">Maintenance</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

@push('scripts')
@if($apptCount > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    const statuses = {
        Pending:   { count: {{ $apptByStatus->get('Pending', 0) }},   color: '#fbbf24' },
        Confirmed: { count: {{ $apptByStatus->get('Confirmed', 0) }}, color: '#185FA5' },
        Completed: { count: {{ $apptByStatus->get('Completed', 0) }}, color: '#16a34a' },
        Cancelled: { count: {{ $apptByStatus->get('Cancelled', 0) }}, color: '#dc2626' },
    };

    const labels = Object.keys(statuses).filter(k => statuses[k].count > 0);
    const data   = labels.map(k => statuses[k].count);
    const colors = labels.map(k => statuses[k].color);

    const ctx = document.getElementById('apptChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 14,
                        font: { size: 12, family: 'Figtree' },
                        color: '#6b7280',
                        usePointStyle: true,
                        pointStyleWidth: 8,
                    }
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.parsed} appointments`
                    }
                }
            }
        }
    });
})();
</script>
@endif
@endpush

</x-app-layout>

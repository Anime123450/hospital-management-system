<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div>
                <h2>Billing</h2>
                <div style="font-size:12px;color:var(--text-muted);margin-top:2px">Invoices and payment tracking</div>
            </div>
            <a href="{{ route('bills.create') }}" class="hms-btn hms-btn-primary">
                <i class="fa-solid fa-plus"></i> Create Bill
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="hms-alert hms-alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Revenue summary --}}
    @php
        $allBills    = \App\Models\Bill::all();
        $totalBilled = $allBills->sum('total_amount');
        $collected   = $allBills->where('status','Paid')->sum('paid_amount');
        $outstanding = $totalBilled - $collected;
    @endphp
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px">
        <div class="hms-card" style="padding:16px 20px">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted);margin-bottom:8px">Total Billed</div>
            <div style="font-size:26px;font-weight:700;color:var(--text)">₹{{ number_format($totalBilled, 2) }}</div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:4px">{{ $allBills->count() }} invoices</div>
        </div>
        <div class="hms-card" style="padding:16px 20px;border-color:#86efac">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--success);margin-bottom:8px">Collected</div>
            <div style="font-size:26px;font-weight:700;color:var(--success)">₹{{ number_format($collected, 2) }}</div>
            <div style="font-size:11px;color:var(--success);margin-top:4px">{{ $allBills->where('status','Paid')->count() }} paid bills</div>
        </div>
        <div class="hms-card" style="padding:16px 20px;border-color:#fca5a5">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--danger);margin-bottom:8px">Outstanding</div>
            <div style="font-size:26px;font-weight:700;color:var(--danger)">₹{{ number_format($outstanding, 2) }}</div>
            <div style="font-size:11px;color:var(--danger);margin-top:4px">{{ $allBills->whereIn('status',['Pending','Partial'])->count() }} unpaid</div>
        </div>
    </div>

    <div class="hms-card">
        <div class="hms-filter-bar">
            <div class="hms-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="billSearch" placeholder="Search by bill ID or patient…" oninput="filterTable(this,'billTable')"/>
            </div>
            <select class="hms-filter-select" onchange="filterByStatus(this,'billTable')">
                <option value="">All Status</option>
                <option value="Pending">Pending</option>
                <option value="Partial">Partial</option>
                <option value="Paid">Paid</option>
            </select>
        </div>

        <div class="hms-table-wrap">
            <table class="hms-table" id="billTable">
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bills as $b)
                        @php $balance = $b->total_amount - $b->paid_amount; @endphp
                        <tr>
                            <td><code style="font-size:12px;background:var(--warning-light);color:var(--warning);padding:2px 7px;border-radius:4px">{{ $b->bill_id }}</code></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div class="hms-mini-av" style="background:#fef3c7;color:#d97706;width:28px;height:28px;font-size:10px">{{ strtoupper(substr($b->patient->name,0,2)) }}</div>
                                    <span style="font-weight:600">{{ $b->patient->name }}</span>
                                </div>
                            </td>
                            <td style="font-size:13px;color:var(--text-muted)">Dr. {{ $b->doctor->name }}</td>
                            <td style="font-size:12px;color:var(--text-muted)">{{ $b->bill_date->format('d M Y') }}</td>
                            <td style="font-weight:700">₹{{ number_format($b->total_amount, 2) }}</td>
                            <td style="color:var(--success);font-weight:600">₹{{ number_format($b->paid_amount, 2) }}</td>
                            <td style="{{ $balance > 0 ? 'color:var(--danger);font-weight:600' : 'color:var(--success);font-weight:600' }}">
                                ₹{{ number_format($balance, 2) }}
                            </td>
                            <td><span class="hms-badge badge-{{ strtolower($b->status) }}">{{ $b->status }}</span></td>
                            <td style="text-align:right;white-space:nowrap">
                                <a href="{{ route('bills.show', $b) }}" class="hms-btn hms-btn-outline hms-btn-sm">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('bills.edit', $b) }}" class="hms-btn hms-btn-outline hms-btn-sm" style="margin-left:4px">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('bills.destroy', $b) }}" class="inline" onsubmit="return confirm('Delete bill {{ $b->bill_id }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="hms-btn hms-btn-danger hms-btn-sm" style="margin-left:4px">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;padding:40px;color:var(--text-muted)">
                                <i class="fa-solid fa-file-invoice" style="font-size:28px;margin-bottom:8px;display:block;opacity:.3"></i>
                                No bills found.
                                <a href="{{ route('bills.create') }}" style="color:var(--primary);display:block;margin-top:6px">Create first bill →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bills->hasPages())
            <div style="padding:14px 18px;border-top:1px solid var(--border)">{{ $bills->links() }}</div>
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
function filterByStatus(sel, tableId) {
    const val = sel.value.toLowerCase();
    document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => {
        row.style.display = !val || row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
}
</script>
@endpush

</x-app-layout>

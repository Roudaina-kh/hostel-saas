@extends('layouts.app')

@push('styles')
<style>
    .kpi-card { border-radius: 12px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,.07); transition: transform .15s; }
    .kpi-card:hover { transform: translateY(-2px); }
    .kpi-card .kpi-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
    .kpi-value { font-size: 1.6rem; font-weight: 700; line-height: 1; }
    .badge-trend { font-size: .7rem; padding: 3px 7px; border-radius: 20px; }
    .badge-up   { background: #d1fae5; color: #065f46; }
    .badge-down { background: #fee2e2; color: #991b1b; }
    .badge-flat { background: #f3f4f6; color: #6b7280; }
    .section-card { border-radius: 12px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
    .op-row { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f3f4f6; }
    .op-row:last-child { border-bottom: none; }
    .op-icon { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .9rem; flex-shrink: 0; }
    .method-badge { font-size: .7rem; padding: 2px 8px; border-radius: 20px; background: #eff6ff; color: #1d4ed8; }
    .cat-badge { font-size: .7rem; padding: 2px 8px; border-radius: 20px; background: #fef3c7; color: #92400e; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="fw-bold mb-1">Tableau de Bord Financier</h4>
            <p class="text-muted small mb-0">
                Suivi des revenus, dépenses et rentabilité de
                <strong>{{ $hostel->name }}</strong>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('staff.financial.reports.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-file-export me-1"></i> Exporter Rapport
            </a>
            <a href="{{ route('staff.cash-shifts.index') }}" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-lock me-1"></i> Clôturer Caisse
            </a>
        </div>
    </div>

    {{-- KPI Cards --}}
    @php
        $revDiff  = $prevRevenue  > 0 ? round(($monthlyRevenue  - $prevRevenue)  / $prevRevenue  * 100, 1) : null;
        $expDiff  = $prevExpenses > 0 ? round(($monthlyExpenses - $prevExpenses) / $prevExpenses * 100, 1) : null;
        $margin   = $monthlyRevenue > 0 ? round($netProfit / $monthlyRevenue * 100, 1) : 0;
        $monthLabel = now()->translatedFormat('F Y');
    @endphp

    <div class="row g-3 mb-4">
        {{-- Revenu --}}
        <div class="col-xl-3 col-md-6">
            <div class="card kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="kpi-icon" style="background:#dbeafe">💰</div>
                    @if($revDiff !== null)
                        <span class="badge-trend {{ $revDiff >= 0 ? 'badge-up' : 'badge-down' }}">
                            {{ $revDiff >= 0 ? '▲' : '▼' }} {{ abs($revDiff) }}%
                        </span>
                    @endif
                </div>
                <div class="kpi-value text-primary">{{ number_format($monthlyRevenue, 0, ',', ' ') }} <small class="fs-6 fw-normal text-muted">TND</small></div>
                <div class="text-muted small mt-1">Revenus — {{ $monthLabel }}</div>
            </div>
        </div>

        {{-- Dépenses --}}
        <div class="col-xl-3 col-md-6">
            <div class="card kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="kpi-icon" style="background:#fee2e2">📋</div>
                    @if($expDiff !== null)
                        <span class="badge-trend {{ $expDiff <= 0 ? 'badge-up' : 'badge-down' }}">
                            {{ $expDiff >= 0 ? '▲' : '▼' }} {{ abs($expDiff) }}%
                        </span>
                    @endif
                </div>
                <div class="kpi-value text-danger">{{ number_format($monthlyExpenses, 0, ',', ' ') }} <small class="fs-6 fw-normal text-muted">TND</small></div>
                <div class="text-muted small mt-1">Dépenses — {{ $monthLabel }}</div>
            </div>
        </div>

        {{-- Bénéfice Net --}}
        <div class="col-xl-3 col-md-6">
            <div class="card kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="kpi-icon" style="background:{{ $netProfit >= 0 ? '#d1fae5' : '#fee2e2' }}">
                        {{ $netProfit >= 0 ? '📈' : '📉' }}
                    </div>
                    <span class="badge-trend {{ $netProfit >= 0 ? 'badge-up' : 'badge-down' }}">
                        Marge {{ $margin }}%
                    </span>
                </div>
                <div class="kpi-value {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $netProfit >= 0 ? '' : '−' }}{{ number_format(abs($netProfit), 0, ',', ' ') }}
                    <small class="fs-6 fw-normal text-muted">TND</small>
                </div>
                <div class="text-muted small mt-1">Bénéfice Net</div>
            </div>
        </div>

        {{-- Lits --}}
        <div class="col-xl-3 col-md-6">
            <div class="card kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="kpi-icon" style="background:#fef3c7">🛏️</div>
                </div>
                <div class="kpi-value text-warning">{{ $totalBeds }}</div>
                <div class="text-muted small mt-1">Lits disponibles</div>
            </div>
        </div>
    </div>

    {{-- Chart + Recent Payments --}}
    <div class="row g-3 mb-4">

        {{-- Bar chart 6 mois --}}
        <div class="col-lg-8">
            <div class="card section-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0">Revenus &amp; Dépenses — 6 derniers mois</h6>
                    <span class="text-muted small">En TND</span>
                </div>
                <canvas id="barChart" style="max-height:260px"></canvas>
            </div>
        </div>

        {{-- Derniers paiements --}}
        <div class="col-lg-4">
            <div class="card section-card p-3 h-100">
                <h6 class="fw-semibold mb-3">Derniers paiements</h6>
                @forelse($recentPayments as $p)
                    <div class="op-row">
                        <div class="op-icon" style="background:#dbeafe">💳</div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-semibold small text-truncate">
                                {{ trim(($p->reservation?->mainGuest?->first_name ?? '') . ' ' . ($p->reservation?->mainGuest?->last_name ?? '')) ?: '—' }}
                            </div>
                            <div class="text-muted" style="font-size:.72rem">
                                {{ $p->created_at?->format('d/m/Y') ?? '—' }}
                                &nbsp;<span class="method-badge">{{ $p->payment_method }}</span>
                            </div>
                        </div>
                        <div class="fw-bold text-primary small">+{{ number_format($p->amount_tnd, 2) }}</div>
                    </div>
                @empty
                    <p class="text-muted small">Aucun paiement ce mois-ci.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Expenses --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="card section-card p-3">
                <h6 class="fw-semibold mb-3">Dernières dépenses enregistrées</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Libellé</th>
                                <th>Catégorie</th>
                                <th>Payé par</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentExpenses as $e)
                            <tr>
                                <td class="text-muted small">{{ \Carbon\Carbon::parse($e->expense_date)->format('d/m/Y') }}</td>
                                <td class="small">{{ $e->label }}</td>
                                <td><span class="cat-badge">{{ $e->category }}</span></td>
                                <td class="text-muted small">{{ $e->payer_name ?? '—' }}</td>
                                <td class="text-end fw-semibold text-danger small">{{ number_format($e->amount, 2) }} TND</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-muted small text-center py-3">Aucune dépense récente.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const labels  = @json($chartLabels);
    const rev     = @json($chartRev);
    const exp     = @json($chartExp);

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Revenus',
                    data: rev,
                    backgroundColor: 'rgba(59,130,246,.75)',
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Dépenses',
                    data: exp,
                    backgroundColor: 'rgba(239,68,68,.65)',
                    borderRadius: 6,
                    borderSkipped: false,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { boxWidth: 12, padding: 16 } },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.dataset.label} : ${ctx.parsed.y.toLocaleString('fr-TN', {minimumFractionDigits:2})} TND`
                    }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => v.toLocaleString('fr-TN') + ' TND'
                    }
                }
            }
        }
    });
})();
</script>
@endpush

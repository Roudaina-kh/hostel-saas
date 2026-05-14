@extends('layouts.app')
@section('title', 'Analytics — ' . $hostel->name)
@section('content')

@php
    function trendPct($current, $prev): ?float {
        if ($prev == 0) return null;
        return round(($current - $prev) / $prev * 100, 1);
    }
    $revTrend  = trendPct($mRevenue, $pRevenue);
    $expTrend  = trendPct($mExpenses, $pExpenses);
    $pftTrend  = trendPct($mProfit, $pProfit);
    $mMargin   = $mRevenue > 0 ? round($mProfit / $mRevenue * 100, 1) : 0;
@endphp

<style>
/* ═══════════════════════════════════════════════════════════════
   ANALYTICS FINANCIERS — HostelFlow
   ═══════════════════════════════════════════════════════════════ */

.an-wrap {
    font-family: 'DM Sans', sans-serif;
    padding-bottom: 3rem;
}

/* ── HERO ─────────────────────────────────────────────────────── */
.an-hero {
    background: linear-gradient(135deg, #0F1C2E 0%, #1A3A3A 45%, #1C2B1A 100%);
    border-radius: 28px;
    padding: 2.5rem 2.5rem 5rem;
    position: relative;
    overflow: hidden;
    margin-bottom: -3rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.an-hero::before {
    content: '';
    position: absolute;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(99,102,241,.18) 0%, transparent 65%);
    top: -200px; right: -100px;
    pointer-events: none;
}
.an-hero::after {
    content: '';
    position: absolute;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(34,197,94,.12) 0%, transparent 70%);
    bottom: -50px; left: 15%;
    pointer-events: none;
}
.an-hero-body { position: relative; z-index: 1; }
.an-hero-right { position: relative; z-index: 1; text-align: right; }
.an-live-badge {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(34,197,94,.15);
    border: 1px solid rgba(34,197,94,.4);
    border-radius: 99px;
    padding: 4px 12px;
    font-size: 11px; font-weight: 700;
    color: #86EFAC;
    letter-spacing: .1em;
    text-transform: uppercase;
    margin-bottom: 1rem;
}
.an-live-dot {
    width: 7px; height: 7px;
    background: #22C55E;
    border-radius: 50%;
    animation: pulse-live 1.4s ease-in-out infinite;
}
@keyframes pulse-live {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .4; transform: scale(.7); }
}
.an-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.6rem;
    font-weight: 700;
    color: #FEFCF9;
    line-height: 1.15;
    margin-bottom: 8px;
}
.an-subtitle {
    font-size: 14px;
    color: rgba(254,252,249,.55);
    font-weight: 500;
    margin: 0;
}
.an-hero-date {
    font-size: 13px;
    color: rgba(254,252,249,.45);
    font-weight: 600;
    margin-top: 6px;
}
.an-update-tag {
    font-size: 11px;
    color: rgba(254,252,249,.35);
    margin-top: 4px;
}

/* ── KPI CARDS ─────────────────────────────────────────────────── */
.an-kpi-row {
    display: grid;
    gap: 1.25rem;
    position: relative;
    z-index: 10;
    margin-bottom: 1.25rem;
}
.an-kpi-row-3 { grid-template-columns: repeat(3, 1fr); }
.an-kpi-row-4 { grid-template-columns: repeat(4, 1fr); margin-top: 0; }

.an-card {
    background: #FEFCF9;
    border-radius: 20px;
    padding: 1.6rem;
    border: 1px solid #DDD6CA;
    box-shadow: 0 8px 30px rgba(28,28,36,.07);
    position: relative;
    overflow: hidden;
    transition: transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s;
}
.an-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 18px 44px rgba(28,28,36,.12);
}
.an-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 20px 20px 0 0;
}
.an-card.rev::before { background: linear-gradient(90deg, #22C55E, #86EFAC); }
.an-card.exp::before { background: linear-gradient(90deg, #EF4444, #FCA5A5); }
.an-card.pft::before { background: linear-gradient(90deg, #6366F1, #A5B4FC); }
.an-card.org::before { background: linear-gradient(90deg, #C8602A, #F97316); }
.an-card.teal::before{ background: linear-gradient(90deg, #1B6B6B, #22D3EE); }

.an-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1rem;
}
.an-icon {
    width: 46px; height: 46px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.rev .an-icon  { background: #DCFCE7; }
.exp .an-icon  { background: #FEE2E2; }
.pft .an-icon  { background: #EDE9FE; }
.org .an-icon  { background: #FEF3E2; }
.teal .an-icon { background: #E8F4F0; }

.an-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .09em;
    color: #6B6B7A;
    margin: 0 0 4px;
}
.an-big-num {
    font-family: 'Playfair Display', serif;
    font-size: 2.4rem;
    font-weight: 700;
    letter-spacing: -1px;
    line-height: 1;
    margin: 0 0 2px;
}
.an-big-num.sm { font-size: 1.8rem; }
.an-unit { font-size: 12px; font-weight: 600; color: #6B6B7A; }
.rev .an-big-num  { color: #16A34A; }
.exp .an-big-num  { color: #DC2626; }
.pft .an-big-num  { color: #4F46E5; }
.org .an-big-num  { color: #C8602A; }
.teal .an-big-num { color: #1B6B6B; }

/* Trend badge */
.an-trend {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px;
    border-radius: 99px;
    font-size: 12px;
    font-weight: 700;
    margin-top: 10px;
}
.an-trend.up   { background: #DCFCE7; color: #16A34A; }
.an-trend.down { background: #FEE2E2; color: #DC2626; }
.an-trend.flat { background: #F1F5F9; color: #6B6B7A; }

/* ── CHARTS ROW ─────────────────────────────────────────────────── */
.an-charts-row {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}
.an-chart-card {
    background: #FEFCF9;
    border-radius: 20px;
    border: 1px solid #DDD6CA;
    box-shadow: 0 8px 30px rgba(28,28,36,.07);
    overflow: hidden;
}
.an-chart-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #EDE8E0;
    background: linear-gradient(90deg, #FAF6EF, #F5EFE6);
}
.an-chart-title {
    font-size: 15px;
    font-weight: 700;
    color: #2E2E3A;
    display: flex;
    align-items: center;
    gap: 8px;
}
.an-chart-body {
    padding: 1.25rem 1.5rem;
}

/* ── BOTTOM ROW ────────────────────────────────────────────────── */
.an-bottom-row {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.25rem;
}

/* ── TABLE ────────────────────────────────────────────────────── */
.an-table {
    width: 100%;
    border-collapse: collapse;
}
.an-table th {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #6B6B7A;
    padding: 8px 12px;
    border-bottom: 1px solid #EDE8E0;
    text-align: left;
    white-space: nowrap;
}
.an-table td {
    padding: 11px 12px;
    font-size: 13.5px;
    color: #2E2E3A;
    border-bottom: 1px solid #F5EFE6;
    vertical-align: middle;
}
.an-table tr:last-child td { border-bottom: none; }
.an-table tr:hover td { background: #FAF6EF; }

.an-method-badge {
    display: inline-block;
    padding: 2px 9px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 600;
}
.method-cash     { background: #DCFCE7; color: #16A34A; }
.method-card     { background: #EDE9FE; color: #4F46E5; }
.method-transfer { background: #FEF3E2; color: #C8602A; }
.method-other    { background: #F1F5F9; color: #6B6B7A; }

/* ── SUMMARY SIDE ────────────────────────────────────────────── */
.an-summary-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .9rem 1.25rem;
    border-bottom: 1px solid #F5EFE6;
}
.an-summary-item:last-child { border-bottom: none; }
.an-summary-label {
    font-size: 13px;
    font-weight: 500;
    color: #6B6B7A;
    display: flex;
    align-items: center;
    gap: 8px;
}
.an-summary-value {
    font-size: 15px;
    font-weight: 700;
    color: #2E2E3A;
}

/* ── GAUGE ─────────────────────────────────────────────────────── */
.an-gauge-track {
    height: 8px;
    background: #EDE8E0;
    border-radius: 99px;
    overflow: hidden;
    margin-top: 6px;
}
.an-gauge-fill {
    height: 100%;
    border-radius: 99px;
    transition: width 1s ease;
}

/* ── FADE ANIMATIONS ───────────────────────────────────────────── */
.an-fade { opacity: 0; transform: translateY(18px); animation: anFade .55s cubic-bezier(.4,0,.2,1) forwards; }
@keyframes anFade { to { opacity: 1; transform: translateY(0); } }
.ad1 { animation-delay: .05s; }
.ad2 { animation-delay: .12s; }
.ad3 { animation-delay: .20s; }
.ad4 { animation-delay: .28s; }
.ad5 { animation-delay: .36s; }
.ad6 { animation-delay: .44s; }

/* ── NUMBER FLASH ──────────────────────────────────────────────── */
@keyframes numFlash { 0%,100% { opacity: 1; } 50% { opacity: .3; } }
.refreshing { animation: numFlash .5s ease; }

@media (max-width: 900px) {
    .an-kpi-row-3 { grid-template-columns: 1fr 1fr; }
    .an-kpi-row-4 { grid-template-columns: 1fr 1fr; }
    .an-charts-row, .an-bottom-row { grid-template-columns: 1fr; }
}
</style>

<div class="an-wrap">

{{-- ── HERO ──────────────────────────────────────────────────────────── --}}
<div class="an-hero an-fade ad1">
    <div class="an-hero-body">
        <div class="an-live-badge">
            <span class="an-live-dot"></span> Live
        </div>
        <h1 class="an-title">Analytics Financiers</h1>
        <p class="an-subtitle">{{ $hostel->name }} — {{ now()->isoFormat('MMMM YYYY') }}</p>
    </div>
    <div class="an-hero-right">
        <div class="an-hero-date">{{ now()->format('d M Y') }}</div>
        <div class="an-update-tag" id="an-last-update">Actualisé à {{ now()->format('H:i') }}</div>
    </div>
</div>

{{-- ── KPI MAIN (3 cards, all-time) ──────────────────────────────────── --}}
<div class="an-kpi-row an-kpi-row-3 an-fade ad2">

    {{-- Revenue --}}
    <div class="an-card rev">
        <div class="an-card-header">
            <div>
                <p class="an-label">Revenus Total</p>
                <p class="an-big-num" id="kpi-total-rev" data-val="{{ $totalRevenue }}">
                    {{ number_format($totalRevenue, 0, '.', ' ') }}
                </p>
                <span class="an-unit">TND (hors annulées)</span>
            </div>
            <div class="an-icon">💰</div>
        </div>
        @php $t = trendPct($mRevenue, $pRevenue); @endphp
        @if($t !== null)
            <span class="an-trend {{ $t >= 0 ? 'up' : 'down' }}" id="badge-rev-trend">
                {{ $t >= 0 ? '↑' : '↓' }} {{ abs($t) }}% vs mois dernier
            </span>
        @else
            <span class="an-trend flat" id="badge-rev-trend">Nouveau</span>
        @endif
    </div>

    {{-- Expenses --}}
    <div class="an-card exp">
        <div class="an-card-header">
            <div>
                <p class="an-label">Dépenses Totales</p>
                <p class="an-big-num" id="kpi-total-exp" data-val="{{ $totalExpenses }}">
                    {{ number_format($totalExpenses, 0, '.', ' ') }}
                </p>
                <span class="an-unit">TND</span>
            </div>
            <div class="an-icon">💸</div>
        </div>
        @php $t = trendPct($mExpenses, $pExpenses); @endphp
        @if($t !== null)
            <span class="an-trend {{ $t <= 0 ? 'up' : 'down' }}" id="badge-exp-trend">
                {{ $t >= 0 ? '↑' : '↓' }} {{ abs($t) }}% vs mois dernier
            </span>
        @else
            <span class="an-trend flat" id="badge-exp-trend">Nouveau</span>
        @endif
    </div>

    {{-- Profit --}}
    <div class="an-card {{ $netProfit >= 0 ? 'pft' : 'exp' }}">
        <div class="an-card-header">
            <div>
                <p class="an-label">Bénéfice Net</p>
                <p class="an-big-num" id="kpi-total-pft" data-val="{{ $netProfit }}">
                    {{ $netProfit >= 0 ? '+' : '' }}{{ number_format($netProfit, 0, '.', ' ') }}
                </p>
                <span class="an-unit">TND</span>
            </div>
            <div class="an-icon">📈</div>
        </div>
        @php $t = trendPct($mProfit, $pProfit); @endphp
        @if($t !== null)
            <span class="an-trend {{ $t >= 0 ? 'up' : 'down' }}" id="badge-pft-trend">
                {{ $t >= 0 ? '↑' : '↓' }} {{ abs($t) }}% vs mois dernier
            </span>
        @else
            <span class="an-trend flat" id="badge-pft-trend">Nouveau</span>
        @endif
    </div>

</div>

{{-- ── KPI MONTHLY (4 cards) ──────────────────────────────────────────── --}}
<div class="an-kpi-row an-kpi-row-4 an-fade ad3">

    <div class="an-card rev">
        <div class="an-card-header">
            <div>
                <p class="an-label">Revenus — {{ now()->format('M Y') }}</p>
                <p class="an-big-num sm" id="kpi-m-rev">{{ number_format($mRevenue, 0, '.', ' ') }}</p>
                <span class="an-unit">TND</span>
            </div>
            <div class="an-icon" style="font-size:18px;">📅</div>
        </div>
        @if($pRevenue > 0)
            <div style="margin-top:8px;">
                <div style="font-size:11px;color:#6B6B7A;margin-bottom:4px;">vs {{ now()->subMonth()->format('M') }} : {{ number_format($pRevenue, 0, '.', ' ') }} TND</div>
                <div class="an-gauge-track">
                    <div class="an-gauge-fill" style="width:{{ $pRevenue > 0 ? min(100,round($mRevenue/$pRevenue*100)) : 0 }}%; background:linear-gradient(90deg,#22C55E,#86EFAC);"></div>
                </div>
            </div>
        @endif
    </div>

    <div class="an-card exp">
        <div class="an-card-header">
            <div>
                <p class="an-label">Dépenses — {{ now()->format('M Y') }}</p>
                <p class="an-big-num sm" id="kpi-m-exp">{{ number_format($mExpenses, 0, '.', ' ') }}</p>
                <span class="an-unit">TND</span>
            </div>
            <div class="an-icon" style="font-size:18px;">🧾</div>
        </div>
        @if($pExpenses > 0)
            <div style="margin-top:8px;">
                <div style="font-size:11px;color:#6B6B7A;margin-bottom:4px;">vs {{ now()->subMonth()->format('M') }} : {{ number_format($pExpenses, 0, '.', ' ') }} TND</div>
                <div class="an-gauge-track">
                    <div class="an-gauge-fill" style="width:{{ $pExpenses > 0 ? min(100,round($mExpenses/$pExpenses*100)) : 0 }}%; background:linear-gradient(90deg,#EF4444,#FCA5A5);"></div>
                </div>
            </div>
        @endif
    </div>

    <div class="an-card pft">
        <div class="an-card-header">
            <div>
                <p class="an-label">Marge Bénéficiaire</p>
                <p class="an-big-num sm" id="kpi-margin">{{ $mMargin }}</p>
                <span class="an-unit">% ce mois</span>
            </div>
            <div class="an-icon" style="font-size:18px;">🎯</div>
        </div>
        <div style="margin-top:8px;">
            <div style="font-size:11px;color:#6B6B7A;margin-bottom:4px;">Marge globale : {{ $marginPct }}%</div>
            <div class="an-gauge-track">
                <div class="an-gauge-fill" style="width:{{ min(100, max(0, $mMargin)) }}%; background:linear-gradient(90deg,#6366F1,#A5B4FC);"></div>
            </div>
        </div>
    </div>

    <div class="an-card org">
        <div class="an-card-header">
            <div>
                <p class="an-label">Réservations</p>
                <p class="an-big-num sm" id="kpi-res">{{ $totalRes }}</p>
                <span class="an-unit">total actives</span>
            </div>
            <div class="an-icon" style="font-size:18px;">📅</div>
        </div>
        <div style="margin-top:8px; font-size:12px; color:#6B6B7A;">
            Ce mois : <strong style="color:#C8602A;">{{ $mRes }}</strong> nouvelles &nbsp;·&nbsp;
            Taux dépenses : <strong style="color:#C8602A;">{{ $expenseRatio }}%</strong>
        </div>
    </div>

</div>

{{-- ── CHARTS ROW ──────────────────────────────────────────────────────── --}}
<div class="an-charts-row an-fade ad4">

    {{-- Line Chart --}}
    <div class="an-chart-card">
        <div class="an-chart-header">
            <div class="an-chart-title">
                <span>📊</span> Évolution sur 12 Mois
            </div>
            <div style="display:flex;gap:16px;font-size:12px;font-weight:600;">
                <span style="color:#16A34A;">● Revenus</span>
                <span style="color:#DC2626;">● Dépenses</span>
                <span style="color:#4F46E5;">● Bénéfice</span>
            </div>
        </div>
        <div class="an-chart-body" style="position:relative;height:280px;">
            <canvas id="lineChart"></canvas>
        </div>
    </div>

    {{-- Doughnut --}}
    <div class="an-chart-card">
        <div class="an-chart-header">
            <div class="an-chart-title"><span>🍩</span> Dépenses / Catégorie</div>
        </div>
        <div class="an-chart-body" style="display:flex;flex-direction:column;align-items:center;gap:1rem;">
            <div style="position:relative;width:200px;height:200px;">
                <canvas id="donutChart"></canvas>
                <div id="donut-center" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;">
                    <div style="font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;color:#2E2E3A;" id="donut-total">{{ number_format($totalExpenses, 0, '.', ' ') }}</div>
                    <div style="font-size:10px;color:#6B6B7A;font-weight:600;">TND Total</div>
                </div>
            </div>
            <div id="donut-legend" style="width:100%;display:flex;flex-direction:column;gap:4px;max-height:130px;overflow-y:auto;padding-right:4px;">
                @foreach(array_combine(array_keys($catLabels ?? []), $catValues ?? []) as $i => $val)
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- ── BOTTOM ROW ──────────────────────────────────────────────────────── --}}
<div class="an-bottom-row an-fade ad5">

    {{-- Recent Payments --}}
    <div class="an-chart-card">
        <div class="an-chart-header">
            <div class="an-chart-title"><span>💳</span> Derniers Paiements</div>
            <a href="{{ route('payments.index') }}" style="font-size:12px;font-weight:600;color:#C8602A;text-decoration:none;">Voir tout →</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="an-table">
                <thead>
                    <tr>
                        <th>Guest</th>
                        <th>Montant</th>
                        <th>Mode</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="payments-table-body">
                    @forelse($recentPayments as $p)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:32px;height:32px;border-radius:10px;background:#FEF3E2;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;color:#C8602A;font-size:13px;flex-shrink:0;">
                                    {{ strtoupper(substr($p['guest'], 0, 1)) }}
                                </div>
                                <span style="font-weight:600;">{{ $p['guest'] }}</span>
                            </div>
                        </td>
                        <td style="font-weight:700;color:#16A34A;">{{ number_format($p['amount'], 3, '.', ' ') }} <span style="font-size:11px;color:#6B6B7A;">TND</span></td>
                        <td>
                            <span class="an-method-badge method-{{ $p['method'] }}">
                                {{ $p['method'] === 'cash' ? 'Espèces' : ($p['method'] === 'card' ? 'Carte' : ($p['method'] === 'transfer' ? 'Virement' : 'Autre')) }}
                            </span>
                        </td>
                        <td style="color:#6B6B7A;font-size:12px;">{{ $p['date'] }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:2rem;color:#A0A0B0;">Aucun paiement enregistré.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Summary Sidebar --}}
    <div class="an-chart-card">
        <div class="an-chart-header">
            <div class="an-chart-title"><span>📋</span> Résumé</div>
        </div>
        <div>
            <div class="an-summary-item">
                <span class="an-summary-label"><span>💰</span> Revenus total</span>
                <span class="an-summary-value" style="color:#16A34A;">{{ number_format($totalRevenue, 0, '.', ' ') }} TND</span>
            </div>
            <div class="an-summary-item">
                <span class="an-summary-label"><span>💸</span> Dépenses total</span>
                <span class="an-summary-value" style="color:#DC2626;">{{ number_format($totalExpenses, 0, '.', ' ') }} TND</span>
            </div>
            <div class="an-summary-item">
                <span class="an-summary-label"><span>📈</span> Bénéfice net</span>
                <span class="an-summary-value" style="color:{{ $netProfit >= 0 ? '#4F46E5' : '#DC2626' }};">{{ number_format($netProfit, 0, '.', ' ') }} TND</span>
            </div>
            <div class="an-summary-item">
                <span class="an-summary-label"><span>🎯</span> Marge bénéf.</span>
                <span class="an-summary-value" style="color:#C8602A;">{{ $marginPct }}%</span>
            </div>
            <div class="an-summary-item">
                <span class="an-summary-label"><span>📊</span> Taux dépenses</span>
                <span class="an-summary-value">{{ $expenseRatio }}%</span>
            </div>
            <div class="an-summary-item">
                <span class="an-summary-label"><span>📅</span> Réservations</span>
                <span class="an-summary-value" style="color:#1B6B6B;">{{ $totalRes }}</span>
            </div>

            {{-- Expense ratio bar --}}
            <div style="padding:1rem 1.25rem;border-top:1px solid #F5EFE6;">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                    <span style="font-size:12px;font-weight:600;color:#22C55E;">Revenus</span>
                    <span style="font-size:12px;font-weight:600;color:#EF4444;">Dépenses</span>
                </div>
                <div style="height:10px;background:#EDE8E0;border-radius:99px;overflow:hidden;">
                    @php $revPct = $totalRevenue + $totalExpenses > 0 ? round($totalRevenue / ($totalRevenue + $totalExpenses) * 100) : 50; @endphp
                    <div style="height:100%;width:{{ $revPct }}%;background:linear-gradient(90deg,#22C55E,#86EFAC);border-radius:99px;float:left;"></div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:5px;font-size:11px;color:#6B6B7A;">
                    <span>{{ $revPct }}%</span>
                    <span>{{ 100 - $revPct }}%</span>
                </div>
            </div>
        </div>
    </div>

</div>
</div>{{-- /an-wrap --}}

{{-- ── Chart.js ──────────────────────────────────────────────────────────── --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {

    // ── Data from PHP ─────────────────────────────────────────────────────
    const chartLabels  = @json($chartLabels);
    const chartRev     = @json($chartRev);
    const chartExp     = @json($chartExp);
    const chartProfit  = @json($chartProfit);
    const catLabels    = @json($catLabels);
    const catValues    = @json($catValues);

    // ── Chart defaults ────────────────────────────────────────────────────
    Chart.defaults.font.family = "'DM Sans', sans-serif";
    Chart.defaults.color       = '#6B6B7A';

    // ── Palette for donut ─────────────────────────────────────────────────
    const PALETTE = [
        '#22C55E','#EF4444','#6366F1','#F97316','#1B6B6B',
        '#EC4899','#14B8A6','#F59E0B','#8B5CF6','#3B82F6','#A0A0B0'
    ];

    // ── Line / Area Chart ─────────────────────────────────────────────────
    const lineCtx = document.getElementById('lineChart').getContext('2d');

    const makeGrad = (ctx, top, bottom) => {
        const g = ctx.createLinearGradient(0, 0, 0, 300);
        g.addColorStop(0, top); g.addColorStop(1, bottom); return g;
    };

    const lineChart = new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Revenus',
                    data: chartRev,
                    borderColor: '#22C55E',
                    backgroundColor: makeGrad(lineCtx, 'rgba(34,197,94,.18)', 'rgba(34,197,94,.01)'),
                    fill: true,
                    tension: .42,
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#22C55E',
                    pointHoverRadius: 6,
                },
                {
                    label: 'Dépenses',
                    data: chartExp,
                    borderColor: '#EF4444',
                    backgroundColor: makeGrad(lineCtx, 'rgba(239,68,68,.14)', 'rgba(239,68,68,.01)'),
                    fill: true,
                    tension: .42,
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#EF4444',
                    pointHoverRadius: 6,
                },
                {
                    label: 'Bénéfice',
                    data: chartProfit,
                    borderColor: '#6366F1',
                    backgroundColor: makeGrad(lineCtx, 'rgba(99,102,241,.12)', 'rgba(99,102,241,.01)'),
                    fill: true,
                    tension: .42,
                    borderWidth: 2.5,
                    borderDash: [6, 3],
                    pointRadius: 4,
                    pointBackgroundColor: '#6366F1',
                    pointHoverRadius: 6,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1C1C24',
                    titleColor: '#FEFCF9',
                    bodyColor: '#A0A0B0',
                    borderColor: '#2E2E3A',
                    borderWidth: 1,
                    padding: 12,
                    callbacks: {
                        label: ctx => ` ${ctx.dataset.label}: ${fmtTND(ctx.parsed.y)} TND`
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(221,214,202,.5)', drawTicks: false },
                    border: { dash: [4,4] },
                    ticks: { font: { size: 11 }, maxRotation: 35 }
                },
                y: {
                    grid: { color: 'rgba(221,214,202,.5)', drawTicks: false },
                    border: { dash: [4,4] },
                    ticks: {
                        font: { size: 11 },
                        callback: v => fmtK(v) + ' TND'
                    }
                }
            }
        }
    });

    // ── Doughnut chart ────────────────────────────────────────────────────
    const donutCtx = document.getElementById('donutChart').getContext('2d');

    const donutChart = new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: catLabels,
            datasets: [{
                data: catValues,
                backgroundColor: PALETTE.slice(0, catLabels.length),
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1C1C24',
                    titleColor: '#FEFCF9',
                    bodyColor: '#A0A0B0',
                    callbacks: {
                        label: ctx => ` ${fmtTND(ctx.parsed)} TND (${pct(ctx.parsed, catValues)}%)`
                    }
                }
            }
        }
    });

    // Build custom legend
    buildDonutLegend(catLabels, catValues, PALETTE);

    // ── Helpers ───────────────────────────────────────────────────────────
    function fmtTND(v) {
        return Number(v).toLocaleString('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 3 });
    }
    function fmtK(v) {
        if (Math.abs(v) >= 1000) return (v / 1000).toFixed(1) + 'K';
        return v.toFixed(0);
    }
    function pct(val, arr) {
        const sum = arr.reduce((a, b) => a + b, 0);
        return sum > 0 ? (val / sum * 100).toFixed(1) : 0;
    }

    function buildDonutLegend(labels, values, palette) {
        const container = document.getElementById('donut-legend');
        container.innerHTML = '';
        labels.forEach((lbl, i) => {
            const row = document.createElement('div');
            row.style.cssText = 'display:flex;align-items:center;justify-content:space-between;gap:8px;padding:3px 0;';
            row.innerHTML = `
                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#2E2E3A;flex:1;min-width:0;">
                    <span style="width:10px;height:10px;border-radius:3px;background:${palette[i] ?? '#ccc'};flex-shrink:0;"></span>
                    <span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${lbl}</span>
                </div>
                <span style="font-size:12px;font-weight:700;color:#2E2E3A;white-space:nowrap;">${fmtTND(values[i])} TND</span>
            `;
            container.appendChild(row);
        });
    }

    // ── Auto-refresh every 30s ────────────────────────────────────────────
    setInterval(refreshData, 30000);

    function refreshData() {
        fetch('{{ route('analytics.data') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(d => {
                // KPI numbers
                flashUpdate('kpi-total-rev',  fmt(d.totalRevenue));
                flashUpdate('kpi-total-exp',  fmt(d.totalExpenses));
                flashUpdate('kpi-total-pft',  (d.netProfit >= 0 ? '+' : '') + fmt(d.netProfit));
                flashUpdate('kpi-m-rev',      fmt(d.mRevenue));
                flashUpdate('kpi-m-exp',      fmt(d.mExpenses));
                flashUpdate('kpi-margin',     d.mMargin ?? d.marginPct);
                flashUpdate('kpi-res',        d.totalRes);
                flashUpdate('donut-total',    fmtTND(d.totalExpenses));

                // Line chart
                lineChart.data.datasets[0].data = d.chartRev;
                lineChart.data.datasets[1].data = d.chartExp;
                lineChart.data.datasets[2].data = d.chartProfit;
                lineChart.update('active');

                // Donut chart
                donutChart.data.datasets[0].data   = d.catValues;
                donutChart.data.labels              = d.catLabels;
                donutChart.update('active');
                buildDonutLegend(d.catLabels, d.catValues, PALETTE);

                // Timestamp
                const now = new Date();
                document.getElementById('an-last-update').textContent =
                    'Actualisé à ' + now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
            })
            .catch(() => {});
    }

    function fmt(v) {
        return Number(v).toLocaleString('fr-FR', { maximumFractionDigits: 0 });
    }

    function flashUpdate(id, value) {
        const el = document.getElementById(id);
        if (!el) return;
        if (el.textContent.trim() === String(value)) return;
        el.classList.add('refreshing');
        setTimeout(() => { el.textContent = value; el.classList.remove('refreshing'); }, 250);
    }

})();
</script>

@endsection

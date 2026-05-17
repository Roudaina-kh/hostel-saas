{{-- ════════════════════════════════════════════════════════════════════════
     ONGLET 3 — FINANCE
     Dashboard financier premium — CSS complet + upgrade glassmorphism,
     micro-interactions, pulsing indicators, motion design.
═════════════════════════════════════════════════════════════════════════ --}}

@php
    if (!function_exists('trendPct')) {
        function trendPct($current, $prev): ?float {
            if ($prev == 0) return null;
            return round(($current - $prev) / $prev * 100, 1);
        }
    }
    $revTrend  = trendPct($finance['mRevenue'],  $finance['pRevenue']);
    $expTrend  = trendPct($finance['mExpenses'], $finance['pExpenses']);
    $pftTrend  = trendPct($finance['mProfit'],   $finance['pProfit']);
    $mMargin   = $finance['mRevenue'] > 0
        ? round($finance['mProfit'] / $finance['mRevenue'] * 100, 1)
        : 0;

    $totalRevenue   = $finance['totalRevenue'];
    $totalExpenses  = $finance['totalExpenses'];
    $netProfit      = $finance['netProfit'];
    $marginPct      = $finance['marginPct'];
    $expenseRatio   = $finance['expenseRatio'];
    $mRevenue       = $finance['mRevenue'];
    $mExpenses      = $finance['mExpenses'];
    $mProfit        = $finance['mProfit'];
    $pRevenue       = $finance['pRevenue'];
    $pExpenses      = $finance['pExpenses'];
    $pProfit        = $finance['pProfit'];
    $totalRes       = $finance['totalRes'];
    $mRes           = $finance['mRes'];
    $recentPayments = $finance['recentPayments'];
    $catLabels      = $finance['catLabels'];
    $catValues      = $finance['catValues'];
@endphp

<style>
/* ═══════════════════════════════════════════════════════════════════════
   FINANCE TAB — STYLES COMPLETS (restaurés + upgrade premium)
═══════════════════════════════════════════════════════════════════════ */

.an-wrap { font-family: 'DM Sans', sans-serif; padding-bottom: 1rem; }

/* ── KPI CARDS ──────────────────────────────────────────────────────── */
.an-kpi-row { display: grid; gap: 1.25rem; position: relative; z-index: 10; margin-bottom: 1.25rem; }
.an-kpi-row-3 { grid-template-columns: repeat(3, 1fr); }
.an-kpi-row-4 { grid-template-columns: repeat(4, 1fr); margin-top: 0; }

.an-card {
    background: rgba(254,252,249,.85);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border-radius: 20px;
    padding: 1.6rem;
    border: 1px solid rgba(221,214,202,.7);
    box-shadow: 0 8px 30px rgba(28,28,36,.07);
    position: relative;
    overflow: hidden;
    transition: transform .35s cubic-bezier(.34,1.56,.64,1),
                box-shadow .35s,
                border-color .35s;
}
.an-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 22px 50px rgba(28,28,36,.13);
    border-color: rgba(200,96,42,.25);
}
.an-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 20px 20px 0 0;
    transition: height .3s ease;
}
.an-card:hover::before { height: 5px; }
.an-card.rev::before { background: linear-gradient(90deg, #22C55E, #86EFAC); }
.an-card.exp::before { background: linear-gradient(90deg, #EF4444, #FCA5A5); }
.an-card.pft::before { background: linear-gradient(90deg, #6366F1, #A5B4FC); }
.an-card.org::before { background: linear-gradient(90deg, #C8602A, #F97316); }
.an-card.teal::before{ background: linear-gradient(90deg, #1B6B6B, #22D3EE); }

/* Shimmer effect on hover */
.an-card::after {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.45), transparent);
    transition: left .8s cubic-bezier(.4,0,.2,1);
    pointer-events: none;
}
.an-card:hover::after { left: 100%; }

.an-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}
.an-icon {
    width: 46px; height: 46px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    transition: transform .4s cubic-bezier(.34,1.56,.64,1);
}
.an-card:hover .an-icon { transform: scale(1.12) rotate(-5deg); }
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
    transition: transform .3s;
}
.an-big-num.sm { font-size: 1.8rem; }
.an-card:hover .an-big-num { transform: translateX(2px); }
.an-unit { font-size: 12px; font-weight: 600; color: #6B6B7A; }
.rev .an-big-num  { color: #16A34A; }
.exp .an-big-num  { color: #DC2626; }
.pft .an-big-num  { color: #4F46E5; }
.org .an-big-num  { color: #C8602A; }
.teal .an-big-num { color: #1B6B6B; }

/* Trend badge — avec pulsing */
.an-trend {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 12px;
    border-radius: 99px;
    font-size: 12px;
    font-weight: 700;
    margin-top: 10px;
    position: relative;
    overflow: hidden;
}
.an-trend.up   { background: #DCFCE7; color: #16A34A; }
.an-trend.down { background: #FEE2E2; color: #DC2626; }
.an-trend.flat { background: #F1F5F9; color: #6B6B7A; }
.an-trend::after {
    content: '';
    position: absolute;
    top: 0; left: -50%;
    width: 50%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.5), transparent);
    animation: trendShine 3s ease-in-out infinite;
}
@keyframes trendShine {
    0%, 100% { left: -50%; }
    50%      { left: 100%; }
}

/* ── CHARTS ROW ─────────────────────────────────────────────────────── */
.an-charts-row {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}
.an-chart-card {
    background: rgba(254,252,249,.92);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 20px;
    border: 1px solid rgba(221,214,202,.7);
    box-shadow: 0 8px 30px rgba(28,28,36,.07);
    overflow: hidden;
    transition: box-shadow .3s;
}
.an-chart-card:hover { box-shadow: 0 14px 42px rgba(28,28,36,.1); }
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
.an-chart-body { padding: 1.25rem 1.5rem; }

/* ── BOTTOM ROW ─────────────────────────────────────────────────────── */
.an-bottom-row {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.25rem;
}

/* ── TABLE ─────────────────────────────────────────────────────────── */
.an-table { width: 100%; border-collapse: collapse; }
.an-table th {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #6B6B7A;
    padding: 10px 14px;
    border-bottom: 1px solid #EDE8E0;
    text-align: left;
    white-space: nowrap;
    background: #FAF6EF;
}
.an-table td {
    padding: 11px 14px;
    font-size: 13.5px;
    color: #2E2E3A;
    border-bottom: 1px solid #F5EFE6;
    vertical-align: middle;
    transition: background .2s;
}
.an-table tr:last-child td { border-bottom: none; }
.an-table tr { transition: transform .2s; }
.an-table tr:hover td { background: #FAF6EF; }
.an-table tr:hover { transform: translateX(2px); }

.an-method-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 600;
}
.method-cash     { background: #DCFCE7; color: #16A34A; }
.method-card     { background: #EDE9FE; color: #4F46E5; }
.method-transfer { background: #FEF3E2; color: #C8602A; }
.method-other    { background: #F1F5F9; color: #6B6B7A; }

/* ── SUMMARY SIDE ──────────────────────────────────────────────────── */
.an-summary-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .95rem 1.25rem;
    border-bottom: 1px solid #F5EFE6;
    transition: background .2s, padding-left .2s;
}
.an-summary-item:last-child { border-bottom: none; }
.an-summary-item:hover {
    background: linear-gradient(90deg, #FAF6EF, transparent);
    padding-left: 1.5rem;
}
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
    font-variant-numeric: tabular-nums;
}

/* ── GAUGE ──────────────────────────────────────────────────────────── */
.an-gauge-track {
    height: 8px;
    background: #EDE8E0;
    border-radius: 99px;
    overflow: hidden;
    margin-top: 6px;
    position: relative;
}
.an-gauge-fill {
    height: 100%;
    border-radius: 99px;
    transition: width 1.2s cubic-bezier(.34,1.56,.64,1);
    position: relative;
    overflow: hidden;
}
.an-gauge-fill::after {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.4), transparent);
    animation: gaugeShimmer 2.5s ease-in-out infinite;
}
@keyframes gaugeShimmer {
    0%   { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* ── FADE ANIMATIONS ────────────────────────────────────────────────── */
.an-fade { opacity: 0; transform: translateY(18px); animation: anFade .55s cubic-bezier(.4,0,.2,1) forwards; }
@keyframes anFade { to { opacity: 1; transform: translateY(0); } }
.ad1 { animation-delay: .05s; }
.ad2 { animation-delay: .12s; }
.ad3 { animation-delay: .20s; }
.ad4 { animation-delay: .28s; }
.ad5 { animation-delay: .36s; }
.ad6 { animation-delay: .44s; }

/* ── NUMBER FLASH ───────────────────────────────────────────────────── */
@keyframes numFlash {
    0%   { transform: scale(1);   color: inherit; }
    50%  { transform: scale(1.08); color: #C8602A; }
    100% { transform: scale(1);   color: inherit; }
}
.refreshing { animation: numFlash .6s ease; }

/* ── PULSING DOTS (live indicators) ─────────────────────────────────── */
.an-pulse-dot {
    display: inline-block;
    width: 6px; height: 6px;
    background: #22C55E;
    border-radius: 50%;
    margin-right: 6px;
    animation: anDotPulse 1.6s ease-in-out infinite;
}
@keyframes anDotPulse {
    0%, 100% { opacity: 1; transform: scale(1); box-shadow: 0 0 0 0 rgba(34,197,94,.5); }
    50%       { opacity: .6; transform: scale(.85); box-shadow: 0 0 0 6px rgba(34,197,94,0); }
}

/* ── COUNTER ANIM AU LOAD (handled by JS) ───────────────────────────── */
.an-counter { display: inline-block; }

@media (max-width: 900px) {
    .an-kpi-row-3, .an-kpi-row-4 { grid-template-columns: 1fr 1fr; }
    .an-charts-row, .an-bottom-row { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .an-kpi-row-3, .an-kpi-row-4 { grid-template-columns: 1fr; }
}
</style>

<div class="an-wrap">

{{-- ── KPI MAIN (3 cards, all-time) ──────────────────────────────────── --}}
<div class="an-kpi-row an-kpi-row-3 an-fade ad2">

    <div class="an-card rev">
        <div class="an-card-header">
            <div>
                <p class="an-label"><span class="an-pulse-dot"></span>Revenus Total</p>
                <p class="an-big-num an-counter" id="kpi-total-rev" data-val="{{ $totalRevenue }}">
                    {{ number_format($totalRevenue, 0, '.', ' ') }}
                </p>
                <span class="an-unit">TND (hors annulées)</span>
            </div>
            <div class="an-icon">💰</div>
        </div>
        @if($revTrend !== null)
            <span class="an-trend {{ $revTrend >= 0 ? 'up' : 'down' }}" id="badge-rev-trend">
                {{ $revTrend >= 0 ? '↑' : '↓' }} {{ abs($revTrend) }}% vs mois dernier
            </span>
        @else
            <span class="an-trend flat" id="badge-rev-trend">Nouveau</span>
        @endif
    </div>

    <div class="an-card exp">
        <div class="an-card-header">
            <div>
                <p class="an-label"><span class="an-pulse-dot" style="background:#EF4444;animation-name:anDotPulseRed"></span>Dépenses Totales</p>
                <p class="an-big-num an-counter" id="kpi-total-exp" data-val="{{ $totalExpenses }}">
                    {{ number_format($totalExpenses, 0, '.', ' ') }}
                </p>
                <span class="an-unit">TND</span>
            </div>
            <div class="an-icon">💸</div>
        </div>
        @if($expTrend !== null)
            <span class="an-trend {{ $expTrend <= 0 ? 'up' : 'down' }}" id="badge-exp-trend">
                {{ $expTrend >= 0 ? '↑' : '↓' }} {{ abs($expTrend) }}% vs mois dernier
            </span>
        @else
            <span class="an-trend flat" id="badge-exp-trend">Nouveau</span>
        @endif
    </div>

    <div class="an-card {{ $netProfit >= 0 ? 'pft' : 'exp' }}">
        <div class="an-card-header">
            <div>
                <p class="an-label"><span class="an-pulse-dot" style="background:#6366F1;"></span>Bénéfice Net</p>
                <p class="an-big-num an-counter" id="kpi-total-pft" data-val="{{ $netProfit }}">
                    {{ $netProfit >= 0 ? '+' : '' }}{{ number_format($netProfit, 0, '.', ' ') }}
                </p>
                <span class="an-unit">TND</span>
            </div>
            <div class="an-icon">📈</div>
        </div>
        @if($pftTrend !== null)
            <span class="an-trend {{ $pftTrend >= 0 ? 'up' : 'down' }}" id="badge-pft-trend">
                {{ $pftTrend >= 0 ? '↑' : '↓' }} {{ abs($pftTrend) }}% vs mois dernier
            </span>
        @else
            <span class="an-trend flat" id="badge-pft-trend">Nouveau</span>
        @endif
    </div>

</div>

<style>
@keyframes anDotPulseRed {
    0%, 100% { opacity: 1; transform: scale(1); box-shadow: 0 0 0 0 rgba(239,68,68,.5); }
    50%       { opacity: .6; transform: scale(.85); box-shadow: 0 0 0 6px rgba(239,68,68,0); }
}
</style>

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

    <div class="an-chart-card">
        <div class="an-chart-header">
            <div class="an-chart-title"><span>📊</span> Évolution sur 12 Mois</div>
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

    <div class="an-chart-card">
        <div class="an-chart-header">
            <div class="an-chart-title"><span>🍩</span> Dépenses / Catégorie</div>
        </div>
        <div class="an-chart-body" style="display:flex;flex-direction:column;align-items:center;gap:1rem;">
            <div style="position:relative;width:200px;height:200px;">
                <canvas id="donutChart"></canvas>
                <div id="donut-center" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;pointer-events:none;">
                    <div style="font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;color:#2E2E3A;" id="donut-total">{{ number_format($totalExpenses, 0, '.', ' ') }}</div>
                    <div style="font-size:10px;color:#6B6B7A;font-weight:600;">TND Total</div>
                </div>
            </div>
            <div id="donut-legend" style="width:100%;display:flex;flex-direction:column;gap:4px;max-height:130px;overflow-y:auto;padding-right:4px;"></div>
        </div>
    </div>

</div>

{{-- ── BOTTOM ROW ──────────────────────────────────────────────────────── --}}
<div class="an-bottom-row an-fade ad5">

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

            <div style="padding:1rem 1.25rem;border-top:1px solid #F5EFE6;">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                    <span style="font-size:12px;font-weight:600;color:#22C55E;">Revenus</span>
                    <span style="font-size:12px;font-weight:600;color:#EF4444;">Dépenses</span>
                </div>
                <div style="height:10px;background:#EDE8E0;border-radius:99px;overflow:hidden;">
                    @php $revPct = $totalRevenue + $totalExpenses > 0 ? round($totalRevenue / ($totalRevenue + $totalExpenses) * 100) : 50; @endphp
                    <div class="an-gauge-fill" style="height:100%;width:{{ $revPct }}%;background:linear-gradient(90deg,#22C55E,#86EFAC);border-radius:99px;float:left;"></div>
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

{{-- ── Chart.js (chargé une seule fois) ───────────────────────────────── --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
window.AnalyticsFinance = (function () {

    const chartLabels  = @json($finance['chartLabels']);
    const chartRev     = @json($finance['chartRev']);
    const chartExp     = @json($finance['chartExp']);
    const chartProfit  = @json($finance['chartProfit']);
    const catLabels    = @json($catLabels);
    const catValues    = @json($catValues);

    let lineChart = null;
    let donutChart = null;
    let initialized = false;

    const PALETTE = [
        '#22C55E','#EF4444','#6366F1','#F97316','#1B6B6B',
        '#EC4899','#14B8A6','#F59E0B','#8B5CF6','#3B82F6','#A0A0B0'
    ];

    function fmtTND(v) { return Number(v).toLocaleString('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 3 }); }
    function fmtK(v)   { return Math.abs(v) >= 1000 ? (v / 1000).toFixed(1) + 'K' : v.toFixed(0); }
    function pct(val, arr) {
        const sum = arr.reduce((a, b) => a + b, 0);
        return sum > 0 ? (val / sum * 100).toFixed(1) : 0;
    }

    function buildDonutLegend(labels, values, palette) {
        const container = document.getElementById('donut-legend');
        if (!container) return;
        container.innerHTML = '';
        labels.forEach((lbl, i) => {
            const row = document.createElement('div');
            row.style.cssText = 'display:flex;align-items:center;justify-content:space-between;gap:8px;padding:3px 0;transition:transform .2s;cursor:default;';
            row.onmouseenter = () => row.style.transform = 'translateX(2px)';
            row.onmouseleave = () => row.style.transform = 'translateX(0)';
            row.innerHTML = `
                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#2E2E3A;flex:1;min-width:0;">
                    <span style="width:10px;height:10px;border-radius:3px;background:${palette[i] ?? '#ccc'};flex-shrink:0;box-shadow:0 0 0 0 ${palette[i]}; animation:legendDotPulse 2s ease-in-out infinite ${i*0.15}s;"></span>
                    <span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${lbl}</span>
                </div>
                <span style="font-size:12px;font-weight:700;color:#2E2E3A;white-space:nowrap;">${fmtTND(values[i])} TND</span>
            `;
            container.appendChild(row);
        });
    }

    /* Counter animation au load — KPI top */
    function animateCounter(id, target) {
        const el = document.getElementById(id);
        if (!el) return;
        const startTime = performance.now();
        const duration = 1100;
        const isNeg = target < 0;
        const abs = Math.abs(target);
        const prefix = (id === 'kpi-total-pft' && target >= 0) ? '+' : (isNeg ? '-' : '');
        function tick(now) {
            const t = Math.min(1, (now - startTime) / duration);
            const eased = 1 - Math.pow(1 - t, 3); // easeOutCubic
            const val = Math.floor(abs * eased);
            el.textContent = prefix + val.toLocaleString('fr-FR');
            if (t < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    function init() {
        if (initialized) return;
        const lineEl  = document.getElementById('lineChart');
        const donutEl = document.getElementById('donutChart');
        if (!lineEl || !donutEl) return;
        if (typeof Chart === 'undefined') return;

        Chart.defaults.font.family = "'DM Sans', sans-serif";
        Chart.defaults.color       = '#6B6B7A';

        const lineCtx = lineEl.getContext('2d');
        const makeGrad = (ctx, top, bottom) => {
            const g = ctx.createLinearGradient(0, 0, 0, 300);
            g.addColorStop(0, top); g.addColorStop(1, bottom); return g;
        };

        lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [
                    { label: 'Revenus', data: chartRev,
                      borderColor: '#22C55E',
                      backgroundColor: makeGrad(lineCtx, 'rgba(34,197,94,.18)', 'rgba(34,197,94,.01)'),
                      fill: true, tension: .42, borderWidth: 2.5,
                      pointRadius: 4, pointBackgroundColor: '#22C55E', pointHoverRadius: 7 },
                    { label: 'Dépenses', data: chartExp,
                      borderColor: '#EF4444',
                      backgroundColor: makeGrad(lineCtx, 'rgba(239,68,68,.14)', 'rgba(239,68,68,.01)'),
                      fill: true, tension: .42, borderWidth: 2.5,
                      pointRadius: 4, pointBackgroundColor: '#EF4444', pointHoverRadius: 7 },
                    { label: 'Bénéfice', data: chartProfit,
                      borderColor: '#6366F1',
                      backgroundColor: makeGrad(lineCtx, 'rgba(99,102,241,.12)', 'rgba(99,102,241,.01)'),
                      fill: true, tension: .42, borderWidth: 2.5, borderDash: [6, 3],
                      pointRadius: 4, pointBackgroundColor: '#6366F1', pointHoverRadius: 7 },
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                animation: { duration: 1200, easing: 'easeOutCubic' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(28,28,36,.95)', titleColor: '#FEFCF9',
                        bodyColor: '#A0A0B0', borderColor: '#2E2E3A',
                        borderWidth: 1, padding: 12, cornerRadius: 10,
                        callbacks: { label: ctx => ` ${ctx.dataset.label}: ${fmtTND(ctx.parsed.y)} TND` }
                    }
                },
                scales: {
                    x: { grid: { color: 'rgba(221,214,202,.5)', drawTicks: false },
                         border: { dash: [4,4] },
                         ticks: { font: { size: 11 }, maxRotation: 35 } },
                    y: { grid: { color: 'rgba(221,214,202,.5)', drawTicks: false },
                         border: { dash: [4,4] },
                         ticks: { font: { size: 11 }, callback: v => fmtK(v) + ' TND' } }
                }
            }
        });

        const donutCtx = donutEl.getContext('2d');
        donutChart = new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catValues,
                    backgroundColor: PALETTE.slice(0, catLabels.length),
                    borderWidth: 0, hoverOffset: 12,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '68%',
                animation: { animateRotate: true, duration: 1400, easing: 'easeOutCubic' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(28,28,36,.95)', titleColor: '#FEFCF9', bodyColor: '#A0A0B0',
                        cornerRadius: 10, padding: 12,
                        callbacks: { label: ctx => ` ${fmtTND(ctx.parsed)} TND (${pct(ctx.parsed, catValues)}%)` }
                    }
                }
            }
        });

        buildDonutLegend(catLabels, catValues, PALETTE);

        // Animate KPI counters au load
        animateCounter('kpi-total-rev', {{ $totalRevenue }});
        animateCounter('kpi-total-exp', {{ $totalExpenses }});
        animateCounter('kpi-total-pft', {{ $netProfit }});

        // Auto-refresh 30s
        setInterval(refreshData, 30000);

        initialized = true;
    }

    function refreshData() {
        const tab = document.getElementById('panel-finance');
        if (!tab || !tab.classList.contains('active')) return;
        if (!lineChart || !donutChart) return;

        fetch('{{ route('analytics.data') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(d => {
                flashUpdate('kpi-total-rev',  fmt(d.totalRevenue));
                flashUpdate('kpi-total-exp',  fmt(d.totalExpenses));
                flashUpdate('kpi-total-pft',  (d.netProfit >= 0 ? '+' : '') + fmt(d.netProfit));
                flashUpdate('kpi-m-rev',      fmt(d.mRevenue));
                flashUpdate('kpi-m-exp',      fmt(d.mExpenses));
                flashUpdate('kpi-margin',     d.mMargin ?? d.marginPct);
                flashUpdate('kpi-res',        d.totalRes);
                flashUpdate('donut-total',    fmtTND(d.totalExpenses));

                lineChart.data.datasets[0].data = d.chartRev;
                lineChart.data.datasets[1].data = d.chartExp;
                lineChart.data.datasets[2].data = d.chartProfit;
                lineChart.update('active');

                donutChart.data.datasets[0].data = d.catValues;
                donutChart.data.labels            = d.catLabels;
                donutChart.update('active');
                buildDonutLegend(d.catLabels, d.catValues, PALETTE);

                const lastEl = document.getElementById('an-last-update');
                if (lastEl) {
                    const now = new Date();
                    lastEl.textContent = 'Actualisé à ' + now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
                }
            })
            .catch(() => {});
    }

    function fmt(v) { return Number(v).toLocaleString('fr-FR', { maximumFractionDigits: 0 }); }
    function flashUpdate(id, value) {
        const el = document.getElementById(id);
        if (!el) return;
        if (el.textContent.trim() === String(value)) return;
        el.classList.add('refreshing');
        setTimeout(() => { el.textContent = value; el.classList.remove('refreshing'); }, 400);
    }

    return { init, refreshData };
})();

// Inject legend dot pulse keyframe (one-time)
(function() {
    if (document.getElementById('legend-dot-pulse-kf')) return;
    const s = document.createElement('style');
    s.id = 'legend-dot-pulse-kf';
    s.textContent = `@keyframes legendDotPulse {
        0%, 100% { box-shadow: 0 0 0 0 currentColor; opacity: 1; }
        50%       { box-shadow: 0 0 0 3px transparent; opacity: .7; }
    }`;
    document.head.appendChild(s);
})();

// Auto-init si l'onglet Finance est actif au chargement
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('panel-finance')?.classList.contains('active')) {
        window.AnalyticsFinance.init();
    }
});
</script>
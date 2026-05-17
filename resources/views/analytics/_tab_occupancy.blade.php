{{-- ═══════════════════════════════════════════════════════════════
     ONGLET OCCUPATION — Premium Edition
     Chart.js line + bar + donut statuts + counters animés
═══════════════════════════════════════════════════════════════ --}}

<style>
/* ── BASE ──────────────────────────────────────────────────────── */
.occ-wrap { font-family: 'DM Sans', sans-serif; }

/* ── KPI STRIP ─────────────────────────────────────────────────── */
.occ-kpi-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.occ-kpi-card {
    background: #fff;
    border-radius: 18px;
    padding: 1.4rem 1.5rem;
    border: 1px solid #E8E2D9;
    box-shadow: 0 2px 12px rgba(28,28,36,.06);
    position: relative;
    overflow: hidden;
    cursor: default;
    transition: box-shadow .3s, transform .3s;
}
.occ-kpi-card:hover {
    box-shadow: 0 8px 28px rgba(28,28,36,.11);
    transform: translateY(-3px);
}
.occ-kpi-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 0 0 18px 18px;
}
.occ-kpi-card.k1::after { background: linear-gradient(90deg, #1B6B6B, #22D3EE); }
.occ-kpi-card.k2::after { background: linear-gradient(90deg, #6366F1, #A5B4FC); }
.occ-kpi-card.k3::after { background: linear-gradient(90deg, #22C55E, #86EFAC); }
.occ-kpi-card.k4::after { background: linear-gradient(90deg, #EF4444, #FCA5A5); }

.occ-kpi-icon { font-size: 22px; margin-bottom: 10px; display: block; }
.occ-kpi-label {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #9CA3AF;
    margin-bottom: 6px;
}
.occ-kpi-val {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem;
    font-weight: 700;
    color: #1C1C24;
    line-height: 1;
}
.occ-kpi-val .u {
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: #9CA3AF;
    margin-left: 3px;
}
.occ-kpi-val.negative { color: #EF4444; }
.occ-kpi-sub { font-size: 11.5px; color: #9CA3AF; margin-top: 5px; line-height: 1.3; }

/* ── OCCUPANCY GAUGE CARD ───────────────────────────────────────── */
.occ-gauge-outer {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #E8E2D9;
    box-shadow: 0 2px 12px rgba(28,28,36,.06);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 1rem;
    transition: box-shadow .3s;
}
.occ-gauge-outer:hover { box-shadow: 0 8px 28px rgba(28,28,36,.11); }
.occ-gauge-canvas-wrap {
    position: relative;
    width: 160px;
    height: 90px;
    flex-shrink: 0;
}
.occ-gauge-canvas-wrap canvas { position: absolute; top: 0; left: 0; }
.occ-gauge-center-val {
    position: absolute;
    bottom: 0; left: 50%;
    transform: translateX(-50%);
    text-align: center;
    white-space: nowrap;
}
.occ-gauge-big {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1C1C24;
}
.occ-gauge-lbl { font-size: 10px; color: #9CA3AF; font-weight: 600; text-transform: uppercase; letter-spacing: .08em; }
.occ-gauge-info { flex: 1; }
.occ-gauge-title { font-size: 16px; font-weight: 700; color: #1C1C24; margin-bottom: 6px; }
.occ-gauge-desc { font-size: 12.5px; color: #6B7280; line-height: 1.5; }
.occ-gauge-formula {
    display: inline-block;
    margin-top: 10px;
    font-size: 11px;
    background: #F3EDE4;
    color: #C8602A;
    padding: 4px 10px;
    border-radius: 6px;
    font-family: monospace;
    font-weight: 600;
}

/* ── PANELS ─────────────────────────────────────────────────────── */
.occ-panel {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #E8E2D9;
    box-shadow: 0 2px 12px rgba(28,28,36,.06);
    overflow: hidden;
    transition: box-shadow .3s;
}
.occ-panel:hover { box-shadow: 0 6px 24px rgba(28,28,36,.09); }
.occ-panel-head {
    padding: 1.1rem 1.4rem;
    border-bottom: 1px solid #F3EDE4;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
}
.occ-panel-title { font-size: 13.5px; font-weight: 700; color: #1C1C24; }
.occ-panel-sub { font-size: 11px; color: #9CA3AF; margin-top: 2px; }
.occ-panel-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 99px;
    background: #E8F4F0;
    color: #1B6B6B;
    letter-spacing: .06em;
    text-transform: uppercase;
    white-space: nowrap;
}
.occ-panel-body { padding: 1.25rem 1.4rem; }

/* ── GRID ───────────────────────────────────────────────────────── */
.occ-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}
.occ-full { margin-bottom: 1rem; }

/* ── UNIT TYPE TABLE ────────────────────────────────────────────── */
.occ-unit-table { width: 100%; border-collapse: collapse; }
.occ-unit-table thead th {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #9CA3AF;
    padding: 8px 12px;
    text-align: left;
    border-bottom: 1px solid #F3EDE4;
}
.occ-unit-table tbody td {
    padding: 11px 12px;
    font-size: 13px;
    color: #374151;
    border-bottom: 1px solid #FAF6EF;
}
.occ-unit-table tbody tr:last-child td { border-bottom: none; }
.occ-unit-table tbody tr { transition: background .2s; }
.occ-unit-table tbody tr:hover td { background: #FAF6EF; }
.occ-unit-badge {
    display: inline-block;
    padding: 2px 9px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 600;
    background: #E8F4F0;
    color: #1B6B6B;
}

/* ── STATUS DONUT ───────────────────────────────────────────────── */
.occ-status-wrap {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}
.occ-status-canvas { position: relative; width: 160px; height: 160px; flex-shrink: 0; }
.occ-status-legend {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 7px;
}
.occ-status-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 5px 8px;
    border-radius: 8px;
    transition: background .2s;
}
.occ-status-row:hover { background: #FAF6EF; }
.occ-status-left { display: flex; align-items: center; gap: 8px; }
.occ-status-dot { width: 9px; height: 9px; border-radius: 3px; flex-shrink: 0; }
.occ-status-name { font-size: 12.5px; color: #374151; font-weight: 500; }
.occ-status-val { font-size: 12.5px; font-weight: 700; color: #1C1C24; }

/* ── TREND BAR LIST ─────────────────────────────────────────────── */
.occ-trend-list { display: flex; flex-direction: column; gap: 10px; }
.occ-trend-row { display: flex; flex-direction: column; gap: 3px; }
.occ-trend-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.occ-trend-month { font-size: 12.5px; font-weight: 600; color: #374151; }
.occ-trend-nums { font-size: 12px; color: #9CA3AF; }
.occ-trend-nums strong { color: #1C1C24; font-weight: 700; }
.occ-trend-track { height: 8px; background: #F3EDE4; border-radius: 99px; overflow: hidden; }
.occ-trend-fill {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, #1B6B6B, #22D3EE);
    width: 0;
    transition: width 1s cubic-bezier(.4,0,.2,1);
}

/* ── EMPTY ──────────────────────────────────────────────────────── */
.occ-empty {
    text-align: center;
    padding: 2.5rem;
    color: #C4B8A8;
    font-size: 13px;
    font-style: italic;
}

/* ── FADE IN ────────────────────────────────────────────────────── */
.occ-fade { opacity: 0; animation: occFadeIn .5s cubic-bezier(.4,0,.2,1) forwards; }
@keyframes occFadeIn {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}
.occ-d1 { animation-delay: .05s; }
.occ-d2 { animation-delay: .12s; }
.occ-d3 { animation-delay: .19s; }
.occ-d4 { animation-delay: .26s; }
.occ-d5 { animation-delay: .33s; }

@media (max-width: 960px) {
    .occ-kpi-strip { grid-template-columns: 1fr 1fr; }
    .occ-grid-2    { grid-template-columns: 1fr; }
    .occ-gauge-outer { flex-direction: column; }
    .occ-status-wrap { flex-direction: column; }
}
</style>

<div class="occ-wrap">

{{-- ── KPIs ──────────────────────────────────────────────────────── --}}
<div class="occ-kpi-strip occ-fade occ-d1">
    <div class="occ-kpi-card k1">
        <span class="occ-kpi-icon">🏨</span>
        <div class="occ-kpi-label">Taux d'Occupation</div>
        <div class="occ-kpi-val occ-counter" data-target="{{ $data['kpis']['occupancy_rate'] }}" data-dec="1">0<span class="u">%</span></div>
        <div class="occ-kpi-sub">Nuits vendues / capacité × 365 j</div>
    </div>
    <div class="occ-kpi-card k2">
        <span class="occ-kpi-icon">⏱</span>
        <div class="occ-kpi-label">Lead Time</div>
        <div class="occ-kpi-val occ-counter {{ $data['kpis']['lead_time'] < 0 ? 'negative' : '' }}"
             data-target="{{ abs($data['kpis']['lead_time']) }}" data-dec="1"
             data-prefix="{{ $data['kpis']['lead_time'] < 0 ? '-' : '' }}">
            0<span class="u">j</span>
        </div>
        <div class="occ-kpi-sub">
            Délai moyen réservation → arrivée
            @if($data['kpis']['lead_time'] < 0)
                <br><span style="color:#EF4444;font-size:10px;">⚠ Valeur négative = données seed</span>
            @endif
        </div>
    </div>
    <div class="occ-kpi-card k3">
        <span class="occ-kpi-icon">🌙</span>
        <div class="occ-kpi-label">Nuits / Réservation</div>
        <div class="occ-kpi-val occ-counter" data-target="{{ $data['kpis']['avg_nights'] }}" data-dec="2">0</div>
        <div class="occ-kpi-sub">Durée moyenne d'une réservation</div>
    </div>
    <div class="occ-kpi-card k4">
        <span class="occ-kpi-icon">❌</span>
        <div class="occ-kpi-label">Taux d'Annulation</div>
        <div class="occ-kpi-val occ-counter" data-target="{{ $data['kpis']['cancel_rate'] }}" data-dec="1">0<span class="u">%</span></div>
        <div class="occ-kpi-sub">Réservations annulées / total</div>
    </div>
</div>

{{-- ── GAUGE OCCUPANCY ────────────────────────────────────────────── --}}
<div class="occ-gauge-outer occ-fade occ-d2">
    <div class="occ-gauge-canvas-wrap">
        <canvas id="occGaugeChart" width="160" height="90"></canvas>
        <div class="occ-gauge-center-val">
            <div class="occ-gauge-big">{{ $data['kpis']['occupancy_rate'] }}%</div>
            <div class="occ-gauge-lbl">Occupation</div>
        </div>
    </div>
    <div class="occ-gauge-info">
        <div class="occ-gauge-title">Taux d'occupation global</div>
        <div class="occ-gauge-desc">
            Mesure le pourcentage de nuits-personnes effectivement vendues par rapport
            à la capacité théorique maximale sur 365 jours.
            Un taux de <strong>70–80%</strong> est considéré comme excellent dans le secteur.
        </div>
        <span class="occ-gauge-formula">Nuits vendues ÷ (Capacité × 365) × 100</span>
    </div>
</div>

{{-- ── ROW 1 : Performance par type + Statuts ─────────────────────── --}}
<div class="occ-grid-2 occ-fade occ-d3">

    {{-- Unit type table --}}
    <div class="occ-panel">
        <div class="occ-panel-head">
            <div>
                <div class="occ-panel-title">Performance par type d'unité</div>
                <div class="occ-panel-sub">Volume, revenue et prix moyen par catégorie</div>
            </div>
        </div>
        <div class="occ-panel-body" style="padding:0">
            @if(count($data['by_unit_type']) > 0)
                <table class="occ-unit-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th style="text-align:right">Résas</th>
                            <th style="text-align:right">Revenue</th>
                            <th style="text-align:right">Moy. TND</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['by_unit_type'] as $row)
                            <tr>
                                <td>
                                    <span class="occ-unit-badge">{{ $row['label'] }}</span>
                                </td>
                                <td style="text-align:right; font-weight:600">
                                    {{ number_format($row['count'], 0, '.', ' ') }}
                                </td>
                                <td style="text-align:right; font-weight:700; color:#1C1C24">
                                    {{ number_format($row['revenue'], 0, '.', ' ') }}
                                </td>
                                <td style="text-align:right; font-weight:700; color:#1B6B6B">
                                    {{ number_format($row['avg_price'], 1, ',', ' ') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="occ-empty">Aucune donnée disponible</div>
            @endif
        </div>
    </div>

    {{-- Status donut --}}
    <div class="occ-panel">
        <div class="occ-panel-head">
            <div>
                <div class="occ-panel-title">Statuts des réservations</div>
                <div class="occ-panel-sub">Distribution sur l'ensemble de la période</div>
            </div>
            @if(count($data['status_split']) > 0)
                @php $totalStatus = array_sum(array_column($data['status_split'], 'count')); @endphp
                <span class="occ-panel-badge">{{ $totalStatus }} total</span>
            @endif
        </div>
        <div class="occ-panel-body">
            @if(count($data['status_split']) > 0)
                <div class="occ-status-wrap">
                    <div class="occ-status-canvas">
                        <canvas id="occStatusDonut" width="160" height="160"></canvas>
                    </div>
                    <div class="occ-status-legend" id="occStatusLegend"></div>
                </div>
            @else
                <div class="occ-empty">Aucune donnée disponible</div>
            @endif
        </div>
    </div>

</div>

{{-- ── ROW 2 : Ligne tendance + Bars mensuels ──────────────────────── --}}
<div class="occ-grid-2 occ-fade occ-d4">

    {{-- Line chart --}}
    <div class="occ-panel">
        <div class="occ-panel-head">
            <div>
                <div class="occ-panel-title">Tendance mensuelle</div>
                <div class="occ-panel-sub">Personnes hébergées — évolution sur la période</div>
            </div>
        </div>
        <div class="occ-panel-body">
            @if(count($data['monthly_trend']) > 0)
                <div style="position:relative; height:220px;">
                    <canvas id="occLineChart"></canvas>
                </div>
            @else
                <div class="occ-empty">Aucune donnée mensuelle disponible</div>
            @endif
        </div>
    </div>

    {{-- Bar list --}}
    <div class="occ-panel">
        <div class="occ-panel-head">
            <div>
                <div class="occ-panel-title">Détail par mois</div>
                <div class="occ-panel-sub">Nombre de personnes hébergées</div>
            </div>
        </div>
        <div class="occ-panel-body">
            @if(count($data['monthly_trend']) > 0)
                @php $maxM = max(array_column($data['monthly_trend'], 'person_count')) ?: 1; @endphp
                <div class="occ-trend-list">
                    @foreach($data['monthly_trend'] as $row)
                        <div class="occ-trend-row">
                            <div class="occ-trend-top">
                                <span class="occ-trend-month">
                                    {{ \Carbon\Carbon::parse($row['month'] . '-01')->translatedFormat('M Y') }}
                                </span>
                                <span class="occ-trend-nums">
                                    <strong>{{ $row['person_count'] }}</strong> pers.
                                </span>
                            </div>
                            <div class="occ-trend-track">
                                <div class="occ-trend-fill" data-w="{{ $row['person_count'] / $maxM * 100 }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="occ-empty">Aucune donnée disponible</div>
            @endif
        </div>
    </div>

</div>

</div>{{-- /occ-wrap --}}

<script>
window.AnalyticsOccupancy = (function () {

    const monthlyData  = @json($data['monthly_trend']);
    const statusData   = @json($data['status_split']);
    const occupancyRate = {{ $data['kpis']['occupancy_rate'] }};

    const STATUS_PALETTE = {
        'confirmed':   '#1B6B6B',
        'checked_in':  '#22C55E',
        'checked_out': '#6366F1',
        'pending':     '#F59E0B',
        'cancelled':   '#EF4444',
    };

    let initialized = false;

    function fmt(v, dec) {
        return Number(v).toLocaleString('fr-FR', {
            minimumFractionDigits: dec ?? 0,
            maximumFractionDigits: dec ?? 0
        });
    }

    /* ── Animated counters ───────────────────────────────────── */
    function runCounters() {
        document.querySelectorAll('#panel-occupancy .occ-counter').forEach(el => {
            const target = parseFloat(el.dataset.target ?? 0);
            const dec    = parseInt(el.dataset.dec ?? 0);
            const prefix = el.dataset.prefix ?? '';
            const suffix = el.querySelector('.u')?.outerHTML ?? '';
            const start  = performance.now();
            const dur    = 1000;
            function tick(now) {
                const t = Math.min(1, (now - start) / dur);
                const e = 1 - Math.pow(1 - t, 3);
                el.innerHTML = prefix + fmt(target * e, dec) + suffix;
                if (t < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        });
    }

    /* ── Animated bar fills ──────────────────────────────────── */
    function animateBars() {
        document.querySelectorAll('#panel-occupancy [data-w]').forEach(el => {
            setTimeout(() => { el.style.width = el.dataset.w + '%'; }, 150);
        });
    }

    /* ── Build status legend ─────────────────────────────────── */
    function buildStatusLegend(items, palette, total) {
        const container = document.getElementById('occStatusLegend');
        if (!container) return;
        container.innerHTML = '';
        items.forEach(item => {
            const color = palette[item.status] ?? '#9CA3AF';
            const pct = total > 0 ? (item.count / total * 100).toFixed(1) : 0;
            const row = document.createElement('div');
            row.className = 'occ-status-row';
            row.innerHTML = `
                <div class="occ-status-left">
                    <span class="occ-status-dot" style="background:${color}"></span>
                    <span class="occ-status-name">${item.label}</span>
                </div>
                <span class="occ-status-val">${fmt(item.count)} <span style="font-size:10.5px;color:#9CA3AF;font-weight:400">${pct}%</span></span>
            `;
            container.appendChild(row);
        });
    }

    function init() {
        if (initialized) return;
        if (typeof Chart === 'undefined') return;

        Chart.defaults.font.family = "'DM Sans', sans-serif";
        Chart.defaults.color = '#9CA3AF';

        runCounters();
        animateBars();

        /* ── Gauge semi-circle ───────────────────────────────── */
        const gaugeEl = document.getElementById('occGaugeChart');
        if (gaugeEl) {
            const rate  = Math.min(100, Math.max(0, occupancyRate));
            const empty = 100 - rate;
            new Chart(gaugeEl.getContext('2d'), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [rate, empty],
                        backgroundColor: ['#1B6B6B', '#F3EDE4'],
                        borderWidth: 0,
                        hoverOffset: 0,
                    }]
                },
                options: {
                    responsive: false,
                    cutout: '72%',
                    rotation: -90,
                    circumference: 180,
                    animation: { animateRotate: true, duration: 1200, easing: 'easeOutCubic' },
                    plugins: { legend: { display: false }, tooltip: { enabled: false } }
                }
            });
        }

        /* ── Status donut ────────────────────────────────────── */
        const statusEl = document.getElementById('occStatusDonut');
        if (statusEl && statusData.length > 0) {
            const labels = statusData.map(s => s.label);
            const values = statusData.map(s => s.count);
            const colors = statusData.map(s => STATUS_PALETTE[s.status] ?? '#9CA3AF');
            const total  = values.reduce((a, b) => a + b, 0);

            new Chart(statusEl.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors,
                        borderWidth: 0,
                        hoverOffset: 10,
                    }]
                },
                options: {
                    responsive: false,
                    cutout: '65%',
                    animation: { animateRotate: true, duration: 1100, easing: 'easeOutCubic' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(28,28,36,.95)',
                            titleColor: '#FEFCF9',
                            bodyColor: '#9CA3AF',
                            cornerRadius: 10,
                            padding: 11,
                            callbacks: {
                                label: ctx => {
                                    const pct = total > 0 ? (ctx.parsed / total * 100).toFixed(1) : 0;
                                    return `  ${fmt(ctx.parsed)} résas (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });

            buildStatusLegend(statusData, STATUS_PALETTE, total);
        }

        /* ── Line chart tendance ─────────────────────────────── */
        const lineEl = document.getElementById('occLineChart');
        if (lineEl && monthlyData.length > 0) {
            const labels = monthlyData.map(r => {
                const [y, m] = r.month.split('-');
                return new Date(y, m - 1).toLocaleDateString('fr-FR', { month: 'short', year: '2-digit' });
            });
            const values = monthlyData.map(r => r.person_count);

            const ctx  = lineEl.getContext('2d');
            const grad = ctx.createLinearGradient(0, 0, 0, 220);
            grad.addColorStop(0, 'rgba(27,107,107,.22)');
            grad.addColorStop(1, 'rgba(27,107,107,.01)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Personnes',
                        data: values,
                        borderColor: '#1B6B6B',
                        backgroundColor: grad,
                        fill: true,
                        tension: .42,
                        borderWidth: 2.5,
                        pointRadius: 4,
                        pointBackgroundColor: '#1B6B6B',
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#1B6B6B',
                        pointHoverBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 1200, easing: 'easeOutCubic' },
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(28,28,36,.95)',
                            titleColor: '#FEFCF9',
                            bodyColor: '#9CA3AF',
                            cornerRadius: 10,
                            padding: 12,
                            callbacks: {
                                label: ctx => `  ${fmt(ctx.parsed.y)} personnes hébergées`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: { font: { size: 11 }, maxRotation: 30 }
                        },
                        y: {
                            grid: { color: 'rgba(232,226,217,.6)', drawTicks: false },
                            border: { dash: [4, 4], display: false },
                            ticks: {
                                font: { size: 11 },
                                stepSize: 1,
                                callback: v => Number.isInteger(v) ? v : null
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        initialized = true;
    }

    return { init };
})();

document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('panel-occupancy')?.classList.contains('active')) {
        setTimeout(() => window.AnalyticsOccupancy.init(), 80);
    }
});
</script>
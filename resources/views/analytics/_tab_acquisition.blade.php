{{-- ═══════════════════════════════════════════════════════════════
     ONGLET ACQUISITION — Premium Edition
     Chart.js donut + bar + funnel animé + counters
═══════════════════════════════════════════════════════════════ --}}

<style>
/* ── RESET & BASE ───────────────────────────────────────────────── */
.acq-wrap { font-family: 'DM Sans', sans-serif; }

/* ── COUNTER ANIMATION ─────────────────────────────────────────── */
@keyframes acqCountUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── KPI STRIP ─────────────────────────────────────────────────── */
.acq-kpi-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.acq-kpi-card {
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
.acq-kpi-card:hover {
    box-shadow: 0 8px 28px rgba(28,28,36,.11);
    transform: translateY(-3px);
}
.acq-kpi-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 0 0 18px 18px;
}
.acq-kpi-card.k1::after { background: linear-gradient(90deg, #C8602A, #F97316); }
.acq-kpi-card.k2::after { background: linear-gradient(90deg, #1B6B6B, #22D3EE); }
.acq-kpi-card.k3::after { background: linear-gradient(90deg, #6366F1, #A5B4FC); }
.acq-kpi-card.k4::after { background: linear-gradient(90deg, #22C55E, #86EFAC); }

.acq-kpi-icon {
    font-size: 22px;
    margin-bottom: 10px;
    display: block;
}
.acq-kpi-label {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #9CA3AF;
    margin-bottom: 6px;
}
.acq-kpi-val {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem;
    font-weight: 700;
    color: #1C1C24;
    line-height: 1;
    animation: acqCountUp .6s cubic-bezier(.4,0,.2,1) both;
}
.acq-kpi-val .u {
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: #9CA3AF;
    margin-left: 3px;
}
.acq-kpi-sub {
    font-size: 11.5px;
    color: #9CA3AF;
    margin-top: 5px;
    line-height: 1.3;
}

/* ── MAIN GRID ─────────────────────────────────────────────────── */
.acq-main-grid {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

/* ── PANEL ─────────────────────────────────────────────────────── */
.acq-panel {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #E8E2D9;
    box-shadow: 0 2px 12px rgba(28,28,36,.06);
    overflow: hidden;
    transition: box-shadow .3s;
}
.acq-panel:hover { box-shadow: 0 6px 24px rgba(28,28,36,.09); }
.acq-panel-head {
    padding: 1.1rem 1.4rem;
    border-bottom: 1px solid #F3EDE4;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
}
.acq-panel-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #1C1C24;
}
.acq-panel-sub {
    font-size: 11px;
    color: #9CA3AF;
    margin-top: 2px;
}
.acq-panel-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 99px;
    background: #FEF3E2;
    color: #C8602A;
    letter-spacing: .06em;
    text-transform: uppercase;
    white-space: nowrap;
}
.acq-panel-body { padding: 1.25rem 1.4rem; }

/* ── DONUT ─────────────────────────────────────────────────────── */
.acq-donut-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}
.acq-donut-canvas-wrap {
    position: relative;
    width: 200px; height: 200px;
}
.acq-donut-center {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    pointer-events: none;
}
.acq-donut-center-val {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem;
    font-weight: 700;
    color: #1C1C24;
    line-height: 1;
}
.acq-donut-center-lbl {
    font-size: 10px;
    color: #9CA3AF;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-top: 3px;
}
.acq-donut-legend {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.acq-legend-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 5px 8px;
    border-radius: 8px;
    transition: background .2s;
    cursor: default;
}
.acq-legend-row:hover { background: #FAF6EF; }
.acq-legend-left {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
    min-width: 0;
}
.acq-legend-dot {
    width: 9px; height: 9px;
    border-radius: 3px;
    flex-shrink: 0;
}
.acq-legend-name {
    font-size: 12.5px;
    color: #374151;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.acq-legend-right {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}
.acq-legend-val {
    font-size: 12px;
    font-weight: 700;
    color: #1C1C24;
}
.acq-legend-pct {
    font-size: 10.5px;
    color: #9CA3AF;
    min-width: 34px;
    text-align: right;
}

/* ── SOURCE BARS ───────────────────────────────────────────────── */
.acq-src-list { display: flex; flex-direction: column; gap: 11px; }
.acq-src-row { display: flex; flex-direction: column; gap: 4px; }
.acq-src-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.acq-src-name { font-size: 13px; font-weight: 600; color: #374151; }
.acq-src-nums { font-size: 12px; color: #9CA3AF; }
.acq-src-nums strong { color: #1C1C24; font-weight: 700; }
.acq-src-track {
    height: 8px;
    background: #F3EDE4;
    border-radius: 99px;
    overflow: hidden;
}
.acq-src-fill {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, #1B6B6B, #22D3EE);
    width: 0;
    transition: width 1s cubic-bezier(.4,0,.2,1);
}
.acq-src-fill.orange { background: linear-gradient(90deg, #C8602A, #F97316); }

/* ── COUNTRY TABLE ─────────────────────────────────────────────── */
.acq-country-table { width: 100%; }
.acq-country-row-item {
    display: grid;
    grid-template-columns: 30px 1fr 120px 60px;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 10px;
    transition: background .2s;
    cursor: default;
}
.acq-country-row-item:hover { background: #FAF6EF; }
.acq-country-rank {
    width: 26px; height: 26px;
    border-radius: 50%;
    background: #F3EDE4;
    color: #9CA3AF;
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}
.acq-country-row-item:nth-child(1) .acq-country-rank { background: linear-gradient(135deg,#F59E0B,#D97706); color:#fff; }
.acq-country-row-item:nth-child(2) .acq-country-rank { background: linear-gradient(135deg,#94A3B8,#64748B); color:#fff; }
.acq-country-row-item:nth-child(3) .acq-country-rank { background: linear-gradient(135deg,#C8602A,#A84E20); color:#fff; }
.acq-country-name { font-size: 13px; font-weight: 600; color: #374151; }
.acq-country-mini-track {
    height: 8px;
    background: #F3EDE4;
    border-radius: 99px;
    overflow: hidden;
}
.acq-country-mini-fill {
    height: 100%;
    background: linear-gradient(90deg, #1B6B6B, #22D3EE);
    border-radius: 99px;
    width: 0;
    transition: width 1s cubic-bezier(.4,0,.2,1);
}
.acq-country-val { font-size: 13px; font-weight: 700; color: #1C1C24; text-align: right; }

/* ── REV BAR CHART WRAP ────────────────────────────────────────── */
.acq-chart-wrap { position: relative; }

/* ── FUNNEL ────────────────────────────────────────────────────── */
.acq-funnel-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    max-width: 500px;
    margin: 0 auto;
}
.acq-funnel-total-label {
    text-align: center;
    margin-bottom: 1rem;
    font-size: 13px;
    color: #9CA3AF;
}
.acq-funnel-total-label strong {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    color: #1C1C24;
    font-weight: 700;
}
.acq-funnel-stage {
    padding: 14px 20px;
    border-radius: 12px;
    background: linear-gradient(135deg, #1B6B6B, #0E4545);
    color: #fff;
    text-align: center;
    transition: transform .25s, box-shadow .25s;
    cursor: default;
    box-shadow: 0 4px 14px rgba(27,107,107,.2);
}
.acq-funnel-stage:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 24px rgba(27,107,107,.3);
}
.acq-funnel-stage.cancelled {
    background: linear-gradient(135deg, #DC2626, #991B1B);
    box-shadow: 0 4px 14px rgba(220,38,38,.2);
}
.acq-funnel-stage.cancelled:hover { box-shadow: 0 8px 24px rgba(220,38,38,.3); }
.acq-funnel-stage-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .09em; opacity: .8; }
.acq-funnel-stage-val { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700; margin-top: 2px; }
.acq-funnel-stage-pct { font-size: 11px; opacity: .75; margin-top: 1px; }
.acq-funnel-arrow { font-size: 13px; color: #C4B8A8; }

/* ── FULL WIDTH PANEL ──────────────────────────────────────────── */
.acq-full { margin-bottom: 1rem; }

/* ── BOTTOM GRID ───────────────────────────────────────────────── */
.acq-bottom-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

/* ── EMPTY ─────────────────────────────────────────────────────── */
.acq-empty {
    text-align: center;
    padding: 2.5rem;
    color: #C4B8A8;
    font-size: 13px;
    font-style: italic;
}

/* ── FADE IN ───────────────────────────────────────────────────── */
.acq-fade { opacity: 0; animation: acqFadeIn .5s cubic-bezier(.4,0,.2,1) forwards; }
@keyframes acqFadeIn {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}
.acq-d1 { animation-delay: .05s; }
.acq-d2 { animation-delay: .12s; }
.acq-d3 { animation-delay: .19s; }
.acq-d4 { animation-delay: .26s; }
.acq-d5 { animation-delay: .33s; }
.acq-d6 { animation-delay: .40s; }

@media (max-width: 960px) {
    .acq-kpi-strip  { grid-template-columns: 1fr 1fr; }
    .acq-main-grid  { grid-template-columns: 1fr; }
    .acq-bottom-grid{ grid-template-columns: 1fr; }
}
</style>

<div class="acq-wrap">

{{-- ── KPIs ──────────────────────────────────────────────────────── --}}
<div class="acq-kpi-strip acq-fade acq-d1">
    <div class="acq-kpi-card k1">
        <span class="acq-kpi-icon">👥</span>
        <div class="acq-kpi-label">Total Clients</div>
        <div class="acq-kpi-val acq-counter" data-target="{{ $data['kpis']['total_clients'] }}">0</div>
        <div class="acq-kpi-sub">Personnes hébergées (hors annulations)</div>
    </div>
    <div class="acq-kpi-card k2">
        <span class="acq-kpi-icon">🛏</span>
        <div class="acq-kpi-label">Durée Moyenne</div>
        <div class="acq-kpi-val acq-counter" data-target="{{ $data['kpis']['avg_stay'] }}" data-dec="2">0<span class="u">j</span></div>
        <div class="acq-kpi-sub">Durée moyenne d'un séjour</div>
    </div>
    <div class="acq-kpi-card k3">
        <span class="acq-kpi-icon">🔁</span>
        <div class="acq-kpi-label">Taux de Répétition</div>
        <div class="acq-kpi-val acq-counter" data-target="{{ $data['kpis']['repeat_rate'] }}" data-dec="1">0<span class="u">%</span></div>
        <div class="acq-kpi-sub">Clients ayant réservé ≥ 2 fois</div>
    </div>
    <div class="acq-kpi-card k4">
        <span class="acq-kpi-icon">✅</span>
        <div class="acq-kpi-label">Taux de Conversion</div>
        <div class="acq-kpi-val acq-counter" data-target="{{ $data['kpis']['conversion_rate'] }}" data-dec="1">0<span class="u">%</span></div>
        <div class="acq-kpi-sub">Demandes contact → confirmées</div>
    </div>
</div>

{{-- ── ROW 1 : Donut sources + Top pays ─────────────────────────── --}}
<div class="acq-main-grid acq-fade acq-d2">

    {{-- Donut --}}
    <div class="acq-panel">
        <div class="acq-panel-head">
            <div>
                <div class="acq-panel-title">Clients par source</div>
                <div class="acq-panel-sub">Répartition des canaux d'acquisition</div>
            </div>
        </div>
        <div class="acq-panel-body">
            @if(count($data['by_source']) > 0)
                <div class="acq-donut-wrap">
                    <div class="acq-donut-canvas-wrap">
                        <canvas id="acqDonutSources" width="200" height="200"></canvas>
                        <div class="acq-donut-center">
                            <div class="acq-donut-center-val">{{ number_format(array_sum(array_column($data['by_source'], 'count')), 0, '.', ' ') }}</div>
                            <div class="acq-donut-center-lbl">Clients</div>
                        </div>
                    </div>
                    <div class="acq-donut-legend" id="acqDonutLegend"></div>
                </div>
            @else
                <div class="acq-empty">Aucune donnée de source disponible</div>
            @endif
        </div>
    </div>

    {{-- Top pays --}}
    <div class="acq-panel">
        <div class="acq-panel-head">
            <div>
                <div class="acq-panel-title">Top pays</div>
                <div class="acq-panel-sub">Provenance géographique des clients — top {{ min(count($data['by_country']), 12) }}</div>
            </div>
            <span class="acq-panel-badge">{{ count($data['by_country']) }} pays</span>
        </div>
        <div class="acq-panel-body">
            @if(count($data['by_country']) > 0)
                @php $maxC = max(array_column($data['by_country'], 'total')); @endphp
                <div class="acq-country-table">
                    @foreach(array_slice($data['by_country'], 0, 12) as $i => $row)
                        <div class="acq-country-row-item">
                            <div class="acq-country-rank">{{ $i + 1 }}</div>
                            <div class="acq-country-name">{{ $row['country'] }}</div>
                            <div class="acq-country-mini-track">
                                <div class="acq-country-mini-fill" data-w="{{ $row['total'] / $maxC * 100 }}"></div>
                            </div>
                            <div class="acq-country-val">{{ number_format($row['total'], 0, '.', ' ') }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="acq-empty">Aucune donnée de nationalité disponible</div>
            @endif
        </div>
    </div>

</div>

{{-- ── ROW 2 : Revenue par source (bar) ──────────────────────────── --}}
<div class="acq-full acq-fade acq-d3">
    <div class="acq-panel">
        <div class="acq-panel-head">
            <div>
                <div class="acq-panel-title">Revenue par source</div>
                <div class="acq-panel-sub">Chiffre d'affaires généré par canal (TND)</div>
            </div>
        </div>
        <div class="acq-panel-body">
            @if(count($data['rev_by_source']) > 0)
                <div class="acq-chart-wrap" style="height:220px; position:relative;">
                    <canvas id="acqBarRevSource"></canvas>
                </div>
            @else
                <div class="acq-empty">Aucune donnée de revenue disponible</div>
            @endif
        </div>
    </div>
</div>

{{-- ── ROW 3 : Sources bars + Funnel ─────────────────────────────── --}}
<div class="acq-bottom-grid acq-fade acq-d4">

    {{-- Source bars --}}
    <div class="acq-panel">
        <div class="acq-panel-head">
            <div>
                <div class="acq-panel-title">Volume par source</div>
                <div class="acq-panel-sub">Nombre de clients par canal</div>
            </div>
        </div>
        <div class="acq-panel-body">
            @if(count($data['by_source']) > 0)
                @php $maxSrc = max(array_column($data['by_source'], 'count')); @endphp
                <div class="acq-src-list">
                    @foreach($data['by_source'] as $i => $row)
                        <div class="acq-src-row">
                            <div class="acq-src-top">
                                <span class="acq-src-name">{{ ucfirst($row['source']) }}</span>
                                <span class="acq-src-nums">
                                    <strong>{{ number_format($row['count'], 0, '.', ' ') }}</strong> · {{ $row['percent'] }}%
                                </span>
                            </div>
                            <div class="acq-src-track">
                                <div class="acq-src-fill {{ $i % 2 === 1 ? 'orange' : '' }}"
                                     data-w="{{ $maxSrc > 0 ? ($row['count'] / $maxSrc * 100) : 0 }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="acq-empty">Aucune donnée disponible</div>
            @endif
        </div>
    </div>

    {{-- Funnel --}}
    <div class="acq-panel">
        <div class="acq-panel-head">
            <div>
                <div class="acq-panel-title">Funnel d'acquisition</div>
                <div class="acq-panel-sub">Parcours des demandes contact → réservation</div>
            </div>
        </div>
        <div class="acq-panel-body">
            @if($data['funnel']['total'] > 0)
                <div class="acq-funnel-total-label">
                    <strong>{{ $data['funnel']['total'] }}</strong> demandes au total
                </div>
                <div class="acq-funnel-wrap">
                    @foreach($data['funnel']['stages'] as $i => $stage)
                        @if($stage['count'] > 0)
                            @php $w = max(42, 100 - $i * 12); @endphp
                            <div class="acq-funnel-stage {{ $stage['status'] === 'cancelled' ? 'cancelled' : '' }}"
                                 style="width: {{ $w }}%">
                                <div class="acq-funnel-stage-label">{{ $stage['label'] }}</div>
                                <div class="acq-funnel-stage-val">{{ $stage['count'] }}</div>
                                <div class="acq-funnel-stage-pct">{{ $stage['percent'] }}%</div>
                            </div>
                            @if(!$loop->last && $stage['status'] !== 'cancelled')
                                <div class="acq-funnel-arrow">▼</div>
                            @endif
                        @endif
                    @endforeach
                </div>
            @else
                <div class="acq-empty">
                    Aucune demande contact pour ce hostel.<br>
                    <small>Le funnel s'activera dès la première demande reçue.</small>
                </div>
            @endif
        </div>
    </div>

</div>

</div>{{-- /acq-wrap --}}

<script>
window.AnalyticsAcquisition = (function () {

    const sourceData   = @json($data['by_source']);
    const revSrcData   = @json($data['rev_by_source']);
    const PALETTE = ['#1B6B6B','#C8602A','#6366F1','#22C55E','#F59E0B','#EC4899','#14B8A6','#8B5CF6','#3B82F6'];

    let initialized = false;
    let donutChart  = null;
    let barChart    = null;

    function fmt(v, dec) {
        return Number(v).toLocaleString('fr-FR', {
            minimumFractionDigits: dec ?? 0,
            maximumFractionDigits: dec ?? 0
        });
    }

    /* ── Animated counter ─────────────────────────────────────── */
    function runCounters() {
        document.querySelectorAll('#panel-acquisition .acq-counter').forEach(el => {
            const target = parseFloat(el.dataset.target ?? 0);
            const dec    = parseInt(el.dataset.dec ?? 0);
            const suffix = el.querySelector('.u')?.outerHTML ?? '';
            const start  = performance.now();
            const dur    = 1000;
            function tick(now) {
                const t = Math.min(1, (now - start) / dur);
                const e = 1 - Math.pow(1 - t, 3);
                el.innerHTML = fmt(target * e, dec) + suffix;
                if (t < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        });
    }

    /* ── Animated bars ────────────────────────────────────────── */
    function animateBars() {
        document.querySelectorAll('#panel-acquisition [data-w]').forEach(el => {
            setTimeout(() => { el.style.width = el.dataset.w + '%'; }, 100);
        });
    }

    /* ── Build donut legend ───────────────────────────────────── */
    function buildDonutLegend(container, items, palette) {
        if (!container) return;
        container.innerHTML = '';
        const total = items.reduce((s, x) => s + x.count, 0);
        items.forEach((item, i) => {
            const pct = total > 0 ? (item.count / total * 100).toFixed(1) : 0;
            const row = document.createElement('div');
            row.className = 'acq-legend-row';
            row.innerHTML = `
                <div class="acq-legend-left">
                    <span class="acq-legend-dot" style="background:${palette[i] ?? '#ccc'}"></span>
                    <span class="acq-legend-name">${item.source.charAt(0).toUpperCase() + item.source.slice(1)}</span>
                </div>
                <div class="acq-legend-right">
                    <span class="acq-legend-val">${fmt(item.count)}</span>
                    <span class="acq-legend-pct">${pct}%</span>
                </div>`;
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

        /* ── Donut ─────────────────────────────────────────────── */
        const donutEl = document.getElementById('acqDonutSources');
        if (donutEl && sourceData.length > 0) {
            const labels = sourceData.map(s => s.source.charAt(0).toUpperCase() + s.source.slice(1));
            const values = sourceData.map(s => s.count);

            donutChart = new Chart(donutEl.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: PALETTE.slice(0, labels.length),
                        borderWidth: 0,
                        hoverOffset: 12,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    animation: { animateRotate: true, duration: 1200, easing: 'easeOutCubic' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(28,28,36,.95)',
                            titleColor: '#FEFCF9',
                            bodyColor: '#9CA3AF',
                            cornerRadius: 10,
                            padding: 12,
                            callbacks: {
                                label: ctx => {
                                    const total = values.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? (ctx.parsed / total * 100).toFixed(1) : 0;
                                    return `  ${fmt(ctx.parsed)} clients (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });

            buildDonutLegend(
                document.getElementById('acqDonutLegend'),
                sourceData,
                PALETTE
            );
        }

        /* ── Bar Revenue ───────────────────────────────────────── */
        const barEl = document.getElementById('acqBarRevSource');
        if (barEl && revSrcData.length > 0) {
            const labels = revSrcData.map(r => r.source.charAt(0).toUpperCase() + r.source.slice(1));
            const values = revSrcData.map(r => r.revenue);
            const counts = revSrcData.map(r => r.reservations_count);

            const ctx  = barEl.getContext('2d');
            const grad = ctx.createLinearGradient(0, 0, 0, 220);
            grad.addColorStop(0, 'rgba(200,96,42,.85)');
            grad.addColorStop(1, 'rgba(200,96,42,.35)');

            barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Revenue TND',
                        data: values,
                        backgroundColor: grad,
                        hoverBackgroundColor: '#C8602A',
                        borderRadius: 10,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 1100, easing: 'easeOutCubic' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(28,28,36,.95)',
                            titleColor: '#FEFCF9',
                            bodyColor: '#9CA3AF',
                            cornerRadius: 10,
                            padding: 12,
                            callbacks: {
                                label: ctx => `  ${fmt(ctx.parsed.y, 0)} TND`,
                                afterLabel: (ctx) => `  ${counts[ctx.dataIndex]} réservations`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: { font: { size: 12 } }
                        },
                        y: {
                            grid: { color: 'rgba(232,226,217,.6)', drawTicks: false },
                            border: { dash: [4, 4], display: false },
                            ticks: {
                                font: { size: 11 },
                                callback: v => Math.abs(v) >= 1000 ? (v / 1000).toFixed(1) + 'K' : v
                            }
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
    if (document.getElementById('panel-acquisition')?.classList.contains('active')) {
        setTimeout(() => window.AnalyticsAcquisition.init(), 80);
    }
});
</script>
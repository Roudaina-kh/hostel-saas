@extends('layouts.app')
@section('title', 'Analytics — ' . $hostel->name)
@section('content')

<style>
/* ── HERO ───────────────────────────────────────────────────────── */
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
.an-subtitle { font-size: 14px; color: rgba(254,252,249,.55); font-weight: 500; margin: 0; }
.an-hero-date { font-size: 13px; color: rgba(254,252,249,.45); font-weight: 600; margin-top: 6px; }
.an-update-tag { font-size: 11px; color: rgba(254,252,249,.35); margin-top: 4px; }

/* ── TABS NAV ───────────────────────────────────────────────────── */
.an-tabs-wrap {
    position: relative;
    z-index: 20;
    background: #FEFCF9;
    border-radius: 20px;
    border: 1px solid #DDD6CA;
    box-shadow: 0 8px 30px rgba(28,28,36,.07);
    display: flex;
    gap: 4px;
    padding: 6px;
    margin-bottom: 1.5rem;
}
.an-tab-btn {
    flex: 1;
    padding: 12px 20px;
    border: none;
    border-radius: 14px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13.5px;
    font-weight: 600;
    color: #6B6B7A;
    background: transparent;
    cursor: pointer;
    transition: all .25s cubic-bezier(.4,0,.2,1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.an-tab-btn:hover { background: #F5EFE6; color: #2E2E3A; }
.an-tab-btn.active {
    background: linear-gradient(135deg, #1B6B6B, #0F4A4A);
    color: #FEFCF9;
    box-shadow: 0 4px 14px rgba(27,107,107,.35);
}
.an-tab-btn .tab-icon { font-size: 16px; }

/* ── PANELS ─────────────────────────────────────────────────────── */
.an-panel { display: none; }
.an-panel.active { display: block; }

/* ── FADE ANIMATION ─────────────────────────────────────────────── */
.an-fade { opacity: 0; transform: translateY(18px); animation: anFade .55s cubic-bezier(.4,0,.2,1) forwards; }
@keyframes anFade { to { opacity: 1; transform: translateY(0); } }
.ad1 { animation-delay: .05s; }
</style>

{{-- ── HERO ──────────────────────────────────────────────────────────── --}}
<div class="an-hero an-fade ad1">
    <div class="an-hero-body">
        <div class="an-live-badge">
            <span class="an-live-dot"></span> Live
        </div>
        <h1 class="an-title">Analytics Dashboard</h1>
        <p class="an-subtitle">{{ $hostel->name }} — {{ now()->isoFormat('MMMM YYYY') }}</p>
    </div>
    <div class="an-hero-right">
        <div class="an-hero-date">{{ now()->format('d M Y') }}</div>
        <div class="an-update-tag" id="an-last-update">Actualisé à {{ now()->format('H:i') }}</div>
    </div>
</div>

{{-- ── TABS NAV ──────────────────────────────────────────────────────── --}}
<div class="an-tabs-wrap" style="margin-top: 4rem;">
    <button class="an-tab-btn {{ $activeTab === 'acquisition' ? 'active' : '' }}"
            data-tab="acquisition" onclick="switchTab('acquisition')">
        <span class="tab-icon">🎯</span> Acquisition
    </button>
    <button class="an-tab-btn {{ $activeTab === 'occupancy' ? 'active' : '' }}"
            data-tab="occupancy" onclick="switchTab('occupancy')">
        <span class="tab-icon">🏨</span> Occupation
    </button>
    <button class="an-tab-btn {{ $activeTab === 'finance' ? 'active' : '' }}"
            data-tab="finance" onclick="switchTab('finance')">
        <span class="tab-icon">💰</span> Finance
    </button>
</div>

{{-- ── PANEL ACQUISITION ─────────────────────────────────────────────── --}}
<div id="panel-acquisition" class="an-panel {{ $activeTab === 'acquisition' ? 'active' : '' }}">
    @php $data = $acquisition; @endphp
    @include('analytics._tab_acquisition')
</div>

{{-- ── PANEL OCCUPANCY ───────────────────────────────────────────────── --}}
<div id="panel-occupancy" class="an-panel {{ $activeTab === 'occupancy' ? 'active' : '' }}">
    @php $data = $occupancy; @endphp
    @include('analytics._tab_occupancy')
</div>

{{-- ── PANEL FINANCE ─────────────────────────────────────────────────── --}}
<div id="panel-finance" class="an-panel {{ $activeTab === 'finance' ? 'active' : '' }}">
    @include('analytics._tab_finance')
</div>

<script>
function switchTab(name) {
    // Hide all panels & deactivate buttons
    document.querySelectorAll('.an-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.an-tab-btn').forEach(b => b.classList.remove('active'));

    // Show target
    document.getElementById('panel-' + name).classList.add('active');
    document.querySelector('[data-tab="' + name + '"]').classList.add('active');

    // Update URL without reload
    const url = new URL(window.location);
    url.searchParams.set('tab', name);
    history.replaceState(null, '', url);

    // Init charts lazily on first reveal
    if (name === 'finance' && window.AnalyticsFinance) {
        setTimeout(() => window.AnalyticsFinance.init(), 80);
    }
    if (name === 'acquisition' && window.AnalyticsAcquisition) {
        setTimeout(() => window.AnalyticsAcquisition.init(), 80);
    }
}

// Init the active tab's charts on page load
document.addEventListener('DOMContentLoaded', function () {
    const active = '{{ $activeTab }}';
    if (active === 'finance' && window.AnalyticsFinance) {
        window.AnalyticsFinance.init();
    }
    if (active === 'acquisition' && window.AnalyticsAcquisition) {
        window.AnalyticsAcquisition.init();
    }
});
</script>

@endsection
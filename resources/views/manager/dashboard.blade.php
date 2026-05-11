@extends('layouts.app')
@section('title', 'Dashboard Manager')
@section('content')

<style>
/* ═══════════════════════════════════════════════════════════════
   MANAGER DASHBOARD — Palette terra/teal/sand
   Structure et data 100% préservés.
   ═══════════════════════════════════════════════════════════════ */

.mgr-wrap { font-family: 'DM Sans', sans-serif; padding-bottom: 2rem; }
.mgr-title { font-family: 'Playfair Display', serif; }

/* ── HERO ── */
.mgr-hero {
    background: linear-gradient(135deg, #1C1C24 0%, #2E3A35 40%, #1B6B6B 100%);
    border-radius: 28px;
    padding: 2.5rem 2.5rem 4.5rem;
    position: relative;
    overflow: hidden;
    margin-bottom: -2.5rem;
}
.mgr-hero::before {
    content: '';
    position: absolute;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(200,96,42,0.22) 0%, transparent 70%);
    top: -150px; right: -100px;
    animation: pulse-glow 4s ease-in-out infinite alternate;
}
.mgr-hero::after {
    content: '';
    position: absolute;
    width: 280px; height: 280px;
    background: radial-gradient(circle, rgba(254,252,249,0.10) 0%, transparent 70%);
    bottom: -60px; left: 25%;
    animation: pulse-glow 3s ease-in-out infinite alternate-reverse;
}
@keyframes pulse-glow {
    from { transform: scale(1); opacity: 0.6; }
    to   { transform: scale(1.3); opacity: 1; }
}

/* ── KPI Cards ── */
.mgr-kpi-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    position: relative;
    z-index: 10;
    margin-bottom: 2rem;
}
.mgr-kpi {
    background: #FEFCF9;
    border-radius: 22px;
    padding: 1.75rem;
    box-shadow: 0 8px 32px rgba(28,28,36,0.08);
    border: 1px solid #DDD6CA;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s;
}
.mgr-kpi:hover { transform: translateY(-6px); box-shadow: 0 20px 48px rgba(28,28,36,0.14); }
.mgr-kpi::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    border-radius: 22px 22px 0 0;
}
.mgr-kpi.terra::before { background: linear-gradient(90deg, #C8602A, #E8A050); }
.mgr-kpi.teal::before  { background: linear-gradient(90deg, #1B6B6B, #4A9A9A); }
.mgr-kpi.sage::before  { background: linear-gradient(90deg, #4A8F6E, #7AB592); }

.mgr-kpi-icon {
    width: 52px; height: 52px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
}
.mgr-kpi.terra .mgr-kpi-icon { background: #FEF3E2; }
.mgr-kpi.teal  .mgr-kpi-icon { background: #E8F4F0; }
.mgr-kpi.sage  .mgr-kpi-icon { background: #D8E9DF; }

.mgr-kpi-num {
    font-family: 'Playfair Display', serif;
    font-size: 3rem;
    font-weight: 700;
    line-height: 1;
    letter-spacing: -1.5px;
}
.mgr-kpi.terra .mgr-kpi-num { color: #C8602A; }
.mgr-kpi.teal  .mgr-kpi-num { color: #1B6B6B; }
.mgr-kpi.sage  .mgr-kpi-num { color: #4A8F6E; }

/* ── Section Card ── */
.mgr-section {
    background: #FEFCF9;
    border-radius: 22px;
    border: 1px solid #DDD6CA;
    box-shadow: 0 4px 16px rgba(28,28,36,0.05);
    overflow: hidden;
}
.mgr-section-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #DDD6CA;
    background: linear-gradient(90deg, #FAF6EF, #F5EFE6);
}

/* ── Room row ── */
.mgr-room {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.25rem;
    border-radius: 14px;
    background: #FAF6EF;
    border: 1px solid #DDD6CA;
    transition: all 0.2s;
    margin-bottom: 0.6rem;
}
.mgr-room:hover {
    border-color: #C8602A;
    background: #FEF3E2;
    transform: translateX(4px);
}
.mgr-room-avatar {
    width: 40px; height: 40px;
    border-radius: 12px;
    background: #FEFCF9;
    border: 1px solid #DDD6CA;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    color: #C8602A;
    box-shadow: 0 1px 3px rgba(28,28,36,0.05);
}

/* ── Stat badge ── */
.mgr-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 12px; border-radius: 99px;
    font-size: 12px; font-weight: 600;
}

/* ── Animations ── */
.mgr-fade {
    opacity: 0;
    transform: translateY(20px);
    animation: mgrFade 0.6s cubic-bezier(0.4,0,0.2,1) forwards;
}
@keyframes mgrFade { to { opacity: 1; transform: translateY(0); } }
.md1 { animation-delay: 0.05s; }
.md2 { animation-delay: 0.15s; }
.md3 { animation-delay: 0.25s; }
</style>

{{-- ── HERO ─────────────────────────────────────────────────── --}}
<div class="mgr-wrap">

    <div class="mgr-hero mgr-fade md1">
        <div style="position:relative; z-index:1;">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
                <span style="font-size:13px; font-weight:600; color:rgba(254,252,249,0.6); letter-spacing:.12em; text-transform:uppercase;">Espace Manager</span>
                <span style="width:32px; height:1px; background:rgba(254,252,249,0.3);"></span>
                <span style="font-size:13px; font-weight:600; color:#F5C896;">{{ now()->format('d M Y') }}</span>
            </div>
            <h1 class="mgr-title" style="font-size:2.5rem; font-weight:700; color:#FEFCF9; line-height:1.1; margin-bottom:10px;">
                Bonjour 👋<br>
                <span style="color:rgba(254,252,249,0.7); font-size:1.5rem; font-style:italic;">{{ $currentManager->name }}</span>
            </h1>
            <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(254,252,249,0.12); backdrop-filter:blur(8px); border:1px solid rgba(254,252,249,0.2); border-radius:99px; padding:6px 16px;">
                <span style="width:8px; height:8px; background:#7AB592; border-radius:50%; animation:pulse-glow 1.5s infinite;"></span>
                <span style="font-size:13px; font-weight:600; color:#FEFCF9;">{{ $managerHostel->name }} · {{ $managerHostel->city }}, {{ $managerHostel->country }}</span>
            </div>
        </div>
    </div>

    {{-- ── KPIs ─────────────────────────────────────────────────── --}}
    <div class="mgr-kpi-grid mgr-fade md2">

        <div class="mgr-kpi terra">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1.25rem;">
                <div>
                    <p style="font-size:12px; font-weight:600; color:#6B6B7A; text-transform:uppercase; letter-spacing:.08em; margin-bottom:4px;">Total chambres</p>
                    <p class="mgr-kpi-num">{{ $stats['total_rooms'] }}</p>
                </div>
                <div class="mgr-kpi-icon">🚪</div>
            </div>
            <span class="mgr-badge" style="background:#FEF3E2; color:#C8602A;">
                <span style="width:8px;height:8px;border-radius:50%;background:#C8602A;"></span>
                Inventaire complet
            </span>
        </div>

        <div class="mgr-kpi sage">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1.25rem;">
                <div>
                    <p style="font-size:12px; font-weight:600; color:#6B6B7A; text-transform:uppercase; letter-spacing:.08em; margin-bottom:4px;">Chambres actives</p>
                    <p class="mgr-kpi-num">{{ $stats['active_rooms'] }}</p>
                </div>
                <div class="mgr-kpi-icon">✅</div>
            </div>
            <span class="mgr-badge" style="background:#D8E9DF; color:#4A8F6E;">
                <span style="width:8px;height:8px;border-radius:50%;background:#4A8F6E;"></span>
                Disponibles
            </span>
        </div>

        <div class="mgr-kpi teal">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1.25rem;">
                <div>
                    <p style="font-size:12px; font-weight:600; color:#6B6B7A; text-transform:uppercase; letter-spacing:.08em; margin-bottom:4px;">Lits désactivés</p>
                    <p class="mgr-kpi-num">{{ $stats['disabled_beds'] }}</p>
                </div>
                <div class="mgr-kpi-icon">🔧</div>
            </div>
            <span class="mgr-badge" style="background:#E8F4F0; color:#1B6B6B;">
                <span style="width:8px;height:8px;border-radius:50%;background:#1B6B6B;"></span>
                Hors service
            </span>
        </div>

    </div>

    {{-- ── Dernières chambres ───────────────────────────────────── --}}
    <div class="mgr-section mgr-fade md3">
        <div class="mgr-section-header">
            <div style="display:flex; align-items:center; gap:10px;">
                <span style="font-size:20px;">📋</span>
                <span class="mgr-title" style="font-size:17px; font-weight:600; color:#2E2E3A;">Dernières chambres</span>
            </div>
            <a href="{{ route('manager.rooms.index') }}" style="font-size:13px; font-weight:600; color:#C8602A; text-decoration:none;">
                Voir tout →
            </a>
        </div>
        <div style="padding:1.25rem;">
            @forelse($stats['rooms'] as $room)
            <div class="mgr-room">
                <div style="display:flex; align-items:center; gap:14px;">
                    <div class="mgr-room-avatar">{{ strtoupper(substr($room->name, 0, 1)) }}</div>
                    <div>
                        <p style="margin:0; font-size:14px; font-weight:600; color:#2E2E3A;">{{ $room->name }}</p>
                        <p style="margin:0; font-size:11px; color:#6B6B7A; font-weight:500; text-transform:uppercase; letter-spacing:.06em;">
                            {{ $room->type === 'private' ? 'Privée' : 'Dortoir' }}
                        </p>
                    </div>
                </div>
                <span class="mgr-badge" style="
                    {{ $room->is_enabled ? 'background:#D8E9DF; color:#4A8F6E;' : 'background:#F5DDD0; color:#A84E20;' }}">
                    <span style="width:8px;height:8px;border-radius:50%;background:{{ $room->is_enabled ? '#4A8F6E' : '#A84E20' }};"></span>
                    {{ $room->is_enabled ? 'Actif' : 'Désactivé' }}
                </span>
            </div>
            @empty
            <div style="text-align:center; padding:3rem 1rem; color:#A0A0B0;">
                <div style="font-size:3rem; opacity:0.4; margin-bottom:1rem;">🛏️</div>
                <p style="font-size:14px; font-weight:500;">Aucune chambre trouvée.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
@extends('layouts.app')
@section('title', 'Dashboard — ' . $activeHostel->name)
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700;0,9..144,900;1,9..144,600;1,9..144,800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

:root {
    --navy:   #0F1F3D;
    --blue:   #1B4FD8;
    --sky:    #38BDF8;
    --orange: #F97316;
    --green:  #10B981;
    --red:    #EF4444;
    --amber:  #F59E0B;
    --card:   #FFFFFF;
    --border: #E8EDF5;
    --muted:  #64748B;
    --bg:     #F0F4FA;
}

.db-wrap { font-family: 'Plus Jakarta Sans', sans-serif; }
.db-title { font-family: 'Fraunces', serif; }

/* ── Animated gradient hero ── */
.hero-gradient {
    background: linear-gradient(135deg, #7C2D12 0%, #C2410C 40%, #F97316 100%);
    border-radius: 28px;
    padding: 2.5rem 2.5rem 5rem;
    position: relative;
    overflow: hidden;
    margin-bottom: -3rem;
}
.hero-gradient::before {
    content: '';
    position: absolute;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(255,200,100,0.3) 0%, transparent 70%);
    top: -150px; right: -100px;
    animation: pulse-glow 4s ease-in-out infinite alternate;
}
.hero-gradient::after {
    content: '';
    position: absolute;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 70%);
    bottom: -80px; left: 30%;
    animation: pulse-glow 3s ease-in-out infinite alternate-reverse;
}
@keyframes pulse-glow {
    from { transform: scale(1); opacity: 0.6; }
    to   { transform: scale(1.3); opacity: 1; }
}

/* ── KPI Cards ── */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    position: relative;
    z-index: 10;
}
.kpi-card {
    background: white;
    border-radius: 20px;
    padding: 1.75rem;
    box-shadow: 0 8px 32px rgba(15,31,61,0.10);
    border: 1px solid rgba(255,255,255,0.9);
    position: relative;
    overflow: hidden;
    transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s;
}
.kpi-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 48px rgba(15,31,61,0.16);
}
.kpi-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    border-radius: 20px 20px 0 0;
}
.kpi-card.blue::before  { background: linear-gradient(90deg, var(--blue), var(--sky)); }
.kpi-card.orange::before { background: linear-gradient(90deg, var(--orange), #FBBF24); }
.kpi-card.green::before  { background: linear-gradient(90deg, var(--green), #34D399); }

.kpi-icon {
    width: 52px; height: 52px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}
.kpi-card.blue  .kpi-icon { background: #EFF6FF; }
.kpi-card.orange .kpi-icon { background: #FFF7ED; }
.kpi-card.green  .kpi-icon { background: #ECFDF5; }

.kpi-num {
    font-family: 'Fraunces', serif;
    font-size: 3.25rem;
    font-weight: 800;
    line-height: 1;
    letter-spacing: -2px;
}
.kpi-card.blue  .kpi-num { color: var(--blue); }
.kpi-card.orange .kpi-num { color: var(--orange); }
.kpi-card.green  .kpi-num { color: var(--green); }

/* ── Section cards ── */
.section-card {
    background: white;
    border-radius: 20px;
    border: 1px solid var(--border);
    box-shadow: 0 4px 16px rgba(15,31,61,0.05);
    overflow: hidden;
}
.section-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(90deg, #F8FAFF, #FAFBFF);
}

/* ── Occupancy ring ── */
.ring-wrap { position: relative; width: 120px; height: 120px; flex-shrink: 0; }
.ring-wrap svg { transform: rotate(-90deg); }
.ring-bg { fill: none; stroke: #E8EDF5; stroke-width: 10; }
.ring-fill { fill: none; stroke-width: 10; stroke-linecap: round; transition: stroke-dashoffset 1.5s cubic-bezier(0.4,0,0.2,1); }
.ring-label {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
}

/* ── Room status bars ── */
.room-bar-track {
    height: 8px; border-radius: 99px;
    background: #E8EDF5;
    overflow: hidden;
    flex: 1;
}
.room-bar-fill {
    height: 100%; border-radius: 99px;
    transition: width 1.2s cubic-bezier(0.4,0,0.2,1);
}

/* ── Stat badge ── */
.stat-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 12px; border-radius: 99px;
    font-size: 12px; font-weight: 600;
}

/* ── Quick actions ── */
.action-btn {
    display: flex; align-items: center; gap: 12px;
    padding: 1rem 1.25rem;
    border-radius: 14px;
    border: 2px solid var(--border);
    background: white;
    transition: all 0.25s;
    text-decoration: none;
    color: var(--navy);
    font-weight: 600;
    font-size: 14px;
}
.action-btn:hover {
    border-color: var(--blue);
    background: #EFF6FF;
    transform: translateX(4px);
    color: var(--blue);
}
.action-btn .ab-icon {
    width: 40px; height: 40px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}

/* ── Animations ── */
.slide-up {
    opacity: 0;
    transform: translateY(24px);
    animation: slideUp 0.6s cubic-bezier(0.4,0,0.2,1) forwards;
}
@keyframes slideUp {
    to { opacity: 1; transform: translateY(0); }
}
.d1 { animation-delay: 0.05s; }
.d2 { animation-delay: 0.12s; }
.d3 { animation-delay: 0.18s; }
.d4 { animation-delay: 0.24s; }
.d5 { animation-delay: 0.30s; }
.d6 { animation-delay: 0.36s; }
.d7 { animation-delay: 0.42s; }

/* ── Dot indicator ── */
.dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }

/* ── Tent card ── */
.tent-half {
    flex: 1; padding: 1.5rem;
    display: flex; flex-direction: column; gap: 12px;
}
.tent-divider { width: 1px; background: var(--border); }
</style>

<div class="db-wrap" style="padding-bottom: 2rem;">

    {{-- ── HERO ─────────────────────────────────────────────────── --}}
    <div class="hero-gradient slide-up d1">
        <div style="position:relative; z-index:1;">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
                <span style="font-size:13px; font-weight:600; color:rgba(255,255,255,0.6); letter-spacing:.12em; text-transform:uppercase;">Dashboard</span>
                <span style="width:32px; height:1px; background:rgba(255,255,255,0.3);"></span>
                <span style="font-size:13px; font-weight:600; color:rgba(255,220,150,0.95);">{{ now()->format('d M Y') }}</span>
            </div>
            <h1 class="db-title" style="font-size:2.75rem; font-weight:800; color:white; line-height:1.1; margin-bottom:10px;">
                Bonjour 👋<br>
                <span style="color:rgba(255,255,255,0.7); font-size:1.75rem;">{{ Auth::guard('owner')->user()->name }}</span>
            </h1>
            <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,0.12); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,0.2); border-radius:99px; padding:6px 16px;">
                <span style="width:8px; height:8px; background:#4ADE80; border-radius:50%; animation:pulse-glow 1.5s infinite;"></span>
                <span style="font-size:13px; font-weight:600; color:white;">{{ $activeHostel->name }}</span>
            </div>
        </div>
    </div>

    {{-- ── KPI CARDS ────────────────────────────────────────────── --}}
    <div class="kpi-grid slide-up d2" style="padding: 0 0.5rem; margin-bottom: 2rem;">

        <div class="kpi-card blue">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1.25rem;">
                <div>
                    <p style="font-size:12px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.08em; margin-bottom:4px;">Réservations actives</p>
                    <p class="kpi-num" id="count-reservations">{{ $activeReservations }}</p>
                </div>
                <div class="kpi-icon">🛏️</div>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span class="stat-badge" style="background:#EFF6FF; color:var(--blue);">
                    <span class="dot" style="background:var(--blue);"></span>
                    En cours
                </span>
            </div>
        </div>

        <div class="kpi-card orange">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1.25rem;">
                <div>
                    <p style="font-size:12px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.08em; margin-bottom:4px;">Lits disponibles</p>
                    <p class="kpi-num" id="count-beds">{{ $availableBeds }}</p>
                </div>
                <div class="kpi-icon">✅</div>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span class="stat-badge" style="background:#FFF7ED; color:var(--orange);">
                    <span class="dot" style="background:var(--orange);"></span>
                    Actifs & libres
                </span>
            </div>
        </div>

        <div class="kpi-card green">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1.25rem;">
                <div>
                    <p style="font-size:12px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.08em; margin-bottom:4px;">Revenus du mois</p>
                    <div style="display:flex; align-items:baseline; gap:8px;">
                        <p class="kpi-num" id="count-revenue">{{ number_format($monthlyRevenue, 0, '.', ' ') }}</p>
                        <span style="font-size:18px; font-weight:700; color:var(--green);">{{ $activeHostel->default_currency ?? 'TND' }}</span>
                    </div>
                </div>
                <div class="kpi-icon">💰</div>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span class="stat-badge" style="background:#ECFDF5; color:var(--green);">
                    <span class="dot" style="background:var(--green);"></span>
                    {{ now()->format('F Y') }}
                </span>
            </div>
        </div>

    </div>

    {{-- ── ROW 2 : Chambres + Taux d'occupation ─────────────────── --}}
    <div style="display:grid; grid-template-columns:1fr 340px; gap:1.25rem; margin-bottom:1.25rem;">

        {{-- Chambres card --}}
        <div class="section-card slide-up d3">
            <div class="section-header">
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="font-size:20px;">🚪</span>
                    <span class="db-title" style="font-size:16px; font-weight:700; color:var(--navy);">État des chambres</span>
                </div>
                @php
                    $total = $privateRoomsCount + $dormitoryRoomsCount + $unavailableRoomsCount;
                @endphp
                <span class="stat-badge" style="background:#F1F5F9; color:var(--muted);">
                    {{ $total }} au total
                </span>
            </div>
            <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1.25rem;">

                {{-- Privées --}}
                <div style="display:flex; align-items:center; gap:1rem;">
                    <div style="width:44px; height:44px; border-radius:14px; background:#EFF6FF; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;">🔑</div>
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <span style="font-size:14px; font-weight:600; color:var(--navy);">Chambres privées</span>
                            <span style="font-size:14px; font-weight:700; color:var(--blue);">{{ $privateRoomsCount }}</span>
                        </div>
                        <div class="room-bar-track">
                            <div class="room-bar-fill" id="bar-private"
                                 style="width:0%; background:linear-gradient(90deg,var(--blue),var(--sky));"
                                 data-target="{{ $total > 0 ? round($privateRoomsCount/$total*100) : 0 }}"></div>
                        </div>
                        <span style="font-size:11px; color:var(--muted); margin-top:3px; display:block;">
                            {{ $total > 0 ? round($privateRoomsCount/$total*100) : 0 }}% du parc · Actives & disponibles
                        </span>
                    </div>
                </div>

                {{-- Dortoirs --}}
                <div style="display:flex; align-items:center; gap:1rem;">
                    <div style="width:44px; height:44px; border-radius:14px; background:#F0FDF4; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;">🏨</div>
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <span style="font-size:14px; font-weight:600; color:var(--navy);">Dortoirs</span>
                            <span style="font-size:14px; font-weight:700; color:var(--green);">{{ $dormitoryRoomsCount }}</span>
                        </div>
                        <div class="room-bar-track">
                            <div class="room-bar-fill" id="bar-dorm"
                                 style="width:0%; background:linear-gradient(90deg,var(--green),#34D399);"
                                 data-target="{{ $total > 0 ? round($dormitoryRoomsCount/$total*100) : 0 }}"></div>
                        </div>
                        <span style="font-size:11px; color:var(--muted); margin-top:3px; display:block;">
                            {{ $total > 0 ? round($dormitoryRoomsCount/$total*100) : 0 }}% du parc · Actifs & disponibles
                        </span>
                    </div>
                </div>

                {{-- Indisponibles --}}
                <div style="display:flex; align-items:center; gap:1rem;">
                    <div style="width:44px; height:44px; border-radius:14px; background:#FFF1F2; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;">🚫</div>
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <span style="font-size:14px; font-weight:600; color:var(--navy);">Indisponibles</span>
                            <span style="font-size:14px; font-weight:700; color:var(--red);">{{ $unavailableRoomsCount }}</span>
                        </div>
                        <div class="room-bar-track">
                            <div class="room-bar-fill" id="bar-unavail"
                                 style="width:0%; background:linear-gradient(90deg,var(--red),#F87171);"
                                 data-target="{{ $total > 0 ? round($unavailableRoomsCount/$total*100) : 0 }}"></div>
                        </div>
                        <span style="font-size:11px; color:var(--muted); margin-top:3px; display:block;">
                            {{ $total > 0 ? round($unavailableRoomsCount/$total*100) : 0 }}% du parc
                            @if($blockedRoomIds->count() > 0)
                                · dont {{ $blockedRoomIds->count() }} bloquée{{ $blockedRoomIds->count()>1?'s':'' }}
                            @endif
                        </span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Taux d'occupation --}}
        <div class="section-card slide-up d4" style="display:flex; flex-direction:column;">
            <div class="section-header">
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="font-size:20px;">📊</span>
                    <span class="db-title" style="font-size:16px; font-weight:700; color:var(--navy);">Occupation</span>
                </div>
            </div>
            <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:2rem 1.5rem; gap:1.5rem;">
                @php
                    $totalRooms = $privateRoomsCount + $dormitoryRoomsCount + $unavailableRoomsCount;
                    $availableRooms = $privateRoomsCount + $dormitoryRoomsCount;
                    $occupancyPct = $totalRooms > 0 ? round(($totalRooms - $availableRooms) / $totalRooms * 100) : 0;
                    $circumference = 2 * M_PI * 50;
                    $offset = $circumference - ($occupancyPct / 100) * $circumference;
                @endphp
                <div class="ring-wrap" style="width:140px; height:140px;">
                    <svg viewBox="0 0 120 120" width="140" height="140">
                        <circle class="ring-bg" cx="60" cy="60" r="50"/>
                        <circle class="ring-fill" cx="60" cy="60" r="50"
                            stroke="{{ $occupancyPct > 70 ? '#EF4444' : ($occupancyPct > 40 ? '#F97316' : '#10B981') }}"
                            stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $circumference }}"
                            id="ring-occ"
                            data-target="{{ $offset }}"/>
                    </svg>
                    <div class="ring-label">
                        <span class="db-title" style="font-size:2rem; font-weight:800; color:var(--navy); line-height:1;">{{ $occupancyPct }}<span style="font-size:1rem;">%</span></span>
                        <span style="font-size:11px; color:var(--muted); font-weight:500;">occupé</span>
                    </div>
                </div>
                <div style="width:100%; display:flex; flex-direction:column; gap:8px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="display:flex; align-items:center; gap:6px; font-size:13px; color:var(--muted); font-weight:500;">
                            <span class="dot" style="background:var(--green);"></span>Disponibles
                        </span>
                        <span style="font-size:14px; font-weight:700; color:var(--navy);">{{ $availableRooms }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="display:flex; align-items:center; gap:6px; font-size:13px; color:var(--muted); font-weight:500;">
                            <span class="dot" style="background:var(--red);"></span>Indisponibles
                        </span>
                        <span style="font-size:14px; font-weight:700; color:var(--navy);">{{ $unavailableRoomsCount }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="display:flex; align-items:center; gap:6px; font-size:13px; color:var(--muted); font-weight:500;">
                            <span class="dot" style="background:var(--blue);"></span>Total
                        </span>
                        <span style="font-size:14px; font-weight:700; color:var(--navy);">{{ $totalRooms }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── ROW 3 : Tentes + Actions rapides ─────────────────────── --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">

        {{-- Espaces tentes --}}
        <div class="section-card slide-up d5">
            <div class="section-header">
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="font-size:20px;">⛺</span>
                    <span class="db-title" style="font-size:16px; font-weight:700; color:var(--navy);">Espaces tentes</span>
                </div>
                @php $totalTents = $activeTentSpacesCount + $inactiveTentSpacesCount; @endphp
                <span class="stat-badge" style="background:#F1F5F9; color:var(--muted);">{{ $totalTents }} total</span>
            </div>
            <div style="display:flex;">
                <div class="tent-half">
                    <div style="width:48px; height:48px; border-radius:16px; background:#ECFDF5; display:flex; align-items:center; justify-content:center; font-size:22px;">✅</div>
                    <div>
                        <p style="font-size:11px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.08em; margin-bottom:4px;">Actifs</p>
                        <p class="db-title" style="font-size:2.5rem; font-weight:800; color:var(--green); line-height:1;">{{ $activeTentSpacesCount }}</p>
                        <p style="font-size:12px; color:var(--muted);">Disponibles à la réservation</p>
                    </div>
                    <span class="stat-badge" style="background:#ECFDF5; color:var(--green); align-self:flex-start;">
                        <span class="dot" style="background:var(--green);"></span>
                        {{ $totalTents > 0 ? round($activeTentSpacesCount/$totalTents*100) : 0 }}% actifs
                    </span>
                </div>
                <div class="tent-divider"></div>
                <div class="tent-half">
                    <div style="width:48px; height:48px; border-radius:16px; background:#FFF7ED; display:flex; align-items:center; justify-content:center; font-size:22px;">⛔</div>
                    <div>
                        <p style="font-size:11px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.08em; margin-bottom:4px;">Inactifs</p>
                        <p class="db-title" style="font-size:2.5rem; font-weight:800; color:var(--amber); line-height:1;">{{ $inactiveTentSpacesCount }}</p>
                        <p style="font-size:12px; color:var(--muted);">Non disponibles</p>
                    </div>
                    <span class="stat-badge" style="background:#FFF7ED; color:var(--amber); align-self:flex-start;">
                        <span class="dot" style="background:var(--amber);"></span>
                        {{ $totalTents > 0 ? round($inactiveTentSpacesCount/$totalTents*100) : 0 }}% inactifs
                    </span>
                </div>
            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="section-card slide-up d6">
            <div class="section-header">
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="font-size:20px;">⚡</span>
                    <span class="db-title" style="font-size:16px; font-weight:700; color:var(--navy);">Actions rapides</span>
                </div>
            </div>
            <div style="padding:1.25rem; display:flex; flex-direction:column; gap:10px;">
                <a href="{{ route('reservations.index') }}" class="action-btn">
                    <div class="ab-icon" style="background:#EFF6FF;">📅</div>
                    <div>
                        <p style="margin:0; font-size:14px; font-weight:600;">Gérer les réservations</p>
                        <p style="margin:0; font-size:12px; color:var(--muted); font-weight:400;">Voir & créer des réservations</p>
                    </div>
                    <svg style="margin-left:auto; flex-shrink:0; opacity:.4;" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="{{ route('rooms.index') }}" class="action-btn">
                    <div class="ab-icon" style="background:#F0FDF4;">🚪</div>
                    <div>
                        <p style="margin:0; font-size:14px; font-weight:600;">Gérer les chambres</p>
                        <p style="margin:0; font-size:12px; color:var(--muted); font-weight:400;">Activer, modifier, bloquer</p>
                    </div>
                    <svg style="margin-left:auto; flex-shrink:0; opacity:.4;" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="{{ route('managers.index') }}" class="action-btn">
                    <div class="ab-icon" style="background:#FFF7ED;">👥</div>
                    <div>
                        <p style="margin:0; font-size:14px; font-weight:600;">Gérer l'équipe</p>
                        <p style="margin:0; font-size:12px; color:var(--muted); font-weight:400;">Managers, staff, financiers</p>
                    </div>
                    <svg style="margin-left:auto; flex-shrink:0; opacity:.4;" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="{{ route('exchange-rates.index') }}" class="action-btn">
                    <div class="ab-icon" style="background:#FFF1F2;">💱</div>
                    <div>
                        <p style="margin:0; font-size:14px; font-weight:600;">Taux de change</p>
                        <p style="margin:0; font-size:12px; color:var(--muted); font-weight:400;">EUR, USD → TND</p>
                    </div>
                    <svg style="margin-left:auto; flex-shrink:0; opacity:.4;" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Animated progress bars ──────────────────────────────────
    setTimeout(function () {
        document.querySelectorAll('.room-bar-fill[data-target]').forEach(function (bar) {
            bar.style.width = bar.dataset.target + '%';
        });
    }, 400);

    // ── Animated ring ───────────────────────────────────────────
    setTimeout(function () {
        var ring = document.getElementById('ring-occ');
        if (ring) ring.style.strokeDashoffset = ring.dataset.target;
    }, 500);

    // ── Animated counters ───────────────────────────────────────
    function animateCounter(el, end, duration) {
        var start = 0;
        var step  = end / (duration / 16);
        var timer = setInterval(function () {
            start += step;
            if (start >= end) { start = end; clearInterval(timer); }
            el.textContent = Math.floor(start).toLocaleString('fr-FR');
        }, 16);
    }

    var els = [
        { id: 'count-reservations', val: {{ $activeReservations }} },
        { id: 'count-beds',         val: {{ $availableBeds }} },
        { id: 'count-revenue',      val: {{ $monthlyRevenue }} },
    ];
    els.forEach(function (item) {
        var el = document.getElementById(item.id);
        if (el && item.val > 0) animateCounter(el, item.val, 1200);
    });
});
</script>

@endsection
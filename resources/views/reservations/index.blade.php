@extends('layouts.app')
@section('title', 'Réservations — ' . $hostel->name)
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700;0,9..144,900;1,9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

:root {
    --navy:   #0F1F3D;
    --blue:   #1B4FD8;
    --orange: #F97316;
    --green:  #10B981;
    --red:    #EF4444;
    --amber:  #F59E0B;
    --muted:  #64748B;
    --border: #E8EDF5;
}
.rv-wrap { font-family: 'Plus Jakarta Sans', sans-serif; }
.rv-title { font-family: 'Fraunces', serif; }

/* ── Hero ── */
.rv-hero {
    background: linear-gradient(135deg, #7C2D12 0%, #C2410C 45%, #F97316 100%);
    border-radius: 24px;
    padding: 2rem 2.25rem 4.5rem;
    position: relative;
    overflow: hidden;
    margin-bottom: -2.75rem;
}
.rv-hero::before {
    content:'';
    position:absolute;
    width:350px;height:350px;
    background:radial-gradient(circle,rgba(255,220,100,.22) 0%,transparent 70%);
    top:-100px;right:-60px;
    animation:orb 5s ease-in-out infinite alternate;
}
@keyframes orb { from{transform:scale(1)} to{transform:scale(1.3) translate(10px,10px)} }

/* ── Stat cards ── */
.rv-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    position: relative;
    z-index: 10;
    margin-bottom: 1.5rem;
}
.rv-stat {
    background: white;
    border-radius: 18px;
    padding: 1.4rem 1.5rem;
    box-shadow: 0 6px 24px rgba(15,31,61,.09);
    border: 1px solid rgba(255,255,255,.9);
    position: relative;
    overflow: hidden;
    transition: transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s;
}
.rv-stat:hover { transform: translateY(-4px); box-shadow: 0 14px 36px rgba(15,31,61,.14); }
.rv-stat::before {
    content:'';
    position:absolute;
    top:0;left:0;right:0;height:3px;border-radius:18px 18px 0 0;
}
.rv-stat.s-blue::before   { background:linear-gradient(90deg,var(--blue),#60A5FA); }
.rv-stat.s-red::before    { background:linear-gradient(90deg,var(--red),#F87171); }
.rv-stat.s-amber::before  { background:linear-gradient(90deg,var(--amber),#FCD34D); }
.rv-stat.s-green::before  { background:linear-gradient(90deg,var(--green),#34D399); }

.rv-stat-label { font-size:11px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--muted); margin-bottom:6px; }
.rv-stat-num { font-family:'Fraunces',serif; font-size:2.6rem; font-weight:900; line-height:1; letter-spacing:-1px; }
.rv-stat.s-blue  .rv-stat-num { color:var(--blue); }
.rv-stat.s-red   .rv-stat-num { color:var(--red); }
.rv-stat.s-amber .rv-stat-num { color:var(--amber); }
.rv-stat.s-green .rv-stat-num { color:var(--green); }
.rv-stat-sub { font-size:12px; color:var(--muted); margin-top:4px; }

/* ── Table section ── */
.rv-section {
    background:white;
    border-radius:20px;
    border:1px solid var(--border);
    box-shadow:0 4px 16px rgba(15,31,61,.05);
    overflow:hidden;
    margin-bottom:1.25rem;
}
.rv-section-head {
    display:flex;align-items:center;justify-content:space-between;
    padding:1.1rem 1.5rem;
    background:linear-gradient(90deg,#F8FAFF,#FAFBFF);
    border-bottom:1px solid var(--border);
}
.rv-section-title { font-family:'Fraunces',serif; font-size:16px; font-weight:700; color:var(--navy); display:flex;align-items:center;gap:8px; }

/* ── Table ── */
.rv-table { width:100%; border-collapse:collapse; }
.rv-table thead tr {
    background:linear-gradient(90deg,#F8FAFF,#F0F4FF);
}
.rv-table th {
    padding:10px 14px;
    font-size:10.5px;
    font-weight:700;
    letter-spacing:.1em;
    text-transform:uppercase;
    color:var(--muted);
    text-align:left;
    border-bottom:1px solid var(--border);
    white-space:nowrap;
}
.rv-table td {
    padding:12px 14px;
    font-size:13.5px;
    color:#334155;
    border-bottom:1px solid #F1F5F9;
    vertical-align:middle;
}
.rv-table tbody tr {
    transition:background .15s;
}
.rv-table tbody tr:hover { background:#F8FAFF; }
.rv-table tbody tr:last-child td { border-bottom:none; }

/* Active row pulse */
.rv-table tbody tr.is-active { background:linear-gradient(90deg,rgba(249,115,22,.04),rgba(249,115,22,.02)); }
.rv-table tbody tr.is-active:hover { background:linear-gradient(90deg,rgba(249,115,22,.08),rgba(249,115,22,.04)); }

/* Guest avatar */
.rv-avatar {
    width:34px;height:34px;
    border-radius:10px;
    background:linear-gradient(135deg,var(--blue),#60A5FA);
    color:white;
    font-size:13px;font-weight:700;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;
}

/* Status badge */
.rv-badge {
    display:inline-flex;align-items:center;gap:5px;
    padding:4px 10px;border-radius:99px;
    font-size:11.5px;font-weight:600;
    white-space:nowrap;
}
.rv-badge.confirmed { background:#ECFDF5;color:#059669; }
.rv-badge.pending   { background:#FFF7ED;color:#D97706; }
.rv-badge.cancelled { background:#FEF2F2;color:#DC2626; }
.rv-badge .bdot { width:6px;height:6px;border-radius:50%; }
.rv-badge.confirmed .bdot { background:#10B981; }
.rv-badge.pending   .bdot { background:#F59E0B; }
.rv-badge.cancelled .bdot { background:#EF4444; }

/* Actions */
.rv-act-btn {
    display:inline-flex;align-items:center;gap:5px;
    padding:5px 10px;border-radius:8px;
    font-size:12px;font-weight:600;
    transition:all .2s;cursor:pointer;text-decoration:none;
    border:1px solid;
}
.rv-act-btn.edit { color:#1B4FD8;background:#EFF6FF;border-color:#BFDBFE; }
.rv-act-btn.edit:hover { background:#DBEAFE;transform:translateY(-1px); }
.rv-act-btn.del  { color:#DC2626;background:#FEF2F2;border-color:#FECACA; }
.rv-act-btn.del:hover  { background:#FEE2E2;transform:translateY(-1px); }

/* ── Calendrier ── */
.cal-grid {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:1rem;
    padding:1.25rem;
}
.cal-month {
    background:white;
    border:1px solid var(--border);
    border-radius:14px;
    overflow:hidden;
    transition:box-shadow .25s,transform .25s;
}
.cal-month:hover { box-shadow:0 8px 24px rgba(15,31,61,.1);transform:translateY(-2px); }
.cal-month-name {
    background:linear-gradient(135deg,var(--navy),#1B4FD8);
    color:white;
    font-size:10.5px;font-weight:700;
    text-align:center;padding:8px;
    text-transform:uppercase;letter-spacing:.1em;
    font-family:'Plus Jakarta Sans',sans-serif;
}
.cal-days-header {
    display:grid;grid-template-columns:repeat(7,1fr);
    background:#F8FAFF;
    border-bottom:1px solid var(--border);
}
.cal-day-name {
    text-align:center;font-size:8.5px;font-weight:700;
    color:var(--muted);padding:5px 2px;letter-spacing:.05em;
}
.cal-days { display:grid;grid-template-columns:repeat(7,1fr);gap:1px;padding:5px; }
.cal-day {
    aspect-ratio:1;
    display:flex;align-items:center;justify-content:center;
    font-size:10px;font-weight:500;
    border-radius:6px;
    cursor:default;
    transition:transform .15s;
    color:#475569;
}
.cal-day:hover { transform:scale(1.2); }
.cal-day.today {
    outline:2px solid var(--blue);
    outline-offset:1px;
    font-weight:700;color:var(--blue);
}
.cal-day.confirmed { background:linear-gradient(135deg,#EF4444,#F87171);color:white;font-weight:700; }
.cal-day.pending   { background:linear-gradient(135deg,#10B981,#34D399);color:white;font-weight:700; }
.cal-day.free      { background:#FEF9C3;color:#92400E; }

/* ── Year nav ── */
.year-nav {
    display:flex;align-items:center;gap:8px;
}
.year-nav a {
    width:28px;height:28px;border-radius:8px;
    display:flex;align-items:center;justify-content:center;
    background:#F1F5F9;color:var(--muted);
    transition:all .2s;text-decoration:none;
}
.year-nav a:hover { background:var(--navy);color:white;transform:scale(1.1); }
.year-nav span { font-weight:700;color:var(--navy);font-size:15px;min-width:44px;text-align:center; }

/* ── Legend ── */
.cal-legend { display:flex;align-items:center;gap:14px;flex-wrap:wrap; }
.cal-legend-item { display:flex;align-items:center;gap:6px;font-size:12px;font-weight:500;color:var(--muted); }
.cal-legend-dot { width:10px;height:10px;border-radius:3px; }

/* ── Animations ── */
.slide-up { opacity:0;transform:translateY(20px);animation:slideUp .5s cubic-bezier(.4,0,.2,1) forwards; }
@keyframes slideUp { to{opacity:1;transform:translateY(0)} }
.d1{animation-delay:.05s} .d2{animation-delay:.1s} .d3{animation-delay:.15s}
.d4{animation-delay:.2s}  .d5{animation-delay:.25s}

/* ── Empty state ── */
.rv-empty {
    padding:3.5rem;text-align:center;color:var(--muted);
    display:flex;flex-direction:column;align-items:center;gap:12px;
}
.rv-empty-icon { font-size:48px;opacity:.4; }

/* ── Action bar ── */
.rv-actions { display:flex;align-items:center;gap:10px;flex-wrap:wrap; }
.rv-btn {
    display:inline-flex;align-items:center;gap:7px;
    padding:8px 16px;border-radius:10px;
    font-size:13px;font-weight:600;
    transition:all .25s;text-decoration:none;cursor:pointer;
}
.rv-btn.primary { color:white;border:none; }
.rv-btn.outline { color:var(--navy);background:white;border:1.5px solid var(--border); }
.rv-btn.outline:hover { border-color:var(--blue);color:var(--blue);background:#EFF6FF; }
</style>

<div class="rv-wrap" style="padding-bottom:2rem;">

    {{-- ── HERO ── --}}
    <div class="rv-hero slide-up d1">
        <div style="position:relative;z-index:1;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <span style="font-size:12px;font-weight:700;color:rgba(255,255,255,.55);letter-spacing:.12em;text-transform:uppercase;">Réservations</span>
                <span style="width:28px;height:1px;background:rgba(255,255,255,.25);"></span>
                <span style="font-size:12px;font-weight:600;color:rgba(255,220,150,.9);">{{ $hostel->name }}</span>
                @php
                    $roleColors = ['owner'=>'rgba(253,186,116,1)','manager'=>'rgba(147,197,253,1)','staff'=>'rgba(110,231,183,1)','financial'=>'rgba(252,211,77,1)'];
                    $roleLabels = ['owner'=>'Propriétaire','manager'=>'Manager','staff'=>'Staff','financial'=>'Financier'];
                    $rc = $roleColors[$role] ?? 'rgba(255,255,255,.6)';
                    $rl = $roleLabels[$role] ?? $role;
                @endphp
                <span style="margin-left:4px;font-size:11px;font-weight:700;color:{{ $rc }};background:rgba(255,255,255,.1);padding:2px 10px;border-radius:99px;border:1px solid rgba(255,255,255,.15);">
                    {{ $rl }}
                </span>
            </div>
            <h1 class="rv-title" style="font-size:2.5rem;font-weight:900;color:white;line-height:1.05;margin-bottom:10px;">
                Gestion des réservations
            </h1>
            <div class="rv-actions" style="margin-top:1.25rem;">
                <a href="#rv-table" class="rv-btn outline" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2);color:white;">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Liste
                </a>
                <a href="#calendar-section" class="rv-btn outline" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2);color:white;">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Calendrier
                </a>
                @if($canCreate)
                    <a href="{{ route($routes['create']) }}" class="rv-btn primary"
                       style="background:white;color:#C2410C;"
                       onmouseover="this.style.background='#FFF7ED'"
                       onmouseout="this.style.background='white'">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Créer une réservation
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ── STATS ── --}}
    <div class="rv-stats slide-up d2">
        <div class="rv-stat s-blue">
            <div class="rv-stat-label">Total</div>
            <div class="rv-stat-num">{{ $stats['total'] }}</div>
            <div class="rv-stat-sub">réservations</div>
        </div>
        <div class="rv-stat s-red">
            <div class="rv-stat-label">Confirmées</div>
            <div class="rv-stat-num">{{ $stats['confirmed'] }}</div>
            <div class="rv-stat-sub">réservations</div>
        </div>
        <div class="rv-stat s-amber">
            <div class="rv-stat-label">En attente</div>
            <div class="rv-stat-num">{{ $stats['pending'] }}</div>
            <div class="rv-stat-sub">réservations</div>
        </div>
        <div class="rv-stat s-green">
            <div class="rv-stat-label">Revenus</div>
            <div class="rv-stat-num" style="font-size:2rem;">{{ number_format($stats['revenue'], 0, '.', ' ') }}</div>
            <div class="rv-stat-sub">TND (hors annulées)</div>
        </div>
    </div>

    @if($role === 'financial')
        <div class="slide-up d2" style="background:linear-gradient(90deg,#FFF7ED,#FFFBEB);border:1px solid #FED7AA;border-radius:14px;padding:14px 18px;display:flex;align-items:center;gap:12px;margin-bottom:1.25rem;">
            <span style="font-size:22px;">ℹ️</span>
            <div>
                <p style="font-size:13.5px;font-weight:700;color:#92400E;margin:0;">Accès lecture seule</p>
                <p style="font-size:12.5px;color:#B45309;margin:0;">En tant que Financier, vous consultez uniquement sans pouvoir créer ou modifier.</p>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div style="background:#ECFDF5;border:1px solid #A7F3D0;border-radius:14px;padding:12px 18px;display:flex;align-items:center;gap:10px;margin-bottom:1.25rem;" class="slide-up d2">
            <span style="font-size:18px;">✅</span>
            <span style="font-size:13.5px;font-weight:600;color:#065F46;">{{ session('success') }}</span>
        </div>
    @endif

    {{-- ── TABLE ── --}}
    <div class="rv-section slide-up d3" id="rv-table">
        <div class="rv-section-head">
            <div class="rv-section-title">
                <span>📋</span>
                Réservations {{ $year }}
            </div>
            <div class="year-nav">
                <a href="{{ route($routes['index'], ['year' => $year - 1]) }}">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <span>{{ $year }}</span>
                <a href="{{ route($routes['index'], ['year' => $year + 1]) }}">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        @if($reservations->isEmpty())
            <div class="rv-empty">
                <div class="rv-empty-icon">📅</div>
                <p style="font-family:'Fraunces',serif;font-size:18px;font-weight:700;color:#1E293B;margin:0;">Aucune réservation pour {{ $year }}</p>
                <p style="font-size:13px;color:var(--muted);margin:0;">Commencez par créer votre première réservation.</p>
                @if($canCreate)
                    <a href="{{ route($routes['create']) }}" class="rv-btn primary" style="background:var(--orange);margin-top:8px;">
                        + Créer une réservation
                    </a>
                @endif
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="rv-table">
                    <thead>
                        <tr>
                            <th>Guest principal</th>
                            <th>Arrivée</th>
                            <th>Départ</th>
                            <th>Nuits</th>
                            <th>Guests</th>
                            <th>Source</th>
                            <th>Montant TND</th>
                            <th>Statut</th>
                            <th>Ajouté par</th>
                            @if($canEdit)<th>Actions</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $res)
                            @php
                                $today    = now()->toDateString();
                                $isActive = $res->start_date->toDateString() <= $today && $res->end_date->toDateString() > $today;
                                $initial  = strtoupper(mb_substr($res->mainGuest?->first_name ?? '?', 0, 1));
                                $colors   = ['#1B4FD8','#059669','#D97706','#7C3AED','#DC2626'];
                                $bg       = $colors[crc32($initial) % count($colors)];
                            @endphp
                            <tr class="{{ $isActive ? 'is-active' : '' }}">
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        @if($isActive)
                                            <div style="position:relative;">
                                                <div class="rv-avatar" style="background:{{ $bg }};">{{ $initial }}</div>
                                                <span style="position:absolute;top:-2px;right:-2px;width:8px;height:8px;background:#10B981;border-radius:50%;border:1.5px solid white;"></span>
                                            </div>
                                        @else
                                            <div class="rv-avatar" style="background:{{ $bg }};">{{ $initial }}</div>
                                        @endif
                                        <div>
                                            <p style="margin:0;font-weight:600;font-size:13.5px;color:#0F172A;">
                                                {{ $res->mainGuest?->first_name }} {{ $res->mainGuest?->last_name }}
                                            </p>
                                            <p style="margin:0;font-size:11.5px;color:var(--muted);">
                                                {{ $res->mainGuest?->country?->name ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td style="white-space:nowrap;font-weight:500;">{{ $res->start_date->format('d/m/Y') }}</td>
                                <td style="white-space:nowrap;font-weight:500;">{{ $res->end_date->format('d/m/Y') }}</td>
                                <td><span style="background:#F1F5F9;padding:3px 9px;border-radius:6px;font-weight:600;font-size:12.5px;">{{ $res->nights }}n</span></td>
                                <td style="text-align:center;font-weight:600;">{{ $res->total_guests }}</td>
                                <td><span style="background:#F8FAFC;border:1px solid var(--border);padding:3px 9px;border-radius:6px;font-size:12px;font-weight:500;">{{ $res->source ?? '—' }}</span></td>
                                <td style="font-weight:700;color:#0F172A;font-family:'Fraunces',serif;font-size:15px;">{{ number_format($res->total_price_tnd, 3) }}</td>
                                <td>
                                    @if($res->status === 'confirmed')
                                        <span class="rv-badge confirmed"><span class="bdot"></span>Confirmé</span>
                                    @elseif($res->status === 'pending')
                                        <span class="rv-badge pending"><span class="bdot"></span>En attente</span>
                                    @else
                                        <span class="rv-badge cancelled"><span class="bdot"></span>{{ ucfirst($res->status) }}</span>
                                    @endif
                                </td>
                                <td style="font-size:12.5px;color:var(--muted);">{{ $res->created_by ?? '—' }}</td>
                                @if($canEdit)
                                <td>
                                    <div style="display:flex;gap:6px;">
                                        <a href="{{ route($routes['edit'], $res->id) }}" class="rv-act-btn edit">
                                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Modifier
                                        </a>
                                        <button type="button" class="rv-act-btn del"
                                                onclick="openDeleteModal({{ $res->id }}, '{{ addslashes($res->mainGuest?->first_name . ' ' . $res->mainGuest?->last_name) }}', '{{ $res->start_date->format('d/m/Y') }}', '{{ $res->end_date->format('d/m/Y') }}')">
                                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ── CALENDRIER ── --}}
    <div class="rv-section slide-up d4" id="calendar-section">
        <div class="rv-section-head">
            <div class="rv-section-title">
                <span>🗓️</span>
                Calendrier {{ $year }}
            </div>
            <div class="cal-legend">
                <div class="cal-legend-item">
                    <div class="cal-legend-dot" style="background:linear-gradient(135deg,#EF4444,#F87171);"></div>Confirmé
                </div>
                <div class="cal-legend-item">
                    <div class="cal-legend-dot" style="background:linear-gradient(135deg,#10B981,#34D399);"></div>En attente
                </div>
                <div class="cal-legend-item">
                    <div class="cal-legend-dot" style="background:#FEF9C3;border:1px solid #FDE68A;"></div>Libre
                </div>
            </div>
        </div>
        <div class="cal-grid" id="annual-calendar"></div>
    </div>
</div>

{{-- ── DELETE MODAL ── --}}
<div id="delete-modal"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(15,31,61,0.6);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:16px;">
    <div style="background:white;border-radius:20px;box-shadow:0 32px 64px rgba(15,31,61,0.25);width:100%;max-width:420px;display:flex;flex-direction:column;max-height:90vh;font-family:'Plus Jakarta Sans',sans-serif;">
        <div style="display:flex;align-items:center;gap:12px;padding:18px 20px;border-bottom:1px solid #FEE2E2;background:linear-gradient(90deg,#FFF1F2,#FFF5F5);border-radius:20px 20px 0 0;flex-shrink:0;">
            <div style="width:42px;height:42px;border-radius:12px;background:#FEE2E2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#DC2626" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div>
                <p style="font-family:'Fraunces',serif;font-weight:700;color:#0F172A;font-size:16px;margin:0;">Supprimer la réservation</p>
                <p style="font-size:12px;color:#DC2626;margin:0;font-weight:500;">Cette action est irréversible</p>
            </div>
        </div>
        <div style="padding:20px;overflow-y:auto;flex:1;">
            <p style="font-size:14px;color:#64748B;margin:0 0 4px;">Réservation de :</p>
            <p style="font-size:15px;font-weight:700;color:#0F172A;margin:0 0 4px;" id="modal-guest-name"></p>
            <p style="font-size:13px;color:#94A3B8;margin:0 0 16px;" id="modal-dates"></p>
            <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:10px;padding:10px 14px;font-size:12.5px;color:#92400E;margin-bottom:16px;">
                ⚠️ Tous les guests et affectations liés seront également supprimés.
            </div>
            <label style="display:block;font-size:11px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">
                Mot de passe de confirmation <span style="color:#EF4444;">*</span>
            </label>
            <input type="password" id="delete-password"
                   style="width:100%;border:1.5px solid #E2E8F0;border-radius:10px;padding:10px 14px;font-size:14px;outline:none;box-sizing:border-box;font-family:'Plus Jakarta Sans',sans-serif;transition:border-color .2s;"
                   placeholder="Votre mot de passe"
                   onfocus="this.style.borderColor='#EF4444'" onblur="this.style.borderColor='#E2E8F0'">
            <p id="delete-pwd-error" style="display:none;font-size:12px;color:#EF4444;margin-top:6px;"></p>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:16px 20px;border-top:1px solid #F1F5F9;background:#FAFAFA;border-radius:0 0 20px 20px;flex-shrink:0;">
            <button onclick="closeDeleteModal()"
                    style="padding:10px;font-size:14px;font-weight:600;color:#374151;background:white;border:1.5px solid #E2E8F0;border-radius:10px;cursor:pointer;transition:all .2s;font-family:'Plus Jakarta Sans',sans-serif;"
                    onmouseover="this.style.borderColor='#CBD5E1'" onmouseout="this.style.borderColor='#E2E8F0'">
                Annuler
            </button>
            <button onclick="submitDelete()"
                    style="display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;font-size:14px;font-weight:600;color:white;background:#EF4444;border:none;border-radius:10px;cursor:pointer;transition:background .2s;font-family:'Plus Jakarta Sans',sans-serif;"
                    onmouseover="this.style.background='#DC2626'" onmouseout="this.style.background='#EF4444'">
                🗑️ Supprimer
            </button>
        </div>
    </div>
</div>

<form id="delete-form" method="POST" style="display:none">
    @csrf @method('DELETE')
    <input type="hidden" name="password" id="delete-form-password">
</form>

<script>
const CALENDAR_YEAR = {{ $year }};
const CALENDAR_DAYS = @json($calendarDays);
const TODAY_STR     = '{{ now()->format("Y-m-d") }}';
var currentDeleteId = null;

function openDeleteModal(id, name, s, e) {
    currentDeleteId = id;
    document.getElementById('modal-guest-name').textContent = name || '—';
    document.getElementById('modal-dates').textContent = 'Du ' + s + ' au ' + e;
    document.getElementById('delete-password').value = '';
    document.getElementById('delete-pwd-error').style.display = 'none';
    document.getElementById('delete-modal').style.display = 'flex';
    setTimeout(function(){ document.getElementById('delete-password').focus(); }, 100);
}
function closeDeleteModal() {
    currentDeleteId = null;
    document.getElementById('delete-modal').style.display = 'none';
}
function submitDelete() {
    var pwd = document.getElementById('delete-password').value;
    var err = document.getElementById('delete-pwd-error');
    if (!pwd || pwd.length < 4) { err.textContent = 'Mot de passe requis.'; err.style.display = 'block'; return; }
    err.style.display = 'none';
    var form = document.getElementById('delete-form');
    form.action = '{{ route($routes["destroy"] ?? $routes["index"], ["id" => "__ID__"]) }}'.replace('__ID__', currentDeleteId);
    document.getElementById('delete-form-password').value = pwd;
    form.submit();
}
document.getElementById('delete-modal').addEventListener('click', function(e){ if(e.target===this) closeDeleteModal(); });
document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeDeleteModal(); });

// ── Calendrier ───────────────────────────────────────────────────────────────
(function(){
    var months   = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
    var dayNames = ['Lu','Ma','Me','Je','Ve','Sa','Di'];
    var allDates = Object.keys(CALENDAR_DAYS).sort();
    var first = allDates[0]||null, last = allDates[allDates.length-1]||null;

    function pad(n){ return String(n).padStart(2,'0'); }

    function buildMonth(year, m) {
        var wrap = document.createElement('div');
        wrap.className = 'cal-month';

        var header = document.createElement('div');
        header.className = 'cal-month-name';
        header.textContent = months[m];
        wrap.appendChild(header);

        var dayHead = document.createElement('div');
        dayHead.className = 'cal-days-header';
        dayNames.forEach(function(d){
            var dn = document.createElement('div');
            dn.className = 'cal-day-name';
            dn.textContent = d;
            dayHead.appendChild(dn);
        });
        wrap.appendChild(dayHead);

        var grid = document.createElement('div');
        grid.className = 'cal-days';

        var offset = new Date(year, m, 1).getDay();
        offset = offset === 0 ? 6 : offset - 1;
        for(var i=0;i<offset;i++){
            var emp = document.createElement('div'); grid.appendChild(emp);
        }

        var days = new Date(year, m+1, 0).getDate();
        for(var d=1;d<=days;d++){
            var ds = year+'-'+pad(m+1)+'-'+pad(d);
            var cell = document.createElement('div');
            cell.className = 'cal-day';
            cell.textContent = d;
            var st = CALENDAR_DAYS[ds];
            if(st==='confirmed')      cell.classList.add('confirmed');
            else if(st==='pending')   cell.classList.add('pending');
            else if(first && last && ds>=first && ds<=last) cell.classList.add('free');
            if(ds===TODAY_STR) cell.classList.add('today');
            cell.title = ds + (st ? ' — '+(st==='confirmed'?'Confirmé':'En attente') : (first&&ds>=first&&ds<=last?' — Libre':''));
            grid.appendChild(cell);
        }
        wrap.appendChild(grid);
        return wrap;
    }

    var container = document.getElementById('annual-calendar');
    for(var m=0;m<12;m++) container.appendChild(buildMonth(CALENDAR_YEAR, m));
})();
</script>

@endsection
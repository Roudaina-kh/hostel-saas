@extends('layouts.app')
@section('title', 'Réservations — ' . $activeHostel?->name)
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

/* Hero */
.rv-hero {
    background: linear-gradient(135deg, #7C2D12 0%, #C2410C 45%, #F97316 100%);
    border-radius: 24px;
    padding: 2rem 2.25rem 4.5rem;
    position: relative;
    overflow: hidden;
    margin-bottom: -2.75rem;
}
.rv-hero::before {
    content:''; position:absolute;
    width:350px;height:350px;
    background:radial-gradient(circle,rgba(255,220,100,.22) 0%,transparent 70%);
    top:-100px;right:-60px;
    animation:orb 5s ease-in-out infinite alternate;
}
@keyframes orb { from{transform:scale(1)} to{transform:scale(1.3) translate(10px,10px)} }

/* Stats */
.rv-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; position:relative; z-index:10; margin-bottom:1.5rem; }
.rv-stat {
    background:white; border-radius:18px; padding:1.4rem 1.5rem;
    box-shadow:0 6px 24px rgba(15,31,61,.09);
    border:1px solid rgba(255,255,255,.9);
    position:relative; overflow:hidden;
    transition:transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s;
}
.rv-stat:hover { transform:translateY(-4px); box-shadow:0 14px 36px rgba(15,31,61,.14); }
.rv-stat::before { content:''; position:absolute; top:0;left:0;right:0; height:3px; border-radius:18px 18px 0 0; }
.rv-stat.s-blue::before  { background:linear-gradient(90deg,var(--blue),#60A5FA); }
.rv-stat.s-red::before   { background:linear-gradient(90deg,var(--red),#F87171); }
.rv-stat.s-amber::before { background:linear-gradient(90deg,var(--amber),#FCD34D); }
.rv-stat.s-green::before { background:linear-gradient(90deg,var(--green),#34D399); }
.rv-stat-label { font-size:11px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--muted); margin-bottom:6px; }
.rv-stat-num { font-family:'Fraunces',serif; font-size:2.6rem; font-weight:900; line-height:1; letter-spacing:-1px; }
.rv-stat.s-blue  .rv-stat-num { color:var(--blue); }
.rv-stat.s-red   .rv-stat-num { color:var(--red); }
.rv-stat.s-amber .rv-stat-num { color:var(--amber); }
.rv-stat.s-green .rv-stat-num { color:var(--green); }
.rv-stat-sub { font-size:12px; color:var(--muted); margin-top:4px; }

/* Sections */
.rv-section {
    background:white; border-radius:20px; border:1px solid var(--border);
    box-shadow:0 4px 16px rgba(15,31,61,.05);
    overflow:hidden; margin-bottom:1.25rem;
}
.rv-section-head {
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;
    padding:1.1rem 1.5rem;
    background:linear-gradient(90deg,#F8FAFF,#FAFBFF);
    border-bottom:1px solid var(--border);
}
.rv-section-title { font-family:'Fraunces',serif; font-size:16px; font-weight:700; color:var(--navy); display:flex; align-items:center; gap:8px; }

/* Table principale */
.rv-table { width:100%; border-collapse:collapse; }
.rv-table thead tr { background:linear-gradient(90deg,#F8FAFF,#F0F4FF); }
.rv-table th {
    padding:10px 14px; font-size:10.5px; font-weight:700; letter-spacing:.1em;
    text-transform:uppercase; color:var(--muted); text-align:left;
    border-bottom:1px solid var(--border); white-space:nowrap;
}
.rv-table td {
    padding:12px 14px; font-size:13.5px; color:#334155;
    border-bottom:1px solid #F1F5F9; vertical-align:middle;
}
.rv-table tbody tr { transition:background .15s; }
.rv-table tbody tr:hover { background:#F8FAFF; }
.rv-table tbody tr:last-child td { border-bottom:none; }
.rv-table tbody tr.is-active { background:linear-gradient(90deg,rgba(249,115,22,.04),rgba(249,115,22,.02)); }
.rv-table tbody tr.is-active:hover { background:linear-gradient(90deg,rgba(249,115,22,.08),rgba(249,115,22,.04)); }

.rv-avatar {
    width:34px;height:34px; border-radius:10px;
    background:linear-gradient(135deg,var(--blue),#60A5FA);
    color:white; font-size:13px; font-weight:700;
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
}

.rv-badge {
    display:inline-flex; align-items:center; gap:5px;
    padding:4px 10px; border-radius:99px;
    font-size:11.5px; font-weight:600; white-space:nowrap;
}
.rv-badge.confirmed { background:#ECFDF5; color:#059669; }
.rv-badge.pending   { background:#FFF7ED; color:#D97706; }
.rv-badge.cancelled { background:#FEF2F2; color:#DC2626; }
.rv-badge .bdot { width:6px; height:6px; border-radius:50%; }
.rv-badge.confirmed .bdot { background:#10B981; }
.rv-badge.pending   .bdot { background:#F59E0B; }
.rv-badge.cancelled .bdot { background:#EF4444; }

/* ── Actions cell : Modifier + Dropdown ─────────────────────── */
.action-cell { display:flex; gap:6px; align-items:center; position:relative; }
.action-btn {
    display:inline-flex; align-items:center; gap:5px;
    padding:5px 10px; border-radius:8px;
    font-size:12px; font-weight:600;
    transition:all .2s; cursor:pointer; text-decoration:none;
    border:1px solid;
}
.action-btn.edit { color:#1B4FD8; background:#EFF6FF; border-color:#BFDBFE; }
.action-btn.edit:hover { background:#DBEAFE; transform:translateY(-1px); }
.action-btn.more {
    color:#64748B; background:white; border-color:#E2E8F0;
    width:30px; height:28px; padding:0; justify-content:center;
    font-size:16px; line-height:1;
}
.action-btn.more:hover { background:#F8FAFF; color:#0F172A; transform:translateY(-1px); }

.action-menu {
    position:absolute; top:calc(100% + 4px); right:0;
    min-width:200px;
    background:white;
    border:1px solid #E2E8F0;
    border-radius:10px;
    box-shadow:0 8px 24px rgba(15,31,61,.12);
    padding:6px;
    z-index:50;
    display:none;
}
.action-menu.open { display:block; animation:menuIn .18s ease-out; }
@keyframes menuIn { from{opacity:0;transform:translateY(-4px)} to{opacity:1;transform:translateY(0)} }

.action-menu a, .action-menu button {
    display:flex; align-items:center; gap:8px;
    padding:8px 10px; border-radius:6px;
    font-size:13px; font-weight:500;
    color:#334155; text-decoration:none;
    background:none; border:none; cursor:pointer;
    width:100%; text-align:left;
    transition:background .15s;
}
.action-menu a:hover, .action-menu button:hover { background:#F8FAFF; }
.action-menu .danger { color:#DC2626; }
.action-menu .danger:hover { background:#FEF2F2; }
.action-menu .divider { height:1px; background:#F1F5F9; margin:4px 0; border:none; }

/* ── PLANNING ───────────────────────────────────────────────── */
.planning-controls { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
.planning-controls .pc-group {
    display:flex; align-items:center; gap:6px;
    background:#F8FAFF; border:1px solid var(--border);
    border-radius:10px; padding:4px;
}
.planning-controls .pc-btn {
    display:inline-flex; align-items:center; gap:4px;
    padding:5px 10px; border-radius:7px;
    font-size:12px; font-weight:600; color:var(--muted);
    background:transparent; border:none; cursor:pointer;
    text-decoration:none; transition:all .15s;
}
.planning-controls .pc-btn:hover { background:white; color:var(--navy); }
.planning-controls .pc-btn.active { background:white; color:var(--navy); box-shadow:0 1px 3px rgba(15,31,61,.08); }
.planning-controls .pc-period {
    font-family:'Fraunces',serif; font-size:14px; font-weight:700; color:var(--navy);
    padding:0 10px; white-space:nowrap;
}
.planning-controls label.show-beds {
    display:inline-flex; align-items:center; gap:6px;
    font-size:12px; font-weight:600; color:var(--muted); cursor:pointer;
    background:#F8FAFF; border:1px solid var(--border);
    border-radius:10px; padding:8px 12px;
}
.planning-controls label.show-beds input { accent-color:#F97316; cursor:pointer; }

.planning-scroll { overflow-x:auto; }
.planning-table {
    width:100%; border-collapse:separate; border-spacing:0;
    font-size:12px; min-width:max-content;
}
.planning-table th, .planning-table td {
    border-right:1px solid #F1F5F9; border-bottom:1px solid #F1F5F9;
    text-align:center; vertical-align:middle;
    background:white;
}
.planning-table th {
    padding:8px 6px; font-weight:700; color:var(--muted);
    background:linear-gradient(90deg,#F8FAFF,#F0F4FF);
    text-transform:uppercase; font-size:10.5px; letter-spacing:.05em;
    position:sticky; top:0;
    white-space:nowrap;
}
.planning-table th.unit-col {
    text-align:left; padding-left:14px;
    min-width:240px;
    position:sticky; left:0; z-index:3;
    background:linear-gradient(90deg,#F8FAFF,#F0F4FF);
}
.planning-table td.unit-cell {
    text-align:left; padding:8px 14px;
    position:sticky; left:0; z-index:2;
    background:white;
    font-weight:600; color:var(--navy);
    border-right:2px solid var(--border);
    white-space:nowrap;
}
.planning-table tr.bed-row td.unit-cell {
    padding-left:36px; font-weight:500; color:#475569; font-size:11.5px;
    background:#FAFCFF;
}
.planning-table tr.bed-row td:not(.unit-cell) { background:#FAFCFF; }
.planning-table tr.dorm-recap td.unit-cell { background:linear-gradient(90deg,#FFF7ED,#FEFCF9); }
.planning-table tr.dorm-recap td:not(.unit-cell) { background:#FFFDF9; }

.planning-table th.today-col, .planning-table td.today-col {
    background:#FEF3E2 !important;
    color:#9A3412;
}
.planning-table th.weekend-col, .planning-table td.weekend-col {
    background:#FAFAFA;
}
.planning-table th.today-col { font-weight:900; color:#9A3412; }

.day-label .d-num { font-family:'Fraunces',serif; font-size:14px; font-weight:900; color:var(--navy); display:block; }
.day-label .d-name { font-size:9.5px; color:var(--muted); font-weight:600; }

.cell-occupied {
    background:linear-gradient(135deg,#FEF2F2,#FEE2E2) !important;
    color:#DC2626; font-weight:700; font-size:11.5px;
    cursor:help;
}
.cell-occupied.pending {
    background:linear-gradient(135deg,#FFF7ED,#FED7AA) !important;
    color:#9A3412;
}
.cell-free {
    background:#F0FDF4 !important;
    color:#15803D; font-weight:700;
}
.cell-dorm-recap {
    font-family:'Fraunces',serif; font-size:14px; font-weight:800;
}
.cell-dorm-recap.full   { background:#FEF2F2; color:#DC2626; }
.cell-dorm-recap.partial{ background:#FFFBEB; color:#D97706; }
.cell-dorm-recap.empty  { background:#F0FDF4; color:#15803D; }

/* Year nav */
.year-nav { display:flex; align-items:center; gap:8px; }
.year-nav a {
    width:28px;height:28px; border-radius:8px;
    display:flex; align-items:center; justify-content:center;
    background:#F1F5F9; color:var(--muted);
    transition:all .2s; text-decoration:none;
}
.year-nav a:hover { background:var(--navy); color:white; transform:scale(1.1); }
.year-nav span { font-weight:700; color:var(--navy); font-size:15px; min-width:44px; text-align:center; }

/* Animations */
.slide-up { opacity:0; transform:translateY(20px); animation:slideUp .5s cubic-bezier(.4,0,.2,1) forwards; }
@keyframes slideUp { to{opacity:1;transform:translateY(0)} }
.d1{animation-delay:.05s} .d2{animation-delay:.1s} .d3{animation-delay:.15s}
.d4{animation-delay:.2s}  .d5{animation-delay:.25s}

/* Empty */
.rv-empty { padding:3.5rem; text-align:center; color:var(--muted); display:flex; flex-direction:column; align-items:center; gap:12px; }
.rv-empty-icon { font-size:48px; opacity:.4; }

/* Hero buttons */
.rv-actions { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
.rv-btn {
    display:inline-flex; align-items:center; gap:7px;
    padding:8px 16px; border-radius:10px;
    font-size:13px; font-weight:600;
    transition:all .25s; text-decoration:none; cursor:pointer;
}
.rv-btn.primary { color:white; border:none; }
.rv-btn.outline { color:var(--navy); background:white; border:1.5px solid var(--border); }
.rv-btn.outline:hover { border-color:var(--blue); color:var(--blue); background:#EFF6FF; }
</style>

<div class="rv-wrap" style="padding-bottom:2rem;">

    {{-- ── HERO ── --}}
    <div class="rv-hero slide-up d1">
        <div style="position:relative;z-index:1;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <span style="font-size:12px;font-weight:700;color:rgba(255,255,255,.55);letter-spacing:.12em;text-transform:uppercase;">Réservations</span>
                <span style="width:28px;height:1px;background:rgba(255,255,255,.25);"></span>
                <span style="font-size:12px;font-weight:600;color:rgba(255,220,150,.9);">{{ $activeHostel?->name }}</span>
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
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Liste
                </a>
                <a href="#planning-section" class="rv-btn outline" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2);color:white;">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 4h18v16H3V4z"/></svg>
                    Planning
                </a>
                @if($canCreate)
                    <a href="{{ route($routes['create']) }}" class="rv-btn primary"
                       style="background:white;color:#C2410C;"
                       onmouseover="this.style.background='#FFF7ED'"
                       onmouseout="this.style.background='white'">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
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

    {{-- ── TABLE PRINCIPALE ── --}}
    <div class="rv-section slide-up d3" id="rv-table">
        <div class="rv-section-head">
            <div class="rv-section-title"><span>📋</span> Réservations {{ $year }}</div>
            <div class="year-nav">
                <a href="{{ route($routes['index'], ['year' => $year - 1]) }}">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <span>{{ $year }}</span>
                <a href="{{ route($routes['index'], ['year' => $year + 1]) }}">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        @if($reservations->isEmpty())
            <div class="rv-empty">
                <div class="rv-empty-icon">📅</div>
                <p style="font-family:'Fraunces',serif;font-size:18px;font-weight:700;color:#1E293B;margin:0;">Aucune réservation pour {{ $year }}</p>
                <p style="font-size:13px;color:var(--muted);margin:0;">Commencez par créer votre première réservation.</p>
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
                            @if($canEdit)<th style="text-align:right;">Actions</th>@endif
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
                                            <p style="margin:0;font-weight:600;font-size:13.5px;color:#0F172A;">{{ $res->mainGuest?->first_name }} {{ $res->mainGuest?->last_name }}</p>
                                            <p style="margin:0;font-size:11.5px;color:var(--muted);">{{ $res->mainGuest?->country?->name ?? '—' }}</p>
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
                                    <div class="action-cell" style="justify-content:flex-end;">
                                        <a href="{{ route($routes['edit'], $res->id) }}" class="action-btn edit" title="Modifier la réservation">
                                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Modifier
                                        </a>
                                        <button type="button" class="action-btn more" onclick="toggleActionMenu({{ $res->id }}, event)" title="Plus d'actions">⋮</button>
                                        <div class="action-menu" id="menu-{{ $res->id }}">
                                            <a href="{{ route($routes['payment_create']) }}?reservation_id={{ $res->id }}">
                                                <span>💰</span> Ajouter un paiement
                                            </a>
                                            <a href="{{ route($routes['edit'], $res->id) }}?focus=extras">
                                                <span>🎁</span> Ajouter un extra
                                            </a>
                                            <hr class="divider">
                                            <button type="button" class="danger"
                                                    onclick="openDeleteModal({{ $res->id }}, '{{ addslashes($res->mainGuest?->first_name . ' ' . $res->mainGuest?->last_name) }}', '{{ $res->start_date->format('d/m/Y') }}', '{{ $res->end_date->format('d/m/Y') }}')">
                                                <span>🗑️</span> Supprimer
                                            </button>
                                        </div>
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

    {{-- ── PLANNING ── --}}
    <div class="rv-section slide-up d4" id="planning-section">
        @php
            $pStart = $planning['start'];
            $pEnd   = $planning['end'];
            $pDays  = $planning['days'];
            $today  = \Carbon\Carbon::today();
            $showBeds = $planning['show_beds'];
            $occupancy = $planning['occupancy'];

            $baseParams = ['year' => $year, 'planning_days' => $pDays, 'show_beds' => $showBeds ? 1 : 0];
            $prevUrl = route($routes['index'], array_merge($baseParams, ['planning_start' => $pStart->copy()->subDays($pDays)->toDateString()]));
            $nextUrl = route($routes['index'], array_merge($baseParams, ['planning_start' => $pStart->copy()->addDays($pDays)->toDateString()]));
            $todayUrl = route($routes['index'], array_merge($baseParams, ['planning_start' => $today->toDateString()]));
        @endphp

        <div class="rv-section-head">
            <div class="rv-section-title">
                <span>📊</span> Planning des hébergements
            </div>

            <div class="planning-controls">
                {{-- Navigation période --}}
                <div class="pc-group">
                    <a href="{{ $prevUrl }}" class="pc-btn" title="Période précédente">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    <span class="pc-period">{{ $pStart->format('d/m') }} → {{ $pEnd->format('d/m/Y') }}</span>
                    <a href="{{ $nextUrl }}" class="pc-btn" title="Période suivante">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <a href="{{ $todayUrl }}" class="pc-btn" style="background:#F8FAFF;border:1px solid var(--border);border-radius:10px;padding:8px 12px;">
                    📍 Aujourd'hui
                </a>

                {{-- Switcher période --}}
                <div class="pc-group">
                    @foreach([7, 14, 30] as $d)
                        <a href="{{ route($routes['index'], ['year' => $year, 'planning_start' => $pStart->toDateString(), 'planning_days' => $d, 'show_beds' => $showBeds ? 1 : 0]) }}"
                           class="pc-btn {{ $pDays === $d ? 'active' : '' }}">{{ $d }}j</a>
                    @endforeach
                </div>

                {{-- Toggle afficher les lits --}}
                <label class="show-beds">
                    <input type="checkbox" id="toggle-beds" {{ $showBeds ? 'checked' : '' }}
                           onchange="toggleShowBeds()">
                    Afficher les lits
                </label>
            </div>
        </div>

        @if($planning['private_rooms']->isEmpty() && $planning['dormitory_rooms']->isEmpty() && $planning['tent_spaces']->isEmpty())
            <div class="rv-empty">
                <div class="rv-empty-icon">🛏️</div>
                <p style="font-family:'Fraunces',serif;font-size:18px;font-weight:700;color:#1E293B;margin:0;">Aucune unité d'hébergement</p>
                <p style="font-size:13px;color:var(--muted);margin:0;">Configurez d'abord vos chambres et tentes.</p>
            </div>
        @else
            <div class="planning-scroll">
                <table class="planning-table">
                    <thead>
                        <tr>
                            <th class="unit-col">Unité</th>
                            @foreach($planning['dates'] as $d)
                                @php
                                    $isToday = $d->isSameDay($today);
                                    $isWeekend = $d->isWeekend();
                                    $cls = $isToday ? 'today-col' : ($isWeekend ? 'weekend-col' : '');
                                @endphp
                                <th class="{{ $cls }}">
                                    <div class="day-label">
                                        <span class="d-num">{{ $d->format('d') }}</span>
                                        <span class="d-name">{{ ucfirst($d->locale('fr')->isoFormat('ddd')) }} {{ $d->format('m') }}</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Chambres privées --}}
                        @foreach($planning['private_rooms'] as $room)
                            <tr>
                                <td class="unit-cell">🏠 {{ $room->name }} <span style="color:var(--muted);font-weight:400;font-size:10.5px;">({{ $room->max_capacity }} pers.)</span></td>
                                @foreach($planning['dates'] as $d)
                                    @php
                                        $key = $d->format('Y-m-d');
                                        $occ = $occupancy['room'][$room->id][$key] ?? null;
                                        $cls = $d->isSameDay($today) ? 'today-col' : ($d->isWeekend() ? 'weekend-col' : '');
                                    @endphp
                                    @if($occ)
                                        <td class="{{ $cls }} cell-occupied {{ $occ['status'] === 'pending' ? 'pending' : '' }}"
                                            title="Occupée par {{ $occ['guest_name'] }} ({{ $occ['status'] === 'pending' ? 'En attente' : 'Confirmé' }})">✗</td>
                                    @else
                                        <td class="{{ $cls }} cell-free" title="Libre">✓</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach

                        {{-- Chambres dortoirs : récap + lits --}}
                        @foreach($planning['dormitory_rooms'] as $room)
                            <tr class="dorm-recap">
                                <td class="unit-cell">🛏️ {{ $room->name }} <span style="color:var(--muted);font-weight:400;font-size:10.5px;">({{ $room->beds->count() }} lits)</span></td>
                                @foreach($planning['dates'] as $d)
                                    @php
                                        $key = $d->format('Y-m-d');
                                        $totalBeds = $room->beds->count();
                                        $occupiedBeds = 0;
                                        foreach ($room->beds as $bed) {
                                            if (isset($occupancy['bed'][$bed->id][$key])) $occupiedBeds++;
                                        }
                                        $freeBeds = max(0, $totalBeds - $occupiedBeds);
                                        if ($totalBeds === 0) $recapCls = 'empty';
                                        elseif ($freeBeds === 0) $recapCls = 'full';
                                        elseif ($freeBeds < $totalBeds) $recapCls = 'partial';
                                        else $recapCls = 'empty';
                                        $cls = $d->isSameDay($today) ? 'today-col' : ($d->isWeekend() ? 'weekend-col' : '');
                                    @endphp
                                    <td class="{{ $cls }} cell-dorm-recap {{ $recapCls }}"
                                        title="{{ $freeBeds }}/{{ $totalBeds }} lits libres">
                                        {{ $freeBeds }}/{{ $totalBeds }}
                                    </td>
                                @endforeach
                            </tr>
                            @if($showBeds)
                                @foreach($room->beds as $bed)
                                    <tr class="bed-row">
                                        <td class="unit-cell">↳ {{ $bed->name }}</td>
                                        @foreach($planning['dates'] as $d)
                                            @php
                                                $key = $d->format('Y-m-d');
                                                $occ = $occupancy['bed'][$bed->id][$key] ?? null;
                                                $cls = $d->isSameDay($today) ? 'today-col' : ($d->isWeekend() ? 'weekend-col' : '');
                                            @endphp
                                            @if($occ)
                                                <td class="{{ $cls }} cell-occupied {{ $occ['status'] === 'pending' ? 'pending' : '' }}"
                                                    title="Occupé par {{ $occ['guest_name'] }} ({{ $occ['status'] === 'pending' ? 'En attente' : 'Confirmé' }})">✗</td>
                                            @else
                                                <td class="{{ $cls }} cell-free" title="Libre">✓</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Tent spaces --}}
                        @foreach($planning['tent_spaces'] as $tent)
                            <tr>
                                <td class="unit-cell">⛺ {{ $tent->name }} <span style="color:var(--muted);font-weight:400;font-size:10.5px;">({{ $tent->max_persons }} pers.)</span></td>
                                @foreach($planning['dates'] as $d)
                                    @php
                                        $key = $d->format('Y-m-d');
                                        $occ = $occupancy['tent_space'][$tent->id][$key] ?? null;
                                        $cls = $d->isSameDay($today) ? 'today-col' : ($d->isWeekend() ? 'weekend-col' : '');
                                    @endphp
                                    @if($occ)
                                        <td class="{{ $cls }} cell-occupied {{ $occ['status'] === 'pending' ? 'pending' : '' }}"
                                            title="Occupée par {{ $occ['guest_name'] }} ({{ $occ['status'] === 'pending' ? 'En attente' : 'Confirmé' }})">✗</td>
                                    @else
                                        <td class="{{ $cls }} cell-free" title="Libre">✓</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Légende --}}
            <div style="padding:12px 1.5rem;border-top:1px solid var(--border);display:flex;gap:18px;flex-wrap:wrap;background:#FAFCFF;">
                <span style="display:inline-flex;align-items:center;gap:6px;font-size:11.5px;color:var(--muted);font-weight:500;">
                    <span style="display:inline-block;width:16px;height:14px;background:#F0FDF4;border:1px solid #BBF7D0;border-radius:3px;color:#15803D;text-align:center;line-height:13px;font-size:10px;font-weight:700;">✓</span>
                    Libre
                </span>
                <span style="display:inline-flex;align-items:center;gap:6px;font-size:11.5px;color:var(--muted);font-weight:500;">
                    <span style="display:inline-block;width:16px;height:14px;background:linear-gradient(135deg,#FEF2F2,#FEE2E2);border:1px solid #FECACA;border-radius:3px;color:#DC2626;text-align:center;line-height:13px;font-size:10px;font-weight:700;">✗</span>
                    Occupé confirmé
                </span>
                <span style="display:inline-flex;align-items:center;gap:6px;font-size:11.5px;color:var(--muted);font-weight:500;">
                    <span style="display:inline-block;width:16px;height:14px;background:linear-gradient(135deg,#FFF7ED,#FED7AA);border:1px solid #FED7AA;border-radius:3px;color:#9A3412;text-align:center;line-height:13px;font-size:10px;font-weight:700;">✗</span>
                    Occupé en attente
                </span>
                <span style="display:inline-flex;align-items:center;gap:6px;font-size:11.5px;color:var(--muted);font-weight:500;">
                    <span style="display:inline-block;padding:1px 5px;background:#FFFBEB;color:#D97706;border-radius:3px;font-size:10px;font-weight:700;">X/Y</span>
                    Dortoir : lits libres / total
                </span>
            </div>
        @endif
    </div>
</div>

{{-- ── DELETE MODAL ── --}}
<div id="delete-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(15,31,61,0.6);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:16px;">
    <div style="background:white;border-radius:20px;box-shadow:0 32px 64px rgba(15,31,61,0.25);width:100%;max-width:420px;display:flex;flex-direction:column;max-height:90vh;font-family:'Plus Jakarta Sans',sans-serif;">
        <div style="display:flex;align-items:center;gap:12px;padding:18px 20px;border-bottom:1px solid #FEE2E2;background:linear-gradient(90deg,#FFF1F2,#FFF5F5);border-radius:20px 20px 0 0;flex-shrink:0;">
            <div style="width:42px;height:42px;border-radius:12px;background:#FEE2E2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#DC2626" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
            <label style="display:block;font-size:11px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">Mot de passe de confirmation <span style="color:#EF4444;">*</span></label>
            <input type="password" id="delete-password" placeholder="Votre mot de passe"
                   style="width:100%;border:1.5px solid #E2E8F0;border-radius:10px;padding:10px 14px;font-size:14px;outline:none;box-sizing:border-box;font-family:'Plus Jakarta Sans',sans-serif;"
                   onfocus="this.style.borderColor='#EF4444'" onblur="this.style.borderColor='#E2E8F0'">
            <p id="delete-pwd-error" style="display:none;font-size:12px;color:#EF4444;margin-top:6px;"></p>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:16px 20px;border-top:1px solid #F1F5F9;background:#FAFAFA;border-radius:0 0 20px 20px;flex-shrink:0;">
            <button onclick="closeDeleteModal()" style="padding:10px;font-size:14px;font-weight:600;color:#374151;background:white;border:1.5px solid #E2E8F0;border-radius:10px;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">Annuler</button>
            <button onclick="submitDelete()" style="display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;font-size:14px;font-weight:600;color:white;background:#EF4444;border:none;border-radius:10px;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;">🗑️ Supprimer</button>
        </div>
    </div>
</div>

<form id="delete-form" method="POST" style="display:none">
    @csrf @method('DELETE')
    <input type="hidden" name="password" id="delete-form-password">
</form>

<script>
var currentDeleteId = null;

// ── Dropdown actions ─────────────────────────────────────────
function toggleActionMenu(id, event) {
    if (event) event.stopPropagation();
    document.querySelectorAll('.action-menu').forEach(function (m) {
        if (m.id !== 'menu-' + id) m.classList.remove('open');
    });
    document.getElementById('menu-' + id).classList.toggle('open');
}
document.addEventListener('click', function (e) {
    if (!e.target.closest('.action-cell')) {
        document.querySelectorAll('.action-menu').forEach(function (m) { m.classList.remove('open'); });
    }
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.action-menu').forEach(function (m) { m.classList.remove('open'); });
    }
});

// ── Toggle "Afficher les lits" ───────────────────────────────
function toggleShowBeds() {
    var checked = document.getElementById('toggle-beds').checked;
    var url = new URL(window.location.href);
    url.searchParams.set('show_beds', checked ? '1' : '0');
    window.location.href = url.toString();
}

// ── Delete modal ─────────────────────────────────────────────
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
document.getElementById('delete-modal').addEventListener('click', function (e) { if (e.target === this) closeDeleteModal(); });
document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeDeleteModal(); });
</script>

@endsection
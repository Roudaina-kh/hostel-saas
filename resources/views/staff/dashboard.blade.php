@extends('layouts.app')
@section('title', 'Tableau de Bord Staff — ' . $activeHostel?->name)
@section('content')

<style>
/* ═══════════════════════════════════════════════════════════════
   STAFF DASHBOARD — Palette terra/teal/sand
   Structure et data 100% préservés.
   ═══════════════════════════════════════════════════════════════ */

.staff-wrap { font-family: 'DM Sans', sans-serif; padding-bottom: 2rem; }
.staff-title { font-family: 'Playfair Display', serif; }

/* ── HERO ── */
.staff-hero {
    background: linear-gradient(135deg, #1C1C24 0%, #2E3A35 40%, #1B6B6B 100%);
    border-radius: 28px;
    padding: 2.5rem 2.5rem 4.5rem;
    position: relative;
    overflow: hidden;
    margin-bottom: -2.5rem;
}
.staff-hero::before {
    content: '';
    position: absolute;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(200,96,42,0.22) 0%, transparent 70%);
    top: -150px; right: -100px;
    animation: pulse-glow 4s ease-in-out infinite alternate;
}
.staff-hero::after {
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

/* ── Action Cards ── */
.staff-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
    position: relative;
    z-index: 10;
    margin-top: 0;
}

.staff-card {
    background: #FEFCF9;
    border-radius: 22px;
    padding: 1.75rem;
    border: 1px solid #DDD6CA;
    box-shadow: 0 4px 16px rgba(28,28,36,0.05);
    transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s;
    position: relative;
    overflow: hidden;
}
.staff-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 48px rgba(28,28,36,0.12);
}
.staff-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    border-radius: 22px 22px 0 0;
}
.staff-card.terra::before { background: linear-gradient(90deg, #C8602A, #E8A050); }
.staff-card.teal::before  { background: linear-gradient(90deg, #1B6B6B, #4A9A9A); }

.staff-card-icon {
    width: 56px; height: 56px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px;
    margin-bottom: 1rem;
}
.staff-card.terra .staff-card-icon { background: #FEF3E2; }
.staff-card.teal  .staff-card-icon { background: #E8F4F0; }

.staff-card h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    font-weight: 600;
    color: #2E2E3A;
    margin-bottom: 0.5rem;
}

.staff-card p {
    font-size: 0.9rem;
    color: #6B6B7A;
    line-height: 1.5;
    margin-bottom: 1.25rem;
}

.staff-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 700;
    text-decoration: none;
    color: #fff;
    transition: all 0.25s ease;
    border: none;
    cursor: pointer;
}
.staff-card.terra .staff-btn {
    background: #C8602A;
    box-shadow: 0 4px 12px rgba(200,96,42,0.25);
}
.staff-card.terra .staff-btn:hover {
    background: #A84E20;
    transform: translateY(-2px);
}
.staff-card.teal .staff-btn {
    background: #1B6B6B;
    box-shadow: 0 4px 12px rgba(27,107,107,0.25);
}
.staff-card.teal .staff-btn:hover {
    background: #134F4F;
    transform: translateY(-2px);
}

/* ── Animations ── */
.staff-fade {
    opacity: 0;
    transform: translateY(20px);
    animation: staffFade 0.6s cubic-bezier(0.4,0,0.2,1) forwards;
}
@keyframes staffFade { to { opacity: 1; transform: translateY(0); } }
.sd1 { animation-delay: 0.05s; }
.sd2 { animation-delay: 0.15s; }
.sd3 { animation-delay: 0.25s; }
</style>

<div class="staff-wrap">

    {{-- ── HERO ─────────────────────────────────────────────────── --}}
    <div class="staff-hero staff-fade sd1">
        <div style="position:relative; z-index:1;">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
                <span style="font-size:13px; font-weight:600; color:rgba(254,252,249,0.6); letter-spacing:.12em; text-transform:uppercase;">
                    {{ $role === 'financial' ? 'Espace Financier' : 'Espace Staff' }}
                </span>
                <span style="width:32px; height:1px; background:rgba(254,252,249,0.3);"></span>
                <span style="font-size:13px; font-weight:600; color:#F5C896;">{{ now()->format('d M Y') }}</span>
            </div>
            <h1 class="staff-title" style="font-size:2.5rem; font-weight:700; color:#FEFCF9; line-height:1.1; margin-bottom:10px;">
                Bienvenue 👋<br>
                <span style="color:rgba(254,252,249,0.7); font-size:1.5rem; font-style:italic;">{{ $user->name }}</span>
            </h1>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(254,252,249,0.12); backdrop-filter:blur(8px); border:1px solid rgba(254,252,249,0.2); border-radius:99px; padding:6px 16px;">
                    <span style="width:8px; height:8px; background:#7AB592; border-radius:50%; animation:pulse-glow 1.5s infinite;"></span>
                    <span style="font-size:13px; font-weight:600; color:#FEFCF9;">{{ $activeHostel?->name }}</span>
                </div>
                <div style="display:inline-flex; align-items:center; gap:6px; background:rgba(200,96,42,0.20); border:1px solid rgba(200,96,42,0.4); border-radius:99px; padding:6px 14px;">
                    <span style="font-size:12px; font-weight:700; color:#F5C896; letter-spacing:.06em; text-transform:uppercase;">
                        {{ $role === 'financial' ? '💰 Responsable Financier' : '🛎️ Staff Opérationnel' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── ACTIONS ─────────────────────────────────────────────── --}}
    <div class="staff-grid staff-fade sd2">

        <div class="staff-card terra">
            <div class="staff-card-icon">📅</div>
            <h3>Opérations du jour</h3>
            <p>Consultez les check-ins et check-outs prévus pour aujourd'hui, et gérez les arrivées des clients.</p>
            <a href="{{ route('staff.reservations.index') }}" class="staff-btn">
                Voir le planning
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        <div class="staff-card teal">
            <div class="staff-card-icon">💳</div>
            <h3>Caisse & Paiements</h3>
            <p>Enregistrez les nouveaux paiements, consultez les transactions du jour ou clôturez votre caisse.</p>
            <a href="{{ route('staff.payments.index') }}" class="staff-btn">
                Gérer les paiements
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

    </div>

</div>

@endsection
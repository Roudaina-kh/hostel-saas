@extends('layouts.app')
@section('title', 'Tableau de bord — ' . $activeHostel->name)
@section('content')

{{-- ── En-tête ─────────────────────────────────────────────── --}}
<div class="mb-10 fade-up">
    <div class="flex items-center gap-4 mb-2">
        <h1 class="text-4xl font-extrabold tracking-tight" style="color: #1A4A6B;">Bienvenue 👋</h1>
    </div>
    <p class="text-[16px] font-medium" style="color: #5A6B7A;">
        Voici le résumé de
        <span class="font-bold text-white px-3 py-1 rounded-lg shadow-sm" style="background-color: #2C6E8A;">
            {{ $activeHostel->name }}
        </span>
    </p>
</div>

{{-- ── Bloc 1 : KPIs principaux ────────────────────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">

    <div class="stat-card fade-up delay-1">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-[16px] font-bold" style="color: #1A4A6B;">Réservations actives</h3>
            <div class="stat-icon">🛏️</div>
        </div>
        <p class="text-5xl font-black" style="color: #2C6E8A;">{{ $activeReservations }}</p>
    </div>

    <div class="stat-card fade-up delay-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-[16px] font-bold" style="color: #1A4A6B;">Lits disponibles</h3>
            <div class="stat-icon">✅</div>
        </div>
        <p class="text-5xl font-black" style="color: #2C6E8A;">{{ $availableBeds }}</p>
    </div>

    <div class="stat-card fade-up delay-3">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-[16px] font-bold" style="color: #1A4A6B;">Revenus du mois</h3>
            <div class="stat-icon">💰</div>
        </div>
        <div class="flex items-baseline gap-2">
            <p class="text-5xl font-black" style="color: #2C6E8A;">
                {{ number_format($monthlyRevenue, 3, '.', ' ') }}
            </p>
            <span class="text-xl font-bold" style="color: #5A6B7A;">
                {{ $activeHostel->default_currency ?? 'TND' }}
            </span>
        </div>
    </div>

</div>

{{-- ── Bloc 2 : État des chambres ───────────────────────────── --}}
<div class="mb-4 fade-up">
    <h2 class="text-[20px] font-extrabold tracking-tight mb-5" style="color: #1A4A6B;">
        État des chambres
    </h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">

    <div class="stat-card fade-up delay-1 border-l-4" style="border-left-color: #2C6E8A;">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-[15px] font-bold" style="color: #1A4A6B;">Chambres privées</h3>
                <p class="text-[12px] font-medium mt-0.5" style="color: #5A6B7A;">Actives & disponibles</p>
            </div>
            <div class="stat-icon">🔑</div>
        </div>
        <div class="flex items-baseline gap-2">
            <p class="text-5xl font-black" style="color: #2C6E8A;">{{ $privateRoomsCount }}</p>
            <span class="text-[13px] font-semibold px-2 py-0.5 rounded-full"
                  style="background-color: #E0F2FE; color: #0369A1;">
                privée{{ $privateRoomsCount > 1 ? 's' : '' }}
            </span>
        </div>
    </div>

    <div class="stat-card fade-up delay-2 border-l-4" style="border-left-color: #0891B2;">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-[15px] font-bold" style="color: #1A4A6B;">Dortoirs</h3>
                <p class="text-[12px] font-medium mt-0.5" style="color: #5A6B7A;">Actifs & disponibles</p>
            </div>
            <div class="stat-icon">🏨</div>
        </div>
        <div class="flex items-baseline gap-2">
            <p class="text-5xl font-black" style="color: #0891B2;">{{ $dormitoryRoomsCount }}</p>
            <span class="text-[13px] font-semibold px-2 py-0.5 rounded-full"
                  style="background-color: #CFFAFE; color: #0E7490;">
                dortoir{{ $dormitoryRoomsCount > 1 ? 's' : '' }}
            </span>
        </div>
    </div>

    <div class="stat-card fade-up delay-3 border-l-4" style="border-left-color: #DC2626;">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-[15px] font-bold" style="color: #1A4A6B;">Chambres indisponibles</h3>
                <p class="text-[12px] font-medium mt-0.5" style="color: #5A6B7A;">
                    Désactivées ou bloquées aujourd'hui
                </p>
            </div>
            <div class="stat-icon">🚫</div>
        </div>
        <div class="flex items-baseline gap-2">
            <p class="text-5xl font-black" style="color: #DC2626;">{{ $unavailableRoomsCount }}</p>
            <span class="text-[13px] font-semibold px-2 py-0.5 rounded-full"
                  style="background-color: #FEE2E2; color: #DC2626;">
                chambre{{ $unavailableRoomsCount > 1 ? 's' : '' }}
            </span>
        </div>
        @if($blockedRoomIds->count() > 0)
        <p class="text-[11px] mt-2" style="color: #9CA3AF;">
            dont {{ $blockedRoomIds->count() }} bloquée{{ $blockedRoomIds->count() > 1 ? 's' : '' }} par blocage actif
        </p>
        @endif
    </div>

</div>

{{-- ── Bloc 3 : Espaces tentes ──────────────────────────────── --}}
<div class="mb-4 fade-up">
    <h2 class="text-[20px] font-extrabold tracking-tight mb-5" style="color: #1A4A6B;">
        Espaces tentes ⛺
    </h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">

    <div class="stat-card fade-up delay-1 border-l-4" style="border-left-color: #16A34A;">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-[15px] font-bold" style="color: #1A4A6B;">Espaces actifs</h3>
                <p class="text-[12px] font-medium mt-0.5" style="color: #5A6B7A;">Disponibles pour réservation</p>
            </div>
            <div class="stat-icon">✅</div>
        </div>
        <div class="flex items-baseline gap-2">
            <p class="text-5xl font-black" style="color: #16A34A;">{{ $activeTentSpacesCount }}</p>
            <span class="text-[13px] font-semibold px-2 py-0.5 rounded-full"
                  style="background-color: #DCFCE7; color: #16A34A;">
                espace{{ $activeTentSpacesCount > 1 ? 's' : '' }}
            </span>
        </div>
    </div>

    <div class="stat-card fade-up delay-2 border-l-4" style="border-left-color: #D97706;">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-[15px] font-bold" style="color: #1A4A6B;">Espaces inactifs</h3>
                <p class="text-[12px] font-medium mt-0.5" style="color: #5A6B7A;">Non disponibles</p>
            </div>
            <div class="stat-icon">⛔</div>
        </div>
        <div class="flex items-baseline gap-2">
            <p class="text-5xl font-black" style="color: #D97706;">{{ $inactiveTentSpacesCount }}</p>
            <span class="text-[13px] font-semibold px-2 py-0.5 rounded-full"
                style="background-color: #FEF3C7; color: #D97706;">
                espace{{ $inactiveTentSpacesCount > 1 ? 's' : '' }}
            </span>
        </div>
    </div>

</div>

@endsection
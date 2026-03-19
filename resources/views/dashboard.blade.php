@extends('layouts.app')
@section('title', 'Tableau de bord — ' . $activeHostel->name)
@section('content')

<div class="mb-10 fade-up">
    <div class="flex items-center gap-4 mb-2">
        <h1 class="text-4xl font-extrabold tracking-tight" style="color: #1A4A6B;">Bienvenue 👋</h1>
    </div>
    <p class="text-[16px] font-medium" style="color: #5A6B7A;">
        Voici le résumé de 
        <span class="font-bold text-white px-3 py-1 rounded-lg shadow-sm" style="background-color: #2C6E8A;">{{ $activeHostel->name }}</span> 
    </p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
    {{-- Réservations --}}
    <div class="stat-card fade-up delay-1">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-[16px] font-bold" style="color: #1A4A6B;">Réservations actives</h3>
            <div class="stat-icon">🛏️</div>
        </div>
        <p class="text-5xl font-black" style="color: #2C6E8A;">
            {{ $activeReservationsCount ?? '0' }}
        </p>
    </div>
    
    {{-- Lits --}}
    <div class="stat-card fade-up delay-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-[16px] font-bold" style="color: #1A4A6B;">Lits disponibles</h3>
            <div class="stat-icon">✅</div>
        </div>
        <p class="text-5xl font-black" style="color: #2C6E8A;">
            {{ $availableBedsCount ?? '0' }}
        </p>
    </div>
    
    {{-- Revenus --}}
    <div class="stat-card fade-up delay-3">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-[16px] font-bold" style="color: #1A4A6B;">Revenus du mois</h3>
            <div class="stat-icon">💰</div>
        </div>
        <div class="flex items-baseline gap-2">
            <p class="text-5xl font-black" style="color: #2C6E8A;">
                {{ number_format($monthlyRevenue ?? 0, 0, ',', ' ') }}
            </p>
            <span class="text-xl font-bold" style="color: #5A6B7A;">{{ $activeHostel->default_currency ?? 'TND' }}</span>
        </div>
    </div>
</div>

@endsection
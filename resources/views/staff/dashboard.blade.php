@extends('layouts.app')
@section('title', 'Tableau de Bord Staff — ' . $hostel->name)
@section('content')

<div class="mb-10 fade-up">
    <div class="flex items-center gap-4 mb-2">
        <h1 class="text-4xl font-extrabold tracking-tight" style="color: #1A4A6B;">Bienvenue, {{ $user->name }} 👋</h1>
    </div>
    <p class="text-[16px] font-medium" style="color: #5A6B7A;">
        Interface Opérationnelle : <span class="font-bold text-white px-3 py-1 rounded-lg shadow-sm" style="background-color: #2C6E8A;">{{ $hostel->name }}</span> 
    </p>
    <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-blue-100 text-blue-700">
        Rôle : {{ $role === 'financial' ? 'Responsable Financier' : 'Staff Opérationnel' }}
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">
    {{-- Placeholder pour les actions staff --}}
    <div class="stat-card fade-up delay-1">
        <h3 class="text-[16px] font-bold mb-4" style="color: #1A4A6B;">Opérations du jour</h3>
        <p class="text-sm text-[#5A6B7A]">Consultez les check-ins et check-outs prévus pour aujourd'hui.</p>
        <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-xl font-bold text-sm">Voir le planning</button>
    </div>

    <div class="stat-card fade-up delay-2">
        <h3 class="text-[16px] font-bold mb-4" style="color: #1A4A6B;">Caisse & Paiements</h3>
        <p class="text-sm text-[#5A6B7A]">Enregistrez les nouveaux paiements ou consultez les transactions.</p>
        <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-xl font-bold text-sm">Gérer les paiements</button>
    </div>
</div>

@endsection

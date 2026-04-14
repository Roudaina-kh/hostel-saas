@extends('layouts.app')
@section('title', 'Dashboard Manager')
@section('content')

<div class="mb-8 fade-up">
    <h1 class="text-3xl font-extrabold tracking-tight text-[#0F172A]">
        Bonjour, {{ $currentManager->name }} 👋
    </h1>
    <p class="text-[15px] font-medium text-[#64748B] mt-1">
        Dashboard de <strong>{{ $managerHostel->name }}</strong>
        — {{ $managerHostel->city }}, {{ $managerHostel->country }}
    </p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 fade-up delay-1">

    <div class="stat-card group">
        <div class="flex items-center gap-5">
            <div class="stat-icon">🚪</div>
            <div>
                <p class="text-[28px] font-black text-[#0F172A] leading-none">{{ $stats['total_rooms'] }}</p>
                <p class="text-[13px] font-bold text-[#64748B] uppercase tracking-wider mt-1.5">Total Rooms</p>
            </div>
        </div>
    </div>

    <div class="stat-card group">
        <div class="flex items-center gap-5">
            <div class="stat-icon">✅</div>
            <div>
                <p class="text-[28px] font-black text-[#0F172A] leading-none">{{ $stats['active_rooms'] }}</p>
                <p class="text-[13px] font-bold text-[#64748B] uppercase tracking-wider mt-1.5">Rooms Actives</p>
            </div>
        </div>
    </div>

    <div class="stat-card group">
        <div class="flex items-center gap-5">
            <div class="stat-icon">🔧</div>
            <div>
                <p class="text-[28px] font-black text-[#0F172A] leading-none">{{ $stats['disabled_beds'] }}</p>
                <p class="text-[13px] font-bold text-[#64748B] uppercase tracking-wider mt-1.5">Lits désactivés</p>
            </div>
        </div>
    </div>

</div>

{{-- Dernières chambres --}}
<div class="glass-table p-8 fade-up delay-2">
    <h2 class="text-lg font-bold text-[#0F172A] mb-6 flex items-center gap-3">
        <span class="text-2xl">📋</span> Dernières Chambres
    </h2>
    <div class="space-y-4">
        @forelse($stats['rooms'] as $room)
        <div class="flex items-center justify-between p-4 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] hover:border-[#3B82F6] transition-all">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm border border-[#E2E8F0] font-bold text-[#3B82F6]">
                    {{ strtoupper(substr($room->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-[#0F172A]">{{ $room->name }}</p>
                    <p class="text-xs text-[#64748B] font-medium uppercase tracking-wider">
                        {{ $room->type === 'private' ? 'Privée' : 'Dortoir' }}
                    </p>
                </div>
            </div>
            {{-- is_enabled remplace status (Sprint 2) --}}
            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                {{ $room->is_enabled
                    ? 'bg-[#ECFDF5] text-[#059669]'
                    : 'bg-[#FEF2F2] text-[#DC2626]' }}">
                {{ $room->is_enabled ? 'Actif' : 'Désactivé' }}
            </span>
        </div>
        @empty
        <p class="text-center text-[#64748B] py-8 font-medium">Aucune chambre trouvée.</p>
        @endforelse
    </div>
    <div class="mt-6 text-center">
        <a href="{{ route('manager.rooms.index') }}" class="text-[#3B82F6] font-bold text-sm hover:underline">
            Voir toutes les chambres →
        </a>
    </div>
</div>

@endsection
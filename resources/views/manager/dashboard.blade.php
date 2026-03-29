@extends('layouts.app')
@section('title', 'Dashboard Manager')
@section('content')

<div class="mb-8 fade-up">
    <h1 class="text-3xl font-extrabold tracking-tight text-[#0F172A]">Bonjour, {{ $currentManager->name }} 👋</h1>
    <p class="text-[15px] font-medium text-[#64748B] mt-1">
        Dashboard de <strong>{{ $managerHostel->name }}</strong> — {{ $managerHostel->city }}, {{ $managerHostel->country }}
    </p>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 fade-up delay-1">
    {{-- Total Rooms --}}
    <div class="stat-card group">
        <div class="flex items-center gap-5">
            <div class="stat-icon bg-[#EFF6FF] text-[#2563EB]">🚪</div>
            <div>
                <p class="text-[28px] font-black text-[#0F172A] leading-none">{{ $stats['total_rooms'] }}</p>
                <p class="text-[13px] font-bold text-[#64748B] uppercase tracking-wider mt-1.5">Total Rooms</p>
            </div>
        </div>
    </div>

    {{-- Active Rooms --}}
    <div class="stat-card group">
        <div class="flex items-center gap-5">
            <div class="stat-icon bg-[#F0FDF4] text-[#059669]">✅</div>
            <div>
                <p class="text-[28px] font-black text-[#0F172A] leading-none">{{ $stats['active_rooms'] }}</p>
                <p class="text-[13px] font-bold text-[#64748B] uppercase tracking-wider mt-1.5">Active Rooms</p>
            </div>
        </div>
    </div>

    {{-- Maintenance Beds --}}
    <div class="stat-card group">
        <div class="flex items-center gap-5">
            <div class="stat-icon bg-[#FFFBEB] text-[#D97706]">🔧</div>
            <div>
                <p class="text-[28px] font-black text-[#0F172A] leading-none">{{ $stats['maintenance_beds'] }}</p>
                <p class="text-[13px] font-bold text-[#64748B] uppercase tracking-wider mt-1.5">Maintenance Beds</p>
            </div>
        </div>
    </div>
</div>

{{-- Permissions & Permissions Section --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 fade-up delay-2">
    <div class="glass-table p-8">
        <h2 class="text-lg font-bold text-[#0F172A] mb-6 flex items-center gap-3">
            <span class="text-2xl">🛡️</span> Vos permissions
        </h2>
        <div class="grid grid-cols-2 gap-3">
            @foreach([
                ['perm'=>'can_manage_rooms',        'label'=>'Chambres',     'color'=>'#EFF6FF','text'=>'#1A4A6B'],
                ['perm'=>'can_manage_reservations', 'label'=>'Réservations', 'color'=>'#F0FDF4','text'=>'#2A6B4F'],
                ['perm'=>'can_manage_team',         'label'=>'Staff',        'color'=>'#FEF9C3','text'=>'#854D0E'],
                ['perm'=>'can_view_financials',     'label'=>'Finances',     'color'=>'#FFFBEB','text'=>'#92400E'],
                ['perm'=>'can_manage_pricing',      'label'=>'Pricing',      'color'=>'#FEF2F2','text'=>'#DC2626'],
                ['perm'=>'can_manage_taxes',        'label'=>'Taxes',        'color'=>'#F5F3FF','text'=>'#6D28D9'],
            ] as $p)
            <div class="flex items-center gap-3 p-3 rounded-2xl border {{ $currentManager->{$p['perm']} ? 'bg-white border-[#E2E8F0]' : 'bg-[#F8FAFC] border-dashed border-[#CBD5E1] opacity-60' }}">
                <span class="text-lg">{{ $currentManager->{$p['perm']} ? '✅' : '🔒' }}</span>
                <span class="text-[14px] font-bold {{ $currentManager->{$p['perm']} ? 'text-[#0F172A]' : 'text-[#94A3B8]' }}">{{ $p['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="glass-table p-8">
        <h2 class="text-lg font-bold text-[#0F172A] mb-6 flex items-center gap-3">
            <span class="text-2xl">📋</span> Dernières Chambres
        </h2>
        <div class="space-y-4">
            @foreach($stats['rooms'] as $room)
            <div class="flex items-center justify-between p-4 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] hover:border-[#3B82F6] transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm border border-[#E2E8F0] font-bold text-[#3B82F6]">
                        {{ substr($room->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-[#0F172A]">{{ $room->name }}</p>
                        <p class="text-xs text-[#64748B] font-medium uppercase tracking-wider">{{ $room->type }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $room->status === 'active' ? 'bg-[#ECFDF5] text-[#059669]' : 'bg-[#FEF2F2] text-[#DC2626]' }}">
                    {{ $room->status }}
                </span>
            </div>
            @endforeach

            @if(count($stats['rooms']) === 0)
                <p class="text-center text-[#64748B] py-8 font-medium">Aucune chambre trouvée.</p>
            @endif
        </div>
        <div class="mt-6 text-center">
            <a href="{{ route('manager.rooms.index') }}" class="text-[#3B82F6] font-bold text-sm hover:underline">Voir toutes les chambres →</a>
        </div>
    </div>
</div>

@endsection
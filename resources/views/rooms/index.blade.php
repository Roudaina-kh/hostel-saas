@extends('layouts.app')
@section('title', 'Chambres')
@section('content')

<div class="flex items-center justify-between mb-8 fade-up">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-[#0F172A]">Chambres</h1>
        <p class="text-[15px] font-medium text-[#64748B] mt-1">Gérez les chambres répertoriées dans votre établissement.</p>
    </div>
    <a href="{{ route('rooms.create') }}" class="btn-blue fade-up delay-1">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Ajouter une chambre
    </a>
</div>

<div class="glass-table fade-up delay-2">
    <table class="w-full text-[14.5px] text-left">
        <thead class="table-header-blue text-[#1E293B] shadow-sm uppercase tracking-wider text-[12px]">
            <tr>
                <th class="font-bold">NOM</th>
                <th class="font-bold">TYPE</th>
                <th class="font-bold text-center">CAPACITÉ</th>
                <th class="font-bold text-center">LITS</th>
                <th class="font-bold text-right">PRIX ACTIF</th>
                <th class="font-bold text-center">STATUT</th>
                <th class="font-bold text-right">ACTIONS</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#E2E8F0]">
            @forelse($rooms as $room)
            <tr class="table-row-hover">
                <td class="font-bold text-[#0F172A]">{{ $room->name }}</td>
                <td class="font-semibold text-[#475569]">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider 
                        {{ $room->type === 'private' ? 'bg-[#EFF6FF] text-[#2563EB]' : 'bg-[#F0FDF4] text-[#059669]' }}">
                        {{ $room->type === 'private' ? 'Privée' : 'Dortoir' }}
                    </span>
                </td>
                <td class="text-center font-bold text-[#3B82F6]">
                    <div class="inline-flex items-center justify-center bg-[#EFF6FF] text-[#2563EB] w-8 h-8 rounded-full border border-[#DBEAFE]">
                        {{ $room->capacity }}
                    </div>
                </td>
                <td class="text-center font-bold text-[#475569]">
                    <div class="inline-flex items-center justify-center bg-[#F8FAFC] text-[#64748B] px-3 py-1 rounded-full border border-[#E2E8F0]">
                        {{ $room->beds_count }}
                    </div>
                </td>
                <td class="text-right font-black text-[#0F172A]">
                    @if($room->prices->where('is_active', true)->first())
                        {{ number_format($room->prices->where('is_active', true)->first()->price_amount, 3) }}
                        <span class="text-[#64748B] text-[12px] font-bold">{{ $room->prices->where('is_active', true)->first()->currency }}</span>
                    @else
                        <span class="text-[#94A3B8] font-medium text-sm border border-[#E2E8F0] px-2 py-1 rounded-md">—</span>
                    @endif
                </td>
                <td class="text-center">
                    @php 
                        $statusClasses = [
                            'active' => 'bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0]',
                            'maintenance' => 'bg-[#FEF3C7] text-[#D97706] border border-[#FDE68A]',
                            'inactive' => 'bg-[#FEF2F2] text-[#DC2626] border border-[#FECACA]'
                        ];
                        $statusLabels = [
                            'active' => 'Actif',
                            'maintenance' => 'En maintenance',
                            'inactive' => 'Inactif'
                        ];
                    @endphp
                    <span class="inline-flex px-3 py-1 {{ $statusClasses[$room->status] }} rounded-full text-[11px] font-black uppercase tracking-wider shadow-sm">
                        {{ $statusLabels[$room->status] ?? ucfirst($room->status) }}
                    </span>
                </td>
                <td class="text-right space-x-3">
                    <a href="{{ route('rooms.edit', $room) }}" class="inline-flex items-center justify-center p-2 rounded-xl text-[#3B82F6] hover:bg-[#EFF6FF] hover:text-[#2563EB] transition-colors" title="Modifier">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </a>
                    <button onclick="deleteItem('{{ route('rooms.destroy', $room) }}', 'cette chambre')" class="inline-flex items-center justify-center p-2 rounded-xl text-[#EF4444] hover:bg-[#FEF2F2] hover:text-[#DC2626] transition-colors cursor-pointer bg-transparent border-none outline-none" title="Supprimer">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-16 text-center text-[#64748B] font-medium text-[15.5px]">
                    <div class="text-5xl mb-4 opacity-50">🚪</div>
                    Vous n'avez pas encore configuré de chambres.<br>
                    <a href="{{ route('rooms.create') }}" class="text-[#3B82F6] font-bold hover:underline mt-2 inline-block">Créer votre première chambre</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('partials.delete-script')
@endsection
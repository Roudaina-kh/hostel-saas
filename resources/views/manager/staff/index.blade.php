@extends('layouts.app')
@section('title', 'Gestion de l\'Équipe (Manager)')
@section('content')

<div class="flex items-center justify-between mb-8 fade-up text-[#1A2B3C]">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight">Équipe Opérationnelle</h1>
        <p class="text-[15px] font-medium text-[#8A9BB0] mt-1">Gérez les membres de votre équipe (Staff, Finance).</p>
    </div>
    @if($currentManager->can_manage_team)
    <a href="{{ route('manager.staff.create') }}" class="btn-blue fade-up delay-1">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Ajouter un membre
    </a>
    @endif
</div>

<div class="glass-table fade-up delay-2">
    <table class="w-full text-[14.5px] text-left">
        <thead class="table-header-blue text-[#1E293B] shadow-sm uppercase tracking-wider text-[12px]">
            <tr>
                <th class="font-bold">NOM</th>
                <th class="font-bold">EMAIL</th>
                <th class="font-bold">RÔLE</th>
                <th class="font-bold text-center">STATUT</th>
                <th class="font-bold text-right">ACTIONS</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#E2E8F0]">
            @forelse($staff as $s)
            <tr class="table-row-hover">
                <td class="font-bold text-[#0F172A]">{{ $s->name }}</td>
                <td class="font-semibold text-[#64748B]">{{ $s->email }}</td>
                <td class="text-left font-bold text-[#3B82F6]">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[#EFF6FF] text-[#2563EB] border border-[#DBEAFE]">
                        {{ ucfirst($s->role) }}
                    </span>
                </td>
                <td class="text-center">
                    <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider shadow-sm border
                        {{ $s->is_active ? 'bg-[#ECFDF5] text-[#059669] border-[#A7F3D0]' : 'bg-[#FEF2F2] text-[#DC2626] border-[#FECACA]' }}">
                        {{ $s->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </td>
                <td class="text-right space-x-3">
                    @if($currentManager->can_manage_team)
                    <a href="{{ route('manager.staff.edit', $s) }}" class="inline-flex items-center justify-center p-2 rounded-xl text-[#3B82F6] hover:bg-[#EFF6FF] transition-colors" title="Modifier">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </a>
                    <form action="{{ route('manager.staff.destroy', $s) }}" method="POST" class="inline-block" onsubmit="return confirm('Supprimer ce membre ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center p-2 rounded-xl text-[#EF4444] hover:bg-[#FEF2F2] transition-colors bg-transparent border-none cursor-pointer">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-16 text-center text-[#94A3B8]">
                    <div class="text-5xl mb-4 opacity-50">👥</div>
                    Aucun membre d'équipe enregistré.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

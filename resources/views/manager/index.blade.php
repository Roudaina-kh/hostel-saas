@extends('layouts.app')
@section('title', 'Équipe')
@section('content')

<div class="flex items-center justify-between mb-8 fade-up">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-[#0F172A]">Équipe</h1>
        <p class="text-[15px] font-medium text-[#64748B] mt-1">Gérez les membres de votre équipe.</p>
    </div>
    <a href="{{ route('managers.create') }}" class="btn-blue">
        + Ajouter un membre
    </a>
</div>

<div class="glass-table fade-up delay-1">
    <table class="w-full text-[14.5px] text-left">
        <thead class="table-header-blue text-[#1E293B] uppercase tracking-wider text-[12px]">
            <tr>
                <th class="font-bold">NOM</th>
                <th class="font-bold">EMAIL</th>
                <th class="font-bold text-center">RÔLE</th>
                <th class="font-bold text-center">STATUT</th>
                <th class="font-bold text-right">ACTIONS</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#E2E8F0]">
            @forelse($managers as $m)
            <tr class="table-row-hover">
                <td class="font-bold text-[#0F172A]">{{ $m->name }}</td>
                <td class="text-[#64748B]">{{ $m->email }}</td>
                <td class="text-center">
                    <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider
                        {{ $m->role === 'manager' ? 'bg-[#EFF6FF] text-[#2563EB] border border-[#DBEAFE]' :
                           ($m->role === 'financial' ? 'bg-[#F0FDF4] text-[#16A34A] border border-[#BBF7D0]' :
                           'bg-[#F8FAFC] text-[#64748B] border border-[#E2E8F0]') }}">
                        {{ $m->role === 'manager' ? 'Manager' : ($m->role === 'financial' ? 'Financier' : 'Staff') }}
                    </span>
                </td>
                <td class="text-center">
                    <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider
                        {{ $m->is_active ? 'bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0]' : 'bg-[#FEF2F2] text-[#DC2626] border border-[#FECACA]' }}">
                        {{ $m->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </td>
                <td class="text-right space-x-2">
                    <a href="{{ route('managers.edit', $m) }}"
                       class="inline-flex items-center justify-center p-2 rounded-xl text-[#3B82F6] hover:bg-[#EFF6FF] transition-colors">
                        ✏️
                    </a>
                    <button onclick="deleteItem('{{ route('managers.destroy', $m) }}', '{{ $m->name }}')"
                            class="inline-flex items-center justify-center p-2 rounded-xl text-[#EF4444] hover:bg-[#FEF2F2] transition-colors cursor-pointer bg-transparent border-none">
                        🗑️
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-16 text-center text-[#64748B] font-medium">
                    <div class="text-5xl mb-4 opacity-50">👥</div>
                    Aucun membre dans votre équipe.<br>
                    <a href="{{ route('managers.create') }}" class="text-[#3B82F6] font-bold">Ajouter le premier membre →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('partials.delete-script')
@endsection
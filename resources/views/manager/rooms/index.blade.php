@extends('layouts.app')
@section('title', 'Chambres (Manager)')
@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-[#0F172A]">Chambres</h1>
        <p class="text-[15px] font-medium text-[#64748B] mt-1">Gérez les chambres de l'établissement.</p>
    </div>
    <a href="{{ route('manager.rooms.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all">
        + Ajouter une chambre
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-600">
            <tr>
                <th class="px-6 py-4 font-bold">Nom</th>
                <th class="px-6 py-4 font-bold">Type</th>
                <th class="px-6 py-4 font-bold text-center">Capacité</th>
                <th class="px-6 py-4 font-bold text-center">Lits</th>
                <th class="px-6 py-4 font-bold text-center">Statut</th>
                <th class="px-6 py-4 font-bold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($rooms as $room)
            <tr class="hover:bg-slate-50 transition-colors">

                <td class="px-6 py-4 font-bold text-slate-800">{{ $room->name }}</td>

                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                        {{ $room->type === 'private' ? 'bg-blue-50 text-blue-700' : 'bg-green-50 text-green-700' }}">
                        {{ $room->type === 'private' ? 'Privée' : 'Dortoir' }}
                    </span>
                </td>

                <td class="px-6 py-4 text-center font-bold text-blue-600">
                    {{ $room->max_capacity }}
                </td>

                <td class="px-6 py-4 text-center font-bold text-slate-600">
                    {{ $room->beds_count ?? 0 }}
                </td>

                {{-- is_enabled remplace status (Sprint 2) --}}
                <td class="px-6 py-4 text-center">
                    @if($room->is_enabled)
                        <span class="px-3 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs font-bold uppercase">
                            Actif
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-50 text-red-700 border border-red-200 rounded-full text-xs font-bold uppercase">
                            Désactivé
                        </span>
                    @endif
                </td>

                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('manager.rooms.edit', $room) }}"
                           class="p-2 rounded-xl text-blue-600 hover:bg-blue-50 transition-colors" title="Modifier">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </a>
                        <form action="{{ route('manager.rooms.destroy', $room) }}" method="POST" class="inline-block"
                              onsubmit="return confirm('Supprimer cette chambre ?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="p-2 rounded-xl text-red-500 hover:bg-red-50 transition-colors bg-transparent border-none cursor-pointer">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-16 text-center text-slate-400 font-medium">
                    <div class="text-5xl mb-4 opacity-50">🚪</div>
                    Aucune chambre trouvée.
                    <a href="{{ route('manager.rooms.create') }}"
                       class="block text-blue-600 font-bold hover:underline mt-2">
                        Créer la première chambre
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
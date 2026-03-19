@extends('layouts.app')
@section('title', 'Lits')
@section('content')

<div class="flex items-center justify-between mb-8 fade-up">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-[#0F172A]">Lits</h1>
        <p class="text-[15px] font-medium text-[#64748B] mt-1">Gérez l'inventaire des lits pour vos chambres (dortoirs).</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start fade-up delay-1">

    {{-- Formulaire d'ajout rapide --}}
    <div class="lg:col-span-1 glass-table p-8">
        <h2 class="text-[17px] font-black text-[#1E293B]" style="margin-bottom: 2rem; padding-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #E2E8F0;">Créer un lit</h2>
        <form method="POST" action="{{ route('beds.store') }}">
            @csrf
            <div style="margin-bottom: 2.5rem;">
                <label class="block text-[13px] font-bold text-[#64748B] uppercase tracking-wide" style="margin-bottom: 1rem;">Nom / Numéro</label>
                <input type="text" name="name" required placeholder="Ex: Lit 01, Lit Haut A..."
                       style="padding: 1.25rem 1rem;"
                       class="w-full rounded-xl text-[14.5px] font-medium outline-none transition-all duration-200 border border-[#E2E8F0] bg-[#F8FAFC] focus:bg-white focus:border-[#3B82F6] focus:ring-4 focus:ring-[#EFF6FF]">
            </div>
            <div style="margin-bottom: 3rem;">
                <label class="block text-[13px] font-bold text-[#64748B] uppercase tracking-wide" style="margin-bottom: 1rem;">Assigner à la Chambre</label>
                <div class="relative">
                    <select name="room_id" required
                            style="padding: 1.25rem 1rem;"
                            class="w-full rounded-xl text-[14.5px] font-medium outline-none transition-all duration-200 border border-[#E2E8F0] bg-[#F8FAFC] focus:bg-white focus:border-[#3B82F6] focus:ring-4 focus:ring-[#EFF6FF] appearance-none cursor-pointer">
                        @foreach($rooms as $r)
                            <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->type === 'private' ? 'Privée' : 'Dortoir' }})</option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#94A3B8]">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn-blue w-full" style="padding: 1.25rem; font-size: 16px;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter le lit
            </button>
        </form>
    </div>

    {{-- Liste des lits --}}
    <div class="flex-1 lg:col-span-2 glass-table">
        <table class="w-full text-[14.5px] text-left">
            <thead class="table-header-blue text-[#1E293B] shadow-sm uppercase tracking-wider text-[12px]">
                <tr>
                    <th class="font-bold">NOM DU LIT</th>
                    <th class="font-bold">CHAMBRE</th>
                    <th class="font-bold text-center">STATUT</th>
                    <th class="font-bold text-right">ACTIONS</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E2E8F0]">
                @forelse($beds as $bed)
                <tr class="table-row-hover">
                    <td class="font-bold text-[#0F172A] flex items-center gap-3">
                        <span class="text-xl">🛏️</span>
                        {{ $bed->name }}
                    </td>
                    <td class="font-semibold text-[#475569]">{{ $bed->room->name }}</td>
                    <td class="text-center">
                        <button onclick="toggleMaintenance({{ $bed->id }})"
                                class="inline-flex items-center px-4 py-1.5 rounded-full text-[12px] font-black uppercase tracking-wider shadow-sm transition-all duration-300 {{ $bed->is_maintenance ? 'bg-[#FEF3C7] text-[#D97706] hover:bg-[#FDE68A] border border-[#FDE68A]' : 'bg-[#ECFDF5] text-[#059669] hover:bg-[#D1FAE5] border border-[#A7F3D0]' }}">
                            {{ $bed->is_maintenance ? 'En Maintenance' : 'Disponible' }}
                        </button>
                    </td>
                    <td class="text-right space-x-2">
                        <button onclick="deleteItem('{{ route('beds.destroy', $bed) }}', 'ce lit')" class="inline-flex items-center justify-center p-2 rounded-xl text-[#EF4444] hover:bg-[#FEF2F2] hover:text-[#DC2626] transition-colors cursor-pointer bg-transparent border-none outline-none" title="Supprimer">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-16 text-center text-[#64748B] font-medium text-[15.5px]">
                        <div class="text-5xl mb-4 opacity-50">🛏️</div>
                        Aucun lit n'a été ajouté.<br>
                        Utilisez le formulaire à gauche pour en créer.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleMaintenance(id) {
    fetch(`/beds/${id}/toggle-maintenance`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    }).then(() => window.location.reload());
}
</script>

@include('partials.delete-script')
@endsection
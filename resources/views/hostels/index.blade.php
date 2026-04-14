@extends('layouts.app')
@section('title', 'Mes Hostels')
@section('content')

<div class="flex items-center justify-between mb-8 fade-up">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-[#0F172A]">Mes Hostels</h1>
        <p class="text-[15px] font-medium text-[#64748B] mt-1">Gérez tous vos établissements depuis un seul endroit.</p>
    </div>
    <a href="{{ route('hostels.create') }}" class="btn-blue fade-up delay-1">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Ajouter un hostel
    </a>
</div>

<div class="glass-table fade-up delay-2">
    <table class="w-full text-[14.5px] text-left">
        <thead class="table-header-blue text-[#1E293B] shadow-sm uppercase tracking-wider text-[12px]">
            <tr>
                <th class="font-bold">NOM</th>
                <th class="font-bold">LOCALISATION</th>
                <th class="font-bold">DEVISE</th>
                <th class="font-bold text-center">STATUT</th>
                <th class="font-bold text-right">ACTIONS</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#E2E8F0]">
            @forelse($hostels as $hostel)
            <tr class="table-row-hover">
                <td class="font-bold text-[#0F172A]">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#1E3A8A] to-[#3B82F6] text-white flex items-center justify-center font-black text-[20px] shadow-md border border-[#93C5FD]">
                            {{ strtoupper(substr($hostel->name, 0, 1)) }}
                        </div>
                        <span class="text-[16px]">{{ $hostel->name }}</span>
                    </div>
                </td>
                <td class="font-semibold text-[#475569]">{{ $hostel->city }}, {{ $hostel->country }}</td>
                <td class="font-extrabold text-[#2563EB] text-[15px]">{{ $hostel->default_currency }}</td>
                <td class="text-center">
                    @if($hostel->id == session('hostel_id'))
                        <span class="inline-flex px-3 py-1 bg-[#ECFDF5] text-[#059669] rounded-full text-[11px] font-black uppercase tracking-wider shadow-sm border border-[#A7F3D0]">
                            ★ Actif
                        </span>
                    @else
                        <form method="POST" action="{{ route('hostel.switch', $hostel) }}" class="inline m-0">
                            @csrf
                            <button class="px-3 py-1 bg-white hover:bg-[#F0F9FF] text-[#64748B] hover:text-[#2563EB] rounded-full text-[11px] font-black uppercase tracking-wider transition-colors duration-200 border border-[#E2E8F0] hover:border-[#93C5FD] shadow-sm">
                                Activer
                            </button>
                        </form>
                    @endif
                </td>
                <td class="text-right space-x-3">
                    <a href="{{ route('hostels.edit', $hostel) }}" class="inline-flex items-center justify-center p-2 rounded-xl text-[#3B82F6] hover:bg-[#EFF6FF] hover:text-[#2563EB] transition-colors" title="Modifier">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </a>
                    <button onclick="deleteHostel({{ $hostel->id }})" class="inline-flex items-center justify-center p-2 rounded-xl text-[#EF4444] hover:bg-[#FEF2F2] hover:text-[#DC2626] transition-colors cursor-pointer bg-transparent border-none outline-none" title="Supprimer">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-16 text-center text-[#64748B] font-medium text-[15.5px]">
                    <div class="text-5xl mb-4 opacity-50">🏢</div>
                    Vous n'avez aucun hostel configuré.<br>
                    <a href="{{ route('hostels.create') }}" class="text-[#3B82F6] font-bold hover:underline mt-2 inline-block">Créer votre premier établissement</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function deleteHostel(id) {
    Swal.fire({
        title: 'Supprimer ce hostel ?',
        text: 'Cette action effacera toutes les données associées.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#94A3B8',
        cancelButtonText: 'Annuler',
        confirmButtonText: 'Oui, supprimer définitivement',
        customClass: {
            popup: 'rounded-2xl shadow-xl border border-[#E2E8F0]',
        }
    }).then(result => {
        if (result.isConfirmed) {
            // ← Formulaire classique, pas fetch — le controller retourne un redirect
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/hostels/' + id;

            var csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').content;

            var method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';

            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection
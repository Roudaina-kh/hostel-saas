@extends('layouts.app')
@section('title', 'Gestion des Lits (Manager)')
@section('content')

<div class="flex items-center justify-between mb-8 fade-up text-[#1A2B3C]">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight">Gestion des Lits</h1>
        <p class="text-[15px] font-medium text-[#8A9BB0] mt-1">
            Gérez les lits individuels dans vos dortoirs (Manager).
        </p>
    </div>

    @if($currentManager->can_manage_rooms)
    <button onclick="openModal('addBedModal')" class="btn-blue fade-up delay-1">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Ajouter un lit
    </button>
    @endif
</div>

<div class="glass-table fade-up delay-2">
    <table class="w-full text-[14.5px] text-left">
        <thead class="table-header-blue text-[#1E293B] shadow-sm uppercase tracking-wider text-[12px]">
            <tr>
                <th class="font-bold">LIT</th>
                <th class="font-bold">CHAMBRE</th>
                <th class="font-bold text-center">STATUT</th>
                <th class="font-bold text-right">ACTIONS</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-[#E2E8F0]">
            @forelse($beds as $bed)
            <tr class="table-row-hover">
                <td class="font-bold text-[#0F172A]">{{ $bed->name }}</td>

                <td class="font-semibold text-[#64748B]">
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg">
                        🚪 {{ $bed->room->name }}
                    </span>
                </td>

                <td class="text-center">
                    <button onclick="toggleMaintenance({{ $bed->id }})"
                        id="btn-m-{{ $bed->id }}"
                        class="inline-flex px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider shadow-sm transition-all border
                        {{ $bed->maintenance ? 'bg-[#FEF3C7] text-[#D97706] border-[#FDE68A]' : 'bg-[#ECFDF5] text-[#059669] border-[#A7F3D0]' }}">
                        {{ $bed->maintenance ? 'En maintenance' : 'Opérationnel' }}
                    </button>
                </td>

                <td class="text-right space-x-3">
                    @if($currentManager->can_manage_rooms)

                    <button onclick="editBed({{ $bed->id }}, '{{ $bed->name }}')"
                        class="inline-flex items-center justify-center p-2 rounded-xl text-[#3B82F6] hover:bg-[#EFF6FF] transition-colors">
                        ✏️
                    </button>

                    <button onclick="deleteItem('{{ route('manager.beds.destroy', $bed) }}', 'ce lit')"
                        class="inline-flex items-center justify-center p-2 rounded-xl text-[#EF4444] hover:bg-[#FEF2F2] transition-colors">
                        🗑️
                    </button>

                    @endif
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="4" class="py-16 text-center text-[#94A3B8]">
                    <div class="text-5xl mb-4 opacity-50">🛏️</div>
                    Aucun lit configuré.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Ajout --}}
<div id="addBedModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl relative">
        <h3 class="text-xl font-bold text-[#1A2B3C] mb-6">Ajouter un lit</h3>

        <form action="{{ route('manager.beds.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-2">Choisir le Dortoir *</label>
                <select name="room_id" required class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none">
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Nom du lit *</label>
                <input type="text" name="name" required placeholder="Ex: Lit A1"
                    class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 btn-blue">Ajouter</button>
                <button type="button" onclick="closeModal('addBedModal')"
                    class="flex-1 px-6 py-2.5 bg-[#F1F5F9] text-[#64748B] rounded-xl font-bold hover:bg-[#E2E8F0]">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="editBedModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl relative">
        <h3 class="text-xl font-bold text-[#1A2B3C] mb-6">Modifier le lit</h3>

        <form id="editBedForm" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold mb-2">Nom du lit *</label>
                <input type="text" name="name" id="edit_bed_name" required
                    class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 btn-blue">Sauvegarder</button>
                <button type="button" onclick="closeModal('editBedModal')"
                    class="flex-1 px-6 py-2.5 bg-[#F1F5F9] text-[#64748B] rounded-xl font-bold hover:bg-[#E2E8F0]">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials.delete-script')

@push('scripts')
<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function editBed(id, name) {
    document.getElementById('edit_bed_name').value = name;
    document.getElementById('editBedForm').action = `/manager/beds/${id}`;
    openModal('editBedModal');
}

async function toggleMaintenance(id) {
    try {
        const res = await fetch(`/manager/beds/${id}/toggle-maintenance`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        const data = await res.json();

        if (data.success) {
            const btn = document.getElementById(`btn-m-${id}`);
            btn.innerText = data.maintenance ? 'En maintenance' : 'Opérationnel';

            btn.className = data.maintenance
                ? 'inline-flex px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider shadow-sm transition-all border bg-[#FEF3C7] text-[#D97706] border-[#FDE68A]'
                : 'inline-flex px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider shadow-sm transition-all border bg-[#ECFDF5] text-[#059669] border-[#A7F3D0]';
        }
    } catch (e) {
        console.error(e);
    }
}
</script>
@endpush

@endsection
@extends('layouts.app')
@section('title', 'Beds')
@section('content')

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">Beds</h1>
    <p style="font-size:0.875rem; color:#8A9BB0; margin:0.25rem 0 0;">Gérez les lits des chambres dortoirs.</p>
</div>

{{-- Formulaire ajout rapide --}}
<div style="background:white; border-radius:1rem; padding:1.5rem; border:1px solid #E8EEF2; margin-bottom:1.5rem;">
    <h2 style="font-size:1rem; font-weight:700; color:#1A2B3C; margin:0 0 1rem;">Ajouter un lit</h2>

    @if($errors->any())
    <div style="background:#FEF2F2; border:1px solid #FECACA; color:#DC2626; border-radius:0.75rem; padding:1rem; margin-bottom:1rem; font-size:0.875rem;">
        @foreach($errors->all() as $e)<p style="margin:0.1rem 0;">• {{ $e }}</p>@endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('beds.store') }}"
          style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
        @csrf
        <div style="flex:1; min-width:200px;">
            <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">
                Chambre dortoir *
            </label>
            <select name="room_id" required
                    style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem; font-size:0.875rem;
                           outline:none; border:1.5px solid #D8E8F0; background:#F8FBFD; color:#1A2B3C; box-sizing:border-box;">
                <option value="">-- Sélectionner --</option>
                @foreach($rooms as $room)
                <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex:1; min-width:200px;">
            <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">
                Nom du lit *
            </label>
            <input type="text" name="name" placeholder="Ex: Lit A1" required
                   style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem; font-size:0.875rem;
                          outline:none; border:1.5px solid #D8E8F0; background:#F8FBFD; color:#1A2B3C; box-sizing:border-box;">
        </div>
        <button type="submit"
                style="padding:0.75rem 1.5rem; border-radius:0.75rem; font-size:0.875rem; font-weight:700;
                       color:white; border:none; cursor:pointer;
                       background:linear-gradient(135deg,#1A4A6B,#2C6E8A);
                       box-shadow:0 4px 15px rgba(44,110,138,0.3);">
            + Ajouter
        </button>
    </form>
</div>

{{-- Liste --}}
<div style="background:white; border-radius:1rem; border:1px solid #E8EEF2; overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
        <thead>
            <tr style="background:#F8FBFD;">
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Nom</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Chambre</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Statut</th>
                <th style="padding:1rem 1.25rem; text-align:right; font-weight:600; color:#5A6B7A;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($beds as $bed)
            <tr style="border-top:1px solid #F0F4F8;">
                <td style="padding:1rem 1.25rem; font-weight:600; color:#1A2B3C;">{{ $bed->name }}</td>
                <td style="padding:1rem 1.25rem; color:#5A6B7A;">{{ $bed->room->name }}</td>
                <td style="padding:1rem 1.25rem;">
                    <span id="bed-status-{{ $bed->id }}"
                          style="padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:500;
                                 {{ $bed->is_enabled ? 'background:#F0FDF4;color:#2A6B4F;' : 'background:#FEF2F2;color:#DC2626;' }}">
                        {{ $bed->is_enabled ? '✅ Actif' : '❌ Désactivé' }}
                    </span>
                </td>
                <td style="padding:1rem 1.25rem; text-align:right; white-space:nowrap;">
                    <button onclick="toggleBed({{ $bed->id }})"
                            id="bed-btn-{{ $bed->id }}"
                            style="font-size:0.75rem; font-weight:600; margin-right:0.75rem; border:none;
                                   cursor:pointer; border-radius:0.5rem; padding:0.3rem 0.75rem;
                                   {{ $bed->is_enabled ? 'background:#FEF2F2; color:#DC2626;' : 'background:#F0FDF4; color:#16A34A;' }}">
                        {{ $bed->is_enabled ? 'Désactiver' : 'Activer' }}
                    </button>
                    <button onclick="deleteBed({{ $bed->id }}, '{{ addslashes($bed->name) }}')"
                            style="font-size:0.75rem; font-weight:500; color:#DC2626; background:none; border:none; cursor:pointer;">
                        Supprimer
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:2.5rem; text-align:center; color:#8A9BB0;">
                    Aucun lit. Commencez par créer une chambre de type Dormitory.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
function toggleBed(id) {
    fetch('/beds/' + id + '/toggle', {   {{-- ← URL corrigée --}}
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        const statusSpan = document.getElementById('bed-status-' + id);
        const btn        = document.getElementById('bed-btn-' + id);

        if (data.is_enabled) {
            statusSpan.textContent = '✅ Actif';
            statusSpan.style.cssText = 'padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:500; background:#F0FDF4; color:#2A6B4F;';
            btn.textContent = 'Désactiver';
            btn.style.cssText = 'font-size:0.75rem; font-weight:600; margin-right:0.75rem; border:none; cursor:pointer; border-radius:0.5rem; padding:0.3rem 0.75rem; background:#FEF2F2; color:#DC2626;';
        } else {
            statusSpan.textContent = '❌ Désactivé';
            statusSpan.style.cssText = 'padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:500; background:#FEF2F2; color:#DC2626;';
            btn.textContent = 'Activer';
            btn.style.cssText = 'font-size:0.75rem; font-weight:600; margin-right:0.75rem; border:none; cursor:pointer; border-radius:0.5rem; padding:0.3rem 0.75rem; background:#F0FDF4; color:#16A34A;';
        }
    })
    .catch(err => console.error('Toggle error:', err));
}

function deleteBed(id, name) {
    Swal.fire({
        title: 'Supprimer "' + name + '" ?',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#DC2626', cancelButtonColor: '#6B7280',
        confirmButtonText: 'Supprimer', cancelButtonText: 'Annuler',
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST'; form.action = '/beds/' + id;
            var csrf = document.createElement('input');
            csrf.type = 'hidden'; csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').content;
            var method = document.createElement('input');
            method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
            form.appendChild(csrf); form.appendChild(method);
            document.body.appendChild(form); form.submit();
        }
    });
}
</script>
@endpush
@endsection
@extends('layouts.app')
@section('title', 'Gestion des lits')
@section('content')

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">Gestion des lits</h1>
    <p style="font-size:0.875rem; color:#8A9BB0; margin:0.25rem 0 0;">Organisez les lits de vos chambres dortoirs.</p>
</div>

{{-- ⚠️ Warning surcapacité (legacy data) --}}
@if($overCapacity->count() > 0)
<div style="background:#FFF7ED; border:1px solid #FED7AA; color:#9A3412; border-radius:1rem; padding:1rem 1.25rem; margin-bottom:1.5rem; font-size:0.875rem;">
    <p style="margin:0 0 0.5rem; font-weight:700;">⚠️ Chambre(s) en surcapacité détectée(s)</p>
    <p style="margin:0 0 0.5rem; font-size:0.8125rem;">
        Les chambres suivantes contiennent plus de lits que leur capacité ne le permet :
    </p>
    <ul style="margin:0.25rem 0 0 1rem; padding:0; font-size:0.8125rem;">
        @foreach($overCapacity as $r)
        <li>
            <strong>{{ $r->name }}</strong> : {{ $r->beds_count }} lit(s) pour une capacité de {{ $r->max_capacity }}
            (à supprimer : {{ $r->beds_count - $r->max_capacity }})
        </li>
        @endforeach
    </ul>
</div>
@endif

{{-- Erreurs globales (validation backend) --}}
@if($errors->any())
<div style="background:#FEF2F2; border:1px solid #FECACA; color:#DC2626; border-radius:0.75rem; padding:1rem 1.25rem; margin-bottom:1.5rem; font-size:0.875rem;">
    @foreach($errors->all() as $e)<p style="margin:0.1rem 0;">• {{ $e }}</p>@endforeach
</div>
@endif

{{-- Succès flash --}}
@if(session('success'))
<div style="background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; border-radius:0.75rem; padding:0.75rem 1.25rem; margin-bottom:1.5rem; font-size:0.875rem; font-weight:500;">
    ✅ {{ session('success') }}
</div>
@endif

{{-- ─── Aucune chambre dortoir ─── --}}
@if($dormitories->isEmpty())
<div style="background:white; border:1px dashed #C5D5E0; border-radius:1rem; padding:3rem 1.5rem; text-align:center;">
    <div style="font-size:2.5rem; margin-bottom:0.75rem;">🛏️</div>
    <p style="font-size:1rem; font-weight:700; color:#1A2B3C; margin:0 0 0.25rem;">Aucune chambre dortoir</p>
    <p style="font-size:0.875rem; color:#5A6B7A; margin:0;">
        Créez d'abord une chambre de type dortoir avant d'ajouter des lits.
    </p>
    <a href="{{ route('rooms.create') }}"
       style="display:inline-block; margin-top:1rem; padding:0.75rem 1.5rem; border-radius:0.75rem; font-size:0.875rem; font-weight:700; color:white; text-decoration:none; background:linear-gradient(135deg,#1A4A6B,#2C6E8A); box-shadow:0 4px 15px rgba(44,110,138,0.3);">
        + Créer une chambre
    </a>
</div>
@else

{{-- ─── Sections par chambre ─── --}}
@foreach($dormitories as $room)
    @php
        $isFull     = $room->beds_count >= $room->max_capacity;
        $isOver     = $room->beds_count > $room->max_capacity;
        $remaining  = max(0, $room->max_capacity - $room->beds_count);
    @endphp

    <div style="background:white; border-radius:1rem; border:1px solid #E8EEF2; margin-bottom:1.5rem; overflow:hidden;">
        {{-- Header chambre --}}
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #F0F4F8; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem; background:linear-gradient(135deg, #F8FBFD, white);">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <div style="width:42px; height:42px; border-radius:0.75rem; background:linear-gradient(135deg,#1A4A6B,#2C6E8A); display:flex; align-items:center; justify-content:center; color:white; font-size:1.125rem;">
                    🛏️
                </div>
                <div>
                    <h2 style="font-size:1.125rem; font-weight:700; color:#1A2B3C; margin:0;">{{ $room->name }}</h2>
                    <p style="font-size:0.75rem; color:#8A9BB0; margin:0.125rem 0 0;">
                        Capacité maximale : {{ $room->max_capacity }} lit(s)
                    </p>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:0.5rem;">
                @if($isOver)
                    <span style="padding:0.35rem 0.85rem; border-radius:9999px; font-size:0.75rem; font-weight:700; background:#FEF2F2; color:#DC2626; border:1px solid #FECACA;">
                        ⚠️ Surcapacité : {{ $room->beds_count }}/{{ $room->max_capacity }}
                    </span>
                @elseif($isFull)
                    <span style="padding:0.35rem 0.85rem; border-radius:9999px; font-size:0.75rem; font-weight:700; background:#FEF2F2; color:#DC2626; border:1px solid #FECACA;">
                        🔒 Pleine ({{ $room->beds_count }}/{{ $room->max_capacity }})
                    </span>
                @else
                    <span style="padding:0.35rem 0.85rem; border-radius:9999px; font-size:0.75rem; font-weight:700; background:#F0FDF4; color:#15803D; border:1px solid #BBF7D0;">
                        ✅ {{ $room->beds_count }}/{{ $room->max_capacity }} lits ({{ $remaining }} restant(s))
                    </span>
                @endif
            </div>
        </div>

        {{-- Liste des lits --}}
        @if($room->beds->isEmpty())
            <div style="padding:2rem; text-align:center; color:#8A9BB0; font-size:0.875rem;">
                Aucun lit pour le moment. Ajoutez le premier ↓
            </div>
        @else
            <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
                <thead>
                    <tr style="background:#F8FBFD;">
                        <th style="padding:0.875rem 1.5rem; text-align:left; font-weight:600; color:#5A6B7A; font-size:0.8125rem;">Nom du lit</th>
                        <th style="padding:0.875rem 1.5rem; text-align:left; font-weight:600; color:#5A6B7A; font-size:0.8125rem;">Statut</th>
                        <th style="padding:0.875rem 1.5rem; text-align:right; font-weight:600; color:#5A6B7A; font-size:0.8125rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($room->beds as $bed)
                    <tr style="border-top:1px solid #F0F4F8;">
                        <td style="padding:0.875rem 1.5rem; font-weight:600; color:#1A2B3C;">{{ $bed->name }}</td>
                        <td style="padding:0.875rem 1.5rem;">
                            <span id="bed-status-{{ $bed->id }}"
                                  style="padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:500;
                                         {{ $bed->is_enabled ? 'background:#F0FDF4;color:#2A6B4F;' : 'background:#FEF2F2;color:#DC2626;' }}">
                                {{ $bed->is_enabled ? '✅ Actif' : '❌ Désactivé' }}
                            </span>
                        </td>
                        <td style="padding:0.875rem 1.5rem; text-align:right; white-space:nowrap;">
                            <button onclick="toggleBed({{ $bed->id }})"
                                    id="bed-btn-{{ $bed->id }}"
                                    style="font-size:0.75rem; font-weight:600; margin-right:0.5rem; border:none;
                                           cursor:pointer; border-radius:0.5rem; padding:0.4rem 0.85rem;
                                           {{ $bed->is_enabled ? 'background:#FEF2F2; color:#DC2626;' : 'background:#F0FDF4; color:#16A34A;' }}">
                                {{ $bed->is_enabled ? 'Désactiver' : 'Activer' }}
                            </button>
                            <button onclick="deleteBed({{ $bed->id }}, '{{ addslashes($bed->name) }}')"
                                    style="font-size:0.75rem; font-weight:600; color:white; background:#DC2626; border:none; cursor:pointer; border-radius:0.5rem; padding:0.4rem 0.85rem;">
                                🗑️ Supprimer
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- Footer : bouton ajouter --}}
        <div style="padding:1rem 1.5rem; background:#FAFCFD; border-top:1px solid #F0F4F8;">
            @if($isFull || $isOver)
                <button disabled
                        style="padding:0.625rem 1.25rem; border-radius:0.625rem; font-size:0.8125rem; font-weight:700;
                               color:#8A9BB0; border:1px dashed #C5D5E0; background:#F8FBFD; cursor:not-allowed; width:100%;">
                    🔒 Capacité atteinte — augmentez la capacité de la chambre pour ajouter des lits
                </button>
            @else
                <button type="button"
                        onclick="addBedToRoom({{ $room->id }}, '{{ addslashes($room->name) }}', {{ $remaining }})"
                        style="padding:0.625rem 1.5rem; border-radius:0.625rem; font-size:0.8125rem; font-weight:700;
                               color:white; border:none; cursor:pointer;
                               background:linear-gradient(135deg,#1A4A6B,#2C6E8A);
                               box-shadow:0 4px 12px rgba(44,110,138,0.25); transition:transform 0.15s;"
                        onmouseover="this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                    + Ajouter un lit
                </button>
            @endif
        </div>
    </div>
@endforeach

@endif

{{-- Form caché pour soumettre l'ajout --}}
<form id="add-bed-form" method="POST" action="{{ route('beds.store') }}" style="display:none;">
    @csrf
    <input type="hidden" name="room_id" id="add-bed-room-id">
    <input type="hidden" name="name"    id="add-bed-name">
</form>

@push('scripts')
<script>
function addBedToRoom(roomId, roomName, remaining) {
    Swal.fire({
        title: 'Ajouter un lit',
        html: '<p style="margin:0; color:#5A6B7A; font-size:0.875rem;">Chambre : <strong>' + roomName + '</strong></p>'
            + '<p style="margin:0.25rem 0 0; color:#8A9BB0; font-size:0.8125rem;">Places restantes : ' + remaining + '</p>',
        input: 'text',
        inputLabel: 'Nom du lit',
        inputPlaceholder: 'Ex: Lit A1',
        showCancelButton: true,
        confirmButtonText: 'Ajouter',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#2C6E8A',
        cancelButtonColor: '#6B7280',
        inputValidator: function (value) {
            if (!value || !value.trim()) return 'Le nom du lit est obligatoire.';
            if (value.length > 100)       return 'Le nom est trop long (max 100 caractères).';
        }
    }).then(function (result) {
        if (result.isConfirmed) {
            document.getElementById('add-bed-room-id').value = roomId;
            document.getElementById('add-bed-name').value    = result.value.trim();
            document.getElementById('add-bed-form').submit();
        }
    });
}

function toggleBed(id) {
    fetch('/beds/' + id + '/toggle', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        var statusSpan = document.getElementById('bed-status-' + id);
        var btn        = document.getElementById('bed-btn-' + id);

        if (data.is_enabled) {
            statusSpan.textContent = '✅ Actif';
            statusSpan.style.cssText = 'padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:500; background:#F0FDF4; color:#2A6B4F;';
            btn.textContent = 'Désactiver';
            btn.style.cssText = 'font-size:0.75rem; font-weight:600; margin-right:0.5rem; border:none; cursor:pointer; border-radius:0.5rem; padding:0.4rem 0.85rem; background:#FEF2F2; color:#DC2626;';
        } else {
            statusSpan.textContent = '❌ Désactivé';
            statusSpan.style.cssText = 'padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:500; background:#FEF2F2; color:#DC2626;';
            btn.textContent = 'Activer';
            btn.style.cssText = 'font-size:0.75rem; font-weight:600; margin-right:0.5rem; border:none; cursor:pointer; border-radius:0.5rem; padding:0.4rem 0.85rem; background:#F0FDF4; color:#16A34A;';
        }
    })
    .catch(function (err) { console.error('Toggle error:', err); });
}

function deleteBed(id, name) {
    Swal.fire({
        title: 'Supprimer "' + name + '" ?',
        text: 'Cette action est irréversible.',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#DC2626', cancelButtonColor: '#6B7280',
        confirmButtonText: 'Supprimer', cancelButtonText: 'Annuler',
    }).then(function (result) {
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
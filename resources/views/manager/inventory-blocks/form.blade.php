@extends('layouts.app')
@section('title', isset($block) ? 'Modifier l\'indisponibilité' : 'Ajouter une indisponibilité')
@section('content')

@php
    $isEdit   = isset($block);
    $action   = $isEdit
        ? route('manager.inventory-blocks.update', $block)
        : route('manager.inventory-blocks.store');
    $method   = $isEdit ? 'PUT' : 'POST';

    $blockTypeLabels = [
        'maintenance'  => 'Maintenance',
        'manual_block' => 'Blocage manuel',
    ];
    $reasonSuggestions = ['plumbing', 'painting', 'owner_request', 'damaged', 'cleaning', 'renovation'];
@endphp

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">
        {{ $isEdit ? 'Modifier l\'indisponibilité' : 'Ajouter une indisponibilité' }}
    </h1>
    <a href="{{ route('manager.inventory-blocks.index') }}"
       style="padding:0.625rem 1.25rem; border-radius:0.75rem; font-size:0.875rem; font-weight:500;
              color:#5A6B7A; text-decoration:none; background:#F8FBFD; border:1px solid #E8EEF2;">
        ← Retour
    </a>
</div>

@if($errors->any())
<div style="background:#FEF2F2; border:1px solid #FECACA; color:#DC2626; border-radius:0.75rem;
            padding:1rem; margin-bottom:1rem; font-size:0.875rem;">
    @foreach($errors->all() as $e)
        <p style="margin:0.1rem 0;">• {{ $e }}</p>
    @endforeach
</div>
@endif

<div style="background:white; border-radius:1rem; padding:1.75rem; border:1px solid #E8EEF2; max-width:680px;">
    <form method="POST" action="{{ $action }}">
        @csrf
        @method($method)

        @php
            $field = 'width:100%; border-radius:0.75rem; padding:0.75rem 1rem; font-size:0.875rem;
                      border:1.5px solid #D8E8F0; background:#F8FBFD; color:#1A2B3C;
                      box-sizing:border-box; outline:none; margin-bottom:1.25rem;';
            $label = 'display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;';
        @endphp

        {{-- Élément bloquable --}}
        <div>
            <label style="{{ $label }}">Type d'élément *</label>
            <select name="blockable_type" id="blockable_type" required style="{{ $field }}"
                    onchange="filterBlockables()">
                <option value="">-- Sélectionner --</option>
                <option value="room"       {{ old('blockable_type', $block->blockable_type ?? '') === 'room'       ? 'selected' : '' }}>Chambre</option>
                <option value="bed"        {{ old('blockable_type', $block->blockable_type ?? '') === 'bed'        ? 'selected' : '' }}>Lit</option>
                <option value="tent_space" {{ old('blockable_type', $block->blockable_type ?? '') === 'tent_space' ? 'selected' : '' }}>Espace tente</option>
            </select>
        </div>

        <div>
            <label style="{{ $label }}">Élément *</label>
            <select name="blockable_id" id="blockable_id" required style="{{ $field }}">
                <option value="">-- Choisir d'abord le type --</option>

                {{-- Rooms --}}
                @foreach($rooms as $room)
                <option value="{{ $room->id }}" data-type="room"
                        {{ old('blockable_id', $block->blockable_id ?? '') == $room->id && old('blockable_type', $block->blockable_type ?? '') === 'room' ? 'selected' : '' }}>
                    {{ $room->name }} ({{ $room->type === 'private' ? 'Privée' : 'Dortoir' }})
                </option>
                @endforeach

                {{-- Beds --}}
                @foreach($beds as $bed)
                <option value="{{ $bed->id }}" data-type="bed"
                        {{ old('blockable_id', $block->blockable_id ?? '') == $bed->id && old('blockable_type', $block->blockable_type ?? '') === 'bed' ? 'selected' : '' }}>
                    {{ $bed->name }} — {{ $bed->room->name }}
                </option>
                @endforeach

                {{-- Tent spaces --}}
                @foreach($tentSpaces as $ts)
                <option value="{{ $ts->id }}" data-type="tent_space"
                        {{ old('blockable_id', $block->blockable_id ?? '') == $ts->id && old('blockable_type', $block->blockable_type ?? '') === 'tent_space' ? 'selected' : '' }}>
                    {{ $ts->name }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Type de blocage --}}
        <div>
            <label style="{{ $label }}">Type de blocage *</label>
            <select name="block_type" required style="{{ $field }}">
                @foreach($blockTypeLabels as $val => $lbl)
                <option value="{{ $val }}" {{ old('block_type', $block->block_type ?? '') === $val ? 'selected' : '' }}>
                    {{ $lbl }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Dates --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            <div>
                <label style="{{ $label }}">Date de début *</label>
                <input type="date" name="start_date" required style="{{ $field }}"
                       value="{{ old('start_date', isset($block) ? $block->start_date->format('Y-m-d') : '') }}">
            </div>
            <div>
                <label style="{{ $label }}">Date de fin <span style="color:#8A9BB0;font-weight:400;">(optionnel)</span></label>
                <input type="date" name="end_date" style="{{ $field }}"
                       value="{{ old('end_date', isset($block) && $block->end_date ? $block->end_date->format('Y-m-d') : '') }}">
                <p style="font-size:0.75rem; color:#8A9BB0; margin:-0.75rem 0 1.25rem;">
                    Laisser vide = blocage indéfini
                </p>
            </div>
        </div>

        {{-- Motif --}}
        <div>
            <label style="{{ $label }}">Motif court <span style="color:#8A9BB0;font-weight:400;">(optionnel)</span></label>
            <input type="text" name="reason" style="{{ $field }}" maxlength="100"
                   placeholder="Ex: plumbing, painting, owner_request"
                   value="{{ old('reason', $block->reason ?? '') }}">
            <p style="font-size:0.75rem; color:#8A9BB0; margin:-0.75rem 0 1.25rem;">
                Suggestions :
                @foreach($reasonSuggestions as $s)
                    <span style="cursor:pointer; color:#2C6E8A; text-decoration:underline;"
                          onclick="document.querySelector('[name=reason]').value='{{ $s }}'">{{ $s }}</span>{{ !$loop->last ? ', ' : '' }}
                @endforeach
            </p>
        </div>

        {{-- Note --}}
        <div>
            <label style="{{ $label }}">Note détaillée <span style="color:#8A9BB0;font-weight:400;">(optionnel)</span></label>
            <textarea name="note" rows="3" style="{{ $field }}"
                      placeholder="Ex: Fuite détectée dans la salle de bain, maintenance prévue lundi matin.">{{ old('note', $block->note ?? '') }}</textarea>
        </div>

        <div style="display:flex; gap:1rem;">
            <button type="submit"
                    style="padding:0.75rem 1.5rem; border-radius:0.75rem; font-size:0.875rem; font-weight:700;
                           color:white; border:none; cursor:pointer;
                           background:linear-gradient(135deg,#1A4A6B,#2C6E8A);
                           box-shadow:0 4px 15px rgba(44,110,138,0.3);">
                {{ $isEdit ? 'Mettre à jour' : 'Enregistrer' }}
            </button>
            <a href="{{ route('manager.inventory-blocks.index') }}"
               style="padding:0.75rem 1.5rem; border-radius:0.75rem; font-size:0.875rem; font-weight:600;
                      color:#5A6B7A; text-decoration:none; background:#F8FBFD; border:1px solid #E8EEF2;">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
function filterBlockables() {
    const type   = document.getElementById('blockable_type').value;
    const select = document.getElementById('blockable_id');
    const options = select.querySelectorAll('option[data-type]');

    options.forEach(opt => {
        opt.style.display = (!type || opt.dataset.type === type) ? '' : 'none';
    });

    // Réinitialise si la sélection actuelle ne correspond plus au type
    const current = select.querySelector('option:checked');
    if (current && current.dataset.type && current.dataset.type !== type) {
        select.value = '';
    }
}

// Init au chargement (mode edit)
document.addEventListener('DOMContentLoaded', filterBlockables);
</script>
@endsection
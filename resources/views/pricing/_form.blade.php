{{--
    Formulaire partagé Sprint 2 — utilisé par manager/pricing/create et manager/pricing/edit
    Variable optionnelle : $price (pour l'édition)
--}}
@php $input = "w-full rounded-xl px-4 py-3 text-sm outline-none transition-all"; $style = "border:1.5px solid #D8E8F0;background:#F8FBFD;color:#1A2B3C;"; @endphp

<div>
    <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Type d'élément *</label>
    <select name="priceable_type" required class="{{ $input }}" style="{{ $style }}">
        <option value="room"
            {{ old('priceable_type', $price->priceable_type ?? '') === 'room' ? 'selected' : '' }}>
            🚪 Chambre
        </option>
        <option value="tent_space"
            {{ old('priceable_type', $price->priceable_type ?? '') === 'tent_space' ? 'selected' : '' }}>
            ⛺ Espace tente
        </option>
        <option value="extra"
            {{ old('priceable_type', $price->priceable_type ?? '') === 'extra' ? 'selected' : '' }}>
            🛒 Extra
        </option>
    </select>
</div>

<div>
    <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Élément tarifé *</label>
    <select name="priceable_id" required class="{{ $input }}" style="{{ $style }}">
        @foreach($rooms as $r)
            <option value="{{ $r->id }}"
                {{ old('priceable_id', $price->priceable_id ?? '') == $r->id ? 'selected' : '' }}>
                🚪 {{ $r->name }} ({{ $r->type }})
            </option>
        @endforeach
        @foreach($tentSpaces as $ts)
            <option value="{{ $ts->id }}"
                {{ old('priceable_id', $price->priceable_id ?? '') == $ts->id ? 'selected' : '' }}>
                ⛺ {{ $ts->name }}
            </option>
        @endforeach
        @foreach($extras as $ex)
            <option value="{{ $ex->id }}"
                {{ old('priceable_id', $price->priceable_id ?? '') == $ex->id ? 'selected' : '' }}>
                🛒 {{ $ex->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Mode de tarification *</label>
    <select name="pricing_mode" required class="{{ $input }}" style="{{ $style }}">
        @foreach(['per_room','per_bed','per_person','per_unit','per_night','per_person_per_night'] as $mode)
            <option value="{{ $mode }}"
                {{ old('pricing_mode', $price->pricing_mode ?? '') === $mode ? 'selected' : '' }}>
                {{ str_replace('_', ' ', $mode) }}
            </option>
        @endforeach
    </select>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Prix HT *</label>
        <input type="number" name="price_ht" step="0.001" min="0" required
               value="{{ old('price_ht', $price->price_ht ?? 0) }}"
               class="{{ $input }}" style="{{ $style }}">
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Prix TTC *</label>
        <input type="number" name="price_ttc" step="0.001" min="0" required
               value="{{ old('price_ttc', $price->price_ttc ?? 0) }}"
               class="{{ $input }}" style="{{ $style }}">
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Valide du *</label>
        <input type="date" name="valid_from" required
               value="{{ old('valid_from', isset($price) ? $price->valid_from?->format('Y-m-d') : '') }}"
               class="{{ $input }}" style="{{ $style }}">
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Valide jusqu'au</label>
        <input type="date" name="valid_to"
               value="{{ old('valid_to', isset($price) ? $price->valid_to?->format('Y-m-d') : '') }}"
               class="{{ $input }}" style="{{ $style }}">
    </div>
</div>

@if(isset($taxes) && $taxes->count() > 0)
<div>
    <label class="block text-sm font-semibold mb-2" style="color:#1A2B3C;">Taxes associées</label>
    <div class="space-y-2">
        @foreach($taxes as $tax)
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="tax_ids[]" value="{{ $tax->id }}"
                   {{ in_array($tax->id, old('tax_ids', isset($price) ? $price->taxes->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                   style="width:1.1rem;height:1.1rem;accent-color:#2C6E8A;">
            <span class="text-sm font-medium" style="color:#1A2B3C;">
                {{ $tax->name }}
                ({{ $tax->type === 'percentage' ? $tax->amount.'%' : $tax->amount.' TND' }})
            </span>
        </label>
        @endforeach
    </div>
</div>
@endif
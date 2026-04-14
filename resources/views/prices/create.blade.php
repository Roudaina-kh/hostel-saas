@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl">
    <h1 class="text-2xl font-black text-slate-800 mb-6">Ajouter un tarif</h1>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-bold">
            @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('prices.store') }}" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-5">
        @csrf

        {{-- Type d'élément --}}
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Type d'élément</label>
            <select name="priceable_type" id="priceable_type" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-200 outline-none"
                    onchange="updatePriceableOptions()">
                <option value="">-- Choisir --</option>
                <option value="room" {{ old('priceable_type') === 'room' ? 'selected' : '' }}>Chambre</option>
                <option value="tent_space" {{ old('priceable_type') === 'tent_space' ? 'selected' : '' }}>Espace tente</option>
                <option value="extra" {{ old('priceable_type') === 'extra' ? 'selected' : '' }}>Extra</option>
            </select>
        </div>

        {{-- Élément --}}
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Élément</label>
            <select name="priceable_id" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-200 outline-none">
                <option value="">-- Sélectionner le type d'abord --</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" data-type="room" {{ old('priceable_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->name }} ({{ $room->type }})
                    </option>
                @endforeach
                @foreach($tentSpaces as $ts)
                    <option value="{{ $ts->id }}" data-type="tent_space" {{ old('priceable_id') == $ts->id ? 'selected' : '' }}>
                        {{ $ts->name }}
                    </option>
                @endforeach
                @foreach($extras as $extra)
                    <option value="{{ $extra->id }}" data-type="extra" {{ old('priceable_id') == $extra->id ? 'selected' : '' }}>
                        {{ $extra->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Mode de calcul --}}
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Mode de calcul</label>
            <select name="pricing_mode" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-200 outline-none">
                <option value="per_room" {{ old('pricing_mode') === 'per_room' ? 'selected' : '' }}>Par chambre</option>
                <option value="per_bed" {{ old('pricing_mode') === 'per_bed' ? 'selected' : '' }}>Par lit</option>
                <option value="per_person" {{ old('pricing_mode') === 'per_person' ? 'selected' : '' }}>Par personne</option>
                <option value="per_unit" {{ old('pricing_mode') === 'per_unit' ? 'selected' : '' }}>Par unité</option>
                <option value="per_night" {{ old('pricing_mode') === 'per_night' ? 'selected' : '' }}>Par nuit</option>
                <option value="per_person_per_night" {{ old('pricing_mode') === 'per_person_per_night' ? 'selected' : '' }}>Par personne / nuit</option>
            </select>
        </div>

        {{-- Prix HT et TTC --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Prix HT</label>
                <input type="number" name="price_ht" step="0.001" min="0" value="{{ old('price_ht', 0) }}" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Prix TTC</label>
                <input type="number" name="price_ttc" step="0.001" min="0" value="{{ old('price_ttc', 0) }}" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-200 outline-none">
            </div>
        </div>

        {{-- Validité --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Valide à partir du</label>
                <input type="date" name="valid_from" value="{{ old('valid_from') }}" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Valide jusqu'au <span class="text-slate-400 font-normal">(optionnel)</span></label>
                <input type="date" name="valid_to" value="{{ old('valid_to') }}"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-200 outline-none">
            </div>
        </div>

        {{-- Taxes --}}
        @if($taxes->count() > 0)
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Taxes applicables</label>
            <div class="space-y-2">
                @foreach($taxes as $tax)
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="tax_ids[]" value="{{ $tax->id }}"
                               {{ in_array($tax->id, old('tax_ids', [])) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-slate-300 text-blue-600">
                        <span class="text-sm font-medium text-slate-700">
                            {{ $tax->name }}
                            <span class="text-slate-400">({{ $tax->type }} — {{ $tax->amount }})</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all">
                Créer le tarif
            </button>
            <a href="{{ route('prices.index') }}"
               class="px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-all">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
function updatePriceableOptions() {
    const type = document.getElementById('priceable_type').value;
    const select = document.querySelector('select[name="priceable_id"]');
    const options = select.querySelectorAll('option[data-type]');
    options.forEach(opt => {
        opt.style.display = (!type || opt.dataset.type === type) ? '' : 'none';
    });
    select.value = '';
}
updatePriceableOptions();
</script>
@endsection
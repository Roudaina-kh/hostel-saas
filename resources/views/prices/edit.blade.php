@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl">
    <h1 class="text-2xl font-black text-slate-800 mb-6">Modifier le tarif</h1>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-bold">
            @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('prices.update', $price) }}" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-5">
        @csrf @method('PUT')

        {{-- Élément (lecture seule) --}}
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Élément</label>
            <input type="text" value="{{ $price->priceable?->name }} ({{ $price->priceable_type }})" disabled
                   class="w-full border border-slate-100 rounded-xl px-4 py-3 text-sm bg-slate-50 text-slate-500">
            <input type="hidden" name="priceable_type" value="{{ $price->priceable_type }}">
            <input type="hidden" name="priceable_id" value="{{ $price->priceable_id }}">
        </div>

        {{-- Mode de calcul --}}
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Mode de calcul</label>
            <select name="pricing_mode" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-200 outline-none">
                @foreach(['per_room','per_bed','per_person','per_unit','per_night','per_person_per_night'] as $mode)
                    <option value="{{ $mode }}" {{ old('pricing_mode', $price->pricing_mode) === $mode ? 'selected' : '' }}>
                        {{ $mode }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Prix --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Prix HT</label>
                <input type="number" name="price_ht" step="0.001" min="0"
                       value="{{ old('price_ht', $price->price_ht) }}" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Prix TTC</label>
                <input type="number" name="price_ttc" step="0.001" min="0"
                       value="{{ old('price_ttc', $price->price_ttc) }}" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-200 outline-none">
            </div>
        </div>

        {{-- Validité --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Valide à partir du</label>
                <input type="date" name="valid_from"
                       value="{{ old('valid_from', $price->valid_from?->format('Y-m-d')) }}" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-200 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Valide jusqu'au</label>
                <input type="date" name="valid_to"
                       value="{{ old('valid_to', $price->valid_to?->format('Y-m-d')) }}"
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
                               {{ $price->taxes->contains($tax->id) ? 'checked' : '' }}
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
                Mettre à jour
            </button>
            <a href="{{ route('prices.index') }}"
               class="px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-all">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
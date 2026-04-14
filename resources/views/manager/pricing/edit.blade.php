@extends('layouts.app')
@section('title', 'Modifier le tarif (Manager)')
@section('content')

<div class="max-w-xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold" style="color: #1A2B3C;">Modifier le tarif</h1>
        <p class="text-sm mt-1" style="color: #8A9BB0;">Modifiez les paramètres du tarif.</p>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-sm" style="border: 1px solid #E8EEF2;">
        @if($errors->any())
        <div class="rounded-xl p-4 mb-6 text-sm" style="background:#FEF2F2;border:1px solid #FECACA;color:#DC2626;">
            @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
        </div>
        @endif

        {{-- Variable corrigée : $price au lieu de $pricing --}}
        <form method="POST" action="{{ route('manager.pricing.update', $price) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold mb-2">Type d'élément *</label>
                <select name="priceable_type" required
                        class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none">
                    <option value="room"       {{ old('priceable_type', $price->priceable_type) === 'room'       ? 'selected' : '' }}>Chambre</option>
                    <option value="tent_space" {{ old('priceable_type', $price->priceable_type) === 'tent_space' ? 'selected' : '' }}>Espace tente</option>
                    <option value="extra"      {{ old('priceable_type', $price->priceable_type) === 'extra'      ? 'selected' : '' }}>Extra</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Élément tarifé *</label>
                <select name="priceable_id" required
                        class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none">
                    @foreach($rooms as $r)
                        <option value="{{ $r->id }}" {{ old('priceable_id', $price->priceable_id) == $r->id ? 'selected' : '' }}>
                            🚪 {{ $r->name }}
                        </option>
                    @endforeach
                    @foreach($tentSpaces as $ts)
                        <option value="{{ $ts->id }}" {{ old('priceable_id', $price->priceable_id) == $ts->id ? 'selected' : '' }}>
                            ⛺ {{ $ts->name }}
                        </option>
                    @endforeach
                    @foreach($extras as $ex)
                        <option value="{{ $ex->id }}" {{ old('priceable_id', $price->priceable_id) == $ex->id ? 'selected' : '' }}>
                            🛒 {{ $ex->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Mode de tarification *</label>
                <select name="pricing_mode" required
                        class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none">
                    @foreach(['per_room','per_bed','per_person','per_unit','per_night','per_person_per_night'] as $mode)
                        <option value="{{ $mode }}" {{ old('pricing_mode', $price->pricing_mode) === $mode ? 'selected' : '' }}>
                            {{ str_replace('_', ' ', $mode) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Prix HT *</label>
                    <input type="number" name="price_ht" step="0.001" min="0" required
                           value="{{ old('price_ht', $price->price_ht) }}"
                           class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Prix TTC *</label>
                    <input type="number" name="price_ttc" step="0.001" min="0" required
                           value="{{ old('price_ttc', $price->price_ttc) }}"
                           class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Valide du *</label>
                    <input type="date" name="valid_from" required
                           value="{{ old('valid_from', $price->valid_from?->format('Y-m-d')) }}"
                           class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Valide jusqu'au</label>
                    <input type="date" name="valid_to"
                           value="{{ old('valid_to', $price->valid_to?->format('Y-m-d')) }}"
                           class="w-full rounded-xl px-4 py-3 bg-[#F8FBFD] border border-[#D8E8F0] outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Taxes associées</label>
                <div class="space-y-2">
                    @foreach($taxes as $tax)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="tax_ids[]" value="{{ $tax->id }}"
                               {{ in_array($tax->id, old('tax_ids', $price->taxes->pluck('id')->toArray())) ? 'checked' : '' }}
                               class="w-4 h-4 accent-[#2C6E8A]">
                        <span class="text-sm font-medium text-[#1A2B3C]">
                            {{ $tax->name }}
                            ({{ $tax->type === 'percentage' ? $tax->amount.'%' : $tax->amount.' TND' }})
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold text-white"
                        style="background: linear-gradient(135deg, #1A4A6B, #2C6E8A); box-shadow:0 4px 15px rgba(44,110,138,0.3);">
                    Mettre à jour
                </button>
                <a href="{{ route('manager.pricing.index') }}"
                   class="px-6 py-2.5 rounded-xl text-sm font-medium"
                   style="background:#F8FBFD; color:#5A6B7A; border:1px solid #E8EEF2;">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
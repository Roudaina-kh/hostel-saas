@extends('layouts.app')
@section('title', 'Créer un hostel')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ajouter un hostel</h1>
            <p class="text-sm text-gray-500 mt-0.5">Remplissez les informations de votre nouvel établissement</p>
        </div>
        <a href="{{ route('hostels.index') }}" class="text-sm text-blue-600 hover:underline">← Retour</a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
            <ul class="list-disc ml-4 space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('hostels.store') }}">
        @csrf

        {{-- 1. Identité --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center">1</span>
                <span class="font-semibold text-gray-800">Identité</span>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1 md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Nom du hostel <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Ex : Hostel La Médina"
                           class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Type d'établissement</label>
                    <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="hostel" {{ old('type') === 'hostel' ? 'selected' : '' }}>🏨 Hostel</option>
                        <option value="camping" {{ old('type') === 'camping' ? 'selected' : '' }}>🏕 Camping</option>
                        <option value="mixed" {{ old('type') === 'mixed' ? 'selected' : '' }}>🌿 Mixte</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Devise par défaut</label>
                    <select name="default_currency" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="TND" {{ old('default_currency', 'TND') === 'TND' ? 'selected' : '' }}>TND — Dinar Tunisien</option>
                        <option value="EUR" {{ old('default_currency') === 'EUR' ? 'selected' : '' }}>EUR — Euro</option>
                        <option value="USD" {{ old('default_currency') === 'USD' ? 'selected' : '' }}>USD — Dollar</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1 md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Description</label>
                    <textarea name="description" rows="3"
                              placeholder="Décrivez votre établissement (emplacement, ambiance, services…)"
                              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- 2. Localisation --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center">2</span>
                <span class="font-semibold text-gray-800">Localisation</span>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Champ caché soumis : region_id (ville si choisie, sinon gouvernorat) --}}
                <input type="hidden" name="region_id" id="regionIdFinal" value="{{ old('region_id') }}" required>

{{-- 🏛 Gouvernorat — datalist searchable (sans pollution Chrome) --}}
<div class="flex flex-col gap-1">
    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
        Gouvernorat <span class="text-red-500">*</span>
    </label>
    <input type="text" id="gouvernoratInput" list="gouvernoratList"
           name="gouvernorat_search_{{ uniqid() }}"
           placeholder="Tapez pour chercher (ex: Tun)"
           autocomplete="off"
           autocorrect="off"
           autocapitalize="off"
           spellcheck="false"
           data-form-type="other"
           data-lpignore="true"
           data-1p-ignore="true"
           class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    <datalist id="gouvernoratList">
        @foreach($regions as $gouvernorat)
            <option value="{{ $gouvernorat->name }}"></option>
        @endforeach
    </datalist>
</div>

{{-- 🏘 Ville — datalist filtrée selon le gouvernorat --}}
<div class="flex flex-col gap-1">
    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
        Ville <span class="text-gray-400 font-normal">(optionnel)</span>
    </label>
    <input type="text" id="villeInput" list="villeList"
           name="ville_search_{{ uniqid() }}"
           placeholder="Choisissez d'abord un gouvernorat"
           autocomplete="off"
           autocorrect="off"
           autocapitalize="off"
           spellcheck="false"
           data-form-type="other"
           data-lpignore="true"
           data-1p-ignore="true"
           disabled
           class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
    <datalist id="villeList"></datalist>
</div>          {{-- 📍 Adresse --}}
                <div class="flex flex-col gap-1 md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Adresse</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                           placeholder="Rue, numéro, quartier…"
                           class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Pays figé en hidden = Tunisie --}}
                <input type="hidden" name="country" value="Tunisie">

                {{-- 🌐 Coordonnées --}}
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Latitude</label>
                    <input type="text" name="latitude" value="{{ old('latitude') }}"
                           placeholder="Ex : 36.8189"
                           class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Longitude</label>
                    <input type="text" name="longitude" value="{{ old('longitude') }}"
                           placeholder="Ex : 10.1658"
                           class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- 3. Contact --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center">3</span>
                <span class="font-semibold text-gray-800">Contact</span>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="+216 XX XXX XXX"
                           class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="hostel@example.com"
                           class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('hostels.index') }}"
               class="px-6 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit"
                    class="flex items-center gap-2 bg-blue-600 text-white px-8 py-2.5 rounded-lg font-semibold text-sm hover:bg-blue-700">
                ✅ Créer le hostel
            </button>
        </div>
    </form>
</div>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- Préparation des données régions pour le JavaScript                  --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
@php
    $regionsData = $regions->map(fn($g) => [
        'id'     => $g->id,
        'name'   => $g->name,
        'cities' => $g->children->map(fn($c) => [
            'id'   => $c->id,
            'name' => $c->name,
        ])->values(),
    ])->values();
@endphp

<script>
// ── Données régions injectées depuis Blade ──────────────────────────────────
const REGIONS = @json($regionsData);

const govInput      = document.getElementById('gouvernoratInput');
const villeInput    = document.getElementById('villeInput');
const villeList     = document.getElementById('villeList');
const regionIdFinal = document.getElementById('regionIdFinal');

let currentGov = null;

// Recherche d'un gouvernorat par nom exact (insensible à la casse)
function findGov(name) {
    if (!name) return null;
    const n = name.trim().toLowerCase();
    return REGIONS.find(g => g.name.toLowerCase() === n) || null;
}

// Quand l'utilisateur tape ou choisit dans le datalist gouvernorat
govInput.addEventListener('input', function() {
    const gov = findGov(this.value);
    if (gov) {
        currentGov = gov;
        regionIdFinal.value = gov.id; // par défaut → id du gouvernorat

        // Active et remplit la datalist villes
        villeInput.disabled = false;
        villeInput.value = '';
        villeInput.placeholder = 'Tapez pour chercher (ex: Mar)';
        villeList.innerHTML = '';
        gov.cities.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.name;
            villeList.appendChild(opt);
        });
    } else {
        currentGov = null;
        regionIdFinal.value = '';
        villeInput.disabled = true;
        villeInput.value = '';
        villeInput.placeholder = "Choisissez d'abord un gouvernorat";
        villeList.innerHTML = '';
    }
});

// Quand l'utilisateur choisit/tape une ville
villeInput.addEventListener('input', function() {
    if (!currentGov) return;
    const n = this.value.trim().toLowerCase();
    const ville = currentGov.cities.find(c => c.name.toLowerCase() === n);
    if (ville) {
        regionIdFinal.value = ville.id;       // ville plus précise → écrase le gouvernorat
    } else {
        regionIdFinal.value = currentGov.id;  // pas de ville valide → on retombe sur le gouvernorat
    }
});

// ── Restauration après erreur de validation (old('region_id')) ──────────────
document.addEventListener('DOMContentLoaded', function() {
    const oldRegionId = '{{ old("region_id") }}';
    if (!oldRegionId) return;

    // Cherche d'abord parmi les gouvernorats
    const gov = REGIONS.find(g => String(g.id) === String(oldRegionId));
    if (gov) {
        govInput.value = gov.name;
        govInput.dispatchEvent(new Event('input'));
        return;
    }

    // Sinon, parmi les villes
    for (const g of REGIONS) {
        const ville = g.cities.find(c => String(c.id) === String(oldRegionId));
        if (ville) {
            govInput.value = g.name;
            govInput.dispatchEvent(new Event('input'));
            setTimeout(() => {
                villeInput.value = ville.name;
                villeInput.dispatchEvent(new Event('input'));
            }, 50);
            return;
        }
    }
});
</script>

@endsection
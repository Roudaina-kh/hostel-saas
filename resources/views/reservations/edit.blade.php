@extends('layouts.app')

@section('title', 'Modifier la réservation #' . $reservation->id)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Modifier la réservation <span class="text-gray-400 font-normal">#{{ $reservation->id }}</span></h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $hostel->name }}</p>
        </div>
        <a href="{{ route('reservations.index') }}" class="text-sm text-blue-600 hover:underline">← Retour aux réservations</a>
    </div>

    @if($errors->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">{{ $errors->first('error') }}</div>
    @endif
    @if($errors->any() && !$errors->has('error'))
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg px-4 py-3 mb-4 text-sm">
            <ul class="list-disc ml-4 space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reservations.update', $reservation->id) }}" id="reservation_form">
        @csrf
        @method('PUT')
        <input type="hidden" name="guests_data" id="guests_data">
        <input type="hidden" name="extras_data" id="extras_data">

        {{-- 1. Informations réservation --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">1</span>
                <span class="font-semibold text-gray-800">Informations réservation</span>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Date d'arrivée <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="start_date" min="2026-01-01"
                               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ old('start_date', $reservation->start_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Date de départ <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" id="end_date"
                               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ old('end_date', $reservation->end_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Nuits</label>
                        <input type="number" name="nights" id="nights" readonly
                               value="{{ $reservation->nights }}"
                               class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-500">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Personnes <span class="text-red-500">*</span></label>
                        <input type="number" name="total_guests" id="total_guests"
                               value="{{ old('total_guests', $reservation->total_guests) }}" min="1"
                               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Statut</label>
                        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending"   @selected(old('status', $reservation->status) === 'pending')>Pending</option>
                            <option value="confirmed" @selected(old('status', $reservation->status) === 'confirmed')>Confirmed</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Source</label>
                        <select name="source" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="walk-in"  @selected(old('source', $reservation->source) === 'walk-in')>Walk-in</option>
                            <option value="booking"  @selected(old('source', $reservation->source) === 'booking')>Booking.com</option>
                            <option value="airbnb"   @selected(old('source', $reservation->source) === 'airbnb')>Airbnb</option>
                            <option value="other"    @selected(old('source', $reservation->source) === 'other')>Autre</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Notes internes</label>
                    <textarea name="notes" rows="2" placeholder="Information interne..."
                              class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes', $reservation->notes) }}</textarea>
                </div>
            </div>
        </div>

        {{-- 2. Guests & Affectations --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">2</span>
                <span class="font-semibold text-gray-800">Guests &amp; Affectations</span>
                <span id="availability_notice" class="hidden ml-auto text-xs text-blue-600 bg-blue-50 border border-blue-200 px-3 py-1 rounded-full">
                    ✓ Disponibilité mise à jour
                </span>
            </div>
            <div class="p-5">
                <div class="flex gap-5">
                    <div class="w-48 flex-shrink-0">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Guests</p>
                        <ul id="guest-list" class="space-y-1.5"></ul>
                    </div>
                    <div class="flex-1 min-w-0" id="guest-details"></div>
                </div>
            </div>
        </div>

        {{-- ✅ 3. Extras (optionnel) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">3</span>
                <span class="font-semibold text-gray-800">Extras</span>
                <span class="text-gray-400 font-normal text-sm ml-1">(optionnel)</span>
                <span id="extras_total_badge" class="hidden ml-auto text-xs font-semibold bg-green-100 text-green-700 px-3 py-1 rounded-full"></span>
            </div>
            <div class="p-5">
                @if(isset($extras) && $extras->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($extras as $extra)
                            @php
                                $isTracked    = in_array($extra->stock_mode, ['consumable', 'rentable']);
                                $existingQty  = $existingExtras[$extra->id] ?? 0;
                                $stock        = $extra->stock_quantity + $existingQty; // stock réel + quantité déjà allouée
                                $isAvailable  = !$isTracked || $stock > 0;
                            @endphp
                            <div class="border border-gray-200 rounded-xl p-4 {{ $isAvailable ? 'bg-white hover:border-blue-300 transition' : 'bg-gray-50 opacity-60' }}">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800 text-sm">{{ $extra->name }}</p>
                                        @if($extra->description)
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $extra->description }}</p>
                                        @endif
                                    </div>
                                    @if($isTracked)
                                        <span class="ml-2 text-xs px-2 py-0.5 rounded-full font-medium flex-shrink-0
                                            {{ $stock > 5 ? 'bg-green-100 text-green-700' : ($stock > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-600') }}">
                                            {{ $stock > 0 ? "Stock : {$stock}" : 'Rupture' }}
                                        </span>
                                    @else
                                        <span class="ml-2 text-xs px-2 py-0.5 rounded-full font-medium bg-blue-100 text-blue-700 flex-shrink-0">∞ Illimité</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <label class="text-xs text-gray-500 font-medium">Quantité :</label>
                                    <input type="number"
                                           class="extra-qty-input w-20 border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-400"
                                           min="0"
                                           max="{{ $isTracked ? $stock : 999 }}"
                                           value="{{ $existingQty }}"
                                           data-extra-id="{{ $extra->id }}"
                                           data-max="{{ $isTracked ? $stock : 999 }}"
                                           {{ !$isAvailable ? 'disabled' : '' }}>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 text-sm italic text-center py-6">
                        🛒 Aucun extra disponible pour cet hostel.
                    </p>
                @endif
            </div>
        </div>

        {{-- 4. Récapitulatif --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">4</span>
                <span class="font-semibold text-gray-800">Récapitulatif tarification</span>
            </div>
            <div class="p-5">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                                <th class="text-left px-3 py-2 font-semibold rounded-l-lg">Guest</th>
                                <th class="text-left px-3 py-2 font-semibold">Affectation</th>
                                <th class="text-left px-3 py-2 font-semibold">Prix saisi</th>
                                <th class="text-left px-3 py-2 font-semibold">Devise</th>
                                <th class="text-left px-3 py-2 font-semibold rounded-r-lg">≈ TND</th>
                            </tr>
                        </thead>
                        <tbody id="summary_body">
                            <tr><td colspan="5" class="px-3 py-6 text-center text-gray-400 italic">Chargement...</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                    <div class="flex justify-between text-sm"><span class="text-gray-600">Total TND</span><strong id="total_tnd">0.000</strong></div>
                    <div class="flex justify-between text-sm"><span class="text-gray-600">Total EUR</span><strong id="total_eur">0.000</strong></div>
                    <div class="flex justify-between text-sm"><span class="text-gray-600">Total USD</span><strong id="total_usd">0.000</strong></div>
                </div>
                @php
                    $eurRate = $rates->get('EUR');
                    $usdRate = $rates->get('USD');
                    $eurSell = $eurRate ? number_format((float)$eurRate->sell_rate_to_tnd, 4, '.', '') : null;
                    $eurBuy  = $eurRate ? number_format((float)$eurRate->buy_rate_to_tnd,  4, '.', '') : null;
                    $usdSell = $usdRate ? number_format((float)$usdRate->sell_rate_to_tnd, 4, '.', '') : null;
                    $usdBuy  = $usdRate ? number_format((float)$usdRate->buy_rate_to_tnd,  4, '.', '') : null;
                @endphp
                <div class="mt-4 bg-blue-50 border border-blue-100 rounded-lg p-3">
                    <p class="text-xs font-bold text-blue-700 uppercase tracking-wide mb-1.5">Taux de change (vente)</p>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-700">
                        <span>
                            <span class="inline-block bg-blue-600 text-white text-xs font-bold px-2 py-0.5 rounded mr-1">EUR</span>
                            Vente : <strong>{{ $eurSell ?? '—' }}</strong> &nbsp;|&nbsp; Achat : <strong>{{ $eurBuy ?? '—' }}</strong>
                        </span>
                        <span>
                            <span class="inline-block bg-blue-600 text-white text-xs font-bold px-2 py-0.5 rounded mr-1">USD</span>
                            Vente : <strong>{{ $usdSell ?? '—' }}</strong> &nbsp;|&nbsp; Achat : <strong>{{ $usdBuy ?? '—' }}</strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. Confirmation --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">5</span>
                <span class="font-semibold text-gray-800">Confirmation &amp; Sécurité</span>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Modifié par <span class="text-red-500">*</span></label>
                        <select name="added_by_user_id" id="added_by_user_id"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" @selected($member->id === auth()->id())>{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Mot de passe <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password_input"
                               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Votre mot de passe" autocomplete="current-password">
                        <p id="password_status" class="text-xs min-h-4 mt-1"></p>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-400">Le mot de passe doit être validé pour appliquer les modifications.</p>
                    <button type="submit" id="submit_btn" disabled
                            class="flex items-center gap-2 bg-blue-600 text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        ENREGISTRER LES MODIFICATIONS
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

@php
    $jsEurSell = $eurRate ? (float)$eurRate->sell_rate_to_tnd : 0;
    $jsEurBuy  = $eurRate ? (float)$eurRate->buy_rate_to_tnd  : 0;
    $jsUsdSell = $usdRate ? (float)$usdRate->sell_rate_to_tnd : 0;
    $jsUsdBuy  = $usdRate ? (float)$usdRate->buy_rate_to_tnd  : 0;
@endphp

<script>
var ROOMS           = {!! json_encode($rooms->load('beds')) !!};
var TENT_SPACES     = {!! json_encode($tentSpaces) !!};
var COUNTRIES       = {!! json_encode($countries) !!};
var EXISTING_GUESTS = {!! json_encode($existingGuests) !!};
var EXISTING_EXTRAS = {!! json_encode($existingExtras ?? []) !!};

var RATES = {
    eur: { sell: {{ $jsEurSell }}, buy: {{ $jsEurBuy }} },
    usd: { sell: {{ $jsUsdSell }}, buy: {{ $jsUsdBuy }} }
};
var ROUTES = {
    availableUnits: '{{ route("reservations.available-units") }}',
    checkPassword:  '{{ route("reservations.check-password") }}',
    csrf:           '{{ csrf_token() }}'
};
var TODAY = new Date().toISOString().split('T')[0];

// ── Gestion extras ────────────────────────────────────────────────────────
var extrasMap = {};

// Initialiser avec les extras existants
Object.keys(EXISTING_EXTRAS).forEach(function(id) {
    extrasMap[id] = EXISTING_EXTRAS[id];
});

function updateExtrasBadge() {
    var count = 0, total = 0;
    Object.keys(extrasMap).forEach(function(id) {
        var q = parseInt(extrasMap[id]) || 0;
        if (q > 0) { count++; total += q; }
    });
    var badge = document.getElementById('extras_total_badge');
    if (!badge) return;
    if (count > 0) { badge.textContent = count + ' extra(s) — ' + total + ' unité(s)'; badge.classList.remove('hidden'); }
    else badge.classList.add('hidden');
}

function serializeExtras() {
    var result = [];
    Object.keys(extrasMap).forEach(function(id) {
        var qty = parseInt(extrasMap[id]) || 0;
        if (qty > 0) result.push({ extra_id: parseInt(id), quantity: qty });
    });
    return JSON.stringify(result);
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Extras : écoute les inputs quantité ───────────────────────────────
    document.querySelectorAll('.extra-qty-input').forEach(function(input) {
        input.addEventListener('input', function() {
            var extraId = this.dataset.extraId;
            var max     = parseInt(this.dataset.max || 999);
            var val     = parseInt(this.value) || 0;
            if (val < 0) { val = 0; this.value = 0; }
            if (val > max) { val = max; this.value = max; }
            extrasMap[extraId] = val;
            updateExtrasBadge();
        });
        // Init badge avec valeurs existantes
        var id = input.dataset.extraId;
        if (extrasMap[id] && parseInt(extrasMap[id]) > 0) {
            updateExtrasBadge();
        }
    });

    var guests         = [];
    var selectedIdx    = 0;
    var availableUnits = buildStaticUnits();

    var $start    = document.getElementById('start_date');
    var $end      = document.getElementById('end_date');
    var $nights   = document.getElementById('nights');
    var $nbGuests = document.getElementById('total_guests');
    var $list     = document.getElementById('guest-list');
    var $detail   = document.getElementById('guest-details');
    var $tbody    = document.getElementById('summary_body');
    var $tTnd     = document.getElementById('total_tnd');
    var $tEur     = document.getElementById('total_eur');
    var $tUsd     = document.getElementById('total_usd');
    var $pwd      = document.getElementById('password_input');
    var $btn      = document.getElementById('submit_btn');
    var $pwdSt    = document.getElementById('password_status');
    var $addedBy  = document.getElementById('added_by_user_id');
    var $form     = document.getElementById('reservation_form');
    var $gdata    = document.getElementById('guests_data');
    var $edata    = document.getElementById('extras_data');
    var $notice   = document.getElementById('availability_notice');

    calcNights();

    function buildStaticUnits() {
        var beds = [], rooms = [], tents = [];
        ROOMS.forEach(function(r) {
            if (r.type === 'dormitory') {
                (r.beds || []).forEach(function(b) {
                    if (b.is_enabled !== false) beds.push({ id: b.id, name: r.name + ' — ' + b.name });
                });
            } else if (r.type === 'private') {
                rooms.push({ id: r.id, name: r.name, capacity: r.capacity || 1, remaining_capacity: r.capacity || 1 });
            }
        });
        TENT_SPACES.forEach(function(s) {
            tents.push({ id: s.id, name: s.name, capacity: s.capacity || 1, remaining_capacity: s.capacity || 1 });
        });
        return { beds: beds, rooms: rooms, tent_spaces: tents };
    }

    function emptyGuest(i) {
        return {
            first_name: '', last_name: '', identity_card: '', email: '', phone: '',
            country_id: COUNTRIES.length ? COUNTRIES[0].id : '',
            gender: 'male', same_as_main: i !== 0,
            item_type: 'bed', item_id: '',
            price_input: 0, currency: 'TND', price_tnd: 0, exchange_rate: 1
        };
    }

    function calcNights() {
        if (!$start.value || !$end.value) { $nights.value = 0; return; }
        var d = (new Date($end.value) - new Date($start.value)) / 86400000;
        $nights.value = d > 0 ? Math.round(d) : 0;
    }

    function initFromExisting() {
        if (EXISTING_GUESTS && EXISTING_GUESTS.length > 0) {
            guests = EXISTING_GUESTS.map(function(g, i) {
                return {
                    first_name:    g.first_name    || '',
                    last_name:     g.last_name     || '',
                    identity_card: g.identity_card || '',
                    email:         g.email         || '',
                    phone:         g.phone         || '',
                    country_id:    g.country_id    || (COUNTRIES.length ? COUNTRIES[0].id : ''),
                    gender:        g.gender        || 'male',
                    same_as_main:  i !== 0,
                    item_type:     g.item_type     || 'bed',
                    item_id:       String(g.item_id || ''),
                    price_input:   parseFloat(g.price_input) || 0,
                    currency:      g.currency      || 'TND',
                    price_tnd:     parseFloat(g.price_tnd)   || 0,
                    exchange_rate: parseFloat(g.exchange_rate) || 1,
                };
            });
            $nbGuests.value = guests.length;
        } else {
            guests = [emptyGuest(0)];
        }
        renderList(); renderDetail(); calcTotals();
    }

    function generateGuests(n) {
        var old = guests.slice();
        guests = [];
        for (var i = 0; i < n; i++) guests.push(old[i] !== undefined ? old[i] : emptyGuest(i));
        selectedIdx = Math.min(selectedIdx, guests.length - 1);
        renderList(); renderDetail(); calcTotals();
    }

    function renderList() {
        $list.innerHTML = '';
        guests.forEach(function(g, i) {
            var li = document.createElement('li');
            var name = g.first_name ? ' ' + g.first_name : '';
            li.textContent = (g.item_id ? '✅' : '⚠️') + ' ' +
                (i === 0 ? 'Guest 1 (Principal)' : 'Guest ' + (i + 1)) + name;
            li.className = 'px-3 py-2 rounded-lg text-sm cursor-pointer border transition-all ' +
                (i === selectedIdx
                    ? 'bg-blue-50 border-blue-300 text-blue-700 font-medium'
                    : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100');
            li.addEventListener('click', (function(idx){ return function(){ selectedIdx = idx; renderList(); renderDetail(); }; })(i));
            $list.appendChild(li);
        });
    }

    function renderDetail() {
        var g = guests[selectedIdx];
        var isMain = selectedIdx === 0;
        var label  = isMain ? 'Guest Principal' : 'Guest ' + (selectedIdx + 1);
        var copts  = COUNTRIES.map(function(c) {
            return '<option value="' + c.id + '"' + (Number(g.country_id) === Number(c.id) ? ' selected' : '') + '>' + esc(c.name) + '</option>';
        }).join('');
        var hasDates = $start.value && $end.value;

        $detail.innerHTML =
            '<div class="border border-gray-200 rounded-xl p-4">' +
            '<div class="flex items-center justify-between mb-3">' +
            '<h3 class="font-semibold text-gray-800">' + esc(label) + '</h3>' +
            '<span id="assign_badge" class="text-xs font-semibold px-2 py-0.5 rounded-full ' +
                (g.item_id ? 'bg-green-100 text-green-700' : 'bg-red-50 text-red-600') + '">' +
                (g.item_id ? 'Affecté ✓' : 'Non affecté') +
            '</span></div>' +
            (!isMain ? '<label class="flex items-center gap-2 text-sm text-gray-600 mb-3 cursor-pointer select-none"><input type="checkbox" id="same_as_main" ' + (g.same_as_main ? 'checked' : '') + ' class="w-4 h-4 rounded border-gray-300 text-blue-600 cursor-pointer">Même informations que le guest principal</label>' : '') +
            '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">' +
            '<div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Informations</p>' +
            '<input type="text" id="first_name" placeholder="Nom *" value="' + esc(g.first_name) + '" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">' +
            '<input type="text" id="last_name" placeholder="Prénom *" value="' + esc(g.last_name) + '" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">' +
            '<input type="text" id="identity_card" placeholder="CIN / Passeport" value="' + esc(g.identity_card) + '" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">' +
            '<input type="email" id="email" placeholder="Email" value="' + esc(g.email) + '" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">' +
            '<input type="tel" id="phone" placeholder="Téléphone" value="' + esc(g.phone) + '" inputmode="numeric" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">' +
            '<select id="gender" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400"><option value="male"' + (g.gender === 'male' ? ' selected' : '') + '>Homme</option><option value="female"' + (g.gender === 'female' ? ' selected' : '') + '>Femme</option></select>' +
            '<select id="country_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">' + copts + '</select></div>' +
            '<div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Affectation</p>' +
            '<select id="item_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">' +
            '<option value="bed"' + (g.item_type === 'bed' ? ' selected' : '') + '>🛏 Dormitory (lit)</option>' +
            '<option value="room"' + (g.item_type === 'room' ? ' selected' : '') + '>🚪 Chambre privée</option>' +
            '<option value="tent_space"' + (g.item_type === 'tent_space' ? ' selected' : '') + '>⛺ Tente</option>' +
            '</select>' +
            '<select id="item_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></select>' +
            '<p id="avail_status" class="text-xs mt-2 ' + (hasDates ? 'text-green-600' : 'text-gray-400') + '">' + (hasDates ? '✓ Disponibilité filtrée selon les dates.' : '⚠ Sélectionnez des dates pour filtrer.') + '</p></div>' +
            '<div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Tarification</p>' +
            '<input type="number" id="price_input" value="' + g.price_input + '" min="0" step="0.001" placeholder="Prix" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">' +
            '<select id="currency" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">' +
            '<option value="TND"' + (g.currency === 'TND' ? ' selected' : '') + '>TND — Dinar</option>' +
            '<option value="EUR"' + (g.currency === 'EUR' ? ' selected' : '') + '>EUR — Euro</option>' +
            '<option value="USD"' + (g.currency === 'USD' ? ' selected' : '') + '>USD — Dollar</option>' +
            '</select>' +
            '<div id="tnd_box" class="bg-green-50 border border-green-200 rounded-lg px-3 py-2 text-sm text-green-800">' +
            '≈ <strong><span id="tnd_disp">' + Number(g.price_tnd).toFixed(3) + '</span> TND</strong><br>' +
            '<span class="text-xs text-green-600">Taux (vente) : <span id="rate_disp">' + g.exchange_rate + '</span></span></div>' +
            '</div></div></div>';

        bindInputs();
        loadUnits();
        calcPrice();
    }

    function bindInputs() {
        var g = guests[selectedIdx];
        var isMain = selectedIdx === 0;
        ['first_name','last_name','identity_card','gender','country_id'].forEach(function(f) {
            var el = document.getElementById(f);
            if (!el) return;
            var fn = function() { g[f] = el.value; if (isMain) propagate(); calcTotals(); renderList(); };
            el.addEventListener('input', fn);
            el.addEventListener('change', fn);
        });
        var emailEl = document.getElementById('email');
        if (emailEl) emailEl.addEventListener('input', function() { g.email = this.value; if (isMain) propagate(); });
        var phoneEl = document.getElementById('phone');
        if (phoneEl) phoneEl.addEventListener('input', function() {
            var clean = this.value.replace(/[^0-9+\s\-()]/g, '');
            if (this.value !== clean) this.value = clean;
            g.phone = clean; if (isMain) propagate();
        });
        if (!isMain) {
            var cb = document.getElementById('same_as_main');
            if (cb) cb.addEventListener('change', function() { g.same_as_main = this.checked; if (this.checked) { copyMain(g); renderDetail(); } });
        }
        var typeEl = document.getElementById('item_type');
        if (typeEl) typeEl.addEventListener('change', function() { g.item_type = this.value; g.item_id = ''; loadUnits(); renderList(); calcTotals(); });
        var unitEl = document.getElementById('item_id');
        if (unitEl) unitEl.addEventListener('change', function() {
            g.item_id = this.value; renderList(); calcTotals();
            var badge = document.getElementById('assign_badge');
            if (badge) { badge.className = 'text-xs font-semibold px-2 py-0.5 rounded-full ' + (g.item_id ? 'bg-green-100 text-green-700' : 'bg-red-50 text-red-600'); badge.textContent = g.item_id ? 'Affecté ✓' : 'Non affecté'; }
        });
        var priceEl = document.getElementById('price_input');
        if (priceEl) priceEl.addEventListener('input', function() { g.price_input = parseFloat(this.value) || 0; calcPrice(); calcTotals(); });
        var currEl = document.getElementById('currency');
        if (currEl) currEl.addEventListener('change', function() { g.currency = this.value; calcPrice(); calcTotals(); });
    }

    function copyMain(t) { var m = guests[0]; ['first_name','last_name','identity_card','email','phone','country_id','gender'].forEach(function(f){ t[f]=m[f]; }); }
    function propagate() { guests.forEach(function(g,i){ if (i && g.same_as_main) copyMain(g); }); }

    function loadUnits() {
        var g  = guests[selectedIdx];
        var el = document.getElementById('item_id');
        if (!el) return;
        el.innerHTML = '<option value="">— Sélectionner une unité —</option>';
        var lists = { bed: availableUnits.beds, room: availableUnits.rooms, tent_space: availableUnits.tent_spaces };
        var list  = lists[g.item_type] || [];
        if (list.length === 0) {
            var no = document.createElement('option'); no.disabled = true; no.textContent = 'Aucune unité disponible'; el.appendChild(no); return;
        }
        list.forEach(function(u) {
            var opt = document.createElement('option');
            opt.value = String(u.id);
            opt.textContent = u.remaining_capacity !== undefined ? u.name + ' (' + u.remaining_capacity + ' place(s) libre(s))' : u.name;
            if (String(g.item_id) === String(u.id)) opt.selected = true;
            el.appendChild(opt);
        });
        var currentValid = g.item_id && list.some(function(u){ return String(u.id) === String(g.item_id); });
        if (currentValid) {
            el.value = String(g.item_id);
        } else {
            g.item_id = String(list[0].id); el.value = g.item_id;
            var badge = document.getElementById('assign_badge');
            if (badge) { badge.className = 'text-xs font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700'; badge.textContent = 'Affecté ✓'; }
            renderList(); calcTotals();
        }
    }

    function calcPrice() {
        var g = guests[selectedIdx];
        var p = parseFloat(g.price_input) || 0;
        if (g.currency === 'TND')      { g.exchange_rate = 1;              g.price_tnd = p; }
        else if (g.currency === 'EUR') { g.exchange_rate = RATES.eur.sell; g.price_tnd = p * g.exchange_rate; }
        else if (g.currency === 'USD') { g.exchange_rate = RATES.usd.sell; g.price_tnd = p * g.exchange_rate; }
        var dispEl = document.getElementById('tnd_disp');
        var rateEl = document.getElementById('rate_disp');
        if (dispEl) dispEl.textContent = Number(g.price_tnd).toFixed(3);
        if (rateEl) rateEl.textContent = g.currency === 'TND' ? '1 (TND)' : String(g.exchange_rate);
    }

    function calcTotals() {
        var tnd = 0, eur = 0, usd = 0;
        $tbody.innerHTML = '';
        guests.forEach(function(g, i) {
            tnd += Number(g.price_tnd) || 0;
            if (g.currency === 'EUR') eur += Number(g.price_input) || 0;
            if (g.currency === 'USD') usd += Number(g.price_input) || 0;
            var tr = document.createElement('tr');
            tr.className = 'border-b border-gray-50 hover:bg-gray-50 transition';
            tr.innerHTML = '<td class="px-3 py-2 text-sm">' + (esc(g.first_name) || '—') + ' ' + esc(g.last_name) + '</td>' +
                '<td class="px-3 py-2 text-sm text-gray-500">' + getUnitLabel(g) + '</td>' +
                '<td class="px-3 py-2 text-sm">' + Number(g.price_input).toFixed(3) + '</td>' +
                '<td class="px-3 py-2 text-sm"><span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded font-medium">' + g.currency + '</span></td>' +
                '<td class="px-3 py-2 text-sm font-semibold">' + Number(g.price_tnd).toFixed(3) + '</td>';
            $tbody.appendChild(tr);
        });
        $tTnd.textContent = tnd.toFixed(3);
        $tEur.textContent = eur.toFixed(3);
        $tUsd.textContent = usd.toFixed(3);
    }

    function getUnitLabel(g) {
        if (!g.item_id) return '<span class="text-red-400 text-xs italic">Non affecté</span>';
        var m = { bed: availableUnits.beds, room: availableUnits.rooms, tent_space: availableUnits.tent_spaces };
        var item = (m[g.item_type] || []).find(function(u){ return String(u.id) === String(g.item_id); });
        return item ? esc(item.name) : 'Unité sélectionnée';
    }

    function fetchAvailability() {
        if (!$start.value || !$end.value) { availableUnits = buildStaticUnits(); renderDetail(); renderList(); calcTotals(); return; }
        fetch(ROUTES.availableUnits + '?start_date=' + $start.value + '&end_date=' + $end.value + '&reservation_id={{ $reservation->id }}')
            .then(function(r){ if (!r.ok) throw new Error(); return r.json(); })
            .then(function(data){
                availableUnits = data;
                guests.forEach(function(g){
                    var listMap = { bed: data.beds || [], room: data.rooms || [], tent_space: data.tent_spaces || [] };
                    var list = listMap[g.item_type] || [];
                    if (!list.some(function(u){ return String(u.id) === String(g.item_id); })) g.item_id = '';
                });
                if ($notice) $notice.classList.remove('hidden');
                renderDetail(); renderList(); calcTotals();
            })
            .catch(function(){ availableUnits = buildStaticUnits(); renderDetail(); renderList(); calcTotals(); });
    }

    var pwdTimer = null;
    $pwd.addEventListener('input', function() {
        clearTimeout(pwdTimer);
        $btn.disabled = true; $pwdSt.textContent = '';
        if (this.value.length < 4) return;
        var val = this.value;
        pwdTimer = setTimeout(function() {
            fetch(ROUTES.checkPassword, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': ROUTES.csrf },
                body: JSON.stringify({ added_by_user_id: $addedBy.value, password: val })
            })
            .then(function(r){ return r.json(); })
            .then(function(d){
                if (d.success) { $btn.disabled = false; $pwdSt.className = 'text-xs min-h-4 mt-1 text-green-600'; $pwdSt.textContent = '✅ Mot de passe correct'; }
                else { $btn.disabled = true; $pwdSt.className = 'text-xs min-h-4 mt-1 text-red-600'; $pwdSt.textContent = '❌ Mot de passe incorrect'; }
            })
            .catch(function(){ $btn.disabled = true; });
        }, 500);
    });
    $addedBy.addEventListener('change', function(){ $pwd.value = ''; $btn.disabled = true; $pwdSt.textContent = ''; });

    $form.addEventListener('submit', function(e) {
        for (var i = 0; i < guests.length; i++) {
            var g = guests[i];
            if (!g.first_name.trim()) { e.preventDefault(); alert('❌ Guest ' + (i+1) + ' : le nom est obligatoire.'); selectedIdx = i; renderList(); renderDetail(); return; }
            if (!g.last_name.trim())  { e.preventDefault(); alert('❌ Guest ' + (i+1) + ' : le prénom est obligatoire.'); selectedIdx = i; renderList(); renderDetail(); return; }
            if (!g.item_id) {
                var listMap = { bed: availableUnits.beds || [], room: availableUnits.rooms || [], tent_space: availableUnits.tent_spaces || [] };
                var list = listMap[g.item_type] || [];
                if (list.length > 0) { g.item_id = String(list[0].id); }
                else { e.preventDefault(); alert('❌ Guest ' + (i+1) + ' n\'a pas d\'affectation.'); selectedIdx = i; renderList(); renderDetail(); return; }
            }
        }
        calcTotals();
        $gdata.value = JSON.stringify(guests);
        // ✅ Sérialiser les extras
        if ($edata) $edata.value = serializeExtras();
    });

    $start.addEventListener('change', function() {
        if (this.value) { var next = new Date(this.value); next.setDate(next.getDate()+1); var ns = next.toISOString().split('T')[0]; $end.min = ns; if ($end.value && $end.value <= this.value) $end.value = ns; }
        calcNights(); fetchAvailability();
    });
    $end.addEventListener('change', function(){ calcNights(); fetchAvailability(); });
    $nbGuests.addEventListener('change', function() { var n = parseInt(this.value); if (!n || n < 1) { n = 1; this.value = 1; } generateGuests(n); });

    function esc(s) { if (!s) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;'); }

    initFromExisting();
    fetchAvailability();

});
</script>
@endsection
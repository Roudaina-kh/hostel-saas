@extends('layouts.app')

@section('breadcrumb', 'Paiements')
@section('page-title', 'Nouveau paiement')

@section('content')

@php
    $rp     = request()->routeIs('manager.*') ? 'manager.' : (request()->routeIs('staff.*') ? 'staff.' : '');
    $prefix = request()->routeIs('manager.*') ? '/manager' : (request()->routeIs('staff.*') ? '/staff' : '');
@endphp

<div style="max-width:720px">
    <div class="form-card">
        <form method="POST" action="{{ route($rp . 'payments.store') }}">
            @csrf

            {{-- ══ 1. Réservation ══════════════════════════════════ --}}
            <div class="form-section">
                <div class="form-section-title">1 · Réservation</div>
                <div class="form-group">
                    <label for="reservation_id">Réservation <span class="required">*</span></label>
                    <select name="reservation_id" id="reservation_id" required
                            class="@error('reservation_id') is-invalid @enderror">
                        <option value="">— Sélectionner —</option>
                        @foreach ($reservations as $res)
                            <option value="{{ $res->id }}"
                                {{ (old('reservation_id', $preselectedReservation?->id) == $res->id) ? 'selected' : '' }}>
                                #{{ $res->id }} — {{ $res->guest_name }}
                                ({{ \Carbon\Carbon::parse($res->start_date)->format('d/m/Y') }}
                                → {{ \Carbon\Carbon::parse($res->end_date)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('reservation_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" id="person-group" style="{{ $preselectedReservation ? '' : 'display:none' }}">
                    <label for="reservation_person_id">Paiement par personne</label>
                    <select name="reservation_person_id" id="reservation_person_id">
                        <option value="">— Paiement global (toute la réservation) —</option>
                        @if($preselectedReservation)
                            @foreach($preselectedReservation->people ?? $preselectedReservation->reservationPeople ?? [] as $person)
                                <option value="{{ $person->id }}"
                                    {{ old('reservation_person_id') == $person->id ? 'selected' : '' }}>
                                    {{ $person->first_name }} {{ $person->last_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <small style="color:var(--color-text-secondary)">Laisser vide pour un paiement global.</small>
                    @error('reservation_person_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- ══ 2. Montant & devise ═════════════════════════════ --}}
            <div class="form-section">
                <div class="form-section-title">2 · Montant</div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
                    <div class="form-group">
                        <label for="currency">Devise <span class="required">*</span></label>
                        <select name="currency" id="currency" required>
                            <option value="TND" {{ old('currency','TND') === 'TND' ? 'selected' : '' }}>TND</option>
                            <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>USD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exchange_rate">Taux de change <span class="required">*</span></label>
                        <input type="number" name="exchange_rate" id="exchange_rate"
                               value="{{ old('exchange_rate', 1.0000) }}"
                               step="0.0001" min="0.0001" max="9999.9999" required
                               class="@error('exchange_rate') is-invalid @enderror">
                        @error('exchange_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="amount_input">Montant saisi <span class="required">*</span></label>
                        <input type="number" name="amount_input" id="amount_input"
                               value="{{ old('amount_input') }}"
                               step="0.001" min="0.001" required placeholder="0.000"
                               class="@error('amount_input') is-invalid @enderror">
                        @error('amount_input')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="amount-preview" id="amount-preview" style="display:none">
                    <span>≈ TND :</span>
                    <strong id="amount-tnd-display" style="color:#534AB7;font-size:18px">—</strong>
                </div>
            </div>

            {{-- ══ 3. Infos paiement ══════════════════════════════ --}}
            <div class="form-section">
                <div class="form-section-title">3 · Informations paiement</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="form-group">
                        <label for="payment_method">Mode de paiement <span class="required">*</span></label>
                        <select name="payment_method" id="payment_method" required
                                class="@error('payment_method') is-invalid @enderror">
                            <option value="">— Choisir —</option>
                            <option value="cash"     {{ old('payment_method') === 'cash'     ? 'selected' : '' }}>💵 Espèces</option>
                            <option value="card"     {{ old('payment_method') === 'card'     ? 'selected' : '' }}>💳 Carte</option>
                            <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>🏦 Virement</option>
                            <option value="other"    {{ old('payment_method') === 'other'    ? 'selected' : '' }}>📝 Autre</option>
                        </select>
                        @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Statut <span class="required">*</span></label>
                        <select name="status" id="status" required
                                class="@error('status') is-invalid @enderror">
                            <option value="paid"    {{ old('status','paid') === 'paid'    ? 'selected' : '' }}>✅ Payé</option>
                            <option value="partial" {{ old('status') === 'partial' ? 'selected' : '' }}>⚠️ Partiel</option>
                            <option value="unpaid"  {{ old('status') === 'unpaid'  ? 'selected' : '' }}>❌ Non payé</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="received_by">Reçu par</label>
                    <input type="text" name="received_by" id="received_by"
                           value="{{ old('received_by') }}" maxlength="100" placeholder="Nom du réceptionniste…"
                           class="@error('received_by') is-invalid @enderror">
                    @error('received_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="note">Note</label>
                    <textarea name="note" id="note" rows="3" maxlength="1000" placeholder="Remarque optionnelle…"
                              class="@error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                    @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- ══ Actions ═════════════════════════════════════════ --}}
            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route($rp . 'payments.index') }}" class="btn-secondary">Annuler</a>
                <button type="submit" class="btn-primary">Enregistrer le paiement</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const RATES = { TND: 1.0000, EUR: 3.3800, USD: 3.1200 };
const currencySelect = document.getElementById('currency');
const rateInput      = document.getElementById('exchange_rate');
const amountInput    = document.getElementById('amount_input');
const previewBox     = document.getElementById('amount-preview');
const tndDisplay     = document.getElementById('amount-tnd-display');

function updateRate() {
    rateInput.value = currencySelect.value !== 'TND' ? (RATES[currencySelect.value] ?? 1) : '1.0000';
    updatePreview();
}
function updatePreview() {
    const amount = parseFloat(amountInput.value);
    const rate   = parseFloat(rateInput.value);
    if (!isNaN(amount) && !isNaN(rate) && amount > 0) {
        tndDisplay.textContent   = (amount * rate).toFixed(3) + ' TND';
        previewBox.style.display = 'flex';
    } else {
        previewBox.style.display = 'none';
    }
}
currencySelect.addEventListener('change', updateRate);
rateInput.addEventListener('input', updatePreview);
amountInput.addEventListener('input', updatePreview);

// ── AJAX personnes — URL dynamique selon le préfixe ──────────────
const reservationSelect = document.getElementById('reservation_id');
const personGroup       = document.getElementById('person-group');
const personSelect      = document.getElementById('reservation_person_id');
const urlPrefix         = '{{ $prefix }}'; // /manager ou /staff ou ''

reservationSelect.addEventListener('change', function () {
    const id = this.value;
    if (!id) {
        personGroup.style.display = 'none';
        personSelect.innerHTML = '<option value="">— Paiement global —</option>';
        return;
    }
    personSelect.innerHTML = '<option value="">Chargement…</option>';
    fetch(`${urlPrefix}/payments/reservation/${id}/people`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(people => {
        personSelect.innerHTML = '<option value="">— Paiement global —</option>';
        people.forEach(p => {
            const opt = document.createElement('option');
            opt.value       = p.id;
            opt.textContent = `${p.first_name} ${p.last_name}`;
            personSelect.appendChild(opt);
        });
        personGroup.style.display = people.length > 0 ? 'block' : 'none';
    })
    .catch(() => {
        personSelect.innerHTML = '<option value="">— Paiement global —</option>';
        personGroup.style.display = 'block';
    });
});

if (reservationSelect.value) updatePreview();
</script>

<style>
.form-card { background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);padding:24px }
.form-section { margin-bottom:24px;padding-bottom:24px;border-bottom:0.5px solid var(--color-border-tertiary) }
.form-section:last-of-type { border-bottom:none }
.form-section-title { font-weight:600;font-size:13px;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.05em;margin-bottom:16px }
.form-group { margin-bottom:12px }
.form-group label { display:block;font-size:12px;font-weight:500;color:var(--color-text-secondary);margin-bottom:4px }
.form-group input,.form-group select,.form-group textarea { width:100%;border:0.5px solid var(--color-border-secondary);border-radius:var(--border-radius-md);padding:8px 10px;font-size:13px;background:var(--color-background-primary);color:var(--color-text-primary) }
.form-group input:focus,.form-group select:focus,.form-group textarea:focus { outline:none;border-color:#534AB7 }
.required { color:#ef4444 }
.invalid-feedback { color:#ef4444;font-size:11px;margin-top:3px }
.is-invalid { border-color:#ef4444 !important }
.amount-preview { display:flex;align-items:center;gap:12px;padding:12px 16px;background:var(--color-background-info);border:0.5px solid var(--color-border-info);border-radius:var(--border-radius-md);margin-top:8px;font-size:14px }
.btn-primary { background:#534AB7;color:#fff;border:none;border-radius:var(--border-radius-md);padding:8px 16px;font-size:13px;font-weight:500;cursor:pointer;text-decoration:none }
.btn-secondary { background:var(--color-background-secondary);color:var(--color-text-primary);border:0.5px solid var(--color-border-secondary);border-radius:var(--border-radius-md);padding:8px 16px;font-size:13px;font-weight:500;cursor:pointer;text-decoration:none }
</style>
@endpush
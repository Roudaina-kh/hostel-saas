@extends('layouts.app')

@section('breadcrumb', 'Paiements')
@section('page-title', 'Modifier paiement #' . $payment->id)

@section('content')

@php
    $rp = request()->routeIs('manager.*') ? 'manager.' : (request()->routeIs('staff.*') ? 'staff.' : '');
@endphp

<div style="max-width:720px">

    <div style="background:var(--color-background-info);border:0.5px solid var(--color-border-info);
                border-radius:var(--border-radius-md);padding:12px 16px;margin-bottom:20px;font-size:13px">
        🔒 <strong>Réservation et personne non modifiables</strong> après création :
        Réservation <strong>#{{ $payment->reservation_id }}</strong>
        @if($payment->reservationPerson)
            · {{ $payment->reservationPerson->first_name }} {{ $payment->reservationPerson->last_name }}
        @else
            · Paiement global
        @endif
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route($rp . 'payments.update', $payment) }}">
            @csrf @method('PUT')

            <div class="form-section">
                <div class="form-section-title">Montant</div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
                    <div class="form-group">
                        <label for="currency">Devise *</label>
                        <select name="currency" id="currency" required>
                            <option value="TND" {{ old('currency', $payment->currency) === 'TND' ? 'selected' : '' }}>TND</option>
                            <option value="EUR" {{ old('currency', $payment->currency) === 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="USD" {{ old('currency', $payment->currency) === 'USD' ? 'selected' : '' }}>USD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exchange_rate">Taux de change *</label>
                        <input type="number" name="exchange_rate" id="exchange_rate"
                               value="{{ old('exchange_rate', $payment->exchange_rate) }}"
                               step="0.0001" min="0.0001" required
                               class="@error('exchange_rate') is-invalid @enderror">
                        @error('exchange_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="amount_input">Montant saisi *</label>
                        <input type="number" name="amount_input" id="amount_input"
                               value="{{ old('amount_input', $payment->amount_input) }}"
                               step="0.001" min="0.001" required
                               class="@error('amount_input') is-invalid @enderror">
                        @error('amount_input')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="amount-preview">
                    ≈ TND : <strong id="amount-tnd-display" style="color:#534AB7;font-size:16px">
                        {{ number_format($payment->amount_tnd, 3) }} TND
                    </strong>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Informations paiement</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="form-group">
                        <label for="payment_method">Mode *</label>
                        <select name="payment_method" id="payment_method" required>
                            <option value="cash"     {{ old('payment_method', $payment->payment_method) === 'cash'     ? 'selected' : '' }}>💵 Espèces</option>
                            <option value="card"     {{ old('payment_method', $payment->payment_method) === 'card'     ? 'selected' : '' }}>💳 Carte</option>
                            <option value="transfer" {{ old('payment_method', $payment->payment_method) === 'transfer' ? 'selected' : '' }}>🏦 Virement</option>
                            <option value="other"    {{ old('payment_method', $payment->payment_method) === 'other'    ? 'selected' : '' }}>📝 Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Statut *</label>
                        <select name="status" id="status" required>
                            <option value="paid"    {{ old('status', $payment->status) === 'paid'    ? 'selected' : '' }}>✅ Payé</option>
                            <option value="partial" {{ old('status', $payment->status) === 'partial' ? 'selected' : '' }}>⚠️ Partiel</option>
                            <option value="unpaid"  {{ old('status', $payment->status) === 'unpaid'  ? 'selected' : '' }}>❌ Non payé</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="received_by">Reçu par</label>
                    <input type="text" name="received_by" id="received_by"
                           value="{{ old('received_by', $payment->received_by) }}" maxlength="100">
                </div>
                <div class="form-group">
                    <label for="note">Note</label>
                    <textarea name="note" id="note" rows="3">{{ old('note', $payment->note) }}</textarea>
                </div>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end">
                <a href="{{ route($rp . 'payments.show', $payment) }}" class="btn-secondary">Annuler</a>
                <button type="submit" class="btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const RATES = { TND: 1.0000, EUR: 3.3800, USD: 3.1200 };
const currencyEl = document.getElementById('currency');
const rateEl     = document.getElementById('exchange_rate');
const amountEl   = document.getElementById('amount_input');
const display    = document.getElementById('amount-tnd-display');

function update() {
    const a = parseFloat(amountEl.value), r = parseFloat(rateEl.value);
    if (!isNaN(a) && !isNaN(r)) display.textContent = (a * r).toFixed(3) + ' TND';
}
currencyEl.addEventListener('change', () => {
    rateEl.value = currencyEl.value !== 'TND' ? (RATES[currencyEl.value] ?? 1) : '1.0000';
    update();
});
rateEl.addEventListener('input', update);
amountEl.addEventListener('input', update);
</script>
<style>
.form-card { background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);padding:24px }
.form-section { margin-bottom:24px;padding-bottom:24px;border-bottom:0.5px solid var(--color-border-tertiary) }
.form-section-title { font-weight:600;font-size:12px;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:.05em;margin-bottom:14px }
.form-group { margin-bottom:12px }
.form-group label { display:block;font-size:12px;font-weight:500;color:var(--color-text-secondary);margin-bottom:4px }
.form-group input,.form-group select,.form-group textarea { width:100%;border:0.5px solid var(--color-border-secondary);border-radius:var(--border-radius-md);padding:8px 10px;font-size:13px;background:var(--color-background-primary);color:var(--color-text-primary) }
.is-invalid { border-color:#ef4444 !important }
.invalid-feedback { color:#ef4444;font-size:11px;margin-top:3px }
.amount-preview { display:flex;align-items:center;gap:12px;padding:10px 14px;background:var(--color-background-info);border:0.5px solid var(--color-border-info);border-radius:var(--border-radius-md);margin-top:8px;font-size:13px }
.btn-primary { background:#534AB7;color:#fff;border:none;border-radius:var(--border-radius-md);padding:8px 16px;font-size:13px;font-weight:500;cursor:pointer;text-decoration:none }
.btn-secondary { background:var(--color-background-secondary);color:var(--color-text-primary);border:0.5px solid var(--color-border-secondary);border-radius:var(--border-radius-md);padding:8px 16px;font-size:13px;font-weight:500;cursor:pointer;text-decoration:none }
</style>
@endpush
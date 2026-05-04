@extends('layouts.app')

@section('breadcrumb', 'Paiements')
@section('page-title', 'Détail paiement #' . $payment->id)

@section('content')

@php
    $rp = request()->routeIs('manager.*') ? 'manager.' : (request()->routeIs('staff.*') ? 'staff.' : '');
@endphp

<div style="max-width:640px">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
        <span class="badge badge-{{ $payment->status_color }}" style="font-size:14px;padding:6px 14px">
            {{ $payment->status_label }}
        </span>
        <span style="color:var(--color-text-secondary);font-size:13px">
            Enregistré le {{ $payment->created_at->format('d/m/Y à H:i') }}
        </span>
    </div>

    <div class="detail-card">

        <div class="detail-row">
            <div class="detail-label">Réservation</div>
            <div class="detail-value">
                #{{ $payment->reservation_id }}
                @if($payment->reservation) — {{ $payment->reservation->guest_name }} @endif
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Paiement pour</div>
            <div class="detail-value">
                @if($payment->reservationPerson)
                    {{ $payment->reservationPerson->first_name }} {{ $payment->reservationPerson->last_name }}
                @else
                    <em style="color:var(--color-text-secondary)">Global (toute la réservation)</em>
                @endif
            </div>
        </div>

        <hr style="border:none;border-top:0.5px solid var(--color-border-tertiary);margin:12px 0">

        <div class="detail-row">
            <div class="detail-label">Montant saisi</div>
            <div class="detail-value" style="font-weight:700;font-size:18px">
                {{ number_format($payment->amount_input, 3) }} {{ $payment->currency }}
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Taux de change</div>
            <div class="detail-value">1 {{ $payment->currency }} = {{ $payment->exchange_rate }} TND</div>
        </div>

        <div class="detail-row" style="background:var(--color-background-info);border-radius:8px;padding:10px 14px">
            <div class="detail-label">≈ Équivalent TND</div>
            <div class="detail-value" style="font-weight:700;font-size:20px;color:#534AB7">
                {{ number_format($payment->amount_tnd, 3) }} TND
            </div>
        </div>

        <hr style="border:none;border-top:0.5px solid var(--color-border-tertiary);margin:12px 0">

        <div class="detail-row">
            <div class="detail-label">Mode de paiement</div>
            <div class="detail-value">{{ $payment->method_label }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Reçu par</div>
            <div class="detail-value">{{ $payment->received_by ?? '—' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Encaissé par (système)</div>
            <div class="detail-value">{{ $payment->user->name ?? '—' }}</div>
        </div>

        @if($payment->note)
        <div class="detail-row">
            <div class="detail-label">Note</div>
            <div class="detail-value" style="font-style:italic">{{ $payment->note }}</div>
        </div>
        @endif

    </div>

    <div style="display:flex;gap:12px;margin-top:16px">
        <a href="{{ route($rp . 'payments.index') }}" class="btn-secondary">← Retour</a>
        @if($rp !== 'staff.')
            <a href="{{ route($rp . 'payments.edit', $payment) }}" class="btn-primary">✏️ Modifier</a>
            <form method="POST" action="{{ route($rp . 'payments.destroy', $payment) }}"
                  onsubmit="return confirm('Supprimer ce paiement ?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">🗑 Supprimer</button>
            </form>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<style>
.detail-card  { background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);padding:20px }
.detail-row   { display:flex;justify-content:space-between;align-items:center;padding:8px 0 }
.detail-label { font-size:12px;color:var(--color-text-secondary);font-weight:500;min-width:160px }
.detail-value { font-size:13px;font-weight:500;text-align:right }
.badge { display:inline-block;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:500 }
.badge-success { background:#dcfce7;color:#16a34a }
.badge-warning { background:#fef9c3;color:#ca8a04 }
.badge-danger  { background:#fee2e2;color:#dc2626 }
.btn-primary  { background:#534AB7;color:#fff;border:none;border-radius:var(--border-radius-md);padding:8px 16px;font-size:13px;font-weight:500;cursor:pointer;text-decoration:none }
.btn-secondary { background:var(--color-background-secondary);color:var(--color-text-primary);border:0.5px solid var(--color-border-secondary);border-radius:var(--border-radius-md);padding:8px 16px;font-size:13px;font-weight:500;cursor:pointer;text-decoration:none }
.btn-danger   { background:#fee2e2;color:#dc2626;border:0.5px solid #fca5a5;border-radius:var(--border-radius-md);padding:8px 16px;font-size:13px;font-weight:500;cursor:pointer }
</style>
@endpush
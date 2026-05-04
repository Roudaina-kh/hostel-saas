@extends('layouts.app')

@section('breadcrumb', 'Paiements')
@section('page-title', 'Gestion des paiements')

@section('content')

@php
    // Détecte le préfixe selon le rôle connecté
    $rp = request()->routeIs('manager.*') ? 'manager.' : (request()->routeIs('staff.*') ? 'staff.' : '');
@endphp

{{-- ── Stat rapide ─────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px">
    <div class="stat-card" style="border-left:4px solid #22c55e">
        <div class="stat-label">Total encaissé (TND)</div>
        <div class="stat-value" style="color:#22c55e">{{ number_format($totalTnd, 3) }}</div>
    </div>
    <div class="stat-card" style="border-left:4px solid #534AB7">
        <div class="stat-label">Paiements total</div>
        <div class="stat-value">{{ $payments->total() }}</div>
    </div>
</div>

{{-- ── Actions ─────────────────────────────────────────────── --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
    <h2 style="font-size:18px;font-weight:600;margin:0">Liste des paiements</h2>
    <a href="{{ route($rp . 'payments.create') }}" class="btn-primary">
        + Nouveau paiement
    </a>
</div>

{{-- ── Tableau ──────────────────────────────────────────────── --}}
<div class="table-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Réservation</th>
                <th>Personne</th>
                <th>Montant</th>
                <th>Devise</th>
                <th>≈ TND</th>
                <th>Mode</th>
                <th>Statut</th>
                <th>Reçu par</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>
                        <a href="{{ route($rp . 'payments.show', $payment) }}" style="font-weight:500">
                            #{{ $payment->reservation_id }}
                        </a>
                        <div style="font-size:11px;color:var(--color-text-secondary)">
                            {{ $payment->reservation->guest_name ?? '—' }}
                        </div>
                    </td>
                    <td>
                        @if($payment->reservationPerson)
                            {{ $payment->reservationPerson->first_name }}
                            {{ $payment->reservationPerson->last_name }}
                        @else
                            <span style="color:var(--color-text-secondary);font-style:italic">Global</span>
                        @endif
                    </td>
                    <td style="font-weight:600">{{ number_format($payment->amount_input, 3) }}</td>
                    <td>{{ $payment->currency }}</td>
                    <td style="font-weight:600;color:#534AB7">{{ number_format($payment->amount_tnd, 3) }} TND</td>
                    <td>
                        @php $methodIcons = ['cash'=>'💵','card'=>'💳','transfer'=>'🏦','other'=>'📝']; @endphp
                        {{ $methodIcons[$payment->payment_method] ?? '' }} {{ $payment->method_label }}
                    </td>
                    <td>
                        <span class="badge badge-{{ $payment->status_color }}">
                            {{ $payment->status_label }}
                        </span>
                    </td>
                    <td>{{ $payment->received_by ?? '—' }}</td>
                    <td style="font-size:12px">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route($rp . 'payments.show', $payment) }}" class="btn-icon" title="Voir">👁</a>
                            {{-- Edit et Delete : pas disponibles pour le staff --}}
                            @if($rp !== 'staff.')
                                <a href="{{ route($rp . 'payments.edit', $payment) }}" class="btn-icon" title="Modifier">✏️</a>
                                <form method="POST" action="{{ route($rp . 'payments.destroy', $payment) }}"
                                      onsubmit="return confirm('Supprimer ce paiement ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon danger" title="Supprimer">🗑</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align:center;padding:32px;color:var(--color-text-secondary)">
                        Aucun paiement enregistré.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px">{{ $payments->links() }}</div>

@endsection

@push('scripts')
<style>
.stat-card   { background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);padding:16px }
.stat-label  { font-size:11px;color:var(--color-text-secondary);font-weight:500;margin-bottom:6px }
.stat-value  { font-size:22px;font-weight:700 }
.badge { display:inline-block;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:500 }
.badge-success { background:#dcfce7;color:#16a34a }
.badge-warning { background:#fef9c3;color:#ca8a04 }
.badge-danger  { background:#fee2e2;color:#dc2626 }
.btn-primary  { background:#534AB7;color:#fff;border:none;border-radius:var(--border-radius-md);padding:8px 16px;font-size:13px;font-weight:500;cursor:pointer;text-decoration:none }
.btn-icon { padding:4px 8px;border-radius:6px;border:0.5px solid var(--color-border-tertiary);background:var(--color-background-secondary);cursor:pointer;font-size:14px }
.btn-icon.danger { border-color:#fca5a5 }
.table-card  { background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);overflow:hidden }
.data-table  { width:100%;border-collapse:collapse;font-size:13px }
.data-table th { padding:10px 12px;text-align:left;font-size:11px;font-weight:600;color:var(--color-text-secondary);border-bottom:0.5px solid var(--color-border-tertiary);background:var(--color-background-secondary) }
.data-table td { padding:10px 12px;border-bottom:0.5px solid var(--color-border-tertiary) }
.data-table tr:last-child td { border-bottom:none }
</style>
@endpush
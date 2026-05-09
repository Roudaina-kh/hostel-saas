@extends('layouts.app')
@section('title', 'Demandes de réservation')

@section('content')
<style>
    .cr-page { max-width:1200px; margin:0 auto; padding:1.5rem; }

    .cr-header {
        display:flex; justify-content:space-between; align-items:flex-end;
        margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;
    }
    .cr-title { font-size:1.7rem; font-weight:700; color:#1A2B3C; margin-bottom:4px; }
    .cr-subtitle { font-size:0.88rem; color:#6B7280; }

    .cr-stats { display:grid; grid-template-columns:repeat(4, 1fr); gap:1rem; margin-bottom:1.5rem; }
    @media (max-width:768px) { .cr-stats { grid-template-columns:repeat(2, 1fr); } }
    .cr-stat {
        background:#fff; border-radius:14px; padding:18px;
        border:1px solid #E5E7EB; display:flex; flex-direction:column; gap:4px;
    }
    .cr-stat-value { font-size:1.7rem; font-weight:700; color:#1A2B3C; }
    .cr-stat-label { font-size:0.78rem; font-weight:600; color:#6B7280; text-transform:uppercase; letter-spacing:0.05em; }
    .cr-stat.new { background:linear-gradient(135deg, #FEF3E2 0%, #FFE4C4 100%); border-color:#F5C896; }
    .cr-stat.new .cr-stat-value { color:#C8602A; }
    .cr-stat.confirmed { background:linear-gradient(135deg, #ECFDF3 0%, #D1FAE5 100%); border-color:#86EFAC; }
    .cr-stat.confirmed .cr-stat-value { color:#16A34A; }
    .cr-stat.cancelled { background:linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%); border-color:#FCA5A5; }
    .cr-stat.cancelled .cr-stat-value { color:#DC2626; }

    .cr-table-wrap {
        background:#fff; border-radius:16px; overflow:hidden;
        border:1px solid #E5E7EB; box-shadow:0 1px 3px rgba(0,0,0,0.04);
    }
    .cr-table { width:100%; border-collapse:collapse; }
    .cr-table th {
        background:#F8F9FB; padding:14px 16px; text-align:left;
        font-size:0.72rem; font-weight:700; color:#6B7280;
        text-transform:uppercase; letter-spacing:0.06em; border-bottom:1px solid #E5E7EB;
    }
    .cr-table td {
        padding:16px; font-size:0.88rem; color:#1A2B3C;
        border-bottom:1px solid #F3F4F6; vertical-align:middle;
    }
    .cr-table tr:last-child td { border-bottom:none; }
    .cr-table tr:hover { background:#FAFBFC; }

    .cr-name { font-weight:600; }
    .cr-contact { color:#6B7280; font-size:0.82rem; line-height:1.4; }

    .cr-badge {
        display:inline-flex; align-items:center; gap:4px;
        padding:4px 12px; border-radius:14px; font-size:0.72rem; font-weight:700;
        text-transform:uppercase; letter-spacing:0.04em;
    }
    .cr-badge-new       { background:#FEF3E2; color:#C8602A; }
    .cr-badge-read      { background:#E8F4F0; color:#1B6B6B; }
    .cr-badge-replied   { background:#E8F0F4; color:#2C6E8A; }
    .cr-badge-confirmed { background:#D1FAE5; color:#16A34A; }
    .cr-badge-cancelled { background:#FEE2E2; color:#DC2626; }

    .cr-actions { display:flex; gap:6px; flex-wrap:wrap; justify-content:flex-end; }
    .cr-btn {
        font-size:0.75rem; font-weight:600; padding:7px 12px;
        border-radius:8px; border:1px solid #E5E7EB; background:#fff;
        color:#1A2B3C; cursor:pointer; transition:all 0.15s;
        text-decoration:none; display:inline-flex; align-items:center; gap:4px;
        white-space:nowrap;
    }
    .cr-btn:hover { border-color:#9CA3AF; }
    .cr-btn-confirm { color:#16A34A; border-color:#86EFAC; background:#F0FDF4; }
    .cr-btn-confirm:hover { background:#16A34A; color:#fff; border-color:#16A34A; }
    .cr-btn-cancel { color:#DC2626; border-color:#FCA5A5; background:#FEF2F2; }
    .cr-btn-cancel:hover { background:#DC2626; color:#fff; border-color:#DC2626; }
    .cr-btn-delete { color:#6B7280; border-color:#E5E7EB; }
    .cr-btn-delete:hover { background:#F3F4F6; color:#374151; }

    .cr-empty { padding:80px 20px; text-align:center; color:#9CA3AF; }
    .cr-empty-icon { font-size:3.5rem; margin-bottom:1rem; opacity:0.4; }
    .cr-empty-title { font-size:1.2rem; color:#1A2B3C; font-weight:600; margin-bottom:0.5rem; }

    .cr-message {
        max-width:240px; color:#6B7280; font-size:0.82rem; line-height:1.5;
        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }
    .cr-dates { font-size:0.82rem; color:#1A2B3C; }
    .cr-dates strong { color:#C8602A; }

    .cr-flash {
        background:#F0FDF4; color:#1B7A4D; border:1px solid #BBF7D0;
        border-radius:12px; padding:12px 18px; margin-bottom:1rem; font-size:0.88rem;
    }

    /* Lignes désactivées (annulé / confirmé) */
    .cr-row-cancelled { opacity:0.55; }
    .cr-row-cancelled td { background:#FAFAFA; }
</style>

@php
    // Détecte le préfixe pour les routes
    $isManager = request()->routeIs('manager.*');
    $routeConfirm = $isManager ? 'manager.contact-requests.confirm' : 'contact-requests.confirm';
    $routeCancel  = $isManager ? 'manager.contact-requests.cancel'  : 'contact-requests.cancel';
    $routeDestroy = $isManager ? 'manager.contact-requests.destroy' : 'contact-requests.destroy';

    $statusLabels = [
        'new'       => ['🆕 Nouveau',   'new'],
        'read'      => ['👁 Lu',         'read'],
        'replied'   => ['✉️ Répondu',    'replied'],
        'confirmed' => ['✅ Confirmé',   'confirmed'],
        'cancelled' => ['❌ Annulé',     'cancelled'],
    ];
@endphp

<div class="cr-page">

    <div class="cr-header">
        <div>
            <h1 class="cr-title">📬 Demandes de réservation</h1>
            <p class="cr-subtitle">Gérez les demandes envoyées par les clients : confirmez, annulez ou supprimez.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="cr-flash">✅ {{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="cr-stats">
        <div class="cr-stat">
            <div class="cr-stat-value">{{ $stats['total'] ?? 0 }}</div>
            <div class="cr-stat-label">Total</div>
        </div>
        <div class="cr-stat new">
            <div class="cr-stat-value">{{ $stats['new'] ?? 0 }}</div>
            <div class="cr-stat-label">🆕 Nouvelles</div>
        </div>
        <div class="cr-stat confirmed">
            <div class="cr-stat-value">{{ $stats['confirmed'] ?? 0 }}</div>
            <div class="cr-stat-label">✅ Confirmées</div>
        </div>
        <div class="cr-stat cancelled">
            <div class="cr-stat-value">{{ $stats['cancelled'] ?? 0 }}</div>
            <div class="cr-stat-label">❌ Annulées</div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="cr-table-wrap">
        @if($requests->isEmpty())
            <div class="cr-empty">
                <div class="cr-empty-icon">📭</div>
                <div class="cr-empty-title">Aucune demande pour le moment</div>
                <p>Les demandes envoyées par les clients depuis la fiche publique apparaîtront ici.</p>
            </div>
        @else
            <table class="cr-table">
                <thead>
                    <tr>
                        <th>Statut</th>
                        <th>Client</th>
                        <th>Destination</th>
                        <th>Dates</th>
                        <th>Voyageurs</th>
                        <th>Message</th>
                        <th>Reçu le</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $r)
                    @php
                        $isCancelled = $r->status === 'cancelled';
                        $isConfirmed = $r->status === 'confirmed';
                        $isPending   = !$isCancelled && !$isConfirmed; // new / read / replied
                        [$label, $badgeClass] = $statusLabels[$r->status] ?? ['?', 'new'];
                    @endphp
                    <tr class="{{ $isCancelled ? 'cr-row-cancelled' : '' }}">
                        <td>
                            <span class="cr-badge cr-badge-{{ $badgeClass }}">{{ $label }}</span>
                        </td>
                        <td>
                            <div class="cr-name">{{ $r->first_name }} {{ $r->last_name }}</div>
                            <div class="cr-contact">
                                ✉️ <a href="mailto:{{ $r->email }}" style="color:inherit">{{ $r->email }}</a>
                                @if($r->phone)<br>📞 {{ $r->phone }}@endif
                            </div>
                        </td>
                        <td>
                            <strong>{{ $r->destination }}</strong>
                            @if($r->room_type)
                                <div class="cr-contact">{{ ucfirst($r->room_type) }}</div>
                            @endif
                        </td>
                        <td class="cr-dates">
                            <strong>{{ \Carbon\Carbon::parse($r->arrival_date)->format('d/m/Y') }}</strong><br>
                            → {{ \Carbon\Carbon::parse($r->departure_date)->format('d/m/Y') }}
                        </td>
                        <td>👥 {{ $r->travelers }}</td>
                        <td>
                            @if($r->message)
                                <div class="cr-message" title="{{ $r->message }}">{{ $r->message }}</div>
                            @else
                                <span style="color:#D1D5DB; font-style:italic;">Aucun</span>
                            @endif
                        </td>
                        <td class="cr-contact">
                            {{ $r->created_at->format('d/m/Y') }}<br>
                            <small>{{ $r->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <div class="cr-actions">
                                {{-- ✅ Confirmer (visible si pas déjà confirmé/annulé) --}}
                                @if($isPending)
                                    <form method="POST" action="{{ route($routeConfirm, $r) }}" style="display:inline"
                                          onsubmit="return confirm('Confirmer cette réservation ? Le client sera notifié.');">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="cr-btn cr-btn-confirm">✅ Confirmer</button>
                                    </form>
                                @endif

                                {{-- ❌ Annuler (visible si pas déjà annulé) --}}
                                @if(!$isCancelled)
                                    <form method="POST" action="{{ route($routeCancel, $r) }}" style="display:inline"
                                          onsubmit="return confirm('Annuler cette demande ?');">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="cr-btn cr-btn-cancel">❌ Annuler</button>
                                    </form>
                                @endif

                                {{-- 🗑 Supprimer (toujours visible) --}}
                                <form method="POST" action="{{ route($routeDestroy, $r) }}" style="display:inline"
                                      onsubmit="return confirm('Supprimer cette demande définitivement ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="cr-btn cr-btn-delete">🗑 Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @if($requests->hasPages())
        <div style="margin-top:1.5rem; display:flex; justify-content:center;">
            {{ $requests->links() }}
        </div>
    @endif
</div>
@endsection
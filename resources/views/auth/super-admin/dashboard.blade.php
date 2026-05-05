@extends('super-admin.layout')

@section('breadcrumb', 'Dashboard')
@section('page-title', 'Vue d\'ensemble de la plateforme')

@section('content')

{{-- Stats ──────────────────────────────────────────────────── --}}
<div class="stats-grid">
    <div class="stat-card purple">
        <div class="stat-label">Propriétaires total</div>
        <div class="stat-value">{{ $stats['total_owners'] }}</div>
        <div class="stat-sub">{{ $stats['active_owners'] }} actifs</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">Hostels plateforme</div>
        <div class="stat-value">{{ $stats['total_hostels'] }}</div>
        <div class="stat-sub">{{ $stats['active_hostels'] }} actifs</div>
    </div>
    <div class="stat-card green">
        <div class="stat-label">Réservations totales</div>
        <div class="stat-value">{{ $stats['total_reservations'] }}</div>
        <div class="stat-sub">{{ $stats['active_reservations'] }} en cours</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-label">Équipes (managers/staff)</div>
        <div class="stat-value">{{ $stats['total_users'] }}</div>
        <div class="stat-sub">Sur toute la plateforme</div>
    </div>
</div>

{{-- Hostels actifs + leurs owners ──────────────────────────── --}}
<div class="sa-card">
    <div class="sa-card-title">
        🏨 Hostels actifs et leurs propriétaires
        <a href="{{ route('super-admin.hostels.index') }}"
           style="margin-left:auto;font-size:12px;color:#7C3AED;text-decoration:none;font-weight:600">
            Voir tous →
        </a>
    </div>
    <div class="sa-table-wrap">
        <table class="sa-table">
            <thead>
                <tr>
                    <th>Hostel</th>
                    <th>Propriétaire</th>
                    <th>Email propriétaire</th>
                    <th>Statut</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats['recent_hostels'] as $hostel)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#1E293B">{{ $hostel->name }}</div>
                        @if($hostel->city)
                            <div style="font-size:11px;color:#94A3B8">📍 {{ $hostel->city }}</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:500">{{ $hostel->owner?->name ?? '—' }}</div>
                    </td>
                    <td style="color:#64748B;font-size:12px">{{ $hostel->owner?->email ?? '—' }}</td>
                    <td>
                        @if($hostel->is_active ?? true)
                            <span class="badge badge-active">✅ Actif</span>
                        @else
                            <span class="badge badge-inactive">🚫 Désactivé</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:#94A3B8">{{ $hostel->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('super-admin.hostels.show', $hostel) }}"
                           class="btn btn-secondary btn-sm">Voir</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:32px;color:#94A3B8">
                        Aucun hostel enregistré.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Derniers propriétaires ─────────────────────────────────── --}}
<div class="sa-card">
    <div class="sa-card-title">
        👤 Derniers propriétaires créés
        <a href="{{ route('super-admin.owners.index') }}"
           style="margin-left:auto;font-size:12px;color:#7C3AED;text-decoration:none;font-weight:600">
            Voir tous →
        </a>
    </div>
    <div class="sa-table-wrap">
        <table class="sa-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Hostels</th>
                    <th>Statut</th>
                    <th>Créé le</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats['recent_owners'] as $owner)
                <tr>
                    <td style="font-weight:600">{{ $owner->name }}</td>
                    <td style="color:#64748B;font-size:12px">{{ $owner->email }}</td>
                    <td>
                        <span class="badge badge-purple">{{ $owner->hostels_count ?? 0 }} hostel(s)</span>
                    </td>
                    <td>
                        @if($owner->is_active ?? true)
                            <span class="badge badge-active">Actif</span>
                        @else
                            <span class="badge badge-inactive">Désactivé</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:#94A3B8">{{ $owner->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:32px;color:#94A3B8">
                        Aucun propriétaire enregistré.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
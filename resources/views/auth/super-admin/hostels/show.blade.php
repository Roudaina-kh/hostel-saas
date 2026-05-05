{{-- resources/views/super-admin/hostels/show.blade.php --}}
@extends('super-admin.layout')
@section('breadcrumb', 'Hostels › Détail')
@section('page-title', $hostel->name)

@section('content')

{{-- Infos hostel ──────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
    <div class="sa-card">
        <div class="sa-card-title">🏨 Informations hostel</div>
        <div style="display:flex;flex-direction:column;gap:10px;font-size:13px">
            <div style="display:flex;justify-content:space-between">
                <span style="color:#64748B">Nom</span>
                <span style="font-weight:600">{{ $hostel->name }}</span>
            </div>
            <div style="display:flex;justify-content:space-between">
                <span style="color:#64748B">Propriétaire</span>
                <span style="font-weight:600">{{ $hostel->owner?->name ?? '—' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between">
                <span style="color:#64748B">Email owner</span>
                <span style="color:#7C3AED">{{ $hostel->owner?->email ?? '—' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between">
                <span style="color:#64748B">Ville</span>
                <span>{{ $hostel->city ?? '—' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between">
                <span style="color:#64748B">Pays</span>
                <span>{{ $hostel->country ?? '—' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between">
                <span style="color:#64748B">Statut</span>
                @if($hostel->is_active ?? true)
                    <span class="badge badge-active">✅ Actif</span>
                @else
                    <span class="badge badge-inactive">🚫 Désactivé</span>
                @endif
            </div>
        </div>
    </div>

    <div class="sa-card">
        <div class="sa-card-title">📊 Activité</div>
        <div class="stats-grid" style="grid-template-columns:1fr 1fr;gap:12px;margin-bottom:0">
            <div class="stat-card blue" style="padding:14px">
                <div class="stat-label">Chambres</div>
                <div class="stat-value" style="font-size:22px">{{ $stats['total_rooms'] }}</div>
            </div>
            <div class="stat-card green" style="padding:14px">
                <div class="stat-label">Lits total</div>
                <div class="stat-value" style="font-size:22px">{{ $stats['total_beds'] }}</div>
            </div>
            <div class="stat-card purple" style="padding:14px">
                <div class="stat-label">Réservations</div>
                <div class="stat-value" style="font-size:22px">{{ $stats['total_reservations'] }}</div>
                <div class="stat-sub">{{ $stats['active_reservations'] }} actives</div>
            </div>
            <div class="stat-card orange" style="padding:14px">
                <div class="stat-label">Équipe</div>
                <div class="stat-value" style="font-size:22px">{{ $stats['team_count'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Actions ─────────────────────────────────────────────── --}}
<div class="sa-card">
    <div class="sa-card-title">⚙️ Actions plateforme</div>
    <div style="display:flex;gap:12px;flex-wrap:wrap">
        <form method="POST" action="{{ route('super-admin.hostels.toggle', $hostel) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="btn {{ ($hostel->is_active ?? true) ? 'btn-warning' : 'btn-primary' }}"
                    onclick="return confirm('Confirmer ?')">
                {{ ($hostel->is_active ?? true) ? '🚫 Désactiver ce hostel' : '✅ Réactiver ce hostel' }}
            </button>
        </form>

        <form method="POST" action="{{ route('super-admin.hostels.destroy', $hostel) }}"
              onsubmit="return confirm('Supprimer définitivement ce hostel ?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">🗑 Supprimer de la plateforme</button>
        </form>

        <a href="{{ route('super-admin.hostels.index') }}" class="btn btn-secondary">← Retour</a>
    </div>
</div>

@endsection
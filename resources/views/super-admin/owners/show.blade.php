@extends('super-admin.layouts.app')
@section('title', $owner->name . ' — Propriétaire')
@section('breadcrumb', $owner->name)
@section('page-title', 'Détail Propriétaire')

@section('content')

{{-- ══ PAGE HEADER ══ --}}
<div class="page-header">
  <div class="page-header-text">
    <div class="section-tag">Propriétaires</div>
    <h1>Fiche <em>propriétaire</em></h1>
    <p>Informations détaillées et gestion du compte</p>
  </div>
  <div class="page-header-actions">
    <a href="{{ route('super-admin.owners.index') }}" class="btn btn-outline">
      ← Retour à la liste
    </a>
    <form method="POST" action="{{ route('super-admin.owners.toggle', $owner) }}" style="display:inline">
      @csrf @method('PATCH')
      <button type="submit" class="btn {{ ($owner->is_active ?? true) ? 'btn-danger' : 'btn-teal' }}">
        {{ ($owner->is_active ?? true) ? '⏸ Suspendre le compte' : '▶ Réactiver le compte' }}
      </button>
    </form>
    <button type="button"
            class="btn btn-danger"
            onclick="openConfirm(
              '{{ route('super-admin.owners.destroy', $owner) }}',
              'Supprimer ce propriétaire',
              'Cette action est irréversible. Toutes les données de {{ addslashes($owner->name) }} seront supprimées définitivement.'
            )">
      🗑 Supprimer
    </button>
  </div>
</div>

{{-- ══ GRID LAYOUT ══ --}}
<div style="display:grid;grid-template-columns:1fr 1.4fr;gap:1.5rem;align-items:start">

  {{-- ── Colonne gauche : Profil ── --}}
  <div style="display:flex;flex-direction:column;gap:1.2rem">

    {{-- Carte profil --}}
    <div class="owner-profile-card">
      <div class="owner-profile-header">
        <div class="owner-avatar-large">
          {{ strtoupper(substr($owner->name, 0, 1)) }}
        </div>
        <div>
          <div class="owner-profile-name">{{ $owner->name }}</div>
          <div class="owner-profile-email">{{ $owner->email }}</div>
          <div style="margin-top:8px">
            @if($owner->is_active ?? true)
              <span class="badge badge-green">● Compte actif</span>
            @else
              <span class="badge badge-red">● Compte suspendu</span>
            @endif
          </div>
        </div>
      </div>
      <div class="owner-profile-body">
        <div class="section-tag" style="margin-bottom:14px">Informations</div>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Téléphone</div>
            <div class="info-value">{{ $owner->phone ?? '—' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Inscription</div>
            <div class="info-value">{{ $owner->created_at->format('d/m/Y') }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Dernière connexion</div>
            <div class="info-value">
              {{ $owner->last_login_at ? $owner->last_login_at->diffForHumans() : '—' }}
            </div>
          </div>
          <div class="info-item">
            <div class="info-label">Auberges</div>
            <div class="info-value">
              <span class="badge badge-teal">🏨 {{ $owner->hostels->count() }}</span>
            </div>
          </div>
          @if($owner->country ?? false)
          <div class="info-item full">
            <div class="info-label">Pays</div>
            <div class="info-value">{{ $owner->country }}</div>
          </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Statistiques rapides --}}
    <div class="card">
      <div class="card-header">
        <div class="card-title">📊 Statistiques</div>
      </div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;background:var(--cream);border-radius:10px;border:1px solid var(--border)">
          <div style="font-size:0.85rem;color:var(--gray)">Total auberges</div>
          <div style="font-family:'Fraunces',serif;font-size:1.2rem;font-weight:700;color:var(--charcoal)">
            {{ $owner->hostels->count() }}
          </div>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;background:var(--cream);border-radius:10px;border:1px solid var(--border)">
          <div style="font-size:0.85rem;color:var(--gray)">Auberges actives</div>
          <div style="font-family:'Fraunces',serif;font-size:1.2rem;font-weight:700;color:#16A34A">
            {{ $owner->hostels->where('is_active', true)->count() }}
          </div>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;background:var(--cream);border-radius:10px;border:1px solid var(--border)">
          <div style="font-size:0.85rem;color:var(--gray)">Ancienneté</div>
          <div style="font-size:0.88rem;font-weight:600;color:var(--teal-dark)">
            {{ $owner->created_at->diffForHumans() }}
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- ── Colonne droite : Auberges ── --}}
  <div>
    <div class="card">
      <div class="card-header">
        <div class="card-title">🏨 Auberges de {{ $owner->name }}</div>
        <span class="badge badge-admin">{{ $owner->hostels->count() }} au total</span>
      </div>
      <div class="card-body" style="padding:16px">

        @if($owner->hostels->isEmpty())
        <div class="empty-state" style="padding:40px 20px">
          <div class="empty-state-icon">🏗</div>
          <h3>Aucune auberge</h3>
          <p>Ce propriétaire n'a pas encore créé d'établissement.</p>
        </div>
        @else
        <div class="hostel-list">
          @foreach($owner->hostels as $hostel)
          <div class="hostel-item">
            <div class="hostel-item-left">
              <div class="hostel-icon">🏨</div>
              <div>
                <div class="hostel-name">{{ $hostel->name }}</div>
                <div class="hostel-city">
                  {{ $hostel->city ?? '' }}{{ ($hostel->city && $hostel->country) ? ', ' : '' }}{{ $hostel->country ?? '' }}
                  @if(!$hostel->city && !$hostel->country)
                    <span style="color:var(--gray-light)">Adresse non renseignée</span>
                  @endif
                </div>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
              @if($hostel->is_active ?? true)
                <span class="badge badge-green">Actif</span>
              @else
                <span class="badge badge-red">Suspendu</span>
              @endif
              <a href="{{ route('super-admin.hostels.show', $hostel) }}"
                 class="btn btn-outline btn-sm">Voir →</a>
              <form method="POST" action="{{ route('super-admin.hostels.toggle', $hostel) }}">
                @csrf @method('PATCH')
                <button type="submit"
                        class="btn btn-sm {{ ($hostel->is_active ?? true) ? 'btn-danger' : 'btn-teal' }}">
                  {{ ($hostel->is_active ?? true) ? '⏸' : '▶' }}
                </button>
              </form>
            </div>
          </div>
          @endforeach
        </div>
        @endif

      </div>

      {{-- Footer avec action rapide --}}
      @if($owner->hostels->isNotEmpty())
      <div class="card-footer-bar" style="padding:14px 16px;border-top:1px solid var(--border);background:var(--cream)">
        <div style="display:flex;align-items:center;justify-content:space-between">
          <span style="font-size:0.78rem;color:var(--gray-light)">
            {{ $owner->hostels->where('is_active', true)->count() }} actives sur {{ $owner->hostels->count() }}
          </span>
          <a href="{{ route('super-admin.hostels.index') }}" class="btn btn-outline btn-sm">
            Voir toutes les auberges →
          </a>
        </div>
      </div>
      @endif

    </div>

    {{-- Zone danger ══ --}}
    <div class="card" style="margin-top:1.2rem;border-color:rgba(239,68,68,0.2);background:rgba(255,255,255,0.8)">
      <div class="card-header" style="border-color:rgba(239,68,68,0.15)">
        <div class="card-title" style="color:#DC2626">⚠️ Zone dangereuse</div>
      </div>
      <div class="card-body">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
          <div>
            <div style="font-size:0.88rem;font-weight:600;color:var(--charcoal);margin-bottom:3px">
              Supprimer ce propriétaire
            </div>
            <div style="font-size:0.78rem;color:var(--gray-light)">
              Action irréversible — toutes les données associées seront perdues
            </div>
          </div>
          <button type="button"
                  class="btn btn-danger"
                  onclick="openConfirm(
                    '{{ route('super-admin.owners.destroy', $owner) }}',
                    'Supprimer {{ addslashes($owner->name) }}',
                    'Cette suppression est définitive et inclut toutes les auberges et données associées. Confirmez ?'
                  )">
            🗑 Supprimer définitivement
          </button>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection

@push('styles')
<style>
@media(max-width:900px){
  div[style*="grid-template-columns:1fr 1.4fr"] {
    grid-template-columns:1fr !important;
  }
}
</style>
@endpush
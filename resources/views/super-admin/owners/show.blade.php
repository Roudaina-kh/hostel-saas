@extends('super-admin.layout')
@section('breadcrumb', 'Propriétaires › Détail')
@section('page-title', $owner->name)

@section('content')

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">

  {{-- Infos propriétaire --}}
  <div class="sa-card">
    <div class="sa-card-title">👤 Informations du propriétaire</div>
    <div style="display:flex;flex-direction:column;gap:12px;font-size:13px">
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F1F5F9">
        <span style="color:#64748B">Nom</span>
        <span style="font-weight:600">{{ $owner->name }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F1F5F9">
        <span style="color:#64748B">Email</span>
        <span style="color:#7C3AED">{{ $owner->email }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F1F5F9">
        <span style="color:#64748B">Téléphone</span>
        <span>{{ $owner->phone ?? '—' }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F1F5F9">
        <span style="color:#64748B">Statut</span>
        @if($owner->is_active ?? true)
          <span class="badge badge-active">✅ Actif</span>
        @else
          <span class="badge badge-inactive">🚫 Désactivé</span>
        @endif
      </div>
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F1F5F9">
        <span style="color:#64748B">Dernière connexion</span>
        <span style="font-weight:500">
          {{ $owner->last_login_at ? $owner->last_login_at->format('d/m/Y à H:i') : 'Jamais connecté' }}
        </span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F1F5F9">
        <span style="color:#64748B">IP dernière connexion</span>
        <span style="font-family:monospace;font-size:12px">{{ $owner->last_login_ip ?? '—' }}</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:8px 0">
        <span style="color:#64748B">Compte créé le</span>
        <span>{{ $owner->created_at->format('d/m/Y') }}</span>
      </div>
    </div>
  </div>

  {{-- Hostels du propriétaire --}}
  <div class="sa-card">
    <div class="sa-card-title">🏨 Hostels ({{ $owner->hostels->count() }})</div>
    @forelse($owner->hostels as $hostel)
      <div style="display:flex;justify-content:space-between;align-items:center;
                  padding:10px 0;border-bottom:1px solid #F1F5F9;font-size:13px">
        <div>
          <div style="font-weight:600">{{ $hostel->name }}</div>
          <div style="font-size:11px;color:#94A3B8">
            {{ $hostel->city ?? '' }}{{ $hostel->city && $hostel->country ? ', ' : '' }}{{ $hostel->country ?? '' }}
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
          @if($hostel->is_active ?? true)
            <span class="badge badge-active">Actif</span>
          @else
            <span class="badge badge-inactive">Désactivé</span>
          @endif
          <a href="{{ route('super-admin.hostels.show', $hostel) }}"
             class="btn btn-secondary btn-sm">Voir</a>
        </div>
      </div>
    @empty
      <p style="color:#94A3B8;font-size:13px;padding:16px 0">Aucun hostel enregistré.</p>
    @endforelse
  </div>

</div>

{{-- Actions --}}
<div class="sa-card">
  <div class="sa-card-title">⚙️ Actions</div>
  <div style="display:flex;gap:12px;flex-wrap:wrap">

    <form method="POST" action="{{ route('super-admin.owners.toggle', $owner) }}">
      @csrf @method('PATCH')
      <button type="submit"
              class="btn {{ ($owner->is_active ?? true) ? 'btn-warning' : 'btn-primary' }}"
              onclick="return confirm('Confirmer ?')">
        {{ ($owner->is_active ?? true) ? '🚫 Désactiver ce compte' : '✅ Réactiver ce compte' }}
      </button>
    </form>

    <form method="POST" action="{{ route('super-admin.owners.destroy', $owner) }}"
          onsubmit="return confirm('Supprimer définitivement ce propriétaire et tous ses hostels ?')">
      @csrf @method('DELETE')
      <button type="submit" class="btn btn-danger">🗑 Supprimer de la plateforme</button>
    </form>

    <a href="{{ route('super-admin.owners.index') }}" class="btn btn-secondary">← Retour</a>
  </div>
</div>

@endsection
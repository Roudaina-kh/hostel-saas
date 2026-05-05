{{-- resources/views/super-admin/owners/index.blade.php --}}
@extends('super-admin.layout')
@section('breadcrumb', 'Propriétaires')
@section('page-title', 'Gestion des propriétaires')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
    <div>
        <p style="color:#64748B;font-size:13px">{{ $owners->total() }} propriétaire(s) sur la plateforme</p>
    </div>
    <a href="{{ route('super-admin.owners.create') }}" class="btn btn-primary">
        ➕ Créer un propriétaire
    </a>
</div>

<div class="sa-table-wrap">
    <table class="sa-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Hostels</th>
                <th>Statut</th>
                <th>Dernière connexion</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($owners as $owner)
            <tr>
                <td style="color:#94A3B8;font-size:12px">{{ $owner->id }}</td>
                <td>
                    <div style="font-weight:600;color:#1E293B">{{ $owner->name }}</div>
                </td>
                <td style="color:#64748B;font-size:12px">{{ $owner->email }}</td>
                <td style="color:#64748B;font-size:12px">{{ $owner->phone ?? '—' }}</td>
                <td>
                    <span class="badge badge-purple">{{ $owner->hostels_count }} hostel(s)</span>
                </td>
                <td>
                    @if($owner->is_active ?? true)
                        <span class="badge badge-active">✅ Actif</span>
                    @else
                        <span class="badge badge-inactive">🚫 Désactivé</span>
                    @endif
                </td>
                <td style="font-size:12px;color:#94A3B8">
                    {{ $owner->last_login_at ? $owner->last_login_at->format('d/m/Y H:i') : 'Jamais' }}
                </td>
                <td>
                    <div style="display:flex;gap:6px;flex-wrap:wrap">
                        <a href="{{ route('super-admin.owners.show', $owner) }}"
                           class="btn btn-secondary btn-sm">👁 Voir</a>

                        {{-- Toggle actif/désactivé --}}
                        <form method="POST" action="{{ route('super-admin.owners.toggle', $owner) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="btn btn-sm {{ ($owner->is_active ?? true) ? 'btn-warning' : 'btn-secondary' }}"
                                    onclick="return confirm('{{ ($owner->is_active ?? true) ? 'Désactiver' : 'Activer' }} ce propriétaire ?')">
                                {{ ($owner->is_active ?? true) ? '🚫 Désactiver' : '✅ Activer' }}
                            </button>
                        </form>

                        {{-- Supprimer --}}
                        <form method="POST" action="{{ route('super-admin.owners.destroy', $owner) }}"
                              onsubmit="return confirm('Supprimer définitivement ce propriétaire ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:40px;color:#94A3B8">
                    Aucun propriétaire enregistré. <a href="{{ route('super-admin.owners.create') }}" style="color:#7C3AED">Créer le premier →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px">{{ $owners->links() }}</div>

@endsection
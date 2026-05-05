@extends('layouts.app')
@section('breadcrumb', 'Hostels')
@section('page-title', 'Gestion des hostels')

@section('content')

<div style="margin-bottom:20px">
    <p style="color:#64748B;font-size:13px">{{ $hostels->count() }} hostel(s)</p>
</div>

<div class="sa-table-wrap">
    <table class="sa-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Hostel</th>
                <th>Propriétaire</th>
                <th>Localisation</th>
                <th>Chambres</th>
                <th>Réservations</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hostels as $hostel)
            <tr>
                <td style="color:#94A3B8;font-size:12px">{{ $hostel->id }}</td>
                <td>
                    <div style="font-weight:600;color:#1E293B">{{ $hostel->name }}</div>
                </td>
                <td>
                    <div style="font-weight:500;font-size:13px">{{ $hostel->owner?->name ?? '—' }}</div>
                    <div style="font-size:11px;color:#94A3B8">{{ $hostel->owner?->email ?? '' }}</div>
                </td>
                <td style="font-size:12px;color:#64748B">
                    {{ $hostel->city ?? '' }}{{ $hostel->city && $hostel->country ? ', ' : '' }}{{ $hostel->country ?? '—' }}
                </td>
                <td style="text-align:center">
                    <span class="badge badge-blue">{{ $hostel->rooms_count ?? 0 }}</span>
                </td>
                <td style="text-align:center;color:#94A3B8;font-size:12px">—</td>
                <td>
                    @if($hostel->is_active ?? true)
                        <span class="badge badge-active">✅ Actif</span>
                    @else
                        <span class="badge badge-inactive">🚫 Désactivé</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:6px;flex-wrap:wrap">
                        <a href="{{ route('super-admin.hostels.show', $hostel) }}"
                           class="btn btn-secondary btn-sm">👁 Voir</a>

                        {{-- Activer / Désactiver --}}
                        <form method="POST" action="{{ route('super-admin.hostels.toggle', $hostel) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="btn btn-sm {{ ($hostel->is_active ?? true) ? 'btn-warning' : 'btn-secondary' }}"
                                    onclick="return confirm('{{ ($hostel->is_active ?? true) ? 'Désactiver' : 'Activer' }} ce hostel ?')">
                                {{ ($hostel->is_active ?? true) ? '🚫 Désactiver' : '✅ Activer' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('super-admin.hostels.destroy', $hostel) }}"
                              onsubmit="return confirm('Supprimer ce hostel définitivement ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:40px;color:#94A3B8">
                    Aucun hostel enregistré sur la plateforme.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>



@endsection
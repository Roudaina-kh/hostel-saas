@extends('super-admin.layout')
@section('breadcrumb', 'Hostels')
@section('page-title', 'Gestion des hostels')

@section('content')

<div style="margin-bottom:20px">
    <p style="color:#64748B;font-size:13px">{{ $hostels->total() }} hostel(s) sur la plateforme</p>
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
                <td style="text-align:center">
                    <span class="badge badge-purple">{{ $hostel->reservations_count ?? 0 }}</span>
                </td>
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

{{-- Pagination custom (cohérente avec le design super-admin) --}}
@if($hostels->hasPages())
<div style="margin-top:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div style="font-size:12px;color:#94A3B8;font-weight:500">
        Affichage <strong style="color:#1E293B">{{ $hostels->firstItem() }}–{{ $hostels->lastItem() }}</strong>
        sur <strong style="color:#1E293B">{{ $hostels->total() }}</strong> résultats
    </div>

    <div style="display:flex;align-items:center;gap:6px">
        {{-- Précédent --}}
        @if($hostels->onFirstPage())
            <span style="display:inline-flex;align-items:center;gap:5px;padding:6px 14px;border-radius:8px;
                         font-size:12px;font-weight:600;background:#F8FAFC;color:#CBD5E1;
                         border:1px solid #E2E8F0;cursor:not-allowed;user-select:none">
                ← Précédent
            </span>
        @else
            <a href="{{ $hostels->previousPageUrl() }}"
               style="display:inline-flex;align-items:center;gap:5px;padding:6px 14px;border-radius:8px;
                      font-size:12px;font-weight:600;background:#fff;color:#475569;
                      border:1px solid #E2E8F0;text-decoration:none;transition:all .15s"
               onmouseover="this.style.background='#F1F5F9';this.style.borderColor='#CBD5E1'"
               onmouseout="this.style.background='#fff';this.style.borderColor='#E2E8F0'">
                ← Précédent
            </a>
        @endif

        {{-- Pages numérotées --}}
        @foreach($hostels->getUrlRange(
            max(1, $hostels->currentPage() - 2),
            min($hostels->lastPage(), $hostels->currentPage() + 2)
        ) as $page => $url)
            @if($page == $hostels->currentPage())
                <span style="display:inline-flex;align-items:center;justify-content:center;
                             width:34px;height:34px;border-radius:8px;font-size:12px;font-weight:700;
                             background:linear-gradient(135deg,#7C3AED,#4F46E5);color:#fff;
                             border:1px solid transparent">{{ $page }}</span>
            @else
                <a href="{{ $url }}"
                   style="display:inline-flex;align-items:center;justify-content:center;
                          width:34px;height:34px;border-radius:8px;font-size:12px;font-weight:600;
                          background:#fff;color:#475569;border:1px solid #E2E8F0;text-decoration:none;transition:all .15s"
                   onmouseover="this.style.background='#F1F5F9'"
                   onmouseout="this.style.background='#fff'">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Suivant --}}
        @if($hostels->hasMorePages())
            <a href="{{ $hostels->nextPageUrl() }}"
               style="display:inline-flex;align-items:center;gap:5px;padding:6px 14px;border-radius:8px;
                      font-size:12px;font-weight:600;background:#fff;color:#475569;
                      border:1px solid #E2E8F0;text-decoration:none;transition:all .15s"
               onmouseover="this.style.background='#F1F5F9';this.style.borderColor='#CBD5E1'"
               onmouseout="this.style.background='#fff';this.style.borderColor='#E2E8F0'">
                Suivant →
            </a>
        @else
            <span style="display:inline-flex;align-items:center;gap:5px;padding:6px 14px;border-radius:8px;
                         font-size:12px;font-weight:600;background:#F8FAFC;color:#CBD5E1;
                         border:1px solid #E2E8F0;cursor:not-allowed;user-select:none">
                Suivant →
            </span>
        @endif
    </div>
</div>
@endif

@endsection
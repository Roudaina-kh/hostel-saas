@extends('layouts.app')
@section('title', 'Tent Spaces')
@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">Tent Spaces</h1>
        <p style="font-size:0.875rem; color:#8A9BB0; margin:0.25rem 0 0;">Gérez vos espaces camping.</p>
    </div>
    <a href="{{ route('tent-spaces.create') }}"
       style="padding:0.625rem 1.25rem; border-radius:0.75rem; font-size:0.875rem; font-weight:700;
              color:white; text-decoration:none;
              background:linear-gradient(135deg,#1A4A6B,#2C6E8A);
              box-shadow:0 4px 15px rgba(44,110,138,0.3);">
        + Ajouter un espace
    </a>
</div>

<div style="background:white; border-radius:1rem; border:1px solid #E8EEF2; overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
        <thead>
            <tr style="background:#F8FBFD;">
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Nom</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Max tentes</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Max personnes</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Statut</th>
                <th style="padding:1rem 1.25rem; text-align:right; font-weight:600; color:#5A6B7A;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tentSpaces as $tent)
            <tr style="border-top:1px solid #F0F4F8;">
                <td style="padding:1rem 1.25rem; font-weight:600; color:#1A2B3C;">{{ $tent->name }}</td>
                <td style="padding:1rem 1.25rem; color:#5A6B7A;">{{ $tent->max_tents ?? '—' }}</td>
                <td style="padding:1rem 1.25rem; color:#5A6B7A;">{{ $tent->max_persons ?? '—' }}</td>
                <td style="padding:1rem 1.25rem;">
                    {{-- is_enabled remplace is_active (Sprint 2) --}}
                    @if($tent->is_enabled)
                        <span style="padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:600; background:#F0FDF4; color:#16A34A;">
                            ✅ Actif
                        </span>
                    @else
                        <span style="padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:600; background:#FEF2F2; color:#DC2626;">
                            ❌ Inactif
                        </span>
                    @endif
                </td>
                <td style="padding:1rem 1.25rem; text-align:right; white-space:nowrap;">
                    <button onclick="toggleTent({{ $tent->id }})"
                            style="font-size:0.75rem; font-weight:600; margin-right:0.75rem; border:none; cursor:pointer; border-radius:0.5rem; padding:0.3rem 0.75rem;
                                   {{ $tent->is_enabled ? 'background:#FEF2F2; color:#DC2626;' : 'background:#F0FDF4; color:#16A34A;' }}">
                        {{-- is_enabled remplace is_active --}}
                        {{ $tent->is_enabled ? 'Désactiver' : 'Activer' }}
                    </button>
                    <a href="{{ route('tent-spaces.edit', $tent) }}"
                       style="font-size:0.75rem; font-weight:500; color:#2C6E8A; text-decoration:none; margin-right:0.75rem;">
                        Modifier
                    </a>
                    <button onclick="deleteTent({{ $tent->id }}, '{{ addslashes($tent->name) }}')"
                            style="font-size:0.75rem; font-weight:500; color:#DC2626; background:none; border:none; cursor:pointer;">
                        Supprimer
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:2.5rem; text-align:center; color:#8A9BB0;">
                    Aucun espace tente.
                    <a href="{{ route('tent-spaces.create') }}" style="color:#2C6E8A; font-weight:500;">Créer le premier</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
function toggleTent(id) {
    fetch('/tent-spaces/' + id + '/toggle', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    }).then(r => r.json()).then(() => window.location.reload());
}

function deleteTent(id, name) {
    Swal.fire({
        title: 'Supprimer "' + name + '" ?',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#DC2626', cancelButtonColor: '#6B7280',
        confirmButtonText: 'Supprimer', cancelButtonText: 'Annuler',
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST'; form.action = '/tent-spaces/' + id;
            var csrf = document.createElement('input');
            csrf.type = 'hidden'; csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').content;
            var method = document.createElement('input');
            method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
            form.appendChild(csrf); form.appendChild(method);
            document.body.appendChild(form); form.submit();
        }
    });
}
</script>
@endpush
@endsection
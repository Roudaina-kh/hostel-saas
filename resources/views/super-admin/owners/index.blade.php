@extends('super-admin.layouts.app')
@section('title', 'Propriétaires')
@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">Propriétaires</h1>
        <p style="font-size:0.875rem; color:#8A9BB0; margin:0.25rem 0 0;">
            {{ $owners->count() }} compte(s) propriétaire sur la plateforme
        </p>
    </div>
</div>

<div style="background:white; border-radius:1rem; border:1px solid #E8EEF2; overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
        <thead>
            <tr style="background:#F8FBFD;">
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Nom</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Email</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Hostels</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Inscrit le</th>
                <th style="padding:1rem 1.25rem; text-align:right; font-weight:600; color:#5A6B7A;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($owners as $owner)
            <tr style="border-top:1px solid #F0F4F8;">
                <td style="padding:1rem 1.25rem; font-weight:600; color:#1A2B3C;">
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <div style="width:36px; height:36px; border-radius:50%; background:#EFF6FF;
                                    display:flex; align-items:center; justify-content:center;
                                    font-weight:700; color:#1A4A6B; font-size:0.875rem;">
                            {{ strtoupper(substr($owner->name, 0, 1)) }}
                        </div>
                        {{ $owner->name }}
                    </div>
                </td>
                <td style="padding:1rem 1.25rem; color:#5A6B7A;">{{ $owner->email }}</td>
                <td style="padding:1rem 1.25rem;">
                    <span style="padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem;
                                 font-weight:500; background:#F0FDF4; color:#2A6B4F;">
                        {{ $owner->hostels_count }} hostel(s)
                    </span>
                </td>
                <td style="padding:1rem 1.25rem; color:#8A9BB0; font-size:0.8rem;">
                    {{ $owner->created_at->format('d/m/Y') }}
                </td>
                <td style="padding:1rem 1.25rem; text-align:right;">
                    <a href="{{ route('super-admin.owners.show', $owner) }}"
                       style="font-size:0.75rem; font-weight:500; color:#2C6E8A;
                              text-decoration:none; margin-right:0.75rem;">
                        Détails
                    </a>
                    <button onclick="deleteOwner({{ $owner->id }}, '{{ addslashes($owner->name) }}')"
                            style="font-size:0.75rem; font-weight:500; color:#DC2626;
                                   background:none; border:none; cursor:pointer;">
                        Supprimer
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:2.5rem; text-align:center; color:#8A9BB0;">
                    Aucun propriétaire sur la plateforme.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
function deleteOwner(id, name) {
    Swal.fire({
        title: 'Supprimer "' + name + '" ?',
        text: 'Tous ses hostels et données seront supprimés.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Supprimer',
        cancelButtonText: 'Annuler',
        background: '#FDFAF5',
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/super-admin/owners/' + id;
            var csrf = document.createElement('input');
            csrf.type = 'hidden'; csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').content;
            var method = document.createElement('input');
            method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
            form.appendChild(csrf); form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush

@endsection
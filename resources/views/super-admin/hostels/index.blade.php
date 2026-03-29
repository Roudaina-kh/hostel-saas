@extends('super-admin.layouts.app')
@section('title', 'Hostels')
@section('content')

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">Hostels</h1>
    <p style="font-size:0.875rem; color:#8A9BB0; margin:0.25rem 0 0;">
        {{ $hostels->count() }} hostel(s) sur la plateforme
    </p>
</div>

<div style="background:white; border-radius:1rem; border:1px solid #E8EEF2; overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
        <thead>
            <tr style="background:#F8FBFD;">
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Hostel</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Ville / Pays</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Propriétaire</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Devise</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Créé le</th>
                <th style="padding:1rem 1.25rem; text-align:right; font-weight:600; color:#5A6B7A;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hostels as $hostel)
            <tr style="border-top:1px solid #F0F4F8;">
                <td style="padding:1rem 1.25rem; font-weight:600; color:#1A2B3C;">
                    🏨 {{ $hostel->name }}
                </td>
                <td style="padding:1rem 1.25rem; color:#5A6B7A;">
                    {{ $hostel->city }}, {{ $hostel->country }}
                </td>
                <td style="padding:1rem 1.25rem; color:#5A6B7A;">
                    {{ $hostel->owner->name }}
                </td>
                <td style="padding:1rem 1.25rem;">
                    <span style="padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem;
                                 font-weight:500; background:#EFF6FF; color:#1A4A6B;">
                        {{ $hostel->default_currency }}
                    </span>
                </td>
                <td style="padding:1rem 1.25rem; color:#8A9BB0; font-size:0.8rem;">
                    {{ $hostel->created_at->format('d/m/Y') }}
                </td>
                <td style="padding:1rem 1.25rem; text-align:right;">
                    <button onclick="deleteHostel({{ $hostel->id }}, '{{ addslashes($hostel->name) }}')"
                            style="font-size:0.75rem; font-weight:500; color:#DC2626;
                                   background:none; border:none; cursor:pointer;">
                        Supprimer
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:2.5rem; text-align:center; color:#8A9BB0;">
                    Aucun hostel sur la plateforme.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
function deleteHostel(id, name) {
    Swal.fire({
        title: 'Supprimer "' + name + '" ?',
        text: 'Cette action est irréversible.',
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
            form.action = '/super-admin/hostels/' + id;
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
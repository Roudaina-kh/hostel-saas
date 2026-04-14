@extends('layouts.app')
@section('title', 'Taxes')
@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">Taxes</h1>
        <p style="font-size:0.875rem; color:#8A9BB0; margin:0.25rem 0 0;">Configurez les taxes de votre hostel.</p>
    </div>
    <a href="{{ route('taxes.create') }}"
       style="padding:0.625rem 1.25rem; border-radius:0.75rem; font-size:0.875rem; font-weight:700;
              color:white; text-decoration:none;
              background:linear-gradient(135deg,#1A4A6B,#2C6E8A);
              box-shadow:0 4px 15px rgba(44,110,138,0.3);">
        + Ajouter une taxe
    </a>
</div>

<div style="background:white; border-radius:1rem; border:1px solid #E8EEF2; overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
        <thead>
            <tr style="background:#F8FBFD;">
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Nom</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Type</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Montant</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Statut</th>
                <th style="padding:1rem 1.25rem; text-align:right; font-weight:600; color:#5A6B7A;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($taxes as $tax)
            <tr style="border-top:1px solid #F0F4F8;">
                <td style="padding:1rem 1.25rem; font-weight:600; color:#1A2B3C;">{{ $tax->name }}</td>
                <td style="padding:1rem 1.25rem;">
                    @php
                    $typeLabels = [
                        'percentage'                 => '% Pourcentage',
                        'fixed_amount'               => 'Montant fixe',
                        'fixed_per_night'            => 'Fixe / nuit',
                        'fixed_per_person_per_night' => 'Fixe / pers / nuit',
                    ];
                    @endphp
                    <span style="padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:500; background:#EFF6FF; color:#1A4A6B;">
                        {{ $typeLabels[$tax->type] ?? $tax->type }}
                    </span>
                </td>
                <td style="padding:1rem 1.25rem; font-weight:600; color:#2C6E8A;">
                    {{ number_format($tax->amount, 3) }}{{ $tax->type === 'percentage' ? ' %' : ' TND' }}
                </td>
                <td style="padding:1rem 1.25rem;">
                    <button onclick="toggleTax({{ $tax->id }})" id="tax-btn-{{ $tax->id }}"
                            style="padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:500; border:none; cursor:pointer;
                                   {{ $tax->is_enabled ? 'background:#F0FDF4;color:#2A6B4F;' : 'background:#FEF2F2;color:#DC2626;' }}">
                        {{ $tax->is_enabled ? '✅ Active' : '❌ Inactive' }}
                    </button>
                </td>
                <td style="padding:1rem 1.25rem; text-align:right; white-space:nowrap;">
                    <a href="{{ route('taxes.edit', $tax) }}"
                       style="font-size:0.75rem; font-weight:500; color:#2C6E8A; text-decoration:none; margin-right:0.75rem;">
                        Modifier
                    </a>
                    <button onclick="deleteTax({{ $tax->id }}, '{{ addslashes($tax->name) }}')"
                            style="font-size:0.75rem; font-weight:500; color:#DC2626; background:none; border:none; cursor:pointer;">
                        Supprimer
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:2.5rem; text-align:center; color:#8A9BB0;">
                    Aucune taxe.
                    <a href="{{ route('taxes.create') }}" style="color:#2C6E8A; font-weight:500;">Créer la première</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
function toggleTax(id) {
    {{-- Route corrigée : /taxes/{tax}/toggle (name: taxes.toggle) --}}
    fetch('/taxes/' + id + '/toggle', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    }).then(r => r.json()).then(data => {
        const btn = document.getElementById('tax-btn-' + id);
        if (data.is_enabled) {
            btn.textContent = '✅ Active';
            btn.style.cssText = 'padding:0.25rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:500;border:none;cursor:pointer;background:#F0FDF4;color:#2A6B4F;';
        } else {
            btn.textContent = '❌ Inactive';
            btn.style.cssText = 'padding:0.25rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:500;border:none;cursor:pointer;background:#FEF2F2;color:#DC2626;';
        }
    });
}

function deleteTax(id, name) {
    Swal.fire({
        title: 'Supprimer "' + name + '" ?',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#DC2626', cancelButtonColor: '#6B7280',
        confirmButtonText: 'Supprimer', cancelButtonText: 'Annuler', background: '#FDFAF5'
    }).then(function(r) {
        if (r.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/taxes/' + id;
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
@extends('layouts.app')
@section('title', 'Extras')
@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">Extras</h1>
        <p style="font-size:0.875rem; color:#8A9BB0; margin:0.25rem 0 0;">Services et produits additionnels.</p>
    </div>
    <a href="{{ route('extras.create') }}"
       style="padding:0.625rem 1.25rem; border-radius:0.75rem; font-size:0.875rem; font-weight:700;
              color:white; text-decoration:none;
              background:linear-gradient(135deg,#1A4A6B,#2C6E8A);
              box-shadow:0 4px 15px rgba(44,110,138,0.3);">
        + Ajouter un extra
    </a>
</div>

<div style="background:white; border-radius:1rem; border:1px solid #E8EEF2; overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
        <thead>
            <tr style="background:#F8FBFD;">
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Nom</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Stock mode</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Stock actuel</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Alerte</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Statut</th>
                <th style="padding:1rem 1.25rem; text-align:right; font-weight:600; color:#5A6B7A;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($extras as $extra)
            <tr style="border-top:1px solid #F0F4F8;">
                <td style="padding:1rem 1.25rem; font-weight:600; color:#1A2B3C;">{{ $extra->name }}</td>
                <td style="padding:1rem 1.25rem;">
                    @php $modeColors = ['unlimited'=>'background:#EFF6FF;color:#1A4A6B;','consumable'=>'background:#FFFBEB;color:#92400E;','rentable'=>'background:#F5F3FF;color:#6D28D9;']; @endphp
                    <span style="padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:500; {{ $modeColors[$extra->stock_mode] }}">
                        {{ ucfirst($extra->stock_mode) }}
                    </span>
                </td>
                <td style="padding:1rem 1.25rem; font-weight:600; color:#1A2B3C;">
                    @if($extra->stock_mode !== 'unlimited')
                        @if($extra->stock_alert_threshold && $extra->stock_quantity <= $extra->stock_alert_threshold)
                            <span style="color:#DC2626;">⚠️ {{ $extra->stock_quantity }}</span>
                        @else
                            {{ $extra->stock_quantity ?? '—' }}
                        @endif
                    @else
                        <span style="color:#8A9BB0;">∞</span>
                    @endif
                </td>
                <td style="padding:1rem 1.25rem; color:#8A9BB0;">
                    {{ $extra->stock_alert_threshold ?? '—' }}
                </td>
                <td style="padding:1rem 1.25rem;">
                    <span style="padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:500;
                                 {{ $extra->is_enabled ? 'background:#F0FDF4;color:#2A6B4F;' : 'background:#FEF2F2;color:#DC2626;' }}">
                        {{ $extra->is_enabled ? '✅ Actif' : '❌ Inactif' }}
                    </span>
                </td>
                <td style="padding:1rem 1.25rem; text-align:right; white-space:nowrap;">
                    <a href="{{ route('extras.movements', $extra) }}"
                       style="font-size:0.75rem; font-weight:500; color:#2A6B4F; text-decoration:none; margin-right:0.75rem;">
                        Stock
                    </a>
                    <a href="{{ route('extras.edit', $extra) }}"
                       style="font-size:0.75rem; font-weight:500; color:#2C6E8A; text-decoration:none; margin-right:0.75rem;">
                        Modifier
                    </a>
                    <button onclick="deleteExtra({{ $extra->id }}, '{{ addslashes($extra->name) }}')"
                            style="font-size:0.75rem; font-weight:500; color:#DC2626; background:none; border:none; cursor:pointer;">
                        Supprimer
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:2.5rem; text-align:center; color:#8A9BB0;">
                    Aucun extra.
                    <a href="{{ route('extras.create') }}" style="color:#2C6E8A; font-weight:500;">Créer le premier</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
function deleteExtra(id, name) {
    Swal.fire({ title:'Supprimer "'+name+'" ?', icon:'warning', showCancelButton:true, confirmButtonColor:'#DC2626', cancelButtonColor:'#6B7280', confirmButtonText:'Supprimer', cancelButtonText:'Annuler', background:'#FDFAF5' })
    .then(function(r) {
        if (r.isConfirmed) {
            var form = document.createElement('form'); form.method='POST'; form.action='/extras/'+id;
            var csrf = document.createElement('input'); csrf.type='hidden'; csrf.name='_token'; csrf.value=document.querySelector('meta[name="csrf-token"]').content;
            var method = document.createElement('input'); method.type='hidden'; method.name='_method'; method.value='DELETE';
            form.appendChild(csrf); form.appendChild(method); document.body.appendChild(form); form.submit();
        }
    });
}
</script>
@endpush
@endsection
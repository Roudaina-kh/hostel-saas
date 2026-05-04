@extends('layouts.app')
@section('title', 'Indisponibilités')
@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">Indisponibilités</h1>
        <p style="font-size:0.875rem; color:#8A9BB0; margin:0.25rem 0 0;">Blocages planifiés de l'inventaire</p>
    </div>
    <a href="{{ route('manager.inventory-blocks.create') }}"
       style="padding:0.625rem 1.25rem; border-radius:0.75rem; font-size:0.875rem; font-weight:600;
              color:white; text-decoration:none; background:linear-gradient(135deg,#1A4A6B,#2C6E8A);
              box-shadow:0 4px 15px rgba(44,110,138,0.25);">
        + Ajouter
    </a>
</div>

@if(session('success'))
<div style="background:#F0FDF4; border:1px solid #86EFAC; color:#166534; border-radius:0.75rem;
            padding:0.875rem 1rem; margin-bottom:1rem; font-size:0.875rem;">
    {{ session('success') }}
</div>
@endif

<div style="background:white; border-radius:1rem; border:1px solid #E8EEF2; overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
        <thead>
            <tr style="background:#F8FBFD;">
                <th style="padding:0.875rem 1rem; text-align:left; font-weight:600; color:#5A6B7A;">Élément</th>
                <th style="padding:0.875rem 1rem; text-align:left; font-weight:600; color:#5A6B7A;">Type</th>
                <th style="padding:0.875rem 1rem; text-align:left; font-weight:600; color:#5A6B7A;">Blocage</th>
                <th style="padding:0.875rem 1rem; text-align:left; font-weight:600; color:#5A6B7A;">Période</th>
                <th style="padding:0.875rem 1rem; text-align:left; font-weight:600; color:#5A6B7A;">Motif</th>
                <th style="padding:0.875rem 1rem; text-align:left; font-weight:600; color:#5A6B7A;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($blocks as $block)
            @php
                $label = match($block->blockable_type) {
                    'room'       => 'Chambre',
                    'bed'        => 'Lit',
                    'tent_space' => 'Espace tente',
                    default      => $block->blockable_type,
                };
                $blockLabel = $block->block_type === 'maintenance' ? 'Maintenance' : 'Blocage manuel';
                $blockColor = $block->block_type === 'maintenance' ? 'background:#FEF3C7;color:#92400E;' : 'background:#FEE2E2;color:#991B1B;';
                $name = $block->blockable?->name ?? '—';
                $isActive = is_null($block->end_date) || $block->end_date >= now()->toDateString();
            @endphp
            <tr style="border-top:1px solid #F0F4F8;">
                <td style="padding:0.875rem 1rem; font-weight:600; color:#1A2B3C;">
                    {{ $name }}
                    <span style="font-size:0.75rem; color:#8A9BB0; font-weight:400; margin-left:0.25rem;">
                        ({{ $label }})
                    </span>
                </td>
                <td style="padding:0.875rem 1rem;">
                    <span style="font-size:0.75rem; font-weight:500; padding:0.2rem 0.6rem;
                                 border-radius:9999px; {{ $blockColor }}">
                        {{ $blockLabel }}
                    </span>
                </td>
                <td style="padding:0.875rem 1rem;">
                    <span style="font-size:0.75rem; font-weight:500; padding:0.2rem 0.6rem;
                                 border-radius:9999px;
                                 {{ $isActive ? 'background:#FEE2E2;color:#991B1B;' : 'background:#F0F4F8;color:#5A6B7A;' }}">
                        {{ $isActive ? '● Actif' : '○ Terminé' }}
                    </span>
                </td>
                <td style="padding:0.875rem 1rem; color:#5A6B7A;">
                    {{ \Carbon\Carbon::parse($block->start_date)->format('d/m/Y') }}
                    →
                    {{ $block->end_date ? \Carbon\Carbon::parse($block->end_date)->format('d/m/Y') : '∞' }}
                </td>
                <td style="padding:0.875rem 1rem; color:#5A6B7A; font-size:0.8rem;">
                    {{ $block->reason ?? '—' }}
                    @if($block->note)
                    <p style="margin:0.2rem 0 0; color:#8A9BB0; font-size:0.75rem;">{{ Str::limit($block->note, 60) }}</p>
                    @endif
                </td>
                <td style="padding:0.875rem 1rem;">
                    <div style="display:flex; gap:0.5rem;">
                        <a href="{{ route('manager.inventory-blocks.edit', $block) }}"
                           style="padding:0.375rem 0.75rem; border-radius:0.5rem; font-size:0.8rem;
                                  color:#1A4A6B; background:#EFF6FF; text-decoration:none; font-weight:500;">
                            Modifier
                        </a>
                        <form method="POST" action="{{ route('manager.inventory-blocks.destroy', $block) }}"
                              onsubmit="return confirm('Supprimer cette indisponibilité ?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="padding:0.375rem 0.75rem; border-radius:0.5rem; font-size:0.8rem;
                                           color:#DC2626; background:#FEF2F2; border:none; cursor:pointer; font-weight:500;">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:2.5rem; text-align:center; color:#8A9BB0;">
                    Aucune indisponibilité enregistrée.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
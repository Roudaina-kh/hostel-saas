@extends('layouts.app')
@section('title', 'Stock — ' . $extra->name)
@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">Stock — {{ $extra->name }}</h1>
        <p style="font-size:0.875rem; color:#8A9BB0; margin:0.25rem 0 0;">
            Stock actuel :
            <strong style="color:{{ $extra->stock_alert_threshold && $extra->stock_quantity <= $extra->stock_alert_threshold ? '#DC2626' : '#2A6B4F' }};">
                {{ $extra->stock_mode !== 'unlimited' ? $extra->stock_quantity : '∞' }}
            </strong>
        </p>
    </div>
    <a href="{{ route('extras.index') }}"
       style="padding:0.625rem 1.25rem; border-radius:0.75rem; font-size:0.875rem; font-weight:500;
              color:#5A6B7A; text-decoration:none; background:#F8FBFD; border:1px solid #E8EEF2;">
        ← Retour aux extras
    </a>
</div>

<div style="display:grid; grid-template-columns:1fr 1.5fr; gap:1.5rem;">

    {{-- Formulaire ajout mouvement --}}
    <div style="background:white; border-radius:1rem; padding:1.5rem; border:1px solid #E8EEF2; height:fit-content;">
        <h2 style="font-size:1rem; font-weight:700; color:#1A2B3C; margin:0 0 1rem;">Enregistrer un mouvement</h2>

        @if($errors->any())
        <div style="background:#FEF2F2; border:1px solid #FECACA; color:#DC2626; border-radius:0.75rem; padding:1rem; margin-bottom:1rem; font-size:0.875rem;">
            @foreach($errors->all() as $e)<p style="margin:0.1rem 0;">• {{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('extras.movements.store', $extra) }}">
            @csrf
            @php $input = 'width:100%; border-radius:0.75rem; padding:0.75rem 1rem; font-size:0.875rem; outline:none; border:1.5px solid #D8E8F0; background:#F8FBFD; color:#1A2B3C; box-sizing:border-box; margin-bottom:1rem;'; @endphp

            <div>
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Type de mouvement *</label>
                <select name="movement_type" required style="{{ $input }}">
                    <optgroup label="➕ Entrées">
                        <option value="initial">Stock initial</option>
                        <option value="purchase">Achat / réapprovisionnement</option>
                        <option value="adjustment_in">Correction positive</option>
                        <option value="return">Retour</option>
                    </optgroup>
                    <optgroup label="➖ Sorties">
                        <option value="adjustment_out">Correction négative</option>
                        <option value="damage">Casse</option>
                        <option value="loss">Perte</option>
                    </optgroup>
                </select>
            </div>

            <div>
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Quantité *</label>
                <input type="number" name="quantity" min="1" required style="{{ $input }}" placeholder="Ex: 10">
            </div>

            <div>
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Note</label>
                <textarea name="note" rows="2" style="{{ $input }}" placeholder="Ex: Achat fournisseur du 12 mai"></textarea>
            </div>

            {{-- Sécurité : vérification du mot de passe de l'utilisateur qui valide --}}
            <div style="background:#FFFBEB; border:1px solid #FCD34D; border-radius:0.75rem; padding:1rem; margin-bottom:1rem;">
                <p style="font-size:0.75rem; font-weight:600; color:#92400E; margin:0 0 0.75rem;">
                    🔐 Sécurité — Validation par mot de passe
                </p>
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Utilisateur *</label>
                <select name="created_by" required style="{{ $input }}">
                    <option value="">-- Sélectionner --</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Mot de passe *</label>
                <input type="password" name="password" required style="{{ $input }}" placeholder="Mot de passe de l'utilisateur sélectionné">
            </div>

            <button type="submit"
                    style="width:100%; padding:0.75rem; border-radius:0.75rem; font-size:0.875rem; font-weight:700;
                           color:white; border:none; cursor:pointer;
                           background:linear-gradient(135deg,#1A4A6B,#2C6E8A);
                           box-shadow:0 4px 15px rgba(44,110,138,0.3);">
                Enregistrer le mouvement
            </button>
        </form>
    </div>

    {{-- Historique --}}
    <div style="background:white; border-radius:1rem; border:1px solid #E8EEF2; overflow:hidden; height:fit-content;">
        <div style="padding:1rem 1.25rem; border-bottom:1px solid #F0F4F8;">
            <h2 style="font-size:1rem; font-weight:700; color:#1A2B3C; margin:0;">Historique des mouvements</h2>
        </div>
        <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
            <thead>
                <tr style="background:#F8FBFD;">
                    <th style="padding:0.75rem 1rem; text-align:left; font-weight:600; color:#5A6B7A;">Type</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-weight:600; color:#5A6B7A;">Qté</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-weight:600; color:#5A6B7A;">Par</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-weight:600; color:#5A6B7A;">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $mv)
                <tr style="border-top:1px solid #F0F4F8;">
                    <td style="padding:0.75rem 1rem;">
                        @php
                        $isIn = $mv->isIncrease();
                        $typeLabels = ['initial'=>'Initial','purchase'=>'Achat','adjustment_in'=>'Correction +','adjustment_out'=>'Correction -','damage'=>'Casse','loss'=>'Perte','return'=>'Retour'];
                        @endphp
                        <span style="font-size:0.75rem; font-weight:500; padding:0.2rem 0.6rem; border-radius:9999px;
                                     {{ $isIn ? 'background:#F0FDF4;color:#2A6B4F;' : 'background:#FEF2F2;color:#DC2626;' }}">
                            {{ $isIn ? '↑' : '↓' }} {{ $typeLabels[$mv->movement_type] ?? $mv->movement_type }}
                        </span>
                        @if($mv->note)
                        <p style="font-size:0.7rem; color:#8A9BB0; margin:0.25rem 0 0;">{{ $mv->note }}</p>
                        @endif
                    </td>
                    <td style="padding:0.75rem 1rem; font-weight:700; color:{{ $isIn ? '#2A6B4F' : '#DC2626' }};">
                        {{ $isIn ? '+' : '-' }}{{ $mv->quantity }}
                    </td>
                    <td style="padding:0.75rem 1rem; color:#5A6B7A; font-size:0.8rem;">{{ $mv->creator->name ?? '—' }}</td>
                    <td style="padding:0.75rem 1rem; color:#8A9BB0; font-size:0.8rem;">{{ $mv->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding:2rem; text-align:center; color:#8A9BB0;">Aucun mouvement enregistré.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
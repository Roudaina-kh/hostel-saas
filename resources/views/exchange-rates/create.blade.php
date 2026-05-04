@extends('layouts.app')
@section('title', 'Ajouter un taux de change')
@section('content')

<div style="max-width:560px;">
    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">
            Ajouter un taux de change
        </h1>
        <p style="font-size:0.875rem; color:#8A9BB0; margin:0.25rem 0 0;">
            Ce taux deviendra le taux actif pour cette devise. L'historique est conservé et immuable.
        </p>
    </div>

    {{-- Avertissement immutabilité --}}
    <div style="background:#FFFBEB; border:1px solid #FCD34D; border-radius:0.75rem;
                padding:1rem; margin-bottom:1.5rem; font-size:0.875rem; color:#92400E;">
        ⚠️ <strong>Important :</strong> Un taux ajouté ne peut jamais être modifié ni supprimé.
        C'est une donnée historique financière. Pour changer le taux actif, ajoutez simplement un nouveau taux.
    </div>

    <div style="background:white; border-radius:1rem; padding:2rem; border:1px solid #E8EEF2;">

        @if($errors->any())
        <div style="background:#FEF2F2; border:1px solid #FECACA; color:#DC2626;
                    border-radius:0.75rem; padding:1rem; margin-bottom:1.5rem; font-size:0.875rem;">
            @foreach($errors->all() as $e)<p style="margin:0.1rem 0;">• {{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('exchange-rates.store') }}">
            @csrf

            @php $input = 'width:100%; border-radius:0.75rem; padding:0.75rem 1rem; font-size:0.875rem; outline:none; border:1.5px solid #D8E8F0; background:#F8FBFD; color:#1A2B3C; box-sizing:border-box;'; @endphp

            <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">
                    Devise *
                </label>
                <select name="currency" required style="{{ $input }}">
                    <option value="">-- Sélectionner --</option>
                    @foreach(['EUR','USD','GBP','MAD','DZD','EGP','LYD','SAR','AED','QAR','CHF','CAD','AUD','JPY','CNY'] as $c)
                        <option value="{{ $c }}" {{ old('currency') === $c ? 'selected' : '' }}>
                            {{ $c }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.25rem;">
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">
                        Taux achat (→ TND) *
                    </label>
                    <input type="number" name="buy_rate_to_tnd" step="0.0001" min="0"
                           value="{{ old('buy_rate_to_tnd') }}" required style="{{ $input }}"
                           placeholder="Ex: 3.3500">
                    <p style="font-size:0.75rem; color:#8A9BB0; margin:0.25rem 0 0;">
                        TND que l'hostel paie pour 1 {{ old('currency', 'devise') }}
                    </p>
                </div>
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">
                        Taux vente (→ TND) *
                    </label>
                    <input type="number" name="sell_rate_to_tnd" step="0.0001" min="0"
                           value="{{ old('sell_rate_to_tnd') }}" required style="{{ $input }}"
                           placeholder="Ex: 3.4100">
                    <p style="font-size:0.75rem; color:#8A9BB0; margin:0.25rem 0 0;">
                        TND que l'hostel reçoit pour 1 {{ old('currency', 'devise') }}
                    </p>
                </div>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">
                    Saisi par
                </label>
                <select name="created_by" style="{{ $input }}">
                    <option value="">-- Optionnel --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('created_by') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex; gap:0.75rem;">
                <button type="submit"
                        style="padding:0.75rem 1.5rem; border-radius:0.75rem; font-size:0.875rem; font-weight:700;
                               color:white; border:none; cursor:pointer;
                               background:linear-gradient(135deg,#1A4A6B,#2C6E8A);
                               box-shadow:0 4px 15px rgba(44,110,138,0.3);">
                    Enregistrer le taux
                </button>
                <a href="{{ route('exchange-rates.index') }}"
                   style="padding:0.75rem 1.5rem; border-radius:0.75rem; font-size:0.875rem; font-weight:500;
                          color:#5A6B7A; text-decoration:none; background:#F8FBFD; border:1px solid #E8EEF2;">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
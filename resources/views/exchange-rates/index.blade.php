@extends('layouts.app')
@section('title', 'Taux de change')
@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">Taux de change</h1>
        <p style="font-size:0.875rem; color:#8A9BB0; margin:0.25rem 0 0;">
            Historique des taux. Le taux actif est toujours le plus récent par devise.
        </p>
    </div>
    <a href="{{ route('exchange-rates.create') }}"
       style="padding:0.625rem 1.25rem; border-radius:0.75rem; font-size:0.875rem; font-weight:700;
              color:white; text-decoration:none;
              background:linear-gradient(135deg,#1A4A6B,#2C6E8A);
              box-shadow:0 4px 15px rgba(44,110,138,0.3);">
        + Ajouter un taux
    </a>
</div>

{{-- Taux actifs --}}
@if($activeRates->count() > 0)
<div style="margin-bottom:1.5rem;">
    <h2 style="font-size:1rem; font-weight:700; color:#1A2B3C; margin:0 0 0.75rem;">Taux actifs</h2>
    <div style="display:flex; gap:1rem; flex-wrap:wrap;">
        @foreach($activeRates as $rate)
        <div style="background:white; border-radius:0.75rem; padding:1rem 1.5rem;
                    border:1px solid #E8EEF2; display:flex; align-items:center; gap:1rem;">
            <span style="font-size:1.25rem; font-weight:900; color:#1A4A6B;">
                {{ $rate->currency }}
            </span>
            <div>
                <div style="font-size:0.75rem; color:#8A9BB0; font-weight:500;">
                    Achat : <strong style="color:#2A6B4F;">{{ number_format($rate->buy_rate_to_tnd, 4) }} TND</strong>
                </div>
                <div style="font-size:0.75rem; color:#8A9BB0; font-weight:500;">
                    Vente : <strong style="color:#2C6E8A;">{{ number_format($rate->sell_rate_to_tnd, 4) }} TND</strong>
                </div>
            </div>
            <span style="font-size:0.7rem; color:#8A9BB0;">
                {{ $rate->created_at->format('d/m/Y') }}
            </span>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Historique complet --}}
<div style="background:white; border-radius:1rem; border:1px solid #E8EEF2; overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
        <thead>
            <tr style="background:#F8FBFD;">
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Devise</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Taux achat (→ TND)</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Taux vente (→ TND)</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Saisi par</th>
                <th style="padding:1rem 1.25rem; text-align:left; font-weight:600; color:#5A6B7A;">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($exchangeRates as $rate)
            <tr style="border-top:1px solid #F0F4F8;">
                <td style="padding:1rem 1.25rem;">
                    <span style="padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.8rem;
                                 font-weight:700; background:#EFF6FF; color:#1A4A6B;">
                        {{ $rate->currency }}
                    </span>
                </td>
                <td style="padding:1rem 1.25rem; font-weight:600; color:#2A6B4F;">
                    {{ number_format($rate->buy_rate_to_tnd, 4) }} TND
                </td>
                <td style="padding:1rem 1.25rem; font-weight:600; color:#2C6E8A;">
                    {{ number_format($rate->sell_rate_to_tnd, 4) }} TND
                </td>
                <td style="padding:1rem 1.25rem; color:#5A6B7A; font-size:0.8rem;">
                    {{ $rate->creator->name ?? '—' }}
                </td>
                <td style="padding:1rem 1.25rem; color:#8A9BB0; font-size:0.8rem;">
                    {{ $rate->created_at->format('d/m/Y H:i') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:2.5rem; text-align:center; color:#8A9BB0;">
                    Aucun taux enregistré.
                    <a href="{{ route('exchange-rates.create') }}" style="color:#2C6E8A; font-weight:500;">
                        Ajouter le premier
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
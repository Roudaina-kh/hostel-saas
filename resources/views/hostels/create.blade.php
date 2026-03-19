@extends('layouts.app')
@section('title', 'Ajouter un hostel')
@section('content')

<div style="max-width:600px;">
    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">Ajouter un hostel</h1>
        <p style="font-size:0.875rem; color:#8A9BB0; margin:0.25rem 0 0;">Créez un nouveau hostel pour votre compte.</p>
    </div>

    <div style="background:white; border-radius:1rem; padding:2rem; border:1px solid #E8EEF2; box-shadow:0 1px 3px rgba(0,0,0,0.05);">

        @if($errors->any())
        <div style="background:#FEF2F2; border:1px solid #FECACA; color:#DC2626; border-radius:0.75rem; padding:1rem; margin-bottom:1.5rem; font-size:0.875rem;">
            @foreach($errors->all() as $e)<p style="margin:0.1rem 0;">• {{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('hostels.store') }}">
            @csrf

            @php $input = 'width:100%; border-radius:0.75rem; padding:0.75rem 1rem; font-size:0.875rem; outline:none; border:1.5px solid #D8E8F0; background:#F8FBFD; color:#1A2B3C; box-sizing:border-box;'; @endphp

            <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Nom du hostel *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ex: Nomads Tunis" style="{{ $input }}">
            </div>

            <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Adresse</label>
                <input type="text" name="address" value="{{ old('address') }}" placeholder="12 Rue de la Médina" style="{{ $input }}">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.25rem;">
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Ville *</label>
                    <input type="text" name="city" value="{{ old('city') }}" required placeholder="Tunis" style="{{ $input }}">
                </div>
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Pays *</label>
                    <input type="text" name="country" value="{{ old('country', 'Tunisie') }}" required style="{{ $input }}">
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.25rem;">
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+216 xx xxx xxx" style="{{ $input }}">
                </div>
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="contact@hostel.tn" style="{{ $input }}">
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem;">
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Devise *</label>
                    <select name="default_currency" style="{{ $input }}">
                        <option value="TND" {{ old('default_currency') === 'TND' ? 'selected' : '' }}>TND — Dinar</option>
                        <option value="EUR">EUR — Euro</option>
                        <option value="USD">USD — Dollar</option>
                        <option value="MAD">MAD — Dirham</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Fuseau horaire</label>
                    <select name="timezone" style="{{ $input }}">
                        <option value="Africa/Tunis">Africa/Tunis</option>
                        <option value="Europe/Paris">Europe/Paris</option>
                        <option value="UTC">UTC</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; gap:0.75rem;">
                <button type="submit"
                        style="padding:0.75rem 1.5rem; border-radius:0.75rem; font-size:0.875rem; font-weight:700;
                               color:white; border:none; cursor:pointer;
                               background:linear-gradient(135deg,#1A4A6B,#2C6E8A);
                               box-shadow:0 4px 15px rgba(44,110,138,0.3);">
                    Créer le hostel
                </button>
                <a href="{{ route('hostels.index') }}"
                   style="padding:0.75rem 1.5rem; border-radius:0.75rem; font-size:0.875rem; font-weight:500;
                          color:#5A6B7A; text-decoration:none; background:#F8FBFD; border:1px solid #E8EEF2;">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
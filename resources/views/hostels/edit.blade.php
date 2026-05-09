@extends('layouts.app')
@section('title', 'Modifier le hostel')
@section('content')

<div style="max-width:700px;">
    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">Modifier : {{ $hostel->name }}</h1>
    </div>

    <div style="background:white; border-radius:1rem; padding:2rem; border:1px solid #E8EEF2; box-shadow:0 1px 3px rgba(0,0,0,0.05);">

        @if($errors->any())
        <div style="background:#FEF2F2; border:1px solid #FECACA; color:#DC2626; border-radius:0.75rem; padding:1rem; margin-bottom:1.5rem; font-size:0.875rem;">
            @foreach($errors->all() as $e)<p style="margin:0.1rem 0;">• {{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('hostels.update', $hostel) }}">
            @csrf
            @method('PUT')

            @php $input = 'width:100%; border-radius:0.75rem; padding:0.75rem 1rem; font-size:0.875rem; outline:none; border:1.5px solid #D8E8F0; background:#F8FBFD; color:#1A2B3C; box-sizing:border-box;'; @endphp

            {{-- Nom --}}
            <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Nom du hostel *</label>
                <input type="text" name="name" value="{{ old('name', $hostel->name) }}" required style="{{ $input }}">
            </div>

            {{-- ✅ RÉGION — select direct name="region_id" — zéro JS --}}
            <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">
          {{-- ✅ RÉGION — Gouvernorat + Ville (datalist searchable) --}}
@php
    $currentRegion = $regions->flatMap(fn($g) => $g->children)->push(...$regions)->firstWhere('id', $hostel->region_id);
    $currentGov    = $currentRegion?->parent_id
                        ? $regions->firstWhere('id', $currentRegion->parent_id)
                        : $currentRegion;
    $currentVille  = $currentRegion?->parent_id ? $currentRegion : null;
@endphp

<input type="hidden" name="region_id" id="regionIdFinal" value="{{ old('region_id', $hostel->region_id) }}" required>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.25rem;">
    <div>
        <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">
            Gouvernorat *
        </label>
        <input type="text" id="gouvernoratInput" list="gouvernoratList"
               value="{{ $currentGov?->name }}"
               placeholder="Tapez pour chercher" autocomplete="off"
               style="{{ $input }}">
        <datalist id="gouvernoratList">
            @foreach($regions as $g)
                <option value="{{ $g->name }}"></option>
            @endforeach
        </datalist>
    </div>
    <div>
        <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">
            Ville (optionnel)
        </label>
        <input type="text" id="villeInput" list="villeList"
               value="{{ $currentVille?->name }}"
               placeholder="Tapez pour chercher" autocomplete="off"
               style="{{ $input }}">
        <datalist id="villeList"></datalist>
    </div>
</div>

            {{-- Adresse --}}
            <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Adresse</label>
                <input type="text" name="address" value="{{ old('address', $hostel->address) }}" style="{{ $input }}">
            </div>


            {{-- Téléphone + Email --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.25rem;">
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone', $hostel->phone) }}" style="{{ $input }}">
                </div>
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $hostel->email) }}" style="{{ $input }}">
                </div>
            </div>

            {{-- Devise + Fuseau --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem;">
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Devise *</label>
                    <select name="default_currency" style="{{ $input }} cursor:pointer;">
                        <option value="TND" {{ old('default_currency', $hostel->default_currency) === 'TND' ? 'selected' : '' }}>TND — Dinar</option>
                        <option value="EUR" {{ old('default_currency', $hostel->default_currency) === 'EUR' ? 'selected' : '' }}>EUR — Euro</option>
                        <option value="USD" {{ old('default_currency', $hostel->default_currency) === 'USD' ? 'selected' : '' }}>USD — Dollar</option>
                        <option value="MAD" {{ old('default_currency', $hostel->default_currency) === 'MAD' ? 'selected' : '' }}>MAD — Dirham</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:600; color:#1A2B3C; margin-bottom:0.5rem;">Fuseau horaire</label>
                    <select name="timezone" style="{{ $input }} cursor:pointer;">
                        <option value="Africa/Tunis" {{ old('timezone', $hostel->timezone) === 'Africa/Tunis' ? 'selected' : '' }}>Africa/Tunis</option>
                        <option value="Europe/Paris" {{ old('timezone', $hostel->timezone) === 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris</option>
                        <option value="UTC"          {{ old('timezone', $hostel->timezone) === 'UTC'          ? 'selected' : '' }}>UTC</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; gap:0.75rem;">
                <button type="submit"
                        style="padding:0.75rem 1.5rem; border-radius:0.75rem; font-size:0.875rem; font-weight:700;
                               color:white; border:none; cursor:pointer;
                               background:linear-gradient(135deg,#1A4A6B,#2C6E8A);
                               box-shadow:0 4px 15px rgba(44,110,138,0.3);">
                    ✅ Enregistrer
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
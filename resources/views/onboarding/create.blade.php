<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer votre hostel — HostelFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sand:    #F5EFE6;
            --sand2:   #EDE3D4;
            --terra:   #C8602A;
            --terra2:  #A84E20;
            --teal:    #1B6B6B;
            --teal2:   #134F4F;
            --night:   #1C1C24;
            --ink:     #2E2E3A;
            --gray:    #6B6B7A;
            --lgray:   #A0A0B0;
            --border:  #DDD6CA;
            --white:   #FEFCF9;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(165deg, #1C1C24 0%, #2E3A35 40%, #1B6B6B 100%);
            position: relative;
            overflow-x: hidden;
            color: var(--ink);
            padding: 32px 20px;
        }

        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(110px);
            pointer-events: none;
            animation: floatBlob 14s ease-in-out infinite alternate;
        }
        .blob-1 { width: 550px; height: 550px; background: rgba(200, 96, 42, 0.22); top: -15%; left: -12%; }
        .blob-2 { width: 650px; height: 650px; background: rgba(27, 107, 107, 0.30); bottom: -22%; right: -12%; animation-delay: -7s; }
        @keyframes floatBlob {
            0%   { transform: translateY(0) scale(1); }
            100% { transform: translateY(-28px) scale(1.06); }
        }

        .page-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
        }

        .card {
            background: rgba(254, 252, 249, 0.92);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(254, 252, 249, 0.6);
            border-radius: 28px;
            padding: 44px 40px 40px;
            box-shadow: 0 24px 48px -12px rgba(28, 28, 36, 0.35);
        }

        .fade-up {
            opacity: 0;
            transform: translateY(18px);
            animation: fadeUp 0.75s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
        .d1 { animation-delay: 0ms; }
        .d2 { animation-delay: 120ms; }
        .d3 { animation-delay: 200ms; }
        .d4 { animation-delay: 280ms; }
        .d5 { animation-delay: 360ms; }
        .d6 { animation-delay: 440ms; }
        .d7 { animation-delay: 520ms; }
        .d8 { animation-delay: 600ms; }

        .logo-ring {
            width: 88px; height: 88px;
            margin: 0 auto 22px;
            border-radius: 50%;
            background: var(--white);
            border: 4px solid rgba(254, 252, 249, 0.9);
            box-shadow: 0 8px 30px rgba(28, 28, 36, 0.15);
            overflow: hidden;
            display: flex; align-items: center; justify-content: center;
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .logo-ring:hover { transform: scale(1.08) rotate(-3deg); }
        .logo-ring img { width: 100%; height: 100%; object-fit: cover; }
        .logo-fallback { font-family: 'Playfair Display', serif; font-size: 26px; font-weight: 700; color: var(--terra); }

        .header { text-align: center; margin-bottom: 32px; }
        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 26px; font-weight: 700;
            color: var(--ink); line-height: 1.2;
        }
        .header h1 em {
            font-style: italic;
            color: var(--terra);
        }
        .header p {
            margin-top: 10px; font-size: 13.5px;
            font-weight: 500; color: var(--gray);
        }

        .error-banner {
            background: rgba(168, 78, 32, 0.08);
            border-left: 4px solid var(--terra2);
            border-radius: 0 12px 12px 0;
            padding: 14px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            color: var(--terra2);
        }
        .error-banner p { font-weight: 600; }

        .form-group { margin-bottom: 18px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px; }

        .form-label {
            display: block; font-size: 12px; font-weight: 700;
            color: var(--gray); margin-bottom: 8px; padding-left: 4px;
            text-transform: uppercase; letter-spacing: 0.06em;
        }

        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; top: 50%; left: 16px;
            transform: translateY(-50%);
            width: 18px; height: 18px;
            color: var(--lgray); pointer-events: none;
            transition: color 0.25s;
        }
        .input-wrap:focus-within .input-icon { color: var(--terra); }

        .input-field {
            width: 100%;
            padding: 13px 16px 13px 44px;
            font-size: 14px; font-weight: 500; font-family: inherit;
            color: var(--ink); background: var(--sand);
            border: 1.5px solid var(--border);
            border-radius: 14px; outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        }
        .input-field-plain {
            width: 100%;
            padding: 13px 16px;
            font-size: 14px; font-weight: 500; font-family: inherit;
            color: var(--ink); background: var(--sand);
            border: 1.5px solid var(--border);
            border-radius: 14px; outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
            appearance: none; -webkit-appearance: none;
        }
        .input-field::placeholder, .input-field-plain::placeholder { color: var(--lgray); font-weight: 400; }
        .input-field:focus, .input-field-plain:focus {
            border-color: var(--terra);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(200, 96, 42, 0.10);
        }
        select.input-field-plain {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23A0A0B0' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 16px;
            padding-right: 40px;
        }

        .btn-submit {
            width: 100%; padding: 14px 20px;
            font-size: 14px; font-weight: 700; font-family: inherit;
            letter-spacing: 0.3px; color: #fff;
            background: var(--terra);
            border: none; border-radius: 14px; cursor: pointer;
            position: relative; overflow: hidden;
            box-shadow: 0 8px 24px rgba(200, 96, 42, 0.32);
            transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 8px;
        }
        .btn-submit:hover {
            background: var(--terra2);
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(200, 96, 42, 0.42);
        }
        .btn-submit::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(120deg, transparent 30%, rgba(255, 255, 255, 0.18) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }
        .btn-submit:hover::after { transform: translateX(100%); }
        .btn-submit svg { width: 16px; height: 16px; transition: transform 0.25s; }
        .btn-submit:hover svg { transform: translateX(4px); }

        /* Country autocomplete */
        .autocomplete-wrap { position: relative; width: 100%; }
        .autocomplete-list {
            position: absolute; top: calc(100% + 6px); left: 0; right: 0; z-index: 1000;
            background: var(--white);
            border: 1.5px solid var(--terra);
            border-radius: 14px;
            max-height: 200px; overflow-y: auto;
            box-shadow: 0 8px 24px rgba(28, 28, 36, 0.12);
            display: none;
        }
        .autocomplete-list.open { display: block; }
        .autocomplete-item {
            padding: 10px 16px;
            font-size: 13.5px; font-weight: 500;
            color: var(--ink); cursor: pointer;
            transition: background 0.15s;
        }
        .autocomplete-item:hover, .autocomplete-item.active { background: var(--sand); }
        .autocomplete-list::-webkit-scrollbar { width: 5px; }
        .autocomplete-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

        /* Phone code selector */
        .phone-wrap { display: flex; gap: 0; }
        .phone-code-btn {
            flex-shrink: 0;
            padding: 0 12px;
            background: var(--sand);
            border: 1.5px solid var(--border);
            border-right: none;
            border-radius: 14px 0 0 14px;
            font-size: 13px; font-weight: 600;
            color: var(--ink); cursor: pointer;
            display: flex; align-items: center; gap: 6px;
            transition: border-color 0.25s, background 0.25s;
            white-space: nowrap;
        }
        .phone-code-btn:focus { outline: none; }
        .phone-wrap:focus-within .phone-code-btn {
            border-color: var(--terra);
            background: var(--white);
        }
        .phone-number-input {
            flex: 1;
            padding: 13px 16px;
            font-size: 14px; font-weight: 500; font-family: inherit;
            color: var(--ink); background: var(--sand);
            border: 1.5px solid var(--border);
            border-left: none;
            border-radius: 0 14px 14px 0; outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        }
        .phone-number-input::placeholder { color: var(--lgray); font-weight: 400; }
        .phone-number-input:focus {
            border-color: var(--terra);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(200, 96, 42, 0.10);
        }
        .phone-dropdown {
            position: absolute; top: calc(100% + 6px); left: 0; right: 0; z-index: 1100;
            width: 100%; min-width: 240px;
            background: var(--white);
            border: 1.5px solid var(--terra);
            border-radius: 14px;
            max-height: 220px; overflow-y: auto;
            box-shadow: 0 8px 24px rgba(28, 28, 36, 0.12);
            display: none;
        }
        .phone-dropdown.open { display: block; }
        .phone-dropdown-search {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; background: var(--white);
        }
        .phone-dropdown-search input {
            width: 100%; border: none; outline: none;
            font-size: 13px; font-family: inherit;
            color: var(--ink); background: transparent;
        }
        .phone-dropdown-item {
            padding: 9px 14px;
            font-size: 13px; font-weight: 500;
            color: var(--ink); cursor: pointer;
            display: flex; justify-content: space-between;
            transition: background 0.15s;
        }
        .phone-dropdown-item:hover { background: var(--sand); }
        .phone-dropdown-item span.code { color: var(--terra); font-weight: 700; }
        .phone-dropdown::-webkit-scrollbar { width: 5px; }
        .phone-dropdown::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

        @media (max-width: 520px) {
            .card { padding: 36px 24px 32px; }
            .header h1 { font-size: 22px; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="page-wrapper">
        <div class="card">

            {{-- Logo --}}
            <div class="fade-up d1" style="text-align:center;">
                <div class="logo-ring">
                    <img src="{{ asset('images/13.png') }}"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                         alt="Logo HostelFlow">
                    <span class="logo-fallback" style="display:none;">HF</span>
                </div>
            </div>

            {{-- Heading --}}
            <div class="header fade-up d2">
                <h1>Créez votre <em>hostel</em></h1>
                <p>Ces informations apparaîtront sur vos documents et réservations.</p>
            </div>

            {{-- Erreurs --}}
            @if($errors->any())
            <div class="error-banner fade-up d2">
                @foreach($errors->all() as $e)
                    <p>→ {{ $e }}</p>
                @endforeach
            </div>
            @endif

            {{-- Formulaire --}}
            <form method="POST" action="{{ route('onboarding.store') }}">
                @csrf

                {{-- Nom du hostel --}}
                <div class="form-group fade-up d3">
                    <label class="form-label" for="name">Nom du hostel *</label>
                    <div class="input-wrap">
                        <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <input id="name" class="input-field" type="text"
                               name="name" value="{{ old('name') }}"
                               placeholder="Ex: Nomads Tunis"
                               required autocomplete="organization">
                    </div>
                </div>

                {{-- Adresse --}}
                <div class="form-group fade-up d4">
                    <label class="form-label" for="address">Adresse</label>
                    <div class="input-wrap">
                        <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <input id="address" class="input-field" type="text"
                               name="address" value="{{ old('address') }}"
                               placeholder="12 Rue de la Médina">
                    </div>
                </div>

                {{-- Ville --}}
                <div class="form-group fade-up d5">
                    <label class="form-label" for="city">Ville *</label>
                    <div class="input-wrap">
                        <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <input id="city" class="input-field" type="text"
                               name="city" value="{{ old('city') }}"
                               placeholder="Tunis" required>
                    </div>
                </div>

                {{-- Région (Gouvernorat) --}}
                <div class="form-group fade-up d5">
                    <label class="form-label" for="region_id">Gouvernorat / Région *</label>
                    <select id="region_id" name="region_id" class="input-field-plain" required>
                        <option value="">— Sélectionner votre gouvernorat —</option>
                        @foreach($regions as $gouvernorat)
                            <option value="{{ $gouvernorat->id }}"
                                {{ old('region_id') == $gouvernorat->id ? 'selected' : '' }}>
                                {{ $gouvernorat->name }}
                            </option>
                            @foreach($gouvernorat->children as $ville)
                                <option value="{{ $ville->id }}"
                                    {{ old('region_id') == $ville->id ? 'selected' : '' }}>
                                    &nbsp;&nbsp;↳ {{ $ville->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                {{-- Pays --}}
                <div class="form-group fade-up d5" style="position:relative; z-index: 50;">
                    <label class="form-label">Pays *</label>
                    <div class="autocomplete-wrap">
                        <div class="input-wrap">
                            <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                            </svg>
                            <input id="country-input" class="input-field" type="text"
                                   autocomplete="off"
                                   placeholder="Tapez pour chercher un pays…"
                                   value="{{ old('country', 'Tunisie') }}">
                            <input type="hidden" name="country" id="country-value" value="{{ old('country', 'Tunisie') }}">
                        </div>
                        <div class="autocomplete-list" id="country-list"></div>
                    </div>
                </div>

                {{-- Téléphone --}}
                <div class="form-group fade-up d6" style="position:relative; z-index: 40;">
                    <label class="form-label">Téléphone</label>
                    <div style="position:relative;">
                        <div class="phone-wrap">
                            <button type="button" class="phone-code-btn" id="phone-code-btn">
                                <span id="phone-flag">🇹🇳</span><span id="phone-code-label">+216</span>
                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <input type="hidden" name="phone_code" id="phone-code-value" value="+216">
                            <input id="phone" class="phone-number-input" type="text"
                                   name="phone" value="{{ old('phone') }}"
                                   placeholder="xx xxx xxx">
                        </div>
                        <div class="phone-dropdown" id="phone-dropdown">
                            <div class="phone-dropdown-search">
                                <input type="text" id="phone-search" placeholder="Rechercher un pays…">
                            </div>
                            <div id="phone-dropdown-list"></div>
                        </div>
                    </div>
                </div>

                {{-- Email --}}
                <div class="form-group fade-up d6">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-wrap">
                        <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                        <input id="email" class="input-field" type="email"
                               name="email" value="{{ old('email') }}"
                               placeholder="contact@hostel.tn">
                    </div>
                </div>

                {{-- Devise / Fuseau horaire --}}
                <div class="form-grid fade-up d7">
                    <div>
                        <label class="form-label" for="default_currency">Devise *</label>
                        <select id="default_currency" name="default_currency" class="input-field-plain">
                            <option value="TND" {{ old('default_currency','TND')=='TND'?'selected':'' }}>TND — Dinar tunisien</option>
                            <option value="EUR" {{ old('default_currency')=='EUR'?'selected':'' }}>EUR — Euro</option>
                            <option value="USD" {{ old('default_currency')=='USD'?'selected':'' }}>USD — Dollar américain</option>
                            <option value="GBP" {{ old('default_currency')=='GBP'?'selected':'' }}>GBP — Livre sterling</option>
                            <option value="MAD" {{ old('default_currency')=='MAD'?'selected':'' }}>MAD — Dirham marocain</option>
                            <option value="DZD" {{ old('default_currency')=='DZD'?'selected':'' }}>DZD — Dinar algérien</option>
                            <option value="EGP" {{ old('default_currency')=='EGP'?'selected':'' }}>EGP — Livre égyptienne</option>
                            <option value="LYD" {{ old('default_currency')=='LYD'?'selected':'' }}>LYD — Dinar libyen</option>
                            <option value="SAR" {{ old('default_currency')=='SAR'?'selected':'' }}>SAR — Riyal saoudien</option>
                            <option value="AED" {{ old('default_currency')=='AED'?'selected':'' }}>AED — Dirham émirien</option>
                            <option value="QAR" {{ old('default_currency')=='QAR'?'selected':'' }}>QAR — Riyal qatarien</option>
                            <option value="KWD" {{ old('default_currency')=='KWD'?'selected':'' }}>KWD — Dinar koweïtien</option>
                            <option value="BHD" {{ old('default_currency')=='BHD'?'selected':'' }}>BHD — Dinar bahreïni</option>
                            <option value="OMR" {{ old('default_currency')=='OMR'?'selected':'' }}>OMR — Rial omanais</option>
                            <option value="JOD" {{ old('default_currency')=='JOD'?'selected':'' }}>JOD — Dinar jordanien</option>
                            <option value="LBP" {{ old('default_currency')=='LBP'?'selected':'' }}>LBP — Livre libanaise</option>
                            <option value="CHF" {{ old('default_currency')=='CHF'?'selected':'' }}>CHF — Franc suisse</option>
                            <option value="CAD" {{ old('default_currency')=='CAD'?'selected':'' }}>CAD — Dollar canadien</option>
                            <option value="AUD" {{ old('default_currency')=='AUD'?'selected':'' }}>AUD — Dollar australien</option>
                            <option value="JPY" {{ old('default_currency')=='JPY'?'selected':'' }}>JPY — Yen japonais</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="timezone">Fuseau horaire</label>
                        <select id="timezone" name="timezone" class="input-field-plain">
                            <optgroup label="— Afrique —">
                                <option value="Africa/Tunis">Africa/Tunis (UTC+1)</option>
                                <option value="Africa/Algiers">Africa/Algiers (UTC+1)</option>
                                <option value="Africa/Casablanca">Africa/Casablanca (UTC+1)</option>
                                <option value="Africa/Cairo">Africa/Cairo (UTC+2)</option>
                            </optgroup>
                            <optgroup label="— Europe —">
                                <option value="Europe/Paris">Europe/Paris (UTC+1)</option>
                                <option value="Europe/London">Europe/London (UTC+0)</option>
                                <option value="Europe/Berlin">Europe/Berlin (UTC+1)</option>
                                <option value="Europe/Madrid">Europe/Madrid (UTC+1)</option>
                                <option value="Europe/Rome">Europe/Rome (UTC+1)</option>
                            </optgroup>
                            <optgroup label="— Universel —">
                                <option value="UTC">UTC (UTC+0)</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                {{-- Bouton --}}
                <button type="submit" class="btn-submit fade-up d8">
                    Créer mon hostel
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

        </div>
    </div>

<script>
/* ═══════════════════════════ COUNTRY AUTOCOMPLETE ═══════════════════════════ */
const COUNTRIES = [
  "Afghanistan","Afrique du Sud","Albanie","Algérie","Allemagne","Andorre",
  "Angola","Arabie Saoudite","Argentine","Arménie","Australie","Autriche",
  "Azerbaïdjan","Bahreïn","Bangladesh","Belgique","Bénin","Bolivie",
  "Bosnie-Herzégovine","Brésil","Bulgarie","Burkina Faso","Cameroun",
  "Canada","Chili","Chine","Chypre","Colombie","Congo (RDC)",
  "Corée du Sud","Côte d'Ivoire","Croatie","Cuba","Danemark","Égypte",
  "Émirats Arabes Unis","Équateur","Espagne","Estonie","États-Unis",
  "Éthiopie","Finlande","France","Gabon","Ghana","Grèce","Guatemala",
  "Guinée","Hongrie","Inde","Indonésie","Irak","Iran","Irlande",
  "Islande","Israël","Italie","Jamaïque","Japon","Jordanie","Kazakhstan",
  "Kenya","Kosovo","Koweït","Lettonie","Liban","Libye","Lituanie",
  "Luxembourg","Madagascar","Malaisie","Mali","Maroc","Maurice",
  "Mauritanie","Mexique","Moldova","Monaco","Mongolie","Mozambique",
  "Namibie","Nigéria","Norvège","Nouvelle-Zélande","Oman","Ouganda",
  "Pakistan","Palestine","Panama","Paraguay","Pays-Bas","Pérou",
  "Philippines","Pologne","Portugal","Qatar","Roumanie","Royaume-Uni",
  "Russie","Rwanda","Sénégal","Serbie","Singapour","Slovaquie",
  "Slovénie","Somalie","Soudan","Sri Lanka","Suède","Suisse","Syrie",
  "Tanzanie","Tchad","Thaïlande","Togo","Tunisie","Turquie","Ukraine",
  "Uruguay","Venezuela","Vietnam","Yémen","Zambie","Zimbabwe"
];

const countryInput = document.getElementById('country-input');
const countryValue = document.getElementById('country-value');
const countryList  = document.getElementById('country-list');

function renderCountryList(filter) {
    const q = filter.trim().toLowerCase();
    const matches = q
        ? COUNTRIES.filter(c => c.toLowerCase().startsWith(q)).concat(
            COUNTRIES.filter(c => !c.toLowerCase().startsWith(q) && c.toLowerCase().includes(q))
          )
        : COUNTRIES;
    if (!matches.length) { countryList.classList.remove('open'); return; }
    countryList.innerHTML = matches.slice(0,30).map(c =>
        `<div class="autocomplete-item" data-value="${c}">${c}</div>`
    ).join('');
    countryList.classList.add('open');
}

countryInput.addEventListener('input', () => renderCountryList(countryInput.value));
countryInput.addEventListener('focus', () => renderCountryList(countryInput.value));

countryList.addEventListener('mousedown', e => {
    const item = e.target.closest('.autocomplete-item');
    if (!item) return;
    countryInput.value = item.dataset.value;
    countryValue.value = item.dataset.value;
    countryList.classList.remove('open');
});

countryInput.addEventListener('keydown', e => {
    const items = countryList.querySelectorAll('.autocomplete-item');
    const active = countryList.querySelector('.autocomplete-item.active');
    let idx = Array.from(items).indexOf(active);
    if (e.key === 'ArrowDown') { e.preventDefault(); idx = Math.min(idx+1, items.length-1); }
    else if (e.key === 'ArrowUp') { e.preventDefault(); idx = Math.max(idx-1, 0); }
    else if (e.key === 'Enter' && active) {
        e.preventDefault();
        countryInput.value = active.dataset.value;
        countryValue.value = active.dataset.value;
        countryList.classList.remove('open'); return;
    } else if (e.key === 'Escape') { countryList.classList.remove('open'); return; }
    else return;
    items.forEach(i => i.classList.remove('active'));
    if (items[idx]) { items[idx].classList.add('active'); items[idx].scrollIntoView({block:'nearest'}); }
});

countryInput.addEventListener('blur', () => {
    setTimeout(() => countryList.classList.remove('open'), 150);
    if (!COUNTRIES.includes(countryInput.value)) countryInput.value = countryValue.value;
});

/* ═══════════════════════════ PHONE COUNTRY CODE ═══════════════════════════ */
const PHONE_CODES = [
  {name:"Algérie",code:"+213",flag:"🇩🇿"},{name:"Allemagne",code:"+49",flag:"🇩🇪"},
  {name:"Arabie Saoudite",code:"+966",flag:"🇸🇦"},{name:"Belgique",code:"+32",flag:"🇧🇪"},
  {name:"Canada",code:"+1",flag:"🇨🇦"},{name:"Égypte",code:"+20",flag:"🇪🇬"},
  {name:"Émirats Arabes Unis",code:"+971",flag:"🇦🇪"},{name:"Espagne",code:"+34",flag:"🇪🇸"},
  {name:"États-Unis",code:"+1",flag:"🇺🇸"},{name:"France",code:"+33",flag:"🇫🇷"},
  {name:"Italie",code:"+39",flag:"🇮🇹"},{name:"Japon",code:"+81",flag:"🇯🇵"},
  {name:"Maroc",code:"+212",flag:"🇲🇦"},{name:"Pays-Bas",code:"+31",flag:"🇳🇱"},
  {name:"Portugal",code:"+351",flag:"🇵🇹"},{name:"Royaume-Uni",code:"+44",flag:"🇬🇧"},
  {name:"Suisse",code:"+41",flag:"🇨🇭"},{name:"Tunisie",code:"+216",flag:"🇹🇳"},
  {name:"Turquie",code:"+90",flag:"🇹🇷"},
];

const phoneBtn      = document.getElementById('phone-code-btn');
const phoneFlag     = document.getElementById('phone-flag');
const phoneLabel    = document.getElementById('phone-code-label');
const phoneCodeVal  = document.getElementById('phone-code-value');
const phoneDropdown = document.getElementById('phone-dropdown');
const phoneSearch   = document.getElementById('phone-search');
const phoneList     = document.getElementById('phone-dropdown-list');

function renderPhoneList(filter) {
    const q = filter.trim().toLowerCase();
    const list = q ? PHONE_CODES.filter(c => c.name.toLowerCase().includes(q) || c.code.includes(q)) : PHONE_CODES;
    phoneList.innerHTML = list.map(c =>
        `<div class="phone-dropdown-item" data-code="${c.code}" data-flag="${c.flag}" data-name="${c.name}">
            <span>${c.flag} ${c.name}</span>
            <span class="code">${c.code}</span>
         </div>`
    ).join('');
}
renderPhoneList('');

phoneBtn.addEventListener('click', e => {
    e.stopPropagation();
    const open = phoneDropdown.classList.toggle('open');
    if (open) { phoneSearch.value=''; renderPhoneList(''); phoneSearch.focus(); }
});

phoneSearch.addEventListener('input', () => renderPhoneList(phoneSearch.value));

phoneList.addEventListener('mousedown', e => {
    const item = e.target.closest('.phone-dropdown-item');
    if (!item) return;
    phoneFlag.textContent  = item.dataset.flag;
    phoneLabel.textContent = item.dataset.code;
    phoneCodeVal.value     = item.dataset.code;
    phoneDropdown.classList.remove('open');
});

document.addEventListener('click', e => {
    if (!phoneBtn.closest('[style]').contains(e.target)) phoneDropdown.classList.remove('open');
});
</script>
</body>
</html>
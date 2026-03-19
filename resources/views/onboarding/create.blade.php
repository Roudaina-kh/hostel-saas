<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer votre hostel — HostelFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --white:     #FFFFFF;
            --beige-100: #FAF6F0;
            --beige-200: #F5EAE0;
            --beige-300: #EAD8C8;
            --navy-900:  #1A2B3C;
            --navy-700:  #1A4A6B;
            --teal-600:  #2C6E8A;
            --gray-400:  #8A9BB0;
            --gray-300:  #E8EEF2;
            --input-bg:  #F8FBFD;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, var(--white) 0%, var(--beige-100) 45%, var(--beige-200) 100%);
            position: relative;
            overflow-x: hidden;
            color: var(--navy-900);
            padding: 32px 20px;
        }

        /* ── Ambient blobs ─────────────────────────────────────── */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            animation: floatBlob 14s ease-in-out infinite alternate;
        }
        .blob-1 {
            width: 550px; height: 550px;
            background: rgba(255,255,255,0.9);
            top: -15%; left: -12%;
            animation-delay: 0s;
        }
        .blob-2 {
            width: 650px; height: 650px;
            background: var(--beige-300);
            bottom: -22%; right: -12%;
            opacity: 0.55;
            animation-delay: -7s;
        }
        @keyframes floatBlob {
            0%   { transform: translateY(0) scale(1);    }
            100% { transform: translateY(-28px) scale(1.06); }
        }

        /* ── Layout ────────────────────────────────────────────── */
        .page-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
            padding: 0;
        }

        /* ── Glass card ────────────────────────────────────────── */
        .card {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.75);
            border-radius: 32px;
            padding: 48px 44px 44px;
            box-shadow:
                0 24px 48px -12px rgba(0,0,0,0.07),
                0 0 0 1px rgba(255,255,255,0.6) inset;
            transition: box-shadow 0.4s ease;
        }
        .card:hover {
            box-shadow:
                0 32px 64px -12px rgba(0,0,0,0.12),
                0 0 0 1px rgba(255,255,255,0.6) inset;
        }

        /* ── Animations ────────────────────────────────────────── */
        .fade-up {
            opacity: 0;
            transform: translateY(18px);
            animation: fadeUp 0.75s cubic-bezier(0.16,1,0.3,1) forwards;
        }
        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }
        .d1  { animation-delay: 0ms;   }
        .d2  { animation-delay: 120ms; }
        .d3  { animation-delay: 200ms; }
        .d4  { animation-delay: 280ms; }
        .d5  { animation-delay: 360ms; }
        .d6  { animation-delay: 440ms; }
        .d7  { animation-delay: 520ms; }
        .d8  { animation-delay: 600ms; }

        /* ── Logo ──────────────────────────────────────────────── */
        .logo-ring {
            width: 96px; height: 96px;
            margin: 0 auto 28px;
            border-radius: 50%;
            background: var(--white);
            border: 4px solid rgba(255,255,255,0.9);
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            overflow: hidden;
            display: flex; align-items: center; justify-content: center;
            transition: transform 0.5s cubic-bezier(0.34,1.56,0.64,1);
        }
        .logo-ring:hover { transform: scale(1.1) rotate(-3deg); }
        .logo-ring img { width: 100%; height: 100%; object-fit: cover; }
        .logo-fallback {
            font-size: 26px; font-weight: 900;
            letter-spacing: -1px; color: var(--navy-700);
        }

        /* ── Header text ───────────────────────────────────────── */
        .header { text-align: center; margin-bottom: 36px; }
        .header h1 {
            font-size: 26px; font-weight: 800;
            letter-spacing: -0.5px; color: var(--navy-900); line-height: 1.2;
        }
        .header h1 span.accent {
            background: linear-gradient(135deg, var(--navy-700), var(--teal-600));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .header p {
            margin-top: 8px; font-size: 13.5px;
            font-weight: 500; color: var(--gray-400);
        }

        /* ── Error banner ──────────────────────────────────────── */
        .error-banner {
            background: #FEF2F2;
            border-left: 4px solid #EF4444;
            border-radius: 0 12px 12px 0;
            padding: 14px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #DC2626;
        }
        .error-banner p { font-weight: 600; }

        /* ── Form ──────────────────────────────────────────────── */
        .form-group { margin-bottom: 18px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px; }
        .form-label {
            display: block; font-size: 13px; font-weight: 700;
            color: var(--navy-900); margin-bottom: 8px; padding-left: 4px;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; top: 50%; left: 16px;
            transform: translateY(-50%);
            width: 18px; height: 18px;
            color: #b0bec8; pointer-events: none;
            transition: color 0.25s;
        }
        .input-wrap:focus-within .input-icon { color: var(--teal-600); }
        .input-field {
            width: 100%;
            padding: 13px 16px 13px 44px;
            font-size: 14px; font-weight: 500; font-family: inherit;
            color: var(--navy-900); background: var(--input-bg);
            border: 1.5px solid var(--gray-300);
            border-radius: 16px; outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        }
        .input-field-plain {
            width: 100%;
            padding: 13px 16px;
            font-size: 14px; font-weight: 500; font-family: inherit;
            color: var(--navy-900); background: var(--input-bg);
            border: 1.5px solid var(--gray-300);
            border-radius: 16px; outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
            appearance: none; -webkit-appearance: none;
        }
        .input-field::placeholder,
        .input-field-plain::placeholder { color: #b0bec8; font-weight: 400; }
        .input-field:focus,
        .input-field-plain:focus {
            border-color: var(--teal-600);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(44,110,138,0.09);
        }
        select.input-field-plain {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%238A9BB0' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 16px;
            padding-right: 40px;
        }

        /* ── Submit button ─────────────────────────────────────── */
        .btn-submit {
            width: 100%; padding: 15px 20px;
            font-size: 14px; font-weight: 700; font-family: inherit;
            letter-spacing: 0.3px; color: var(--white);
            background: linear-gradient(135deg, var(--navy-700) 0%, var(--teal-600) 100%);
            border: none; border-radius: 16px; cursor: pointer;
            position: relative; overflow: hidden;
            box-shadow: 0 6px 20px rgba(44,110,138,0.28);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 8px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(44,110,138,0.38);
        }
        .btn-submit:active { transform: translateY(0); }
        /* Shimmer effect */
        .btn-submit::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.12) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }
        .btn-submit:hover::after { transform: translateX(100%); }
        .btn-submit svg {
            width: 16px; height: 16px;
            transition: transform 0.25s;
        }
        .btn-submit:hover svg { transform: translateX(4px); }

        /* ── Country autocomplete ─────────────────────────────── */
        .autocomplete-wrap { position: relative; width: 100%; }
        .autocomplete-list {
            position: absolute; top: calc(100% + 6px); left: 0; right: 0; z-index: 1000;
            background: var(--white);
            border: 1.5px solid var(--teal-600);
            border-radius: 14px;
            max-height: 200px; overflow-y: auto;
            box-shadow: 0 8px 24px rgba(0,0,0,0.10);
            display: none;
        }
        .autocomplete-list.open { display: block; }
        .autocomplete-item {
            padding: 10px 16px;
            font-size: 13.5px; font-weight: 500;
            color: var(--navy-900); cursor: pointer;
            transition: background 0.15s;
        }
        .autocomplete-item:hover,
        .autocomplete-item.active { background: var(--beige-100); }
        .autocomplete-list::-webkit-scrollbar { width: 5px; }
        .autocomplete-list::-webkit-scrollbar-thumb { background: var(--beige-300); border-radius: 3px; }

        /* ── Phone code selector ─────────────────────────────────── */
        .phone-wrap { display: flex; gap: 0; }
        .phone-code-btn {
            flex-shrink: 0;
            padding: 0 12px;
            background: var(--input-bg);
            border: 1.5px solid var(--gray-300);
            border-right: none;
            border-radius: 16px 0 0 16px;
            font-size: 13px; font-weight: 600;
            color: var(--navy-900); cursor: pointer;
            display: flex; align-items: center; gap: 6px;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
            white-space: nowrap;
        }
        .phone-code-btn:focus { outline: none; }
        .phone-wrap:focus-within .phone-code-btn {
            border-color: var(--teal-600);
            background: var(--white);
        }
        .phone-number-input {
            flex: 1;
            padding: 13px 16px;
            font-size: 14px; font-weight: 500; font-family: inherit;
            color: var(--navy-900); background: var(--input-bg);
            border: 1.5px solid var(--gray-300);
            border-left: none;
            border-radius: 0 16px 16px 0; outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        }
        .phone-number-input::placeholder { color: #b0bec8; font-weight: 400; }
        .phone-number-input:focus {
            border-color: var(--teal-600);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(44,110,138,0.09);
        }
        .phone-dropdown {
            position: absolute; top: calc(100% + 6px); left: 0; right: 0; z-index: 1100;
            width: 100%; min-width: 240px;
            background: var(--white);
            border: 1.5px solid var(--teal-600);
            border-radius: 14px;
            max-height: 220px; overflow-y: auto;
            box-shadow: 0 8px 24px rgba(0,0,0,0.10);
            display: none;
        }
        .phone-dropdown.open { display: block; }
        .phone-dropdown-search {
            padding: 10px 14px;
            border-bottom: 1px solid var(--gray-300);
            position: sticky; top: 0; background: var(--white);
        }
        .phone-dropdown-search input {
            width: 100%; border: none; outline: none;
            font-size: 13px; font-family: inherit;
            color: var(--navy-900); background: transparent;
        }
        .phone-dropdown-item {
            padding: 9px 14px;
            font-size: 13px; font-weight: 500;
            color: var(--navy-900); cursor: pointer;
            display: flex; justify-content: space-between;
            transition: background 0.15s;
        }
        .phone-dropdown-item:hover { background: var(--beige-100); }
        .phone-dropdown-item span.code { color: var(--teal-600); font-weight: 700; }
        .phone-dropdown::-webkit-scrollbar { width: 5px; }
        .phone-dropdown::-webkit-scrollbar-thumb { background: var(--beige-300); border-radius: 3px; }

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
                    <img src="{{ asset('images/logo.jpg') }}"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                         alt="Logo HostelFlow">
                    <span class="logo-fallback" style="display:none;">HF</span>
                </div>
            </div>

            {{-- Heading --}}
            <div class="header fade-up d2">
                <h1>Créez votre <span class="accent">hostel</span></h1>
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
                            <option value="CNY" {{ old('default_currency')=='CNY'?'selected':'' }}>CNY — Yuan chinois</option>
                            <option value="INR" {{ old('default_currency')=='INR'?'selected':'' }}>INR — Roupie indienne</option>
                            <option value="TRY" {{ old('default_currency')=='TRY'?'selected':'' }}>TRY — Livre turque</option>
                            <option value="BRL" {{ old('default_currency')=='BRL'?'selected':'' }}>BRL — Réal brésilien</option>
                            <option value="MXN" {{ old('default_currency')=='MXN'?'selected':'' }}>MXN — Peso mexicain</option>
                            <option value="ZAR" {{ old('default_currency')=='ZAR'?'selected':'' }}>ZAR — Rand sud-africain</option>
                            <option value="NGN" {{ old('default_currency')=='NGN'?'selected':'' }}>NGN — Naira nigérian</option>
                            <option value="KES" {{ old('default_currency')=='KES'?'selected':'' }}>KES — Shilling kényan</option>
                            <option value="GHS" {{ old('default_currency')=='GHS'?'selected':'' }}>GHS — Cedi ghanéen</option>
                            <option value="XOF" {{ old('default_currency')=='XOF'?'selected':'' }}>XOF — Franc CFA BCEAO</option>
                            <option value="XAF" {{ old('default_currency')=='XAF'?'selected':'' }}>XAF — Franc CFA CEMAC</option>
                            <option value="SEK" {{ old('default_currency')=='SEK'?'selected':'' }}>SEK — Couronne suédoise</option>
                            <option value="NOK" {{ old('default_currency')=='NOK'?'selected':'' }}>NOK — Couronne norvégienne</option>
                            <option value="DKK" {{ old('default_currency')=='DKK'?'selected':'' }}>DKK — Couronne danoise</option>
                            <option value="PLN" {{ old('default_currency')=='PLN'?'selected':'' }}>PLN — Zloty polonais</option>
                            <option value="CZK" {{ old('default_currency')=='CZK'?'selected':'' }}>CZK — Couronne tchèque</option>
                            <option value="HUF" {{ old('default_currency')=='HUF'?'selected':'' }}>HUF — Forint hongrois</option>
                            <option value="RON" {{ old('default_currency')=='RON'?'selected':'' }}>RON — Leu roumain</option>
                            <option value="RUB" {{ old('default_currency')=='RUB'?'selected':'' }}>RUB — Rouble russe</option>
                            <option value="THB" {{ old('default_currency')=='THB'?'selected':'' }}>THB — Baht thaïlandais</option>
                            <option value="SGD" {{ old('default_currency')=='SGD'?'selected':'' }}>SGD — Dollar singapourien</option>
                            <option value="HKD" {{ old('default_currency')=='HKD'?'selected':'' }}>HKD — Dollar de Hong Kong</option>
                            <option value="NZD" {{ old('default_currency')=='NZD'?'selected':'' }}>NZD — Dollar néo-zélandais</option>
                            <option value="IDR" {{ old('default_currency')=='IDR'?'selected':'' }}>IDR — Roupie indonésienne</option>
                            <option value="PKR" {{ old('default_currency')=='PKR'?'selected':'' }}>PKR — Roupie pakistanaise</option>
                            <option value="MYR" {{ old('default_currency')=='MYR'?'selected':'' }}>MYR — Ringgit malaisien</option>
                            <option value="PHP" {{ old('default_currency')=='PHP'?'selected':'' }}>PHP — Peso philippin</option>
                            <option value="VND" {{ old('default_currency')=='VND'?'selected':'' }}>VND — Dong vietnamien</option>
                            <option value="KRW" {{ old('default_currency')=='KRW'?'selected':'' }}>KRW — Won coréen</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="timezone">Fuseau horaire</label>
                        <select id="timezone" name="timezone" class="input-field-plain">
                            <optgroup label="— Afrique —">
                                <option value="Africa/Tunis">Africa/Tunis (UTC+1)</option>
                                <option value="Africa/Algiers">Africa/Algiers (UTC+1)</option>
                                <option value="Africa/Casablanca">Africa/Casablanca (UTC+1)</option>
                                <option value="Africa/Tripoli">Africa/Tripoli (UTC+2)</option>
                                <option value="Africa/Cairo">Africa/Cairo (UTC+2)</option>
                                <option value="Africa/Lagos">Africa/Lagos (UTC+1)</option>
                                <option value="Africa/Nairobi">Africa/Nairobi (UTC+3)</option>
                                <option value="Africa/Johannesburg">Africa/Johannesburg (UTC+2)</option>
                                <option value="Africa/Abidjan">Africa/Abidjan (UTC+0)</option>
                                <option value="Africa/Dakar">Africa/Dakar (UTC+0)</option>
                                <option value="Africa/Accra">Africa/Accra (UTC+0)</option>
                                <option value="Africa/Khartoum">Africa/Khartoum (UTC+3)</option>
                            </optgroup>
                            <optgroup label="— Europe —">
                                <option value="Europe/Paris">Europe/Paris (UTC+1)</option>
                                <option value="Europe/London">Europe/London (UTC+0)</option>
                                <option value="Europe/Berlin">Europe/Berlin (UTC+1)</option>
                                <option value="Europe/Madrid">Europe/Madrid (UTC+1)</option>
                                <option value="Europe/Rome">Europe/Rome (UTC+1)</option>
                                <option value="Europe/Amsterdam">Europe/Amsterdam (UTC+1)</option>
                                <option value="Europe/Brussels">Europe/Brussels (UTC+1)</option>
                                <option value="Europe/Zurich">Europe/Zurich (UTC+1)</option>
                                <option value="Europe/Lisbon">Europe/Lisbon (UTC+0)</option>
                                <option value="Europe/Stockholm">Europe/Stockholm (UTC+1)</option>
                                <option value="Europe/Oslo">Europe/Oslo (UTC+1)</option>
                                <option value="Europe/Copenhagen">Europe/Copenhagen (UTC+1)</option>
                                <option value="Europe/Warsaw">Europe/Warsaw (UTC+1)</option>
                                <option value="Europe/Prague">Europe/Prague (UTC+1)</option>
                                <option value="Europe/Budapest">Europe/Budapest (UTC+1)</option>
                                <option value="Europe/Bucharest">Europe/Bucharest (UTC+2)</option>
                                <option value="Europe/Helsinki">Europe/Helsinki (UTC+2)</option>
                                <option value="Europe/Athens">Europe/Athens (UTC+2)</option>
                                <option value="Europe/Istanbul">Europe/Istanbul (UTC+3)</option>
                                <option value="Europe/Moscow">Europe/Moscow (UTC+3)</option>
                                <option value="Europe/Kiev">Europe/Kiev (UTC+2)</option>
                            </optgroup>
                            <optgroup label="— Moyen-Orient —">
                                <option value="Asia/Riyadh">Asia/Riyadh (UTC+3)</option>
                                <option value="Asia/Dubai">Asia/Dubai (UTC+4)</option>
                                <option value="Asia/Kuwait">Asia/Kuwait (UTC+3)</option>
                                <option value="Asia/Qatar">Asia/Qatar (UTC+3)</option>
                                <option value="Asia/Beirut">Asia/Beirut (UTC+2)</option>
                                <option value="Asia/Amman">Asia/Amman (UTC+2)</option>
                                <option value="Asia/Baghdad">Asia/Baghdad (UTC+3)</option>
                                <option value="Asia/Tehran">Asia/Tehran (UTC+3:30)</option>
                            </optgroup>
                            <optgroup label="— Asie —">
                                <option value="Asia/Kolkata">Asia/Kolkata (UTC+5:30)</option>
                                <option value="Asia/Karachi">Asia/Karachi (UTC+5)</option>
                                <option value="Asia/Dhaka">Asia/Dhaka (UTC+6)</option>
                                <option value="Asia/Colombo">Asia/Colombo (UTC+5:30)</option>
                                <option value="Asia/Bangkok">Asia/Bangkok (UTC+7)</option>
                                <option value="Asia/Jakarta">Asia/Jakarta (UTC+7)</option>
                                <option value="Asia/Singapore">Asia/Singapore (UTC+8)</option>
                                <option value="Asia/Kuala_Lumpur">Asia/Kuala_Lumpur (UTC+8)</option>
                                <option value="Asia/Manila">Asia/Manila (UTC+8)</option>
                                <option value="Asia/Shanghai">Asia/Shanghai (UTC+8)</option>
                                <option value="Asia/Tokyo">Asia/Tokyo (UTC+9)</option>
                                <option value="Asia/Seoul">Asia/Seoul (UTC+9)</option>
                                <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh (UTC+7)</option>
                                <option value="Asia/Almaty">Asia/Almaty (UTC+6)</option>
                            </optgroup>
                            <optgroup label="— Amériques —">
                                <option value="America/New_York">America/New_York (UTC-5)</option>
                                <option value="America/Chicago">America/Chicago (UTC-6)</option>
                                <option value="America/Denver">America/Denver (UTC-7)</option>
                                <option value="America/Los_Angeles">America/Los_Angeles (UTC-8)</option>
                                <option value="America/Toronto">America/Toronto (UTC-5)</option>
                                <option value="America/Vancouver">America/Vancouver (UTC-8)</option>
                                <option value="America/Sao_Paulo">America/Sao_Paulo (UTC-3)</option>
                                <option value="America/Mexico_City">America/Mexico_City (UTC-6)</option>
                                <option value="America/Buenos_Aires">America/Buenos_Aires (UTC-3)</option>
                                <option value="America/Bogota">America/Bogota (UTC-5)</option>
                                <option value="America/Lima">America/Lima (UTC-5)</option>
                                <option value="America/Santiago">America/Santiago (UTC-4)</option>
                                <option value="America/Caracas">America/Caracas (UTC-4)</option>
                                <option value="America/Havana">America/Havana (UTC-5)</option>
                            </optgroup>
                            <optgroup label="— Océanie —">
                                <option value="Australia/Sydney">Australia/Sydney (UTC+11)</option>
                                <option value="Australia/Melbourne">Australia/Melbourne (UTC+11)</option>
                                <option value="Australia/Brisbane">Australia/Brisbane (UTC+10)</option>
                                <option value="Australia/Perth">Australia/Perth (UTC+8)</option>
                                <option value="Pacific/Auckland">Pacific/Auckland (UTC+13)</option>
                                <option value="Pacific/Fiji">Pacific/Fiji (UTC+12)</option>
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
  {name:"Afghanistan",code:"+93",flag:"🇦🇫"},{name:"Afrique du Sud",code:"+27",flag:"🇿🇦"},
  {name:"Algérie",code:"+213",flag:"🇩🇿"},{name:"Allemagne",code:"+49",flag:"🇩🇪"},
  {name:"Arabie Saoudite",code:"+966",flag:"🇸🇦"},{name:"Argentine",code:"+54",flag:"🇦🇷"},
  {name:"Australie",code:"+61",flag:"🇦🇺"},{name:"Autriche",code:"+43",flag:"🇦🇹"},
  {name:"Bahreïn",code:"+973",flag:"🇧🇭"},{name:"Belgique",code:"+32",flag:"🇧🇪"},
  {name:"Brésil",code:"+55",flag:"🇧🇷"},{name:"Canada",code:"+1",flag:"🇨🇦"},
  {name:"Chili",code:"+56",flag:"🇨🇱"},{name:"Chine",code:"+86",flag:"🇨🇳"},
  {name:"Colombie",code:"+57",flag:"🇨🇴"},{name:"Côte d'Ivoire",code:"+225",flag:"🇨🇮"},
  {name:"Danemark",code:"+45",flag:"🇩🇰"},{name:"Égypte",code:"+20",flag:"🇪🇬"},
  {name:"Émirats Arabes Unis",code:"+971",flag:"🇦🇪"},{name:"Espagne",code:"+34",flag:"🇪🇸"},
  {name:"États-Unis",code:"+1",flag:"🇺🇸"},{name:"Finlande",code:"+358",flag:"🇫🇮"},
  {name:"France",code:"+33",flag:"🇫🇷"},{name:"Ghana",code:"+233",flag:"🇬🇭"},
  {name:"Grèce",code:"+30",flag:"🇬🇷"},{name:"Inde",code:"+91",flag:"🇮🇳"},
  {name:"Indonésie",code:"+62",flag:"🇮🇩"},{name:"Irak",code:"+964",flag:"🇮🇶"},
  {name:"Iran",code:"+98",flag:"🇮🇷"},{name:"Irlande",code:"+353",flag:"🇮🇪"},
  {name:"Israël",code:"+972",flag:"🇮🇱"},{name:"Italie",code:"+39",flag:"🇮🇹"},
  {name:"Japon",code:"+81",flag:"🇯🇵"},{name:"Jordanie",code:"+962",flag:"🇯🇴"},
  {name:"Kenya",code:"+254",flag:"🇰🇪"},{name:"Koweït",code:"+965",flag:"🇰🇼"},
  {name:"Liban",code:"+961",flag:"🇱🇧"},{name:"Libye",code:"+218",flag:"🇱🇾"},
  {name:"Luxembourg",code:"+352",flag:"🇱🇺"},{name:"Malaisie",code:"+60",flag:"🇲🇾"},
  {name:"Maroc",code:"+212",flag:"🇲🇦"},{name:"Mauritanie",code:"+222",flag:"🇲🇷"},
  {name:"Mexique",code:"+52",flag:"🇲🇽"},{name:"Nigéria",code:"+234",flag:"🇳🇬"},
  {name:"Norvège",code:"+47",flag:"🇳🇴"},{name:"Nouvelle-Zélande",code:"+64",flag:"🇳🇿"},
  {name:"Oman",code:"+968",flag:"🇴🇲"},{name:"Pakistan",code:"+92",flag:"🇵🇰"},
  {name:"Palestine",code:"+970",flag:"🇵🇸"},{name:"Pays-Bas",code:"+31",flag:"🇳🇱"},
  {name:"Philippines",code:"+63",flag:"🇵🇭"},{name:"Pologne",code:"+48",flag:"🇵🇱"},
  {name:"Portugal",code:"+351",flag:"🇵🇹"},{name:"Qatar",code:"+974",flag:"🇶🇦"},
  {name:"Roumanie",code:"+40",flag:"🇷🇴"},{name:"Royaume-Uni",code:"+44",flag:"🇬🇧"},
  {name:"Russie",code:"+7",flag:"🇷🇺"},{name:"Sénégal",code:"+221",flag:"🇸🇳"},
  {name:"Singapour",code:"+65",flag:"🇸🇬"},{name:"Suède",code:"+46",flag:"🇸🇪"},
  {name:"Suisse",code:"+41",flag:"🇨🇭"},{name:"Thaïlande",code:"+66",flag:"🇹🇭"},
  {name:"Tunisie",code:"+216",flag:"🇹🇳"},{name:"Turquie",code:"+90",flag:"🇹🇷"},
  {name:"Ukraine",code:"+380",flag:"🇺🇦"},{name:"Vietnam",code:"+84",flag:"🇻🇳"},
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
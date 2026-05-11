<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Équipe — HostelFlow</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            overflow: hidden;
            color: var(--ink);
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(110px);
            pointer-events: none;
            animation: floatBlob 14s ease-in-out infinite alternate;
        }
        .blob-1 { width: 550px; height: 550px; background: rgba(27, 107, 107, 0.40); top: -15%; left: -12%; }
        .blob-2 { width: 650px; height: 650px; background: rgba(200, 96, 42, 0.20); bottom: -22%; right: -12%; animation-delay: -7s; }
        .blob-3 { width: 300px; height: 300px; background: rgba(254, 252, 249, 0.05); top: 40%; right: 25%; animation-delay: -3s; }
        @keyframes floatBlob {
            0%   { transform: translateY(0) scale(1); }
            100% { transform: translateY(-28px) scale(1.06); }
        }

        .page-wrapper { position: relative; z-index: 10; width: 100%; max-width: 420px; padding: 24px 20px; }

        .role-badges {
            display: flex; justify-content: center; gap: 8px;
            margin-bottom: 24px;
        }
        .role-chip {
            font-size: 11px; font-weight: 600;
            padding: 5px 14px; border-radius: 20px;
            background: rgba(254, 252, 249, 0.10);
            color: rgba(254, 252, 249, 0.80);
            border: 1px solid rgba(254, 252, 249, 0.15);
            letter-spacing: 0.04em;
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

        .fade-up { opacity: 0; transform: translateY(18px); animation: fadeUp 0.75s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
        .d1 { animation-delay: 0ms; }
        .d2 { animation-delay: 120ms; }
        .d3 { animation-delay: 220ms; }
        .d4 { animation-delay: 320ms; }
        .d5 { animation-delay: 420ms; }

        .icon-circle {
            width: 72px; height: 72px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--teal), var(--teal2));
            display: flex; align-items: center; justify-content: center;
            font-size: 30px;
            box-shadow: 0 8px 24px rgba(27, 107, 107, 0.35);
            border: 3px solid rgba(254, 252, 249, 0.5);
        }

        .header { text-align: center; margin-bottom: 28px; }
        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 24px; font-weight: 700;
            color: var(--ink); line-height: 1.2;
        }
        .header h1 em {
            font-style: italic;
            color: var(--teal);
        }
        .header p {
            margin-top: 8px; font-size: 13px;
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
        .form-label {
            display: block; font-size: 12px; font-weight: 700;
            color: var(--gray); margin-bottom: 8px; padding-left: 4px;
            text-transform: uppercase; letter-spacing: 0.06em;
        }

        .input-field {
            width: 100%;
            padding: 13px 16px;
            font-size: 14px; font-weight: 500; font-family: inherit;
            color: var(--ink); background: var(--sand);
            border: 1.5px solid var(--border);
            border-radius: 14px; outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        }
        .input-field::placeholder { color: var(--lgray); font-weight: 400; }
        .input-field:focus {
            border-color: var(--teal);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(27, 107, 107, 0.12);
        }

        .row-opts {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 22px;
        }
        .remember-label {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; font-weight: 500;
            color: var(--gray); cursor: pointer;
        }
        .remember-label input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--teal);
            cursor: pointer;
        }

        .btn-submit {
            width: 100%; padding: 14px 20px;
            font-size: 14px; font-weight: 700; font-family: inherit;
            letter-spacing: 0.3px; color: #fff;
            background: var(--teal);
            border: none; border-radius: 14px; cursor: pointer;
            position: relative; overflow: hidden;
            box-shadow: 0 8px 24px rgba(27, 107, 107, 0.32);
            transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-submit:hover {
            background: var(--teal2);
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(27, 107, 107, 0.42);
        }
        .btn-submit::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(120deg, transparent 30%, rgba(255, 255, 255, 0.18) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }
        .btn-submit:hover::after { transform: translateX(100%); }

        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 26px 0 18px;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px;
            background: linear-gradient(to right, transparent, var(--border), transparent);
        }
        .divider span {
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.10em;
            color: var(--lgray);
        }

        .footer-links {
            display: flex; justify-content: center; gap: 16px;
            font-size: 12px; color: var(--gray);
        }
        .footer-links a {
            font-weight: 600; color: var(--terra);
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer-links a:hover { color: var(--terra2); text-decoration: underline; }

        .footer-text {
            text-align: center; margin-top: 20px;
            font-size: 11.5px; color: rgba(254, 252, 249, 0.45);
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="blob blob-3"></div>

<div class="page-wrapper">

    <div class="role-badges fade-up d1">
        <span class="role-chip">Manager</span>
        <span class="role-chip">Staff</span>
        <span class="role-chip">Financier</span>
    </div>

    <div class="card">

        {{-- Icône --}}
        <div class="fade-up d2" style="text-align:center;">
            <div class="icon-circle">👔</div>
        </div>

        {{-- Heading --}}
        <div class="header fade-up d2">
            <h1>Espace <em>équipe</em></h1>
            <p>Connectez-vous pour accéder à votre dashboard</p>
        </div>

        {{-- Erreurs --}}
        @if($errors->any())
        <div class="error-banner fade-up d2">
            @foreach($errors->all() as $e)<p>→ {{ $e }}</p>@endforeach
        </div>
        @endif

        {{-- Formulaire (route corrigée : user.login.store) --}}
        <form method="POST" action="{{ route('user.login.store') }}">
            @csrf

            <div class="form-group fade-up d3">
                <label class="form-label" for="email">Adresse email</label>
                <input id="email" class="input-field" type="email"
                       name="email" value="{{ old('email') }}"
                       placeholder="vous@hostelflow.com"
                       required autocomplete="email">
            </div>

            <div class="form-group fade-up d4">
                <label class="form-label" for="password">Mot de passe</label>
                <input id="password" class="input-field" type="password"
                       name="password" placeholder="••••••••"
                       required autocomplete="current-password">
            </div>

            <div class="row-opts fade-up d5">
                <label class="remember-label">
                    <input type="checkbox" name="remember">
                    Se souvenir de moi
                </label>
            </div>

            <button type="submit" class="btn-submit fade-up d5">
                Accéder à mon dashboard
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        <div class="divider"><span>Autres accès</span></div>

        <div class="footer-links">
            Vous êtes propriétaire ?
            <a href="{{ route('owner.login') }}">Connexion propriétaire</a>
        </div>
        <div class="footer-links" style="margin-top:8px;">
            Administration ?
            <a href="{{ route('super-admin.login') }}">Super Admin</a>
        </div>

    </div>

    <p class="footer-text">HostelFlow © {{ date('Y') }}</p>

</div>

</body>
</html>
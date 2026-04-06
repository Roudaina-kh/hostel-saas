<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Propriétaire — HostelFlow</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <div class="mt-6 text-center text-xs text-slate-400 space-y-1">
    <p>
        Staff / Manager / Financial → 
        <a href="{{ route('user.login') }}" class="text-blue-500 font-bold hover:underline">
            Connexion équipe
        </a>
    </p>
    <p>
        Administration → 
        <a href="{{ route('super-admin.login') }}" class="text-blue-500 font-bold hover:underline">
            Super Admin
        </a>
    </p>
</div>

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, var(--white) 0%, var(--beige-100) 45%, var(--beige-200) 100%);
            position: relative;
            overflow: hidden;
            color: var(--navy-900);
        }

        /* ── Ambient blobs ─────────────────────────────────────── */
        .blob {
            position: absolute;
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
            max-width: 440px;
            padding: 24px 20px;
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
        .d3  { animation-delay: 220ms; }
        .d4  { animation-delay: 320ms; }
        .d5  { animation-delay: 420ms; }
        .d6  { animation-delay: 500ms; }

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
            font-size: 28px; font-weight: 800;
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
            padding: 14px 16px 14px 44px;
            font-size: 14px; font-weight: 500; font-family: inherit;
            color: var(--navy-900); background: var(--input-bg);
            border: 1.5px solid var(--gray-300);
            border-radius: 16px; outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        }
        .input-field::placeholder { color: #b0bec8; font-weight: 400; }
        .input-field:focus {
            border-color: var(--teal-600);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(44,110,138,0.09);
        }

        /* ── Options row ───────────────────────────────────────── */
        .row-opts {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px;
        }
        .remember-label {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; font-weight: 500; color: #5A6B7A;
            cursor: pointer; user-select: none;
        }
        .remember-label input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--teal-600); cursor: pointer;
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

        /* ── Divider ───────────────────────────────────────────── */
        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 28px 0 24px;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px;
            background: linear-gradient(to right, transparent, var(--gray-300), transparent);
        }
        .divider span {
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: #b0bec8;
        }

        /* ── Footer ────────────────────────────────────────────── */
        .footer-text {
            text-align: center; font-size: 13px;
            font-weight: 500; color: #5A6B7A; margin-top: 0;
        }
        .footer-text a {
            font-weight: 700; color: var(--teal-600);
            text-decoration: none; margin-left: 4px;
            transition: color 0.2s;
        }
        .footer-text a:hover { color: var(--navy-700); text-decoration: underline; }

        @media (max-width: 480px) {
            .card { padding: 36px 24px 32px; }
            .header h1 { font-size: 24px; }
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
                <h1>Bienvenue, <span class="accent">propriétaire</span></h1>
                <p>Entrez vos identifiants pour accéder à votre espace de gestion</p>
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
            <form method="POST" action="{{ route('owner.login.store') }}">
                @csrf

                {{-- Email --}}
                <div class="form-group fade-up d3">
                    <label class="form-label" for="email">Adresse email</label>
                    <div class="input-wrap">
                        <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                        <input id="email" class="input-field" type="email"
                               name="email" value="{{ old('email') }}"
                               placeholder="vous@hostelflow.com"
                               required autocomplete="email">
                    </div>
                </div>

                {{-- Mot de passe --}}
                <div class="form-group fade-up d4">
                    <label class="form-label" for="password">Mot de passe</label>
                    <div class="input-wrap">
                        <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input id="password" class="input-field" type="password"
                               name="password" placeholder="••••••••"
                               required autocomplete="current-password">
                    </div>
                </div>

                {{-- Options --}}
                <div class="row-opts fade-up d5">
                    <label class="remember-label">
                        <input type="checkbox" name="remember">
                        Se souvenir de moi
                    </label>
                </div>

                {{-- Bouton --}}
                <button type="submit" class="btn-submit fade-up d5">
                    Se connecter
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>
            <div class="mt-4 text-center text-xs text-slate-400">
    Vous êtes staff / manager ? 
    <a href="{{ route('user.login') }}" class="text-blue-500 font-bold hover:underline">
        Connexion équipe
    </a>
</div>

            <div class="divider fade-up d6"><span>ou</span></div>

            <p class="footer-text fade-up d6">
                Pas encore de compte ?
                <a href="{{ route('register') }}">Créer un compte gratuit</a>
            </p>

        </div>
    </div>

</body>
</html>
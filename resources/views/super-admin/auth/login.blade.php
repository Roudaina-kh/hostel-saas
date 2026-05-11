<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration Centrale — HostelFlow</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --terra:   #C8602A;
            --teal:    #1B6B6B;
            --teal-glow: #4A9A9A;
            --sand:    #F5EFE6;
            --night:   #1C1C24;
            --white:   #FEFCF9;
            --cream:   #F5C896;
            --sage:    #7AB592;
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
            color: var(--white);
            padding: 2rem 1rem;
        }

        /* ── Orbes ambiantes ── */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(110px);
            pointer-events: none;
            animation: floatBlob 16s ease-in-out infinite alternate;
            z-index: 2;
        }
        .blob-1 { width: 600px; height: 600px; background: rgba(122, 181, 146, 0.20); top: -15%; left: -10%; }
        .blob-2 { width: 500px; height: 500px; background: rgba(200, 96, 42, 0.20); bottom: -15%; right: -10%; animation-delay: -8s; }
        .blob-3 { width: 320px; height: 320px; background: rgba(254, 252, 249, 0.04); top: 50%; left: 30%; animation-delay: -4s; }
        @keyframes floatBlob {
            0%   { transform: translate(0,0) scale(1); }
            100% { transform: translate(20px, -20px) scale(1.08); }
        }

        /* ── ✨ Watermark "Hostel...flow" ── */
        .bg-watermark {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-weight: 700;
            font-size: 11rem;
            color: rgba(200, 96, 42, 0.07);
            letter-spacing: -4px;
            line-height: 1;
            pointer-events: none;
            user-select: none;
            z-index: 1;
            white-space: nowrap;
        }
        .bg-watermark-left  { left: 4%; }
        .bg-watermark-right { right: 4%; }

        @media (max-width: 1400px) {
            .bg-watermark { font-size: 8.5rem; }
        }
        @media (max-width: 1100px) {
            .bg-watermark { font-size: 6.5rem; }
            .bg-watermark-left  { left: 2%; }
            .bg-watermark-right { right: 2%; }
        }
        @media (max-width: 900px) {
            .bg-watermark { display: none; }
        }

        .page-wrapper { position: relative; z-index: 10; width: 100%; max-width: 460px; }

        .header-block { text-align: center; margin-bottom: 28px; }

        .logo-circle {
            width: 96px;
            height: 96px;
            margin: 0 auto 24px;
            border-radius: 50%;
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow:
                0 12px 40px rgba(122, 181, 146, 0.40),
                0 0 0 1px rgba(254, 252, 249, 0.5);
            animation: logo-float 4s ease-in-out infinite;
        }
        @keyframes logo-float {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-6px); }
        }
        .logo-circle::before {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 1px solid rgba(122, 181, 146, 0.35);
            animation: logo-pulse 2.5s ease-in-out infinite;
        }
        @keyframes logo-pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50%      { transform: scale(1.08); opacity: 0.5; }
        }
        .logo-circle img { width: 64px; height: 64px; object-fit: contain; border-radius: 16px; }
        .logo-fallback {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 28px;
            color: var(--teal);
        }

        .header-block h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--white);
            line-height: 1.15;
            letter-spacing: -0.5px;
        }
        .header-block h1 em {
            font-style: italic;
            color: var(--sage);
            font-weight: 600;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            padding: 7px 18px;
            border-radius: 99px;
            background: rgba(122, 181, 146, 0.20);
            border: 1px solid rgba(122, 181, 146, 0.40);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.14em;
            color: var(--sage);
            text-transform: uppercase;
            backdrop-filter: blur(8px);
        }
        .role-badge-icon { font-size: 14px; }

        .card {
            background: rgba(254, 252, 249, 0.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(254, 252, 249, 0.12);
            border-radius: 24px;
            padding: 36px 32px;
            box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.4);
        }

        .fade-up { opacity: 0; transform: translateY(18px); animation: fadeUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
        .d1 { animation-delay: 0ms; }
        .d2 { animation-delay: 100ms; }
        .d3 { animation-delay: 200ms; }
        .d4 { animation-delay: 300ms; }
        .d5 { animation-delay: 400ms; }
        .d6 { animation-delay: 500ms; }

        .error-banner {
            background: rgba(168, 78, 32, 0.18);
            border: 1px solid rgba(168, 78, 32, 0.35);
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 18px;
            font-size: 13px;
            color: #FFB8A0;
            font-weight: 500;
        }

        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: rgba(254, 252, 249, 0.85);
            margin-bottom: 8px;
            padding-left: 4px;
        }

        .input-field {
            width: 100%;
            padding: 12px 16px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 500;
            color: var(--white);
            background: rgba(254, 252, 249, 0.06);
            border: 1px solid rgba(254, 252, 249, 0.14);
            border-radius: 12px;
            outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        }
        .input-field::placeholder { color: rgba(254, 252, 249, 0.35); font-weight: 400; }
        .input-field:focus {
            border-color: var(--sage);
            background: rgba(254, 252, 249, 0.10);
            box-shadow: 0 0 0 4px rgba(122, 181, 146, 0.20);
        }

        .btn-submit {
            width: 100%;
            padding: 14px 20px;
            margin-top: 6px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.3px;
            color: var(--white);
            background: linear-gradient(135deg, var(--teal) 0%, var(--sage) 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(27, 107, 107, 0.45);
            transition: transform 0.25s, box-shadow 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(122, 181, 146, 0.55);
        }
        .btn-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent 30%, rgba(255, 255, 255, 0.20) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }
        .btn-submit:hover::after { transform: translateX(100%); }
        .btn-submit svg { width: 16px; height: 16px; transition: transform 0.25s; }
        .btn-submit:hover svg { transform: translateX(4px); }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 22px;
            font-size: 13px;
            font-weight: 500;
            color: rgba(254, 252, 249, 0.6);
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--white); }
        .back-link svg { transition: transform 0.25s; }
        .back-link:hover svg { transform: translateX(-3px); }

        .copyright {
            text-align: center;
            margin-top: 24px;
            font-size: 11px;
            color: rgba(254, 252, 249, 0.3);
            font-weight: 500;
            letter-spacing: 0.10em;
            line-height: 1.6;
        }
        .copyright strong {
            color: var(--sage);
            font-weight: 600;
        }

        @media (max-width: 480px) {
            .card { padding: 28px 22px; }
            .header-block h1 { font-size: 1.6rem; }
        }
    </style>
</head>
<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    {{-- ✨ Watermark "Hostel...flow" en arrière-plan --}}
    <div class="bg-watermark bg-watermark-left">Hostel</div>
    <div class="bg-watermark bg-watermark-right">flow</div>

    <div class="page-wrapper">

        {{-- ── Header (logo + titre + badge) ─────────────────────── --}}
        <div class="header-block fade-up d1">
            <div class="logo-circle">
                <img src="{{ asset('images/13.png') }}"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                     alt="Logo HostelFlow">
                <span class="logo-fallback" style="display:none;">HF</span>
            </div>

            <h1>Administration <em>Centrale</em></h1>

            <div class="role-badge">
                <span class="role-badge-icon">🛡️</span>
                <span>Super Admin</span>
            </div>
        </div>

        {{-- ── Card formulaire ─────────────────────────────────────── --}}
        <div class="card fade-up d2">

            @if($errors->any())
            <div class="error-banner">
                @foreach($errors->all() as $e)<p>→ {{ $e }}</p>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('super-admin.login.store') }}">
                @csrf

                <div class="form-group fade-up d3">
                    <label class="form-label" for="email">Adresse email</label>
                    <input id="email" class="input-field" type="email"
                           name="email" value="{{ old('email') }}"
                           placeholder="admin@hostelflow.com"
                           required autocomplete="email">
                </div>

                <div class="form-group fade-up d4">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input id="password" class="input-field" type="password"
                           name="password" placeholder="••••••••"
                           required autocomplete="current-password">
                </div>

                <button type="submit" class="btn-submit fade-up d5">
                    Accéder au panneau d'administration
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>

                <a href="{{ route('owner.login') }}" class="back-link fade-up d6">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à la connexion principale
                </a>
            </form>

        </div>

        <p class="copyright fade-up d6">
            HostelFlow © {{ date('Y') }}<br>
            <strong>Accès réservé aux administrateurs</strong>
        </p>

    </div>

</body>
</html>
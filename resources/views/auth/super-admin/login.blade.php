<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — HostelFlow</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --white:     #FFFFFF;
            --navy-900:  #1A2B3C;
            --navy-700:  #1A4A6B;
            --teal-600:  #2C6E8A;
            --gray-300:  #E8EEF2;
            --input-bg:  #F8FBFD;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #0f172a 0%, #1A2B3C 60%, #1A4A6B 100%);
            color: var(--white);
        }

        .page-wrapper { width: 100%; max-width: 420px; padding: 24px 20px; }

        .card {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 28px;
            padding: 44px 40px 40px;
            box-shadow: 0 24px 48px -12px rgba(0,0,0,0.4);
        }

        .fade-up { opacity: 0; transform: translateY(18px); animation: fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
        .d1 { animation-delay: 0ms; }
        .d2 { animation-delay: 120ms; }
        .d3 { animation-delay: 220ms; }
        .d4 { animation-delay: 320ms; }
        .d5 { animation-delay: 420ms; }

        .logo-ring {
            width: 80px; height: 80px;
            margin: 0 auto 24px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            border: 2px solid rgba(255,255,255,0.2);
            overflow: hidden;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-ring img { width: 100%; height: 100%; object-fit: cover; }

        .header { text-align: center; margin-bottom: 32px; }
        .header h1 { font-size: 24px; font-weight: 800; color: var(--white); }
        .badge {
            display: inline-block; margin-top: 8px;
            background: rgba(44,110,138,0.4); border: 1px solid rgba(44,110,138,0.6);
            color: #7dd3fc; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em;
            padding: 3px 12px; border-radius: 999px;
        }

        .error-banner {
            background: rgba(239,68,68,0.15);
            border-left: 3px solid #EF4444;
            border-radius: 0 10px 10px 0;
            padding: 12px 14px;
            margin-bottom: 20px;
            font-size: 13px; color: #fca5a5;
        }
        .error-banner p { font-weight: 600; }

        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 12px; font-weight: 700; color: rgba(255,255,255,0.6); margin-bottom: 8px; }
        .input-field {
            width: 100%;
            padding: 13px 16px;
            font-size: 14px; font-weight: 500; font-family: inherit;
            color: var(--white);
            background: rgba(255,255,255,0.07);
            border: 1.5px solid rgba(255,255,255,0.12);
            border-radius: 14px; outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        }
        .input-field::placeholder { color: rgba(255,255,255,0.3); font-weight: 400; }
        .input-field:focus {
            border-color: var(--teal-600);
            background: rgba(255,255,255,0.1);
            box-shadow: 0 0 0 4px rgba(44,110,138,0.2);
        }

        .btn-submit {
            width: 100%; padding: 14px 20px; margin-top: 8px;
            font-size: 14px; font-weight: 700; font-family: inherit;
            color: var(--white);
            background: linear-gradient(135deg, #1d4ed8 0%, var(--teal-600) 100%);
            border: none; border-radius: 14px; cursor: pointer;
            box-shadow: 0 6px 20px rgba(29,78,216,0.35);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(29,78,216,0.4); }
        .btn-submit:active { transform: translateY(0); }

        .footer-text {
            text-align: center; margin-top: 24px;
            font-size: 12px; color: rgba(255,255,255,0.35); font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="card">

            <div class="fade-up d1" style="text-align:center;">
                <div class="logo-ring">
                    <img src="{{ asset('images/logo.jpg') }}"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                         alt="HostelFlow">
                    <span style="display:none; font-size:20px; font-weight:900;">HF</span>
                </div>
            </div>

            <div class="header fade-up d2">
                <h1>Administration Centrale</h1>
                <span class="badge">Super Admin</span>
            </div>

            @if($errors->any())
            <div class="error-banner fade-up d2">
                @foreach($errors->all() as $e)
                    <p>→ {{ $e }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('super-admin.login.store') }}" class="fade-up d3">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input id="email" class="input-field" type="email"
                           name="email" value="{{ old('email') }}"
                           placeholder="admin@hostelflow.com"
                           required autocomplete="email">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input id="password" class="input-field" type="password"
                           name="password" placeholder="••••••••"
                           required autocomplete="current-password">
                </div>

                <button type="submit" class="btn-submit fade-up d4">
                    Accéder au panneau d'administration
                </button>
            </form>

            <div class="fade-up d5" style="text-align:center; margin-top:20px;">
                <a href="{{ route('owner.login') }}"
                   style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600;
                          color:rgba(255,255,255,0.5); text-decoration:none; transition:color 0.2s;"
                   onmouseover="this.style.color='rgba(255,255,255,0.9)'"
                   onmouseout="this.style.color='rgba(255,255,255,0.5)'">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à la page de connexion principale
                </a>
            </div>
            <p class="footer-text" style="margin-top:16px;">HostelFlow © 2026 — Accès réservé aux administrateurs</p>

        </div>
    </div>
</body>
</html>

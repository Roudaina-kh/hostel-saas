<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Super Admin — Connexion</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="min-height:100vh; display:flex; align-items:center; justify-content:center;
             background: linear-gradient(135deg, #1A1A2E 0%, #16213E 50%, #0F3460 100%);">

<div style="width:100%; max-width:420px; padding:1rem;">

    <div style="text-align:center; margin-bottom:2rem;">
        <div style="width:64px; height:64px; margin:0 auto 1rem; border-radius:1rem;
                    background:rgba(255,255,255,0.1); display:flex; align-items:center;
                    justify-content:center; font-size:2rem;">⚡</div>
        <h1 style="color:white; font-size:1.5rem; font-weight:700; margin:0;">Super Admin</h1>
        <p style="color:rgba(255,255,255,0.5); font-size:0.875rem; margin:0.5rem 0 0;">
            Accès réservé à l'administration de la plateforme
        </p>
    </div>

    <div style="background:rgba(255,255,255,0.05); border-radius:1.25rem; padding:2rem;
                border:1px solid rgba(255,255,255,0.1); backdrop-filter:blur(10px);">

        @if($errors->any())
        <div style="background:rgba(220,38,38,0.2); border:1px solid rgba(220,38,38,0.4);
                    border-radius:0.75rem; padding:1rem; margin-bottom:1.5rem; font-size:0.875rem; color:#FCA5A5;">
            @foreach($errors->all() as $e)<p style="margin:0.1rem 0;">• {{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('super-admin.login') }}">
            @csrf

            <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600;
                               color:rgba(255,255,255,0.8); margin-bottom:0.5rem;">
                    Adresse email
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="superadmin@hostel-saas.com"
                       style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem;
                              font-size:0.875rem; outline:none; box-sizing:border-box;
                              background:rgba(255,255,255,0.08); border:1.5px solid rgba(255,255,255,0.15);
                              color:white;"
                       onfocus="this.style.borderColor='rgba(255,255,255,0.4)'"
                       onblur="this.style.borderColor='rgba(255,255,255,0.15)'">
            </div>

            <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600;
                               color:rgba(255,255,255,0.8); margin-bottom:0.5rem;">
                    Mot de passe
                </label>
                <input type="password" name="password" required
                       placeholder="••••••••"
                       style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem;
                              font-size:0.875rem; outline:none; box-sizing:border-box;
                              background:rgba(255,255,255,0.08); border:1.5px solid rgba(255,255,255,0.15);
                              color:white;"
                       onfocus="this.style.borderColor='rgba(255,255,255,0.4)'"
                       onblur="this.style.borderColor='rgba(255,255,255,0.15)'">
            </div>

            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.5rem;">
                <input type="checkbox" name="remember" id="remember"
                       style="accent-color:#E94560; width:16px; height:16px;">
                <label for="remember" style="font-size:0.875rem; color:rgba(255,255,255,0.6);">
                    Se souvenir de moi
                </label>
            </div>

            <button type="submit"
                    style="width:100%; padding:0.875rem; border-radius:0.75rem; border:none;
                           font-size:0.875rem; font-weight:700; color:white; cursor:pointer;
                           background:linear-gradient(135deg, #E94560, #C2185B);
                           box-shadow:0 4px 15px rgba(233,69,96,0.4);"
                    onmouseover="this.style.opacity='0.9'"
                    onmouseout="this.style.opacity='1'">
                Accéder à l'administration →
            </button>
        </form>
    </div>

    <p style="text-align:center; margin-top:1.5rem; font-size:0.75rem; color:rgba(255,255,255,0.3);">
        Accès restreint — Hostel SaaS Platform
    </p>
</div>

</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — HostelFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #fafafa; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(226, 232, 240, 0.8); }
        .input-focus { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .input-focus:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); background: white; }
        .btn-gradient { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); transition: transform 0.2s; }
        .btn-gradient:hover { transform: translateY(-1px); box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.3); }
        .fade-up { animation: fadeUp 0.6s ease-out forwards; opacity: 0; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .bg-login {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(15, 23, 42, 0.4), rgba(15, 23, 42, 0.6)), url('{{ asset("images/Hostel (1).png") }}');
            background-size: cover;
            background-position: center;
            z-index: -1;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="bg-login"></div>

    <div class="max-w-[440px] w-full fade-up">
        
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center p-4 bg-white rounded-3xl shadow-xl mb-6 ring-1 ring-slate-200">
                <img src="{{ asset('images/logo.jpg') }}" alt="HostelFlow" class="w-16 h-16 object-cover rounded-2xl">
            </div>
            <h1 class="text-3xl font-extrabold text-[#0f172a] tracking-tight mb-2">Bienvenue sur HostelFlow</h1>
            <p class="text-slate-500 font-medium">Connectez-vous pour gérer vos opérations.</p>
        </div>

        <div class="glass-card rounded-[32px] p-10 shadow-2xl shadow-blue-500/5">
            
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-3">
                    <span class="text-red-500 text-xl">⚠️</span>
                    <div class="text-sm font-bold text-red-600">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('user.login.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Adresse Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="nom@exemple.com"
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-slate-900 font-medium outline-none input-focus placeholder:text-slate-400">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2 ml-1">
                        <label class="block text-sm font-bold text-slate-700">Mot de passe</label>
                        <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">Oublié ?</a>
                    </div>
                    <input type="password" name="password" required
                           placeholder="••••••••"
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-slate-900 font-medium outline-none input-focus placeholder:text-slate-400">
                </div>

                <div class="flex items-center gap-2 ml-1">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    <label for="remember" class="text-xs font-bold text-slate-500 cursor-pointer select-none">Se souvenir de moi</label>
                </div>

                <button type="submit" class="w-full btn-gradient py-4 rounded-2xl text-white font-bold text-lg shadow-lg shadow-blue-500/20 active:scale-[0.98] transition-all">
                    Se connecter
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-slate-100 text-center space-y-2">
    <p class="text-sm font-medium text-slate-500">
        Vous êtes propriétaire ? 
        <a href="{{ route('owner.login') }}" class="text-blue-600 font-bold hover:underline">
            Connexion propriétaire
        </a>
    </p>
</div>

        <div class="mt-10 text-center">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">HostelFlow © 2026 — Plateforme de Gestion</p>
        </div>

    </div>

</body>
</html>

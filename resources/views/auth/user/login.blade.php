<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Équipe — HostelFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border: 1px solid rgba(226,232,240,0.8); }
        .input-focus { transition: all 0.3s; }
        .input-focus:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59,130,246,0.1); background: white; }
        .btn-gradient { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); transition: transform 0.2s; }
        .btn-gradient:hover { transform: translateY(-1px); }
        .fade-up { animation: fadeUp 0.6s ease-out forwards; opacity: 0; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .bg-login {
            position: fixed; top:0; left:0; width:100%; height:100%;
            background: linear-gradient(rgba(15,23,42,0.5), rgba(15,23,42,0.7));
            background-color: #1e293b;
            z-index: -1;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="bg-login"></div>

    <div class="max-w-[440px] w-full fade-up">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center p-4 bg-white rounded-3xl shadow-xl mb-6 ring-1 ring-slate-200">
                <img src="{{ asset('images/logo.jpg') }}" alt="HostelFlow" class="w-16 h-16 object-cover rounded-2xl">
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2">Espace Équipe</h1>
            <p class="text-slate-400 font-medium">Manager · Staff · Financial</p>
        </div>

        <div class="glass-card rounded-[32px] p-10 shadow-2xl">

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

            <form action="{{ route('user.login.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Adresse Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="nom@exemple.com"
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-slate-900 font-medium outline-none input-focus placeholder:text-slate-400">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Mot de passe</label>
                    <input type="password" name="password" required
                           placeholder="••••••••"
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-slate-900 font-medium outline-none input-focus placeholder:text-slate-400">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 cursor-pointer">
                    <label for="remember" class="text-xs font-bold text-slate-500 cursor-pointer">Se souvenir de moi</label>
                </div>

                <button type="submit" class="w-full btn-gradient py-4 rounded-2xl text-white font-bold text-lg shadow-lg active:scale-[0.98] transition-all">
                    Se connecter →
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 text-center space-y-2">
                <p class="text-sm text-slate-500">
                    Vous êtes propriétaire ?
                    <a href="{{ route('owner.login') }}" class="text-blue-600 font-bold hover:underline ml-1">Connexion propriétaire</a>
                </p>
                <p class="text-sm text-slate-500">
                    Administration ?
                    <a href="{{ route('super-admin.login') }}" class="text-blue-600 font-bold hover:underline ml-1">Super Admin</a>
                </p>
            </div>
        </div>

        <div class="mt-6 text-center">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">HostelFlow © 2026</p>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte — Hostel SaaS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4"
      style="background-color: #FDFAF5;">

<div class="w-full max-w-md">

    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="w-14 h-14 mx-auto rounded-2xl flex items-center justify-center text-2xl mb-4"
             style="background: linear-gradient(135deg, #2C6E8A, #3A8FA8);">
            🏨
        </div>
        <h2 class="text-xl font-bold" style="color: #2C6E8A;">Bienvenue, propriétaire 👋</h2>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl p-8 shadow-sm" style="border: 1px solid #E8EEF2;">

        <div class="mb-6">
            <h2 class="text-2xl font-bold mb-1" style="color: #1A2B3C;">Créer un compte</h2>
            <p class="text-sm" style="color: #8A9BB0;">
                Commencez gratuitement, sans carte de crédit.
            </p>
        </div>

        @if($errors->any())
        <div class="rounded-xl p-4 mb-6 text-sm"
             style="background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626;">
            @foreach($errors->all() as $e)
                <p>• {{ $e }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-2" style="color: #1A2B3C;">
                    Nom complet
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       placeholder="Ahmed Ben Ali"
                       class="w-full rounded-xl px-4 py-3 text-sm outline-none transition-all"
                       style="border: 1.5px solid #D8E8F0; background: #F8FBFD; color: #1A2B3C;"
                       onfocus="this.style.borderColor='#2C6E8A'; this.style.background='#FFFFFF'; this.style.boxShadow='0 0 0 3px rgba(44,110,138,0.1)'"
                       onblur="this.style.borderColor='#D8E8F0'; this.style.background='#F8FBFD'; this.style.boxShadow='none'">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2" style="color: #1A2B3C;">
                    Adresse email
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="ahmed@hostel.tn"
                       class="w-full rounded-xl px-4 py-3 text-sm outline-none transition-all"
                       style="border: 1.5px solid #D8E8F0; background: #F8FBFD; color: #1A2B3C;"
                       onfocus="this.style.borderColor='#2C6E8A'; this.style.background='#FFFFFF'; this.style.boxShadow='0 0 0 3px rgba(44,110,138,0.1)'"
                       onblur="this.style.borderColor='#D8E8F0'; this.style.background='#F8FBFD'; this.style.boxShadow='none'">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2" style="color: #1A2B3C;">
                    Mot de passe
                </label>
                <input type="password" name="password" required
                       placeholder="Minimum 8 caractères"
                       class="w-full rounded-xl px-4 py-3 text-sm outline-none transition-all"
                       style="border: 1.5px solid #D8E8F0; background: #F8FBFD; color: #1A2B3C;"
                       onfocus="this.style.borderColor='#2C6E8A'; this.style.background='#FFFFFF'; this.style.boxShadow='0 0 0 3px rgba(44,110,138,0.1)'"
                       onblur="this.style.borderColor='#D8E8F0'; this.style.background='#F8FBFD'; this.style.boxShadow='none'">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2" style="color: #1A2B3C;">
                    Confirmer le mot de passe
                </label>
                <input type="password" name="password_confirmation" required
                       placeholder="••••••••"
                       class="w-full rounded-xl px-4 py-3 text-sm outline-none transition-all"
                       style="border: 1.5px solid #D8E8F0; background: #F8FBFD; color: #1A2B3C;"
                       onfocus="this.style.borderColor='#2C6E8A'; this.style.background='#FFFFFF'; this.style.boxShadow='0 0 0 3px rgba(44,110,138,0.1)'"
                       onblur="this.style.borderColor='#D8E8F0'; this.style.background='#F8FBFD'; this.style.boxShadow='none'">
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl text-sm font-bold text-white transition-all"
                    style="background: linear-gradient(135deg, #1A4A6B, #2C6E8A);
                           box-shadow: 0 4px 15px rgba(44,110,138,0.3);"
                    onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(44,110,138,0.4)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(44,110,138,0.3)'">
                Créer mon compte →
            </button>
        </form>

        <div class="flex items-center gap-4 my-6">
            <div class="flex-1 h-px" style="background: #E8EEF2;"></div>
            <span class="text-xs font-medium" style="color: #8A9BB0;">ou</span>
            <div class="flex-1 h-px" style="background: #E8EEF2;"></div>
        </div>

        <p class="text-center text-sm" style="color: #5A6B7A;">
            Déjà un compte ?
            <a href="{{ route('login') }}"
               class="font-bold hover:underline ml-1"
               style="color: #2C6E8A;">
                Se connecter
            </a>
        </p>
    </div>
</div>

</body>
</html>
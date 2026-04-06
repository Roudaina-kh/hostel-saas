<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrez votre établissement — HostelFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; color: #fff; overflow-x: hidden; }
        .bg-hero {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.9)), url('{{ asset("images/wallpaper2.jpg") }}');
            background-size: cover;
            background-position: center;
            z-index: -1;
        }
        .info-panel { background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); }
        .form-card { background: #fff; color: #1e293b; border-radius: 1rem; box-shadow: 0 50px 100px -20px rgba(0,0,0,0.5); }
        .input-pill { border: 1px solid #e2e8f0; border-radius: 0.5rem; transition: all 0.2s; }
        .input-pill:focus { border-color: #3b82f6; ring: 4px; ring-color: rgba(59, 130, 246, 0.1); }
        .footer { background: #051622; color: #94a3b8; }
    </style>
</head>
<body class="min-h-screen">

    <div class="bg-hero"></div>

    <!-- Header -->
    <header class="fixed top-0 left-0 w-full z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 py-4 px-8 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-12 h-12 rounded-xl scale-110">
            <span class="text-2xl font-extrabold text-[#0f172a] tracking-tight">HostelFlow</span>
        </div>
        <nav class="hidden md:flex items-center gap-8">
            <a href="{{ route('landing') }}" class="text-sm font-bold text-slate-600 hover:text-blue-600">Accueil</a>
            <a href="#" class="text-sm font-bold text-slate-600 hover:text-blue-600">Groupes 10 +</a>
            <a href="#" class="text-sm font-bold text-slate-600 hover:text-blue-600">Guides</a>
        </nav>
        <div class="flex items-center gap-4">
            <a href="{{ route('owner.login')}}" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-full text-sm font-bold shadow-lg shadow-blue-200 transition-all active:scale-95">Extranet</a>
        </div>
    </header>

    <main class="pt-32 pb-20 px-6 max-w-7xl mx-auto">
        <div class="mb-16">
            <h1 class="text-4xl md:text-5xl font-black mb-4 tracking-tight">ENREGISTREZ VOTRE LOGEMENT MAINTENANT.</h1>
            <p class="text-xl text-blue-400 font-bold uppercase tracking-wide">C'EST GRATUIT ET VOUS POUVEZ ANNULER À TOUT MOMENT</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
            <!-- Left Side: Info -->
            <div class="info-panel p-10 rounded-3xl space-y-8">
                <div class="flex items-start gap-4">
                    <div class="mt-1 w-6 h-6 flex-shrink-0 bg-blue-500 rounded-full flex items-center justify-center text-white">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <p class="text-lg font-medium leading-relaxed">HostelFlow est une plate-forme internationale dédiée aux auberges et logements étudiants</p>
                </div>
                <div class="flex items-start gap-4">
                    <div class="mt-1 w-6 h-6 flex-shrink-0 bg-blue-500 rounded-full flex items-center justify-center text-white">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <p class="text-lg font-medium leading-relaxed">Réservations garanties, obtenez votre argent plus tôt avec le prépaiement - taux non remboursables (acompte de 100 %)</p>
                </div>
                <div class="flex items-start gap-4">
                    <div class="mt-1 w-6 h-6 flex-shrink-0 bg-blue-500 rounded-full flex items-center justify-center text-white">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <p class="text-lg font-medium leading-relaxed">Obtenez plus de réservations en proposant de «Réservez maintenant et payez plus tard» (acompte de 0%)</p>
                </div>
                <div class="flex items-start gap-4">
                    <div class="mt-1 w-6 h-6 flex-shrink-0 bg-blue-500 rounded-full flex items-center justify-center text-white">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <p class="text-lg font-medium leading-relaxed">Des lits réservés dans le monde entier et ça continue !</p>
                </div>
                <div class="flex items-start gap-4">
                    <div class="mt-1 w-6 h-6 flex-shrink-0 bg-blue-500 rounded-full flex items-center justify-center text-white">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <p class="text-lg font-medium leading-relaxed">Aucuns frais d'inscription ou d'abonnement</p>
                </div>

                <!-- Partner Trust Banner -->
                <div class="pt-8 mt-8 border-t border-slate-700/50">
                    <p class="text-xs font-bold text-slate-400 mb-6 uppercase tracking-widest italic">And much more...</p>
                    <div class="grid grid-cols-2 gap-y-4 gap-x-8">
                        <div class="flex items-center gap-3 opacity-80 hover:opacity-100 transition-opacity">
                            <span class="text-xl">🇬🇧</span> <span class="text-sm font-bold">Dorms.com</span>
                        </div>
                        <div class="flex items-center gap-3 opacity-80 hover:opacity-100 transition-opacity">
                            <span class="text-xl">🇪🇸</span> <span class="text-sm font-bold">Albergues.com</span>
                        </div>
                        <div class="flex items-center gap-3 opacity-80 hover:opacity-100 transition-opacity">
                            <span class="text-xl">🇫🇷</span> <span class="text-sm font-bold">AubergesDeJeunesse.com</span>
                        </div>
                        <div class="flex items-center gap-3 opacity-80 hover:opacity-100 transition-opacity">
                            <span class="text-xl">🇮🇹</span> <span class="text-sm font-bold">OstelliDellaGioventu.com</span>
                        </div>
                        <div class="flex items-center gap-3 opacity-80 hover:opacity-100 transition-opacity">
                            <span class="text-xl">🇨🇳</span> <span class="text-sm font-bold">Dorms.com</span>
                        </div>
                        <div class="flex items-center gap-3 opacity-80 hover:opacity-100 transition-opacity">
                            <span class="text-xl">🇧🇷</span> <span class="text-sm font-bold">Dorms.com</span>
                        </div>
                        <div class="flex items-center gap-3 opacity-80 hover:opacity-100 transition-opacity">
                            <span class="text-xl">🇩🇪</span> <span class="text-sm font-bold">Hostales.com</span>
                        </div>
                        <div class="flex items-center gap-3 opacity-80 hover:opacity-100 transition-opacity">
                            <span class="text-xl">🇰🇷</span> <span class="text-sm font-bold">Dorms.com</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="form-card p-10">
                <div class="flex items-center gap-3 mb-8 pb-4 border-b border-slate-100">
                    <i class="fa-solid fa-hotel text-blue-600 text-2xl"></i>
                    <h2 class="text-xl font-black text-slate-800">Enregistrez votre établissement</h2>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-4 bg-green-50 border border-green-100 rounded-xl text-green-700 font-bold flex items-center gap-3">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('register-hostel.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase mb-2">Nom de l'auberge *</label>
                        <input type="text" name="hostel_name" required class="w-full p-4 input-pill outline-none text-slate-800 font-medium bg-slate-50 border-slate-200" placeholder="Ex: Maison de la Plage">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase mb-2">Prénom *</label>
                            <input type="text" name="first_name" required class="w-full p-4 input-pill outline-none text-slate-800 font-medium bg-slate-50 border-slate-200">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase mb-2">Nom de famille *</label>
                            <input type="text" name="last_name" required class="w-full p-4 input-pill outline-none text-slate-800 font-medium bg-slate-50 border-slate-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase mb-2">Email *</label>
                        <input type="email" name="email" required class="w-full p-4 input-pill outline-none text-slate-800 font-medium bg-slate-50 border-slate-200">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase mb-2">Pays *</label>
                            <select name="country" required class="w-full p-4 input-pill outline-none text-slate-800 font-medium bg-slate-50 border-slate-200 appearance-none">
                                <option value="Tunisie">Tunisie</option>
                                <option value="France">France</option><option value="Maroc">Maroc</option><option value="Algérie">Algérie</option>
                                <option value="Italie">Italie</option><option value="Espagne">Espagne</option><option value="Allemagne">Allemagne</option>
                                <option value="Royaume-Uni">Royaume-Uni</option><option value="États-Unis">États-Unis</option><option value="Canada">Canada</option>
                                <option value="Belgique">Belgique</option><option value="Suisse">Suisse</option><option value="Égypte">Égypte</option>
                                <option value="Turquie">Turquie</option><option value="Portugal">Portugal</option><option value="Grèce">Grèce</option>
                                <option value="Pays-Bas">Pays-Bas</option><option value="Autriche">Autriche</option><option value="Suède">Suède</option>
                                <option value="Norvège">Norvège</option><option value="Danemark">Danemark</option><option value="Finlande">Finlande</option>
                                <option value="Irlande">Irlande</option><option value="Luxembourg">Luxembourg</option><option value="Émirats Arabes Unis">Émirats Arabes Unis</option>
                                <option value="Arabie Saoudite">Arabie Saoudite</option><option value="Qatar">Qatar</option><option value="Koweït">Koweït</option>
                                <option value="Liban">Liban</option><option value="Jordanie">Jordanie</option><option value="Sénégal">Sénégal</option>
                                <option value="Côte d'Ivoire">Côte d'Ivoire</option><option value="Cameroun">Cameroun</option><option value="Mali">Mali</option>
                                <option value="Guinée">Guinée</option><option value="Bénin">Bénin</option><option value="Togo">Togo</option>
                                <option value="Burkina Faso">Burkina Faso</option><option value="Gabon">Gabon</option><option value="Congo">Congo</option>
                                <option value="RD Congo">RD Congo</option><option value="Madagascar">Madagascar</option><option value="Maurice">Maurice</option>
                                <option value="Chine">Chine</option><option value="Japon">Japon</option><option value="Corée du Sud">Corée du Sud</option>
                                <option value="Brésil">Brésil</option><option value="Argentine">Argentine</option><option value="Mexique">Mexique</option>
                                <option value="Australie">Australie</option><option value="Nouvelle-Zélande">Nouvelle-Zélande</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase mb-2">Ville *</label>
                            <input type="text" name="city" required class="w-full p-4 input-pill outline-none text-slate-800 font-medium bg-slate-50 border-slate-200" placeholder="Sélectionnez ou saisissez">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase mb-2">Numéro de téléphone</label>
                            <div class="flex gap-0">
                                <select class="p-4 input-pill rounded-r-none border-r-0 outline-none text-slate-800 font-bold bg-slate-50 border-slate-200">
                                    <option>+216</option><option>+33</option><option>+212</option><option>+213</option><option>+39</option><option>+34</option><option>+49</option>
                                    <option>+44</option><option>+1</option><option>+32</option><option>+41</option><option>+20</option><option>+90</option><option>+351</option>
                                    <option>+30</option><option>+31</option><option>+43</option><option>+46</option><option>+47</option><option>+45</option><option>+358</option>
                                    <option>+353</option><option>+352</option><option>+971</option><option>+966</option><option>+974</option><option>+965</option><option>+961</option>
                                    <option>+962</option><option>+221</option><option>+225</option><option>+237</option><option>+223</option><option>+224</option><option>+229</option>
                                    <option>+228</option><option>+226</option><option>+241</option><option>+242</option><option>+243</option><option>+261</option><option>+230</option>
                                    <option>+86</option><option>+81</option><option>+82</option><option>+55</option><option>+54</option><option>+52</option><option>+61</option><option>+64</option>
                                </select>
                                <input type="text" name="phone" required placeholder="XX XXX XXX" class="w-full p-4 input-pill rounded-l-none outline-none text-slate-800 font-medium bg-slate-50 border-slate-200">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase mb-2">ID Skype</label>
                            <input type="text" name="skype_id" class="w-full p-4 input-pill outline-none text-slate-800 font-medium bg-slate-50 border-slate-200" placeholder="live:user_123">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase mb-2">Channel Manager</label>
                        <input type="text" name="channel_manager" class="w-full p-4 input-pill outline-none text-slate-800 font-medium bg-slate-50 border-slate-200">
                    </div>

                    <!-- reCAPTCHA Placeholder -->
                    <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 border-[3px] border-slate-200 rounded-md bg-white"></div>
                            <span class="text-sm font-bold text-slate-600">Je ne suis pas un robot</span>
                        </div>
                        <div class="text-center">
                            <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" class="w-8 mx-auto grayscale opacity-50 mb-1">
                            <p class="text-[8px] font-bold text-slate-400">reCAPTCHA</p>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 p-5 rounded-xl text-white font-black text-lg shadow-xl shadow-blue-200 transition-all active:scale-95 uppercase">
                        Merci d'envoyer votre demande
                    </button>
                    
                    <p class="text-center">
                        <a href="{{ route('landing') }}" class="text-xs font-bold text-slate-400 hover:text-blue-600">Annuler et retourner à l'accueil</a>
                    </p>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer pt-20 pb-12 px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 mb-20">
            <div>
                <h4 class="font-bold text-lg mb-8 uppercase tracking-widest text-slate-500">Destinations Populaires</h4>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm font-medium">
                    <a href="#" class="hover:text-blue-500">Amsterdam</a><a href="#" class="hover:text-blue-500">Athenes</a>
                    <a href="#" class="hover:text-blue-500">Barcelone</a><a href="#" class="hover:text-blue-500">Berlin</a>
                    <a href="#" class="hover:text-blue-500">Bruxelles</a><a href="#" class="hover:text-blue-500">Budapest</a>
                    <a href="#" class="hover:text-blue-500">Dublin</a><a href="#" class="hover:text-blue-500">Florence</a>
                    <a href="#" class="hover:text-blue-500">Lisbonne</a><a href="#" class="hover:text-blue-500">Londres</a>
                    <a href="#" class="hover:text-blue-500">Madrid</a><a href="#" class="hover:text-blue-500">Milan</a>
                    <a href="#" class="hover:text-blue-500">Munich</a><a href="#" class="hover:text-blue-500">New York</a>
                    <a href="#" class="hover:text-blue-500">Nice</a><a href="#" class="hover:text-blue-500">Paris</a>
                    <a href="#" class="hover:text-blue-500">Prague</a><a href="#" class="hover:text-blue-500">Rome</a>
                    <a href="#" class="hover:text-blue-500">Stockholm</a><a href="#" class="hover:text-blue-500">Venise</a>
                    <a href="#" class="hover:text-blue-500">Vienne</a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-8 uppercase tracking-widest text-slate-500">Navigation</h4>
                <ul class="space-y-4 font-medium">
                    <li><a href="{{ route('landing') }}" class="hover:text-blue-500">Accueil</a></li>
                    <li><a href="#" class="hover:text-blue-500">Groupes 10 +</a></li>
                    <li><a href="#" class="hover:text-blue-500">Guides</a></li>
                    <li><a href="#" class="hover:text-blue-500">Blog des routards</a></li>
                    <li><a href="{{ route('register-hostel.create') }}" class="hover:text-blue-500">Ajouter votre établissement</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-8 uppercase tracking-widest text-slate-500">Informations</h4>
                <ul class="space-y-4 font-medium">
                    <li><a href="#" class="hover:text-blue-500">Aide / FAQ / Nous Joindre</a></li>
                    <li><a href="#" class="hover:text-blue-500">Conditions</a></li>
                    <li><a href="#" class="hover:text-blue-500">Confidentialité</a></li>
                    <li><a href="#" class="hover:text-blue-500">Mentions légales</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-8 uppercase tracking-widest text-slate-500">Téléchargez l'application</h4>
                <div class="space-y-4">
                    <div class="h-12 w-40 bg-slate-800 rounded flex items-center px-4 hover:bg-slate-700 cursor-pointer border border-slate-700 transition-all">
                        <i class="fa-brands fa-apple text-2xl mr-2"></i>
                        <span class="text-[10px] leading-tight font-bold">Download on the <br><span class="text-base">App Store</span></span>
                    </div>
                    <div class="h-12 w-40 bg-slate-800 rounded flex items-center px-4 hover:bg-slate-700 cursor-pointer border border-slate-700 transition-all">
                        <i class="fa-brands fa-google-play text-xl mr-2"></i>
                        <span class="text-[10px] leading-tight font-bold">GET IT ON <br><span class="text-base">Google Play</span></span>
                    </div>
                </div>
                <div class="mt-12 flex items-center gap-2 opacity-50">
                    <img src="{{ asset('images/logo.jpg') }}" class="w-8 h-8 rounded-lg">
                    <span class="text-sm font-black tracking-tighter">HostelFlow</span>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-12 border-t border-slate-800 text-center">
            <p class="text-xs text-slate-500 max-w-5xl mx-auto">
                Tous droits réservés. HostelFlow.com ® propose la réservation d'auberges de jeunesse dans le monde entier.
            </p>
        </div>
    </footer>

</body>
</html>

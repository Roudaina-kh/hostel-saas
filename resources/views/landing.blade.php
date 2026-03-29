<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelFlow — Réservation d'Auberges de Jeunesse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #fff; overflow-x: hidden; }
        .hero-section { 
            position: relative;
            min-height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #0052cc;
        }
        .hero-bg-anim {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('{{ asset("images/Hostel (1).png") }}');
            background-size: 110%;
            background-position: center;
            animation: hero-panning 20s infinite alternate ease-in-out;
            z-index: 1;
        }
        @keyframes hero-panning {
            0% { background-position: center; background-size: 110%; }
            100% { background-position: 55% 45%; background-size: 115%; }
        }
        @keyframes hero-panning {
            0% { background-position: center; background-size: 110%; }
            100% { background-position: 55% 45%; background-size: 115%; }
        }
        .logo-anim { animation: pulse 2s infinite ease-in-out; }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
        .animate-pulse-subtle { animation: pulse-subtle 4s infinite ease-in-out; }
        @keyframes pulse-subtle { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.02); } }
        .glass-card { 
            position: relative;
            z-index: 10;
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(15px); 
            border-radius: 2.5rem; 
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.5); 
        }
        .search-bar { border-radius: 1.5rem; box-shadow: 0 20px 50px rgba(0,0,0,0.15); border: 4px solid #fff; }
        .counter-btn { width: 32px; height: 32px; border-radius: 50%; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
        .counter-btn:hover { background: #3b82f6; color: white; border-color: #3b82f6; }
        .calendar-dropdown { position: absolute; top: 110%; left: 0; width: 680px; background: white; border-radius: 1.5rem; box-shadow: 0 30px 60px rgba(0,0,0,0.2); padding: 2rem; z-index: 50; display: none; }
        .calendar-grid { display: grid; grid-template-cols: 1fr 1fr; gap: 2.5rem; }
        
        /* New Animations */
        .reveal-up { animation: reveal-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes reveal-up {
            0% { transform: translateY(30px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        .animate-float { animation: float-slow 6s infinite ease-in-out; }
        @keyframes float-slow {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(2deg); }
        }
        .animate-float-delayed { animation: float-slow 7s infinite ease-in-out -2s; }
        .animate-float-more { animation: float-slow 5s infinite ease-in-out -1s; }

        .hostel-card { transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease; cursor: pointer; }
        .hostel-card:hover { transform: translateY(-12px); box-shadow: 0 30px 40px -10px rgba(0, 0, 0, 0.15); }
        .offer-card { transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); }
        .offer-card:hover { transform: translateY(-15px) scale(1.02); box-shadow: 0 40px 70px -15px rgba(0,0,0,0.1); }
        
        .footer { background: #051622; color: #fff; }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="fixed top-0 left-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-slate-100 py-4 px-8 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-12 h-12 rounded-xl scale-110">
            <span class="text-2xl font-extrabold text-[#0f172a] tracking-tight">HostelFlow</span>
        </div>

        <div class="hidden lg:flex items-center gap-6">
            <div class="flex items-center gap-2 text-slate-400">
                <i class="fa-solid fa-barcode text-xl cursor-not-allowed"></i>
                <i class="fa-solid fa-qrcode text-xl cursor-not-allowed"></i>
            </div>
            <a href="#" class="text-sm font-bold text-slate-700 hover:text-blue-600 transition-colors">Téléchargez l'application gratuite</a>
            <a href="{{ route('register-hostel.create') }}" class="px-5 py-2.5 bg-blue-50 text-blue-700 rounded-full text-sm font-bold hover:bg-blue-100 transition-all">
                Envoyer une demande de réservation
            </a>
            <div class="flex items-center gap-4 text-slate-600 border-l pl-6 border-slate-200">
                <span class="font-bold text-sm">EUR</span>
                <i class="fa-solid fa-globe"></i>
                <a href="{{ route('login') }}" class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center hover:bg-slate-200 transition-all">
                    <i class="fa-solid fa-user"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Hero / Login Section -->
    <main class="pt-24">
        <section class="hero-section p-8 pb-32">
            <div class="hero-bg-anim"></div>
            <div class="max-w-[440px] w-full">
                <!-- Re-using existing login form structure as requested -->
                <div class="glass-card p-10 transform hover:scale-[1.01] transition-all duration-500">
                    <div class="text-center mb-10">
                        <div class="inline-flex items-center justify-center p-3 bg-white rounded-2xl shadow-xl mb-6 ring-1 ring-slate-100 animate-pulse-subtle">
                            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-12 h-12 rounded-xl object-cover">
                        </div>
                        <h2 class="text-3xl font-black text-[#0f172a] tracking-tight mb-2">Espace Membres</h2>
                        <p class="text-slate-500 font-medium text-sm">Gérez vos propriétés HostelFlow</p>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl text-xs font-bold text-red-600">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                            <input type="email" name="email" required placeholder="nom@exemple.com"
                                   class="w-full bg-slate-50/50 border border-slate-100 rounded-2xl px-5 py-4 outline-none focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-medium text-slate-900">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Mot de passe</label>
                            <input type="password" name="password" required placeholder="••••••••"
                                   class="w-full bg-slate-50/50 border border-slate-100 rounded-2xl px-5 py-4 outline-none focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-medium text-slate-900">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 py-5 rounded-2xl text-white font-black text-lg shadow-xl shadow-blue-200 transition-all active:scale-[0.98]">
                            Se connecter
                        </button>

                        <div class="text-center pt-4">
                            <a href="{{ route('register-hostel.create') }}" class="text-sm font-bold text-blue-600 hover:underline">Devenir partenaire →</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Search Section (Photo 2 & 4 Refined) -->
        <section class="max-w-6xl mx-auto -mt-12 px-4 relative z-30">
            <div class="bg-white p-2 search-bar flex flex-col md:flex-row items-center gap-1 relative">
                <!-- Location -->
                <div class="flex-1 w-full relative group">
                    <i class="fa-solid fa-location-dot absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    <input type="text" placeholder="Ville, quartier ou logement..." 
                           class="w-full pl-14 pr-6 py-6 bg-transparent border-none rounded-xl focus:ring-0 outline-none font-bold text-slate-700 placeholder:text-slate-400">
                </div>
                
                <!-- Dates (Mimicking Capture 4) -->
                <div class="flex items-center gap-4 px-8 border-l border-slate-100 hidden md:flex cursor-pointer hover:bg-slate-50 py-4 transition-all rounded-xl relative overflow-visible" onclick="document.getElementById('cal-drop').style.display = document.getElementById('cal-drop').style.display === 'block' ? 'none' : 'block'">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-calendar-days text-blue-500 text-xl"></i>
                        <span class="font-black text-sm whitespace-nowrap text-slate-800">4 avr. 2026</span>
                    </div>
                    <span class="text-slate-300 mx-1">—</span>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-calendar-days text-blue-600 text-xl"></i>
                        <span class="font-black text-sm whitespace-nowrap text-slate-800">6 avr. 2026</span>
                    </div>

                    <!-- Calendar Dropdown Mockup (Capture 4 style) -->
                    <div id="cal-drop" class="calendar-dropdown">
                        <div class="calendar-grid">
                            <div>
                                <h4 class="font-black text-center mb-4 text-[#0f172a] uppercase tracking-widest text-xs">Mars 2026</h4>
                                <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-slate-400 mb-2">
                                    <span>LUN</span><span>MAR</span><span>MER</span><span>JEU</span><span>VEN</span><span>SAM</span><span>DIM</span>
                                </div>
                                <div class="grid grid-cols-7 gap-1 text-center">
                                    @for($i=1; $i<=31; $i++)
                                        <span class="p-2 rounded-lg hover:bg-blue-50 cursor-pointer {{ $i == 25 ? 'bg-blue-600 text-white font-black' : 'text-slate-700' }}">{{ $i }}</span>
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <h4 class="font-black text-center mb-4 text-[#0f172a] uppercase tracking-widest text-xs">Avril 2026</h4>
                                <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-slate-400 mb-2">
                                    <span>LUN</span><span>MAR</span><span>MER</span><span>JEU</span><span>VEN</span><span>SAM</span><span>DIM</span>
                                </div>
                                <div class="grid grid-cols-7 gap-1 text-center text-xs">
                                    @for($i=1; $i<=30; $i++)
                                        <span class="p-2 rounded-lg hover:bg-blue-50 cursor-pointer {{ ($i >= 4 && $i <= 6) ? 'bg-yellow-400 font-black' : 'text-slate-700' }}">{{ $i }}</span>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Persons (With - / +) -->
                <div class="flex items-center gap-6 px-10 border-l border-slate-100 hidden md:flex">
                    <div class="flex items-center gap-3">
                        <button onclick="decrementGuest()" class="counter-btn"><i class="fa-solid fa-minus text-xs"></i></button>
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-users text-blue-500 text-xl"></i>
                            <span id="guest-count" class="font-black text-sm text-slate-800 w-24 text-center">2 Personnes</span>
                        </div>
                        <button onclick="incrementGuest()" class="counter-btn"><i class="fa-solid fa-plus text-xs"></i></button>
                    </div>
                </div>

                <button class="w-full md:w-20 h-16 bg-yellow-400 hover:bg-yellow-500 rounded-2xl flex items-center justify-center text-slate-900 shadow-xl shadow-yellow-400/30 transition-all active:scale-95 m-1">
                    <i class="fa-solid fa-magnifying-glass text-2xl"></i>
                </button>
            </div>
            
            <script>
                let guests = 2;
                function incrementGuest() { guests++; updateGuestUI(); }
                function decrementGuest() { if(guests > 1) guests--; updateGuestUI(); }
                function updateGuestUI() { document.getElementById('guest-count').innerText = guests + (guests > 1 ? ' Personnes' : ' Personne'); }
            </script>
            
            <div class="mt-12 text-center">
                <h1 class="text-4xl md:text-6xl font-black text-[#0f172a] mb-4">30.000 Auberges De Jeunesse</h1>
                <p class="text-lg text-slate-500 font-medium">Explorez le monde ensemble.</p>
            </div>
        </section>

        <!-- Offers Section (Photo 3 Refined) -->
        <section class="py-24 bg-slate-50/50 mt-12 px-6 overflow-hidden">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-3xl font-extrabold text-center text-[#0f172a] mb-16 reveal-up">Toutes les meilleures offres en un seul endroit</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                    <div class="bg-white p-8 rounded-[40px] shadow-xl shadow-slate-200/50 offer-card reveal-up" style="animation-delay: 0.1s;">
                        <div class="w-24 h-24 mx-auto mb-6 bg-blue-50 rounded-full flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                            <i class="fa-solid fa-mug-hot text-4xl text-blue-600"></i>
                        </div>
                        <h3 class="font-bold text-slate-800">Petit déjeuner gratuit</h3>
                    </div>
                    <div class="bg-white p-8 rounded-[40px] shadow-xl shadow-slate-200/50 offer-card reveal-up" style="animation-delay: 0.2s;">
                        <div class="w-24 h-24 mx-auto mb-6 bg-red-50 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-calendar-xmark text-4xl text-red-500"></i>
                        </div>
                        <h3 class="font-bold text-slate-800">Annulation gratuite</h3>
                    </div>
                    <div class="bg-white p-8 rounded-[40px] shadow-xl shadow-slate-200/50 offer-card reveal-up" style="animation-delay: 0.3s;">
                        <div class="w-24 h-24 mx-auto mb-6 bg-green-50 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-wifi text-4xl text-green-600"></i>
                        </div>
                        <h3 class="font-bold text-slate-800">Wifi gratuit</h3>
                    </div>
                </div>
            </div>
        </section>

        <!-- Popular Places (Photo 4) -->
        <section class="py-24 bg-blue-600 relative overflow-hidden">
            <div class="max-w-6xl mx-auto px-8 flex flex-col md:flex-row items-center gap-16 relative z-10">
                <div class="md:w-1/2">
                    <h2 class="text-4xl md:text-5xl font-black text-white leading-tight mb-8">
                        Découvrez des lieux populaires pour des aventures <span class="underline decoration-yellow-400">inoubliables</span>
                    </h2>
                </div>
                <div class="md:w-1/2 flex gap-4 rotate-3">
                    <div class="space-y-4 animate-float">
                        <div class="bg-white p-2 rounded-lg shadow-2xl rotate-[-5deg] hover:rotate-0 transition-transform duration-500">
                            <img src="{{ asset('images/hostel.jpg') }}" class="rounded w-48 h-64 object-cover">
                        </div>
                    </div>
                    <div class="space-y-4 translate-y-12">
                        <div class="bg-white p-2 rounded-lg shadow-2xl rotate-[5deg] animate-float-delayed hover:rotate-0 transition-transform duration-500">
                            <img src="{{ asset('images/camp.jpg') }}" class="rounded w-48 h-64 object-cover">
                        </div>
                        <div class="bg-white p-2 rounded-lg shadow-2xl rotate-[-2deg] animate-float-more hover:rotate-0 transition-transform duration-500">
                            <img src="{{ asset('images/youth.jpg') }}" class="rounded w-48 h-48 object-cover">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Grid pattern overlay -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px;"></div>
        </section>

        <!-- Hostel Showcase (Capture 2 Repro) -->
        <section class="py-20 px-6 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <div class="flex justify-center mb-4">
                        <div class="bg-slate-100 p-4 rounded-full">
                            <i class="fa-solid fa-hotel text-4xl text-blue-600"></i>
                        </div>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 mb-2">Auberges de jeunesse</h2>
                    <p class="text-slate-500 font-medium">Comparez et réservez les meilleures auberges de Jeunesse avec dortoirs et chambres privées.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                    <!-- Card 1 -->
                    <div class="hostel-card bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm">
                        <img src="{{ asset('images/vienne.jpg') }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <p class="text-xs font-bold text-slate-400 mb-1">Auberges à <span class="text-blue-600">Vienne</span></p>
                            <p class="text-xs font-medium text-slate-500">De</p>
                            <p class="text-lg font-black text-slate-900">26,98 €</p>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="hostel-card bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm">
                        <img src="{{ asset('images/Montreal.jpg') }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <p class="text-xs font-bold text-slate-400 mb-1">Auberges à <span class="text-blue-600">Montreal</span></p>
                            <p class="text-xs font-medium text-slate-500">De</p>
                            <p class="text-lg font-black text-slate-900">35,68 €</p>
                        </div>
                    </div>
                    <!-- Card 3 -->
                    <div class="hostel-card bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm">
                        <img src="{{ asset('images/prague.jpg') }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <p class="text-xs font-bold text-slate-400 mb-1">Auberges à <span class="text-blue-600">Prague</span></p>
                            <p class="text-xs font-medium text-slate-500">De</p>
                            <p class="text-lg font-black text-slate-900">16,57 €</p>
                        </div>
                    </div>
                    <!-- Card 4 -->
                    <div class="hostel-card bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm">
                        <img src="{{ asset('images/lille.jpg') }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <p class="text-xs font-bold text-slate-400 mb-1">Auberges à <span class="text-blue-600">Lille</span></p>
                            <p class="text-xs font-medium text-slate-500">De</p>
                            <p class="text-lg font-black text-slate-900">31,19 €</p>
                        </div>
                    </div>
                    <!-- Card 5 -->
                    <div class="hostel-card bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=400&q=80" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <p class="text-xs font-bold text-slate-400 mb-1">Auberges à <span class="text-blue-600">Paris</span></p>
                            <p class="text-xs font-medium text-slate-500">De</p>
                            <p class="text-lg font-black text-slate-900">34,78 €</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer (Photo 5) -->
    <footer class="footer pt-20 pb-12 px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 mb-20">
            <div class="col-span-1">
                <h4 class="font-bold text-lg mb-8 uppercase tracking-widest text-slate-500">Destinations Populaires</h4>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-slate-400 text-sm font-medium">
                    <a href="#" class="hover:text-blue-500">Amsterdam</a>
                    <a href="#" class="hover:text-blue-500">Athens</a>
                    <a href="#" class="hover:text-blue-500">Barcelona</a>
                    <a href="#" class="hover:text-blue-500">Berlin</a>
                    <a href="#" class="hover:text-blue-500">Brussels</a>
                    <a href="#" class="hover:text-blue-500">Budapest</a>
                    <a href="#" class="hover:text-blue-500">Dublin</a>
                    <a href="#" class="hover:text-blue-500">Florence</a>
                    <a href="#" class="hover:text-blue-500">Lisbon</a>
                    <a href="#" class="hover:text-blue-500">London</a>
                    <a href="#" class="hover:text-blue-500">Madrid</a>
                    <a href="#" class="hover:text-blue-500">Milan</a>
                    <a href="#" class="hover:text-blue-500">Munich</a>
                    <a href="#" class="hover:text-blue-500">New York</a>
                    <a href="#" class="hover:text-blue-500">Nice</a>
                    <a href="#" class="hover:text-blue-500">Paris</a>
                    <a href="#" class="hover:text-blue-500">Prague</a>
                    <a href="#" class="hover:text-blue-500">Rome</a>
                    <a href="#" class="hover:text-blue-500">Stockholm</a>
                    <a href="#" class="hover:text-blue-500">Venice</a>
                    <a href="#" class="hover:text-blue-500">Vienna</a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-8 uppercase tracking-widest text-slate-500">Navigation</h4>
                <ul class="space-y-4 text-slate-400 font-medium">
                    <li><a href="#" class="hover:text-blue-500 transition-colors">Accueil</a></li>
                    <li><a href="#" class="hover:text-blue-500 transition-colors">Groupes 10 +</a></li>
                    <li><a href="#" class="hover:text-blue-500 transition-colors">Guides</a></li>
                    <li><a href="#" class="hover:text-blue-500 transition-colors">Blog des routards</a></li>
                    <li><a href="{{ route('register-hostel.create') }}" class="hover:text-blue-500 transition-colors">Ajouter votre établissement</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-blue-500 transition-colors">Extranet</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-8 uppercase tracking-widest text-slate-500">Informations</h4>
                <ul class="space-y-4 text-slate-400 font-medium">
                    <li><a href="#" class="hover:text-blue-500 transition-colors">Aide / FAQ / Nous Joindre</a></li>
                    <li><a href="#" class="hover:text-blue-500 transition-colors">Conditions</a></li>
                    <li><a href="#" class="hover:text-blue-500 transition-colors">Confidentialité</a></li>
                    <li><a href="#" class="hover:text-blue-500 transition-colors">Mentions légales</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-8 uppercase tracking-widest text-slate-500">Téléchargez l'application gratuite</h4>
                <div class="space-y-4">
                    <img src="https://text-to-image.workload.internal/v1/get-image?prompt=apple+app+store+badge+black+high+res" class="h-12 cursor-pointer shadow-lg active:scale-95 transition-all">
                    <img src="https://text-to-image.workload.internal/v1/get-image?prompt=google+play+store+badge+black+high+res" class="h-12 cursor-pointer shadow-lg active:scale-95 transition-all">
                </div>
                <div class="flex items-center gap-2 mt-8 opacity-50 gray-scale">
                    <img src="{{ asset('images/logo.jpg') }}" class="w-8 h-8 rounded-lg">
                    <span class="text-sm font-black tracking-tighter">HostelFlow</span>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-12 border-t border-slate-800 text-center">
            <p class="text-xs text-slate-500 leading-normal max-w-5xl mx-auto">
                Tous droits réservés. AubergesDeJeunesse.com ® propose la réservation dernière minute d'auberges de jeunesse dans le monde entier. Vous pouvez réserver des auberges de jeunesse à Paris, des Auberges de jeunesse à Londres, des auberges de jeunesse à Barcelone, des auberges de jeunesse à New York, des auberges de jeunesse à Rome, des auberges de jeunesse à Amsterdam, des auberges de jeunesse à Dublin, des auberges de jeunesse à Madrid et des auberges de jeunesse à Prague ... Nous fournissons également des informations de dernière minute, des guides et commentaires pour choisir votre parfaite auberge de jeunesse pas chère dans le centre ville.
            </p>
        </div>
    </footer>

</body>
</html>

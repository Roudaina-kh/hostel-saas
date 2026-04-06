<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin — Hostel SaaS')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color: #F0F4F8; min-height:100vh; display:flex;">

    {{-- SIDEBAR --}}
    <aside style="width:240px; min-height:100vh; display:flex; flex-direction:column;
                  background: linear-gradient(180deg, #1A1A2E 0%, #16213E 60%, #0F3460 100%);">

        <div style="padding:1.5rem; border-bottom:1px solid rgba(255,255,255,0.1);">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <div style="width:36px; height:36px; background:rgba(255,255,255,0.15);
                            border-radius:10px; display:flex; align-items:center;
                            justify-content:center; font-size:1.1rem;">⚡</div>
                <div>
                    <p style="color:white; font-weight:700; font-size:0.9rem; margin:0;">Super Admin</p>
                    <p style="color:rgba(255,255,255,0.4); font-size:0.7rem; margin:0;">Hostel SaaS Platform</p>
                </div>
            </div>
        </div>

        <nav style="flex:1; padding:1.5rem 1rem;">
            @php
            $navItems = [
                ['route' => 'super-admin.dashboard',      'icon' => '📊', 'label' => 'Dashboard'],
                ['route' => 'super-admin.owners.index',   'icon' => '👑', 'label' => 'Propriétaires'],
                ['route' => 'super-admin.hostels.index',  'icon' => '🏨', 'label' => 'Hostels'],
            ];
            @endphp

            @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               style="display:flex; align-items:center; gap:0.75rem; padding:0.625rem 1rem;
                      border-radius:0.75rem; text-decoration:none; font-size:0.875rem;
                      font-weight:500; margin-bottom:0.25rem; transition:all 0.15s;
                      {{ request()->routeIs($item['route'].'*')
                         ? 'background:rgba(255,255,255,0.15); color:white;'
                         : 'color:rgba(255,255,255,0.6);' }}">
                <span>{{ $item['icon'] }}</span>
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>

        <div style="padding:1rem; border-top:1px solid rgba(255,255,255,0.1);">
            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem;">
                <div style="width:32px; height:32px; background:rgba(255,255,255,0.15);
                            border-radius:50%; display:flex; align-items:center;
                            justify-content:center; color:white; font-size:0.875rem; font-weight:700;">
                    {{ strtoupper(substr(Auth::guard('super_admin')->user()->name, 0, 1)) }}
                </div>
                <div>
                    <p style="color:white; font-size:0.8rem; font-weight:600; margin:0;">
                        {{ Auth::guard('super_admin')->user()->name }}
                    </p>
                    <p style="color:rgba(255,255,255,0.4); font-size:0.7rem; margin:0;">Super Admin</p>
                </div>
            </div>
            <form method="POST" action="{{ route('super-admin.logout') }}">
                @csrf
                <button style="width:100%; padding:0.5rem; border-radius:0.5rem; border:none;
                               background:rgba(255,255,255,0.1); color:rgba(255,255,255,0.7);
                               font-size:0.8rem; cursor:pointer;">
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- CONTENU --}}
    <div style="flex:1; display:flex; flex-direction:column;">
        <header style="background:white; border-bottom:1px solid #E8EEF2;
                       padding:1rem 2rem; display:flex; align-items:center; justify-content:space-between;">
            <h2 style="font-size:1rem; font-weight:600; color:#1A2B3C; margin:0;">
                @yield('title', 'Dashboard')
            </h2>
            <span style="font-size:0.75rem; padding:0.25rem 0.75rem; border-radius:9999px;
                         background:#FEF2F2; color:#DC2626; font-weight:600;">
                ⚡ Mode Super Admin
            </span>
        </header>

        <main style="padding:2rem; flex:1;">

            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({ icon:'success', title:'Succès !',
                        text:'{{ session('success') }}',
                        timer:2500, showConfirmButton:false,
                        background:'#FDFAF5', color:'#1A2B3C' });
                });
            </script>
            @endif

            @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({ icon:'error', title:'Erreur',
                        text:'{{ session('error') }}',
                        background:'#FDFAF5', color:'#1A2B3C' });
                });
            </script>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
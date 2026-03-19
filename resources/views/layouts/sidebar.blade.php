<aside class="min-h-screen flex flex-col transition-all duration-300 relative z-20 flex-shrink-0"
       style="width: 18rem; background-color: #FDFAF5; border-right: 1px solid #E8EEF2; box-shadow: 4px 0 24px rgba(0,0,0,0.02);">

    {{-- Logo / Header --}}
    <div class="px-6 py-8">
        <div class="flex items-center gap-4">
            <div class="rounded-full overflow-hidden shrink-0 shadow-md" style="width: 64px; height: 64px; flex-shrink: 0;">
                <img src="{{ asset('images/logo.jpg') }}" alt="HostelFlow Logo" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <span style="display:none;" class="text-[#1A2B3C] font-black text-lg tracking-tighter">HF</span>
            </div>
            <div>
                <h2 class="text-[#1A2B3C] font-black tracking-tight leading-none" style="font-size: 22px;">HostelFlow</h2>
                <p class="font-bold text-[#3B82F6] mt-2 leading-tight" style="font-size: 11px; max-width: 140px;">La meilleure application pour réserver des auberges !</p>
            </div>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 px-4 py-8 space-y-4 overflow-y-auto custom-scrollbar">

        @php
        $menu = [
            ['route' => 'dashboard',        'icon' => '📊', 'label' => 'Tableau de bord'],
            ['route' => 'rooms.index',       'icon' => '🚪', 'label' => 'Chambres'],
            ['route' => 'beds.index',        'icon' => '🛏️', 'label' => 'Lits'],
            ['route' => 'tent-spaces.index', 'icon' => '⛺', 'label' => 'Espaces Tentes'],
            ['route' => 'pricing.index',     'icon' => '💲', 'label' => 'Prix'],
            ['route' => 'taxes.index',       'icon' => '🧾', 'label' => 'Taxes'],
            ['route' => 'hostels.index',     'icon' => '🏠', 'label' => 'Hostels'],
        ];
        $soon = ['Équipe', 'Réservations', 'Rapports'];
        @endphp

        @foreach($menu as $item)
        @php
            $isActive = request()->routeIs(explode('.', $item['route'])[0].'*');
        @endphp
        <a href="{{ route($item['route']) }}"
           class="group flex items-center gap-4 px-4 py-4 rounded-2xl font-bold transition-all duration-300 relative
                  {{ $isActive
                     ? 'bg-[#F8FAFC] text-[#3B82F6] border-2 border-[#3B82F6]'
                     : 'text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] border-2 border-[#E2E8F0] hover:border-[#BAE6FD]' }}"
           style="font-size: 15.5px; {{ $isActive ? 'box-shadow: 0 4px 12px rgba(59,130,246,0.15);' : '' }}">
            
            <span class="flex-shrink-0 transition-transform duration-300 {{ $isActive ? 'scale-110 drop-shadow-md' : 'grayscale-[0.5] opacity-80 group-hover:grayscale-0 group-hover:opacity-100 group-hover:scale-110' }}" style="font-size: 22px;">{{ $item['icon'] }}</span>
            {{ $item['label'] }}
        </a>
        @endforeach

        <div class="pt-6 pb-2 px-1">
            <span class="font-bold text-[#94A3B8] uppercase tracking-widest pl-1" style="font-size: 11px;">Bientôt</span>
        </div>

        @foreach($soon as $label)
        <span class="flex items-center justify-between px-4 py-3.5 rounded-2xl font-semibold text-[#8A9BB0]/60 cursor-not-allowed border-2 border-[#F1F5F9]" style="font-size: 14px;">
            <span class="flex items-center gap-3.5">
                <span class="opacity-30 text-lg">🔒</span>{{ $label }}
            </span>
            <span class="font-extrabold bg-[#E8EEF2] text-[#8A9BB0] px-2.5 py-1 rounded-lg uppercase tracking-wider" style="font-size: 10px;">Bientôt</span>
        </span>
        @endforeach
    </nav>

    {{-- User Profile (Acting as Logout Button) --}}
    <form method="POST" action="{{ route('logout') }}" class="m-0 mt-auto mx-4 mb-6">
        @csrf
        <button type="submit" title="Déconnexion"
                class="w-full text-center px-4 py-2.5 bg-[#E0F2FE] border border-[#BAE6FD] rounded-xl text-[#0369A1] font-bold text-[14px] truncate transition-all duration-200 hover:bg-[#BAE6FD]/50 shadow-sm hover:shadow">
            {{ Auth::user()->name }}
        </button>
    </form>
</aside>
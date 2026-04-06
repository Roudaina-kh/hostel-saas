<aside class="min-h-screen flex flex-col transition-all duration-300 relative z-20 flex-shrink-0"
       style="width: 18rem; background-color: #FDFAF5; border-right: 1px solid #E8EEF2; box-shadow: 4px 0 24px rgba(0,0,0,0.02);">

    {{-- Logo / Header --}}
    <div class="px-6 py-8">
        <div class="flex items-center gap-4">
            <div class="rounded-full overflow-hidden shrink-0 shadow-md" style="width: 64px; height: 64px; flex-shrink: 0;">
                <img src="{{ asset('images/logo.jpg') }}" alt="HostelFlow Logo" class="w-full h-full object-cover"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <span style="display:none;" class="text-[#1A2B3C] font-black text-lg tracking-tighter">HF</span>
            </div>
            <div>
                <h2 class="text-[#1A2B3C] font-black tracking-tight leading-none" style="font-size: 22px;">HostelFlow</h2>
                <p class="font-bold text-[#3B82F6] mt-2 leading-tight" style="font-size: 11px; max-width: 140px;">
                    La meilleure application pour réserver des auberges !
                </p>
            </div>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 px-4 py-8 space-y-4 overflow-y-auto custom-scrollbar">

        @php
            $staffHostelId = session('staff_hostel_id');
            $isUserGuard   = Auth::guard('user')->check();
            $isOwnerGuard  = Auth::guard('owner')->check();
            $isSuperAdmin  = Auth::guard('super_admin')->check();

            $isManager   = $isUserGuard && Auth::guard('user')->user()?->roleInHostel($staffHostelId) === 'manager';
            $isFinancial = $isUserGuard && Auth::guard('user')->user()?->roleInHostel($staffHostelId) === 'financial';
            $isStaff     = $isUserGuard && ! $isManager && ! $isFinancial;

            $logoutRoute = $isOwnerGuard ? 'owner.logout' : ($isSuperAdmin ? 'super-admin.logout' : 'user.logout');

            // ── Menu Owner ──────────────────────────────────────
            if ($isOwnerGuard) {
                $menu = [
                    ['route' => 'dashboard',          'icon' => '📊', 'label' => 'Dashboard'],
                    ['route' => 'rooms.index',         'icon' => '🚪', 'label' => 'Chambres'],
                    ['route' => 'beds.index',          'icon' => '🛏️', 'label' => 'Lits'],
                    ['route' => 'tent-spaces.index',   'icon' => '⛺', 'label' => 'Espaces Tente'],
                    ['route' => 'pricing.index',       'icon' => '💲', 'label' => 'Tarifs'],
                    ['route' => 'taxes.index',         'icon' => '🧾', 'label' => 'Taxes'],
                    ['route' => 'managers.index',      'icon' => '👥', 'label' => 'Équipe'],
                    ['route' => 'hostels.index',       'icon' => '🏠', 'label' => 'Hostels'],
                ];
                $soon = ['Reservations', 'Reports'];
            }

            // ── Menu Manager ─────────────────────────────────────
            elseif ($isManager) {
                $menu = [
                    ['route' => 'manager.dashboard',      'icon' => '📊', 'label' => 'Dashboard'],
                    ['route' => 'manager.rooms.index',    'icon' => '🚪', 'label' => 'Chambres'],
                    ['route' => 'manager.beds.index',     'icon' => '🛏️', 'label' => 'Lits'],
                    ['route' => 'manager.pricing.index',  'icon' => '💰', 'label' => 'Tarifs'],
                    ['route' => 'manager.taxes.index',    'icon' => '🧾', 'label' => 'Taxes'],
                    ['route' => 'manager.staff.index',    'icon' => '👥', 'label' => 'Équipe'],
                    ['route' => 'manager.settings.edit',  'icon' => '⚙️', 'label' => 'Paramètres'],
                ];
                $soon = ['Reservations', 'Reports'];
            }

            // ── Menu Financier ───────────────────────────────────
            elseif ($isFinancial) {
                $menu = [
                    ['route' => 'staff.financial.dashboard',      'icon' => '📈', 'label' => 'Dashboard'],
                    ['route' => 'staff.cash-shifts.index',        'icon' => '🔒', 'label' => 'Clôture Caisse'],
                    ['route' => 'staff.financial.reports.index',  'icon' => '📊', 'label' => 'Rapports'],
                ];
                $soon = ['Tax Audit', 'Profit Analytics'];
            }

            // ── Menu Staff opérationnel ──────────────────────────
            else {
                $menu = [
                    ['route' => 'staff.dashboard', 'icon' => '📊', 'label' => 'Dashboard'],
                ];
                $soon = ['Reservations', 'Reports', 'Financials'];
            }
        @endphp

        @foreach($menu as $item)
            @php $isActive = request()->routeIs($item['route'] . '*'); @endphp
            <a href="{{ route($item['route']) }}"
               class="group flex items-center gap-4 px-4 py-4 rounded-2xl font-bold transition-all duration-300 relative
                      {{ $isActive
                          ? 'bg-[#F8FAFC] text-[#3B82F6] border-2 border-[#3B82F6]'
                          : 'text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] border-2 border-[#E2E8F0] hover:border-[#BAE6FD]' }}"
               style="font-size: 15.5px; {{ $isActive ? 'box-shadow: 0 4px 12px rgba(59,130,246,0.15);' : '' }}">
                <span class="flex-shrink-0 transition-transform duration-300
                             {{ $isActive ? 'scale-110 drop-shadow-md' : 'grayscale-[0.5] opacity-80 group-hover:grayscale-0 group-hover:opacity-100 group-hover:scale-110' }}"
                      style="font-size: 22px;">{{ $item['icon'] }}</span>
                {{ $item['label'] }}
            </a>
        @endforeach

        <div class="pt-6 pb-2 px-1">
            <span class="font-bold text-[#94A3B8] uppercase tracking-widest pl-1" style="font-size: 11px;">Bientôt</span>
        </div>

        @foreach($soon as $label)
            <span class="flex items-center justify-between px-4 py-3.5 rounded-2xl font-semibold text-[#8A9BB0]/60 cursor-not-allowed border-2 border-[#F1F5F9]"
                  style="font-size: 14px;">
                <span class="flex items-center gap-3.5">
                    <span class="opacity-30 text-lg">🔒</span>{{ $label }}
                </span>
                <span class="font-extrabold bg-[#E8EEF2] text-[#8A9BB0] px-2.5 py-1 rounded-lg uppercase tracking-wider"
                      style="font-size: 10px;">Bientôt</span>
            </span>
        @endforeach

    </nav>

    {{-- Logout --}}
    <form method="POST" action="{{ route($logoutRoute) }}" class="m-0 mt-auto mx-4 mb-6 flex justify-center">
        @csrf
        <button type="submit" title="Déconnexion"
                class="flex items-center justify-center gap-2 font-bold text-[14px] text-black transition-all duration-200 hover:opacity-70 bg-transparent border-none cursor-pointer">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Déconnexion
        </button>
    </form>
</aside>
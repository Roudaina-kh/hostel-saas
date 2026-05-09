<style>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,700;0,900;1,600;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

#hf-sidebar {
    width: 17rem;
    height: 100vh;
    display: flex;
    flex-direction: column;
    flex-shrink: 0;
    position: relative;
    z-index: 20;
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: linear-gradient(160deg, #0D1B35 0%, #0F2548 55%, #0D1B35 100%);
    box-shadow: 4px 0 32px rgba(0,0,0,0.25);
    overflow: hidden;
}
#hf-sidebar::before {
    content: '';
    position: absolute;
    width: 280px; height: 280px;
    background: radial-gradient(circle, rgba(249,115,22,0.18) 0%, transparent 70%);
    top: -60px; right: -60px;
    pointer-events: none;
    animation: orb-drift 6s ease-in-out infinite alternate;
}
#hf-sidebar::after {
    content: '';
    position: absolute;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);
    bottom: 80px; left: -40px;
    pointer-events: none;
    animation: orb-drift 8s ease-in-out infinite alternate-reverse;
}
@keyframes orb-drift {
    from { transform: translate(0,0) scale(1); }
    to   { transform: translate(12px, 16px) scale(1.2); }
}

.sb-logo {
    padding: 1.25rem 1.25rem 1rem;
    display: flex;
    align-items: center;
    gap: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}
.sb-logo img {
    width: 42px; height: 42px;
    border-radius: 14px;
    object-fit: cover;
    box-shadow: 0 4px 16px rgba(249,115,22,0.3);
    border: 2px solid rgba(249,115,22,0.4);
    transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s;
}
.sb-logo:hover img {
    transform: rotate(-5deg) scale(1.1);
    box-shadow: 0 6px 24px rgba(249,115,22,0.5);
}
.sb-logo-text { line-height: 1.15; }
.sb-logo-name {
    font-family: 'Fraunces', serif;
    font-weight: 900;
    font-size: 18px;
    color: #FFFFFF;
    letter-spacing: -0.5px;
}
.sb-logo-tagline {
    font-size: 10.5px;
    font-weight: 500;
    color: rgba(249,115,22,0.85);
    margin-top: 1px;
}

/* ── HOSTEL SWITCHER ── */
.sb-hostel-switcher {
    margin: 0.75rem 1.25rem 0;
    position: relative;
    z-index: 50;
    flex-shrink: 0;
}
.sb-hostel-trigger {
    width: 100%;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 9px 12px;
    display: flex;
    align-items: center;
    gap: 9px;
    cursor: pointer;
    transition: background 0.2s, border-color 0.2s;
}
.sb-hostel-trigger:hover {
    background: rgba(255,255,255,0.09);
    border-color: rgba(255,255,255,0.14);
}
.sb-hostel-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #4ADE80;
    box-shadow: 0 0 8px rgba(74,222,128,0.8);
    flex-shrink: 0;
    animation: blink 2s infinite;
}
@keyframes blink {
    0%,100% { opacity: 1; }
    50%      { opacity: 0.4; }
}
.sb-hostel-name {
    font-size: 13px;
    font-weight: 600;
    color: rgba(255,255,255,0.85);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
}
.sb-hostel-chevron {
    flex-shrink: 0;
    color: rgba(255,255,255,0.35);
    transition: transform 0.25s;
    font-size: 10px;
}
.sb-hostel-trigger.open .sb-hostel-chevron { transform: rotate(180deg); }

/* Dropdown */
.sb-hostel-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    left: 0; right: 0;
    background: #0F2548;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 16px 40px rgba(0,0,0,0.4);
    z-index: 100;
}
.sb-hostel-dropdown.open { display: block; }
.sb-hostel-dropdown-header {
    padding: 10px 14px 6px;
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.3);
}
.sb-hostel-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 14px;
    cursor: pointer;
    transition: background 0.15s;
    text-decoration: none;
}
.sb-hostel-option:hover { background: rgba(255,255,255,0.06); }
.sb-hostel-option.current { background: rgba(249,115,22,0.1); }
.sb-hostel-option-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: rgba(255,255,255,0.2); flex-shrink: 0;
}
.sb-hostel-option.current .sb-hostel-option-dot { background: #4ADE80; }
.sb-hostel-option-name {
    font-size: 13px; font-weight: 500;
    color: rgba(255,255,255,0.7);
    flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.sb-hostel-option.current .sb-hostel-option-name { color: #FB923C; font-weight: 600; }
.sb-hostel-option-check { font-size: 12px; color: #4ADE80; }

.sb-hostel-dropdown-divider {
    height: 1px; background: rgba(255,255,255,0.06);
    margin: 4px 0;
}
.sb-add-hostel-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px 12px;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.15s;
}
.sb-add-hostel-btn:hover { background: rgba(249,115,22,0.08); }
.sb-add-hostel-icon {
    width: 22px; height: 22px; border-radius: 6px;
    background: rgba(249,115,22,0.15);
    border: 1px solid rgba(249,115,22,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; flex-shrink: 0;
}
.sb-add-hostel-label {
    font-size: 13px; font-weight: 600; color: #FB923C;
}

/* ── PROFIL USER ── */
.sb-user-profile {
    margin: 0.6rem 1.25rem 0;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 9px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
    position: relative; z-index: 1;
}
.sb-user-avatar {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, #F97316, #FBBF24);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800; color: #fff; flex-shrink: 0;
}
.sb-user-name {
    font-size: 12.5px; font-weight: 600;
    color: rgba(255,255,255,0.8);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1;
}
.sb-user-role {
    font-size: 10px; color: rgba(249,115,22,0.75); font-weight: 600;
    white-space: nowrap;
}

.sb-section-label {
    padding: 1rem 1.5rem 0.4rem;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.25);
    position: relative; z-index: 1;
}

#hf-sidebar nav {
    flex: 1;
    overflow-y: auto;
    padding: 0.25rem 0.875rem 1rem;
    position: relative; z-index: 1;
    scrollbar-width: none;
}
#hf-sidebar nav::-webkit-scrollbar { display: none; }

.sb-item {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 10px 14px;
    border-radius: 12px;
    margin-bottom: 3px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    color: rgba(255,255,255,0.55);
    position: relative;
    overflow: hidden;
    transition: color 0.25s;
    cursor: pointer;
    border: 1px solid transparent;
}
.sb-item::before {
    content: '';
    position: absolute; inset: 0;
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    transform: translateX(-110%);
    transition: transform 0.35s cubic-bezier(0.4,0,0.2,1);
}
.sb-item:hover::before { transform: translateX(0); }
.sb-item:hover { color: rgba(255,255,255,0.92); border-color: rgba(255,255,255,0.07); }
.sb-item.active {
    background: rgba(249,115,22,0.12);
    border-color: rgba(249,115,22,0.25);
    color: #FB923C;
    font-weight: 600;
}
.sb-item.active::before { transform: translateX(0); background: transparent; }
.sb-item.active::after {
    content: '';
    position: absolute;
    left: 0; top: 20%; bottom: 20%;
    width: 3px;
    border-radius: 0 3px 3px 0;
    background: linear-gradient(180deg, #F97316, #FBBF24);
    box-shadow: 0 0 12px rgba(249,115,22,0.6);
}
.sb-icon {
    width: 32px; height: 32px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
    background: rgba(255,255,255,0.04);
    transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1), background 0.25s;
}
.sb-item:hover .sb-icon { transform: scale(1.18) rotate(-5deg); background: rgba(255,255,255,0.08); }
.sb-item.active .sb-icon { background: rgba(249,115,22,0.18); transform: scale(1.08); }

.sb-soon {
    display: flex; align-items: center; gap: 11px;
    padding: 9px 14px; border-radius: 12px; margin-bottom: 3px;
    opacity: 0.35; cursor: not-allowed;
    font-size: 14px; font-weight: 500; color: rgba(255,255,255,0.5);
}
.sb-soon-badge {
    margin-left: auto; font-size: 9.5px; font-weight: 700;
    letter-spacing: .08em; padding: 2px 8px; border-radius: 99px;
    background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.4);
}

.sb-logout-wrap {
    flex-shrink: 0;
    padding: 0.875rem 1.25rem;
    border-top: 1px solid rgba(255,255,255,0.06);
    position: relative; z-index: 1;
}
.sb-logout-btn {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px; border-radius: 12px;
    background: rgba(239,68,68,0.08);
    border: 1px solid rgba(239,68,68,0.15);
    cursor: pointer; transition: all 0.25s; width: 100%;
}
.sb-logout-btn:hover { background: rgba(239,68,68,0.18); border-color: rgba(239,68,68,0.3); transform: translateX(3px); }
.sb-logout-btn svg { color: #F87171; flex-shrink: 0; transition: transform 0.3s; }
.sb-logout-btn:hover svg { transform: translateX(3px); }
.sb-logout-btn span { font-size: 13.5px; font-weight: 600; color: #F87171; }
</style>

<aside id="hf-sidebar">

    {{-- Logo --}}
    <div class="sb-logo">
        <img src="{{ asset('images/logo2.png') }}" alt="HostelFlow"
             onerror="this.src='{{ asset('images/13.png') }}'">
        <div class="sb-logo-text">
            <div class="sb-logo-name">HostelFlow</div>
            <div class="sb-logo-tagline">Gestion d'auberges</div>
        </div>
    </div>

    @php
        $staffHostelId = session('staff_hostel_id');
        $activeHostelId = session('hostel_id');
        $isUserGuard   = Auth::guard('user')->check();
        $isOwnerGuard  = Auth::guard('owner')->check();
        $isSuperAdmin  = Auth::guard('super_admin')->check();

        $isManager   = false;
        $isStaff     = false;
        $isFinancial = false;

        if ($isUserGuard) {
            $userRole = Auth::guard('user')->user()?->hostels()
                ->where('hostels.id', $staffHostelId)
                ->first()?->pivot->role ?? 'unknown';
            $isManager   = $userRole === 'manager';
            $isStaff     = $userRole === 'staff';
            $isFinancial = $userRole === 'financial';
        }

        $logoutRoute = $isOwnerGuard ? 'owner.logout' : ($isSuperAdmin ? 'super-admin.logout' : 'user.logout');

        if ($isOwnerGuard) {
            $owner       = Auth::guard('owner')->user();
            $allHostels  = $owner->hostels()->latest()->get();
            $activeHostel = $allHostels->firstWhere('id', $activeHostelId) ?? $allHostels->first();
            $hostelName  = $activeHostel?->name ?? 'Mon Hostel';
            $userName    = $owner->name;
            $userRole    = 'Propriétaire';
            $userInitial = strtoupper(mb_substr($userName, 0, 1));

            $menu = [
                ['route' => 'dashboard',              'icon' => '📊', 'label' => 'Dashboard',        'group' => 'main'],
                ['route' => 'reservations.index',     'icon' => '📅', 'label' => 'Réservations',     'group' => 'main'],
                ['route' => 'rooms.index',            'icon' => '🚪', 'label' => 'Chambres',          'group' => 'inventory'],
                ['route' => 'beds.index',             'icon' => '🛏️', 'label' => 'Lits',             'group' => 'inventory'],
                ['route' => 'tent-spaces.index',      'icon' => '⛺',  'label' => 'Tentes',           'group' => 'inventory'],
                ['route' => 'extras.index',           'icon' => '🛒', 'label' => 'Extras',            'group' => 'inventory'],
                ['route' => 'inventory-blocks.index', 'icon' => '🚫', 'label' => 'Indisponibilités', 'group' => 'inventory'],
                ['route' => 'prices.index',           'icon' => '💲', 'label' => 'Tarifs',            'group' => 'finance'],
                ['route' => 'taxes.index',            'icon' => '🧾', 'label' => 'Taxes',             'group' => 'finance'],
                ['route' => 'exchange-rates.index',   'icon' => '💱', 'label' => 'Taux de change',   'group' => 'finance'],
                ['route' => 'payments.index',         'icon' => '💳', 'label' => 'Paiements',         'group' => 'finance'],
                ['route' => 'managers.index',         'icon' => '👥', 'label' => 'Équipe',            'group' => 'admin'],
                ['route' => 'contact-requests.index', 'icon' => '📩', 'label' => 'Demandes clients', 'group' => 'admin'],
                ['route' => 'hostels.index',          'icon' => '🏠', 'label' => 'Mes Hostels',       'group' => 'admin'],
            ];
            $soon = ['Rapports', 'Analytics'];

        } elseif ($isManager) {
            $allHostels  = collect();
            $hostelName  = optional(\App\Models\Hostel::find($staffHostelId))->name ?? 'Hostel';
            $userName    = Auth::guard('user')->user()->name;
            $userRole    = 'Manager';
            $userInitial = strtoupper(mb_substr($userName, 0, 1));
            $menu = [
                ['route' => 'manager.dashboard',              'icon' => '📊', 'label' => 'Dashboard',        'group' => 'main'],
                ['route' => 'manager.reservations.index',     'icon' => '📅', 'label' => 'Réservations',     'group' => 'main'],
                ['route' => 'manager.rooms.index',            'icon' => '🚪', 'label' => 'Chambres',          'group' => 'inventory'],
                ['route' => 'manager.beds.index',             'icon' => '🛏️', 'label' => 'Lits',             'group' => 'inventory'],
                ['route' => 'manager.inventory-blocks.index', 'icon' => '🚫', 'label' => 'Indisponibilités', 'group' => 'inventory'],
                ['route' => 'manager.pricing.index',          'icon' => '💰', 'label' => 'Tarifs',            'group' => 'finance'],
                ['route' => 'manager.taxes.index',            'icon' => '🧾', 'label' => 'Taxes',             'group' => 'finance'],
                ['route' => 'manager.payments.index',         'icon' => '💳', 'label' => 'Paiements',         'group' => 'finance'],
                ['route' => 'manager.exchange-rates.index',   'icon' => '💱', 'label' => 'Taux de change',   'group' => 'finance'],
                ['route' => 'manager.staff.index',            'icon' => '👥', 'label' => 'Équipe',            'group' => 'admin'],
                ['route' => 'manager.contact-requests.index', 'icon' => '📩', 'label' => 'Demandes clients', 'group' => 'admin'],
                ['route' => 'manager.settings.edit',          'icon' => '⚙️', 'label' => 'Paramètres',       'group' => 'admin'],
            ];
            $soon = ['Rapports'];

        } elseif ($isStaff) {
            $allHostels  = collect();
            $hostelName  = optional(\App\Models\Hostel::find($staffHostelId))->name ?? 'Hostel';
            $userName    = Auth::guard('user')->user()->name;
            $userRole    = 'Staff';
            $userInitial = strtoupper(mb_substr($userName, 0, 1));
            $menu = [
                ['route' => 'staff.dashboard',          'icon' => '📊', 'label' => 'Dashboard',    'group' => 'main'],
                ['route' => 'staff.reservations.index', 'icon' => '📅', 'label' => 'Réservations', 'group' => 'main'],
                ['route' => 'staff.payments.index',     'icon' => '💳', 'label' => 'Paiements',     'group' => 'finance'],
            ];
            $soon = ['Rapports'];

        } elseif ($isFinancial) {
            $allHostels  = collect();
            $hostelName  = optional(\App\Models\Hostel::find($staffHostelId))->name ?? 'Hostel';
            $userName    = Auth::guard('user')->user()->name;
            $userRole    = 'Financier';
            $userInitial = strtoupper(mb_substr($userName, 0, 1));
            $menu = [
                ['route' => 'staff.financial.dashboard',     'icon' => '📈', 'label' => 'Dashboard',      'group' => 'main'],
                ['route' => 'staff.reservations.index',      'icon' => '📅', 'label' => 'Réservations',   'group' => 'main'],
                ['route' => 'staff.cash-shifts.index',       'icon' => '🔒', 'label' => 'Clôture Caisse', 'group' => 'finance'],
                ['route' => 'staff.financial.reports.index', 'icon' => '📊', 'label' => 'Rapports',       'group' => 'finance'],
            ];
            $soon = ['Tax Audit', 'Analytics'];

        } else {
            $allHostels  = collect();
            $hostelName  = 'Hostel';
            $userName    = 'Utilisateur';
            $userRole    = '';
            $userInitial = 'U';
            $menu = [['route' => 'staff.dashboard', 'icon' => '📊', 'label' => 'Dashboard', 'group' => 'main']];
            $soon = ['Réservations', 'Rapports'];
        }

        $groups = [
            'main'      => ['label' => 'Principal',      'items' => []],
            'inventory' => ['label' => 'Inventaire',     'items' => []],
            'finance'   => ['label' => 'Finance',        'items' => []],
            'admin'     => ['label' => 'Administration', 'items' => []],
        ];
        foreach ($menu as $item) {
            $groups[$item['group']]['items'][] = $item;
        }
    @endphp

    {{-- ✅ HOSTEL SWITCHER --}}
    <div class="sb-hostel-switcher">
        <div class="sb-hostel-trigger" id="hostelTrigger"
             onclick="toggleHostelDropdown()"
             style="{{ $isOwnerGuard && $allHostels->count() > 1 ? '' : 'cursor:default' }}">
            <span class="sb-hostel-dot"></span>
            <span class="sb-hostel-name">{{ $hostelName }}</span>
            @if($isOwnerGuard && $allHostels->count() > 1)
                <span class="sb-hostel-chevron">▼</span>
            @endif
        </div>

        @if($isOwnerGuard && $allHostels->count() > 1)
        <div class="sb-hostel-dropdown" id="hostelDropdown">
            <div class="sb-hostel-dropdown-header">Mes hostels</div>
            @foreach($allHostels as $h)
                <form method="POST" action="{{ route('hostel.switch', $h) }}" style="margin:0">
                    @csrf
                    <button type="submit" class="sb-hostel-option {{ $h->id == $activeHostelId ? 'current' : '' }}"
                            style="width:100%;background:none;border:none;text-align:left;cursor:pointer;">
                        <span class="sb-hostel-option-dot"></span>
                        <span class="sb-hostel-option-name">{{ $h->name }}</span>
                        @if($h->id == $activeHostelId)
                            <span class="sb-hostel-option-check">✓</span>
                        @endif
                    </button>
                </form>
            @endforeach
            <div class="sb-hostel-dropdown-divider"></div>
            <a href="{{ route('hostels.create') }}" class="sb-add-hostel-btn">
                <span class="sb-add-hostel-icon">+</span>
                <span class="sb-add-hostel-label">Ajouter un hostel</span>
            </a>
        </div>
        @endif
    </div>

    {{-- ✅ Si 1 seul hostel → juste le bouton ajouter --}}
    @if($isOwnerGuard && $allHostels->count() <= 1)
    <div style="margin: 0.4rem 1.25rem 0; position:relative; z-index:1;">
        <a href="{{ route('hostels.create') }}"
           style="display:flex;align-items:center;gap:8px;padding:6px 10px;border-radius:10px;
                  background:rgba(249,115,22,0.08);border:1px dashed rgba(249,115,22,0.25);
                  text-decoration:none;transition:all 0.2s;"
           onmouseover="this.style.background='rgba(249,115,22,0.15)'"
           onmouseout="this.style.background='rgba(249,115,22,0.08)'">
            <span style="font-size:12px;color:rgba(249,115,22,0.7)">+</span>
            <span style="font-size:11.5px;font-weight:600;color:rgba(249,115,22,0.7)">Ajouter un hostel</span>
        </a>
    </div>
    @endif

    {{-- ✅ PROFIL UTILISATEUR --}}
    <div class="sb-user-profile" style="margin-top:0.6rem">
        <div class="sb-user-avatar">{{ $userInitial }}</div>
        <div style="flex:1;min-width:0">
            <div class="sb-user-name">{{ $userName }}</div>
            @if($userRole)
            <div class="sb-user-role">{{ $userRole }}</div>
            @endif
        </div>
    </div>

    {{-- Nav --}}
    <nav>
        @foreach($groups as $groupKey => $group)
            @if(count($group['items']) > 0)
                <div class="sb-section-label">{{ $group['label'] }}</div>
                @foreach($group['items'] as $item)
                    @php $isActive = request()->routeIs($item['route'] . '*'); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="sb-item {{ $isActive ? 'active' : '' }}">
                        <span class="sb-icon">{{ $item['icon'] }}</span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            @endif
        @endforeach

        @if(count($soon) > 0)
            <div class="sb-section-label">Bientôt</div>
            @foreach($soon as $label)
                <div class="sb-soon">
                    <span class="sb-icon" style="font-size:14px; opacity:.5;">🔒</span>
                    <span>{{ $label }}</span>
                    <span class="sb-soon-badge">Soon</span>
                </div>
            @endforeach
        @endif
    </nav>

    {{-- Logout --}}
    <div class="sb-logout-wrap">
        <form method="POST" action="{{ route($logoutRoute) }}">
            @csrf
            <button type="submit" class="sb-logout-btn">
                <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>

</aside>

<script>
function toggleHostelDropdown() {
    const trigger  = document.getElementById('hostelTrigger');
    const dropdown = document.getElementById('hostelDropdown');
    if (!dropdown) return;
    const isOpen = dropdown.classList.contains('open');
    dropdown.classList.toggle('open');
    trigger.classList.toggle('open');
}
document.addEventListener('click', function(e) {
    const switcher = document.querySelector('.sb-hostel-switcher');
    if (switcher && !switcher.contains(e.target)) {
        document.getElementById('hostelDropdown')?.classList.remove('open');
        document.getElementById('hostelTrigger')?.classList.remove('open');
    }
});
</script>
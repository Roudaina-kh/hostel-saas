
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','Dashboard') — HostelFlow</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --cream:#FDF8F2; --warm-white:#FFFCF7;
  --coral:#FF6B47; --coral-light:#FF8A6A;
  --teal:#2ABFBF; --teal-dark:#1A9999;
  --sand:#F0E6D3; --sand-dark:#D4B896;
  --charcoal:#2C2C2C; --charcoal-light:#3E3E3E;
  --gray:#6B6B6B; --gray-light:#A0A0A0;
  --border:#E8DDD0; --border-dark:rgba(255,255,255,0.08);
  --shadow:rgba(44,44,44,0.08); --shadow-strong:rgba(44,44,44,0.18);
  --sidebar-w:250px; --header-h:64px;
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--charcoal);display:flex;min-height:100vh;overflow-x:hidden}
::-webkit-scrollbar{width:5px}::-webkit-scrollbar-track{background:var(--sand)}::-webkit-scrollbar-thumb{background:var(--sand-dark);border-radius:4px}::-webkit-scrollbar-thumb:hover{background:var(--coral)}

/* ══ SIDEBAR ══ */
.sidebar{
  width:var(--sidebar-w);min-height:100vh;
  background:var(--charcoal);
  display:flex;flex-direction:column;
  position:fixed;top:0;left:0;z-index:200;
  box-shadow:4px 0 24px rgba(0,0,0,0.18);
  transition:transform 0.3s ease;
}

/* Logo */
.sidebar-logo{
  padding:18px 18px 14px;
  border-bottom:1px solid var(--border-dark);
  display:flex;align-items:center;gap:10px;
  text-decoration:none;
}
.logo-dot{width:8px;height:8px;background:var(--teal);border-radius:50%;flex-shrink:0;animation:pulse 2s ease infinite}
@keyframes pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.6);opacity:0.6}}
.logo-img-box{
  height:38px;background:rgba(255,255,255,0.1);
  border-radius:9px;padding:5px 11px;
  display:flex;align-items:center;
  border:1px solid rgba(255,255,255,0.12);
}
.logo-img-box img{height:27px;width:auto;object-fit:contain;filter:brightness(0) invert(1);display:block}
.logo-txt{font-family:'Fraunces',serif;font-size:1.25rem;font-weight:700;color:#fff;display:none}
.logo-txt span{color:var(--coral)}

/* Hostel badge */
.hostel-badge-wrap{padding:12px 14px 0}
.hostel-badge{
  background:rgba(42,191,191,0.12);border:1px solid rgba(42,191,191,0.22);
  border-radius:10px;padding:9px 12px;
}
.hostel-badge-label{font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--gray-light);margin-bottom:4px}
.hostel-badge-name{font-size:0.85rem;font-weight:600;color:#fff;display:flex;align-items:center;justify-content:space-between}
.hostel-badge-name span{font-size:0.65rem;background:var(--teal);color:#fff;padding:2px 8px;border-radius:8px}

/* Multi-hostel switcher */
.hostel-switch-list{padding:6px 14px 0;display:flex;flex-direction:column;gap:4px}
.hostel-switch-btn{
  background:none;border:none;cursor:pointer;
  width:100%;text-align:left;padding:6px 10px;border-radius:8px;
  font-family:'DM Sans',sans-serif;font-size:0.78rem;color:rgba(255,255,255,0.55);
  transition:all 0.2s;display:flex;align-items:center;gap:7px;
}
.hostel-switch-btn:hover{background:rgba(255,255,255,0.07);color:#fff}
.hostel-switch-btn.current{color:var(--teal);font-weight:600}

/* Nav */
.sidebar-nav{flex:1;padding:14px 10px;overflow-y:auto}
.nav-label{font-size:0.58rem;font-weight:700;text-transform:uppercase;letter-spacing:0.14em;color:var(--gray-light);padding:0 10px;margin:14px 0 5px}
.nav-item{
  display:flex;align-items:center;gap:10px;
  padding:9px 12px;border-radius:10px;
  text-decoration:none;color:rgba(255,255,255,0.58);
  font-size:0.82rem;font-weight:500;
  transition:all 0.2s;margin-bottom:2px;position:relative;
}
.nav-item:hover{background:rgba(255,255,255,0.08);color:#fff}
.nav-item.active{background:rgba(42,191,191,0.18);color:var(--teal);font-weight:600}
.nav-item.active::before{content:'';position:absolute;left:0;top:6px;bottom:6px;width:3px;background:var(--teal);border-radius:0 3px 3px 0}
.nav-icon{font-size:1rem;width:20px;text-align:center;flex-shrink:0}

/* Sidebar footer */
.sidebar-footer{padding:12px;border-top:1px solid var(--border-dark)}
.sidebar-user{display:flex;align-items:center;gap:9px;padding:9px 11px;border-radius:10px;background:rgba(255,255,255,0.06);margin-bottom:8px}
.sidebar-avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--teal),var(--coral));display:flex;align-items:center;justify-content:center;font-size:0.82rem;font-weight:700;color:#fff;flex-shrink:0}
.sidebar-user-name{font-size:0.8rem;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.sidebar-user-role{font-size:0.65rem;color:var(--gray-light)}
.sidebar-logout{display:flex;align-items:center;justify-content:center;gap:7px;width:100%;padding:8px;border-radius:9px;background:rgba(255,107,71,0.1);border:1px solid rgba(255,107,71,0.18);color:var(--coral);font-family:'DM Sans',sans-serif;font-size:0.8rem;font-weight:600;cursor:pointer;transition:all 0.2s}
.sidebar-logout:hover{background:rgba(255,107,71,0.2)}

/* ══ MAIN ══ */
.main-wrapper{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}

/* Header */
.top-header{
  height:var(--header-h);
  background:rgba(253,248,242,0.96);backdrop-filter:blur(16px);
  border-bottom:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;
  padding:0 2rem;position:sticky;top:0;z-index:100;
  box-shadow:0 2px 12px var(--shadow);
}
.header-left{display:flex;align-items:center;gap:14px}
.header-burger{display:none;background:none;border:none;font-size:1.3rem;cursor:pointer;padding:4px}
.header-breadcrumb{font-size:0.78rem;color:var(--gray-light)}
.header-breadcrumb b{color:var(--charcoal);font-weight:600}
.header-title{font-family:'Fraunces',serif;font-size:1.2rem;font-weight:600;color:var(--charcoal)}
.header-right{display:flex;align-items:center;gap:10px}
.header-hostel{display:flex;align-items:center;gap:5px;background:rgba(42,191,191,0.1);border:1px solid rgba(42,191,191,0.22);border-radius:18px;padding:4px 12px;font-size:0.75rem;font-weight:600;color:var(--teal-dark)}
.header-notif{width:36px;height:36px;border-radius:50%;background:var(--cream);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:0.95rem;transition:all 0.2s;position:relative}
.header-notif:hover{background:var(--sand)}
.notif-dot{position:absolute;top:6px;right:7px;width:7px;height:7px;background:var(--coral);border-radius:50%;border:2px solid var(--cream)}
.header-avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--teal),var(--coral));display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;color:#fff;cursor:pointer;border:2px solid transparent;transition:border-color 0.2s}
.header-avatar:hover{border-color:var(--teal)}

/* ══ CONTENT ══ */
.content-area{flex:1;padding:2rem 2.5rem;background:var(--cream)}

/* ══ PAGE HEADER ══ */
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem}
.page-header h1{font-family:'Fraunces',serif;font-size:1.75rem;font-weight:600;color:var(--charcoal);margin-bottom:4px}
.page-header h1 em{font-style:italic;color:var(--teal)}
.page-header p{font-size:0.87rem;color:var(--gray)}
.page-actions{display:flex;gap:9px;align-items:center;flex-wrap:wrap}
.section-tag{display:inline-flex;align-items:center;gap:5px;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:var(--coral);margin-bottom:5px}
.section-tag::before{content:"";width:12px;height:2px;background:var(--coral);border-radius:2px;display:inline-block}

/* ══ BUTTONS ══ */
.btn{font-family:'DM Sans',sans-serif;border:none;border-radius:10px;cursor:pointer;transition:all 0.2s;font-weight:600;font-size:0.84rem;padding:9px 20px;display:inline-flex;align-items:center;gap:7px;text-decoration:none}
.btn-coral{background:var(--coral);color:#fff;box-shadow:0 4px 14px rgba(255,107,71,0.28)}
.btn-coral:hover{background:#e85535;transform:translateY(-1px)}
.btn-teal{background:var(--teal);color:#fff;box-shadow:0 4px 14px rgba(42,191,191,0.28)}
.btn-teal:hover{background:var(--teal-dark);transform:translateY(-1px)}
.btn-outline{background:transparent;color:var(--charcoal);border:1.5px solid var(--border)}
.btn-outline:hover{border-color:var(--teal);color:var(--teal-dark)}
.btn-danger{background:rgba(239,68,68,0.1);color:#DC2626;border:1px solid rgba(239,68,68,0.18)}
.btn-danger:hover{background:rgba(239,68,68,0.18)}
.btn-sm{padding:6px 14px;font-size:0.77rem;border-radius:8px}
.btn-xs{padding:4px 10px;font-size:0.72rem;border-radius:7px}

/* ══ CARDS ══ */
.card{background:var(--warm-white);border:1px solid var(--border);border-radius:18px;overflow:hidden;box-shadow:0 2px 12px var(--shadow);transition:box-shadow 0.2s}
.card:hover{box-shadow:0 4px 20px var(--shadow-strong)}
.card-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px}
.card-title{font-family:'Fraunces',serif;font-size:1rem;font-weight:600;color:var(--charcoal)}
.card-body{padding:20px}
.card-footer-bar{padding:12px 20px;border-top:1px solid var(--border);background:var(--cream)}

/* ══ STAT CARDS ══ */
.stats-row{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:2rem}
.stat-card{background:var(--warm-white);border:1px solid var(--border);border-radius:16px;padding:20px;display:flex;align-items:flex-start;gap:14px;transition:all 0.25s}
.stat-card:hover{transform:translateY(-3px);box-shadow:0 8px 24px var(--shadow-strong)}
.stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0}
.stat-icon.coral{background:rgba(255,107,71,0.12)}
.stat-icon.teal{background:rgba(42,191,191,0.12)}
.stat-icon.sand{background:var(--sand)}
.stat-icon.green{background:rgba(34,197,94,0.12)}
.stat-icon.red{background:rgba(239,68,68,0.1)}
.stat-value{font-family:'Fraunces',serif;font-size:1.8rem;font-weight:700;color:var(--charcoal);line-height:1}
.stat-label{font-size:0.77rem;color:var(--gray);margin-top:3px}
.stat-sub{font-size:0.72rem;color:var(--gray-light);margin-top:5px}
.stat-trend{font-size:0.72rem;margin-top:4px;font-weight:600}
.stat-trend.up{color:#22C55E}
.stat-trend.down{color:var(--coral)}

/* ══ TABLE ══ */
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
thead tr{border-bottom:1.5px solid var(--border)}
thead th{font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--gray-light);padding:11px 14px;text-align:left;white-space:nowrap}
tbody tr{border-bottom:1px solid var(--border);transition:background 0.15s}
tbody tr:last-child{border-bottom:none}
tbody tr:hover{background:var(--cream)}
tbody td{font-size:0.84rem;color:var(--charcoal);padding:12px 14px;vertical-align:middle}
.td-muted{color:var(--gray)}
.td-mono{font-family:monospace;font-size:0.8rem;color:var(--teal-dark)}

/* ══ BADGES ══ */
.badge{display:inline-flex;align-items:center;gap:4px;font-size:0.68rem;font-weight:700;padding:3px 9px;border-radius:20px}
.badge-coral{background:rgba(255,107,71,0.12);color:var(--coral)}
.badge-teal{background:rgba(42,191,191,0.12);color:var(--teal-dark)}
.badge-green{background:rgba(34,197,94,0.12);color:#16A34A}
.badge-gray{background:var(--sand);color:var(--gray)}
.badge-red{background:rgba(239,68,68,0.1);color:#DC2626}

/* ══ FORMS ══ */
.form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px}
.form-group{display:flex;flex-direction:column;gap:5px}
.form-group.full{grid-column:1/-1}
.form-label{font-size:0.72rem;font-weight:700;color:var(--gray);text-transform:uppercase;letter-spacing:0.06em}
.form-label .req{color:var(--coral)}
.form-control{font-family:'DM Sans',sans-serif;font-size:0.87rem;color:var(--charcoal);background:var(--cream);border:1.5px solid var(--border);border-radius:10px;padding:10px 13px;transition:border-color 0.2s,box-shadow 0.2s;outline:none}
.form-control:focus{border-color:var(--teal);box-shadow:0 0 0 3px rgba(42,191,191,0.1)}
.form-control.is-invalid{border-color:var(--coral)}
select.form-control{appearance:none;cursor:pointer;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236B6B6B' d='M6 8L1 3h10z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:32px}
textarea.form-control{resize:vertical;min-height:90px}
.form-hint{font-size:0.72rem;color:var(--gray-light);margin-top:2px}
.form-error{font-size:0.72rem;color:var(--coral);margin-top:2px}

/* ══ ALERTS ══ */
.alert{padding:11px 16px;border-radius:11px;font-size:0.84rem;margin-bottom:1.4rem;display:flex;align-items:center;gap:10px}
.alert-success{background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.22);color:#16A34A}
.alert-error{background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.18);color:#DC2626}
.alert-warning{background:rgba(234,179,8,0.1);border:1px solid rgba(234,179,8,0.22);color:#A16207}
.alert-info{background:rgba(42,191,191,0.1);border:1px solid rgba(42,191,191,0.22);color:var(--teal-dark)}

/* ══ EMPTY STATE ══ */
.empty-state{text-align:center;padding:60px 20px}
.empty-state-icon{font-size:2.8rem;margin-bottom:12px}
.empty-state h3{font-family:'Fraunces',serif;font-size:1.25rem;font-weight:600;color:var(--charcoal);margin-bottom:7px}
.empty-state p{font-size:0.87rem;color:var(--gray);max-width:300px;margin:0 auto 18px}

/* ══ TOAST ══ */
.toast-container{position:fixed;bottom:22px;right:22px;z-index:9999;display:flex;flex-direction:column;gap:9px}
.toast{background:var(--charcoal);color:#fff;padding:11px 16px;border-radius:13px;font-size:0.84rem;box-shadow:0 10px 32px rgba(0,0,0,0.22);display:flex;align-items:center;gap:9px;min-width:250px;animation:toastIn 0.3s ease}
@keyframes toastIn{from{opacity:0;transform:translateX(28px)}to{opacity:1;transform:translateX(0)}}
.toast.success{border-left:4px solid #22C55E}
.toast.error{border-left:4px solid var(--coral)}

/* ══ RESPONSIVE ══ */
@media(max-width:1024px){
  .sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}
  .main-wrapper{margin-left:0}.header-burger{display:block}
  .stats-row{grid-template-columns:1fr 1fr}
  .form-grid-3{grid-template-columns:1fr 1fr}
}
@media(max-width:640px){
  .content-area{padding:1.2rem}.stats-row{grid-template-columns:1fr}
  .form-grid-2,.form-grid-3{grid-template-columns:1fr}
  .page-header{flex-direction:column}.header-hostel{display:none}
}
</style>
@stack('styles')
</head>
<body>

{{-- ══ SIDEBAR ══ --}}
<aside class="sidebar" id="sidebar">

  {{-- Logo --}}
  <a href="{{ route('dashboard') }}" class="sidebar-logo">
    <div class="logo-dot"></div>
    <div class="logo-img-box">
      <img src="{{ asset('images/logo2.png') }}" alt="HostelFlow"
           onerror="this.style.display='none';document.querySelector('.logo-txt').style.display='block'">
      <span class="logo-txt">Hostel<span>Flow</span></span>
    </div>
  </a>

  {{-- Hostel actif + switcher --}}
  @php
    $owner       = auth('owner')->user();
    $allHostels  = $owner?->hostels ?? collect();
    $activeId    = session('selected_hostel_id');
    $activeHostel = $allHostels->firstWhere('id', $activeId) ?? $allHostels->first();
  @endphp

  @if($activeHostel)
  <div class="hostel-badge-wrap">
    <div class="hostel-badge">
      <div class="hostel-badge-label">Établissement actif</div>
      <div class="hostel-badge-name">
        <span>{{ Str::limit($activeHostel->name, 20) }}</span>
        <span>Actif</span>
      </div>
    </div>
  </div>

  @if($allHostels->count() > 1)
  <div class="hostel-switch-list">
    @foreach($allHostels as $h)
    <form method="POST" action="{{ route('hostel.switch', $h) }}">
      @csrf
      <button type="submit" class="hostel-switch-btn {{ $h->id == $activeHostel->id ? 'current' : '' }}">
        <span>🏨</span> {{ Str::limit($h->name, 22) }}
        @if($h->id == $activeHostel->id)<span style="margin-left:auto;font-size:0.6rem">✓</span>@endif
      </button>
    </form>
    @endforeach
  </div>
  @endif
  @endif

  {{-- Navigation --}}
  <nav class="sidebar-nav">
    <div class="nav-label">Principal</div>
    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <span class="nav-icon">🏠</span> Tableau de bord
    </a>
    <a href="{{ route('rooms.index') }}" class="nav-item {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
      <span class="nav-icon">🛏</span> Chambres
    </a>
    <a href="{{ route('beds.index') }}" class="nav-item {{ request()->routeIs('beds.*') ? 'active' : '' }}">
      <span class="nav-icon">🛌</span> Lits
    </a>
    <a href="{{ route('tent-spaces.index') }}" class="nav-item {{ request()->routeIs('tent-spaces.*') ? 'active' : '' }}">
      <span class="nav-icon">⛺</span> Espaces camping
    </a>

    <div class="nav-label">Tarification</div>
    <a href="{{ route('prices.index') }}" class="nav-item {{ request()->routeIs('prices.*') ? 'active' : '' }}">
      <span class="nav-icon">💶</span> Tarifs
    </a>
    <a href="{{ route('taxes.index') }}" class="nav-item {{ request()->routeIs('taxes.*') ? 'active' : '' }}">
      <span class="nav-icon">🧾</span> Taxes
    </a>
    <a href="{{ route('exchange-rates.index') }}" class="nav-item {{ request()->routeIs('exchange-rates.*') ? 'active' : '' }}">
      <span class="nav-icon">💱</span> Taux de change
    </a>

    <div class="nav-label">Gestion</div>
    <a href="{{ route('extras.index') }}" class="nav-item {{ request()->routeIs('extras.*') ? 'active' : '' }}">
      <span class="nav-icon">🎁</span> Extras & Stock
    </a>
    <a href="{{ route('inventory-blocks.index') }}" class="nav-item {{ request()->routeIs('inventory-blocks.*') ? 'active' : '' }}">
      <span class="nav-icon">🔒</span> Indisponibilités
    </a>
    <a href="{{ route('managers.index') }}" class="nav-item {{ request()->routeIs('managers.*') ? 'active' : '' }}">
      <span class="nav-icon">👥</span> Équipe
    </a>

    <div class="nav-label">Établissements</div>
    <a href="{{ route('hostels.index') }}" class="nav-item {{ request()->routeIs('hostels.*') ? 'active' : '' }}">
      <span class="nav-icon">🏨</span> Mes auberges
    </a>
  </nav>

  {{-- User --}}
  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="sidebar-avatar">
        {{ strtoupper(substr(auth('owner')->user()?->name ?? 'O', 0, 1)) }}
      </div>
      <div style="min-width:0">
        <div class="sidebar-user-name">{{ auth('owner')->user()?->name }}</div>
        <div class="sidebar-user-role">Propriétaire</div>
      </div>
    </div>
    <form method="POST" action="{{ route('owner.logout') }}">
      @csrf
      <button type="submit" class="sidebar-logout">⬅ Se déconnecter</button>
    </form>
  </div>
</aside>

{{-- ══ MAIN ══ --}}
<div class="main-wrapper">

  <header class="top-header">
    <div class="header-left">
      <button class="header-burger" id="burgerBtn" onclick="toggleSidebar()">☰</button>
      <div>
        <div class="header-breadcrumb">
          HostelFlow › <b>@yield('breadcrumb', 'Dashboard')</b>
        </div>
        <div class="header-title">@yield('page-title', 'Tableau de bord')</div>
      </div>
    </div>
    <div class="header-right">
      @if(isset($activeHostel) && $activeHostel)
        <div class="header-hostel">🏨 {{ Str::limit($activeHostel->name, 20) }}</div>
      @endif
      <div class="header-notif" title="Notifications">
        🔔<div class="notif-dot"></div>
      </div>
      <div class="header-avatar" title="{{ auth('owner')->user()?->name }}">
        {{ strtoupper(substr(auth('owner')->user()?->name ?? 'O', 0, 1)) }}
      </div>
    </div>
  </header>

  <main class="content-area">

    @if(session('success'))
      <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-error">
        ❌
        <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
      </div>
    @endif

    @yield('content')
  </main>
</div>

<div class="toast-container" id="toastContainer"></div>

<script>
function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open')}
function checkMobile(){
  const b=document.getElementById('burgerBtn');
  if(b) b.style.display=window.innerWidth<=1024?'flex':'none';
}
checkMobile(); window.addEventListener('resize',checkMobile);
document.addEventListener('click',e=>{
  const s=document.getElementById('sidebar');
  const b=document.getElementById('burgerBtn');
  if(s&&s.classList.contains('open')&&!s.contains(e.target)&&e.target!==b) s.classList.remove('open');
});
function showToast(msg,type='success'){
  const c=document.getElementById('toastContainer');
  const t=document.createElement('div');
  t.className=`toast ${type}`;
  t.innerHTML=`<span>${type==='success'?'✅':'❌'}</span><span>${msg}</span>`;
  c.appendChild(t);
  setTimeout(()=>{t.style.opacity='0';t.style.transition='all 0.3s';setTimeout(()=>t.remove(),300);},3500);
}
</script>
@stack('scripts')
</body>
</html>
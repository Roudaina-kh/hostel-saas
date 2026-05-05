<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Super Admin') — HostelFlow</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:#F1F5F9;color:#1E293B;display:flex;min-height:100vh}

/* ── Sidebar ── */
.sa-sidebar{
  width:240px;flex-shrink:0;
  background:linear-gradient(180deg,#1E1B4B 0%,#312E81 100%);
  display:flex;flex-direction:column;min-height:100vh;position:sticky;top:0;
}
.sa-logo{padding:24px 20px 20px;display:flex;align-items:center;gap:10px;
  border-bottom:1px solid rgba(255,255,255,0.08)}
.sa-logo img{height:36px;width:auto;object-fit:contain;filter:brightness(0) invert(1)}
.sa-logo-text{font-size:1.1rem;font-weight:800;color:#fff;letter-spacing:-0.3px}
.sa-logo-text span{color:#A78BFA}
.sa-role-badge{margin:12px 16px;background:rgba(167,139,250,0.15);border:1px solid rgba(167,139,250,0.3);
  border-radius:8px;padding:6px 12px;font-size:10px;font-weight:700;
  color:#C4B5FD;text-transform:uppercase;letter-spacing:0.1em;text-align:center}
<a href="{{ route('super-admin.managers.index') }}"
   class="sa-nav-item {{ request()->routeIs('super-admin.managers.*') ? 'active' : '' }}">
  <span class="sa-nav-icon">👔</span> Managers
</a>
.sa-nav{flex:1;padding:8px 12px;overflow-y:auto}
.sa-nav-section{font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;
  color:rgba(255,255,255,0.25);padding:12px 8px 4px}
.sa-nav-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;
  margin-bottom:2px;text-decoration:none;font-size:13.5px;font-weight:500;
  color:rgba(255,255,255,0.55);transition:all 0.2s;cursor:pointer;border:1px solid transparent}
.sa-nav-item:hover{background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.9)}
.sa-nav-item.active{background:rgba(167,139,250,0.18);border-color:rgba(167,139,250,0.3);
  color:#C4B5FD;font-weight:600}
.sa-nav-icon{width:30px;height:30px;border-radius:8px;background:rgba(255,255,255,0.05);
  display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.sa-nav-item.active .sa-nav-icon{background:rgba(167,139,250,0.2)}

.sa-logout{padding:12px 16px;border-top:1px solid rgba(255,255,255,0.08)}
.sa-logout form button{display:flex;align-items:center;gap:8px;width:100%;
  padding:9px 12px;border-radius:10px;background:rgba(239,68,68,0.1);
  border:1px solid rgba(239,68,68,0.2);cursor:pointer;transition:all 0.2s;
  font-size:13px;font-weight:600;color:#FCA5A5}
.sa-logout form button:hover{background:rgba(239,68,68,0.2);border-color:rgba(239,68,68,0.4)}

/* ── Main ── */
.sa-main{flex:1;display:flex;flex-direction:column;min-width:0}
.sa-topbar{background:#fff;border-bottom:1px solid #E2E8F0;padding:0 28px;height:60px;
  display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:10}
.sa-topbar-left{display:flex;flex-direction:column}
.sa-breadcrumb{font-size:11px;color:#94A3B8;font-weight:500;margin-bottom:2px}
.sa-page-title{font-size:16px;font-weight:700;color:#1E293B}
.sa-admin-info{display:flex;align-items:center;gap:10px}
.sa-admin-avatar{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#7C3AED,#4F46E5);
  display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700}
.sa-admin-name{font-size:13px;font-weight:600;color:#1E293B}
.sa-admin-role{font-size:10px;color:#7C3AED;font-weight:700;text-transform:uppercase}

.sa-content{padding:28px;flex:1}

/* ── Alerts ── */
.alert{padding:12px 16px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:20px;display:flex;align-items:center;gap:8px}
.alert-success{background:#D1FAE5;border:1px solid #6EE7B7;color:#065F46}
.alert-error{background:#FEE2E2;border:1px solid #FCA5A5;color:#DC2626}

/* ── Cards ── */
.sa-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:24px;margin-bottom:20px}
.sa-card-title{font-size:14px;font-weight:700;color:#1E293B;margin-bottom:16px;display:flex;align-items:center;gap:8px}

/* ── Stats grid ── */
.stats-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;margin-bottom:24px}
.stat-card{background:#fff;border:1px solid #E2E8F0;border-radius:14px;padding:20px;position:relative;overflow:hidden}
.stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px}
.stat-card.purple::before{background:linear-gradient(90deg,#7C3AED,#A78BFA)}
.stat-card.blue::before{background:linear-gradient(90deg,#1D4ED8,#60A5FA)}
.stat-card.green::before{background:linear-gradient(90deg,#059669,#34D399)}
.stat-card.orange::before{background:linear-gradient(90deg,#D97706,#FCD34D)}
.stat-label{font-size:11px;font-weight:600;color:#94A3B8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:8px}
.stat-value{font-size:28px;font-weight:800;color:#1E293B}
.stat-sub{font-size:11px;color:#94A3B8;margin-top:4px}

/* ── Table ── */
.sa-table-wrap{background:#fff;border:1px solid #E2E8F0;border-radius:14px;overflow:hidden}
.sa-table{width:100%;border-collapse:collapse;font-size:13px}
.sa-table th{padding:11px 16px;text-align:left;font-size:11px;font-weight:600;
  color:#64748B;border-bottom:1px solid #E2E8F0;background:#F8FAFC;text-transform:uppercase;letter-spacing:0.05em}
.sa-table td{padding:12px 16px;border-bottom:1px solid #F1F5F9;vertical-align:middle}
.sa-table tr:last-child td{border-bottom:none}
.sa-table tr:hover td{background:#FAFAFA}

/* ── Badges ── */
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600}
.badge-active{background:#D1FAE5;color:#059669}
.badge-inactive{background:#FEE2E2;color:#DC2626}
.badge-purple{background:#EDE9FE;color:#7C3AED}
.badge-blue{background:#DBEAFE;color:#1D4ED8}

/* ── Buttons ── */
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:10px;
  font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;border:none;transition:all 0.2s}
.btn-primary{background:linear-gradient(135deg,#7C3AED,#4F46E5);color:#fff;box-shadow:0 4px 12px rgba(124,58,237,0.3)}
.btn-primary:hover{transform:translateY(-1px);box-shadow:0 6px 16px rgba(124,58,237,0.4)}
.btn-danger{background:#FEE2E2;color:#DC2626;border:1px solid #FECACA}
.btn-danger:hover{background:#FECACA}
.btn-secondary{background:#F1F5F9;color:#475569;border:1px solid #E2E8F0}
.btn-secondary:hover{background:#E2E8F0}
.btn-sm{padding:5px 10px;font-size:11px;border-radius:7px}
.btn-warning{background:#FEF9C3;color:#CA8A04;border:1px solid #FDE68A}
.btn-warning:hover{background:#FDE68A}

/* ── Form ── */
.form-group{margin-bottom:18px}
.form-label{display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em}
.form-input,.form-select{width:100%;padding:10px 14px;border:1.5px solid #E2E8F0;border-radius:10px;
  font-family:'Inter',sans-serif;font-size:13px;color:#1E293B;background:#FAFAFA;outline:none;transition:border-color 0.2s}
.form-input:focus,.form-select:focus{border-color:#7C3AED;background:#fff;box-shadow:0 0 0 3px rgba(124,58,237,0.1)}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-error{font-size:11px;color:#DC2626;margin-top:4px}
</style>
</head>
<body>

<aside class="sa-sidebar">
  <div class="sa-logo">
    <img src="{{ asset('images/13.png') }}" alt="HostelFlow" onerror="this.style.display='none'">
    <span class="sa-logo-text">Hostel<span>Flow</span></span>
  </div>
  <div class="sa-role-badge">🛡 Super Administrateur</div>

  <nav class="sa-nav">
    <div class="sa-nav-section">Plateforme</div>
    <a href="{{ route('super-admin.dashboard') }}"
       class="sa-nav-item {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">
      <span class="sa-nav-icon">📊</span> Dashboard
    </a>

    <div class="sa-nav-section">Gestion</div>
    <a href="{{ route('super-admin.owners.index') }}"
       class="sa-nav-item {{ request()->routeIs('super-admin.owners.*') ? 'active' : '' }}">
      <span class="sa-nav-icon">👤</span> Propriétaires
    </a>
    <a href="{{ route('super-admin.hostels.index') }}"
       class="sa-nav-item {{ request()->routeIs('super-admin.hostels.*') ? 'active' : '' }}">
      <span class="sa-nav-icon">🏨</span> Hostels
    </a>
  </nav>

  <div class="sa-logout">
    <form method="POST" action="{{ route('super-admin.logout') }}">
      @csrf
      <button type="submit">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
        </svg>
        Déconnexion
      </button>
    </form>
  </div>
</aside>

<div class="sa-main">
  {{-- Topbar --}}
  <div class="sa-topbar">
    <div class="sa-topbar-left">
      <div class="sa-breadcrumb">Super Admin › @yield('breadcrumb', 'Dashboard')</div>
      <div class="sa-page-title">@yield('page-title', 'Dashboard')</div>
    </div>
    <div class="sa-admin-info">
      <div class="sa-admin-avatar">
        {{ strtoupper(substr(Auth::guard('super_admin')->user()->name, 0, 1)) }}
      </div>
      <div>
        <div class="sa-admin-name">{{ Auth::guard('super_admin')->user()->name }}</div>
        <div class="sa-admin-role">Super Admin</div>
      </div>
    </div>
  </div>

  {{-- Alerts --}}
  <div style="padding:0 28px;padding-top:20px">
    @if(session('success'))
      <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif
  </div>

  {{-- Content --}}
  <div class="sa-content">
    @yield('content')
  </div>
</div>

</body>
</html>
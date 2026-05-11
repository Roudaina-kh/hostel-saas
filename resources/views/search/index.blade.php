<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelFlow — Découvrez la Tunisie</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
    :root {
        --sand:    #F5EFE6;
        --sand2:   #EDE3D4;
        --terra:   #C8602A;
        --terra2:  #A84E20;
        --teal:    #1B6B6B;
        --teal2:   #134F4F;
        --night:   #1C1C24;
        --ink:     #2E2E3A;
        --gray:    #6B6B7A;
        --lgray:   #A0A0B0;
        --border:  #DDD6CA;
        --white:   #FEFCF9;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body { font-family: 'DM Sans', sans-serif; background: var(--white); color: var(--ink); overflow-x: hidden; }

    /* ── NAVBAR ── */
    nav {
        position: fixed; top: 0; left: 0; right: 0; z-index: 200;
        display: flex; align-items: center; justify-content: space-between;
        padding: 0 2.5rem; height: 68px;
        background: rgba(254,252,249,0.95); backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--border);
    }
    .nav-logo {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem; font-weight: 700; color: var(--ink);
        text-decoration: none; display: flex; align-items: center; gap: 10px;
    }
    .nav-logo img { height: 36px; width: auto; }
    .nav-links { display: flex; align-items: center; gap: 6px; list-style: none; }
    .nav-links a {
        font-size: 0.83rem; font-weight: 500; color: var(--gray);
        text-decoration: none; padding: 6px 14px; border-radius: 20px;
        transition: all 0.2s;
    }
    .nav-links a:hover { color: var(--ink); background: var(--sand); }
    .nav-links a.active { color: var(--terra); background: rgba(200,96,42,0.08); }
    .nav-actions { display: flex; gap: 10px; align-items: center; }
    .btn-nav {
        font-size: 0.82rem; font-weight: 600; padding: 8px 20px;
        border-radius: 24px; border: 1.5px solid var(--border);
        background: none; color: var(--ink); cursor: pointer;
        text-decoration: none; transition: all 0.2s;
    }
    .btn-nav:hover { border-color: var(--terra); color: var(--terra); }
    .btn-nav-primary {
        background: var(--terra); color: #fff; border-color: var(--terra);
        box-shadow: 0 4px 14px rgba(200,96,42,0.3);
    }
    .btn-nav-primary:hover { background: var(--terra2); color: #fff; }

    /* ── HERO ── */
    .hero {
        min-height: 100vh;
        background: linear-gradient(165deg, #1C1C24 0%, #2E3A35 40%, #1B6B6B 100%);
        position: relative; overflow: hidden;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        padding: 100px 2rem 60px;
    }
    .hero-pattern {
        position: absolute; inset: 0;
        background-image:
            radial-gradient(circle at 20% 50%, rgba(200,96,42,0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(27,107,107,0.2) 0%, transparent 40%),
            radial-gradient(circle at 60% 80%, rgba(254,252,249,0.03) 0%, transparent 30%);
        pointer-events: none;
    }
    .hero-dots {
        position: absolute; top: 80px; right: 6%;
        display: grid; grid-template-columns: repeat(6, 1fr); gap: 16px;
        opacity: 0.15;
    }
    .hero-dot { width: 4px; height: 4px; border-radius: 50%; background: #fff; }
    .hero-content { position: relative; z-index: 2; text-align: center; max-width: 760px; }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);
        border-radius: 24px; padding: 6px 18px;
        font-size: 0.75rem; font-weight: 600; color: rgba(255,255,255,0.7);
        text-transform: uppercase; letter-spacing: 0.12em;
        margin-bottom: 1.5rem;
        animation: fadeUp 0.7s 0.1s both;
    }
    .hero-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.8rem, 6vw, 5rem);
        font-weight: 700; color: #fff; line-height: 1.1;
        margin-bottom: 1.2rem;
        animation: fadeUp 0.7s 0.2s both;
    }
    .hero-title em { font-style: italic; color: #F5C896; }
    .hero-sub {
        font-size: 1.05rem; color: rgba(255,255,255,0.65);
        line-height: 1.75; max-width: 520px; margin: 0 auto 2.5rem;
        animation: fadeUp 0.7s 0.35s both;
    }

    /* ── SEARCH BOX ── */
    .search-box {
        background: var(--white); border-radius: 20px;
        padding: 10px; display: flex; align-items: center;
        max-width: 860px; width: 100%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
        animation: fadeUp 0.7s 0.5s both;
        position: relative;
    }
    .search-field {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 18px; flex: 1;
        border-right: 1px solid var(--border);
        cursor: pointer; border-radius: 12px; transition: background 0.2s;
    }
    .search-field:last-of-type { border-right: none; }
    .search-field:hover { background: var(--sand); }
    .search-icon { font-size: 1rem; color: var(--terra); flex-shrink: 0; }
    .sf-inner { display: flex; flex-direction: column; gap: 1px; min-width: 0; }
    .sf-label { font-size: 0.65rem; font-weight: 700; color: var(--lgray); text-transform: uppercase; letter-spacing: 0.07em; }
    .sf-input {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.88rem; font-weight: 500; color: var(--ink);
        background: none; border: none; outline: none; width: 100%;
    }
    .sf-input::placeholder { color: var(--lgray); font-weight: 400; }
    .search-btn {
        background: var(--terra); color: #fff; border: none; border-radius: 14px;
        padding: 13px 28px; font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem; font-weight: 700; cursor: pointer;
        display: flex; align-items: center; gap: 8px;
        transition: all 0.25s; white-space: nowrap;
        box-shadow: 0 4px 14px rgba(200,96,42,0.4);
        flex-shrink: 0;
    }
    .search-btn:hover { background: var(--terra2); transform: scale(1.02); }

    /* Autocomplete dropdown */
    .autocomplete-list {
        position: absolute; top: calc(100% + 8px); left: 10px; right: 10px;
        background: var(--white); border-radius: 16px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        border: 1px solid var(--border); overflow: hidden;
        z-index: 100; display: none;
    }
    .autocomplete-list.open { display: block; }
    .ac-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 18px; cursor: pointer; transition: background 0.15s;
        font-size: 0.88rem;
    }
    .ac-item:hover { background: var(--sand); }
    .ac-type {
        font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.06em; color: var(--lgray); margin-left: auto;
    }

    /* ── QUICK FILTERS ── */
    .quick-filters {
        display: flex; align-items: center; gap: 8px;
        margin-top: 1.4rem; flex-wrap: wrap; justify-content: center;
        animation: fadeUp 0.7s 0.65s both;
    }
    .qf-label { font-size: 0.78rem; color: rgba(255,255,255,0.5); font-weight: 500; }
    .qf-chip {
        font-size: 0.78rem; font-weight: 500;
        background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8);
        border: 1px solid rgba(255,255,255,0.15);
        padding: 5px 14px; border-radius: 20px; cursor: pointer;
        text-decoration: none; transition: all 0.2s;
    }
    .qf-chip:hover { background: rgba(255,255,255,0.2); color: #fff; }

    /* ── STATS ── */
    .hero-stats {
        display: flex; gap: 3rem; margin-top: 3rem;
        animation: fadeUp 0.7s 0.8s both;
    }
    .stat { text-align: center; }
    .stat-num {
        font-family: 'Playfair Display', serif;
        font-size: 2rem; font-weight: 700; color: #fff;
    }
    .stat-num span { color: #F5C896; }
    .stat-desc { font-size: 0.72rem; color: rgba(255,255,255,0.5); font-weight: 500; text-transform: uppercase; letter-spacing: 0.08em; }

    /* ── SECTIONS COMMUNES ── */
    section { padding: 80px 3rem; }
    .section-tag {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.12em; color: var(--terra); margin-bottom: 0.8rem;
    }
    .section-tag::before { content: ""; width: 18px; height: 2px; background: var(--terra); border-radius: 2px; }
    h2 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.8rem, 3.5vw, 2.6rem);
        font-weight: 700; line-height: 1.2; color: var(--ink); margin-bottom: 0.5rem;
    }
    h2 em { font-style: italic; color: var(--teal); }
    .section-desc { font-size: 0.95rem; color: var(--gray); max-width: 460px; line-height: 1.75; margin-bottom: 2.5rem; }

    /* ── HOSTEL CARDS ── */
    .hostels-section { background: var(--sand); }
    .hostels-inner { max-width: 1200px; margin: 0 auto; }
    .hostels-header {
        display: flex; justify-content: space-between; align-items: flex-end;
        margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;
    }
    .link-all {
        font-size: 0.85rem; font-weight: 700; color: var(--terra);
        text-decoration: none; display: flex; align-items: center; gap: 4px;
        transition: gap 0.2s;
    }
    .link-all:hover { gap: 10px; }
    .hostels-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;
    }
    .hostel-card {
        background: var(--white); border-radius: 22px;
        border: 1px solid var(--border); overflow: hidden;
        cursor: pointer; transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1);
        opacity: 0; transform: translateY(24px);
    }
    .hostel-card.visible { animation: fadeUp 0.5s forwards; }
    .hostel-card:hover { box-shadow: 0 20px 48px rgba(28,28,36,0.12); transform: translateY(-6px); }
    .card-img {
        width: 100%; height: 200px; overflow: hidden; position: relative;
        background: linear-gradient(135deg, var(--teal) 0%, var(--night) 100%);
    }
    .card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
    .hostel-card:hover .card-img img { transform: scale(1.08); }
    .card-badge {
        position: absolute; top: 12px; left: 12px;
        background: var(--terra); color: #fff;
        font-size: 0.65rem; font-weight: 700;
        padding: 4px 10px; border-radius: 20px;
        text-transform: uppercase; letter-spacing: 0.05em;
    }
    .card-badge.teal { background: var(--teal); }
    .card-placeholder {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem; opacity: 0.3;
    }
    .card-body { padding: 18px 20px 22px; }
    .card-region { font-size: 0.68rem; font-weight: 700; color: var(--teal); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 4px; }
    .card-name { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 600; color: var(--ink); margin-bottom: 10px; }
    .card-meta { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
    .card-rating { display: flex; align-items: center; gap: 4px; font-size: 0.8rem; font-weight: 600; color: var(--ink); }
    .star { color: #E8A020; }
    .card-type {
        font-size: 0.7rem; font-weight: 600; color: var(--gray);
        background: var(--sand); padding: 3px 10px; border-radius: 12px;
    }
    .card-footer { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
    .card-price { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: var(--ink); }
    .card-price span { font-family: 'DM Sans', sans-serif; font-size: 0.72rem; font-weight: 400; color: var(--lgray); }
    .btn-book {
        font-family: 'DM Sans', sans-serif; font-size: 0.78rem; font-weight: 700;
        background: var(--terra); color: #fff; border: none;
        padding: 9px 18px; border-radius: 18px; cursor: pointer;
        transition: all 0.2s; text-decoration: none;
    }
    .btn-book:hover { background: var(--terra2); transform: scale(1.04); }
    .card-no-price { font-size: 0.8rem; color: var(--lgray); font-style: italic; }

    /* ── SEARCH RESULTS ── */
    .results-section { background: var(--white); min-height: 60vh; }
    .results-inner { max-width: 1200px; margin: 0 auto; }
    .results-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;
    }
    .results-count { font-size: 0.9rem; color: var(--gray); }
    .results-count strong { color: var(--ink); }
    .sort-bar { display: flex; gap: 8px; flex-wrap: wrap; }
    .sort-btn {
        font-size: 0.78rem; font-weight: 600; color: var(--gray);
        background: var(--sand); border: 1.5px solid transparent;
        padding: 6px 16px; border-radius: 20px; cursor: pointer;
        transition: all 0.2s; text-decoration: none;
    }
    .sort-btn:hover, .sort-btn.active {
        border-color: var(--terra); color: var(--terra); background: rgba(200,96,42,0.06);
    }

    /* Filter sidebar + grid layout */
    .results-layout { display: grid; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start; }
    .filters-panel {
        background: var(--sand); border-radius: 20px;
        padding: 24px; position: sticky; top: 88px;
        border: 1px solid var(--border);
    }
    .filter-title { font-weight: 700; color: var(--ink); font-size: 0.85rem; margin-bottom: 1rem; }
    .filter-group { margin-bottom: 1.5rem; }
    .filter-group-label {
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.08em; color: var(--lgray); margin-bottom: 0.6rem;
    }
    .filter-option {
        display: flex; align-items: center; gap: 8px;
        font-size: 0.85rem; color: var(--ink); cursor: pointer;
        padding: 5px 0; transition: color 0.15s;
    }
    .filter-option:hover { color: var(--terra); }
    .filter-option input { accent-color: var(--terra); }
    .price-range { display: flex; gap: 8px; }
    .price-input {
        flex: 1; border: 1.5px solid var(--border); border-radius: 10px;
        padding: 7px 10px; font-size: 0.83rem; font-family: 'DM Sans', sans-serif;
        background: var(--white); color: var(--ink); outline: none;
        transition: border-color 0.2s;
    }
    .price-input:focus { border-color: var(--terra); }
    .filter-apply {
        width: 100%; padding: 10px; border: none; border-radius: 12px;
        background: var(--terra); color: #fff; font-family: 'DM Sans', sans-serif;
        font-size: 0.85rem; font-weight: 700; cursor: pointer;
        transition: background 0.2s; margin-top: 0.5rem;
    }
    .filter-apply:hover { background: var(--terra2); }

    /* Empty state */
    .empty-state {
        text-align: center; padding: 80px 20px; color: var(--gray);
    }
    .empty-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.4; }
    .empty-title { font-family: 'Playfair Display', serif; font-size: 1.6rem; color: var(--ink); margin-bottom: 0.5rem; }

    /* Pagination */
    .pagination { display: flex; justify-content: center; gap: 6px; margin-top: 2.5rem; flex-wrap: wrap; }
    .pagination a, .pagination span {
        display: inline-flex; align-items: center; justify-content: center;
        width: 38px; height: 38px; border-radius: 50%;
        font-size: 0.85rem; font-weight: 600;
        text-decoration: none; color: var(--gray);
        border: 1.5px solid var(--border); transition: all 0.2s;
    }
    .pagination a:hover { border-color: var(--terra); color: var(--terra); }
    .pagination .active span, .pagination [aria-current="page"] span {
        background: var(--terra); color: #fff; border-color: var(--terra);
    }

    /* ── FOOTER ── */
/* ── FOOTER (terra plein) ── */
footer {
    background: var(--terra);
    color: rgba(254, 252, 249, 0.85);
    padding: 60px 3rem 30px;
    position: relative;
    overflow: hidden;
}
footer::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(254,252,249,0.35), transparent);
}
footer::after {
    content: '';
    position: absolute;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(254,252,249,0.08) 0%, transparent 70%);
    top: -150px; right: -100px;
    pointer-events: none;
}
.footer-inner { max-width: 1100px; margin: 0 auto; position: relative; z-index: 1; }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 3rem; margin-bottom: 2.5rem; }
.footer-logo {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem; font-weight: 700;
    color: var(--white);
    margin-bottom: 10px;
    display: flex; align-items: center; gap: 10px;
    letter-spacing: -0.5px;
}
.footer-logo img { height: 36px; filter: brightness(0) invert(1); }
.footer-tagline {
    font-size: 0.85rem; line-height: 1.75;
    max-width: 280px;
    color: rgba(254, 252, 249, 0.75);
}
.footer-col-title {
    font-weight: 700; font-size: 0.78rem;
    text-transform: uppercase; letter-spacing: 0.12em;
    color: var(--white);
    margin-bottom: 1.1rem;
}
.footer-links { list-style: none; display: flex; flex-direction: column; gap: 8px; }
.footer-links a {
    font-size: 0.85rem;
    color: rgba(254, 252, 249, 0.75);
    text-decoration: none;
    transition: color 0.2s, transform 0.2s;
    display: inline-block;
}
.footer-links a:hover {
    color: #F5C896;
    transform: translateX(4px);
}
.footer-bottom {
    border-top: 1px solid rgba(254, 252, 249, 0.18);
    padding-top: 20px;
    display: flex; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
}
.footer-copy {
    font-size: 0.78rem;
    color: rgba(254, 252, 249, 0.6);
}

    /* ── ANIMATIONS ── */
    @keyframes fadeUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
    .reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.6s ease, transform 0.6s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>

{{-- ── NAVBAR ── --}}
<nav>
    <a href="{{ url('/') }}" class="nav-logo">
        <img src="{{ asset('images/13.png') }}" alt="HostelFlow" onerror="this.style.display='none'">
        HostelFlow
    </a>
    <ul class="nav-links">
        <li><a href="{{ url('/') }}">Accueil</a></li>
        <li><a href="{{ route('search.index') }}" class="active">Explorer</a></li>
        <li><a href="#">Blog</a></li>
    </ul>
    <div class="nav-actions">
        <a href="{{ route('owner.login') }}" class="btn-nav">Connexion</a>
        <a href="{{ route('register') }}" class="btn-nav btn-nav-primary">Publier mon hostel</a>
    </div>
</nav>

{{-- ── HERO ── --}}
<section class="hero">
    <div class="hero-pattern"></div>
    <div class="hero-dots">@for($i=0;$i<30;$i++)<div class="hero-dot"></div>@endfor</div>

    <div class="hero-content">
        <div class="hero-eyebrow">✦ Hébergements authentiques en Tunisie</div>
        <h1 class="hero-title">
            Découvrez des<br><em>adresses uniques</em><br>à travers la Tunisie
        </h1>
        <p class="hero-sub">
            Hostels de charme, campings sous les étoiles, bivouacs dans le désert.
            Trouvez l'hébergement qui correspond à votre aventure.
        </p>

        {{-- ── BARRE DE RECHERCHE ── --}}
        <form method="GET" action="{{ route('search.index') }}" id="searchForm">
        <div class="search-box">
            {{-- Destination --}}
            <div class="search-field" id="regionField">
                <span class="search-icon">📍</span>
                <div class="sf-inner">
                    <span class="sf-label">Destination</span>
                    <input class="sf-input" type="text" id="regionInput"
                           placeholder="Tunis, Djerba, Tozeur…"
                           value="{{ $params->regionSlug ? ucfirst($params->regionSlug) : '' }}"
                           autocomplete="off">
                    <input type="hidden" name="region" id="regionHidden" value="{{ $params->regionSlug ?? '' }}">
                </div>
                {{-- Autocomplete --}}
                <div class="autocomplete-list" id="autocompleteList"></div>
            </div>

            {{-- Check-in --}}
            <div class="search-field">
                <span class="search-icon">📅</span>
                <div class="sf-inner">
                    <span class="sf-label">Arrivée</span>
                    <input class="sf-input" type="date" name="check_in"
                           value="{{ $params->checkIn ?? '' }}"
                           min="{{ date('Y-m-d') }}">
                </div>
            </div>

            {{-- Check-out --}}
            <div class="search-field">
                <span class="search-icon">📅</span>
                <div class="sf-inner">
                    <span class="sf-label">Départ</span>
                    <input class="sf-input" type="date" name="check_out"
                           value="{{ $params->checkOut ?? '' }}">
                </div>
            </div>

            {{-- Voyageurs --}}
            <div class="search-field" style="border-right:none">
                <span class="search-icon">👥</span>
                <div class="sf-inner">
                    <span class="sf-label">Voyageurs</span>
                    <input class="sf-input" type="number" name="guests"
                           min="1" max="20" value="{{ $params->guests }}" placeholder="2">
                </div>
            </div>

            <button type="submit" class="search-btn">🔍 Rechercher</button>
        </div>
        </form>

        {{-- Quick filters --}}
        <div class="quick-filters">
            <span class="qf-label">Populaire :</span>
            <a href="{{ route('search.index', ['subtypes' => ['private']]) }}" class="qf-chip">🛏 Chambres privées</a>
            <a href="{{ route('search.index', ['subtypes' => ['dormitory']]) }}" class="qf-chip">🛌 Dortoirs</a>
            <a href="{{ route('search.index', ['subtypes' => ['tent']]) }}" class="qf-chip">🏕 Tentes</a>
            <a href="{{ route('search.index', ['region' => 'djerba']) }}" class="qf-chip">🌊 Djerba</a>
            <a href="{{ route('search.index', ['region' => 'tozeur']) }}" class="qf-chip">🏜 Tozeur</a>
        </div>

        <div class="hero-stats">
            <div class="stat"><div class="stat-num">61<span>+</span></div><div class="stat-desc">Destinations</div></div>
            <div class="stat"><div class="stat-num">24<span>gov</span></div><div class="stat-desc">Gouvernorats</div></div>
            <div class="stat"><div class="stat-num">100<span>%</span></div><div class="stat-desc">Authentique</div></div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════ --}}
{{-- SECTION RÉSULTATS DE RECHERCHE                        --}}
{{-- ══════════════════════════════════════════════════════ --}}
@if($result)
<section class="results-section">
    <div class="results-inner">

        {{-- Header résultats --}}
        <div class="results-header reveal">
            <div>
                <div class="section-tag">Résultats</div>
                <h2>{{ $result->total() }} hébergement{{ $result->total() > 1 ? 's' : '' }} <em>trouvé{{ $result->total() > 1 ? 's' : '' }}</em></h2>
                @if($params->regionSlug)
                    <p class="section-desc">Dans la région : <strong>{{ ucfirst(str_replace('-', ' ', $params->regionSlug)) }}</strong></p>
                @endif
            </div>
            <div class="sort-bar">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'popularity']) }}"
                   class="sort-btn {{ $params->sortBy === 'popularity' ? 'active' : '' }}">⭐ Popularité</a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}"
                   class="sort-btn {{ $params->sortBy === 'price_asc' ? 'active' : '' }}">💰 Prix ↑</a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}"
                   class="sort-btn {{ $params->sortBy === 'price_desc' ? 'active' : '' }}">💰 Prix ↓</a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}"
                   class="sort-btn {{ $params->sortBy === 'rating' ? 'active' : '' }}">🏆 Note</a>
            </div>
        </div>

        @if($result->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <div class="empty-title">Aucun hébergement disponible</div>
                <p>Essayez d'autres dates ou une autre destination.</p>
            </div>
        @else
            <div class="results-layout">

                {{-- ═══ FILTRES (NOUVEAU) ═══ --}}
                <aside class="filters-panel reveal">
                    <div class="filter-title">🎛 Filtres</div>

                    <form method="GET" action="{{ route('search.index') }}" id="filterForm">
                        {{-- Préserver les autres params actifs --}}
                        <input type="hidden" name="region"    value="{{ $params->regionSlug }}">
                        <input type="hidden" name="check_in"  value="{{ $params->checkIn }}">
                        <input type="hidden" name="check_out" value="{{ $params->checkOut }}">
                        <input type="hidden" name="guests"    value="{{ $params->guests }}">
                        <input type="hidden" name="sort"      value="{{ $params->sortBy }}">

                        {{-- ── HOSTELS ── --}}
                        <div class="filter-group">
                            <div class="filter-group-label">🏨 Hostels</div>

                            <label class="filter-option">
                                <input type="checkbox" name="subtypes[]" value="private"
                                       {{ in_array('private', $params->subtypes ?? []) ? 'checked' : '' }}>
                                Chambres privées
                            </label>

                            <label class="filter-option">
                                <input type="checkbox" name="subtypes[]" value="dormitory"
                                       {{ in_array('dormitory', $params->subtypes ?? []) ? 'checked' : '' }}>
                                Chambres dortoir
                            </label>

                            <div style="margin-left:22px; margin-top:6px; margin-bottom:8px">
                                <div class="filter-group-label" style="margin-bottom:4px">Capacité min. (dortoir)</div>
                                <select name="dorm_min_capacity" class="price-input" style="width:100%; cursor:pointer">
                                    <option value="">— Indifférent —</option>
                                    @foreach([2,4,6,8,10,12] as $cap)
                                        <option value="{{ $cap }}" {{ ($params->dormMinCapacity ?? null) == $cap ? 'selected' : '' }}>
                                            {{ $cap }} personnes ou +
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- ── CAMPING ── --}}
                        <div class="filter-group">
                            <div class="filter-group-label">🏕 Camping</div>

                            <label class="filter-option">
                                <input type="checkbox" name="subtypes[]" value="tent"
                                       {{ in_array('tent', $params->subtypes ?? []) ? 'checked' : '' }}>
                                Emplacements pour tentes
                            </label>

                            <div style="margin-left:22px; margin-top:6px">
                                <div class="filter-group-label" style="margin-bottom:4px">Capacité min. (tente)</div>
                                <select name="tent_min_capacity" class="price-input" style="width:100%; cursor:pointer">
                                    <option value="">— Indifférent —</option>
                                    @foreach([2,3,4,6,8] as $cap)
                                        <option value="{{ $cap }}" {{ ($params->tentMinCapacity ?? null) == $cap ? 'selected' : '' }}>
                                            {{ $cap }} personnes ou +
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- ── PRIX ── --}}
                        <div class="filter-group">
                            <div class="filter-group-label">💰 Prix / nuit (TND)</div>
                            <div class="price-range">
                                <input type="number" class="price-input" name="min_price"
                                       placeholder="Min" value="{{ $params->minPrice }}" min="0">
                                <input type="number" class="price-input" name="max_price"
                                       placeholder="Max" value="{{ $params->maxPrice }}" min="0">
                            </div>
                        </div>

                        <button type="submit" class="filter-apply">Appliquer les filtres</button>

                        <a href="{{ route('search.index', ['region' => $params->regionSlug]) }}"
                           style="display:block; text-align:center; margin-top:10px;
                                  color:var(--gray); font-size:0.78rem; text-decoration:none">
                            ↺ Réinitialiser les filtres
                        </a>
                    </form>
                </aside>

                {{-- ═══ GRILLE RÉSULTATS ═══ --}}
                <div>
                    <div class="hostels-grid">
                        @foreach($result->hostels as $hostel)
                            @include('search._hostel_card', ['hostel' => $hostel])
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="pagination">
                        {{ $result->hostels->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

{{-- ══════════════════════════════════════════════════════ --}}
{{-- PAGE D'ACCUEIL (pas de recherche)                     --}}
{{-- ══════════════════════════════════════════════════════ --}}
@else

    {{-- Hébergements populaires --}}
    @if($popular && $popular->count() > 0)
    <section class="hostels-section">
        <div class="hostels-inner">
            <div class="hostels-header reveal">
                <div>
                    <div class="section-tag">À la une</div>
                    <h2>Hébergements <em>populaires</em></h2>
                    <p class="section-desc">Les meilleures adresses sélectionnées par notre communauté.</p>
                </div>
                <a href="{{ route('search.index') }}" class="link-all">Voir tout →</a>
            </div>
            <div class="hostels-grid">
                @foreach($popular as $hostel)
                    @include('search._hostel_card', ['hostel' => $hostel])
                @endforeach
            </div>
        </div>
    </section>
    @else
    <section class="hostels-section">
        <div class="hostels-inner">
            <div class="empty-state">
                <div class="empty-icon">🏨</div>
                <div class="empty-title">Aucun hébergement pour le moment</div>
                <p>Les propriétaires peuvent s'inscrire et publier leurs hostels.</p>
                <a href="{{ route('register') }}" class="btn-book" style="display:inline-block;margin-top:1.5rem">Publier mon hostel</a>
            </div>
        </div>
    </section>
    @endif
@endif

{{-- ── FOOTER ── --}}
<footer>
    <div class="footer-inner">
        <div class="footer-grid">
            <div>
                <div class="footer-logo">
                    <img src="{{ asset('images/13.png') }}" alt="HostelFlow" onerror="this.style.display='none'">
                    HostelFlow
                </div>
                <p class="footer-tagline">La plateforme de référence pour les hébergements authentiques en Tunisie. Hostels, campings, bivouacs.</p>
            </div>
            <div>
                <div class="footer-col-title">Explorer</div>
                <ul class="footer-links">
                    <li><a href="{{ route('search.index') }}">Tous les hébergements</a></li>
                    <li><a href="{{ route('search.index', ['subtypes' => ['private']]) }}">Chambres privées</a></li>
                    <li><a href="{{ route('search.index', ['subtypes' => ['dormitory']]) }}">Dortoirs</a></li>
                    <li><a href="{{ route('search.index', ['subtypes' => ['tent']]) }}">Tentes</a></li>
                    <li><a href="{{ route('search.index', ['region' => 'tunis']) }}">Tunis</a></li>
                    <li><a href="{{ route('search.index', ['region' => 'djerba']) }}">Djerba</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Espace pro</div>
                <ul class="footer-links">
                    <li><a href="{{ route('owner.login') }}">Connexion propriétaire</a></li>
                    <li><a href="{{ route('register') }}">Publier mon hostel</a></li>
                    <li><a href="{{ route('user.login') }}">Connexion équipe</a></li>
                    <li><a href="{{ route('super-admin.login') }}" style="color:rgba(254,252,249,0.45);font-size:0.78rem">🛡 Admin</a></li>                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-copy">© {{ date('Y') }} HostelFlow. Tous droits réservés.</div>
        </div>
    </div>
</footer>

<script>
// ── Autocomplete régions ─────────────────────────────────────────────────────
const regionInput  = document.getElementById('regionInput');
const regionHidden = document.getElementById('regionHidden');
const acList       = document.getElementById('autocompleteList');
let acTimer = null;

regionInput.addEventListener('input', function() {
    clearTimeout(acTimer);
    regionHidden.value = '';
    const q = this.value.trim();
    if (q.length < 1) { acList.classList.remove('open'); return; }
    acTimer = setTimeout(() => fetchRegions(q), 250);
});

regionInput.addEventListener('focus', function() {
    if (this.value.trim()) fetchRegions(this.value.trim());
});

function fetchRegions(q) {
    fetch(`{{ route('search.regions') }}?q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(data => {
            if (!data.length) { acList.classList.remove('open'); return; }
            acList.innerHTML = data.map(r => `
                <div class="ac-item" data-slug="${r.slug}" data-name="${r.name}">
                    <span>📍 ${r.name}</span>
                    <span class="ac-type">${r.type}</span>
                </div>
            `).join('');
            acList.classList.add('open');
            acList.querySelectorAll('.ac-item').forEach(item => {
                item.addEventListener('click', () => {
                    regionInput.value  = item.dataset.name;
                    regionHidden.value = item.dataset.slug;
                    acList.classList.remove('open');
                });
            });
        });
}

// FIX : avant submit, si pas de slug → chercher par nom
document.getElementById('searchForm').addEventListener('submit', async function(e) {
    const text = regionInput.value.trim();
    if (text && !regionHidden.value) {
        e.preventDefault();
        try {
            const res  = await fetch(`{{ route('search.regions') }}?q=${encodeURIComponent(text)}`);
            const data = await res.json();
            if (data.length > 0) {
                const exact = data.find(r => r.name.toLowerCase() === text.toLowerCase());
                regionHidden.value = (exact || data[0]).slug;
                regionInput.value  = (exact || data[0]).name;
            } else {
                regionHidden.value = '';
            }
        } catch(err) {
            regionHidden.value = '';
        }
        this.submit();
    }
});

document.addEventListener('click', e => {
    if (!e.target.closest('#regionField')) acList.classList.remove('open');
});

// ── Scroll reveal ────────────────────────────────────────────────────────────
const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const idx = Array.from(entry.target.parentElement?.children || []).indexOf(entry.target);
            setTimeout(() => entry.target.classList.add('visible'), idx * 80);
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll('.reveal, .hostel-card, .region-card').forEach(el => observer.observe(el));
</script>

</body>
</html>
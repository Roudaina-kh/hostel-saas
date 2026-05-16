<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $hostel->name }} — HostelFlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
    :root {
        --sand: #F5EFE6; --sand2: #EDE3D4; --terra: #C8602A; --terra2: #A84E20;
        --teal: #1B6B6B; --night: #1C1C24; --ink: #2E2E3A;
        --gray: #6B6B7A; --lgray: #A0A0B0; --border: #DDD6CA; --white: #FEFCF9;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DM Sans', sans-serif; background: var(--white); color: var(--ink); }

    nav {
        position: fixed; top: 0; left: 0; right: 0; z-index: 200;
        display: flex; align-items: center; justify-content: space-between;
        padding: 0 2.5rem; height: 68px;
        background: rgba(254,252,249,0.97); backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--border);
    }
    .nav-logo { font-family: 'Playfair Display', serif; font-size: 1.3rem; font-weight: 700; color: var(--ink); text-decoration: none; display: flex; align-items: center; gap: 10px; }
    .nav-logo img { height: 34px; }
    .back-link { font-size: 0.85rem; color: var(--terra); text-decoration: none; display: flex; align-items: center; gap: 6px; font-weight: 600; }
    .back-link:hover { text-decoration: underline; }

    .hostel-hero {
        margin-top: 68px; height: 420px;
        background: linear-gradient(135deg, var(--teal) 0%, var(--night) 100%);
        position: relative; overflow: hidden;
    }
    .hostel-hero img { width: 100%; height: 100%; object-fit: cover; }
    .hostel-hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(28,28,36,0.75) 0%, transparent 55%);
    }
    .hostel-hero-info {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 2rem 3rem; color: #fff;
        display: flex; justify-content: space-between; align-items: flex-end;
    }
    .hostel-type {
        display: inline-block; background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25); border-radius: 20px;
        padding: 4px 14px; font-size: 0.72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px;
    }
    .hostel-name { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 700; line-height: 1.15; }
    .hostel-location { font-size: 0.9rem; color: rgba(255,255,255,0.75); margin-top: 6px; display: flex; align-items: center; gap: 6px; }
    .hostel-rating-badge {
        background: #fff; color: var(--ink); border-radius: 14px;
        padding: 10px 18px; text-align: center; flex-shrink: 0;
    }
    .rating-num { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: var(--ink); }
    .rating-stars { color: #E8A020; font-size: 0.75rem; }
    .rating-count { font-size: 0.7rem; color: var(--gray); margin-top: 2px; }

    .page-content { max-width: 1100px; margin: 0 auto; padding: 2.5rem 3rem; display: grid; grid-template-columns: 1fr 340px; gap: 2.5rem; align-items: start; }

    .content-section { background: var(--white); border: 1px solid var(--border); border-radius: 20px; padding: 24px 28px; margin-bottom: 1.5rem; }
    .cs-title { font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 600; color: var(--ink); margin-bottom: 1rem; padding-bottom: 12px; border-bottom: 1px solid var(--border); }
    .description { font-size: 0.92rem; color: var(--gray); line-height: 1.8; }
    .rooms-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .room-item {
        background: var(--sand); border-radius: 14px; padding: 16px;
        border: 1.5px solid transparent; transition: border-color 0.2s;
    }
    .room-item:hover { border-color: var(--terra); }
    .room-name { font-weight: 700; font-size: 0.9rem; color: var(--ink); margin-bottom: 4px; }
    .room-meta { font-size: 0.78rem; color: var(--gray); }
    .room-beds { font-size: 0.72rem; color: var(--lgray); margin-top: 6px; display: flex; flex-wrap: wrap; gap: 4px; }
    .bed-chip { background: var(--white); border: 1px solid var(--border); border-radius: 8px; padding: 2px 8px; }

    .sidebar-card {
        background: var(--white); border: 1.5px solid var(--border);
        border-radius: 20px; padding: 24px; position: sticky; top: 88px;
        box-shadow: 0 8px 32px rgba(28,28,36,0.08);
    }
    .price-display { text-align: center; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border); }
    .price-from { font-size: 0.72rem; color: var(--lgray); text-transform: uppercase; letter-spacing: 0.06em; }
    .price-amount { font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 700; color: var(--ink); }
    .price-unit { font-size: 0.8rem; color: var(--lgray); }
    .avail-status { border-radius: 12px; padding: 12px 16px; font-size: 0.85rem; font-weight: 600; text-align: center; margin-bottom: 1.5rem; }
    .avail-ok   { background: rgba(27,107,107,0.1); color: var(--teal); }
    .avail-low  { background: rgba(200,96,42,0.1);  color: var(--terra); }
    .avail-full { background: rgba(200,50,50,0.1);  color: #c83232; }
    .booking-form { display: flex; flex-direction: column; gap: 12px; }
    .form-group { display: flex; flex-direction: column; gap: 4px; }
    .form-label { font-size: 0.72rem; font-weight: 700; color: var(--lgray); text-transform: uppercase; letter-spacing: 0.06em; }
    .form-input {
        border: 1.5px solid var(--border); border-radius: 12px;
        padding: 10px 14px; font-size: 0.88rem; font-family: 'DM Sans', sans-serif;
        color: var(--ink); background: var(--sand); outline: none; transition: border-color 0.2s;
    }
    .form-input:focus { border-color: var(--terra); background: var(--white); }
    .btn-reserve {
        width: 100%; padding: 14px; border: none; border-radius: 14px;
        background: var(--terra); color: #fff; font-family: 'DM Sans', sans-serif;
        font-size: 0.95rem; font-weight: 700; cursor: pointer;
        transition: all 0.25s; box-shadow: 0 6px 20px rgba(200,96,42,0.35);
    }
    .btn-reserve:hover { background: var(--terra2); transform: translateY(-2px); box-shadow: 0 10px 28px rgba(200,96,42,0.4); }
    .hostel-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .info-item { background: var(--sand); border-radius: 12px; padding: 12px 14px; }
    .info-label { font-size: 0.68rem; font-weight: 700; color: var(--lgray); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 3px; }
    .info-value { font-size: 0.88rem; font-weight: 600; color: var(--ink); }
    </style>
</head>
<body>

<nav>
    <a href="{{ url('/') }}" class="nav-logo">
        <img src="{{ asset('images/13.png') }}" alt="HostelFlow" onerror="this.style.display='none'">
        HostelFlow
    </a>
    <a href="{{ route('search.index') }}" class="back-link">← Retour aux résultats</a>
</nav>

{{-- ── Hero ── --}}
@php
    /*
     * Storage Resolution Strategy — même logique que _hostel_card.blade.php :
     *   "images/..."  → asset public (seeds DemoHostelImagesSeeder)
     *   autre         → storage symlink (uploads dynamiques owner)
     */
    $heroCoverUrl = null;
    if ($hostel->cover_image) {
        $heroCoverUrl = str_starts_with($hostel->cover_image, 'images/')
            ? asset($hostel->cover_image)
            : asset('storage/' . $hostel->cover_image);
    }
@endphp

<div class="hostel-hero">
    @if($heroCoverUrl)
        <img src="{{ $heroCoverUrl }}" alt="{{ $hostel->name }}"
             onerror="this.style.display='none'">
    @endif
    <div class="hostel-hero-overlay"></div>
    <div class="hostel-hero-info">
        <div>
            <div class="hostel-type">{{ $hostel->type === 'camping' ? '🏕 Camping' : '🏨 Hostel' }}</div>
            <div class="hostel-name">{{ $hostel->name }}</div>
            <div class="hostel-location">
                📍 {{ $hostel->region?->name ?? '' }}
                @if($hostel->city) — {{ $hostel->city }} @endif
            </div>
        </div>
        @if($hostel->rating > 0)
        <div class="hostel-rating-badge">
            <div class="rating-num">{{ number_format($hostel->rating, 1) }}</div>
            <div class="rating-stars">★★★★★</div>
            <div class="rating-count">{{ $hostel->total_reviews }} avis</div>
        </div>
        @endif
    </div>
</div>

<div class="page-content">

    {{-- ── Colonne gauche ── --}}
    <div>
        <div class="content-section">
            <div class="cs-title">À propos</div>
            @if($hostel->description)
                <p class="description">{{ $hostel->description }}</p>
            @else
                <p class="description" style="color:var(--lgray);font-style:italic">
                    Hébergement authentique situé {{ $hostel->region ? 'à ' . $hostel->region->name : 'en Tunisie' }}.
                    Contactez-nous pour plus d'informations.
                </p>
            @endif
        </div>

        <div class="content-section">
            <div class="cs-title">Informations</div>
            <div class="hostel-info-grid">
                @if($hostel->city)
                <div class="info-item"><div class="info-label">Ville</div><div class="info-value">{{ $hostel->city }}</div></div>
                @endif
                @if($hostel->country)
                <div class="info-item"><div class="info-label">Pays</div><div class="info-value">{{ $hostel->country }}</div></div>
                @endif
                @if($hostel->phone)
                <div class="info-item"><div class="info-label">Téléphone</div><div class="info-value">{{ $hostel->phone }}</div></div>
                @endif
                @if($hostel->email)
                <div class="info-item"><div class="info-label">Email</div><div class="info-value">{{ $hostel->email }}</div></div>
                @endif
                @if($hostel->address)
                <div class="info-item" style="grid-column:span 2">
                    <div class="info-label">Adresse</div>
                    <div class="info-value">{{ $hostel->address }}</div>
                </div>
                @endif
            </div>
        </div>

        @if($hostel->rooms->count() > 0)
        <div class="content-section">
            <div class="cs-title">Chambres disponibles ({{ $hostel->rooms->count() }})</div>
            <div class="rooms-grid">
                @foreach($hostel->rooms as $room)
                <div class="room-item">
                    <div class="room-name">{{ $room->name }}</div>
                    <div class="room-meta">
                        {{ $room->type === 'dormitory' ? '🛏 Dortoir' : '🚪 Chambre privée' }}
                        @if($room->capacity) · {{ $room->capacity }} pers.@endif
                    </div>
                    @if($room->beds->count() > 0)
                    <div class="room-beds">
                        @foreach($room->beds->take(4) as $bed)
                            <span class="bed-chip">{{ $bed->name }}</span>
                        @endforeach
                        @if($room->beds->count() > 4)
                            <span class="bed-chip">+{{ $room->beds->count() - 4 }}</span>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- ── Sidebar ── --}}
    <div>
        <div class="sidebar-card">
            @php $minPrice = $hostel->prices->min('price_ttc'); @endphp

            <div class="price-display">
                <div class="price-from">À partir de</div>
                @if($minPrice)
                    <div class="price-amount">{{ number_format($minPrice, 0) }} <span style="font-size:1.2rem">TND</span></div>
                    <div class="price-unit">par nuit / par personne</div>
                @else
                    <div class="price-amount" style="font-size:1.2rem;color:var(--lgray)">Prix sur demande</div>
                @endif
            </div>

            @if($availability)
            <div class="avail-status avail-{{ $availability['status'] }}">
                @if($availability['status'] === 'full')      🔴 Complet pour ces dates
                @elseif($availability['status'] === 'low')   🟡 Plus que {{ $availability['available'] }} place(s) !
                @else                                         ✅ {{ $availability['available'] }} place(s) disponible(s)
                @endif
            </div>
            @endif

            <form class="booking-form" method="GET" action="{{ route('search.show', $hostel->id) }}">
                <div class="form-group">
                    <label class="form-label">Arrivée</label>
                    <input type="date" name="check_in" class="form-input"
                           value="{{ request('check_in') }}" min="{{ date('Y-m-d') }}"
                           onchange="this.form.submit()">
                </div>
                <div class="form-group">
                    <label class="form-label">Départ</label>
                    <input type="date" name="check_out" class="form-input"
                           value="{{ request('check_out') }}"
                           onchange="this.form.submit()">
                </div>
                <div class="form-group">
                    <label class="form-label">Voyageurs</label>
                    <input type="number" name="guests" class="form-input"
                           min="1" max="20" value="{{ request('guests', 1) }}">
                </div>
            </form>

            <a href="{{ route('contact.create', [
                    'hostel'     => $hostel->id,
                    'check_in'   => request('check_in'),
                    'check_out'  => request('check_out'),
                    'guests'     => request('guests', 1)
                ]) }}"
               class="btn-reserve"
               style="display:block;text-align:center;text-decoration:none;margin-top:1rem">
                📋 Demander une réservation
            </a>

            <p style="font-size:0.72rem;color:var(--lgray);text-align:center;margin-top:10px">
                Confirmation sous 24h · Sans engagement
            </p>
        </div>
    </div>
</div>

</body>
</html>
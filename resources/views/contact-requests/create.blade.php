<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver — {{ $hostel->name }} — HostelFlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
    :root {
        --sand:#F5EFE6; --sand2:#EDE3D4; --terra:#C8602A; --terra2:#A84E20;
        --teal:#1B6B6B; --night:#1C1C24; --ink:#2E2E3A;
        --gray:#6B6B7A; --lgray:#A0A0B0; --border:#DDD6CA; --white:#FEFCF9;
        --ok:#1B7A4D; --err:#C03A3A;
    }
    *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
    body { font-family:'DM Sans', sans-serif; background:var(--sand); color:var(--ink); min-height:100vh; }

    nav {
        position:sticky; top:0; left:0; right:0; z-index:100;
        display:flex; align-items:center; justify-content:space-between;
        padding:0 2.5rem; height:68px;
        background:rgba(254,252,249,0.97); backdrop-filter:blur(16px);
        border-bottom:1px solid var(--border);
    }
    .nav-logo { font-family:'Playfair Display', serif; font-size:1.3rem; font-weight:700;
                color:var(--ink); text-decoration:none; display:flex; align-items:center; gap:10px; }
    .nav-logo img { height:34px; }
    .back-link { font-size:0.85rem; color:var(--terra); text-decoration:none;
                 display:flex; align-items:center; gap:6px; font-weight:600; }
    .back-link:hover { text-decoration:underline; }

    .page { max-width:780px; margin:0 auto; padding:2rem 1.5rem 4rem; }

    .hostel-summary {
        background:linear-gradient(135deg, var(--teal) 0%, var(--night) 100%);
        color:#fff; border-radius:20px; padding:24px 28px;
        margin-bottom:1.5rem; display:flex; gap:18px; align-items:center;
    }
    .hostel-summary-icon {
        width:60px; height:60px; border-radius:14px;
        background:rgba(255,255,255,0.12); display:flex; align-items:center; justify-content:center;
        font-size:1.8rem; flex-shrink:0;
    }
    .hostel-summary-info h2 { font-family:'Playfair Display', serif; font-size:1.4rem; font-weight:600; margin-bottom:3px; }
    .hostel-summary-info p { font-size:0.85rem; color:rgba(255,255,255,0.7); }

    .form-card { background:var(--white); border-radius:22px; padding:30px;
                 box-shadow:0 4px 20px rgba(28,28,36,0.05); border:1px solid var(--border); }

    .page-title { font-family:'Playfair Display', serif; font-size:1.7rem; font-weight:700;
                  color:var(--ink); margin-bottom:6px; }
    .page-title em { font-style:italic; color:var(--terra); }
    .page-sub { font-size:0.9rem; color:var(--gray); margin-bottom:1.8rem; line-height:1.6; }

    .alert { border-radius:14px; padding:14px 18px; font-size:0.88rem; margin-bottom:1.5rem; line-height:1.5; }
    .alert-success { background:rgba(27,122,77,0.08); color:var(--ok); border:1px solid rgba(27,122,77,0.25); }
    .alert-error { background:rgba(192,58,58,0.08); color:var(--err); border:1px solid rgba(192,58,58,0.25); }
    .alert ul { margin:6px 0 0 18px; }

    fieldset { border:none; padding:0; margin-bottom:1.5rem; padding-bottom:1.5rem; border-bottom:1px solid var(--border); }
    fieldset:last-of-type { border-bottom:none; padding-bottom:0; }
    legend { font-family:'Playfair Display', serif; font-size:1.05rem; font-weight:600;
             color:var(--ink); margin-bottom:1rem; padding:0; }
    .legend-icon { color:var(--terra); margin-right:6px; }

    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .form-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }
    @media (max-width: 640px) { .form-grid, .form-grid-3 { grid-template-columns:1fr; } }

    .form-group { display:flex; flex-direction:column; gap:5px; margin-bottom:14px; }
    .form-group:last-child { margin-bottom:0; }
    .form-label { font-size:0.72rem; font-weight:700; color:var(--gray);
                  text-transform:uppercase; letter-spacing:0.06em; }
    .form-label .req { color:var(--terra); margin-left:3px; }
    .form-input, .form-select, .form-textarea {
        border:1.5px solid var(--border); border-radius:12px;
        padding:11px 14px; font-size:0.92rem; font-family:'DM Sans', sans-serif;
        color:var(--ink); background:var(--sand); outline:none;
        transition:border-color 0.2s, background 0.2s; width:100%;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color:var(--terra); background:var(--white);
    }
    .form-textarea { resize:vertical; min-height:90px; }
    .form-help { font-size:0.75rem; color:var(--lgray); margin-top:3px; }

    .submit-row { display:flex; gap:12px; align-items:center; justify-content:flex-end;
                  margin-top:1.5rem; flex-wrap:wrap; }
    .btn { font-family:'DM Sans', sans-serif; font-size:0.9rem; font-weight:700;
           padding:13px 28px; border-radius:14px; cursor:pointer; border:none;
           transition:all 0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px; }
    .btn-secondary { background:var(--sand); color:var(--ink); border:1.5px solid var(--border); }
    .btn-secondary:hover { border-color:var(--terra); color:var(--terra); }
    .btn-primary { background:var(--terra); color:#fff; box-shadow:0 6px 20px rgba(200,96,42,0.35); }
    .btn-primary:hover { background:var(--terra2); transform:translateY(-2px); box-shadow:0 10px 28px rgba(200,96,42,0.4); }

    .footnote { margin-top:1.5rem; padding:14px 18px; background:rgba(27,107,107,0.05);
                border-radius:12px; font-size:0.78rem; color:var(--gray); line-height:1.5; }
    </style>
</head>
<body>

<nav>
    <a href="{{ url('/') }}" class="nav-logo">
        <img src="{{ asset('images/13.png') }}" alt="HostelFlow" onerror="this.style.display='none'">
        HostelFlow
    </a>
    <a href="{{ route('search.show', $hostel->id) }}" class="back-link">← Retour à la fiche</a>
</nav>

<div class="page">
    <div class="hostel-summary">
        <div class="hostel-summary-icon">{{ $hostel->type === 'camping' ? '🏕' : '🏨' }}</div>
        <div class="hostel-summary-info">
            <h2>{{ $hostel->name }}</h2>
            <p>📍 {{ $hostel->region?->name ?? 'Tunisie' }}@if($hostel->city) — {{ $hostel->city }}@endif</p>
        </div>
    </div>

    <div class="form-card">
        <div class="page-title">Demander une <em>réservation</em></div>
        <p class="page-sub">Remplissez le formulaire ci-dessous. L'équipe de l'hostel vous recontactera sous 24h pour confirmer votre séjour.</p>

        @if(session('contact_success'))
            <div class="alert alert-success">
                ✅ <strong>Demande envoyée avec succès !</strong> L'équipe de {{ $hostel->name }} vous recontactera sous 24h.
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                ⚠️ <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}">
            @csrf
            <input type="hidden" name="hostel_id" value="{{ $hostel->id }}">
            <input type="hidden" name="destination" value="{{ $hostel->name }}">

            <fieldset>
                <legend><span class="legend-icon">👤</span> Vos informations</legend>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Prénom <span class="req">*</span></label>
                        <input type="text" name="first_name" class="form-input"
                               value="{{ old('first_name') }}" required maxlength="100" placeholder="Ex : Mehdi">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom <span class="req">*</span></label>
                        <input type="text" name="last_name" class="form-input"
                               value="{{ old('last_name') }}" required maxlength="100" placeholder="Ex : Ben Salah">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Email <span class="req">*</span></label>
                        <input type="email" name="email" class="form-input"
                               value="{{ old('email') }}" required maxlength="150" placeholder="exemple@email.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" name="phone" class="form-input"
                               value="{{ old('phone') }}" maxlength="30" placeholder="+216 XX XXX XXX">
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend><span class="legend-icon">📅</span> Votre séjour</legend>
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="form-label">Arrivée <span class="req">*</span></label>
                        <input type="date" name="arrival_date" class="form-input"
                               value="{{ old('arrival_date', request('check_in')) }}"
                               min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Départ <span class="req">*</span></label>
                        <input type="date" name="departure_date" class="form-input"
                               value="{{ old('departure_date', request('check_out')) }}"
                               min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Voyageurs</label>
                        <input type="number" name="travelers" class="form-input"
                               value="{{ old('travelers', request('guests', 1)) }}" min="1" max="50">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Type de chambre souhaité</label>
                    <select name="room_type" class="form-select">
                        <option value="">— Indifférent —</option>
                        <option value="private" {{ old('room_type') === 'private' ? 'selected' : '' }}>🛏 Chambre privée</option>
                        <option value="dormitory" {{ old('room_type') === 'dormitory' ? 'selected' : '' }}>🛌 Dortoir</option>
                        <option value="tent" {{ old('room_type') === 'tent' ? 'selected' : '' }}>🏕 Emplacement tente</option>
                    </select>
                </div>
            </fieldset>

            <fieldset>
                <legend><span class="legend-icon">💬</span> Message <span style="font-size:0.78rem; font-weight:400; color:var(--lgray);">(optionnel)</span></legend>
                <div class="form-group">
                    <textarea name="message" class="form-textarea" maxlength="2000"
                              placeholder="Demandes particulières, questions, etc.">{{ old('message') }}</textarea>
                    <div class="form-help">2000 caractères maximum</div>
                </div>
            </fieldset>

            <div class="submit-row">
                <a href="{{ route('search.show', $hostel->id) }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">📨 Envoyer ma demande</button>
            </div>
        </form>

        <div class="footnote">
            🔒 <strong>Vos informations sont confidentielles.</strong>
            Elles ne seront utilisées que par l'équipe de {{ $hostel->name }} pour traiter votre demande.
            Aucun engagement de paiement à ce stade — la confirmation finale se fait par échange direct.
        </div>
    </div>
</div>
</body>
</html>
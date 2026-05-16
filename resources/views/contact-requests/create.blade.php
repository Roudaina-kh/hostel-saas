<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver — {{ $hostel->name }} — HostelFlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
    :root {
        --sand:#F5EFE6; --sand2:#EDE3D4; --terra:#C8602A; --terra2:#A84E20;
        --teal:#1B6B6B; --teal2:#134F4F; --night:#1C1C24; --ink:#2E2E3A;
        --gray:#6B6B7A; --lgray:#A0A0B0; --border:#DDD6CA; --white:#FEFCF9;
        --ok:#1B7A4D; --err:#C03A3A;
    }
    *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
    body {
        font-family:'DM Sans', sans-serif;
        background: var(--sand);
        color:var(--ink);
        min-height:100vh;
    }

    /* ── Nav ── */
    nav {
        position:sticky; top:0; z-index:100;
        display:flex; align-items:center; justify-content:space-between;
        padding:0 2rem; height:64px;
        background:rgba(254,252,249,0.97); backdrop-filter:blur(16px);
        border-bottom:1px solid var(--border);
    }
    .nav-logo { font-family:'Playfair Display', serif; font-size:1.2rem; font-weight:700;
                color:var(--ink); text-decoration:none; display:flex; align-items:center; gap:8px; }
    .nav-logo img { height:30px; border-radius:6px; }
    .back-link { font-size:0.82rem; color:var(--terra); text-decoration:none;
                 display:flex; align-items:center; gap:5px; font-weight:600;
                 padding:7px 16px; border-radius:30px; border:1.5px solid rgba(200,96,42,0.25);
                 transition:all .2s; }
    .back-link:hover { background:rgba(200,96,42,0.06); }

    /* ── Page ── */
    .page { max-width:700px; margin:0 auto; padding:2rem 1.25rem 5rem; }

    /* ── Hostel banner ── */
    .hostel-banner {
        border-radius:20px; overflow:hidden; margin-bottom:1.5rem;
        background:linear-gradient(135deg, var(--teal) 0%, var(--night) 100%);
        padding:22px 26px; display:flex; align-items:center; gap:16px;
        position:relative;
    }
    .hostel-banner::before {
        content:''; position:absolute; inset:0;
        background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .hostel-icon {
        width:56px; height:56px; border-radius:14px;
        background:rgba(255,255,255,0.12); display:flex; align-items:center;
        justify-content:center; font-size:1.6rem; flex-shrink:0; backdrop-filter:blur(4px);
        border:1px solid rgba(255,255,255,0.15);
    }
    .hostel-info h2 { font-family:'Playfair Display', serif; font-size:1.3rem; font-weight:700; color:#fff; margin-bottom:4px; }
    .hostel-info p { font-size:0.82rem; color:rgba(255,255,255,0.65); display:flex; align-items:center; gap:4px; }

    /* ── Form card ── */
    .form-card {
        background:var(--white);
        border-radius:24px;
        box-shadow:0 8px 40px rgba(28,28,36,0.09), 0 2px 8px rgba(28,28,36,0.04);
        border:1px solid var(--border);
        overflow:hidden;
    }

    .card-header {
        padding:28px 32px 20px;
        border-bottom:1px solid var(--border);
        background:linear-gradient(180deg, var(--white) 0%, var(--sand) 100%);
    }
    .card-title { font-family:'Playfair Display', serif; font-size:1.65rem; font-weight:700; color:var(--ink); margin-bottom:6px; }
    .card-title em { font-style:italic; color:var(--terra); }
    .card-sub { font-size:0.88rem; color:var(--gray); line-height:1.6; }

    .card-body { padding:28px 32px; }

    /* ── Alerts ── */
    .alert { border-radius:14px; padding:14px 18px; font-size:0.87rem; margin-bottom:1.5rem; line-height:1.5; display:flex; align-items:flex-start; gap:10px; }
    .alert-success { background:rgba(27,122,77,0.07); color:var(--ok); border:1px solid rgba(27,122,77,0.2); }
    .alert-error   { background:rgba(192,58,58,0.07); color:var(--err); border:1px solid rgba(192,58,58,0.2); }
    .alert ul { margin:6px 0 0 18px; }

    /* ── Section headers ── */
    .section {
        margin-bottom:2rem;
        padding-bottom:2rem;
        border-bottom:1px dashed var(--border);
    }
    .section:last-of-type { border-bottom:none; margin-bottom:0; padding-bottom:0; }
    .section-head {
        display:flex; align-items:center; gap:10px;
        margin-bottom:1.2rem;
    }
    .section-num {
        width:28px; height:28px; border-radius:50%;
        background:linear-gradient(135deg, var(--terra) 0%, var(--terra2) 100%);
        color:#fff; font-size:0.78rem; font-weight:700;
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
        box-shadow:0 3px 10px rgba(200,96,42,0.3);
    }
    .section-label { font-family:'Playfair Display', serif; font-size:1rem; font-weight:600; color:var(--ink); }
    .section-opt { font-size:0.75rem; font-weight:400; color:var(--lgray); margin-left:4px; }

    /* ── Field group ── */
    .form-grid   { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .form-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }
    @media(max-width:600px) { .form-grid, .form-grid-3 { grid-template-columns:1fr; } }

    .fgroup { display:flex; flex-direction:column; gap:5px; }

    .flabel {
        font-size:0.7rem; font-weight:700; color:var(--gray);
        text-transform:uppercase; letter-spacing:0.07em;
        display:flex; align-items:center; gap:4px;
    }
    .flabel .req { color:var(--terra); }

    /* ── Input with icon ── */
    .input-wrap { position:relative; }
    .input-icon {
        position:absolute; left:13px; top:50%; transform:translateY(-50%);
        color:var(--lgray); font-size:0.9rem; pointer-events:none;
        transition:color .2s;
    }
    .finput, .fselect, .ftextarea {
        width:100%; padding:11px 14px 11px 38px;
        border:1.5px solid var(--border); border-radius:12px;
        font-size:0.9rem; font-family:'DM Sans', sans-serif;
        color:var(--ink); background:var(--sand);
        outline:none; transition:border-color .2s, background .2s, box-shadow .2s;
        -webkit-appearance:none; appearance:none;
    }
    .finput:focus, .fselect:focus, .ftextarea:focus {
        border-color:var(--terra); background:var(--white);
        box-shadow:0 0 0 3px rgba(200,96,42,0.1);
    }
    .finput:focus ~ .input-icon, .finput:not(:placeholder-shown) ~ .input-icon { color:var(--terra); }
    .finput.no-icon, .fselect.no-icon { padding-left:14px; }
    .ftextarea { resize:vertical; min-height:100px; padding:12px 14px; }
    .fselect { padding-right:36px; cursor:pointer; }
    .select-arrow {
        position:absolute; right:13px; top:50%; transform:translateY(-50%);
        pointer-events:none; color:var(--gray); font-size:0.75rem;
    }
    .finput.is-invalid { border-color:var(--err); background:#fff8f8; }
    .finput.is-invalid:focus { box-shadow:0 0 0 3px rgba(192,58,58,0.1); }
    .field-err { font-size:0.73rem; color:var(--err); display:none; margin-top:2px; }
    .field-err.show { display:block; }
    .field-help { font-size:0.73rem; color:var(--lgray); margin-top:2px; }

    /* ── Room type radio cards ── */
    .room-cards { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-top:4px; }
    @media(max-width:500px) { .room-cards { grid-template-columns:1fr; } }
    .room-radio { display:none; }
    .room-card-label {
        display:flex; flex-direction:column; align-items:center; gap:6px;
        padding:14px 10px; border-radius:14px; cursor:pointer;
        border:1.5px solid var(--border); background:var(--sand);
        transition:all .2s; text-align:center;
    }
    .room-card-icon { font-size:1.4rem; }
    .room-card-name { font-size:0.8rem; font-weight:600; color:var(--ink); }
    .room-card-desc { font-size:0.7rem; color:var(--lgray); }
    .room-radio:checked + .room-card-label {
        border-color:var(--terra); background:rgba(200,96,42,0.06);
        box-shadow:0 0 0 3px rgba(200,96,42,0.1);
    }
    .room-radio:checked + .room-card-label .room-card-name { color:var(--terra); }
    .room-card-label:hover { border-color:var(--terra); background:rgba(200,96,42,0.04); }

    /* ── Submit row ── */
    .submit-row {
        display:flex; gap:12px; align-items:center; justify-content:flex-end;
        margin-top:2rem; padding-top:1.5rem; border-top:1px solid var(--border);
        flex-wrap:wrap;
    }
    .btn {
        font-family:'DM Sans', sans-serif; font-size:0.9rem; font-weight:700;
        padding:12px 26px; border-radius:50px; cursor:pointer; border:none;
        transition:all .2s; text-decoration:none;
        display:inline-flex; align-items:center; gap:8px;
    }
    .btn-ghost { background:transparent; color:var(--gray); border:1.5px solid var(--border); }
    .btn-ghost:hover { border-color:var(--terra); color:var(--terra); }
    .btn-main {
        background:linear-gradient(135deg, var(--terra) 0%, var(--terra2) 100%);
        color:#fff; box-shadow:0 6px 20px rgba(200,96,42,0.35);
        padding:12px 30px;
    }
    .btn-main:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(200,96,42,0.45); }
    .btn-main svg { width:16px; height:16px; }

    /* ── Footnote ── */
    .footnote {
        margin:0 32px 28px; padding:14px 18px;
        background:rgba(27,107,107,0.05); border:1px solid rgba(27,107,107,0.12);
        border-radius:12px; font-size:0.77rem; color:var(--gray); line-height:1.6;
    }
    .footnote strong { color:var(--teal); }

    /* ── Char counter ── */
    .char-counter { font-size:0.72rem; color:var(--lgray); text-align:right; margin-top:3px; }
    </style>
</head>
<body>

<nav>
    <a href="{{ url('/') }}" class="nav-logo">
        <img src="{{ asset('images/logo2.png') }}" alt="HostelFlow" onerror="this.src='{{ asset('images/13.png') }}'">
        HostelFlow
    </a>
    <a href="{{ route('search.show', $hostel->id) }}" class="back-link">
        ← Retour à la fiche
    </a>
</nav>

<div class="page">

    {{-- Hostel banner --}}
    <div class="hostel-banner">
        <div class="hostel-icon">{{ $hostel->type === 'camping' ? '🏕' : '🏨' }}</div>
        <div class="hostel-info">
            <h2>{{ $hostel->name }}</h2>
            <p>📍 {{ $hostel->region?->name ?? 'Tunisie' }}@if($hostel->city) — {{ $hostel->city }}@endif</p>
        </div>
    </div>

    {{-- Form card --}}
    <div class="form-card">
        <div class="card-header">
            <div class="card-title">Demander une <em>réservation</em></div>
            <p class="card-sub">Remplissez le formulaire ci-dessous. L'équipe de l'hostel vous recontactera sous 24h pour confirmer votre séjour.</p>
        </div>

        <div class="card-body">
            @if(session('contact_success'))
                <div class="alert alert-success">
                    ✅ <div><strong>Demande envoyée avec succès !</strong><br>L'équipe de {{ $hostel->name }} vous recontactera sous 24h.</div>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    ⚠️ <div><strong>Veuillez corriger les erreurs suivantes :</strong>
                    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" id="contactForm" novalidate>
                @csrf
                <input type="hidden" name="hostel_id" value="{{ $hostel->id }}">
                <input type="hidden" name="destination" value="{{ $hostel->name }}">

                {{-- Section 1 : Vos informations --}}
                <div class="section">
                    <div class="section-head">
                        <div class="section-num">1</div>
                        <div class="section-label">Vos informations</div>
                    </div>

                    <div class="form-grid" style="margin-bottom:14px">
                        <div class="fgroup">
                            <label class="flabel" for="first_name">Prénom <span class="req">*</span></label>
                            <div class="input-wrap">
                                <input id="first_name" type="text" name="first_name" class="finput"
                                       value="{{ old('first_name') }}" required maxlength="100"
                                       placeholder="Ex : Mehdi" autocomplete="given-name">
                                <span class="input-icon">👤</span>
                            </div>
                        </div>
                        <div class="fgroup">
                            <label class="flabel" for="last_name">Nom <span class="req">*</span></label>
                            <div class="input-wrap">
                                <input id="last_name" type="text" name="last_name" class="finput"
                                       value="{{ old('last_name') }}" required maxlength="100"
                                       placeholder="Ex : Ben Salah" autocomplete="family-name">
                                <span class="input-icon">👤</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="fgroup">
                            <label class="flabel" for="email">Email <span class="req">*</span></label>
                            <div class="input-wrap">
                                <input id="email" type="email" name="email" class="finput"
                                       value="{{ old('email') }}" required maxlength="150"
                                       placeholder="exemple@email.com"
                                       pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}"
                                       autocomplete="email">
                                <span class="input-icon">✉️</span>
                            </div>
                            <span class="field-err" id="email_err">Format invalide — ex : nom@exemple.com</span>
                        </div>
                        <div class="fgroup">
                            <label class="flabel" for="phone">Téléphone</label>
                            <div class="input-wrap">
                                <input id="phone" type="tel" name="phone" class="finput"
                                       value="{{ old('phone') }}" maxlength="20"
                                       placeholder="+216 XX XXX XXX"
                                       inputmode="numeric" autocomplete="tel">
                                <span class="input-icon">📱</span>
                            </div>
                            <span class="field-help">Chiffres, +, espaces et tirets uniquement</span>
                        </div>
                    </div>
                </div>

                {{-- Section 2 : Votre séjour --}}
                <div class="section">
                    <div class="section-head">
                        <div class="section-num">2</div>
                        <div class="section-label">Votre séjour</div>
                    </div>

                    <div class="form-grid-3" style="margin-bottom:16px">
                        <div class="fgroup">
                            <label class="flabel" for="arrival_date">Arrivée <span class="req">*</span></label>
                            <div class="input-wrap">
                                <input id="arrival_date" type="date" name="arrival_date" class="finput"
                                       value="{{ old('arrival_date', request('check_in')) }}"
                                       min="{{ date('Y-m-d') }}" required>
                                <span class="input-icon">📅</span>
                            </div>
                        </div>
                        <div class="fgroup">
                            <label class="flabel" for="departure_date">Départ <span class="req">*</span></label>
                            <div class="input-wrap">
                                <input id="departure_date" type="date" name="departure_date" class="finput"
                                       value="{{ old('departure_date', request('check_out')) }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                <span class="input-icon">📅</span>
                            </div>
                        </div>
                        <div class="fgroup">
                            <label class="flabel" for="travelers">Voyageurs</label>
                            <div class="input-wrap">
                                <input id="travelers" type="number" name="travelers" class="finput"
                                       value="{{ old('travelers', request('guests', 1)) }}"
                                       min="1" max="50">
                                <span class="input-icon">👥</span>
                            </div>
                        </div>
                    </div>

                    <div class="fgroup">
                        <label class="flabel">Type de chambre souhaité</label>
                        <div class="room-cards">
                            <div>
                                <input type="radio" name="room_type" id="rt_any" value="" class="room-radio"
                                       {{ old('room_type', '') === '' ? 'checked' : '' }}>
                                <label for="rt_any" class="room-card-label">
                                    <span class="room-card-icon">🏠</span>
                                    <span class="room-card-name">Indifférent</span>
                                    <span class="room-card-desc">Toutes options</span>
                                </label>
                            </div>
                            <div>
                                <input type="radio" name="room_type" id="rt_private" value="private" class="room-radio"
                                       {{ old('room_type') === 'private' ? 'checked' : '' }}>
                                <label for="rt_private" class="room-card-label">
                                    <span class="room-card-icon">🚪</span>
                                    <span class="room-card-name">Chambre privée</span>
                                    <span class="room-card-desc">Pour 1–3 pers.</span>
                                </label>
                            </div>
                            <div>
                                <input type="radio" name="room_type" id="rt_dorm" value="dormitory" class="room-radio"
                                       {{ old('room_type') === 'dormitory' ? 'checked' : '' }}>
                                <label for="rt_dorm" class="room-card-label">
                                    <span class="room-card-icon">🛌</span>
                                    <span class="room-card-name">Dortoir</span>
                                    <span class="room-card-desc">Lit en dortoir</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3 : Message --}}
                <div class="section">
                    <div class="section-head">
                        <div class="section-num">3</div>
                        <div class="section-label">Message <span class="section-opt">(optionnel)</span></div>
                    </div>
                    <div class="fgroup">
                        <textarea id="message_field" name="message" class="ftextarea" maxlength="2000"
                                  placeholder="Demandes particulières, questions, informations complémentaires…">{{ old('message') }}</textarea>
                        <div class="char-counter"><span id="char_count">0</span> / 2000 caractères</div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="submit-row">
                    <a href="{{ route('search.show', $hostel->id) }}" class="btn btn-ghost">Annuler</a>
                    <button type="submit" class="btn btn-main">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Envoyer ma demande
                    </button>
                </div>
            </form>
        </div>

        <div class="footnote">
            🔒 <strong>Vos informations sont confidentielles.</strong>
            Elles ne seront utilisées que par l'équipe de {{ $hostel->name }} pour traiter votre demande.
            Aucun engagement de paiement à ce stade — la confirmation finale se fait par échange direct.
        </div>
    </div>
</div>

<script>
(function () {
    var today = new Date().toISOString().split('T')[0];
    var tomorrow = (function () { var d = new Date(); d.setDate(d.getDate()+1); return d.toISOString().split('T')[0]; })();

    // ── Dates par défaut ────────────────────────────────────────────────────
    var arr = document.getElementById('arrival_date');
    var dep = document.getElementById('departure_date');
    if (arr && !arr.value) { arr.value = today; }
    if (dep && !dep.value) { dep.value = tomorrow; }

    // Empêcher départ avant arrivée
    if (arr) arr.addEventListener('change', function () {
        if (dep && dep.value && dep.value <= this.value) {
            var next = new Date(this.value);
            next.setDate(next.getDate() + 1);
            dep.value = next.toISOString().split('T')[0];
            dep.min = dep.value;
        }
    });

    // ── Téléphone : chiffres + +, espace, tiret, parenthèses ───────────────
    var phone = document.getElementById('phone');
    if (phone) {
        phone.addEventListener('input', function () {
            var clean = this.value.replace(/[^0-9+\s\-()]/g, '');
            if (this.value !== clean) this.value = clean;
        });
        phone.addEventListener('keydown', function (e) {
            var allowed = ['Backspace','Delete','Tab','Escape','Enter','ArrowLeft','ArrowRight','Home','End'];
            if (allowed.includes(e.key) || (e.ctrlKey && ['a','c','v','x','z'].includes(e.key.toLowerCase()))) return;
            if (!/[0-9+\s\-() ]/.test(e.key)) e.preventDefault();
        });
    }

    // ── Email validation ────────────────────────────────────────────────────
    var emailEl  = document.getElementById('email');
    var emailErr = document.getElementById('email_err');
    var emailRe  = /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/;
    if (emailEl) {
        emailEl.addEventListener('blur', function () {
            if (this.value && !emailRe.test(this.value)) {
                this.classList.add('is-invalid');
                if (emailErr) emailErr.classList.add('show');
            } else {
                this.classList.remove('is-invalid');
                if (emailErr) emailErr.classList.remove('show');
            }
        });
        emailEl.addEventListener('input', function () {
            if (this.classList.contains('is-invalid') && emailRe.test(this.value)) {
                this.classList.remove('is-invalid');
                if (emailErr) emailErr.classList.remove('show');
            }
        });
    }

    // ── Compteur de caractères du message ──────────────────────────────────
    var msg    = document.getElementById('message_field');
    var ccount = document.getElementById('char_count');
    if (msg && ccount) {
        msg.addEventListener('input', function () { ccount.textContent = this.value.length; });
    }

    // ── Validation avant soumission ─────────────────────────────────────────
    var form = document.getElementById('contactForm');
    if (form) form.addEventListener('submit', function (e) {
        if (emailEl && emailEl.value && !emailRe.test(emailEl.value)) {
            e.preventDefault();
            emailEl.classList.add('is-invalid');
            if (emailErr) emailErr.classList.add('show');
            emailEl.scrollIntoView({ behavior:'smooth', block:'center' });
        }
    });
})();
</script>
</body>
</html>

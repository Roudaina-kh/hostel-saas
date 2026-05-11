<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HostelFlow')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    :root {
        /* ── Palette principale ── */
        --sand:    #F5EFE6;
        --sand2:   #EDE3D4;
        --terra:   #C8602A;
        --terra2:  #A84E20;
        --terra-soft: #FEF3E2;
        --teal:    #1B6B6B;
        --teal2:   #134F4F;
        --teal-soft: #E8F4F0;
        --night:   #1C1C24;
        --ink:     #2E2E3A;
        --gray:    #6B6B7A;
        --lgray:   #A0A0B0;
        --border:  #DDD6CA;
        --white:   #FEFCF9;

        /* ── États sémantiques ── */
        --success:      #4A8F6E;
        --success-soft: #D8E9DF;
        --warning:      #C8842A;
        --warning-soft: #FBE8C8;
        --danger:       #A84E20;
        --danger-soft:  #F5DDD0;
        --info:         #1B6B6B;
        --info-soft:    #D8E9E9;

        --shadow-sm: 0 1px 3px rgba(28,28,36,0.05);
        --shadow-md: 0 8px 24px rgba(28,28,36,0.08);
        --shadow-lg: 0 20px 48px rgba(28,28,36,0.12);
    }

    *, *::before, *::after { box-sizing: border-box; }

    body {
        margin: 0; padding: 0;
        font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        color: var(--ink);
        background: var(--sand);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Playfair Display', Georgia, serif;
        color: var(--ink);
        margin: 0;
        line-height: 1.2;
    }

    /* ── ANIMATIONS GLOBALES ── */
    .fade-up {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeUp 0.6s cubic-bezier(0.25, 1, 0.5, 1) forwards;
    }
    @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
    .delay-1 { animation-delay: 100ms; }
    .delay-2 { animation-delay: 200ms; }
    .delay-3 { animation-delay: 300ms; }
    .delay-4 { animation-delay: 400ms; }
    .delay-5 { animation-delay: 500ms; }

    .reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.6s ease, transform 0.6s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    /* ═══════════════════════════════════════════════════════════════
       UTILITY CLASSES — Composants réutilisables
       ═══════════════════════════════════════════════════════════════ */

    .card-soft {
        background: var(--white);
        border-radius: 22px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }
    .card-elevated {
        background: var(--white);
        border-radius: 22px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-md);
        transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s;
    }
    .card-elevated:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .btn-terra {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        background: var(--terra); color: #fff;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.88rem; font-weight: 700;
        padding: 11px 22px; border-radius: 14px; border: none;
        cursor: pointer; text-decoration: none;
        box-shadow: 0 6px 20px rgba(200,96,42,0.28);
        transition: all 0.25s ease;
    }
    .btn-terra:hover {
        background: var(--terra2);
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(200,96,42,0.38);
        color: #fff;
    }
    .btn-teal {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        background: var(--teal); color: #fff;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.88rem; font-weight: 700;
        padding: 11px 22px; border-radius: 14px; border: none;
        cursor: pointer; text-decoration: none;
        box-shadow: 0 6px 20px rgba(27,107,107,0.28);
        transition: all 0.25s ease;
    }
    .btn-teal:hover {
        background: var(--teal2);
        transform: translateY(-2px);
        color: #fff;
    }
    .btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        background: var(--sand);
        color: var(--ink);
        border: 1.5px solid var(--border);
        font-family: 'DM Sans', sans-serif;
        font-size: 0.88rem; font-weight: 600;
        padding: 11px 22px; border-radius: 14px;
        cursor: pointer; text-decoration: none;
        transition: all 0.2s;
    }
    .btn-ghost:hover {
        border-color: var(--terra);
        color: var(--terra);
    }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 12px; border-radius: 14px;
        font-size: 0.72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.04em;
    }
    .badge-success  { background: var(--success-soft);  color: var(--success); }
    .badge-warning  { background: var(--warning-soft);  color: var(--warning); }
    .badge-danger   { background: var(--danger-soft);   color: var(--danger); }
    .badge-info     { background: var(--info-soft);     color: var(--info); }
    .badge-neutral  { background: var(--sand2);         color: var(--gray); }

    .form-input-soft {
        width: 100%;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        padding: 11px 14px;
        font-size: 0.92rem;
        font-family: 'DM Sans', sans-serif;
        color: var(--ink);
        background: var(--sand);
        outline: none;
        transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
    }
    .form-input-soft:focus {
        border-color: var(--terra);
        background: var(--white);
        box-shadow: 0 0 0 4px rgba(200,96,42,0.12);
    }

    .section-tag {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.12em; color: var(--terra);
        margin-bottom: 0.6rem;
    }
    .section-tag::before {
        content: ""; width: 18px; height: 2px;
        background: var(--terra); border-radius: 2px;
    }

    .alert-soft {
        border-radius: 14px; padding: 14px 18px;
        font-size: 0.9rem; line-height: 1.5;
        margin-bottom: 1.5rem;
    }
    .alert-success { background: var(--success-soft); color: var(--success); border: 1px solid rgba(74,143,110,0.3); }
    .alert-error   { background: var(--danger-soft);  color: var(--danger);  border: 1px solid rgba(168,78,32,0.3); }
    .alert-info    { background: var(--info-soft);    color: var(--info);    border: 1px solid rgba(27,107,107,0.3); }

    /* ═══════════════════════════════════════════════════════════════
       LAYOUT STRUCTURE
       ═══════════════════════════════════════════════════════════════ */
    .app-shell {
        display: flex;
        min-height: 100vh;
        background: var(--sand);
    }
    .app-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow-x: hidden;
        position: relative;
        background: linear-gradient(180deg, var(--sand) 0%, #FAF6EF 100%);
    }
    .app-main-inner {
        flex: 1;
        padding: 1.5rem 2rem 3rem;
        max-width: 1400px;
        width: 100%;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .app-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(110px);
        pointer-events: none;
        z-index: 0;
    }
    .app-blob-1 {
        width: 500px; height: 500px;
        background: rgba(200, 96, 42, 0.08);
        top: -100px; right: -150px;
    }
    .app-blob-2 {
        width: 400px; height: 400px;
        background: rgba(27, 107, 107, 0.08);
        bottom: 100px; left: -100px;
    }

    ::selection { background: var(--terra); color: #fff; }

    ::-webkit-scrollbar { width: 10px; height: 10px; }
    ::-webkit-scrollbar-track { background: var(--sand); }
    ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 5px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--lgray); }

    /* ═══════════════════════════════════════════════════════════════
       LEGACY ALIASES — Anciennes classes redirigées
       ═══════════════════════════════════════════════════════════════ */
    .stat-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 22px;
        padding: 1.75rem;
        box-shadow: var(--shadow-md);
        transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-lg);
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--terra), var(--teal));
    }
    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
        background: var(--terra-soft);
        color: var(--terra);
        box-shadow: 0 4px 12px rgba(200,96,42,0.15);
        border: 1px solid var(--border);
        transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1);
    }
    .stat-card:hover .stat-icon { transform: scale(1.12) rotate(-5deg); }

    .btn-blue {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        background: var(--terra); color: #fff;
        font-weight: 700; font-size: 14px;
        padding: 0.75rem 1.5rem; border-radius: 14px; border: none;
        text-decoration: none;
        box-shadow: 0 6px 20px rgba(200,96,42,0.28);
        transition: all 0.3s cubic-bezier(0.25,1,0.5,1);
    }
    .btn-blue:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(200,96,42,0.38); color: #fff; }

    .glass-table {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 22px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .table-header-blue {
        background: linear-gradient(90deg, var(--sand) 0%, var(--sand2) 100%);
        border-bottom: 2px solid var(--border);
    }
    .table-row-hover { transition: background 0.2s; }
    .table-row-hover:hover { background: #FAF6EF; }

    /* ═══════════════════════════════════════════════════════════════
       🎯 TAILWIND OVERRIDE — Transforme automatiquement les bleus en terra/teal
       Tactique pour migrer 80% des pages internes sans modifier chaque fichier.
       ═══════════════════════════════════════════════════════════════ */

    /* ── BACKGROUNDS bleus → terra ── */
    .bg-blue-50,
    .bg-blue-100   { background-color: var(--terra-soft) !important; }
    .bg-blue-500,
    .bg-blue-600   { background-color: var(--terra) !important; }
    .bg-blue-700,
    .bg-blue-800,
    .bg-blue-900   { background-color: var(--terra2) !important; }

    /* ── TEXT bleus → terra ── */
    .text-blue-50,
    .text-blue-100,
    .text-blue-200 { color: var(--terra-soft) !important; }
    .text-blue-500,
    .text-blue-600 { color: var(--terra) !important; }
    .text-blue-700,
    .text-blue-800,
    .text-blue-900 { color: var(--terra2) !important; }

    /* ── BORDERS bleus → terra ── */
    .border-blue-100,
    .border-blue-200 { border-color: var(--border) !important; }
    .border-blue-300,
    .border-blue-400 { border-color: rgba(200,96,42,0.4) !important; }
    .border-blue-500,
    .border-blue-600,
    .border-blue-700 { border-color: var(--terra) !important; }

    /* ── HOVER states bleus → terra ── */
    .hover\:bg-blue-50:hover    { background-color: var(--terra-soft) !important; }
    .hover\:bg-blue-100:hover   { background-color: var(--sand2) !important; }
    .hover\:bg-blue-600:hover   { background-color: var(--terra) !important; }
    .hover\:bg-blue-700:hover   { background-color: var(--terra2) !important; }
    .hover\:text-blue-600:hover { color: var(--terra) !important; }
    .hover\:text-blue-700:hover { color: var(--terra2) !important; }
    .hover\:border-blue-400:hover,
    .hover\:border-blue-500:hover,
    .hover\:border-blue-600:hover { border-color: var(--terra) !important; }

    /* ── FOCUS states bleus → terra ── */
    .focus\:ring-blue-500:focus,
    .focus\:ring-blue-600:focus {
        --tw-ring-color: rgba(200,96,42,0.3) !important;
    }
    .focus\:border-blue-500:focus,
    .focus\:border-blue-600:focus { border-color: var(--terra) !important; }

    /* ── INDIGO (accents secondaires) → teal ── */
    .bg-indigo-500, .bg-indigo-600 { background-color: var(--teal) !important; }
    .text-indigo-600              { color: var(--teal) !important; }
    .border-indigo-500            { border-color: var(--teal) !important; }
    .hover\:bg-indigo-700:hover   { background-color: var(--teal2) !important; }

    /* ── SKY/CYAN (accents tertiaires) → teal ── */
    .bg-sky-500, .bg-sky-600       { background-color: var(--teal) !important; }
    .bg-cyan-500, .bg-cyan-600     { background-color: var(--teal) !important; }
    .text-sky-600, .text-cyan-600  { color: var(--teal) !important; }

    /* ── GREEN (succès) → sage ── */
    .bg-green-50, .bg-green-100   { background-color: var(--success-soft) !important; }
    .bg-green-500, .bg-green-600  { background-color: var(--success) !important; }
    .text-green-600, .text-green-700 { color: var(--success) !important; }
    .border-green-200, .border-green-300 { border-color: rgba(74,143,110,0.3) !important; }

    /* ── RED (erreurs) → terra rougi ── */
    .bg-red-50, .bg-red-100       { background-color: var(--danger-soft) !important; }
    .bg-red-500, .bg-red-600      { background-color: var(--danger) !important; }
    .text-red-500, .text-red-600,
    .text-red-700                 { color: var(--danger) !important; }
    .border-red-200, .border-red-300 { border-color: rgba(168,78,32,0.3) !important; }

    /* ── ORANGE (warning) → warning-amber ── */
    .bg-orange-50, .bg-orange-100   { background-color: var(--warning-soft) !important; }
    .bg-orange-500, .bg-orange-600  { background-color: var(--warning) !important; }
    .text-orange-600                { color: var(--warning) !important; }

    /* ── GRAY-50/100 (backgrounds neutres) → sand ── */
    .bg-gray-50    { background-color: var(--sand) !important; }
    .bg-gray-100   { background-color: var(--sand2) !important; }

    /* ── Selection background ── */
    .selection\:bg-\[\#3B82F6\] *::selection { background: var(--terra) !important; }
    </style>
</head>
<body>

<div class="app-shell">

    @include('layouts.sidebar')

    <main class="app-main">

        <div class="app-blob app-blob-1"></div>
        <div class="app-blob app-blob-2"></div>

        @include('layouts.header')

        <div class="app-main-inner">

            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success', title: 'Succès !',
                        text: '{{ session("success") }}',
                        timer: 2500, showConfirmButton: false,
                        background: '#FEFCF9', color: '#2E2E3A',
                        iconColor: '#4A8F6E',
                    });
                });
            </script>
            @endif

            @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'error', title: 'Erreur',
                        text: '{{ session("error") }}',
                        background: '#FEFCF9', color: '#2E2E3A',
                        iconColor: '#A84E20',
                    });
                });
            </script>
            @endif

            @yield('content')

        </div>
    </main>
</div>

@stack('scripts')

</body>
</html>
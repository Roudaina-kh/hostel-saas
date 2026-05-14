php

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelFlow — Découvrez la Tunisie</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

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

    /* NAVBAR */
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

    /* HERO */
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

    /* SEARCH BOX */
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

    /* MAP SECTION */
    .map-section { background: var(--white); }
    .map-inner { max-width: 1200px; margin: 0 auto; }
    .map-header { text-align: center; margin-bottom: 2.5rem; }
    .map-header .section-tag { margin-left: auto; margin-right: auto; }
    .map-header h2 { margin-bottom: 0.8rem; }
    .map-header .section-desc {
        margin-left: auto; margin-right: auto;
        max-width: 560px; text-align: center;
    }
    .map-container {
        position: relative; height: 600px;
        border-radius: 24px; overflow: hidden;
        border: 1px solid var(--border);
        box-shadow: 0 12px 40px rgba(0,0,0,0.08);
        background: var(--sand);
    }
    #hostel-map { width: 100%; height: 100%; }
    .leaflet-tile-pane { filter: saturate(0.9) brightness(1.02); }

    .hf-marker { background: transparent !important; border: none !important; }
    .hf-marker__pin {
        width: 22px; height: 22px;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 3px 10px rgba(0,0,0,0.35);
        cursor: pointer;
        transition: transform 0.18s ease;
    }
    .hf-marker__pin--terra { background: var(--terra); }
    .hf-marker__pin--teal  { background: var(--teal); }
    .hf-marker:hover .hf-marker__pin { transform: scale(1.4); }

    .leaflet-popup-content-wrapper {
        border-radius: 16px !important;
        padding: 0 !important;
        overflow: hidden !important;
        box-shadow: 0 12px 40px rgba(0,0,0,0.2) !important;
        border: 1px solid var(--border);
    }
    .leaflet-popup-content { margin: 0 !important; width: 260px !important; }
    .leaflet-popup-tip { background: var(--white) !important; }

    .hf-popup__img { width: 100%; height: 140px; object-fit: cover; display: block; }
    .hf-popup__placeholder {
        width: 100%; height: 140px;
        background: linear-gradient(135deg, var(--teal), var(--night));
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem; color: rgba(255,255,255,0.5);
    }
    .hf-popup__body { padding: 14px 16px 16px; }
    .hf-popup__category {
        font-size: 0.62rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.1em;
        margin-bottom: 4px;
    }
    .hf-popup__category--terra { color: var(--terra); }
    .hf-popup__category--teal  { color: var(--teal); }
    .hf-popup__name {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem; font-weight: 600;
        color: var(--ink); margin-bottom: 6px; line-height: 1.25;
    }
    .hf-popup__region { font-size: 0.78rem; color: var(--gray); margin-bottom: 12px; }
    .hf-popup__btn {
        display: inline-block;
        background: var(--terra); color: #fff !important;
        padding: 7px 16px; border-radius: 14px;
        font-size: 0.78rem; font-weight: 700;
        text-decoration: none; transition: all 0.2s;
    }
    .hf-popup__btn:hover { background: var(--terra2); transform: scale(1.04); }

    .map-legend {
        position: absolute; bottom: 18px; left: 18px; z-index: 400;
        background: var(--white); border-radius: 14px;
        padding: 12px 16px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.15);
        border: 1px solid var(--border);
        font-family: 'DM Sans', sans-serif;
    }
    .map-legend__title {
        font-size: 0.65rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: var(--lgray); margin-bottom: 8px;
    }
    .map-legend__item {
        display: flex; align-items: center;
        font-size: 0.83rem; color: var(--ink);
        margin-bottom: 5px; font-weight: 500;
    }
    .map-legend__item:last-child { margin-bottom: 0; }
    .map-legend__pin {
        width: 12px; height: 12px; border-radius: 50%;
        display: inline-block; margin-right: 9px;
        vertical-align: middle;
        border: 2px solid #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,0.25);
        flex-shrink: 0;
    }
    .map-legend__pin--terra { background: var(--terra); }
    .map-legend__pin--teal  { background: var(--teal); }
    .map-legend__count { margin-left: 6px; color: var(--lgray); font-weight: 400; font-size: 0.78rem; }

    .map-note {
        position: absolute; bottom: 18px; right: 18px; z-index: 400;
        background: rgba(28,28,36,0.85);
        color: rgba(255,255,255,0.9);
        padding: 7px 14px; border-radius: 20px;
        font-size: 0.73rem; font-weight: 500;
        pointer-events: none; opacity: 0;
        transition: opacity 0.3s;
    }
    .map-container:hover .map-note { opacity: 1; }

    @media (max-width: 640px) {
        .map-container { height: 460px; }
        .map-legend { bottom: 12px; left: 12px; padding: 10px 13px; }
        .map-note { display: none; }
    }

    /* HOSTEL CARDS */
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

    /* FEATURES SECTION */
    .features-section {
        background: var(--white);
        position: relative; overflow: hidden;
    }
    .features-section::before {
        content: ''; position: absolute; inset: 0;
        background:
            radial-gradient(circle at 10% 20%, rgba(200,96,42,0.06) 0%, transparent 40%),
            radial-gradient(circle at 90% 80%, rgba(27,107,107,0.06) 0%, transparent 40%);
        pointer-events: none;
    }
    .features-inner {
        max-width: 1200px; margin: 0 auto;
        position: relative; z-index: 1;
    }
    .features-header { text-align: center; margin-bottom: 3.5rem; }
    .features-header .section-tag { margin: 0 auto 0.8rem; }
    .features-header h2 { margin-bottom: 0.8rem; }
    .features-header .section-desc {
        max-width: 580px; margin: 0 auto; text-align: center;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1.5rem;
    }
    @media (max-width: 1100px) {
        .features-grid { grid-template-columns: repeat(3, 1fr); gap: 2rem 1.5rem; }
    }
    @media (max-width: 700px) {
        .features-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 440px) {
        .features-grid { grid-template-columns: 1fr; max-width: 280px; margin: 0 auto; }
    }

    .feature-card { text-align: center; padding: 1rem 0.5rem; }

    .feature-icon-wrap {
        width: 92px; height: 92px;
        margin: 0 auto 1.4rem;
        border-radius: 50%;
        background: var(--white);
        box-shadow:
            0 10px 28px rgba(0,0,0,0.06),
            0 2px 8px rgba(0,0,0,0.04),
            inset 0 0 0 1px var(--border);
        display: flex; align-items: center; justify-content: center;
        animation: feature-float 3.5s ease-in-out infinite;
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .feature-icon-wrap::before {
        content: ''; position: absolute; inset: -7px;
        border-radius: 50%;
        border: 1.5px dashed transparent;
        transition: border-color 0.3s, opacity 0.3s;
        opacity: 0;
    }
    .feature-card:hover .feature-icon-wrap {
        transform: scale(1.05);
        box-shadow:
            0 14px 36px rgba(0,0,0,0.09),
            0 3px 10px rgba(0,0,0,0.05),
            inset 0 0 0 1px var(--border);
    }
    .feature-card:hover .feature-icon-wrap::before {
        border-color: currentColor;
        opacity: 0.4;
    }
    .feature-icon-wrap--terra { color: var(--terra); }
    .feature-icon-wrap--teal  { color: var(--teal); }
    .feature-icon-wrap svg { width: 40px; height: 40px; color: inherit; }

    .feature-card:nth-child(1) .feature-icon-wrap { animation-delay: 0s; }
    .feature-card:nth-child(2) .feature-icon-wrap { animation-delay: 0.6s; }
    .feature-card:nth-child(3) .feature-icon-wrap { animation-delay: 1.2s; }
    .feature-card:nth-child(4) .feature-icon-wrap { animation-delay: 0.3s; }
    .feature-card:nth-child(5) .feature-icon-wrap { animation-delay: 0.9s; }

    @keyframes feature-float {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-12px); }
    }

    .feature-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.08rem; font-weight: 600;
        color: var(--ink);
        margin-bottom: 0.5rem; line-height: 1.3;
    }
    .feature-desc {
        font-size: 0.83rem; line-height: 1.65;
        color: var(--gray);
        padding: 0 0.3rem;
    }

    /* ══════════════════════════════════════
       AVIS CLIENTS — section reviews
       ══════════════════════════════════════ */
    .reviews-section {
        background: var(--sand);
        position: relative; overflow: hidden;
    }
    .reviews-section::before {
        content: ''; position: absolute; inset: 0;
        background:
            radial-gradient(circle at 80% 10%, rgba(200,96,42,0.07) 0%, transparent 40%),
            radial-gradient(circle at 15% 90%, rgba(27,107,107,0.07) 0%, transparent 40%);
        pointer-events: none;
    }
    .reviews-inner {
        max-width: 1200px; margin: 0 auto;
        position: relative; z-index: 1;
    }
    .reviews-header {
        text-align: center; margin-bottom: 3rem;
    }
    .reviews-header .section-tag { margin: 0 auto 0.8rem; }
    .reviews-header h2 { margin-bottom: 0.6rem; }
    .reviews-header .section-desc {
        max-width: 500px; margin: 0 auto; text-align: center;
    }

    .reviews-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.4rem;
    }
    @media (max-width: 1024px) { .reviews-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 560px)  { .reviews-grid { grid-template-columns: 1fr; max-width: 380px; margin: 0 auto; } }

    .review-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 22px;
        padding: 28px 24px 24px;
        display: flex; flex-direction: column; gap: 16px;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
        position: relative; overflow: hidden;
    }
    .review-card::before {
        content: '\201C';
        position: absolute; top: 12px; right: 22px;
        font-family: 'Playfair Display', serif;
        font-size: 4.5rem; line-height: 1;
        color: var(--terra); opacity: 0.1;
        pointer-events: none;
    }
    .review-card:hover {
        box-shadow: 0 16px 44px rgba(28,28,36,0.10);
        transform: translateY(-5px);
    }

    /* Sweep animation for stars */
    @keyframes star-sweep {
        0%   { clip-path: inset(0 100% 0 0); opacity: 0.2; }
        100% { clip-path: inset(0 0% 0 0);   opacity: 1; }
    }

    .review-stars {
        display: flex; gap: 3px;
    }
    .review-stars .s {
        font-size: 1.15rem;
        color: #E8A020;
        opacity: 0.2;
        /* initially hidden, JS triggers the animation */
    }
    .review-stars.animated .s {
        opacity: 1;
        animation: star-sweep 0.4s ease both;
    }
    .review-stars.animated .s:nth-child(1) { animation-delay: 0.05s; }
    .review-stars.animated .s:nth-child(2) { animation-delay: 0.15s; }
    .review-stars.animated .s:nth-child(3) { animation-delay: 0.25s; }
    .review-stars.animated .s:nth-child(4) { animation-delay: 0.35s; }
    .review-stars.animated .s:nth-child(5) { animation-delay: 0.45s; }
    /* inactive star stays gray */
    .review-stars .s.empty { color: var(--border); }

    .review-text {
        font-size: 0.9rem; line-height: 1.75;
        color: var(--gray); flex: 1;
        font-style: italic;
    }

    .review-author {
        display: flex; align-items: center; gap: 12px;
        padding-top: 14px;
        border-top: 1px solid var(--border);
    }
    .review-avatar {
        width: 44px; height: 44px; border-radius: 50%;
        background: linear-gradient(135deg, var(--terra) 0%, var(--teal) 100%);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem; font-weight: 700; color: #fff;
        flex-shrink: 0;
        border: 2px solid var(--sand2);
    }
    .review-author-info { display: flex; flex-direction: column; gap: 2px; }
    .review-author-name {
        font-weight: 700; font-size: 0.9rem; color: var(--ink);
    }
    .review-author-meta {
        font-size: 0.73rem; color: var(--lgray);
        display: flex; align-items: center; gap: 6px;
    }
    .review-author-meta::before {
        content: ''; display: inline-block;
        width: 5px; height: 5px; border-radius: 50%;
        background: var(--terra); opacity: 0.6;
        flex-shrink: 0;
    }

    /* SEARCH RESULTS */
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

    .empty-state { text-align: center; padding: 80px 20px; color: var(--gray); }
    .empty-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.4; }
    .empty-title { font-family: 'Playfair Display', serif; font-size: 1.6rem; color: var(--ink); margin-bottom: 0.5rem; }

    /* PAGINATION */
    .hf-pager {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 1rem;
        margin-top: 2.5rem; padding-top: 1.5rem;
        border-top: 1px solid var(--border);
    }
    .hf-pager__summary { font-size: 0.85rem; color: var(--gray); }
    .hf-pager__summary strong { color: var(--ink); font-weight: 600; }
    .hf-pager__nav { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .hf-pager__arrow {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 18px; border-radius: 10px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.83rem; font-weight: 600;
        text-decoration: none; white-space: nowrap;
        transition: all 0.18s ease;
        border: 1.5px solid rgba(200,96,42,0.3);
        background: rgba(200,96,42,0.06);
        color: var(--terra); cursor: pointer;
    }
    .hf-pager__arrow:hover {
        background: var(--terra); color: #fff; border-color: var(--terra);
        box-shadow: 0 3px 10px rgba(200,96,42,0.28);
        transform: translateY(-1px);
    }
    .hf-pager__arrow--disabled {
        opacity: 0.38; pointer-events: none;
        background: var(--sand); border-color: var(--border);
        color: var(--lgray); cursor: not-allowed;
    }
    .hf-pager__gap {
        display: inline-flex; align-items: center; justify-content: center;
        width: 36px; height: 36px; color: var(--lgray);
        font-size: 0.88rem; user-select: none;
    }
    .hf-pager__page {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 36px; height: 36px; padding: 0 6px;
        border-radius: 9px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.88rem; font-weight: 500;
        text-decoration: none; transition: all 0.16s ease;
        border: 1.5px solid var(--border);
        color: var(--ink); background: transparent;
        cursor: pointer;
    }
    .hf-pager__page:hover {
        border-color: var(--terra); color: var(--terra);
        background: rgba(200,96,42,0.05);
    }
    .hf-pager__page--active {
        background: var(--terra); border-color: var(--terra);
        color: #fff; font-weight: 700;
        box-shadow: 0 3px 10px rgba(200,96,42,0.32);
        cursor: default; pointer-events: none;
    }
    nav[role="navigation"] { all: unset; display: block; }
    nav[role="navigation"] > div { display: flex; flex-direction: column; gap: 1rem; }
    @media (max-width: 640px) {
        .hf-pager { flex-direction: column; align-items: center; }
        .hf-pager__nav { justify-content: center; }
    }

    /* FOOTER */
    footer {
        background: var(--terra);
        color: rgba(254, 252, 249, 0.85);
        padding: 60px 3rem 30px;
        position: relative; overflow: hidden;
    }
    footer::before {
        content: ''; position: absolute;
        top: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(to right, transparent, rgba(254,252,249,0.35), transparent);
    }
    footer::after {
        content: ''; position: absolute;
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
        color: var(--white); margin-bottom: 10px;
        display: flex; align-items: center; gap: 10px;
        letter-spacing: -0.5px;
    }
    .footer-logo img { height: 36px; filter: brightness(0) invert(1); }
    .footer-tagline {
        font-size: 0.85rem; line-height: 1.75; max-width: 280px;
        color: rgba(254, 252, 249, 0.75);
    }
    .footer-col-title {
        font-weight: 700; font-size: 0.78rem;
        text-transform: uppercase; letter-spacing: 0.12em;
        color: var(--white); margin-bottom: 1.1rem;
    }
    .footer-links { list-style: none; display: flex; flex-direction: column; gap: 8px; }
    .footer-links a {
        font-size: 0.85rem; color: rgba(254, 252, 249, 0.75);
        text-decoration: none; transition: color 0.2s, transform 0.2s;
        display: inline-block;
    }
    .footer-links a:hover { color: #F5C896; transform: translateX(4px); }
    .footer-bottom {
        border-top: 1px solid rgba(254, 252, 249, 0.18);
        padding-top: 20px;
        display: flex; justify-content: space-between;
        flex-wrap: wrap; gap: 10px;
    }
    .footer-copy { font-size: 0.78rem; color: rgba(254, 252, 249, 0.6); }

    /* ANIMATIONS */
    @keyframes fadeUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
    .reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.6s ease, transform 0.6s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>

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

        <form method="GET" action="{{ route('search.index') }}" id="searchForm">
        <div class="search-box">
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
                <div class="autocomplete-list" id="autocompleteList"></div>
            </div>

            <div class="search-field">
                <span class="search-icon">📅</span>
                <div class="sf-inner">
                    <span class="sf-label">Arrivée</span>
                    <input class="sf-input" type="date" name="check_in"
                           value="{{ $params->checkIn ?? '' }}"
                           min="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="search-field">
                <span class="search-icon">📅</span>
                <div class="sf-inner">
                    <span class="sf-label">Départ</span>
                    <input class="sf-input" type="date" name="check_out"
                           value="{{ $params->checkOut ?? '' }}">
                </div>
            </div>

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

@if($result)
<section class="results-section">
    <div class="results-inner">

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

                <aside class="filters-panel reveal">
                    <div class="filter-title">🎛 Filtres</div>

                    <form method="GET" action="{{ route('search.index') }}" id="filterForm">
                        <input type="hidden" name="region"    value="{{ $params->regionSlug }}">
                        <input type="hidden" name="check_in"  value="{{ $params->checkIn }}">
                        <input type="hidden" name="check_out" value="{{ $params->checkOut }}">
                        <input type="hidden" name="guests"    value="{{ $params->guests }}">
                        <input type="hidden" name="sort"      value="{{ $params->sortBy }}">

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

                <div>
                    <div class="hostels-grid">
                        @foreach($result->hostels as $hostel)
                            @include('search._hostel_card', ['hostel' => $hostel])
                        @endforeach
                    </div>

                    @if($result->hostels->hasPages())
                    <div class="hf-pager">
                        <span class="hf-pager__summary">
                            <strong>{{ $result->hostels->firstItem() }}–{{ $result->hostels->lastItem() }}</strong>
                            sur {{ $result->hostels->total() }} hébergements
                        </span>
                        <div class="hf-pager__nav">
                            @if($result->hostels->onFirstPage())
                                <span class="hf-pager__arrow hf-pager__arrow--disabled">← Précédent</span>
                            @else
                                <a href="{{ $result->hostels->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                                   class="hf-pager__arrow">← Précédent</a>
                            @endif

                            @foreach($result->hostels->getUrlRange(1, $result->hostels->lastPage()) as $page => $url)
                                @if($page == $result->hostels->currentPage())
                                    <span class="hf-pager__page hf-pager__page--active">{{ $page }}</span>
                                @elseif($page == 1 || $page == $result->hostels->lastPage() || abs($page - $result->hostels->currentPage()) <= 1)
                                    <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}"
                                       class="hf-pager__page">{{ $page }}</a>
                                @elseif(abs($page - $result->hostels->currentPage()) == 2)
                                    <span class="hf-pager__gap">…</span>
                                @endif
                            @endforeach

                            @if($result->hostels->hasMorePages())
                                <a href="{{ $result->hostels->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                                   class="hf-pager__arrow">Suivant →</a>
                            @else
                                <span class="hf-pager__arrow hf-pager__arrow--disabled">Suivant →</span>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        @endif
    </div>
</section>

@else

    @if(!empty($mapHostels) && count($mapHostels) > 0)
    <section class="map-section">
        <div class="map-inner">
            <div class="map-header reveal">
                <div class="section-tag">Découvrir</div>
                <h2>Notre réseau sur la <em>carte de Tunisie</em></h2>
                <p class="section-desc">
                    Explorez les {{ count($mapHostels) }} hébergements HostelFlow répartis dans 17 gouvernorats.
                    Cliquez sur un point pour découvrir le hostel.
                </p>
            </div>

            <div class="map-container reveal">
                <div id="hostel-map"></div>

                <div class="map-legend">
                    <div class="map-legend__title">Légende</div>
                    @php
                        $cjCount = collect($mapHostels)->where('category', 'cj')->count();
                        $mjCount = collect($mapHostels)->where('category', 'mj')->count();
                    @endphp
                    <div class="map-legend__item">
                        <span class="map-legend__pin map-legend__pin--terra"></span>
                        Complexe de Jeunes
                        <span class="map-legend__count">({{ $cjCount }})</span>
                    </div>
                    <div class="map-legend__item">
                        <span class="map-legend__pin map-legend__pin--teal"></span>
                        Maison de Jeunes
                        <span class="map-legend__count">({{ $mjCount }})</span>
                    </div>
                </div>

                <div class="map-note">💡 Cliquez sur la carte pour zoomer à la molette</div>
            </div>
        </div>
    </section>
    @endif

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

    <section class="features-section">
        <div class="features-inner">

            <div class="features-header reveal">
                <div class="section-tag">Pourquoi nous</div>
                <h2>Les atouts <em>HostelFlow</em></h2>
                <p class="section-desc">
                    Une plateforme pensée pour valoriser l'authenticité tunisienne
                    et offrir aux voyageurs une expérience sans friction.
                </p>
            </div>

            <div class="features-grid">

                <div class="feature-card reveal">
                    <div class="feature-icon-wrap feature-icon-wrap--terra">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Hostels Vérifiés</h3>
                    <p class="feature-desc">
                        30 hébergements certifiés par notre équipe à travers tout le pays.
                    </p>
                </div>

                <div class="feature-card reveal">
                    <div class="feature-icon-wrap feature-icon-wrap--teal">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Couverture Nationale</h3>
                    <p class="feature-desc">
                        17 gouvernorats couverts, du Nord forestier au Sud saharien.
                    </p>
                </div>

                <div class="feature-card reveal">
                    <div class="feature-icon-wrap feature-icon-wrap--terra">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="5" width="20" height="14" rx="3"/>
                            <line x1="2" y1="10" x2="22" y2="10"/>
                            <path d="M6 15h3"/>
                            <path d="M11 15h2"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Paiement Sécurisé</h3>
                    <p class="feature-desc">
                        Transactions chiffrées multi-devises : TND, EUR et USD.
                    </p>
                </div>

                <div class="feature-card reveal">
                    <div class="feature-icon-wrap feature-icon-wrap--teal">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Réservation Instantanée</h3>
                    <p class="feature-desc">
                        Confirmez votre séjour en quelques clics, sans attente.
                    </p>
                </div>

                <div class="feature-card reveal">
                    <div class="feature-icon-wrap feature-icon-wrap--terra">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Support Local</h3>
                    <p class="feature-desc">
                        Une équipe tunisienne à votre écoute, 7 jours sur 7.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- AVIS CLIENTS                                          --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <section class="reviews-section">
        <div class="reviews-inner">

            <div class="reviews-header reveal">
                <div class="section-tag">Témoignages</div>
                <h2>Ce que disent nos <em>voyageurs</em></h2>
                <p class="section-desc">
                    Des milliers de voyageurs nous font confiance chaque année pour explorer la Tunisie.
                </p>
            </div>

            <div class="reviews-grid">

                {{-- Avis 1 --}}
                <div class="review-card reveal">
                    <div class="review-stars" data-rating="5">
                        <span class="s">★</span><span class="s">★</span><span class="s">★</span><span class="s">★</span><span class="s">★</span>
                    </div>
                    <p class="review-text">
                        "Réservation ultra simple, le complexe de Béja était exactement comme sur les photos.
                        Personnel accueillant et région magnifique. Je recommande vivement à tous les amateurs de randonnée !"
                    </p>
                    <div class="review-author">
                        <div class="review-avatar">YB</div>
                        <div class="review-author-info">
                            <span class="review-author-name">Yasmine Ben Salah</span>
                            <span class="review-author-meta">Tunis · avril 2025</span>
                        </div>
                    </div>
                </div>

                {{-- Avis 2 --}}
                <div class="review-card reveal">
                    <div class="review-stars" data-rating="5">
                        <span class="s">★</span><span class="s">★</span><span class="s">★</span><span class="s">★</span><span class="s">★</span>
                    </div>
                    <p class="review-text">
                        "Grâce à HostelFlow j'ai découvert la Maison de Jeunes de Tabarka.
                        Vue imprenable sur la mer, ambiance chaleureuse et prix très abordable.
                        Une expérience authentique que je referai sans hésiter."
                    </p>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, var(--teal) 0%, var(--teal2) 100%)">MK</div>
                        <div class="review-author-info">
                            <span class="review-author-name">Mohamed Khelifi</span>
                            <span class="review-author-meta">Sfax · mars 2025</span>
                        </div>
                    </div>
                </div>

                {{-- Avis 3 --}}
                <div class="review-card reveal">
                    <div class="review-stars" data-rating="4">
                        <span class="s">★</span><span class="s">★</span><span class="s">★</span><span class="s">★</span><span class="s empty">★</span>
                    </div>
                    <p class="review-text">
                        "Excellent site, clair et rapide. J'ai trouvé un hébergement à Djerba en quelques minutes.
                        Le complexe Houmet Essouk est idéal pour une escapade en famille.
                        Très bon rapport qualité-prix."
                    </p>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, #A84E20 0%, #C8602A 100%)">ST</div>
                        <div class="review-author-info">
                            <span class="review-author-name">Sana Trabelsi</span>
                            <span class="review-author-meta">Sousse · février 2025</span>
                        </div>
                    </div>
                </div>

                {{-- Avis 4 --}}
                <div class="review-card reveal">
                    <div class="review-stars" data-rating="5">
                        <span class="s">★</span><span class="s">★</span><span class="s">★</span><span class="s">★</span><span class="s">★</span>
                    </div>
                    <p class="review-text">
                        "Je cherchais un bivouac dans le désert près de Tozeur et HostelFlow m'a orienté parfaitement.
                        Interface intuitive, support réactif. Le rêve sous les étoiles est devenu réalité !"
                    </p>
                    <div class="review-author">
                        <div class="review-avatar" style="background: linear-gradient(135deg, var(--teal2) 0%, var(--teal) 100%)">AB</div>
                        <div class="review-author-info">
                            <span class="review-author-name">Anis Bouazizi</span>
                            <span class="review-author-meta">Monastir · janvier 2025</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endif

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
                    <li><a href="{{ route('super-admin.login') }}" style="color:rgba(254,252,249,0.45);font-size:0.78rem">🛡 Admin</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-copy">© {{ date('Y') }} HostelFlow. Tous droits réservés.</div>
        </div>
    </div>
</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

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

// ── Stars sweep animation on scroll-in ──────────────────────────────────────
const starObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            // small delay so the card fadeUp finishes first
            setTimeout(() => {
                entry.target.querySelector('.review-stars')?.classList.add('animated');
            }, 320);
            starObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.3 });

document.querySelectorAll('.review-card').forEach(card => starObserver.observe(card));

// ── LEAFLET MAP ──────────────────────────────────────────────────────────────
const mapEl = document.getElementById('hostel-map');
@isset($mapHostels)
if (mapEl) {
    const hostels = @json($mapHostels);

    const map = L.map('hostel-map', {
        scrollWheelZoom: false,
        zoomControl: true,
        attributionControl: true,
        maxBounds: [[30.0, 7.0], [37.7, 12.2]],
        maxBoundsViscosity: 1.0,
        minZoom: 6,
        maxZoom: 13,
    }).setView([34.5, 9.5], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18,
    }).addTo(map);

    map.on('click', () => map.scrollWheelZoom.enable());
    map.on('mouseout', () => map.scrollWheelZoom.disable());

    const markers = [];

    hostels.forEach(h => {
        const icon = L.divIcon({
            className: 'hf-marker',
            html: `<div class="hf-marker__pin hf-marker__pin--${h.color}"></div>`,
            iconSize: [22, 22],
            iconAnchor: [11, 11],
            popupAnchor: [0, -10],
        });

        const marker = L.marker([h.lat, h.lng], { icon }).addTo(map);

        const categoryLabel = h.category === 'cj' ? 'Complexe de Jeunes' : 'Maison de Jeunes';
        const imgHtml = h.image
            ? `<img src="${h.image}" alt="${escapeHtml(h.name)}" class="hf-popup__img" onerror="this.outerHTML='<div class=\\'hf-popup__placeholder\\'>🏨</div>'">`
            : `<div class="hf-popup__placeholder">🏨</div>`;

        const regionLine = h.region && h.city
            ? `${escapeHtml(h.city)}, ${escapeHtml(h.region)}`
            : escapeHtml(h.city || h.region || 'Tunisie');

        const popupHtml = `
            <div class="hf-popup">
                ${imgHtml}
                <div class="hf-popup__body">
                    <div class="hf-popup__category hf-popup__category--${h.color}">${categoryLabel}</div>
                    <div class="hf-popup__name">${escapeHtml(h.name)}</div>
                    <div class="hf-popup__region">📍 ${regionLine}</div>
                    <a href="${h.url}" class="hf-popup__btn">Voir le hostel →</a>
                </div>
            </div>
        `;

        marker.bindPopup(popupHtml, {
            maxWidth: 280,
            minWidth: 260,
            offset: [0, -6],
            closeButton: true,
        });

        markers.push(marker);
    });

    if (markers.length > 0) {
        const group = L.featureGroup(markers);
        map.fitBounds(group.getBounds(), { padding: [40, 40], maxZoom: 8 });
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}
@endisset
</script>

</body>
</html>
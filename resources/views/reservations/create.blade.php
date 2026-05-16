@extends('layouts.app')

@section('title', 'Créer une réservation')

@push('styles')
<style>
/* ════════════════════════════════════════════════════
   RESERVATION FORM — uses layout :root variables
   --terra, --teal, --sand, --sand2, --border, --ink,
   --gray, --lgray, --white
════════════════════════════════════════════════════ */

/* ── Hero ── */
.rv-hero {
    background: linear-gradient(135deg, #1B6B6B 0%, #145555 45%, #A84E20 80%, #C8602A 100%);
    border-radius: 24px;
    padding: 36px 40px 36px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(27,107,107,0.22), 0 4px 16px rgba(200,96,42,0.18);
}
.rv-hero::before {
    content: '';
    position: absolute; inset: 0; pointer-events: none;
    background:
        radial-gradient(ellipse 500px 250px at 90% -30%, rgba(200,96,42,0.22) 0%, transparent 70%),
        radial-gradient(ellipse 350px 350px at -5% 120%, rgba(27,107,107,0.3) 0%, transparent 55%);
}
.rv-hero-content {
    position: relative; z-index: 1;
    display: flex; justify-content: space-between; align-items: center;
}
.rv-hero-eyebrow {
    font-size: 0.63rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.16em; color: rgba(255,255,255,0.5);
    margin-bottom: 10px;
}
.rv-hero-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.85rem; font-weight: 700; color: #fff;
    line-height: 1.15; margin-bottom: 6px;
}
.rv-hero-sub {
    font-size: 0.85rem; color: rgba(255,255,255,0.6);
    display: flex; align-items: center; gap: 7px;
}
.rv-back {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.13); border: 1.5px solid rgba(255,255,255,0.22);
    color: #fff; font-size: 0.8rem; font-weight: 600;
    padding: 10px 22px; border-radius: 50px;
    text-decoration: none; transition: all 0.2s;
    backdrop-filter: blur(8px); white-space: nowrap; flex-shrink: 0;
}
.rv-back:hover { background: rgba(255,255,255,0.23); color: #fff; }

/* Step nav */
.rv-steps {
    position: relative; z-index: 1;
    display: flex; gap: 0;
    margin: 28px -40px -36px;
    border-top: 1px solid rgba(255,255,255,0.1);
    background: rgba(0,0,0,0.18);
    backdrop-filter: blur(4px);
}
.rv-step {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 13px 8px;
    font-size: 0.77rem; font-weight: 600;
    color: rgba(255,255,255,0.45);
    cursor: pointer; transition: all 0.2s;
    border-bottom: 3px solid transparent;
    text-align: center; white-space: nowrap;
    border-right: 1px solid rgba(255,255,255,0.07);
    user-select: none;
}
.rv-step:last-child { border-right: none; }
.rv-step:hover { color: rgba(255,255,255,0.8); background: rgba(255,255,255,0.04); }
.rv-step.active { color: #fff; border-bottom-color: rgba(255,255,255,0.75); }
.rv-step-n {
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(255,255,255,0.15); font-size: 0.68rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,0.65); flex-shrink: 0; transition: all 0.2s;
}
.rv-step.active .rv-step-n {
    background: rgba(255,255,255,0.92); color: #1B6B6B;
}

/* ── Progress ── */
.rv-progress {
    background: #fff; border-radius: 14px;
    padding: 12px 20px; margin-bottom: 24px;
    border: 1.5px solid var(--border);
    box-shadow: 0 2px 10px rgba(46,46,58,0.05);
    display: flex; align-items: center; gap: 14px;
}
.rv-progress-label { font-size: 0.7rem; font-weight: 700; color: var(--lgray); text-transform: uppercase; letter-spacing: 0.08em; white-space: nowrap; }
.rv-progress-track { flex: 1; height: 6px; background: var(--sand2); border-radius: 99px; overflow: hidden; }
.rv-progress-fill { height: 100%; background: linear-gradient(90deg, var(--teal), var(--terra)); border-radius: 99px; width: 0%; transition: width 0.5s ease; }
.rv-progress-pct { font-size: 0.75rem; font-weight: 800; color: var(--terra); min-width: 32px; text-align: right; }

/* ── Section cards ── */
.rv-card {
    background: var(--white);
    border: 1.5px solid var(--border);
    border-left: 4px solid var(--terra);
    border-radius: 20px;
    box-shadow: 0 2px 16px rgba(46,46,58,0.06);
    margin-bottom: 22px; overflow: hidden;
    scroll-margin-top: 80px;
}
.rv-card-head {
    display: flex; align-items: center; gap: 14px;
    padding: 18px 24px;
    background: linear-gradient(to right, #fff 0%, var(--sand) 100%);
    border-bottom: 1.5px solid var(--border);
}
.rv-card-num {
    width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, var(--terra), var(--terra2, #A84E20));
    color: #fff; font-size: 0.82rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 12px rgba(200,96,42,0.3);
}
.rv-card-title { font-size: 0.97rem; font-weight: 700; color: var(--ink); line-height: 1.2; }
.rv-card-sub { font-size: 0.7rem; color: var(--lgray); margin-top: 2px; }
.rv-card-body { padding: 24px; }

/* ── Labels ── */
.rv-label {
    display: block; font-size: 0.63rem; font-weight: 700;
    color: var(--lgray); text-transform: uppercase;
    letter-spacing: 0.1em; margin-bottom: 7px;
}
.rv-label .req { color: var(--terra); }

/* ── Inputs ── */
#rv-form input[type="text"],
#rv-form input[type="date"],
#rv-form input[type="number"],
#rv-form input[type="password"],
#rv-form input[type="email"],
#rv-form input[type="tel"],
#rv-form select,
#rv-form textarea {
    width: 100% !important;
    border: 1.5px solid var(--border) !important;
    border-radius: 11px !important;
    background: var(--sand) !important;
    color: var(--ink) !important;
    font-size: 0.88rem !important;
    font-family: 'DM Sans', sans-serif !important;
    padding: 11px 14px !important;
    outline: none !important;
    box-shadow: none !important;
    transition: border-color 0.18s, box-shadow 0.18s !important;
    -webkit-appearance: none;
    appearance: none;
}
#rv-form input:focus,
#rv-form select:focus,
#rv-form textarea:focus {
    border-color: var(--terra) !important;
    box-shadow: 0 0 0 3px rgba(200,96,42,0.1) !important;
    background: #fff !important;
}
#rv-form input[readonly],
#rv-form input:disabled {
    background: var(--sand2) !important;
    color: var(--lgray) !important;
    cursor: not-allowed !important;
}
#rv-form textarea { resize: none !important; }

/* ── Grid layouts ── */
.rv-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
.rv-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.rv-grid-4 { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 14px; }

/* ── Stepper ── */
.rv-stepper { display: flex; align-items: stretch; }
.rv-step-btn {
    width: 36px; flex-shrink: 0; border: 1.5px solid var(--border);
    background: var(--white); color: var(--terra);
    font-size: 1.05rem; font-weight: 800; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s; user-select: none;
}
.rv-step-btn:first-child { border-radius: 10px 0 0 10px; border-right: none; }
.rv-step-btn:last-child  { border-radius: 0 10px 10px 0; border-left: none; }
.rv-step-btn:hover  { background: var(--terra); color: #fff; border-color: var(--terra); }
.rv-step-btn:active { transform: scale(0.9); }
.rv-stepper input[type="number"] {
    border-radius: 0 !important; text-align: center !important;
    border-left: none !important; border-right: none !important;
}
.rv-stepper input::-webkit-outer-spin-button,
.rv-stepper input::-webkit-inner-spin-button { -webkit-appearance: none; }
.rv-stepper input[type=number] { -moz-appearance: textfield; }

/* ── Guest list (JS-generated) ── */
#guest-list { list-style: none; padding: 0; margin: 0; }
#guest-list li {
    padding: 10px 14px !important;
    border-radius: 11px !important;
    border: 1.5px solid var(--border) !important;
    background: var(--sand) !important;
    margin-bottom: 7px !important;
    font-size: 0.83rem !important;
    font-weight: 500 !important;
    color: var(--ink) !important;
    cursor: pointer; transition: all 0.18s !important;
    list-style: none !important;
}
#guest-list li:hover {
    border-color: rgba(200,96,42,0.4) !important;
}
#guest-list li.bg-blue-50 {
    background: rgba(200,96,42,0.08) !important;
    border-color: var(--terra) !important;
    border-width: 2px !important;
    color: var(--terra2, #A84E20) !important;
    font-weight: 700 !important;
}

/* ── Guest detail (JS overrides) ── */
#guest-details > div:not(.rv-empty) {
    border: 1.5px solid var(--border) !important;
    border-radius: 16px !important;
    padding: 22px !important;
    background: #fff !important;
}
#guest-details input, #guest-details select {
    border: 1.5px solid var(--border) !important;
    border-radius: 10px !important;
    background: var(--sand) !important;
    color: var(--ink) !important;
    font-size: 0.86rem !important;
    padding: 9px 12px !important;
    width: 100%; transition: all 0.18s !important;
}
#guest-details input:focus, #guest-details select:focus {
    border-color: var(--terra) !important;
    box-shadow: 0 0 0 3px rgba(200,96,42,0.1) !important;
    outline: none !important; background: #fff !important;
}
#guest-details h3 { font-weight: 700 !important; font-size: 0.95rem !important; color: var(--ink) !important; }
#guest-details p.text-xs.font-bold.text-gray-400 { font-size: 0.62rem !important; letter-spacing: 0.1em !important; color: var(--lgray) !important; }
#guest-details #assign_badge { border-radius: 30px !important; font-size: 0.7rem !important; padding: 3px 12px !important; }
#guest-details .bg-green-100  { background: rgba(27,107,107,0.1) !important; }
#guest-details .text-green-700 { color: var(--teal) !important; }
#guest-details .bg-red-50      { background: rgba(200,96,42,0.07) !important; }
#guest-details .text-red-600   { color: var(--terra) !important; }
#guest-details .text-green-600 { color: var(--teal) !important; }
#guest-details #tnd_box {
    background: rgba(27,107,107,0.07) !important;
    border: 1.5px solid rgba(27,107,107,0.18) !important;
    color: var(--teal) !important; border-radius: 11px !important;
}
#guest-details #tnd_box .text-green-600 { color: var(--teal) !important; }
#guest-details #rate_warn { background: rgba(200,96,42,0.06) !important; border-color: rgba(200,96,42,0.2) !important; color: var(--terra) !important; border-radius: 10px !important; }
#guest-details .bg-yellow-50 { background: rgba(200,96,42,0.06) !important; }
#guest-details .border-yellow-200 { border-color: rgba(200,96,42,0.2) !important; }
#guest-details .text-yellow-700, #guest-details .text-yellow-800 { color: var(--terra) !important; }
#guest-details .border-red-400 { border-color: var(--terra) !important; }
#guest-details .text-orange-500 { color: var(--terra) !important; }
#guest-details #avail_status.text-green-600 { color: var(--teal) !important; }
#guest-details #email_err { color: #c83232 !important; }
#guest-details label.flex.items-center { font-size: 0.83rem !important; color: var(--gray) !important; }

#availability_notice {
    background: rgba(27,107,107,0.08) !important;
    border-color: rgba(27,107,107,0.22) !important;
    color: var(--teal) !important;
    border-radius: 30px !important; font-weight: 600 !important;
}

/* ── Extras ── */
.rv-extra-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 14px; }
.rv-extra {
    background: var(--white); border: 1.5px solid var(--border);
    border-radius: 16px; padding: 18px;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.18s;
}
.rv-extra:hover {
    border-color: rgba(200,96,42,0.4);
    box-shadow: 0 6px 18px rgba(200,96,42,0.1);
    transform: translateY(-2px);
}
.rv-extra.off { opacity: 0.48; pointer-events: none; transform: none; }
.rv-extra-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; }
.rv-extra-name { font-weight: 700; font-size: 0.88rem; color: var(--ink); }
.rv-extra-desc { font-size: 0.72rem; color: var(--gray); margin-top: 2px; }
.rv-badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 30px; font-size: 0.67rem; font-weight: 700; white-space: nowrap; flex-shrink: 0; margin-left: 8px; }
.rv-badge-ok   { background: rgba(27,107,107,0.1); color: #1B6B6B; }
.rv-badge-low  { background: rgba(200,96,42,0.1); color: #C8602A; }
.rv-badge-none { background: rgba(200,50,50,0.1); color: #c83232; }
.rv-badge-inf  { background: rgba(27,107,107,0.08); color: #1B6B6B; }
#extras_total_badge {
    background: rgba(27,107,107,0.1) !important; color: var(--teal) !important;
    font-weight: 700 !important; border-radius: 30px !important;
}
.rv-extra-foot {
    padding-top: 12px; border-top: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}

/* ── Summary table ── */
.rv-table-wrap { border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; }
.rv-table { width: 100%; border-collapse: collapse; font-size: 0.86rem; }
.rv-table thead { background: linear-gradient(135deg, var(--sand), var(--sand2)); }
.rv-table th {
    padding: 11px 16px; text-align: left;
    font-size: 0.62rem; font-weight: 700; color: var(--lgray);
    text-transform: uppercase; letter-spacing: 0.09em;
    border-bottom: 1.5px solid var(--border);
}
.rv-table td { padding: 12px 16px; border-bottom: 1px solid #f1ebe3; color: var(--ink); vertical-align: middle; }
.rv-table tbody tr:last-child td { border-bottom: none; }
.rv-table tbody tr:hover td { background: var(--sand); }
#summary_body td { padding: 12px 16px !important; font-size: 0.86rem !important; }
#summary_body .bg-gray-100 { background: var(--sand) !important; color: var(--gray) !important; border-radius: 6px !important; }
#summary_body .text-red-400 { color: #A84E20 !important; font-style: italic; }

.rv-totals {
    margin-top: 18px; padding: 18px 20px;
    background: var(--sand); border-radius: 14px;
    border: 1.5px solid var(--border);
}
.rv-total-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 5px 0; font-size: 0.86rem; color: var(--gray);
    border-bottom: 1px dashed rgba(0,0,0,0.06);
}
.rv-total-row:last-child { border-bottom: none; }
.rv-total-row strong { color: var(--ink); font-weight: 700; }

.rv-rates {
    margin-top: 18px; padding: 15px 20px;
    background: rgba(27,107,107,0.05);
    border: 1.5px solid rgba(27,107,107,0.15);
    border-radius: 14px;
    display: flex; align-items: center; gap: 24px; flex-wrap: wrap;
}
.rv-rates-title { font-size: 0.62rem; font-weight: 800; color: #1B6B6B; text-transform: uppercase; letter-spacing: 0.12em; white-space: nowrap; }
.rv-rate-pill { display: flex; align-items: center; gap: 10px; font-size: 0.84rem; color: var(--ink); }
.rv-cur { background: #1B6B6B; color: #fff; font-size: 0.62rem; font-weight: 800; padding: 2px 10px; border-radius: 20px; }

/* ── Confirmation ── */
.rv-operator {
    background: var(--sand); border: 1.5px solid var(--border);
    border-radius: 16px; padding: 18px 20px;
    display: flex; align-items: center; gap: 14px;
}
.rv-avatar {
    width: 48px; height: 48px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, var(--terra), #A84E20);
    color: #fff; font-size: 1.2rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 14px rgba(200,96,42,0.28);
}
.rv-op-name { font-weight: 700; font-size: 0.92rem; color: var(--ink); }
.rv-op-role { font-size: 0.72rem; color: var(--gray); margin-top: 2px; }
.rv-online {
    margin-left: auto; background: rgba(27,107,107,0.1);
    color: #1B6B6B; font-size: 0.68rem; font-weight: 700;
    padding: 5px 13px; border-radius: 30px; white-space: nowrap;
}

/* ── Password ── */
.rv-pwd { position: relative; }
.rv-pwd input { padding-right: 44px !important; }
.rv-eye {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; font-size: 1rem;
    color: var(--lgray); padding: 4px; transition: color 0.18s; line-height: 1;
}
.rv-eye:hover { color: var(--terra); }
#password_status { font-size: 0.73rem; margin-top: 7px; display: block; min-height: 1rem; }
#password_status.text-green-600 { color: #1B6B6B !important; }
#password_status.text-red-600   { color: #c83232 !important; }

/* ── Submit ── */
.rv-submit-zone {
    margin-top: 22px; padding: 22px 26px;
    background: linear-gradient(135deg, rgba(200,96,42,0.04), rgba(27,107,107,0.04));
    border: 1.5px solid var(--border); border-radius: 16px;
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
}
.rv-submit-hint { font-size: 0.75rem; color: var(--lgray); display: flex; align-items: center; gap: 8px; }
.rv-btn-submit {
    background: linear-gradient(135deg, var(--terra), #A84E20) !important;
    border: none !important; border-radius: 50px !important;
    padding: 13px 34px !important; font-size: 0.9rem !important; font-weight: 700 !important;
    color: #fff !important; cursor: pointer !important;
    box-shadow: 0 8px 26px rgba(200,96,42,0.35) !important;
    transition: all 0.2s !important;
    display: inline-flex; align-items: center; gap: 8px; white-space: nowrap;
    font-family: 'DM Sans', sans-serif !important;
}
.rv-btn-submit:hover:not(:disabled) {
    transform: translateY(-2px) !important;
    box-shadow: 0 14px 34px rgba(200,96,42,0.45) !important;
}
.rv-btn-submit:disabled { opacity: 0.35 !important; cursor: not-allowed !important; transform: none !important; }

/* ── Alerts ── */
.rv-alert { border-radius: 13px; padding: 13px 18px; font-size: 0.86rem; font-weight: 500; margin-bottom: 20px; }
.rv-alert-ok   { background: rgba(27,107,107,0.07); border: 1.5px solid rgba(27,107,107,0.18); color: #1B6B6B; }
.rv-alert-err  { background: rgba(200,50,50,0.07);  border: 1.5px solid rgba(200,50,50,0.18);  color: #c83232; }
.rv-alert-warn { background: rgba(200,96,42,0.07);  border: 1.5px solid rgba(200,96,42,0.2);   color: #C8602A; }

/* ── Empty state ── */
.rv-empty-state {
    border: 2px dashed rgba(200,96,42,0.2); border-radius: 16px;
    min-height: 130px; display: flex; flex-direction: column;
    align-items: center; justify-content: center; gap: 8px;
    color: #A84E20; font-size: 0.84rem; padding: 24px; text-align: center;
    background: rgba(200,96,42,0.02);
}

/* ── Misc ── */
.rv-section-icon { margin-left: auto; font-size: 1.4rem; opacity: 0.3; }
.rv-gap { margin-bottom: 16px; }
</style>
@endpush

@section('content')
<div style="max-width:960px;margin:0 auto;padding:24px 20px 48px">

    {{-- ══ HERO ══ --}}
    <div class="rv-hero">
        <div class="rv-hero-content">
            <div>
                <div class="rv-hero-eyebrow">Gestion des réservations</div>
                <div class="rv-hero-title">Nouvelle réservation</div>
                <div class="rv-hero-sub">
                    🏨 {{ $activeHostel?->name }}
                </div>
            </div>
            <a id="back-link" href="#" class="rv-back">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour
            </a>
        </div>
        <div class="rv-steps">
            <div class="rv-step active" onclick="scrollTo(1)"><span class="rv-step-n">1</span> Réservation</div>
            <div class="rv-step" onclick="scrollTo(2)"><span class="rv-step-n">2</span> Guests</div>
            <div class="rv-step" onclick="scrollTo(3)"><span class="rv-step-n">3</span> Extras</div>
            <div class="rv-step" onclick="scrollTo(4)"><span class="rv-step-n">4</span> Récap</div>
            <div class="rv-step" onclick="scrollTo(5)"><span class="rv-step-n">5</span> Validation</div>
        </div>
    </div>

    {{-- ══ PROGRESS ══ --}}
    <div class="rv-progress">
        <span class="rv-progress-label">Progression</span>
        <div class="rv-progress-track"><div class="rv-progress-fill" id="rv-fill"></div></div>
        <span class="rv-progress-pct" id="rv-pct">0%</span>
    </div>

    {{-- ══ ALERTS ══ --}}
    @if(session('success'))
        <div class="rv-alert rv-alert-ok">✅ {{ session('success') }}</div>
    @endif
    @if($errors->has('error'))
        <div class="rv-alert rv-alert-err">❌ {{ $errors->first('error') }}</div>
    @endif
    @if($errors->any() && !$errors->has('error'))
        <div class="rv-alert rv-alert-warn">
            <ul style="margin:0;padding-left:16px">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="" id="rv-form">
        @csrf
        <input type="hidden" name="guests_data" id="guests_data">
        <input type="hidden" name="extras_data" id="extras_data">

        {{-- ══ 1 — Informations réservation ══ --}}
        <div class="rv-card" id="rz-sec-1">
            <div class="rv-card-head">
                <span class="rv-card-num">1</span>
                <div>
                    <div class="rv-card-title">Informations réservation</div>
                    <div class="rv-card-sub">Dates, capacité et source</div>
                </div>
                <span class="rv-section-icon">📅</span>
            </div>
            <div class="rv-card-body">
                {{-- Ligne 1 : Dates + Nuits --}}
                <div class="rv-grid-3 rv-gap">
                    <div>
                        <label class="rv-label">Arrivée <span class="req">*</span></label>
                        <input type="date" name="start_date" id="start_date"
                               min="2026-01-01" value="{{ old('start_date') }}" required>
                    </div>
                    <div>
                        <label class="rv-label">Départ <span class="req">*</span></label>
                        <input type="date" name="end_date" id="end_date"
                               min="2026-01-02" value="{{ old('end_date') }}" required>
                    </div>
                    <div>
                        <label class="rv-label">Nuits</label>
                        <input type="number" name="nights" id="nights" readonly value="1">
                    </div>
                </div>
                {{-- Ligne 2 : Personnes + Statut + Source --}}
                <div class="rv-grid-3 rv-gap">
                    <div>
                        <label class="rv-label">Personnes <span class="req">*</span></label>
                        <div class="rv-stepper">
                            <button type="button" class="rv-step-btn" onclick="stepField('total_guests',-1)">−</button>
                            <input type="number" name="total_guests" id="total_guests"
                                   value="1" min="1" required style="width:54px!important">
                            <button type="button" class="rv-step-btn" onclick="stepField('total_guests',1)">+</button>
                        </div>
                    </div>
                    <div>
                        <label class="rv-label">Statut</label>
                        <select name="status">
                            <option value="pending">⏳ En attente</option>
                            <option value="confirmed">✅ Confirmé</option>
                        </select>
                    </div>
                    <div>
                        <label class="rv-label">Source</label>
                        <select name="source">
                            <option value="walk-in">🚶 Walk-in</option>
                            <option value="booking">📱 Booking.com</option>
                            <option value="airbnb">🏠 Airbnb</option>
                            <option value="other">📌 Autre</option>
                        </select>
                    </div>
                </div>
                {{-- Notes --}}
                <div>
                    <label class="rv-label">Notes internes</label>
                    <textarea name="notes" rows="2" placeholder="Demandes spéciales, notes internes…">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ══ 2 — Guests & Affectations ══ --}}
        <div class="rv-card" id="rz-sec-2">
            <div class="rv-card-head">
                <span class="rv-card-num">2</span>
                <div>
                    <div class="rv-card-title">Guests &amp; Affectations</div>
                    <div class="rv-card-sub">Informations personnelles et attribution des lits</div>
                </div>
                <span id="availability_notice" class="hidden ml-auto text-xs px-3 py-1 rounded-full border">
                    ✓ Disponibilité mise à jour
                </span>
            </div>
            <div class="rv-card-body">
                <div style="display:flex;gap:22px">
                    {{-- Sidebar --}}
                    <div style="width:200px;flex-shrink:0">
                        <p style="font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--lgray);margin:0 0 10px;padding-bottom:8px;border-bottom:1.5px solid var(--border)">
                            Liste des guests
                        </p>
                        <ul id="guest-list"></ul>
                    </div>
                    {{-- Detail panel --}}
                    <div style="flex:1;min-width:0" id="guest-details">
                        <div class="rv-empty-state rv-empty">
                            <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.3" style="opacity:.3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Définissez le nombre de personnes<br>pour renseigner les guests.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ 3 — Extras ══ --}}
        <div class="rv-card" id="rz-sec-3">
            <div class="rv-card-head">
                <span class="rv-card-num">3</span>
                <div>
                    <div class="rv-card-title">
                        Extras
                        <span style="font-size:.72rem;font-weight:500;color:var(--lgray);margin-left:6px">(optionnel)</span>
                    </div>
                    <div class="rv-card-sub">Services et prestations additionnels</div>
                </div>
                <span id="extras_total_badge" class="hidden ml-auto text-xs font-semibold px-3 py-1 rounded-full"></span>
            </div>
            <div class="rv-card-body">
                @if(isset($extras) && $extras->count() > 0)
                    <div class="rv-extra-grid">
                        @foreach($extras as $extra)
                            @php
                                $isTracked   = in_array($extra->stock_mode, ['consumable','rentable']);
                                $stock       = $extra->stock_quantity;
                                $isAvailable = !$isTracked || $stock > 0;
                                $badgeCls = $isTracked ? ($stock > 5 ? 'rv-badge-ok' : ($stock > 0 ? 'rv-badge-low' : 'rv-badge-none')) : 'rv-badge-inf';
                                $badgeTxt = $isTracked ? ($stock > 0 ? "Stock : {$stock}" : 'Rupture') : '∞ Illimité';
                            @endphp
                            <div class="rv-extra {{ !$isAvailable ? 'off' : '' }}">
                                <div class="rv-extra-top">
                                    <div>
                                        <div class="rv-extra-name">{{ $extra->name }}</div>
                                        @if($extra->description)
                                            <div class="rv-extra-desc">{{ $extra->description }}</div>
                                        @endif
                                    </div>
                                    <span class="rv-badge {{ $badgeCls }}">{{ $badgeTxt }}</span>
                                </div>
                                <div class="rv-extra-foot">
                                    <span style="font-size:.7rem;font-weight:700;color:var(--lgray);text-transform:uppercase;letter-spacing:.08em">Quantité</span>
                                    <div class="rv-stepper">
                                        <button type="button" class="rv-step-btn" onclick="stepExtra(this,-1)" {{ !$isAvailable ? 'disabled' : '' }}>−</button>
                                        <input type="number"
                                               class="extra-qty-input"
                                               style="width:50px!important;text-align:center!important"
                                               min="0" max="{{ $isTracked ? $stock : 999 }}"
                                               value="0"
                                               data-extra-id="{{ $extra->id }}"
                                               data-max="{{ $isTracked ? $stock : 999 }}"
                                               {{ !$isAvailable ? 'disabled' : '' }}>
                                        <button type="button" class="rv-step-btn" onclick="stepExtra(this,1)" {{ !$isAvailable ? 'disabled' : '' }}>+</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rv-empty-state">🛒 Aucun extra disponible pour cet hostel.</div>
                @endif
            </div>
        </div>

        {{-- ══ 4 — Récapitulatif ══ --}}
        <div class="rv-card" id="rz-sec-4">
            <div class="rv-card-head">
                <span class="rv-card-num">4</span>
                <div>
                    <div class="rv-card-title">Récapitulatif tarification</div>
                    <div class="rv-card-sub">Prix par guest et conversion de devises</div>
                </div>
                <span class="rv-section-icon">📊</span>
            </div>
            <div class="rv-card-body">
                <div class="rv-table-wrap">
                    <table class="rv-table">
                        <thead>
                            <tr>
                                <th>Guest</th>
                                <th>Affectation</th>
                                <th>Prix saisi</th>
                                <th>Devise</th>
                                <th>≈ TND</th>
                            </tr>
                        </thead>
                        <tbody id="summary_body">
                            <tr>
                                <td colspan="5" style="text-align:center;padding:28px;color:var(--lgray);font-style:italic">
                                    Aucun guest configuré
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="rv-totals" style="margin-top:18px">
                    <div class="rv-total-row"><span>Total TND</span><strong id="total_tnd">0.000</strong></div>
                    <div class="rv-total-row"><span>Total EUR</span><strong id="total_eur">0.000</strong></div>
                    <div class="rv-total-row"><span>Total USD</span><strong id="total_usd">0.000</strong></div>
                    <div class="rv-total-row" id="extras_summary_row" style="display:none">
                        <span style="color:#1B6B6B;font-weight:600">+ Extras</span>
                        <strong id="extras_tnd_display" style="color:#1B6B6B">0.000 TND</strong>
                    </div>
                </div>

                @php
                    $eurRate = $rates->get('EUR');
                    $usdRate = $rates->get('USD');
                    $eurSell = $eurRate ? number_format((float)$eurRate->sell_rate_to_tnd, 4, '.', '') : null;
                    $eurBuy  = $eurRate ? number_format((float)$eurRate->buy_rate_to_tnd,  4, '.', '') : null;
                    $usdSell = $usdRate ? number_format((float)$usdRate->sell_rate_to_tnd, 4, '.', '') : null;
                    $usdBuy  = $usdRate ? number_format((float)$usdRate->buy_rate_to_tnd,  4, '.', '') : null;
                @endphp
                <div class="rv-rates">
                    <span class="rv-rates-title">🔄 Taux de change</span>
                    <span class="rv-rate-pill">
                        <span class="rv-cur">EUR</span>
                        Vente : <strong>{{ $eurSell ?? '—' }}</strong> · Achat : <strong>{{ $eurBuy ?? '—' }}</strong>
                    </span>
                    <span class="rv-rate-pill">
                        <span class="rv-cur">USD</span>
                        Vente : <strong>{{ $usdSell ?? '—' }}</strong> · Achat : <strong>{{ $usdBuy ?? '—' }}</strong>
                    </span>
                </div>
            </div>
        </div>

        {{-- ══ 5 — Confirmation ══ --}}
        <div class="rv-card" id="rz-sec-5" style="margin-bottom:0">
            <div class="rv-card-head">
                <span class="rv-card-num">5</span>
                <div>
                    <div class="rv-card-title">Confirmation &amp; Sécurité</div>
                    <div class="rv-card-sub">Validation par mot de passe avant création</div>
                </div>
                <span class="rv-section-icon">🔒</span>
            </div>
            <div class="rv-card-body">
                <div class="rv-grid-2" style="margin-bottom:22px">
                    <div>
                        <label class="rv-label">Opérateur connecté</label>
                        <div class="rv-operator">
                            <div class="rv-avatar">{{ strtoupper(mb_substr($currentUser['name'], 0, 1)) }}</div>
                            <div style="flex:1;min-width:0">
                                <div class="rv-op-name">{{ $currentUser['name'] }}</div>
                                <div class="rv-op-role">{{ $currentUser['role'] }}</div>
                            </div>
                            <span class="rv-online">● Connecté</span>
                        </div>
                    </div>
                    <div>
                        <label class="rv-label">Mot de passe de confirmation <span class="req">*</span></label>
                        <div class="rv-pwd">
                            <input type="password" name="password" id="password_input"
                                   placeholder="Entrez votre mot de passe"
                                   autocomplete="current-password">
                            <button type="button" class="rv-eye" id="_pwd_eye"
                                    onclick="togglePwd()" title="Afficher/masquer">👁</button>
                        </div>
                        <p id="password_status"></p>
                    </div>
                </div>

                <div class="rv-submit-zone">
                    <div class="rv-submit-hint">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Mot de passe requis pour valider la réservation.
                    </div>
                    <button type="submit" id="submit_btn" disabled class="rv-btn-submit">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Créer la réservation
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

@php
    $eurRate = $rates->get('EUR');
    $usdRate = $rates->get('USD');
    $jsEurSell = $eurRate ? (float)$eurRate->sell_rate_to_tnd : 0;
    $jsEurBuy  = $eurRate ? (float)$eurRate->buy_rate_to_tnd  : 0;
    $jsUsdSell = $usdRate ? (float)$usdRate->sell_rate_to_tnd : 0;
    $jsUsdBuy  = $usdRate ? (float)$usdRate->buy_rate_to_tnd  : 0;
@endphp

@push('scripts')
<script>
/* ── Navigation ── */
function scrollTo(n) {
    var el = document.getElementById('rz-sec-' + n);
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
(function() {
    function sync() {
        var tabs = document.querySelectorAll('.rv-step');
        var cur = 0;
        for (var i = 1; i <= 5; i++) {
            var s = document.getElementById('rz-sec-' + i);
            if (s && s.getBoundingClientRect().top <= 100) cur = i - 1;
        }
        tabs.forEach(function(t, idx) { t.classList.toggle('active', idx === cur); });
    }
    window.addEventListener('scroll', sync, { passive: true });
})();

/* ── Progress ── */
function updateProgress() {
    var checks = [
        !!document.getElementById('start_date')?.value,
        !!document.getElementById('end_date')?.value,
        parseInt(document.getElementById('total_guests')?.value || 0) >= 1,
        !document.getElementById('submit_btn')?.disabled
    ];
    var pct = Math.round(checks.filter(Boolean).length / checks.length * 100);
    var fill = document.getElementById('rv-fill');
    var lbl  = document.getElementById('rv-pct');
    if (fill) fill.style.width = pct + '%';
    if (lbl)  lbl.textContent  = pct + '%';
}

/* ── Steppers ── */
function stepField(id, delta) {
    var el = document.getElementById(id);
    if (!el || el.disabled) return;
    el.value = Math.max(parseInt(el.min)||0, Math.min(parseInt(el.max)||9999, (parseInt(el.value)||0) + delta));
    el.dispatchEvent(new Event('change', { bubbles: true }));
    el.dispatchEvent(new Event('input',  { bubbles: true }));
    updateProgress();
}
function stepExtra(btn, delta) {
    var input = btn.parentElement.querySelector('.extra-qty-input');
    if (!input || input.disabled) return;
    var max = parseInt(input.dataset.max || 999);
    input.value = Math.max(0, Math.min(max, (parseInt(input.value)||0) + delta));
    input.dispatchEvent(new Event('input', { bubbles: true }));
}
function togglePwd() {
    var i = document.getElementById('password_input');
    var e = document.getElementById('_pwd_eye');
    if (!i) return;
    i.type = i.type === 'password' ? 'text' : 'password';
    if (e) e.textContent = i.type === 'password' ? '👁' : '🙈';
}
</script>

<script>
var ROOMS       = {!! json_encode($rooms->load('beds')) !!};
var TENT_SPACES = {!! json_encode($tentSpaces) !!};
var COUNTRIES   = {!! json_encode($countries) !!};
var RATES = {
    eur: { sell: {{ $jsEurSell }}, buy: {{ $jsEurBuy }} },
    usd: { sell: {{ $jsUsdSell }}, buy: {{ $jsUsdBuy }} }
};

(function() {
    var p = window.location.pathname.split('/')[1] || '';
    window._HOSTEL_PREFIX = (p === 'manager' || p === 'staff') ? '/' + p : '';
})();

var ROUTES = {
    availableUnits: window.location.origin + window._HOSTEL_PREFIX + '/reservations/available-units',
    checkPassword:  window.location.origin + window._HOSTEL_PREFIX + '/reservations/check-password',
    csrf: '{{ csrf_token() }}'
};
var TODAY = new Date().toISOString().split('T')[0];

var extrasMap = {};
function updateExtrasBadge() {
    var count = 0, total = 0;
    Object.keys(extrasMap).forEach(function(id) { var q = parseInt(extrasMap[id])||0; if (q>0){count++;total+=q;} });
    var badge = document.getElementById('extras_total_badge');
    var row   = document.getElementById('extras_summary_row');
    if (badge) { if (count>0){badge.textContent=count+' extra(s) — '+total+' unité(s)';badge.classList.remove('hidden');}else badge.classList.add('hidden'); }
    if (row) row.style.display = count>0 ? 'flex' : 'none';
}
function serializeExtras() {
    var r=[];
    Object.keys(extrasMap).forEach(function(id){var q=parseInt(extrasMap[id])||0;if(q>0)r.push({extra_id:parseInt(id),quantity:q});});
    return JSON.stringify(r);
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var _prefix = window._HOSTEL_PREFIX || '';
    var _form   = document.getElementById('rv-form');
    if (_form) _form.action = window.location.origin + _prefix + '/reservations';
    var _back = document.getElementById('back-link');
    if (_back) _back.href = window.location.origin + _prefix + '/reservations';

    document.querySelectorAll('.extra-qty-input').forEach(function(input) {
        input.addEventListener('input', function() {
            var max = parseInt(this.dataset.max||999), val = parseInt(this.value)||0;
            if (val<0){val=0;this.value=0;} if (val>max){val=max;this.value=max;}
            extrasMap[this.dataset.extraId] = val;
            updateExtrasBadge();
        });
    });

    var guests=[],selectedIdx=0,availableUnits=buildStaticUnits();
    var $start=$('#start_date'),$end=$('#end_date'),$nights=$('#nights'),$nb=$('#total_guests');
    var $list=$('#guest-list'),$detail=$('#guest-details'),$tbody=$('#summary_body');
    var $tTnd=$('#total_tnd'),$tEur=$('#total_eur'),$tUsd=$('#total_usd');
    var $pwd=$('#password_input'),$btn=$('#submit_btn'),$pwdSt=$('#password_status');
    var $gdata=$('#guests_data'),$edata=$('#extras_data'),$notice=$('#availability_notice');

    function $(id){return document.getElementById(id);}
    $start=$('start_date');$end=$('end_date');$nights=$('nights');$nb=$('total_guests');
    $list=$('guest-list');$detail=$('guest-details');$tbody=$('summary_body');
    $tTnd=$('total_tnd');$tEur=$('total_eur');$tUsd=$('total_usd');
    $pwd=$('password_input');$btn=$('submit_btn');$pwdSt=$('password_status');
    $gdata=$('guests_data');$edata=$('extras_data');$notice=$('availability_notice');

    if (!$start.value){$start.value=TODAY;}
    if (!$end.value){var t=new Date(TODAY);t.setDate(t.getDate()+1);$end.value=t.toISOString().split('T')[0];}
    calcNights(); updateProgress();

    function buildStaticUnits(){
        var beds=[],rooms=[],tents=[];
        ROOMS.forEach(function(r){
            if(r.type==='dormitory')(r.beds||[]).forEach(function(b){if(b.is_enabled!==false)beds.push({id:b.id,name:r.name+' — '+b.name});});
            else if(r.type==='private')rooms.push({id:r.id,name:r.name,capacity:r.capacity||1,remaining_capacity:r.capacity||1});
        });
        TENT_SPACES.forEach(function(s){tents.push({id:s.id,name:s.name,capacity:s.capacity||1,remaining_capacity:s.capacity||1});});
        return{beds:beds,rooms:rooms,tent_spaces:tents};
    }
    function emptyGuest(i){return{first_name:'',last_name:'',identity_card:'',email:'',phone:'',country_id:COUNTRIES.length?COUNTRIES[0].id:'',gender:'male',same_as_main:i!==0,item_type:'bed',item_id:'',price_input:0,currency:'TND',price_tnd:0,exchange_rate:1};}
    function calcNights(){if(!$start.value||!$end.value){$nights.value=0;return;}var d=(new Date($end.value)-new Date($start.value))/86400000;$nights.value=d>0?Math.round(d):0;}
    function generateGuests(n){var old=guests.slice();guests=[];for(var i=0;i<n;i++)guests.push(old[i]!==undefined?old[i]:emptyGuest(i));selectedIdx=Math.min(selectedIdx,guests.length-1);renderList();renderDetail();calcTotals();}

    function renderList(){
        $list.innerHTML='';
        guests.forEach(function(g,i){
            var li=document.createElement('li');
            var nm=g.first_name?' '+g.first_name:'';
            li.textContent=(g.item_id?'✅':'⚠️')+' '+(i===0?'Guest 1 (Principal)':'Guest '+(i+1))+nm;
            li.className='px-3 py-2 rounded-lg text-sm cursor-pointer border transition-all '+(i===selectedIdx?'bg-blue-50 border-blue-300 text-blue-700 font-medium':'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100');
            li.addEventListener('click',(function(idx){return function(){selectedIdx=idx;renderList();renderDetail();};})(i));
            $list.appendChild(li);
        });
    }

    function renderDetail(){
        var g=guests[selectedIdx],isMain=selectedIdx===0,label=isMain?'Guest Principal':'Guest '+(selectedIdx+1);
        var copts=COUNTRIES.map(function(c){return'<option value="'+c.id+'"'+(Number(g.country_id)===Number(c.id)?' selected':'')+'>'+esc(c.name)+'</option>';}).join('');
        var hasDates=$start.value&&$end.value;
        $detail.innerHTML=
            '<div class="border border-gray-200 rounded-xl p-4">'+
            '<div class="flex items-center justify-between mb-3">'+
            '<h3 class="font-semibold text-gray-800">'+esc(label)+'</h3>'+
            '<span id="assign_badge" class="text-xs font-semibold px-2 py-0.5 rounded-full '+(g.item_id?'bg-green-100 text-green-700':'bg-red-50 text-red-600')+'">'+(g.item_id?'Affecté ✓':'Non affecté')+'</span></div>'+
            (!isMain?'<label class="flex items-center gap-2 text-sm text-gray-600 mb-3 cursor-pointer select-none"><input type="checkbox" id="same_as_main" '+(g.same_as_main?'checked':'')+' class="w-4 h-4 rounded border-gray-300 text-blue-600 cursor-pointer">Même informations que le guest principal</label>':'')+
            '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">'+
            '<div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Informations</p>'+
            '<input type="text" id="first_name" placeholder="Nom *" value="'+esc(g.first_name)+'" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">'+
            '<input type="text" id="last_name" placeholder="Prénom *" value="'+esc(g.last_name)+'" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">'+
            '<input type="text" id="identity_card" placeholder="CIN / Passeport" value="'+esc(g.identity_card)+'" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">'+
            '<input type="email" id="email" placeholder="Email (ex: nom@mail.com)" value="'+esc(g.email)+'" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-1 focus:outline-none focus:ring-2 focus:ring-blue-400">'+
            '<span id="email_err" class="hidden text-xs text-red-500 mb-1 block">Format invalide</span>'+
            '<input type="tel" id="phone" placeholder="Téléphone" value="'+esc(g.phone)+'" inputmode="numeric" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">'+
            '<select id="gender" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400"><option value="male"'+(g.gender==='male'?' selected':'')+'>Homme</option><option value="female"'+(g.gender==='female'?' selected':'')+'>Femme</option></select>'+
            '<select id="country_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">'+copts+'</select></div>'+
            '<div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Affectation</p>'+
            '<select id="item_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">'+
            '<option value="bed"'+(g.item_type==='bed'?' selected':'')+'>🛏 Dormitory (lit)</option>'+
            '<option value="room"'+(g.item_type==='room'?' selected':'')+'>🚪 Chambre privée</option>'+
            '<option value="tent_space"'+(g.item_type==='tent_space'?' selected':'')+'>⛺ Tente</option>'+
            '</select>'+
            '<select id="item_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></select>'+
            '<p id="avail_status" class="text-xs mt-2 '+(hasDates?'text-green-600':'text-gray-400')+'">'+(hasDates?'✓ Disponibilité filtrée selon les dates.':'⚠ Sélectionnez des dates pour filtrer.')+'</p></div>'+
            '<div><p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Tarification</p>'+
            '<input type="number" id="price_input" value="'+g.price_input+'" min="0" step="0.001" placeholder="Prix" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">'+
            '<select id="currency" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">'+
            '<option value="TND"'+(g.currency==='TND'?' selected':'')+'>TND — Dinar</option>'+
            '<option value="EUR"'+(g.currency==='EUR'?' selected':'')+'>EUR — Euro</option>'+
            '<option value="USD"'+(g.currency==='USD'?' selected':'')+'>USD — Dollar</option>'+
            '</select>'+
            '<div id="tnd_box" class="bg-green-50 border border-green-200 rounded-lg px-3 py-2 text-sm text-green-800">'+
            '≈ <strong><span id="tnd_disp">'+Number(g.price_tnd).toFixed(3)+'</span> TND</strong><br>'+
            '<span class="text-xs text-green-600">Taux (vente) : <span id="rate_disp">'+g.exchange_rate+'</span></span></div>'+
            '<div id="rate_warn" class="hidden mt-1 bg-yellow-50 border border-yellow-200 rounded-lg px-3 py-2 text-xs text-yellow-700">⚠️ Taux non configuré.</div>'+
            '</div></div></div>';
        bindInputs();loadUnits();calcPrice();
    }

    function bindInputs(){
        var g=guests[selectedIdx],isMain=selectedIdx===0;
        ['first_name','last_name','identity_card','gender','country_id'].forEach(function(f){
            var el=document.getElementById(f);if(!el)return;
            var fn=function(){g[f]=el.value;if(isMain)propagate();calcTotals();renderList();};
            el.addEventListener('input',fn);el.addEventListener('change',fn);
        });
        var em=document.getElementById('email');
        if(em)em.addEventListener('input',function(){g.email=this.value;if(isMain)propagate();var err=document.getElementById('email_err');if(this.value&&!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value)){this.classList.add('border-red-400');if(err)err.classList.remove('hidden');}else{this.classList.remove('border-red-400');if(err)err.classList.add('hidden');}});
        var ph=document.getElementById('phone');
        if(ph)ph.addEventListener('input',function(){var c=this.value.replace(/[^0-9+\s\-()]/g,'');if(this.value!==c)this.value=c;g.phone=c;if(isMain)propagate();});
        if(!isMain){var cb=document.getElementById('same_as_main');if(cb)cb.addEventListener('change',function(){g.same_as_main=this.checked;if(this.checked){copyMain(g);renderDetail();}});}
        var ty=document.getElementById('item_type');if(ty)ty.addEventListener('change',function(){g.item_type=this.value;g.item_id='';loadUnits();renderList();calcTotals();});
        var ui=document.getElementById('item_id');if(ui)ui.addEventListener('change',function(){g.item_id=this.value;renderList();calcTotals();var b=document.getElementById('assign_badge');if(b){b.className='text-xs font-semibold px-2 py-0.5 rounded-full '+(g.item_id?'bg-green-100 text-green-700':'bg-red-50 text-red-600');b.textContent=g.item_id?'Affecté ✓':'Non affecté';}});
        var pr=document.getElementById('price_input');if(pr)pr.addEventListener('input',function(){g.price_input=parseFloat(this.value)||0;calcPrice();calcTotals();});
        var cu=document.getElementById('currency');if(cu)cu.addEventListener('change',function(){g.currency=this.value;calcPrice();calcTotals();});
    }
    function copyMain(t){var m=guests[0];['first_name','last_name','identity_card','email','phone','country_id','gender'].forEach(function(f){t[f]=m[f];});}
    function propagate(){guests.forEach(function(g,i){if(i&&g.same_as_main)copyMain(g);});}

    function loadUnits(){
        var g=guests[selectedIdx],el=document.getElementById('item_id');if(!el)return;
        el.innerHTML='<option value="">— Sélectionner —</option>';
        var lists={bed:availableUnits.beds,room:availableUnits.rooms,tent_space:availableUnits.tent_spaces};
        var list=lists[g.item_type]||[];
        if(list.length===0){var no=document.createElement('option');no.disabled=true;no.textContent='Aucune unité disponible';el.appendChild(no);g.item_id='';var s=document.getElementById('avail_status');if(s){s.textContent='⚠️ Aucune unité disponible.';s.className='text-xs mt-2 text-orange-500';}return;}
        list.forEach(function(u){var opt=document.createElement('option');opt.value=String(u.id);opt.textContent=u.remaining_capacity!==undefined?u.name+' ('+u.remaining_capacity+' place(s))':u.name;el.appendChild(opt);});
        var cv=g.item_id&&list.some(function(u){return String(u.id)===String(g.item_id);});
        if(cv){el.value=String(g.item_id);}else{g.item_id=String(list[0].id);el.value=g.item_id;var b=document.getElementById('assign_badge');if(b){b.className='text-xs font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700';b.textContent='Affecté ✓';}renderList();calcTotals();}
        var s=document.getElementById('avail_status');if(s){s.textContent=($start.value&&$end.value)?'✓ Disponibilité filtrée selon les dates.':'⚠ Toutes unités affichées.';s.className='text-xs mt-2 '+($start.value&&$end.value?'text-green-600':'text-gray-400');}
    }
    function calcPrice(){
        var g=guests[selectedIdx],p=parseFloat(g.price_input)||0;
        if(g.currency==='TND'){g.exchange_rate=1;g.price_tnd=p;}
        else if(g.currency==='EUR'){g.exchange_rate=RATES.eur.sell;g.price_tnd=p*g.exchange_rate;}
        else if(g.currency==='USD'){g.exchange_rate=RATES.usd.sell;g.price_tnd=p*g.exchange_rate;}
        var de=document.getElementById('tnd_disp'),re=document.getElementById('rate_disp'),we=document.getElementById('rate_warn'),be=document.getElementById('tnd_box');
        if(de)de.textContent=Number(g.price_tnd).toFixed(3);
        if(re)re.textContent=g.currency==='TND'?'1 (TND)':String(g.exchange_rate);
        var zero=g.currency!=='TND'&&g.exchange_rate===0;
        if(we)we.classList.toggle('hidden',!zero);
        if(be)be.className='rounded-lg px-3 py-2 text-sm '+(zero?'bg-yellow-50 border border-yellow-200 text-yellow-800':'bg-green-50 border border-green-200 text-green-800');
    }
    function calcTotals(){
        var tnd=0,eur=0,usd=0;$tbody.innerHTML='';
        guests.forEach(function(g,i){
            tnd+=Number(g.price_tnd)||0;
            if(g.currency==='EUR')eur+=Number(g.price_input)||0;
            if(g.currency==='USD')usd+=Number(g.price_input)||0;
            var tr=document.createElement('tr');
            tr.className='border-b border-gray-50 hover:bg-gray-50 transition';
            tr.innerHTML='<td class="px-3 py-2 text-sm">'+(esc(g.first_name)||'—')+' '+esc(g.last_name)+'</td>'+
                '<td class="px-3 py-2 text-sm text-gray-500">'+getUnitLabel(g)+'</td>'+
                '<td class="px-3 py-2 text-sm">'+Number(g.price_input).toFixed(3)+'</td>'+
                '<td class="px-3 py-2 text-sm"><span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded font-medium">'+g.currency+'</span></td>'+
                '<td class="px-3 py-2 text-sm font-semibold">'+Number(g.price_tnd).toFixed(3)+'</td>';
            $tbody.appendChild(tr);
        });
        $tTnd.textContent=tnd.toFixed(3);$tEur.textContent=eur.toFixed(3);$tUsd.textContent=usd.toFixed(3);
    }
    function getUnitLabel(g){
        if(!g.item_id)return'<span class="text-red-400 text-xs italic">Non affecté</span>';
        var m={bed:availableUnits.beds,room:availableUnits.rooms,tent_space:availableUnits.tent_spaces};
        var item=(m[g.item_type]||[]).find(function(u){return String(u.id)===String(g.item_id);});
        return item?esc(item.name):'Unité sélectionnée';
    }
    function fetchAvailability(){
        if(!$start.value||!$end.value){availableUnits=buildStaticUnits();guests.forEach(function(g){var lm={bed:availableUnits.beds,room:availableUnits.rooms,tent_space:availableUnits.tent_spaces};if(!lm[g.item_type].some(function(u){return String(u.id)===String(g.item_id);}))g.item_id='';});renderDetail();renderList();calcTotals();return;}
        fetch(ROUTES.availableUnits+'?start_date='+$start.value+'&end_date='+$end.value)
            .then(function(r){if(!r.ok)throw new Error();return r.json();})
            .then(function(data){availableUnits=data;guests.forEach(function(g){var lm={bed:data.beds||[],room:data.rooms||[],tent_space:data.tent_spaces||[]};if(!(lm[g.item_type]||[]).some(function(u){return String(u.id)===String(g.item_id);}))g.item_id='';});if($notice)$notice.classList.remove('hidden');renderDetail();renderList();calcTotals();})
            .catch(function(){availableUnits=buildStaticUnits();renderDetail();renderList();calcTotals();});
    }

    var pwdTimer=null;
    $pwd.addEventListener('input',function(){
        clearTimeout(pwdTimer);$btn.disabled=true;$pwdSt.textContent='';updateProgress();
        if(this.value.length<4)return;
        var val=this.value;
        pwdTimer=setTimeout(function(){
            fetch(ROUTES.checkPassword,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':ROUTES.csrf},body:JSON.stringify({password:val})})
            .then(function(r){return r.json();})
            .then(function(d){if(d.success){$btn.disabled=false;$pwdSt.className='text-xs min-h-4 mt-1 text-green-600';$pwdSt.textContent='✅ Mot de passe correct';}else{$btn.disabled=true;$pwdSt.className='text-xs min-h-4 mt-1 text-red-600';$pwdSt.textContent='❌ Mot de passe incorrect';}updateProgress();})
            .catch(function(){$btn.disabled=true;$pwdSt.className='text-xs min-h-4 mt-1 text-red-600';$pwdSt.textContent='⚠️ Erreur réseau';updateProgress();});
        },500);
    });

    var _form=document.getElementById('rv-form');
    _form.addEventListener('submit',function(e){
        for(var i=0;i<guests.length;i++){
            var g=guests[i];
            if(!g.first_name.trim()){e.preventDefault();alert('❌ Guest '+(i+1)+' : le nom est obligatoire.');selectedIdx=i;renderList();renderDetail();return;}
            if(!g.last_name.trim()){e.preventDefault();alert('❌ Guest '+(i+1)+' : le prénom est obligatoire.');selectedIdx=i;renderList();renderDetail();return;}
            if(!g.item_id){var lm={bed:availableUnits.beds||[],room:availableUnits.rooms||[],tent_space:availableUnits.tent_spaces||[]};var lst=lm[g.item_type]||[];if(lst.length>0){g.item_id=String(lst[0].id);}else{e.preventDefault();alert('❌ Guest '+(i+1)+' : aucune unité disponible.');return;}}
        }
        calcTotals();
        $gdata.value=JSON.stringify(guests);
        if($edata)$edata.value=serializeExtras();
    });

    $start.addEventListener('change',function(){if(this.value){var n=new Date(this.value);n.setDate(n.getDate()+1);var ns=n.toISOString().split('T')[0];$end.min=ns;if($end.value&&$end.value<=this.value)$end.value=ns;}calcNights();fetchAvailability();updateProgress();});
    $end.addEventListener('change',function(){calcNights();fetchAvailability();updateProgress();});
    $nb.addEventListener('change',function(){var n=parseInt(this.value);if(!n||n<1){n=1;this.value=1;}generateGuests(n);updateProgress();});

    function esc(s){if(!s)return'';return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');}

    generateGuests(1);fetchAvailability();
});
</script>
@endpush
@endsection

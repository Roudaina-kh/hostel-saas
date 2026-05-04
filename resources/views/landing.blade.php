<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HostelFlow — Voyagez libre, dormez bien</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --cream: #FDF8F2;
  --warm-white: #FFFCF7;
  --coral: #FF6B47;
  --coral-light: #FF8A6A;
  --teal: #2ABFBF;
  --teal-dark: #1A9999;
  --sand: #F0E6D3;
  --sand-dark: #D4B896;
  --charcoal: #2C2C2C;
  --gray: #6B6B6B;
  --gray-light: #A0A0A0;
  --border: #E8DDD0;
  --shadow: rgba(44,44,44,0.08);
  --shadow-strong: rgba(44,44,44,0.22);
}
* { margin:0; padding:0; box-sizing:border-box; }
html { scroll-behavior:smooth; }
body { font-family:'DM Sans',sans-serif; background:var(--cream); color:var(--charcoal); overflow-x:hidden; }
::-webkit-scrollbar{width:5px}::-webkit-scrollbar-track{background:var(--sand)}::-webkit-scrollbar-thumb{background:var(--sand-dark);border-radius:4px}::-webkit-scrollbar-thumb:hover{background:var(--coral)}

/* ══ MODAL OVERLAY ══ */
.modal-overlay {
  position:fixed; inset:0; z-index:1000;
  background:rgba(20,20,20,0.65); backdrop-filter:blur(8px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity 0.3s; padding:20px;
}
.modal-overlay.open { opacity:1; pointer-events:all; }
.modal-box {
  background:var(--warm-white); border-radius:24px;
  width:100%; max-width:580px; max-height:90vh; overflow-y:auto;
  padding:40px;
  transform:translateY(30px) scale(0.97);
  transition:transform 0.3s cubic-bezier(0.34,1.56,0.64,1);
  position:relative; box-shadow:0 30px 80px rgba(0,0,0,0.25);
}
.modal-overlay.open .modal-box { transform:translateY(0) scale(1); }
.modal-close {
  position:absolute; top:18px; right:20px;
  background:var(--sand); border:none; border-radius:50%;
  width:34px; height:34px; font-size:1rem; cursor:pointer;
  display:flex; align-items:center; justify-content:center;
  transition:all 0.2s; color:var(--charcoal);
}
.modal-close:hover { background:var(--coral); color:#fff; }
.modal-title { font-family:'Fraunces',serif; font-size:1.8rem; font-weight:600; margin-bottom:6px; color:var(--charcoal); }
.modal-sub { font-size:0.88rem; color:var(--gray); margin-bottom:2rem; line-height:1.6; }

/* Form */
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.form-group { display:flex; flex-direction:column; gap:6px; }
.form-group.full { grid-column:span 2; }
.form-label { font-size:0.75rem; font-weight:600; color:var(--gray); text-transform:uppercase; letter-spacing:0.06em; }
.form-input,.form-select,.form-textarea {
  font-family:'DM Sans',sans-serif; font-size:0.88rem; font-weight:400;
  color:var(--charcoal); background:var(--cream);
  border:1.5px solid var(--border); border-radius:12px;
  padding:11px 14px; transition:border-color 0.2s,box-shadow 0.2s; outline:none;
}
.form-input:focus,.form-select:focus,.form-textarea:focus {
  border-color:var(--teal); box-shadow:0 0 0 3px rgba(42,191,191,0.12);
}
.form-textarea { resize:vertical; min-height:90px; }
.form-select { appearance:none; cursor:pointer; }
.btn-submit {
  width:100%; padding:14px; margin-top:10px;
  background:var(--coral); color:#fff; border:none; border-radius:14px;
  font-family:'DM Sans',sans-serif; font-size:0.95rem; font-weight:700;
  cursor:pointer; transition:all 0.25s;
  box-shadow:0 6px 20px rgba(255,107,71,0.35);
}
.btn-submit:hover { background:#e85535; transform:translateY(-2px); box-shadow:0 10px 28px rgba(255,107,71,0.4); }
.btn-submit-teal {
  width:100%; padding:14px; margin-top:10px;
  background:var(--teal); color:#fff; border:none; border-radius:14px;
  font-family:'DM Sans',sans-serif; font-size:0.95rem; font-weight:700;
  cursor:pointer; transition:all 0.25s;
  box-shadow:0 6px 20px rgba(42,191,191,0.35);
  text-decoration:none; display:block; text-align:center;
}
.btn-submit-teal:hover { background:var(--teal-dark); transform:translateY(-2px); }

/* Login */
.login-box { max-width:440px; }
.login-divider { display:flex; align-items:center; gap:12px; margin:18px 0; }
.login-divider::before,.login-divider::after { content:''; flex:1; height:1px; background:var(--border); }
.login-divider span { font-size:0.75rem; color:var(--gray-light); }
.forgot-link { font-size:0.8rem; color:var(--teal-dark); text-decoration:none; display:block; text-align:right; margin-top:4px; }
.forgot-link:hover { text-decoration:underline; }
.login-error {
  background:#FEE2E2; border:1px solid #FECACA; color:#DC2626;
  padding:10px 14px; border-radius:10px; font-size:0.82rem; margin-bottom:14px;
  display:none;
}

/* Blog modal */
.blog-box { max-width:680px; }
.blog-hero-img { width:100%; height:220px; object-fit:cover; border-radius:14px; margin-bottom:1.5rem; }
.blog-tag { display:inline-block; background:var(--sand); color:var(--coral); font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; padding:4px 12px; border-radius:20px; margin-bottom:12px; }
.blog-content { color:var(--gray); line-height:1.85; font-size:0.92rem; }
.blog-content h3 { font-family:'Fraunces',serif; font-size:1.2rem; color:var(--charcoal); margin:1.4rem 0 0.5rem; }
.blog-content p { margin-bottom:1rem; }
.blog-meta { display:flex; align-items:center; gap:16px; font-size:0.78rem; color:var(--gray-light); margin-bottom:1.5rem; }

/* ══ NAVBAR ══ */
nav {
  position:fixed; top:0; left:0; right:0; z-index:100;
  display:flex; align-items:center; justify-content:space-between;
  padding:0 2.5rem; height:72px;
  background:rgba(253,248,242,0.96); backdrop-filter:blur(16px);
  border-bottom:1px solid rgba(232,221,208,0.6);
  animation:slideDown 0.6s ease forwards;
}
@keyframes slideDown{from{transform:translateY(-100%);opacity:0}to{transform:translateY(0);opacity:1}}

/* ── Logo : visible, claire, cohérente ── */
.logo { display:flex; align-items:center; gap:10px; text-decoration:none; }
.logo-dot { width:8px; height:8px; background:var(--teal); border-radius:50%; animation:pulse 2s ease infinite; flex-shrink:0; }
@keyframes pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.6);opacity:0.6}}

.logo-img-wrap {
  height:48px;
  display:flex; align-items:center;
  background:var(--charcoal);
  border-radius:10px;
  padding:5px 12px;
  box-shadow:0 2px 10px rgba(44,44,44,0.15);
}
.logo-img-wrap img {
  height:36px; width:auto;
  object-fit:contain;
  display:block;
  /* si logo sur fond sombre sinon retirer le filter */
  filter:brightness(0) invert(1);
}
.logo-text-fallback {
  font-family:'Fraunces',serif; font-size:1.5rem; font-weight:700; color:#fff;
  white-space:nowrap;
}
.logo-text-fallback span { color:var(--coral); }

.nav-links { display:flex; align-items:center; gap:0; list-style:none; }
.nav-links a {
  font-size:0.8rem; font-weight:500; color:var(--gray);
  text-decoration:none; padding:6px 11px; border-radius:20px; transition:all 0.2s; white-space:nowrap;
}
.nav-links a:hover { color:var(--charcoal); background:var(--sand); }
.nav-actions { display:flex; align-items:center; gap:10px; }
.btn-ghost {
  font-family:'DM Sans',sans-serif; font-size:0.82rem; font-weight:500;
  color:var(--charcoal); background:none; border:1.5px solid var(--border);
  padding:8px 18px; border-radius:24px; cursor:pointer; transition:all 0.2s;
}
.btn-ghost:hover { border-color:var(--coral); color:var(--coral); }
.btn-primary {
  font-family:'DM Sans',sans-serif; font-size:0.82rem; font-weight:600;
  color:#fff; background:var(--coral); border:none;
  padding:8px 20px; border-radius:24px; cursor:pointer; transition:all 0.25s;
  box-shadow:0 4px 14px rgba(255,107,71,0.3);
}
.btn-primary:hover { background:var(--coral-light); transform:translateY(-1px); }

/* ══ HERO ══ */
.hero {
  min-height:100vh; position:relative;
  display:flex; flex-direction:column; align-items:center; justify-content:center;
  padding:110px 2rem 70px; overflow:hidden;
}
/* ── CHANGEMENT IMAGE : first.jpg ── */
.hero-bg-img {
  position:absolute; inset:0;
  background-image:url('{{ asset("images/first.jpg") }}');
  background-size:cover; background-position:center;
  filter:brightness(0.9) saturate(1.1);
}
.hero-bg-overlay {
  position:absolute; inset:0;
  background:linear-gradient(160deg,rgba(253,248,242,0.85) 0%,rgba(253,248,242,0.65) 40%,rgba(42,191,191,0.06) 100%);
}
.blob { position:absolute; border-radius:50%; filter:blur(70px); pointer-events:none; animation:floatBlob 9s ease-in-out infinite; }
.blob-1{width:400px;height:400px;background:rgba(42,191,191,0.14);top:-80px;left:-100px}
.blob-2{width:300px;height:300px;background:rgba(255,107,71,0.1);bottom:60px;right:-80px;animation-delay:-4s}
.blob-3{width:200px;height:200px;background:rgba(240,230,211,0.5);top:40%;left:62%;animation-delay:-7s}
@keyframes floatBlob{0%,100%{transform:translate(0,0)}33%{transform:translate(18px,-26px)}66%{transform:translate(-14px,18px)}}
.dots-grid { position:absolute; top:110px; right:5%; display:grid; grid-template-columns:repeat(8,1fr); gap:14px; opacity:0.3; animation:fadeIn 1.5s 0.8s both; }
.dot { width:4px; height:4px; border-radius:50%; background:var(--sand-dark); }
.dessin-float { position:absolute; right:4%; bottom:12%; width:160px; opacity:0.22; animation:floatBlob 7s ease-in-out infinite; pointer-events:none; border-radius:12px; }

.hero-content { position:relative; z-index:2; text-align:center; max-width:820px; width:100%; }
.hero-badge {
  display:inline-flex; align-items:center; gap:6px;
  background:rgba(255,252,247,0.92); border:1px solid var(--border);
  border-radius:24px; padding:6px 16px; font-size:0.78rem; font-weight:500; color:var(--teal-dark);
  margin-bottom:1.5rem; animation:fadeUp 0.7s 0.2s both; box-shadow:0 2px 12px var(--shadow);
}
.hero-badge::before { content:"✦"; font-size:0.7rem; }
h1 { font-family:'Fraunces',serif; font-size:clamp(2.6rem,5.5vw,4.8rem); font-weight:600; line-height:1.1; color:var(--charcoal); margin-bottom:1.2rem; animation:fadeUp 0.7s 0.35s both; }
h1 em { font-style:italic; color:var(--coral); animation:wiggle 3s 1.5s ease-in-out infinite; display:inline-block; }
@keyframes wiggle{0%,100%{transform:rotate(-1deg)}50%{transform:rotate(1.5deg)}}
.hero-sub { font-size:1.05rem; color:var(--gray); max-width:520px; margin:0 auto 2rem; line-height:1.7; animation:fadeUp 0.7s 0.5s both; }

.hero-aventure-wrap {
  position:relative; width:100%; max-width:820px; margin:0 auto 2rem;
  border-radius:22px; overflow:hidden; height:300px;
  animation:fadeUp 0.7s 0.55s both; box-shadow:0 12px 40px rgba(44,44,44,0.18);
}
.hero-aventure-wrap img { width:100%; height:100%; object-fit:cover; }
.hero-aventure-overlay { position:absolute; inset:0; background:linear-gradient(to top,rgba(44,44,44,0.38) 0%,transparent 55%); }

.search-bar {
  background:rgba(255,252,247,0.97); border-radius:18px;
  box-shadow:0 10px 44px rgba(44,44,44,0.14); padding:10px;
  display:flex; align-items:center; max-width:820px; width:100%; margin:0 auto;
  border:1px solid var(--border); animation:fadeUp 0.7s 0.65s both; transition:box-shadow 0.3s;
}
.search-bar:hover { box-shadow:0 14px 52px rgba(44,44,44,0.18); }
.search-field {
  display:flex; align-items:center; gap:10px; padding:12px 18px; flex:1;
  border-right:1.5px solid var(--border); cursor:pointer; border-radius:10px; transition:background 0.2s;
}
.search-field:last-of-type { border-right:none; }
.search-field:hover { background:var(--sand); }
.search-icon { font-size:1.1rem; color:var(--coral); flex-shrink:0; }
.search-field-inner { display:flex; flex-direction:column; gap:1px; min-width:0; }
.search-label { font-size:0.67rem; font-weight:700; color:var(--gray-light); text-transform:uppercase; letter-spacing:0.06em; }
.search-input { font-family:'DM Sans',sans-serif; font-size:0.88rem; font-weight:500; color:var(--charcoal); background:none; border:none; outline:none; width:100%; }
.search-input::placeholder { color:var(--gray-light); font-weight:400; }
.search-btn {
  background:var(--coral); color:#fff; border:none; border-radius:12px; padding:14px 26px;
  font-family:'DM Sans',sans-serif; font-size:0.88rem; font-weight:700;
  cursor:pointer; display:flex; align-items:center; gap:8px; transition:all 0.25s; white-space:nowrap;
  box-shadow:0 4px 14px rgba(255,107,71,0.35);
}
.search-btn:hover { background:#e85535; transform:scale(1.02); }
.quick-filters { display:flex; align-items:center; gap:10px; margin-top:1.4rem; animation:fadeUp 0.7s 0.75s both; flex-wrap:wrap; justify-content:center; }
.qf-label { font-size:0.78rem; color:var(--gray-light); font-weight:500; }
.qf-tag { font-size:0.78rem; font-weight:500; color:var(--gray); background:rgba(255,252,247,0.92); border:1px solid var(--border); padding:5px 14px; border-radius:20px; cursor:pointer; transition:all 0.2s; }
.qf-tag:hover { background:var(--coral); color:#fff; border-color:var(--coral); transform:translateY(-2px); }
.hero-ctas { display:flex; gap:14px; justify-content:center; margin-top:2rem; flex-wrap:wrap; animation:fadeUp 0.7s 0.85s both; }
.cta-primary { font-family:'DM Sans',sans-serif; font-size:0.92rem; font-weight:700; background:var(--coral); color:#fff; border:none; padding:14px 30px; border-radius:30px; cursor:pointer; transition:all 0.25s; box-shadow:0 6px 22px rgba(255,107,71,0.35); }
.cta-primary:hover { background:#e85535; transform:translateY(-3px); box-shadow:0 10px 28px rgba(255,107,71,0.42); }
.cta-secondary { font-family:'DM Sans',sans-serif; font-size:0.92rem; font-weight:600; background:var(--warm-white); color:var(--charcoal); border:1.5px solid var(--border); padding:14px 30px; border-radius:30px; cursor:pointer; transition:all 0.25s; }
.cta-secondary:hover { border-color:var(--teal); color:var(--teal-dark); transform:translateY(-2px); }
.stats-strip { position:relative; z-index:2; display:flex; gap:2.5rem; margin-top:2.5rem; animation:fadeUp 0.7s 1s both; justify-content:center; flex-wrap:wrap; }
.stat { text-align:center; }
.stat-num { font-family:'Fraunces',serif; font-size:1.8rem; font-weight:700; color:var(--charcoal); }
.stat-num span { color:var(--coral); }
.stat-desc { font-size:0.73rem; color:var(--gray-light); font-weight:500; }

/* ══ SECTIONS ══ */
section { padding:88px 3rem; }
.section-tag { display:inline-flex; align-items:center; gap:6px; font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.12em; color:var(--coral); margin-bottom:1rem; }
.section-tag::before { content:""; width:18px; height:2px; background:var(--coral); border-radius:2px; display:inline-block; }
h2 { font-family:'Fraunces',serif; font-size:clamp(2rem,4vw,3rem); font-weight:600; line-height:1.15; color:var(--charcoal); margin-bottom:0.6rem; }
h2 em { font-style:italic; color:var(--teal); }
.section-desc { font-size:1rem; color:var(--gray); max-width:520px; line-height:1.7; margin-bottom:2.5rem; }

/* ══ PERKS ══ */
.perks-section { background:var(--warm-white); border-top:1px solid var(--border); border-bottom:1px solid var(--border); padding:50px 3rem; }
.perks-row { display:flex; gap:1.5rem; align-items:stretch; flex-wrap:wrap; justify-content:center; max-width:1100px; margin:0 auto; }
.perk {
  display:flex; align-items:center; gap:14px; background:var(--cream);
  border:1px solid var(--border); border-radius:18px; padding:18px 22px;
  flex:1; min-width:185px; max-width:245px; transition:all 0.3s; cursor:default;
  opacity:0; transform:translateY(20px);
}
.perk.visible { animation:fadeUp 0.5s forwards; }
.perk:hover { transform:translateY(-5px); box-shadow:0 10px 28px var(--shadow-strong); border-color:var(--teal); }
.perk-icon { font-size:1.8rem; flex-shrink:0; }
.perk-title { font-size:0.86rem; font-weight:700; color:var(--charcoal); }
.perk-desc { font-size:0.73rem; color:var(--gray-light); margin-top:2px; }

/* ══ OFFERS ══ */
.offers-section { max-width:1200px; margin:0 auto; }
.offers-header { display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:2.2rem; flex-wrap:wrap; gap:1rem; }
.link-all { font-size:0.85rem; font-weight:700; color:var(--coral); text-decoration:none; display:flex; align-items:center; gap:4px; transition:gap 0.2s; }
.link-all:hover { gap:10px; }
.offers-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(270px,1fr)); gap:1.5rem; }
.offer-card {
  background:var(--warm-white); border:1px solid var(--border);
  border-radius:22px; overflow:hidden; cursor:pointer;
  opacity:0; transform:translateY(28px);
  transition:all 0.4s cubic-bezier(0.34,1.56,0.64,1);
  transform-style:preserve-3d; will-change:transform;
}
.offer-card.visible { animation:fadeUp 0.5s forwards; }
.offer-card:hover { box-shadow:0 24px 60px var(--shadow-strong); }
.card-img-wrap { width:100%; height:210px; overflow:hidden; position:relative; }
.card-img-wrap img { width:100%; height:100%; object-fit:cover; transition:transform 0.55s cubic-bezier(0.25,0.46,0.45,0.94); display:block; }
.offer-card:hover .card-img-wrap img { transform:scale(1.1); }
.card-img-wrap::after { content:''; position:absolute; inset:0; background:linear-gradient(135deg,rgba(255,255,255,0.18) 0%,transparent 50%,rgba(42,191,191,0.12) 100%); opacity:0; transition:opacity 0.4s; }
.offer-card:hover .card-img-wrap::after { opacity:1; }
.card-badge { position:absolute; top:12px; left:12px; background:var(--coral); color:#fff; font-size:0.67rem; font-weight:700; padding:4px 10px; border-radius:20px; text-transform:uppercase; letter-spacing:0.05em; z-index:1; }
.card-badge.teal { background:var(--teal); }
.card-body { padding:18px 20px 20px; }
.card-city { font-size:0.7rem; font-weight:700; color:var(--teal-dark); text-transform:uppercase; letter-spacing:0.09em; margin-bottom:4px; }
.card-name { font-family:'Fraunces',serif; font-size:1.1rem; font-weight:600; color:var(--charcoal); margin-bottom:8px; }
.card-meta { display:flex; align-items:center; gap:12px; margin-bottom:12px; }
.card-rating { display:flex; align-items:center; gap:4px; font-size:0.8rem; font-weight:600; }
.star { color:#F5C842; }
.card-tags { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:14px; }
.tag { font-size:0.7rem; font-weight:500; color:var(--gray); background:var(--sand); padding:3px 10px; border-radius:12px; }
.card-footer { display:flex; justify-content:space-between; align-items:center; gap:8px; }
.card-price { font-family:'Fraunces',serif; font-size:1.3rem; font-weight:700; color:var(--charcoal); white-space:nowrap; }
.card-price span { font-family:'DM Sans',sans-serif; font-size:0.73rem; font-weight:400; color:var(--gray-light); }
.card-btns { display:flex; flex-direction:column; gap:5px; align-items:flex-end; }
.btn-book { font-family:'DM Sans',sans-serif; font-size:0.75rem; font-weight:700; background:var(--coral); color:#fff; border:none; padding:7px 16px; border-radius:18px; cursor:pointer; transition:all 0.2s; white-space:nowrap; }
.btn-book:hover { background:#e85535; transform:scale(1.05); }
.btn-request { font-family:'DM Sans',sans-serif; font-size:0.72rem; font-weight:600; background:none; color:var(--teal-dark); border:1.5px solid var(--teal); padding:6px 12px; border-radius:18px; cursor:pointer; transition:all 0.2s; white-space:nowrap; }
.btn-request:hover { background:var(--teal); color:#fff; }

/* ══ DESTINATIONS ══ */
.destinations-section { background:var(--warm-white); border-top:1px solid var(--border); }
.dest-content { max-width:1200px; margin:0 auto; }
.dest-grid { display:grid; grid-template-columns:repeat(4,1fr); grid-template-rows:auto auto; gap:1rem; }
.dest-card {
  border-radius:20px; overflow:hidden; position:relative; cursor:pointer;
  background:var(--sand); opacity:0; transform:scale(0.94);
  transition:transform 0.4s cubic-bezier(0.34,1.56,0.64,1),box-shadow 0.3s;
}
.dest-card.visible { animation:scaleIn 0.55s forwards; }
@keyframes scaleIn{to{opacity:1;transform:scale(1)}}
.dest-card.large { grid-column:span 2; grid-row:span 2; }
.dest-img { width:100%; height:100%; object-fit:cover; min-height:160px; display:block; transition:transform 0.6s cubic-bezier(0.25,0.46,0.45,0.94); }
.dest-card.large .dest-img { min-height:340px; }
.dest-card:hover { transform:scale(1.03); box-shadow:0 18px 50px rgba(44,44,44,0.28); z-index:2; }
.dest-card:hover .dest-img { transform:scale(1.12); }
.dest-overlay { position:absolute; inset:0; background:linear-gradient(to top,rgba(20,20,20,0.68) 0%,transparent 55%); transition:background 0.35s; }
.dest-card:hover .dest-overlay { background:linear-gradient(to top,rgba(20,20,20,0.78) 0%,rgba(20,20,20,0.12) 65%); }
.dest-card::before { content:''; position:absolute; top:0; left:-100%; width:60%; height:100%; background:linear-gradient(to right,transparent,rgba(255,255,255,0.18),transparent); transition:left 0.5s ease; z-index:2; pointer-events:none; }
.dest-card:hover::before { left:150%; }
.dest-info { position:absolute; bottom:0; left:0; right:0; padding:16px 18px; z-index:3; }
.dest-name { font-family:'Fraunces',serif; font-size:1.1rem; font-weight:600; color:#fff; line-height:1.2; transition:letter-spacing 0.3s; }
.dest-card.large .dest-name { font-size:1.65rem; }
.dest-card:hover .dest-name { letter-spacing:0.02em; }
.dest-count { font-size:0.72rem; color:rgba(255,255,255,0.8); margin-top:3px; }

/* ══ DEST LIST ══ */
.dest-list-section { padding:50px 3rem; background:var(--cream); border-bottom:1px solid var(--border); }
.dest-list-inner { max-width:1100px; margin:0 auto; }
.dest-list-title { font-family:'Fraunces',serif; font-size:1.3rem; font-weight:600; margin-bottom:1.5rem; }
.dest-tags { display:flex; flex-wrap:wrap; gap:10px; }
.dest-tag { font-size:0.82rem; font-weight:500; color:var(--charcoal); background:var(--warm-white); border:1px solid var(--border); padding:8px 18px; border-radius:24px; cursor:pointer; transition:all 0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
.dest-tag:hover { background:var(--teal); color:#fff; border-color:var(--teal); transform:translateY(-2px); box-shadow:0 4px 14px rgba(42,191,191,0.25); }

/* ══ ADVENTURE ══ */
.adventure-section { padding:0 3rem 90px; max-width:1200px; margin:0 auto; }
.adventure-inner {
  border-radius:28px; overflow:hidden; position:relative;
  background:linear-gradient(140deg,#14393A 0%,#1A9999 50%,#2ABFBF 100%);
  padding:64px 56px; display:flex; align-items:center; justify-content:space-between; gap:2rem;
}
.adventure-inner::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/svg%3E"); }
.adventure-text { position:relative; z-index:1; max-width:460px; }
.adventure-text .section-tag { color:rgba(255,255,255,0.7); }
.adventure-text .section-tag::before { background:rgba(255,255,255,0.5); }
.adventure-text h2 { color:#fff; font-size:2.3rem; margin-bottom:1rem; }
.adventure-text h2 em { color:#A8ECEC; }
.adventure-text p { color:rgba(255,255,255,0.75); line-height:1.75; margin-bottom:1.8rem; }
.adventure-btns { display:flex; gap:12px; flex-wrap:wrap; }
.btn-white { font-family:'DM Sans',sans-serif; font-size:0.88rem; font-weight:700; background:#fff; color:var(--teal-dark); border:none; padding:12px 26px; border-radius:24px; cursor:pointer; transition:all 0.25s; }
.btn-white:hover { background:var(--cream); transform:translateY(-2px); box-shadow:0 8px 22px rgba(0,0,0,0.2); }
.btn-outline-white { font-family:'DM Sans',sans-serif; font-size:0.88rem; font-weight:600; background:none; color:#fff; border:1.5px solid rgba(255,255,255,0.45); padding:12px 26px; border-radius:24px; cursor:pointer; transition:all 0.25s; }
.btn-outline-white:hover { border-color:#fff; background:rgba(255,255,255,0.12); }
.adventure-photos { position:relative; z-index:1; display:flex; gap:12px; flex-shrink:0; }
.adv-photo { width:130px; height:185px; border-radius:16px; overflow:hidden; transition:transform 0.3s; flex-shrink:0; }
.adv-photo img { width:100%; height:100%; object-fit:cover; transition:transform 0.5s; }
.adv-photo:nth-child(2) { transform:translateY(22px); }
.adv-photo:hover { transform:translateY(-8px) !important; box-shadow:0 16px 40px rgba(0,0,0,0.3); }
.adv-photo:hover img { transform:scale(1.08); }

/* ══ FOOTER ══ */
footer { background:var(--charcoal); color:rgba(255,255,255,0.7); padding:60px 3rem 30px; }
.footer-grid { display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:3rem; max-width:1100px; margin:0 auto 3rem; }

/* ── Logo footer : même style que navbar ── */
.footer-logo-wrap {
  display:flex; align-items:center; gap:10px; text-decoration:none; margin-bottom:12px;
}
.footer-logo-img {
  height:44px;
  background:rgba(255,255,255,0.1);
  border-radius:10px;
  padding:6px 12px;
  display:flex; align-items:center;
  border:1px solid rgba(255,255,255,0.15);
}
.footer-logo-img img {
  height:32px; width:auto; object-fit:contain;
  filter:brightness(0) invert(1);
}
.footer-logo-text { font-family:'Fraunces',serif; font-size:1.4rem; font-weight:700; color:#fff; }
.footer-logo-text span { color:var(--coral); }

.footer-tagline { font-size:0.84rem; margin-top:12px; max-width:240px; line-height:1.75; }

/* ── Réseaux sociaux : images réelles ── */
.footer-social { display:flex; gap:10px; margin-top:1.5rem; flex-wrap:wrap; }
.social-btn {
  width:42px; height:42px; border-radius:50%;
  background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2);
  display:flex; align-items:center; justify-content:center;
  cursor:pointer; transition:all 0.25s; overflow:hidden; padding:9px;
  text-decoration:none;
}
.social-btn:hover { background:var(--coral); border-color:var(--coral); transform:scale(1.15) translateY(-2px); box-shadow:0 6px 16px rgba(255,107,71,0.4); }
.social-btn img { width:100%; height:100%; object-fit:contain; filter:brightness(0) invert(1); display:block; }

.footer-col-title { font-weight:700; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.1em; color:#fff; margin-bottom:1.2rem; }
.footer-links { list-style:none; display:flex; flex-direction:column; gap:8px; }
.footer-links a { font-size:0.82rem; color:rgba(255,255,255,0.6); text-decoration:none; transition:color 0.2s; }
.footer-links a:hover { color:var(--coral); }
.footer-bottom { max-width:1100px; margin:0 auto; padding-top:24px; border-top:1px solid rgba(255,255,255,0.1); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; }
.footer-copy { font-size:0.77rem; color:rgba(255,255,255,0.4); }
.footer-legal { display:flex; gap:20px; }
.footer-legal a { font-size:0.77rem; color:rgba(255,255,255,0.4); text-decoration:none; }
.footer-legal a:hover { color:rgba(255,255,255,0.7); }

/* ══ ANIMATIONS ══ */
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:0.3}}
.reveal { opacity:0; transform:translateY(30px); transition:opacity 0.65s ease,transform 0.65s ease; }
.reveal.visible { opacity:1; transform:translateY(0); }
</style>
</head>
<body>

{{-- ══ MODAL RÉSERVATION CLIENT ══ --}}
<div class="modal-overlay" id="modalReservation">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal('modalReservation')">✕</button>
    <div class="modal-title">📋 Demande de réservation</div>
    <p class="modal-sub">Remplissez ce formulaire et notre équipe vous confirme sous 24h.</p>
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Prénom *</label>
        <input class="form-input" type="text" placeholder="Jean">
      </div>
      <div class="form-group">
        <label class="form-label">Nom *</label>
        <input class="form-input" type="text" placeholder="Dupont">
      </div>
      <div class="form-group">
        <label class="form-label">Email *</label>
        <input class="form-input" type="email" placeholder="jean@email.com">
      </div>
      <div class="form-group">
        <label class="form-label">Téléphone</label>
        <input class="form-input" type="tel" placeholder="+33 6 00 00 00 00">
      </div>
      <div class="form-group full">
        <label class="form-label">Destination *</label>
        <select class="form-select">
          <option value="">-- Choisir une destination --</option>
          <option>Paris</option><option>Amsterdam</option><option>Athens</option>
          <option>Barcelona</option><option>Berlin</option><option>Brussels</option>
          <option>Budapest</option><option>Dublin</option><option>Florence</option>
          <option>Lisbon</option><option>London</option><option>Madrid</option>
          <option>Milan</option><option>Munich</option><option>New York</option>
          <option>Nice</option><option>Prague</option><option>Rome</option>
          <option>Stockholm</option><option>Venice</option><option>Vienna</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Date d'arrivée *</label>
        <input class="form-input" type="date">
      </div>
      <div class="form-group">
        <label class="form-label">Date de départ *</label>
        <input class="form-input" type="date">
      </div>
      <div class="form-group">
        <label class="form-label">Nombre de voyageurs</label>
        <select class="form-select">
          <option>1 voyageur</option><option>2 voyageurs</option>
          <option>3 voyageurs</option><option>4 voyageurs</option><option>5+</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Type de chambre</label>
        <select class="form-select">
          <option>Dortoir (lit)</option><option>Chambre privée</option>
          <option>Chambre double</option><option>Suite familiale</option>
        </select>
      </div>
      <div class="form-group full">
        <label class="form-label">Message / Demandes spéciales</label>
        <textarea class="form-textarea" placeholder="Précisez vos besoins, arrivée tardive, allergies alimentaires…"></textarea>
      </div>
    </div>
    <button class="btn-submit" onclick="submitForm()">🚀 Envoyer ma demande</button>
  </div>
</div>

{{-- ══ MODAL CONNEXION PROPRIÉTAIRE — Formulaire POST réel ══ --}}
<div class="modal-overlay" id="modalLogin">
  <div class="modal-box login-box">
    <button class="modal-close" onclick="closeModal('modalLogin')">✕</button>
    <div class="modal-title">🔐 Connexion Propriétaire</div>
    <p class="modal-sub">Accédez à votre espace de gestion HostelFlow.</p>

    @if(session('error'))
      <div class="login-error" style="display:block">{{ session('error') }}</div>
    @endif

    {{-- Formulaire POST vers la vraie route Laravel --}}
    <form method="POST" action="{{ route('owner.login.store') }}" id="loginForm">
      @csrf
      <div class="form-grid">
        <div class="form-group full">
          <label class="form-label">Adresse email</label>
          <input class="form-input" type="email" name="email" placeholder="proprietaire@hostel.com"
                 value="{{ old('email') }}" required
                 style="{{ $errors->has('email') ? 'border-color:var(--coral)' : '' }}">
          @error('email')
            <span style="font-size:0.75rem;color:var(--coral)">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-group full">
          <label class="form-label">Mot de passe</label>
          <input class="form-input" type="password" name="password" placeholder="••••••••••" required
                 style="{{ $errors->has('password') ? 'border-color:var(--coral)' : '' }}">
          @error('password')
            <span style="font-size:0.75rem;color:var(--coral)">{{ $message }}</span>
          @enderror
          <a href="#" class="forgot-link">Mot de passe oublié ?</a>
        </div>
      </div>
      <button type="submit" class="btn-submit">Se connecter →</button>
    </form>

    <div class="login-divider"><span>Pas encore de compte ?</span></div>
    <a href="{{ route('register') }}" class="btn-submit-teal">Créer un compte établissement</a>
  </div>
</div>

{{-- ══ MODAL BLOG ══ --}}
<div class="modal-overlay" id="modalBlog">
  <div class="modal-box blog-box">
    <button class="modal-close" onclick="closeModal('modalBlog')">✕</button>
    <span class="blog-tag">🎒 Blog des Routards</span>
    <div class="modal-title">Les 10 auberges les plus mythiques d'Europe</div>
    <div class="blog-meta">
      <span>✍️ Par Sophie Martin</span>
      <span>📅 18 avril 2026</span>
      <span>⏱ 8 min de lecture</span>
    </div>
    <img class="blog-hero-img" src="{{ asset('images/aventure.jpg') }}" alt="Auberge Europe"
         onerror="this.style.background='linear-gradient(135deg,#2ABFBF,#FF6B47)';this.removeAttribute('src')">
    <div class="blog-content">
      <p>L'Europe regorge d'auberges qui vont bien au-delà du simple lit en dortoir. Certaines sont nichées dans d'anciens palais, d'autres surplombent des canaux ou s'intègrent dans des forêts anciennes. Voici notre sélection des établissements qui ont marqué l'histoire du voyage indépendant.</p>
      <h3>🏰 1. Le Chateau Hostel — Prague</h3>
      <p>Installé dans un bâtiment du XVIIe siècle à deux pas du château de Prague, cet hostel propose des plafonds peints à la fresque et des dortoirs avec vue sur les toits dorés de la vieille ville.</p>
      <h3>🌊 2. Canal Garden Hostel — Amsterdam</h3>
      <p>Les fenêtres donnent directement sur le Prinsengracht. Le vélo est inclus dans la nuitée — une nécessité dans cette ville.</p>
      <h3>⛵ 3. The Rooftop — Barcelone</h3>
      <p>Terrasse à ciel ouvert sur les toits du quartier gothique, bar à cocktails, yoga au lever du soleil face à la Méditerranée.</p>
      <h3>🌿 4. The Forest Lodge — Munich</h3>
      <p>À 20 minutes du centre, ce lodge en bois propose des chambres qui sentent le pin et un sauna traditionnel.</p>
      <h3>🎨 5. Art Squat Hostel — Berlin</h3>
      <p>Chaque chambre est peinte par un artiste différent. Les couloirs sont des galeries éphémères.</p>
      <p style="margin-top:1.5rem;padding:16px;background:var(--sand);border-radius:12px;font-size:0.85rem;color:var(--gray)">
        <strong>💡 Conseil HostelFlow :</strong> Réservez au moins 3 semaines à l'avance pour les auberges premium en haute saison (juillet–août).
      </p>
    </div>
  </div>
</div>

{{-- ══ NAVBAR ══ --}}
<nav>
  <a href="{{ url('/') }}" class="logo">
    <div class="logo-dot"></div>
    <div class="logo-img-wrap">
      <img src="{{ asset('images/logo2.png') }}" alt="HostelFlow"
           onerror="this.parentElement.innerHTML='<span class=\'logo-text-fallback\'>Hostel<span>Flow</span></span>'">
    </div>
  </a>
  <ul class="nav-links">
    <li><a href="{{ url('/') }}">Accueil</a></li>
    <li><a href="#">Groupes 10+</a></li>
    <li><a href="#">Guides</a></li>
    <li><a href="#" onclick="openModal('modalBlog'); return false;">Blog des routards</a></li>
    <li><a href="#">Ajouter votre établissement</a></li>
    <li><a href="#" onclick="openModal('modalReservation'); return false;">Extranet</a></li>
  </ul>
  <div class="nav-actions">
    <button class="btn-ghost" onclick="openModal('modalLogin')">Se connecter</button>
    <button class="btn-primary" onclick="openModal('modalReservation')">S'inscrire</button>
  </div>
</nav>

{{-- Si des erreurs de validation viennent du formulaire de login, ouvrir le modal --}}
@if($errors->any() || session('error'))
<script>document.addEventListener('DOMContentLoaded',()=>openModal('modalLogin'));</script>
@endif

{{-- ══ HERO — image first.jpg ══ --}}
<section class="hero">
  <div class="hero-bg-img"></div>
  <div class="hero-bg-overlay"></div>
  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>
  <div class="blob blob-3"></div>
  <div class="dots-grid">
    <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
    <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
    <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
    <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
    <div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div>
  </div>
  <img src="{{ asset('images/dessin.jpg') }}" alt="" class="dessin-float" onerror="this.style.display='none'">

  <div class="hero-content">
    <div class="hero-badge">✦ Plus de 25 000 auberges dans 180 pays</div>
    <h1>Découvrez des lieux <em>populaires</em><br>pour des aventures <em>inoubliables</em></h1>
    <p class="hero-sub">Réservez des auberges de jeunesse uniques, rencontrez des voyageurs du monde entier et créez des souvenirs qui durent toute une vie.</p>

    <div class="hero-aventure-wrap">
      <img src="{{ asset('images/aventure.jpg') }}" alt="Aventure"
           onerror="this.parentElement.style.background='linear-gradient(135deg,rgba(42,191,191,0.4),rgba(255,107,71,0.4))'">
      <div class="hero-aventure-overlay"></div>
    </div>

    <div class="search-bar">
      <div class="search-field">
        <span class="search-icon">📍</span>
        <div class="search-field-inner">
          <span class="search-label">Destination</span>
          <input class="search-input" id="searchDest" type="text" placeholder="Paris, Barcelona, Amsterdam…">
        </div>
      </div>
      <div class="search-field">
        <span class="search-icon">📅</span>
        <div class="search-field-inner">
          <span class="search-label">Arrivée</span>
          <input class="search-input" type="date">
        </div>
      </div>
      <div class="search-field">
        <span class="search-icon">📅</span>
        <div class="search-field-inner">
          <span class="search-label">Départ</span>
          <input class="search-input" type="date">
        </div>
      </div>
      <div class="search-field">
        <span class="search-icon">👥</span>
        <div class="search-field-inner">
          <span class="search-label">Voyageurs</span>
          <input class="search-input" type="text" placeholder="2 voyageurs">
        </div>
      </div>
      <button class="search-btn">🔍 Rechercher</button>
    </div>

    <div class="quick-filters">
      <span class="qf-label">Populaire :</span>
      <span class="qf-tag">🏙 City Break</span>
      <span class="qf-tag">🏕 Camping</span>
      <span class="qf-tag">🏖 Plage</span>
      <span class="qf-tag">⛰ Montagne</span>
      <span class="qf-tag">🎒 Routard</span>
      <span class="qf-tag">🌍 Europe</span>
    </div>

    <div class="hero-ctas">
      <button class="cta-primary" onclick="document.getElementById('offres').scrollIntoView({behavior:'smooth'})">🌟 Voir les meilleures offres</button>
      <button class="cta-secondary" onclick="openModal('modalReservation')">📋 Envoyer une demande de réservation</button>
    </div>

    <div class="stats-strip">
      <div class="stat"><div class="stat-num">25k<span>+</span></div><div class="stat-desc">Auberges</div></div>
      <div class="stat"><div class="stat-num">180<span>+</span></div><div class="stat-desc">Pays</div></div>
      <div class="stat"><div class="stat-num">4M<span>+</span></div><div class="stat-desc">Voyageurs</div></div>
      <div class="stat"><div class="stat-num">9.1<span>/10</span></div><div class="stat-desc">Note moy.</div></div>
    </div>
  </div>
</section>

{{-- ══ PERKS ══ --}}
<div class="perks-section">
  <div class="perks-row">
    <div class="perk"><div class="perk-icon">🌿</div><div><div class="perk-title">Immersion nature</div><div class="perk-desc">Hébergements en plein air</div></div></div>
    <div class="perk"><div class="perk-icon">✅</div><div><div class="perk-title">Annulation gratuite</div><div class="perk-desc">Sans frais jusqu'à 48h avant</div></div></div>
    <div class="perk"><div class="perk-icon">📶</div><div><div class="perk-title">Wi-Fi gratuit</div><div class="perk-desc">Restez connecté partout</div></div></div>
    <div class="perk"><div class="perk-icon">🛡</div><div><div class="perk-title">Sécurité garantie</div><div class="perk-desc">Paiement 100% protégé</div></div></div>
    <div class="perk"><div class="perk-icon">😴</div><div><div class="perk-title">Repos & bien-être</div><div class="perk-desc">Espaces calmes sélectionnés</div></div></div>
  </div>
</div>

{{-- ══ MEILLEURES OFFRES ══ --}}
<section id="offres" style="padding:90px 3rem;">
  <div class="offers-section">
    <div class="offers-header reveal">
      <div>
        <div class="section-tag">Meilleures offres</div>
        <h2>Toutes les meilleures offres<br><em>en un seul endroit</em></h2>
        <p class="section-desc">Des hébergements soigneusement sélectionnés pour votre prochaine aventure.</p>
      </div>
      <a href="#" class="link-all" onclick="openModal('modalReservation');return false;">Voir toutes les offres →</a>
    </div>
    <div class="offers-grid">
      <div class="offer-card">
        <div class="card-img-wrap">
          <img src="{{ asset('images/city.jpg') }}" alt="City Hostel" onerror="this.src='https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600&q=80'">
          <div class="card-badge">🔥 Populaire</div>
        </div>
        <div class="card-body">
          <div class="card-city">Paris, France</div>
          <div class="card-name">City Hostel Le Marais</div>
          <div class="card-meta"><div class="card-rating"><span class="star">★</span> 9.4 <span style="color:var(--gray-light);font-weight:400">(312 avis)</span></div></div>
          <div class="card-tags"><span class="tag">📶 Wi-Fi</span><span class="tag">✅ Annulation gratuite</span><span class="tag">🌿 Nature</span></div>
          <div class="card-footer">
            <div class="card-price">12€ <span>/ nuit</span></div>
            <div class="card-btns">
              <button class="btn-book" onclick="openModal('modalReservation')">Réserver</button>
              <button class="btn-request" onclick="openModal('modalReservation')">Demande</button>
            </div>
          </div>
        </div>
      </div>
      <div class="offer-card">
        <div class="card-img-wrap">
          <img src="{{ asset('images/the garden.jpg') }}" alt="The Garden" onerror="this.src='https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=600&q=80'">
          <div class="card-badge teal">⭐ Top noté</div>
        </div>
        <div class="card-body">
          <div class="card-city">Amsterdam, Pays-Bas</div>
          <div class="card-name">The Garden Backpackers</div>
          <div class="card-meta"><div class="card-rating"><span class="star">★</span> 9.7 <span style="color:var(--gray-light);font-weight:400">(488 avis)</span></div></div>
          <div class="card-tags"><span class="tag">🚲 Vélos incl.</span><span class="tag">📶 Wi-Fi</span><span class="tag">🍺 Bar</span></div>
          <div class="card-footer">
            <div class="card-price">18€ <span>/ nuit</span></div>
            <div class="card-btns">
              <button class="btn-book" onclick="openModal('modalReservation')">Réserver</button>
              <button class="btn-request" onclick="openModal('modalReservation')">Demande</button>
            </div>
          </div>
        </div>
      </div>
      <div class="offer-card">
        <div class="card-img-wrap">
          <img src="{{ asset('images/sunset.jpg') }}" alt="Sunset" onerror="this.src='https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&q=80'">
          <div class="card-badge">💥 -20%</div>
        </div>
        <div class="card-body">
          <div class="card-city">Barcelone, Espagne</div>
          <div class="card-name">Sunset Gothic Hideaway</div>
          <div class="card-meta"><div class="card-rating"><span class="star">★</span> 9.2 <span style="color:var(--gray-light);font-weight:400">(276 avis)</span></div></div>
          <div class="card-tags"><span class="tag">🏊 Piscine</span><span class="tag">📶 Wi-Fi</span><span class="tag">✅ Annulation</span></div>
          <div class="card-footer">
            <div class="card-price">14€ <span>/ nuit</span></div>
            <div class="card-btns">
              <button class="btn-book" onclick="openModal('modalReservation')">Réserver</button>
              <button class="btn-request" onclick="openModal('modalReservation')">Demande</button>
            </div>
          </div>
        </div>
      </div>
      <div class="offer-card">
        <div class="card-img-wrap">
          <img src="{{ asset('images/prague.jpg') }}" alt="Prague" onerror="this.src='https://images.unsplash.com/photo-1467269204594-9661b134dd2b?w=600&q=80'">
          <div class="card-badge teal">🆕 Nouveau</div>
        </div>
        <div class="card-body">
          <div class="card-city">Prague, Tchéquie</div>
          <div class="card-name">Old Town Wanderers Inn</div>
          <div class="card-meta"><div class="card-rating"><span class="star">★</span> 9.5 <span style="color:var(--gray-light);font-weight:400">(154 avis)</span></div></div>
          <div class="card-tags"><span class="tag">🍳 Petit-déj. incl.</span><span class="tag">🎮 Game room</span><span class="tag">📶 Wi-Fi</span></div>
          <div class="card-footer">
            <div class="card-price">10€ <span>/ nuit</span></div>
            <div class="card-btns">
              <button class="btn-book" onclick="openModal('modalReservation')">Réserver</button>
              <button class="btn-request" onclick="openModal('modalReservation')">Demande</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ══ DESTINATIONS ══ --}}
<section class="destinations-section" style="padding:90px 3rem;">
  <div class="dest-content">
    <div class="reveal" style="margin-bottom:2.5rem">
      <div class="section-tag">Explorer le monde</div>
      <h2>Destinations <em>Populaires</em></h2>
      <p class="section-desc">Les plus belles villes du monde choisies par notre communauté.</p>
    </div>
    <div class="dest-grid">
      <div class="dest-card large">
        <img class="dest-img" src="{{ asset('images/paris.jpg') }}" alt="Paris" onerror="this.src='https://images.unsplash.com/photo-1499856871958-5b9627545d1a?w=700&q=80'">
        <div class="dest-overlay"></div>
        <div class="dest-info"><div class="dest-name">Paris</div><div class="dest-count">🏨 1 240 auberges</div></div>
      </div>
      <div class="dest-card">
        <img class="dest-img" src="{{ asset('images/florence.jpg') }}" alt="Florence" onerror="this.src='https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?w=500&q=80'">
        <div class="dest-overlay"></div>
        <div class="dest-info"><div class="dest-name">Florence</div><div class="dest-count">🏨 412 auberges</div></div>
      </div>
      <div class="dest-card">
        <img class="dest-img" src="{{ asset('images/london.jpg') }}" alt="London" onerror="this.src='https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=500&q=80'">
        <div class="dest-overlay"></div>
        <div class="dest-info"><div class="dest-name">Londres</div><div class="dest-count">🏨 986 auberges</div></div>
      </div>
      <div class="dest-card">
        <img class="dest-img" src="{{ asset('images/barcelone.jpg') }}" alt="Barcelona" onerror="this.src='https://images.unsplash.com/photo-1539037116277-4db20889f2d4?w=500&q=80'">
        <div class="dest-overlay"></div>
        <div class="dest-info"><div class="dest-name">Barcelone</div><div class="dest-count">🏨 738 auberges</div></div>
      </div>
      <div class="dest-card">
        <img class="dest-img" src="{{ asset('images/New York.jpg') }}" alt="New York" onerror="this.src='https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=500&q=80'">
        <div class="dest-overlay"></div>
        <div class="dest-info"><div class="dest-name">New York</div><div class="dest-count">🏨 623 auberges</div></div>
      </div>
      <div class="dest-card">
        <img class="dest-img" src="{{ asset('images/rome.jpg') }}" alt="Rome" onerror="this.src='https://images.unsplash.com/photo-1503917988258-f87a78e3c995?w=500&q=80'">
        <div class="dest-overlay"></div>
        <div class="dest-info"><div class="dest-name">Rome</div><div class="dest-count">🏨 881 auberges</div></div>
      </div>
    </div>
  </div>
</section>

{{-- ══ LISTE DES DESTINATIONS ══ --}}
<div class="dest-list-section">
  <div class="dest-list-inner reveal">
    <div class="dest-list-title">🗺 Toutes les destinations</div>
    <div class="dest-tags">
      <a href="#" class="dest-tag">🇳🇱 Amsterdam</a>
      <a href="#" class="dest-tag">🇬🇷 Athens</a>
      <a href="#" class="dest-tag">🇪🇸 Barcelona</a>
      <a href="#" class="dest-tag">🇩🇪 Berlin</a>
      <a href="#" class="dest-tag">🇭🇺 Budapest</a>
      <a href="#" class="dest-tag">🇮🇹 Florence</a>
      <a href="#" class="dest-tag">🇬🇧 London</a>
      <a href="#" class="dest-tag">🇪🇸 Madrid</a>
      <a href="#" class="dest-tag">🇮🇹 Milan</a>
      <a href="#" class="dest-tag">🇩🇪 Munich</a>
      <a href="#" class="dest-tag">🇺🇸 New York</a>
      <a href="#" class="dest-tag">🇫🇷 Nice</a>
      <a href="#" class="dest-tag">🇫🇷 Paris</a>
      <a href="#" class="dest-tag">🇨🇿 Prague</a>
      <a href="#" class="dest-tag">🇮🇹 Rome</a>
      <a href="#" class="dest-tag">🇸🇪 Stockholm</a>
      <a href="#" class="dest-tag">🇮🇹 Venice</a>
      <a href="#" class="dest-tag">🇦🇹 Vienna</a>
    </div>
  </div>
</div>

{{-- ══ SECTION AVENTURE ══ --}}
<div class="adventure-section" style="padding-top:90px">
  <div class="adventure-inner reveal">
    <div class="adventure-text">
      <div class="section-tag">Aventure garantie</div>
      <h2>Rejoignez une <em>communauté</em><br>de vrais voyageurs</h2>
      <p>Des jeunes du monde entier qui campent, explorent, se perdent et se retrouvent. Des auberges de caractère, des rencontres improbables, des histoires vraies.</p>
      <div class="adventure-btns">
        <button class="btn-white" onclick="openModal('modalReservation')">🎒 Explorer maintenant</button>
        <button class="btn-outline-white" onclick="openModal('modalBlog')">📖 Lire le blog</button>
      </div>
    </div>
    <div class="adventure-photos">
      <div class="adv-photo"><img src="{{ asset('images/aventure.jpg') }}" alt="Aventure" onerror="this.src='https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=300&q=80'"></div>
      <div class="adv-photo"><img src="{{ asset('images/camping.jpg') }}" alt="Camping" onerror="this.src='https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=300&q=80'"></div>
      <div class="adv-photo"><img src="{{ asset('images/travelers.jpg') }}" alt="Travelers" onerror="this.src='https://images.unsplash.com/photo-1523464862212-d6631d073194?w=300&q=80'"></div>
    </div>
  </div>
</div>

{{-- ══ FOOTER ══ --}}
<footer>
  <div class="footer-grid">
    <div class="footer-brand">
      {{-- Logo footer cohérent --}}
      <a href="{{ url('/') }}" class="footer-logo-wrap">
        <div class="footer-logo-img">
          <img src="{{ asset('images/logo2.png') }}" alt="HostelFlow"
               onerror="this.parentElement.innerHTML='<span class=\'footer-logo-text\'>Hostel<span>Flow</span></span>'">
        </div>
      </a>
      <p class="footer-tagline">La plateforme de référence pour les voyageurs indépendants. 25 000+ auberges dans 180 pays.</p>

      {{-- Réseaux sociaux avec vraies images --}}
      <div class="footer-social">
        <a href="#" class="social-btn" title="Facebook">
          <img src="{{ asset('images/fb.png') }}" alt="Facebook"
               onerror="this.outerHTML='<svg viewBox=\'0 0 24 24\' fill=\'white\' width=\'20\' height=\'20\'><path d=\'M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z\'></path></svg>'">
        </a>
        <a href="#" class="social-btn" title="Instagram">
          <img src="{{ asset('images/instagram.png') }}" alt="Instagram"
               onerror="this.outerHTML='<svg viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'white\' stroke-width=\'2\' width=\'20\' height=\'20\'><rect x=\'2\' y=\'2\' width=\'20\' height=\'20\' rx=\'5\' ry=\'5\'></rect><path d=\'M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z\'></path><line x1=\'17.5\' y1=\'6.5\' x2=\'17.51\' y2=\'6.5\'></line></svg>'">
        </a>
        <a href="#" class="social-btn" title="TikTok">
          <img src="{{ asset('images/tiktok.png') }}" alt="TikTok"
               onerror="this.outerHTML='<span style=\'color:white;font-size:1rem;font-weight:700\'>TT</span>'">
        </a>
        <a href="#" class="social-btn" title="YouTube">
          <img src="{{ asset('images/youtube.png') }}" alt="YouTube"
               onerror="this.outerHTML='<svg viewBox=\'0 0 24 24\' fill=\'white\' width=\'20\' height=\'20\'><path d=\'M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z\'></path><polygon points=\'9.75 15.02 15.5 12 9.75 8.98 9.75 15.02\' fill=\'#333\'></polygon></svg>'">
        </a>
      </div>
    </div>
    <div>
      <div class="footer-col-title">Navigation</div>
      <ul class="footer-links">
        <li><a href="{{ url('/') }}">Accueil</a></li>
        <li><a href="#">Groupes 10+</a></li>
        <li><a href="#">Guides</a></li>
        <li><a href="#" onclick="openModal('modalBlog');return false">Blog des routards</a></li>
        <li><a href="#">Ajouter votre établissement</a></li>
        <li><a href="#" onclick="openModal('modalReservation');return false">Extranet</a></li>
      </ul>
    </div>
    <div>
      <div class="footer-col-title">Informations</div>
      <ul class="footer-links">
        <li><a href="#">Aide / FAQ</a></li>
        <li><a href="#">Nous Joindre</a></li>
        <li><a href="#">Conditions</a></li>
        <li><a href="#">Confidentialité</a></li>
        <li><a href="#">Mentions légales</a></li>
        <li><a href="#">Cookies</a></li>
      </ul>
    </div>
    <div>
      <div class="footer-col-title">Destinations</div>
      <ul class="footer-links">
        <li><a href="#">Europe</a></li>
        <li><a href="#">Amériques</a></li>
        <li><a href="#">Asie</a></li>
        <li><a href="#">Afrique</a></li>
        <li><a href="#">Océanie</a></li>
        <li><a href="#">Moyen-Orient</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="footer-copy">© {{ date('Y') }} HostelFlow. Tous droits réservés. 🌍</div>
    <div class="footer-legal">
      <a href="#">Conditions d'utilisation</a>
      <a href="#">Politique de confidentialité</a>
      <a href="#">Plan du site</a>
    </div>
  </div>
</footer>

<script>
function openModal(id) {
  document.getElementById(id).classList.add('open');
  document.body.style.overflow='hidden';
}
function closeModal(id) {
  document.getElementById(id).classList.remove('open');
  document.body.style.overflow='';
}
document.querySelectorAll('.modal-overlay').forEach(o=>{
  o.addEventListener('click',e=>{ if(e.target===o){o.classList.remove('open');document.body.style.overflow='';} });
});
document.addEventListener('keydown',e=>{
  if(e.key==='Escape') document.querySelectorAll('.modal-overlay.open').forEach(m=>{m.classList.remove('open');document.body.style.overflow='';});
});
function submitForm() {
  const btn=document.querySelector('#modalReservation .btn-submit');
  btn.textContent='✅ Demande envoyée avec succès !';
  btn.style.background='var(--teal)';
  setTimeout(()=>{closeModal('modalReservation');btn.textContent='🚀 Envoyer ma demande';btn.style.background='';},2000);
}

/* Scroll reveal */
const revealEls=document.querySelectorAll('.reveal,.offer-card,.dest-card,.perk');
const observer=new IntersectionObserver((entries)=>{
  entries.forEach(entry=>{
    if(entry.isIntersecting){
      const el=entry.target;
      const idx=Array.from(el.parentElement.children).indexOf(el);
      setTimeout(()=>el.classList.add('visible'),idx*90);
      observer.unobserve(el);
    }
  });
},{threshold:0.1,rootMargin:'0px 0px -40px 0px'});
revealEls.forEach(el=>observer.observe(el));

/* Typing effect */
const destinations=['Paris, France…','Barcelona, Espagne…','Amsterdam, Pays-Bas…','Prague, Tchéquie…','Rome, Italie…','Berlin, Allemagne…'];
let dIdx=0,cIdx=0,isDeleting=false;
function type(){
  const el=document.getElementById('searchDest'); if(!el)return;
  const current=destinations[dIdx];
  el.placeholder=isDeleting?current.substring(0,cIdx-1):current.substring(0,cIdx+1);
  isDeleting?cIdx--:cIdx++;
  if(!isDeleting&&cIdx===current.length){isDeleting=true;setTimeout(type,1800);return;}
  if(isDeleting&&cIdx===0){isDeleting=false;dIdx=(dIdx+1)%destinations.length;}
  setTimeout(type,isDeleting?45:75);
}
setTimeout(type,1400);

/* Navbar scroll */
const nav=document.querySelector('nav');
window.addEventListener('scroll',()=>{nav.style.boxShadow=window.scrollY>60?'0 4px 24px rgba(44,44,44,0.1)':'none';});

/* 3D Tilt offer cards */
document.querySelectorAll('.offer-card').forEach(card=>{
  card.addEventListener('mousemove',e=>{
    const r=card.getBoundingClientRect();
    const x=e.clientX-r.left,y=e.clientY-r.top;
    const rotY=((x-r.width/2)/(r.width/2))*5;
    const rotX=-((y-r.height/2)/(r.height/2))*5;
    card.style.transform=`perspective(900px) rotateX(${rotX}deg) rotateY(${rotY}deg) translateY(-8px) scale(1.01)`;
  });
  card.addEventListener('mouseleave',()=>{card.style.transform='';});
});

/* Parallax dest cards */
document.querySelectorAll('.dest-card').forEach(card=>{
  const img=card.querySelector('.dest-img');
  card.addEventListener('mousemove',e=>{
    const r=card.getBoundingClientRect();
    const x=(e.clientX-r.left)/r.width-0.5,y=(e.clientY-r.top)/r.height-0.5;
    if(img) img.style.transform=`scale(1.12) translate(${x*14}px,${y*14}px)`;
  });
  card.addEventListener('mouseleave',()=>{ if(img) img.style.transform='scale(1)'; });
});
</script>
</body>
</html>
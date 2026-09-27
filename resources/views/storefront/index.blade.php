{{-- Storefront — Modern Unified v4 (All Enhancements) --}}
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $shop->name ?? 'المتجر' }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<style>
:root{--bg:#f7f7f5;--surface:#fff;--ink:#171717;--muted:#777;--line:#e9e7e2;--accent:#111;--brand:#e96b2c;--brand2:#f59e0b;--soft:#f1ede7;--sale:#d94b4b;--success:#16855b;--danger:#c92828;--radius:18px;--shadow:0 12px 35px rgba(20,20,20,.07);--shadow-lg:0 20px 50px rgba(20,20,20,.12)}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:var(--bg);color:var(--ink);font-family:Tajawal,Arial,sans-serif;-webkit-font-smoothing:antialiased;padding-bottom:0}a{text-decoration:none;color:inherit}button,input{font:inherit}button{cursor:pointer;border:0}.wrap{width:min(1240px,calc(100% - 32px));margin:auto}

/* ═══ Topline ═══ */
.topline{background:#151515;color:#fff;font-size:12px;padding:8px 0}.topline .wrap{display:flex;justify-content:center;gap:24px;align-items:center}.topline b{color:#ffb37c}

/* ═══ Header ═══ */
.header{position:sticky;top:0;z-index:50;background:rgba(255,255,255,.94);backdrop-filter:blur(14px);border-bottom:1px solid var(--line)}.head{height:72px;display:grid;grid-template-columns:auto minmax(280px,1fr) auto;gap:24px;align-items:center}.brand{display:flex;align-items:center;gap:11px;font-weight:900;white-space:nowrap}.brandmark{width:42px;height:42px;border-radius:13px;background:#151515;color:#fff;display:grid;place-items:center}.brandname{font-size:18px}.search{height:46px;border:1px solid var(--line);background:#f8f8f7;border-radius:13px;display:flex;align-items:center;padding:0 14px;gap:9px}.search input{width:100%;border:0;outline:0;background:transparent;color:var(--ink);font-size:14px}.actions{display:flex;gap:8px}.iconbtn{width:42px;height:42px;border:1px solid var(--line);background:#fff;border-radius:12px;display:grid;place-items:center;position:relative}.badge{position:absolute;top:-5px;left:-5px;min-width:18px;height:18px;padding:0 4px;border-radius:99px;background:var(--brand);color:#fff;font-size:10px;display:grid;place-items:center;font-weight:900}

/* ═══ Nav ═══ */
.nav{border-top:1px solid #f3f1ed}.nav .wrap{height:44px;display:flex;align-items:center;gap:24px;overflow:auto;white-space:nowrap}.nav a{font-size:13px;font-weight:700;color:#555}.nav a:hover,.nav a.active{color:#111}.nav a.active{position:relative}.nav a.active:after{content:"";position:absolute;right:0;left:0;bottom:-14px;height:2px;background:var(--brand)}

/* ═══ HERO v4 — تكبير على الجوال ═══ */
.hero{margin:24px auto 0;display:grid;grid-template-columns:1.05fr .95fr;min-height:430px;background:#151515;border-radius:28px;overflow:hidden;color:#fff}
.hero-copy{padding:58px 58px 48px;display:flex;flex-direction:column;justify-content:center;position:relative}
.eyebrow{display:inline-flex;width:max-content;gap:8px;align-items:center;color:#ffb37c;font-weight:800;font-size:12px;margin-bottom:17px}
.eyebrow i{width:7px;height:7px;border-radius:50%;background:var(--brand);box-shadow:0 0 0 5px rgba(233,107,44,.18)}
.hero h1{font-size:clamp(34px,4vw,58px);line-height:1.08;margin:0 0 18px;letter-spacing:-1.5px}
.hero p{font-size:16px;line-height:1.9;color:#c9c9c9;max-width:540px;margin:0 0 28px}
.hero-actions{display:flex;gap:10px;flex-wrap:wrap}
.primary{border:0;background:#fff;color:#111;border-radius:12px;padding:13px 21px;font-weight:900;transition:.2s}
.primary:hover{transform:translateY(-2px);background:#fbbf24}
.secondary{border:1px solid #555;background:transparent;color:#fff;border-radius:12px;padding:12px 20px;font-weight:800;transition:.2s}
.secondary:hover{background:rgba(255,255,255,.08)}
.hero-visual{position:relative;min-height:430px;background:linear-gradient(145deg,#292929,#101010);display:grid;place-items:center;overflow:hidden}
.hero-visual:before{content:"";position:absolute;width:360px;height:360px;border-radius:50%;background:rgba(233,107,44,.18);filter:blur(10px)}
.hero-product{position:relative;width:min(80%,420px);height:82%;display:grid;place-items:center;z-index:2}
.hero-product img{max-width:100%;max-height:100%;object-fit:cover;filter:drop-shadow(0 30px 28px rgba(0,0,0,.38));animation:heroFloat 5s ease-in-out infinite}
@keyframes heroFloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.hero-empty{font-size:100px;opacity:.25}
.hero-label{position:absolute;bottom:24px;right:24px;background:rgba(255,255,255,.1);backdrop-filter:blur(10px);padding:10px 13px;border-radius:11px;font-size:12px;color:#eee;z-index:3;border:1px solid rgba(255,255,255,.1)}
.hero-dots{position:absolute;bottom:20px;left:24px;display:flex;gap:6px;z-index:4}
.hero-dot{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.3);cursor:pointer;transition:.25s;border:0;padding:0}
.hero-dot.active{width:24px;border-radius:4px;background:var(--brand2)}

/* ═══ Sections ═══ */
.section{padding:42px 0 0}
.section-head{display:flex;justify-content:space-between;align-items:end;margin-bottom:18px}
.kicker{font-size:11px;font-weight:900;color:var(--brand);letter-spacing:.8px}
.section h2{font-size:26px;margin:4px 0 0}
.section h2 small{color:#aaa;font-size:14px;font-weight:600}

/* ═══ Categories v4 — أيقونات حقيقية ═══ */
.categories{display:grid;grid-template-columns:repeat(6,1fr);gap:12px}
.cat{background:#fff;border:1px solid var(--line);border-radius:18px;padding:18px 12px;text-align:center;transition:.25s;position:relative;overflow:hidden}
.cat::before{content:"";position:absolute;inset:0;background:linear-gradient(135deg,rgba(233,107,44,.05),transparent 60%);opacity:0;transition:.25s}
.cat:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);border-color:rgba(233,107,44,.3)}
.cat:hover::before{opacity:1}
.cat-icon{width:52px;height:52px;border-radius:16px;margin:0 auto 12px;background:linear-gradient(135deg,var(--soft),#f8f4ee);display:grid;place-items:center;font-size:24px;position:relative;transition:.25s;border:1px solid rgba(0,0,0,.03)}
.cat:hover .cat-icon{background:linear-gradient(135deg,var(--brand),var(--brand2));transform:scale(1.08) rotate(-3deg)}
.cat:hover .cat-icon span{filter:brightness(10)}
.cat strong{font-size:13px;font-weight:900;display:block;position:relative}
.cat small{display:block;color:#999;font-size:10px;margin-top:4px;position:relative}

/* ═══ Promo ═══ */
.promo{margin-top:34px;display:grid;grid-template-columns:1fr 1fr;gap:14px}
.promo-card{min-height:150px;border-radius:20px;padding:28px;background:#fff;border:1px solid var(--line);position:relative;overflow:hidden}
.promo-card.dark{background:linear-gradient(135deg,#1f1f1f,#0f0f0f);color:#fff;border:0}
.promo-card h3{margin:0 0 7px;font-size:22px}
.promo-card p{margin:0;color:#777;font-size:13px;line-height:1.7}
.promo-card.dark p{color:#aaa}
.promo-card .circle{position:absolute;width:180px;height:180px;border-radius:50%;left:-50px;bottom:-100px;background:rgba(233,107,44,.22)}

/* ═══ FLASH SALE v4 ═══ */
.flash{margin:32px 0 0;background:linear-gradient(135deg,#2a0e0a 0%,#4a1810 60%,#6b2a17 100%);border-radius:24px;padding:22px;color:#fff;position:relative;overflow:hidden;box-shadow:0 20px 50px -20px rgba(217,75,75,.5)}
.flash::before{content:"";position:absolute;width:280px;height:280px;border-radius:50%;background:rgba(255,255,255,.05);top:-100px;left:-80px;pointer-events:none}
.flash::after{content:"";position:absolute;width:200px;height:200px;border-radius:50%;background:rgba(233,107,44,.15);bottom:-80px;right:-60px;pointer-events:none;filter:blur(20px)}
.flash-head{display:flex;align-items:center;justify-content:space-between;gap:14px;position:relative;z-index:2;flex-wrap:wrap}
.flash-title{display:flex;align-items:center;gap:10px;font-size:22px;font-weight:900;letter-spacing:-.5px}
.flash-title-icon{width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,#ff6b35,#d94b4b);display:grid;place-items:center;font-size:22px;box-shadow:0 8px 20px rgba(217,75,75,.5);animation:flashPulse 2s ease-in-out infinite}
@keyframes flashPulse{0%,100%{transform:scale(1)}50%{transform:scale(1.08)}}
.flash-sub{font-size:11px;color:#ffb8a1;margin-top:3px;font-weight:700}
.flash-timer{display:flex;gap:6px;direction:ltr;align-items:center}
.timebox{min-width:52px;padding:8px 6px;border-radius:12px;background:rgba(0,0,0,.35);text-align:center;border:1px solid rgba(255,255,255,.08);backdrop-filter:blur(8px)}
.timebox b{font-size:20px;display:block;font-weight:900;font-variant-numeric:tabular-nums;color:#fff}
.timebox span{font-size:8px;color:#ffb8a1;font-weight:700;text-transform:uppercase}
.time-sep{font-size:20px;font-weight:900;color:#ffb8a1;padding:0 2px}
.flash-products{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-top:18px;position:relative;z-index:2}
.flash-products .product{background:rgba(255,255,255,.97)}

/* ═══ Filter v4 — تصميم أجمل ═══ */
.filter-shell{margin-bottom:22px}
.filters{background:#fff;border:1px solid var(--line);border-radius:20px;padding:16px;box-shadow:var(--shadow)}
.filters-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:14px;flex-wrap:wrap}
.filters-title{display:flex;align-items:center;gap:8px;font-size:14px;font-weight:900}
.filters-title-icon{width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,#fff7ed,#fed7aa);display:grid;place-items:center;font-size:15px}
.filters-reset{font-size:11px;font-weight:900;color:#999;padding:6px 12px;border-radius:9px;background:#f8f8f7;transition:.2s;border:0;cursor:pointer}
.filters-reset:hover{background:#fee;color:var(--danger)}
.filters-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px}
.filter-block{background:#fafaf8;border:1px solid #f0eeea;border-radius:14px;padding:12px;transition:.2s}
.filter-block:hover{border-color:rgba(233,107,44,.3);background:#fffdf9}
.filter-block-label{font-size:10px;font-weight:900;color:#999;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;display:flex;align-items:center;gap:5px}
.sort-chips{display:flex;gap:6px;flex-wrap:wrap}
.sort-chip{padding:8px 14px;border-radius:999px;background:#fff;border:1.5px solid #e9e7e2;color:#666;font-size:11px;font-weight:900;cursor:pointer;transition:.2s;white-space:nowrap;display:inline-flex;align-items:center;gap:4px}
.sort-chip:hover{border-color:var(--brand);color:var(--brand);background:#fff9f5}
.sort-chip.active{background:linear-gradient(135deg,#151515,#000);border-color:#000;color:#fff;box-shadow:0 6px 16px -4px rgba(0,0,0,.3)}
.price-inputs{display:grid;grid-template-columns:1fr auto 1fr;gap:8px;align-items:center}
.price-input{width:100%;height:42px;border:1.5px solid #e9e7e2;background:#fff;border-radius:11px;padding:0 12px;font-size:13px;font-weight:800;outline:0;text-align:center;transition:.2s;color:var(--ink)}
.price-input:focus{border-color:var(--brand);box-shadow:0 0 0 3px rgba(233,107,44,.1)}
.price-input::placeholder{color:#bbb;font-weight:700;font-size:11px}
.price-sep{color:#ccc;font-weight:900;font-size:14px}
.filters-actions{display:flex;justify-content:flex-end;gap:8px;margin-top:12px}
.btn-ghost{padding:10px 18px;border-radius:10px;background:#f8f8f7;color:#666;font-size:12px;font-weight:900;cursor:pointer;transition:.2s;border:0}
.btn-ghost:hover{background:#f0eeea;color:#333}
.btn-apply{padding:10px 22px;border-radius:10px;background:linear-gradient(135deg,var(--brand),#d9541a);color:#fff;font-size:12px;font-weight:900;cursor:pointer;transition:.2s;border:0;box-shadow:0 8px 20px -6px rgba(233,107,44,.4)}
.btn-apply:hover{transform:translateY(-2px);box-shadow:0 12px 24px -6px rgba(233,107,44,.5)}

/* ═══ Products v4 — شارات جديدة ═══ */
.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:15px}
.product{background:#fff;border:1px solid var(--line);border-radius:17px;overflow:hidden;transition:.25s;min-width:0;position:relative;display:flex;flex-direction:column}
.product:hover{transform:translateY(-5px);box-shadow:var(--shadow-lg);border-color:rgba(233,107,44,.2)}
.pimg{height:270px;background:linear-gradient(135deg,#f7f6f3,#f0eee9);position:relative;display:grid;place-items:center;overflow:hidden}
.pimg img{width:100%;height:100%;object-fit:cover;padding:18px;transition:.4s cubic-bezier(.2,.9,.3,1.1)}
.product:hover .pimg img{transform:scale(1.06)}

/* Badges Row */
.pbadges{position:absolute;top:11px;right:11px;display:flex;flex-direction:column;gap:5px;z-index:3}
.tag{border-radius:8px;padding:5px 10px;font-size:10px;font-weight:900;color:#fff;box-shadow:0 4px 10px rgba(0,0,0,.15);display:inline-flex;align-items:center;gap:3px;width:max-content}
.tag.sale{background:linear-gradient(135deg,#d94b4b,#b02c2c)}
.tag.new{background:linear-gradient(135deg,#16855b,#0e6b47)}
.tag.hot{background:linear-gradient(135deg,#ff8c00,#e96b2c)}
.tag.out{background:#666}

.heart{position:absolute;top:10px;left:10px;width:36px;height:36px;border-radius:50%;border:1px solid rgba(255,255,255,.9);background:rgba(255,255,255,.95);display:grid;place-items:center;z-index:3;cursor:pointer;transition:.25s;backdrop-filter:blur(8px);box-shadow:0 4px 12px rgba(0,0,0,.08)}
.heart:hover{transform:scale(1.1);background:#fff}
.heart.active{background:#fee;border-color:#fcc}
.heart.active svg{fill:#d94b4b;color:#d94b4b}

.pbody{padding:14px;flex:1;display:flex;flex-direction:column}
.pname{font-size:14px;font-weight:900;line-height:1.4;min-height:40px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;transition:.2s}
.product:hover .pname{color:var(--brand)}
.rating{display:flex;align-items:center;gap:6px;color:#999;font-size:10px;margin:7px 0;font-weight:700}
.stars{color:#e5a31c;letter-spacing:1px}
.price{display:flex;align-items:baseline;gap:8px;flex-wrap:wrap;margin-top:auto;padding-top:6px}
.price strong{font-size:19px;font-weight:900;letter-spacing:-.5px;color:var(--ink)}
.currency{font-size:10px;color:#888;font-weight:700}
.old{text-decoration:line-through;color:#bbb;font-size:11px;font-weight:700}
.add{margin-top:12px;width:100%;height:42px;border:1.5px solid #e9e7e2;background:#fff;border-radius:12px;font-weight:900;font-size:12px;transition:.25s;display:flex;align-items:center;justify-content:center;gap:6px;color:var(--ink)}
.add:hover{background:#151515;color:#fff;border-color:#151515;transform:translateY(-1px);box-shadow:0 8px 20px -6px rgba(0,0,0,.3)}
.add:disabled{opacity:.5;cursor:not-allowed;background:#eee}

/* ═══ Story ═══ */
.story{margin:50px 0;background:linear-gradient(135deg,#eee9e2,#f8f4ed);border-radius:25px;padding:42px;display:grid;grid-template-columns:1fr 1fr;gap:35px;align-items:center}
.story h2{font-size:31px;margin:7px 0 12px;line-height:1.25}
.story p{line-height:1.9;color:#666;margin:0;font-size:14px}
.story-points{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.point{background:#fff;padding:17px;border-radius:14px;box-shadow:0 4px 12px rgba(0,0,0,.04);transition:.2s}
.point:hover{transform:translateY(-3px);box-shadow:var(--shadow)}
.point b{display:block;margin-bottom:5px;font-size:13px;color:var(--ink)}
.point span{font-size:11px;color:#777;line-height:1.6}

/* ═══ Footer ═══ */
.footer{margin-top:55px;background:linear-gradient(180deg,#171717 0%,#0f0f0f 100%);color:#fff;padding:50px 0 110px;position:relative;overflow:hidden}
.footer::before{content:"";position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--brand),var(--brand2),var(--brand));opacity:.9}
.footgrid{display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;gap:36px;position:relative;z-index:2}

.footer h3{margin:0 0 18px;font-size:16px;font-weight:900;color:#fff;letter-spacing:-.2px;position:relative;padding-bottom:10px}
.footer h3::after{content:"";position:absolute;bottom:0;right:0;width:28px;height:2px;background:linear-gradient(90deg,var(--brand),var(--brand2));border-radius:2px}

.footer p{color:#b8b8b8;font-size:13px;line-height:1.9;margin:0;font-weight:500}

.footer .brand-line{font-size:15px;font-weight:900;color:#fff;margin-bottom:12px;display:block}
.footer .brand-desc{color:#a8a8a8;font-size:13px;line-height:1.9;margin-bottom:18px;max-width:320px}
.footer .social-row{display:flex;gap:8px;margin-top:14px}
.footer .social-btn{width:38px;height:38px;border-radius:12px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);display:grid;place-items:center;transition:.25s;color:#fff;font-size:16px}
.footer .social-btn:hover{background:linear-gradient(135deg,var(--brand),var(--brand2));border-color:transparent;transform:translateY(-3px);box-shadow:0 8px 20px rgba(233,107,44,.4)}

.footer a{display:flex;align-items:center;gap:6px;color:#c9c9c9;font-size:13px;font-weight:700;line-height:2.2;transition:.25s;padding:2px 0}
.footer a:hover{color:#ffb37c;transform:translateX(-4px)}
.footer a::before{content:"›";color:var(--brand);font-weight:900;opacity:0;transition:.25s;margin-left:-4px}
.footer a:hover::before{opacity:1;margin-left:0}

.copy{border-top:1px solid rgba(255,255,255,.08);margin-top:36px;padding-top:22px;color:#9a9a9a;font-size:12px;text-align:center;font-weight:600;letter-spacing:.2px;position:relative;z-index:2}
.copy strong{color:#ffb37c;font-weight:900}

.footer-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:rgba(233,107,44,.12);border:1px solid rgba(233,107,44,.25);border-radius:99px;font-size:11px;font-weight:900;color:#ffb37c;margin-top:14px}

html.dark .footer{background:linear-gradient(180deg,#0a0c0f 0%,#000 100%)}
html.dark .footer h3{color:#fff}
html.dark .footer a{color:#b8b8b8}
html.dark .footer a:hover{color:#ffb37c}

@media(max-width:680px){
  .footer{padding:36px 0 100px;margin-top:40px}
  .footgrid{grid-template-columns:1fr 1fr;gap:24px}
  .footer h3{font-size:14px;margin-bottom:14px}
  .footer p{font-size:12px}
  .footer a{font-size:12px}
  .footer .brand-line{font-size:14px}
  .footer .brand-desc{font-size:12px}
  .copy{font-size:11px;padding-top:18px}
}
@media(max-width:420px){
  .footgrid{grid-template-columns:1fr;gap:22px}
  .footer{padding:30px 0 100px}
}

/* ═══ Toast ═══ */
.toast{position:fixed;left:20px;bottom:90px;z-index:100;min-width:260px;max-width:calc(100% - 40px);padding:14px 18px;border-radius:14px;background:#151515;color:#fff;box-shadow:0 20px 50px rgba(0,0,0,.3);transform:translateY(25px);opacity:0;pointer-events:none;transition:.3s cubic-bezier(.2,.9,.3,1.1);font-size:13px;font-weight:800;display:flex;align-items:center;gap:10px}
.toast.show{transform:none;opacity:1}

/* ═══ Drawer ═══ */
.drawer{position:fixed;inset:0;z-index:80;background:rgba(0,0,0,.5);backdrop-filter:blur(4px);opacity:0;pointer-events:none;transition:.3s}
.drawer.open{opacity:1;pointer-events:auto}
.drawer-panel{width:min(340px,88%);height:100%;background:#fff;padding:22px;transform:translateX(100%);transition:.3s cubic-bezier(.2,.9,.3,1.05);overflow:auto}
.drawer.open .drawer-panel{transform:none}
.drawer-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;padding-bottom:16px;border-bottom:1px solid #eee}
.drawer a{display:flex;align-items:center;gap:10px;padding:14px 12px;border-radius:12px;font-weight:800;font-size:13px;transition:.2s;color:#333;margin-bottom:2px}
.drawer a:hover{background:#f8f8f7;color:var(--brand)}
.drawer a i{width:18px;height:18px;color:#999}

/* ═══ Bottom Nav v4 ═══ */
.bottom{display:none}

/* ═══════════════════════════════════════════
   RESPONSIVE
   ═══════════════════════════════════════════ */
@media(max-width:1000px){
  .grid{grid-template-columns:repeat(3,1fr)}
  .categories{grid-template-columns:repeat(3,1fr)}
  .hero{grid-template-columns:1fr}
  .hero-visual{min-height:380px}
  .hero-copy{padding:40px}
  .footgrid{grid-template-columns:1fr 1fr}
  .flash-products{grid-template-columns:repeat(3,1fr)}
}

@media(max-width:680px){
  .wrap{width:min(100% - 20px,600px)}
  .topline{display:none}
  .head{height:62px;grid-template-columns:auto 1fr auto;gap:8px}
  .brandname{display:none}
  .brandmark{width:38px;height:38px}
  .search{height:40px;border-radius:11px}
  .search input{font-size:12px}
  .actions .hide-sm{display:none}
  .iconbtn{width:38px;height:38px}
  .nav{display:none}

  /* Hero v4 — أطول وأكثر حضوراً */
  .hero{margin-top:12px;border-radius:22px;min-height:auto}
  .hero-copy{padding:32px 24px 28px;text-align:right}
  .hero h1{font-size:32px;letter-spacing:-1px}
  .hero p{font-size:13px;margin-bottom:20px}
  .hero-actions{gap:8px}
  .primary,.secondary{padding:11px 16px;font-size:12px}
  .hero-visual{min-height:320px;order:-1}
  .hero-product{height:280px;width:85%}
  .hero-product img{max-height:260px}
  .hero-label{bottom:16px;right:16px;font-size:10px;padding:8px 11px}
  .hero-dots{bottom:14px;left:16px}

  /* Sections */
  .section{padding-top:30px}
  .section h2{font-size:22px}
  .section h2 small{font-size:12px}

  /* Categories — أفقية */
  .categories{display:flex;overflow:auto;padding-bottom:5px;gap:10px;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;scrollbar-width:none}
  .categories::-webkit-scrollbar{display:none}
  .cat{min-width:110px;padding:16px 10px;scroll-snap-align:start;flex-shrink:0}
  .cat-icon{width:46px;height:46px;font-size:22px}
  .cat strong{font-size:12px}

  /* Promo — عمود واحد */
  .promo{grid-template-columns:1fr;gap:10px}
  .promo-card{min-height:120px;padding:22px}
  .promo-card h3{font-size:18px}

  /* Flash Sale */
  .flash{padding:16px;border-radius:18px;margin-top:24px}
  .flash-title{font-size:17px}
  .flash-title-icon{width:36px;height:36px;font-size:18px}
  .flash-sub{font-size:10px}
  .flash-timer{gap:4px}
  .timebox{min-width:44px;padding:6px 4px;border-radius:10px}
  .timebox b{font-size:16px}
  .timebox span{font-size:7px}
  .flash-products{grid-template-columns:repeat(2,1fr);gap:9px;margin-top:14px}
  .flash-products .product:nth-child(n+3){display:none}

  /* Filters v4 — محسّن للجوال */
  .filters{padding:14px;border-radius:16px}
  .filters-title{font-size:13px}
  .filters-grid{grid-template-columns:1fr;gap:10px}
  .filter-block{padding:11px}
  .sort-chips{gap:5px}
  .sort-chip{padding:7px 11px;font-size:10px}
  .price-input{height:40px;font-size:12px}

  /* Products */
  .grid{grid-template-columns:repeat(2,1fr);gap:9px}
  .pimg{height:190px}
  .pimg img{padding:12px}
  .pbody{padding:11px}
  .pname{font-size:12px;min-height:34px}
  .price strong{font-size:16px}
  .add{height:38px;font-size:11px;margin-top:9px}
  .tag{padding:4px 8px;font-size:9px}
  .heart{width:32px;height:32px;top:8px;left:8px}

  /* Story */
  .story{grid-template-columns:1fr;padding:25px;margin-top:35px;gap:20px}
  .story h2{font-size:23px}
  .story p{font-size:13px}
  .story-points{grid-template-columns:1fr 1fr;gap:9px}
  .point{padding:14px}
  .point b{font-size:12px}
  .point span{font-size:10px}

  /* Footer */
  .footgrid{grid-template-columns:1fr 1fr;gap:20px}
  .footer{padding:35px 0 95px}
  .footer h3{font-size:14px}

  /* Toast */
  .toast{left:10px;bottom:80px;min-width:0;right:10px;max-width:none;font-size:12px;padding:12px 14px}
  .drawer-panel{width:85%}

  /* Bottom Nav v4 — أفضل */
  .bottom{display:grid;position:fixed;bottom:10px;right:10px;left:10px;height:64px;background:rgba(21,21,21,.97);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border-radius:20px;z-index:60;grid-template-columns:repeat(5,1fr);box-shadow:0 20px 50px rgba(0,0,0,.3);padding:4px;border:1px solid rgba(255,255,255,.05)}
  .bottom a{display:flex;flex-direction:column;justify-content:center;align-items:center;gap:3px;font-size:9px;color:rgba(255,255,255,.5);font-weight:800;border-radius:16px;transition:.2s;position:relative}
  .bottom a:hover{color:rgba(255,255,255,.8)}
  .bottom a.active{color:#ffb37c;background:rgba(233,107,44,.15)}
  .bottom a.active::before{content:"";position:absolute;top:2px;width:24px;height:3px;border-radius:2px;background:#ffb37c}
  .bottom a i{width:20px;height:20px}
  .bottom .bottom-badge{position:absolute;top:6px;right:calc(50% - 18px);min-width:16px;height:16px;padding:0 4px;border-radius:99px;background:#d94b4b;color:#fff;font-size:9px;font-weight:900;display:grid;place-items:center;border:2px solid #151515}
}

@media(max-width:420px){
  .hero h1{font-size:28px}
  .hero-product{height:240px}
  .hero-product img{max-height:220px}
  .hero-visual{min-height:280px}
  .grid{gap:8px}
  .pimg{height:170px}
  .categories .cat{min-width:100px;padding:14px 8px}
}

/* ═══════════════════════════════════════════
   🎨 WEATHER-EFFECTS-V5
   ظلال + بروز + انعكاس + وضع ليلي + طقس
   ═══════════════════════════════════════════ */

/* ═══ 🌗 Dark Mode ═══ */
html.dark {
  --bg: #0e1014;
  --surface: #1a1d22;
  --ink: #f0ede6;
  --muted: #9a9a9a;
  --line: #2a2e35;
  --soft: #23272e;
  --brand: #ff8f4d;
  --brand2: #f59e0b;
  --shadow: 0 12px 35px rgba(0,0,0,.4);
  --shadow-lg: 0 20px 50px rgba(0,0,0,.55);
}
html.dark body {
  background: #0e1014;
  color: #f0ede6;
}
html.dark .topline { background: #000; }
html.dark .header {
  background: rgba(14,16,20,.92);
  border-bottom-color: #2a2e35;
}
html.dark .search {
  background: #1a1d22;
  border-color: #2a2e35;
  color: #f0ede6;
}
html.dark .search input { color: #f0ede6; }
html.dark .iconbtn {
  background: #1a1d22;
  border-color: #2a2e35;
  color: #f0ede6;
}
html.dark .nav { border-top-color: #2a2e35; }
html.dark .nav a { color: #9a9a9a; }
html.dark .nav a.active { color: #fff; }
html.dark .cat,
html.dark .product,
html.dark .filters,
html.dark .filter-block,
html.dark .promo-card {
  background: #1a1d22;
  border-color: #2a2e35;
  color: #f0ede6;
}
html.dark .cat-icon {
  background: linear-gradient(135deg, #23272e, #2a2e35);
}
html.dark .pimg {
  background: linear-gradient(135deg, #1f232a, #23272e);
}
html.dark .pname { color: #f0ede6; }
html.dark .price strong { color: #fff; }
html.dark .add {
  background: #23272e;
  border-color: #2a2e35;
  color: #f0ede6;
}
html.dark .add:hover {
  background: var(--brand);
  border-color: var(--brand);
  color: #fff;
}
html.dark .drawer-panel {
  background: #1a1d22;
  color: #f0ede6;
}
html.dark .drawer a { color: #f0ede6; }
html.dark .drawer a:hover { background: #23272e; }
html.dark .story {
  background: linear-gradient(135deg, #1a1d22, #23272e);
}
html.dark .point { background: #23272e; color: #f0ede6; }
html.dark .filter-block { background: #23272e; }
html.dark .sort-chip {
  background: #1a1d22;
  border-color: #2a2e35;
  color: #9a9a9a;
}
html.dark .price-input {
  background: #1a1d22;
  border-color: #2a2e35;
  color: #f0ede6;
}

/* ═══ 🌟 Advanced Shadows — 3D Depth ═══ */
.cat,
.product,
.promo-card,
.story,
.filters,
.flash {
  position: relative;
  transform-style: preserve-3d;
  will-change: transform;
}

/* ظل أمامي + انعكاس */
.cat {
  box-shadow:
    0 1px 2px rgba(20,20,20,.04),
    0 4px 8px rgba(20,20,20,.05),
    0 12px 24px rgba(20,20,20,.06),
    0 24px 48px -12px rgba(20,20,20,.08);
  transition: all .35s cubic-bezier(.2,.9,.3,1.1);
}
.cat:hover {
  box-shadow:
    0 2px 4px rgba(20,20,20,.05),
    0 8px 16px rgba(233,107,44,.08),
    0 20px 40px rgba(233,107,44,.12),
    0 32px 64px -16px rgba(20,20,20,.15);
  transform: translateY(-6px) scale(1.01);
}

/* بطاقات المنتجات — بروز كامل */
.product {
  box-shadow:
    0 1px 2px rgba(20,20,20,.04),
    0 3px 6px rgba(20,20,20,.04),
    0 8px 16px rgba(20,20,20,.05),
    0 18px 36px -8px rgba(20,20,20,.08);
  transition: all .4s cubic-bezier(.2,.9,.3,1.1);
}
.product:hover {
  box-shadow:
    0 2px 4px rgba(20,20,20,.05),
    0 6px 12px rgba(233,107,44,.08),
    0 16px 32px rgba(233,107,44,.12),
    0 32px 64px -12px rgba(20,20,20,.18),
    0 0 0 1px rgba(233,107,44,.15);
  transform: translateY(-8px) scale(1.015);
}

/* انعكاس ضوئي على الصورة عند hover */
.pimg::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,0) 30%, rgba(255,255,255,.25) 50%, rgba(255,255,255,0) 70%);
  opacity: 0;
  transition: opacity .5s, transform .6s;
  transform: translateX(-100%);
  pointer-events: none;
  z-index: 2;
}
.product:hover .pimg::after {
  opacity: 1;
  transform: translateX(100%);
}

/* Filter block */
.filters {
  box-shadow:
    0 1px 2px rgba(20,20,20,.04),
    0 6px 12px rgba(20,20,20,.05),
    0 20px 40px -10px rgba(20,20,20,.08);
}
.filter-block {
  box-shadow: 0 1px 2px rgba(20,20,20,.03), 0 2px 6px rgba(20,20,20,.04);
  transition: all .25s;
}
.filter-block:hover {
  box-shadow: 0 2px 4px rgba(20,20,20,.04), 0 6px 16px rgba(233,107,44,.08);
  transform: translateY(-2px);
}

/* Promo cards — بروز قوي */
.promo-card {
  box-shadow:
    0 1px 3px rgba(20,20,20,.05),
    0 8px 16px rgba(20,20,20,.06),
    0 24px 48px -12px rgba(20,20,20,.1);
  transition: all .35s cubic-bezier(.2,.9,.3,1.1);
}
.promo-card:hover {
  transform: translateY(-5px);
  box-shadow:
    0 4px 8px rgba(20,20,20,.06),
    0 12px 24px rgba(20,20,20,.08),
    0 32px 64px -16px rgba(20,20,20,.15);
}

/* Flash Sale — بروز درامي */
.flash {
  box-shadow:
    0 4px 8px rgba(217,75,75,.15),
    0 12px 24px rgba(217,75,75,.2),
    0 32px 64px -16px rgba(217,75,75,.25);
}
.flash:hover {
  box-shadow:
    0 6px 12px rgba(217,75,75,.2),
    0 20px 40px rgba(217,75,75,.25),
    0 48px 80px -20px rgba(217,75,75,.3);
}

/* Story — بروز ناعم */
.story {
  box-shadow:
    0 2px 4px rgba(20,20,20,.03),
    0 12px 24px rgba(20,20,20,.05),
    0 32px 64px -16px rgba(20,20,20,.08);
}

/* Hero — بروز ملكي */
.hero {
  box-shadow:
    0 4px 8px rgba(0,0,0,.1),
    0 16px 32px rgba(0,0,0,.15),
    0 48px 80px -24px rgba(0,0,0,.25);
}
.hero:hover {
  box-shadow:
    0 6px 12px rgba(0,0,0,.12),
    0 24px 48px rgba(0,0,0,.18),
    0 64px 96px -32px rgba(0,0,0,.3);
}

/* Header — ظل ناعم */
.header {
  box-shadow:
    0 1px 2px rgba(20,20,20,.03),
    0 4px 8px rgba(20,20,20,.04),
    0 12px 24px -8px rgba(20,20,20,.06);
}

/* ═══════════════════════════════════════════
   ☀️🌧️❄️ Weather Effects
   ═══════════════════════════════════════════ */

/* طبقة الطقس (خلفية ثابتة) */
.weather-layer {
  position: fixed;
  inset: 0;
  pointer-events: none;
  z-index: 1;
  opacity: 0;
  transition: opacity 1.2s ease;
  overflow: hidden;
}
.weather-layer.active { opacity: 1; }
.weather-layer * { pointer-events: none; }

/* ☀️ مشمس — توهج ذهبي */
.weather-layer.sunny {
  background: radial-gradient(circle at 15% 10%, rgba(255,184,77,.18), transparent 40%),
              radial-gradient(circle at 85% 20%, rgba(255,150,50,.1), transparent 35%);
}

/* 🌧️ ممطر — قطرات */
.weather-layer.rainy {
  background: linear-gradient(180deg, rgba(80,90,110,.06), rgba(50,60,80,.12));
}
.rain-drop {
  position: absolute;
  width: 2px;
  height: 18px;
  background: linear-gradient(180deg, transparent, rgba(120,150,200,.6));
  border-radius: 1px;
  animation: rainFall linear infinite;
}
@keyframes rainFall {
  0%   { transform: translateY(-100px); opacity: 0; }
  10%  { opacity: 1; }
  100% { transform: translateY(100vh); opacity: .7; }
}

/* ❄️ ثلج */
.weather-layer.snowy {
  background: linear-gradient(180deg, rgba(220,235,255,.06), rgba(200,220,240,.1));
}
.snow-flake {
  position: absolute;
  color: rgba(255,255,255,.85);
  font-size: 14px;
  animation: snowFall linear infinite;
  user-select: none;
}
@keyframes snowFall {
  0%   { transform: translateY(-50px) rotate(0deg); opacity: 0; }
  10%  { opacity: 1; }
  100% { transform: translateY(100vh) rotate(360deg); opacity: .7; }
}

/* ☁️ غائم */
.weather-layer.cloudy {
  background: linear-gradient(180deg, rgba(160,170,190,.08), rgba(140,150,170,.12));
}
.cloud {
  position: absolute;
  background: rgba(255,255,255,.4);
  border-radius: 100px;
  filter: blur(20px);
  animation: cloudMove linear infinite;
}
@keyframes cloudMove {
  0%   { transform: translateX(100vw); }
  100% { transform: translateX(-100vw); }
}

/* 🌙 ليلي */
.weather-layer.night {
  background: radial-gradient(circle at 80% 15%, rgba(200,180,255,.12), transparent 30%),
              linear-gradient(180deg, rgba(20,20,40,.15), rgba(10,10,30,.25));
}
.star {
  position: absolute;
  background: rgba(255,255,255,.9);
  border-radius: 50%;
  animation: twinkle 2s infinite;
}
@keyframes twinkle {
  0%, 100% { opacity: .3; transform: scale(1); }
  50%      { opacity: 1; transform: scale(1.3); }
}

/* 🌤️ غروب */
.weather-layer.sunset {
  background: linear-gradient(180deg, rgba(255,120,60,.12), rgba(255,80,120,.08), transparent);
}

/* ═══ زر اختيار الطقس ═══ */
.weather-picker {
  position: fixed;
  top: 50%;
  left: 8px;
  transform: translateY(-50%) translateX(-100%);
  z-index: 90;
  background: rgba(255,255,255,.98);
  backdrop-filter: blur(14px);
  border-radius: 22px;
  padding: 8px;
  box-shadow: 0 12px 32px rgba(0,0,0,.18);
  display: flex;
  flex-direction: column;
  gap: 3px;
  border: 1px solid rgba(0,0,0,.06);
  transition: transform .35s cubic-bezier(.2,.9,.3,1.05), opacity .3s;
  opacity: 0;
  pointer-events: none;
  max-height: 90vh;
  overflow: hidden;
}
html.dark .weather-picker {
  background: rgba(26,29,34,.98);
  border-color: rgba(255,255,255,.08);
}
.weather-picker.open {
  transform: translateY(-50%) translateX(0);
  opacity: 1;
  pointer-events: auto;
}

/* زر فتح المنتقي (الوضع المطوي) */
.weather-toggle {
  position: fixed;
  top: 50%;
  left: 4px;
  transform: translateY(-50%);
  z-index: 91;
  width: 30px;
  height: 56px;
  background: linear-gradient(135deg, var(--brand), var(--brand2));
  border: 0;
  border-radius: 0 14px 14px 0;
  color: #fff;
  font-size: 18px;
  cursor: pointer;
  display: grid;
  place-items: center;
  box-shadow: 0 8px 20px rgba(233,107,44,.4);
  transition: all .3s;
  padding: 0;
}
.weather-toggle:hover {
  transform: translateY(-50%) translateX(2px);
  box-shadow: 0 12px 28px rgba(233,107,44,.55);
}
.weather-toggle .arrow {
  transition: transform .3s;
  font-size: 11px;
}
.weather-picker.open ~ .weather-toggle .arrow {
  transform: rotate(180deg);
}

.weather-btn {
  width: 38px;
  height: 38px;
  border-radius: 12px;
  border: 0;
  background: transparent;
  font-size: 18px;
  cursor: pointer;
  transition: .25s;
  display: grid;
  place-items: center;
}
.weather-btn:hover {
  background: rgba(233,107,44,.12);
  transform: scale(1.15);
}
.weather-btn.active {
  background: linear-gradient(135deg, var(--brand), var(--brand2));
  box-shadow: 0 4px 12px rgba(233,107,44,.4);
  color: #fff;
}

/* خلفية معتمة عند الفتح */
.weather-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.15);
  z-index: 89;
  opacity: 0;
  pointer-events: none;
  transition: opacity .3s;
  backdrop-filter: blur(2px);
}
.weather-overlay.show {
  opacity: 1;
  pointer-events: auto;
}

@media (max-width: 680px) {
  .weather-picker {
    left: 6px;
    padding: 6px;
    gap: 2px;
    border-radius: 18px;
  }
  .weather-btn {
    width: 34px;
    height: 34px;
    font-size: 16px;
  }
  .weather-toggle {
    width: 26px;
    height: 48px;
    font-size: 16px;
  }
}



/* ═══ 🔗 Social Icons ═══ */
.social-row{display:flex;gap:10px;margin-top:16px;flex-wrap:wrap}
.social-btn{width:44px;height:44px;border-radius:14px;display:grid;place-items:center;transition:.3s cubic-bezier(.2,.9,.3,1.1);color:#fff;position:relative;overflow:hidden;text-decoration:none;box-shadow:0 4px 12px rgba(0,0,0,.15)}
.social-btn svg{width:20px;height:20px;fill:currentColor;transition:.3s;z-index:2;position:relative}
.social-btn::before{content:"";position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.2),transparent 60%);opacity:0;transition:.3s}
.social-btn:hover{transform:translateY(-4px) scale(1.05);box-shadow:0 12px 28px rgba(0,0,0,.3)}
.social-btn:hover::before{opacity:1}
.social-btn:hover svg{transform:scale(1.15) rotate(-5deg)}

.social-btn.facebook{background:linear-gradient(135deg,#1877F2,#0d5dbf)}
.social-btn.facebook:hover{box-shadow:0 12px 28px rgba(24,119,242,.5)}

.social-btn.whatsapp{background:linear-gradient(135deg,#25D366,#128C7E)}
.social-btn.whatsapp:hover{box-shadow:0 12px 28px rgba(37,211,102,.5)}

.social-btn.telegram{background:linear-gradient(135deg,#229ED9,#0088cc)}
.social-btn.telegram:hover{box-shadow:0 12px 28px rgba(34,158,217,.5)}

.social-btn.phone{background:linear-gradient(135deg,#e96b2c,#d9541a)}
.social-btn.phone:hover{box-shadow:0 12px 28px rgba(233,107,44,.5)}

/* Tooltip */
.social-btn::after{content:attr(data-tip);position:absolute;bottom:calc(100% + 8px);left:50%;transform:translateX(-50%) scale(.8);background:#151515;color:#fff;font-size:10px;font-weight:900;padding:5px 10px;border-radius:8px;white-space:nowrap;opacity:0;pointer-events:none;transition:.25s;z-index:10}
.social-btn:hover::after{opacity:1;transform:translateX(-50%) scale(1)}

@media(max-width:680px){
  .social-btn{width:40px;height:40px;border-radius:12px}
  .social-btn svg{width:18px;height:18px}
}



/* ═══ 🔍 Autocomplete Dropdown ═══ */
.ac-wrap {
  position: relative;
  flex: 1;
  max-width: 620px;
  margin: auto;
}
.ac-dropdown {
  position: absolute;
  top: calc(100% + 8px);
  right: 0;
  left: 0;
  background: #fff;
  border-radius: 18px;
  box-shadow:
    0 4px 12px rgba(15,23,42,0.06),
    0 20px 50px -12px rgba(15,23,42,0.18);
  border: 1.5px solid #e5e7eb;
  z-index: 999;
  max-height: 480px;
  overflow-y: auto;
  display: none;
  animation: acFadeIn 0.2s ease;
  scrollbar-width: thin;
  scrollbar-color: #cbd5e1 transparent;
}
.ac-dropdown::-webkit-scrollbar { width: 6px; }
.ac-dropdown::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}
.ac-dropdown.show { display: block; }
@keyframes acFadeIn {
  from { opacity: 0; transform: translateY(-6px); }
  to   { opacity: 1; transform: translateY(0); }
}

.ac-section-title {
  padding: 12px 16px 6px;
  font-size: 10px;
  font-weight: 900;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  background: #f8fafc;
  border-bottom: 1px solid #f1f5f9;
}

.ac-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  cursor: pointer;
  transition: .15s;
  text-decoration: none;
  color: inherit;
  border-bottom: 1px solid #f8fafc;
}
.ac-item:last-child { border-bottom: 0; }
.ac-item:hover, .ac-item.active {
  background: #fff7ed;
}
.ac-item:hover .ac-item-name mark, .ac-item.active .ac-item-name mark {
  background: #fbbf24;
}

.ac-item-img {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  overflow: hidden;
  background: #f1f5f9;
  flex-shrink: 0;
  display: grid;
  place-items: center;
  font-size: 20px;
}
.ac-item-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.ac-item-body { flex: 1; min-width: 0; }
.ac-item-name {
  font-size: 13px;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.4;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  margin-bottom: 3px;
}
.ac-item-name mark {
  background: #fef3c7;
  color: #b45309;
  padding: 0 2px;
  border-radius: 3px;
}
.ac-item-meta {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 11px;
  color: #64748b;
  font-weight: 700;
}
.ac-item-price {
  color: #e96b2c;
  font-weight: 900;
  font-size: 13px;
}
.ac-item-old {
  color: #94a3b8;
  text-decoration: line-through;
  font-size: 10px;
}
.ac-item-discount {
  background: #dc2626;
  color: #fff;
  padding: 2px 6px;
  border-radius: 6px;
  font-size: 9px;
  font-weight: 900;
}

.ac-cat-item {
  padding: 10px 16px;
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  text-decoration: none;
  color: inherit;
  transition: .15s;
  border-bottom: 1px solid #f8fafc;
}
.ac-cat-item:hover { background: #fff7ed; }
.ac-cat-icon {
  width: 32px;
  height: 32px;
  border-radius: 10px;
  background: #fef3c7;
  display: grid;
  place-items: center;
  font-size: 16px;
  flex-shrink: 0;
}
.ac-cat-name {
  font-size: 13px;
  font-weight: 800;
  color: #334155;
}

.ac-loading, .ac-empty {
  padding: 24px 16px;
  text-align: center;
  font-size: 13px;
  color: #94a3b8;
  font-weight: 700;
}
.ac-empty-icon { font-size: 36px; margin-bottom: 6px; }

.ac-footer {
  padding: 10px 16px;
  border-top: 1px solid #f1f5f9;
  background: #f8fafc;
  text-align: center;
}
.ac-footer a {
  font-size: 12px;
  font-weight: 900;
  color: #e96b2c;
  text-decoration: none;
}
.ac-footer a:hover { text-decoration: underline; }

/* Skeleton */
.ac-skel {
  height: 60px;
  margin: 8px 16px;
  background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
  background-size: 200% 100%;
  animation: acSkel 1.4s infinite;
  border-radius: 12px;
}
@keyframes acSkel {
  0%   { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

@media (max-width: 640px) {
  .ac-dropdown { max-height: 380px; }
  .ac-item { padding: 10px 12px; gap: 10px; }
  .ac-item-img { width: 42px; height: 42px; }
  .ac-item-name { font-size: 12px; }
}


/* ═══ 🔧 إصلاحات CSS الناقصة ═══ */

/* 🌗 Theme Toggle Button */
.theme-btn {
  width: 42px;
  height: 42px;
  border-radius: 12px;
  border: 1px solid var(--line);
  background: #fff;
  display: grid;
  place-items: center;
  cursor: pointer;
  font-size: 18px;
  transition: all .3s cubic-bezier(.2,.9,.3,1.1);
  position: relative;
  overflow: hidden;
}
.theme-btn:hover {
  transform: scale(1.08) rotate(-8deg);
  border-color: var(--brand);
  box-shadow: 0 4px 12px rgba(233,107,44,.2);
}
html.dark .theme-btn {
  background: #1a1d22;
  border-color: #2a2e35;
}

/* 🔍 Autocomplete — عرض الكل */
.ac-all {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 12px 16px;
  background: linear-gradient(135deg, #fff7ed, #fef3c7);
  color: #c2410c;
  font-size: 13px;
  font-weight: 900;
  text-decoration: none;
  border-top: 1px solid #f1f5f9;
  transition: all .2s;
  border-radius: 0 0 18px 18px;
}
.ac-all:hover {
  background: linear-gradient(135deg, #fed7aa, #fdba74);
  color: #9a3412;
}

/* 🔍 Autocomplete — بديل بدون صورة */
.ac-noimg {
  width: 100%;
  height: 100%;
  display: grid;
  place-items: center;
  background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
  font-size: 22px;
  color: #94a3b8;
}

/* 🔍 Autocomplete — شارة نفذ */
.ac-out {
  display: inline-block;
  padding: 2px 8px;
  background: #fee2e2;
  color: #991b1b;
  border-radius: 6px;
  font-size: 10px;
  font-weight: 900;
  margin-right: 6px;
}

/* 📱 Mobile adjustments */
@media (max-width: 680px) {
  .theme-btn {
    width: 38px;
    height: 38px;
    font-size: 16px;
  }
  .ac-all {
    font-size: 12px;
    padding: 10px 14px;
  }
}

/* 🌗 Dark mode adjustments */
html.dark .ac-all {
  background: linear-gradient(135deg, #23272e, #2a2e35);
  color: #ffb37c;
  border-top-color: #2a2e35;
}
html.dark .ac-all:hover {
  background: linear-gradient(135deg, #2a2e35, #333942);
  color: #ffd4b3;
}
html.dark .ac-noimg {
  background: linear-gradient(135deg, #23272e, #2a2e35);
  color: #6b7280;
}
html.dark .ac-out {
  background: #4a1e1e;
  color: #fca5a5;
}


</style>
</head>
<body>

@php
$shopName = $shop->name ?? 'المتجر';
$heroProducts = isset($products) ? $products->filter(fn($p)=>!empty($p->image))->take(5)->values() : collect();
$hero = $heroProducts->first();

$saleProducts = isset($products) ? $products->filter(function($p){
  return isset($p->compare_price,$p->price) && $p->compare_price > $p->price;
})->take(8) : collect();

$cartCount = 0;
$sessionCart = session('cart', []);
if (is_array($sessionCart)) {
    foreach ($sessionCart as $item) {
        $cartCount += is_array($item) ? (int)($item['qty'] ?? 0) : (int)$item;
    }
}

$currentSort = request('sort','latest');
$currentCategory = request('category');
$currentQuery = trim((string)request('q',''));
$minPrice = request('min_price');
$maxPrice = request('max_price');
@endphp

<div class="topline"><div class="wrap">
  <span>تسوق بسهولة من متجرك المفضل</span>
  <b>{{ $shopName }}</b>
  <span>منتجات مختارة بعناية</span>
  @if(request()->is('demo-shop*') || str_contains($shopName ?? '', 'تجريبي'))
    <a href="/demo-dashboard" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);color:#fff;padding:5px 14px;border-radius:99px;font-size:11px;font-weight:900;text-decoration:none;margin-right:auto;display:inline-flex;align-items:center;gap:5px;box-shadow:0 4px 12px rgba(139,92,246,0.3);transition:all .2s;">
      🎮 لوحة التحكم التجريبية
    </a>
  @endif
</div></div>

<header class="header">
  <div class="wrap head">
    <a class="brand" href="/shop">
      <span class="brandmark"><i data-lucide="shopping-bag"></i></span>
      <span class="brandname">{{ $shopName }}</span>
    </a>
    <form class="search ac-wrap" action="/shop" method="GET" autocomplete="off">
  <i data-lucide="search" width="18"></i>
  <input type="text"
    name="q"
    id="acInput"
    value="{{ request('q','') }}"
    placeholder="ابحث عن منتج، تصنيف..."
    autocomplete="off"
    aria-label="بحث"
    aria-expanded="false"
    aria-haspopup="listbox">
  <button type="submit" style="display:none"></button>
  <div class="ac-dropdown" id="acDropdown" role="listbox"></div>
</form>
    <div class="actions">
      <button class="theme-btn" onclick="toggleTheme()" aria-label="تبديل الوضع" id="themeBtn">🌙</button>
      <button class="iconbtn hide-sm" onclick="openDrawer()" aria-label="القائمة"><i data-lucide="menu" width="18"></i></button>
      <a class="iconbtn" href="/wishlist" aria-label="المفضلة"><i data-lucide="heart" width="18"></i></a>
      <a class="iconbtn" href="/cart" aria-label="السلة">
        <i data-lucide="shopping-cart" width="18"></i>
        <span id="cartBadge" class="badge" style="display:none;">0</span>
      </a>
    </div>
  </div>
  <div class="nav">
    <div class="wrap">
      <a class="active" href="/shop">الرئيسية</a>
      <a href="#categories">التصنيفات</a>
      <a href="#offers">العروض</a>
      <a href="#productsSection">المنتجات</a>
      <a href="#about">عن المتجر</a>
      <a href="/track">تتبع طلبك</a>
    </div>
  </div>
</header>

<main class="wrap">

  {{-- ═══ Hero v4 — يدعم السلايدر ═══ --}}
  <section class="hero" id="heroSection">
    <div class="hero-copy">
      <div class="eyebrow"><i></i>تجربة تسوق أبسط وأجمل</div>
      <h1>اكتشف منتجات<br><span style="color:#ff9b63">تستحق التجربة</span></h1>
      <p>تصفح مجموعتنا المختارة، قارن الأسعار، واختر ما يناسبك بسهولة من واجهة مصممة للتسوق الحقيقي.</p>
      <div class="hero-actions">
        <button class="primary" onclick="document.getElementById('productsSection').scrollIntoView({behavior:'smooth'})">
          تسوق الآن <i data-lucide="arrow-left" width="15" style="vertical-align:middle"></i>
        </button>
        <a class="secondary" href="#categories">استكشف التصنيفات</a>
      </div>
    </div>
    <div class="hero-visual">
      @if($heroProducts->count() > 1)
        @foreach($heroProducts as $i => $hp)
          <div class="hero-product hero-slide {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}" style="{{ $i === 0 ? '' : 'opacity:0;position:absolute;inset:0;' }}">
            <a href="/product/{{ $hp->id }}" aria-label="{{ $hp->name }}">
              <img src="{{ $hp->image_url ?? Storage::url($hp->image) }}" alt="{{ $hp->name }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
            </a>
          </div>
        @endforeach
        <div class="hero-label">اختيارات مميزة من {{ $shopName }}</div>
        <div class="hero-dots">
          @foreach($heroProducts as $i => $hp)
            <button class="hero-dot {{ $i === 0 ? 'active' : '' }}" onclick="heroGo({{ $i }})" aria-label="الشريحة {{ $i+1 }}"></button>
          @endforeach
        </div>
      @elseif($hero && $hero->image)
        <div class="hero-product">
          <img src="{{ $hero->image_url ?? Storage::url($hero->image) }}" alt="{{ $hero->name }}">
        </div>
        <div class="hero-label">اختيارات مميزة من {{ $shopName }}</div>
      @else
        <div class="hero-empty">🛍️</div>
      @endif
    </div>
  </section>

  {{-- ═══ Categories v4 — أيقونات حقيقية ═══ --}}
  @if(isset($categories) && $categories->count())
  <section id="categories" class="section">
    <div class="section-head">
      <div>
        <div class="kicker">SHOP BY CATEGORY</div>
        <h2>تسوق حسب التصنيف</h2>
      </div>
    </div>
    <div class="categories">
      @foreach($categories as $c)
        @php
          $name = $c->name ?? '';
          $icon = '📦';
          if (preg_match('/عسل|شهد/u', $name)) $icon = '🍯';
          elseif (preg_match('/ملابس|أزياء|ثياب|قميص/u', $name)) $icon = '👕';
          elseif (preg_match('/حذا|أحذية|نعال/u', $name)) $icon = '👟';
          elseif (preg_match('/إلكترون|جوال|هاتف|كمبيوتر/u', $name)) $icon = '📱';
          elseif (preg_match('/أطفال|طفل|صبي|طفلة/u', $name)) $icon = '🧸';
          elseif (preg_match('/مكياج|عطر|جمال|تجميل/u', $name)) $icon = '💄';
          elseif (preg_match('/مستلزمات|منزل|أثاث/u', $name)) $icon = '🏠';
          elseif (preg_match('/سيارة|سيارات|مركبات/u', $name)) $icon = '🚗';
          elseif (preg_match('/ذهب|فضة|مجوهرات|إكسسوارات/u', $name)) $icon = '💎';
          elseif (preg_match('/رياضة|رياضي/u', $name)) $icon = '⚽';
          elseif (preg_match('/كتاب|قرطاسية/u', $name)) $icon = '📚';
          elseif (preg_match('/منتجات أخرى|أخرى/u', $name)) $icon = '🎁';
        @endphp
        <a class="cat" href="?category={{ $c->id }}">
          <span class="cat-icon"><span>{{ $icon }}</span></span>
          <strong>{{ $name }}</strong>
          <small>عرض المنتجات</small>
        </a>
      @endforeach
    </div>
  </section>
  @endif

  {{-- ═══ Flash Sale v4 ═══ --}}
  @if($saleProducts->count())
  <section id="offers" class="flash">
    <div class="flash-head">
      <div>
        <div class="flash-title">
          <span class="flash-title-icon">⚡</span>
          <span>عروض محدودة</span>
        </div>
        <div class="flash-sub">خصومات حقيقية · تنتهي اليوم · لا تفوّتها</div>
      </div>
      <div class="flash-timer" id="flashTimer">
        <div class="timebox"><b id="fh">00</b><span>ساعة</span></div>
        <span class="time-sep">:</span>
        <div class="timebox"><b id="fm">00</b><span>دقيقة</span></div>
        <span class="time-sep">:</span>
        <div class="timebox"><b id="fs">00</b><span>ثانية</span></div>
      </div>
    </div>
    <div class="flash-products">
      @foreach($saleProducts->take(4) as $p)
        @php
          $disc = ($p->compare_price > $p->price) ? round((1 - ($p->price/$p->compare_price))*100) : 0;
        @endphp
        <article class="product">
          <div class="pimg">
            <div class="pbadges">
              @if($disc > 0)<span class="tag sale">-{{ $disc }}%</span>@endif
            </div>
            <a href="/product/{{ $p->id }}" style="display:block;width:100%;height:100%">
              @if($p->image)<img src="{{ $p->image_url ?? ($p->image_url ?? Storage::url($p->image)) }}" alt="{{ $p->name }}" loading="lazy">
              @else<div style="display:grid;place-items:center;height:100%;font-size:50px;opacity:.25">📦</div>@endif
            </a>
          </div>
          <div class="pbody">
            <a href="/product/{{ $p->id }}" class="pname">{{ $p->name }}</a>
            <div class="price">
              <strong>{{ number_format($p->price,0) }}</strong>
              <span class="currency">ر.ي</span>
              <span class="old">{{ number_format($p->compare_price,0) }}</span>
            </div>
            <button class="add" onclick="quickAdd({{ $p->id }},this)">
              <i data-lucide="shopping-bag" width="14"></i> أضف للسلة
            </button>
          </div>
        </article>
      @endforeach
    </div>
  </section>
  @endif

  {{-- ═══ Promo Cards ═══ --}}
  <section class="promo">
    <div class="promo-card dark">
      <div class="circle"></div>
      <h3>عروض تستحق الانتباه</h3>
      <p>اكتشف المنتجات المخفضة والأسعار الخاصة المتاحة الآن.</p>
    </div>
    <div class="promo-card">
      <h3>اختيارات جديدة</h3>
      <p>منتجات جديدة تضاف باستمرار لتجد ما تبحث عنه بسهولة.</p>
    </div>
  </section>

  {{-- ═══ Products + Filters v4 ═══ --}}
  <section id="productsSection" class="section">
    <div class="section-head">
      <div>
        <div class="kicker">OUR PRODUCTS</div>
        <h2 id="productsTitle">كل المنتجات <small>({{ isset($products) ? $products->count() : 0 }})</small></h2>
      </div>
    </div>

    {{-- ═══ FILTER v4 ═══ --}}
    <div class="filters">
      <div class="filters-head">
        <div class="filters-title">
          <span class="filters-title-icon">🎯</span>
          <span>تصفية النتائج</span>
        </div>
        <button type="button" class="filters-reset" onclick="resetFilters()">🔄 مسح الكل</button>
      </div>

      <div class="filters-grid">
        {{-- الترتيب --}}
        <div class="filter-block" style="grid-column: span 2;">
          <div class="filter-block-label">⚡ الترتيب</div>
          <div class="sort-chips" id="sortChips">
            <button type="button" class="sort-chip {{ $currentSort==='latest'?'active':'' }}" data-sort="latest">🆕 الأحدث</button>
            <button type="button" class="sort-chip {{ $currentSort==='best'?'active':'' }}" data-sort="best">🔥 الأكثر مبيعاً</button>
            <button type="button" class="sort-chip {{ $currentSort==='price_asc'?'active':'' }}" data-sort="price_asc">⬆️ الأرخص</button>
            <button type="button" class="sort-chip {{ $currentSort==='price_desc'?'active':'' }}" data-sort="price_desc">⬇️ الأعلى</button>
            <button type="button" class="sort-chip {{ $currentSort==='name'?'active':'' }}" data-sort="name">🔤 الاسم</button>
          </div>
        </div>

        {{-- السعر --}}
        <div class="filter-block">
          <div class="filter-block-label">💵 نطاق السعر (ريال)</div>
          <div class="price-inputs">
            <input type="number" class="price-input" id="minPrice" value="{{ $minPrice }}" placeholder="من" min="0" inputmode="numeric">
            <span class="price-sep">—</span>
            <input type="number" class="price-input" id="maxPrice" value="{{ $maxPrice }}" placeholder="إلى" min="0" inputmode="numeric">
          </div>
        </div>
      </div>

      <div class="filters-actions">
        <button type="button" class="btn-ghost" onclick="resetFilters()">🔄 إعادة</button>
        <button type="button" class="btn-apply" onclick="applyFilters()">🎯 تطبيق</button>
      </div>
    </div>

    {{-- ═══ PRODUCTS GRID v4 ═══ --}}
    <div id="productGrid" class="grid">
      @forelse($products as $p)
        @php
          $hasDiscount = isset($p->compare_price,$p->price) && $p->compare_price > $p->price;
          $discount = $hasDiscount ? round((1 - ($p->price/$p->compare_price))*100) : 0;
          $createdTs = optional($p->created_at)->timestamp ?? 0;
          $isNew = $createdTs && $createdTs >= now()->subDays(14)->timestamp;
          $isHot = ($p->sold_count ?? 0) >= 10;
          // ✅ فحص المخزون الفعلي:
          // 1) إذا كان للمنتج variants → المجموع من variants
          // 2) إذا كان stock مباشر > 0
          // 3) إذا كلاهما صفر → نفد
          $directStock = (int)($p->stock ?? 0);
          $hasVariants = method_exists($p, 'variants') && $p->relationLoaded('variants') && $p->variants->count() > 0;
          if (!$hasVariants && method_exists($p, 'variants')) {
              try { $hasVariants = $p->variants()->exists(); } catch (\Throwable $e) {}
          }
          $variantStock = 0;
          if ($hasVariants && $p->relationLoaded('variants')) {
              $variantStock = (int) $p->variants->sum('stock');
          } elseif ($hasVariants) {
              try { $variantStock = (int) $p->variants()->sum('stock'); } catch (\Throwable $e) {}
          }
          $inStock = ($directStock > 0) || ($variantStock > 0);
        @endphp
        <article class="product"
          data-name="{{ mb_strtolower($p->name ?? '') }}"
          data-category-id="{{ $p->category_id ?? '' }}"
          data-price="{{ (float)($p->price ?? 0) }}"
          data-sold="{{ (int)($p->sold_count ?? 0) }}"
          data-created="{{ $createdTs }}">
          <div class="pimg">
            <div class="pbadges">
              @if($discount > 0)
                <span class="tag sale">-{{ $discount }}%</span>
              @endif
              @if($isNew && $discount === 0)
                <span class="tag new">✨ جديد</span>
              @endif
              @if($isHot && $discount === 0 && !$isNew)
                <span class="tag hot">🔥 مطلوب</span>
              @endif
            </div>
            <a href="/product/{{ $p->id }}" style="display:block;width:100%;height:100%">
              @if($p->image)
                <img src="{{ $p->image_url ?? ($p->image_url ?? Storage::url($p->image)) }}" alt="{{ $p->name }}" loading="lazy">
              @else
                <div style="display:grid;place-items:center;height:100%;font-size:50px;opacity:.25">📦</div>
              @endif
            </a>
            <button class="heart" onclick="toggleWishlist({{ $p->id }},this)" aria-label="المفضلة">
              <i data-lucide="heart" width="16"></i>
            </button>
          </div>
          <div class="pbody">
            <a href="/product/{{ $p->id }}" class="pname">{{ $p->name }}</a>
            <div class="rating">
              <span class="stars">★★★★★</span>
              <span>{{ $isHot ? 'مميز' : 'متاح' }}</span>
            </div>
            <div class="price">
              <strong>{{ number_format($p->price,0) }}</strong>
              <span class="currency">ر.ي</span>
              @if($hasDiscount)
                <span class="old">{{ number_format($p->compare_price,0) }}</span>
              @endif
            </div>
            @if($inStock)
              <button class="add" onclick="quickAdd({{ $p->id }},this)">
                <i data-lucide="shopping-bag" width="14"></i> أضف للسلة
              </button>
            @else
              <button class="add" disabled>نفد المخزون</button>
            @endif
          </div>
        </article>
      @empty
        <div style="grid-column:1/-1;background:#fff;border:1px solid var(--line);padding:50px;text-align:center;border-radius:18px;color:#888">
          <div style="font-size:60px;margin-bottom:12px">🛍️</div>
          <b style="display:block;font-size:16px;color:#333;margin-bottom:6px">لا توجد منتجات حالياً</b>
          <span style="font-size:12px">ستظهر المنتجات هنا عند توفرها</span>
        </div>
      @endforelse
    </div>
  </section>

  {{-- ═══ Story ═══ --}}
  <section id="about" class="story">
    <div>
      <div class="kicker">WHY SHOP WITH US</div>
      <h2>تجربة واضحة من أول نقرة حتى استلام الطلب</h2>
      <p>صممنا واجهة المتجر لتكون بسيطة وسريعة، مع وصول مباشر للمنتجات والتصنيفات والسلة والتتبع دون تشتيت.</p>
    </div>
    <div class="story-points">
      <div class="point"><b>✨ اختيار واضح</b><span>تصنيفات وبحث للوصول للمنتج بسرعة</span></div>
      <div class="point"><b>🛒 سلة سهلة</b><span>أضف منتجاتك وتابع طلبك من مكان واحد</span></div>
      <div class="point"><b>📱 واجهة متجاوبة</b><span>تجربة محسنة للجوال والكمبيوتر</span></div>
      <div class="point"><b>💬 رسائل مريحة</b><span>التنبيهات داخل الصفحة بدل رسائل المتصفح</span></div>
    </div>
  </section>

</main>

<footer class="footer">
  <div class="wrap">
    <div class="footgrid">

      {{-- العمود 1: عن المتجر --}}
      <div>
        <div class="brand-line">{{ $shopName }}</div>
        <p class="brand-desc">
          متجرك الموثوق للمنتجات الأصلية. نوفر تجربة تسوق سهلة وآمنة مع توصيل سريع لكل المحافظات.
        </p>
        <div class="social-row">

          @php
            $__waNumber = preg_replace('/[^0-9]/','', $shop->whatsapp ?? '');
            if ($__waNumber && !str_starts_with($__waNumber, '967') && strlen($__waNumber) <= 10) {
                $__waNumber = '967' . ltrim($__waNumber, '0');
            }
          @endphp

          {{-- Facebook --}}
          @if(($shop->settings['facebook'] ?? false))
            <a class="social-btn facebook" href="{{ $shop->settings['facebook'] }}" target="_blank" rel="noopener" data-tip="فيسبوك" aria-label="فيسبوك">
              <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
          @else
            <a class="social-btn facebook" href="#" onclick="event.preventDefault();if(window.toast)toast('رابط فيسبوك غير مُضاف');" data-tip="فيسبوك" aria-label="فيسبوك">
              <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
          @endif

          {{-- WhatsApp --}}
          @if($__waNumber)
            <a class="social-btn whatsapp" href="https://wa.me/{{ $__waNumber }}" target="_blank" rel="noopener" data-tip="واتساب" aria-label="واتساب">
              <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
          @else
            <a class="social-btn whatsapp" href="#" onclick="event.preventDefault();if(window.toast)toast('رقم واتساب غير مُضاف');" data-tip="واتساب" aria-label="واتساب">
              <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
          @endif

          {{-- Telegram --}}
          @if(($shop->settings['telegram'] ?? false))
            <a class="social-btn telegram" href="{{ $shop->settings['telegram'] }}" target="_blank" rel="noopener" data-tip="تليجرام" aria-label="تليجرام">
              <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
            </a>
          @else
            <a class="social-btn telegram" href="#" onclick="event.preventDefault();if(window.toast)toast('رابط تليجرام غير مُضاف');" data-tip="تليجرام" aria-label="تليجرام">
              <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
            </a>
          @endif

          {{-- Phone --}}
          @if(($shop->phone ?? false))
            <a class="social-btn phone" href="tel:{{ $shop->phone }}" data-tip="اتصال" aria-label="اتصال">
              <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </a>
          @endif

        </div>
      </div>

      {{-- العمود 2: المتجر --}}
      <div>
        <h3>المتجر</h3>
        <a href="/shop">الرئيسية</a>
        <a href="#productsSection">جميع المنتجات</a>
        <a href="#categories">التصنيفات</a>
        <a href="#offers">عروض محدودة</a>
      </div>

      {{-- العمود 3: خدمة العملاء --}}
      <div>
        <h3>خدمة العملاء</h3>
        <a href="/track">تتبع الطلب</a>
        <a href="/cart">سلة التسوق</a>
        <a href="/wishlist">المفضلة</a>
        <a href="/contact">تواصل معنا</a>
      </div>

      {{-- العمود 4: حسابك --}}
      <div>
        <h3>حسابك</h3>
        @auth
          <a href="/account">حسابي</a>
          <a href="/orders">طلباتي</a>
          <form method="POST" action="/logout" style="margin:0">
            @csrf
            <button type="submit" style="background:none;border:0;color:#c9c9c9;font-size:13px;font-weight:700;padding:2px 0;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:6px">
              تسجيل الخروج
            </button>
          </form>
        @else
          <a href="/login">تسجيل الدخول</a>
          <a href="/register">حساب جديد</a>
          <a href="/orders">طلباتي</a>
          <a href="/track">تتبع كزائر</a>
        @endauth
      </div>

    </div>

    <div class="copy">
      © {{ date('Y') }} <strong>{{ $shopName }}</strong> — جميع الحقوق محفوظة · صُنع بـ ❤️ في اليمن
    </div>
  </div>
</footer>

{{-- ═══ Bottom Nav v4 ═══ --}}
<nav class="bottom">
  <a class="active" href="/shop"><i data-lucide="home"></i><span>الرئيسية</span></a>
  <a href="#categories"><i data-lucide="grid-2x2"></i><span>التصنيفات</span></a>
  <a href="#productsSection"><i data-lucide="package"></i><span>المنتجات</span></a>
  <a href="/cart" style="position:relative">
    <i data-lucide="shopping-cart"></i>
    <span>السلة</span>
    @if($cartCount > 0)<span class="bottom-badge">{{ $cartCount }}</span>@endif
  </a>
  <a href="/account"><i data-lucide="user"></i><span>حسابي</span></a>
</nav>

{{-- ═══ Toast ═══ --}}
<div id="toast" class="toast"></div>

{{-- ═══ Drawer ═══ --}}
<div id="drawer" class="drawer" onclick="if(event.target===this)closeDrawer()">
  <aside class="drawer-panel">
    <div class="drawer-head">
      <b style="font-size:16px">{{ $shopName }}</b>
      <button class="iconbtn" onclick="closeDrawer()" style="border:0"><i data-lucide="x" width="18"></i></button>
    </div>
    <a href="/shop"><i data-lucide="home"></i>الرئيسية</a>
    <a href="#categories" onclick="closeDrawer()"><i data-lucide="grid-2x2"></i>التصنيفات</a>
    <a href="#productsSection" onclick="closeDrawer()"><i data-lucide="package"></i>كل المنتجات</a>
    <a href="#offers" onclick="closeDrawer()"><i data-lucide="zap"></i>العروض</a>
    <a href="/wishlist"><i data-lucide="heart"></i>المفضلة</a>
    <a href="/track"><i data-lucide="package-search"></i>تتبع الطلب</a>
    <a href="/account"><i data-lucide="user"></i>حسابي</a>
  </aside>
</div>

<script>
if(window.lucide)lucide.createIcons();

// ═══ Toast ═══
let toastTimer;
function toast(msg, ok=true){
  const el = document.getElementById('toast');
  el.innerHTML = '<span>' + (ok ? '✓' : '⚠️') + '</span><span>' + msg + '</span>';
  el.style.borderRight = '4px solid ' + (ok ? '#54a96b' : '#d34b4b');
  el.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => el.classList.remove('show'), 2800);
}

// ═══ Drawer ═══
function openDrawer(){document.getElementById('drawer').classList.add('open');document.body.style.overflow='hidden'}
function closeDrawer(){document.getElementById('drawer').classList.remove('open');document.body.style.overflow=''}
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeDrawer()});

function csrf(){return document.querySelector('meta[name="csrf-token"]')?.content || ''}

// ═══ Quick Add ═══
async function quickAdd(id, btn){
  if(btn.dataset.busy) return;
  btn.dataset.busy = '1';
  const old = btn.innerHTML;
  btn.innerHTML = '⏳ جاري الإضافة...';
  try {
    const r = await fetch('/cart/add/' + id, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf(),
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({qty: 1})
    });
    const d = await r.json().catch(() => ({}));
    if (!r.ok) throw new Error(d.message || 'تعذر إضافة المنتج');
    const count = d.count || d.cart_count || d.total_items;
    if (count !== undefined) updateCartBadge(count);
    else updateCartBadgeFromPage();
    toast(d.message || '✅ تمت إضافة المنتج للسلة');
  } catch(e) {
    toast(e.message || 'حدث خطأ، حاول مرة أخرى', false);
  } finally {
    btn.dataset.busy = '';
    btn.innerHTML = old;
  }
}

function updateCartBadge(count){
  const b = document.getElementById('cartBadge');
  if (b) {
    b.textContent = count;
    b.style.display = count > 0 ? 'grid' : 'none';
  }
  const bb = document.querySelector('.bottom-badge');
  if (bb) bb.textContent = count;
}
function updateCartBadgeFromPage(){
  const b = document.getElementById('cartBadge');
  if (b) {
    const n = (parseInt(b.textContent) || 0) + 1;
    updateCartBadge(n);
  }
}

// ═══ Wishlist ═══
async function toggleWishlist(id, btn){
  try {
    const r = await fetch('/wishlist/toggle/' + id, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf(),
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      },
    });
    const d = await r.json().catch(() => ({}));
    if (r.status === 401) {
      toast('سجّل الدخول أولاً', false);
      return;
    }
    if (!r.ok) throw new Error(d.message || 'تعذر تحديث المفضلة');
    btn.classList.toggle('active', !!d.added);
    toast(d.message || (d.added ? '❤️ تمت الإضافة للمفضلة' : 'تمت الإزالة من المفضلة'));
  } catch(e) {
    toast(e.message || 'تعذر تحديث المفضلة', false);
  }
}

// ═══ Cart Count on Load ═══
(async()=>{
  try {
    const r = await fetch('/cart/count', {headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}});
    const d = await r.json();
    if (d.count !== undefined) updateCartBadge(d.count);
  } catch(e) {}
})();

// ═══ Hero Slider ═══
(function(){
  const slides = document.querySelectorAll('.hero-slide');
  const dots = document.querySelectorAll('.hero-dot');
  if (slides.length < 2) return;

  let current = 0;
  window.heroGo = function(i){
    current = (i + slides.length) % slides.length;
    slides.forEach((s, n) => {
      s.classList.toggle('active', n === current);
      s.style.opacity = n === current ? '1' : '0';
      s.style.position = n === current ? 'relative' : 'absolute';
      s.style.inset = n === current ? 'auto' : '0';
    });
    dots.forEach((d, n) => d.classList.toggle('active', n === current));
  };
  setInterval(() => window.heroGo(current + 1), 6000);
})();

// ═══ Filters v4 — client-side ═══
let currentSort = @json($currentSort);
let currentCategory = @json($currentCategory);
const grid = document.getElementById('productGrid');
const allProducts = Array.from(document.querySelectorAll('#productGrid .product[data-name]'));

function normalize(s){return String(s||'').toLocaleLowerCase('ar').trim()}

function applyFilters(){
  const min = Number(document.getElementById('minPrice')?.value || 0);
  const maxRaw = document.getElementById('maxPrice')?.value || '';
  const max = maxRaw === '' ? Infinity : Number(maxRaw);

  let visible = 0;
  allProducts.forEach(el => {
    const price = Number(el.dataset.price || 0);
    const cat = String(el.dataset.categoryId || '');
    const okCat = !currentCategory || cat === String(currentCategory);
    const okPrice = price >= min && price <= max;
    const ok = okCat && okPrice;
    el.style.display = ok ? 'flex' : 'none';
    if (ok) visible++;
  });

  const visibleProds = allProducts.filter(p => p.style.display !== 'none');
  visibleProds.sort((a,b) => {
    if (currentSort === 'price_asc') return Number(a.dataset.price) - Number(b.dataset.price);
    if (currentSort === 'price_desc') return Number(b.dataset.price) - Number(a.dataset.price);
    if (currentSort === 'best') return Number(b.dataset.sold) - Number(a.dataset.sold);
    if (currentSort === 'name') return normalize(a.dataset.name).localeCompare(normalize(b.dataset.name), 'ar');
    return Number(b.dataset.created) - Number(a.dataset.created);
  });
  visibleProds.forEach(el => grid.appendChild(el));

  const title = document.getElementById('productsTitle');
  if (title) {
    const small = title.querySelector('small');
    if (small) small.textContent = '(' + visible + ')';
  }
}

document.querySelectorAll('#sortChips .sort-chip').forEach(btn => {
  btn.addEventListener('click', () => {
    currentSort = btn.dataset.sort;
    document.querySelectorAll('#sortChips .sort-chip').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    applyFilters();
  });
});

function resetFilters(){
  currentCategory = null;
  currentSort = 'latest';
  const min = document.getElementById('minPrice'); if (min) min.value = '';
  const max = document.getElementById('maxPrice'); if (max) max.value = '';
  document.querySelectorAll('#sortChips .sort-chip').forEach(b => b.classList.toggle('active', b.dataset.sort === 'latest'));
  allProducts.forEach(el => el.style.display = 'flex');
  history.replaceState({}, '', location.pathname);
  applyFilters();
}

// ═══ Flash Sale Timer ═══
(function(){
  const fh = document.getElementById('fh'), fm = document.getElementById('fm'), fs = document.getElementById('fs');
  if (!fh) return;
  function tick(){
    const now = new Date();
    const end = new Date();
    end.setHours(23, 59, 59, 999);
    let diff = Math.max(0, Math.floor((end - now) / 1000));
    const h = Math.floor(diff / 3600); diff %= 3600;
    const m = Math.floor(diff / 60);
    const s = diff % 60;
    fh.textContent = String(h).padStart(2, '0');
    fm.textContent = String(m).padStart(2, '0');
    fs.textContent = String(s).padStart(2, '0');
  }
  tick();
  setInterval(tick, 1000);
})();

// ═══ Init ═══
document.addEventListener('DOMContentLoaded', () => {
  applyFilters();
});

// ═══════════════════════════════════════════
// 🌗 Theme Management
// ═══════════════════════════════════════════
(function initTheme(){
  const saved = localStorage.getItem('ms-theme');
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const isDark = saved ? saved === 'dark' : prefersDark;
  if (isDark) {
    document.documentElement.classList.add('dark');
    const btn = document.getElementById('themeBtn');
    if (btn) btn.textContent = '☀️';
  }
})();

window.toggleTheme = function(){
  const isDark = document.documentElement.classList.toggle('dark');
  localStorage.setItem('ms-theme', isDark ? 'dark' : 'light');
  const btn = document.getElementById('themeBtn');
  if (btn) btn.textContent = isDark ? '☀️' : '🌙';
  if (window.lucide) lucide.createIcons();
};

// ═══════════════════════════════════════════
// ☀️🌧️ Weather Effects
// ═══════════════════════════════════════════
let weatherInterval = null;

window.toggleWeatherPicker = function(){
  const picker = document.getElementById('weatherPicker');
  const overlay = document.getElementById('weatherOverlay');
  if (!picker) return;
  const isOpen = picker.classList.contains('open');
  if (isOpen) {
    picker.classList.remove('open');
    overlay?.classList.remove('show');
  } else {
    picker.classList.add('open');
    overlay?.classList.add('show');
  }
};

window.closeWeatherPicker = function(){
  document.getElementById('weatherPicker')?.classList.remove('open');
  document.getElementById('weatherOverlay')?.classList.remove('show');
};

window.setWeather = function(type){
  const layer = document.getElementById('weatherLayer');
  if (!layer) return;

  // إغلاق المنتقي تلقائياً بعد الاختيار (على الجوال)
  if (window.innerWidth < 680) {
    setTimeout(closeWeatherPicker, 300);
  }

  // إزالة الأنشطة السابقة
  document.querySelectorAll('.weather-btn').forEach(b => {
    b.classList.toggle('active', b.dataset.weather === type);
  });

  // إيقاف أي تأثير قديم
  if (weatherInterval) { clearInterval(weatherInterval); weatherInterval = null; }
  layer.innerHTML = '';
  layer.className = 'weather-layer';

  if (type === 'none') {
    localStorage.setItem('ms-weather', 'none');
    return;
  }

  layer.classList.add(type);
  layer.classList.add('active');
  localStorage.setItem('ms-weather', type);

  // توليد الجزيئات حسب النوع
  if (type === 'rainy') {
    for (let i = 0; i < 60; i++) {
      const drop = document.createElement('div');
      drop.className = 'rain-drop';
      drop.style.left = Math.random() * 100 + '%';
      drop.style.animationDuration = (0.5 + Math.random() * 0.8) + 's';
      drop.style.animationDelay = (Math.random() * 2) + 's';
      drop.style.opacity = 0.3 + Math.random() * 0.5;
      layer.appendChild(drop);
    }
    // تجديد القطرات
    weatherInterval = setInterval(() => {
      if (layer.children.length > 100) return;
      const drop = document.createElement('div');
      drop.className = 'rain-drop';
      drop.style.left = Math.random() * 100 + '%';
      drop.style.animationDuration = (0.5 + Math.random() * 0.8) + 's';
      layer.appendChild(drop);
      setTimeout(() => drop.remove(), 2500);
    }, 120);
  }

  if (type === 'snowy') {
    const flakes = ['❄️', '❅', '❆', '✻', '✽'];
    for (let i = 0; i < 30; i++) {
      const flake = document.createElement('div');
      flake.className = 'snow-flake';
      flake.textContent = flakes[Math.floor(Math.random() * flakes.length)];
      flake.style.left = Math.random() * 100 + '%';
      flake.style.animationDuration = (6 + Math.random() * 6) + 's';
      flake.style.animationDelay = (Math.random() * 5) + 's';
      flake.style.fontSize = (10 + Math.random() * 12) + 'px';
      layer.appendChild(flake);
    }
  }

  if (type === 'cloudy') {
    for (let i = 0; i < 5; i++) {
      const cloud = document.createElement('div');
      cloud.className = 'cloud';
      const size = 100 + Math.random() * 200;
      cloud.style.width = size + 'px';
      cloud.style.height = (size * 0.4) + 'px';
      cloud.style.top = (10 + Math.random() * 50) + '%';
      cloud.style.animationDuration = (40 + Math.random() * 40) + 's';
      cloud.style.animationDelay = (Math.random() * 20) + 's';
      cloud.style.opacity = 0.3 + Math.random() * 0.4;
      layer.appendChild(cloud);
    }
  }

  if (type === 'night') {
    for (let i = 0; i < 40; i++) {
      const star = document.createElement('div');
      star.className = 'star';
      const size = 1 + Math.random() * 3;
      star.style.width = size + 'px';
      star.style.height = size + 'px';
      star.style.left = Math.random() * 100 + '%';
      star.style.top = Math.random() * 70 + '%';
      star.style.animationDelay = (Math.random() * 3) + 's';
      layer.appendChild(star);
    }
  }
};

// استرجاع الطقس المحفوظ
(function restoreWeather(){
  const saved = localStorage.getItem('ms-weather');
  if (saved && saved !== 'none') {
    setTimeout(() => window.setWeather(saved), 300);
  }
})();

</script>


{{-- 🌤️ Weather Layer --}}
<div id="weatherLayer" class="weather-layer"></div>

{{-- 🎨 Weather Picker --}}
<div class="weather-overlay" id="weatherOverlay" onclick="closeWeatherPicker()"></div>

{{-- 🎨 زر فتح منتقي الطقس --}}
<button class="weather-toggle" onclick="toggleWeatherPicker()" aria-label="تغيير الأجواء" title="تغيير أجواء المتجر">
  <span>🎨</span>
  <span class="arrow">◀</span>
</button>

{{-- منتقي الطقس --}}
<div class="weather-picker" id="weatherPicker">
  <button class="weather-btn" data-weather="none" title="بدون أجواء" onclick="setWeather('none')">🚫</button>
  <button class="weather-btn" data-weather="sunny" title="مشمس" onclick="setWeather('sunny')">☀️</button>
  <button class="weather-btn" data-weather="cloudy" title="غائم" onclick="setWeather('cloudy')">☁️</button>
  <button class="weather-btn" data-weather="rainy" title="ممطر" onclick="setWeather('rainy')">🌧️</button>
  <button class="weather-btn" data-weather="snowy" title="ثلج" onclick="setWeather('snowy')">❄️</button>
  <button class="weather-btn" data-weather="night" title="ليلي" onclick="setWeather('night')">🌙</button>
  <button class="weather-btn" data-weather="sunset" title="غروب" onclick="setWeather('sunset')">🌅</button>
</div>

<script>
// ═══════════════════════════════════════════════════════════
// 🔍 Autocomplete — بحث فوري مع اقتراحات
// ═══════════════════════════════════════════════════════════
(function() {
  const input = document.getElementById('acInput');
  const dropdown = document.getElementById('acDropdown');
  if (!input || !dropdown) return;

  let debounceTimer = null;
  let currentController = null;
  let activeIndex = -1;
  let results = [];

  function search(q) {
    if (currentController) currentController.abort();
    currentController = new AbortController();

    fetch('/api/search/suggest?q=' + encodeURIComponent(q), {
      signal: currentController.signal,
      headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
      results = data.results || [];
      render(results, q);
    })
    .catch(err => {
      if (err.name === 'AbortError') return;
      console.error('Search error:', err);
    });
  }

  function render(items, q) {
    if (!items.length) {
      dropdown.innerHTML = '<div class="ac-empty">لا توجد نتائج لـ "' + escapeHtml(q) + '"</div>';
      dropdown.classList.add('show');
      input.setAttribute('aria-expanded', 'true');
      activeIndex = -1;
      return;
    }

    let html = '';
    items.forEach((item, i) => {
      const img = item.image
        ? '<img src="' + item.image + '" alt="" loading="lazy">'
        : '<div class="ac-noimg">📦</div>';
      const stockBadge = item.stock > 0 ? '' : '<span class="ac-out">نفذ</span>';
      html += '<a href="' + item.url + '" class="ac-item" data-index="' + i + '">' +
        '<div class="ac-item-img">' + img + '</div>' +
        '<div class="ac-item-body">' +
        '<div class="ac-item-name">' + highlight(item.name, q) + '</div>' +
        '<div class="ac-item-meta">' +
        '<span class="ac-item-price">' + item.price + ' ر.ي</span>' +
        stockBadge +
        '</div></div></a>';
    });

    html += '<a href="/shop?q=' + encodeURIComponent(q) + '" class="ac-all">🔍 عرض كل النتائج لـ "' + escapeHtml(q) + '"</a>';

    dropdown.innerHTML = html;
    dropdown.classList.add('show');
    input.setAttribute('aria-expanded', 'true');
    activeIndex = -1;

    dropdown.querySelectorAll('.ac-item').forEach(el => {
      el.addEventListener('mouseenter', () => {
        activeIndex = parseInt(el.dataset.index);
        updateActive();
      });
    });
  }

  function highlight(text, q) {
    if (!q) return escapeHtml(text);
    const safeText = escapeHtml(text);
    const safeQ = escapeHtml(q).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    return safeText.replace(new RegExp('(' + safeQ + ')', 'gi'), '<mark>$1</mark>');
  }

  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, c => ({
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    })[c]);
  }

  function updateActive() {
    dropdown.querySelectorAll('.ac-item').forEach((el, i) => {
      el.classList.toggle('active', i === activeIndex);
    });
  }

  function close() {
    dropdown.classList.remove('show');
    input.setAttribute('aria-expanded', 'false');
    activeIndex = -1;
  }

  input.addEventListener('input', function() {
    const q = this.value.trim();
    clearTimeout(debounceTimer);
    if (q.length < 2) { close(); return; }
    debounceTimer = setTimeout(() => search(q), 250);
  });

  input.addEventListener('keydown', function(e) {
    const items = dropdown.querySelectorAll('.ac-item');
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      if (!dropdown.classList.contains('show')) return;
      activeIndex = Math.min(activeIndex + 1, items.length - 1);
      updateActive();
      items[activeIndex]?.scrollIntoView({ block: 'nearest' });
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      activeIndex = Math.max(activeIndex - 1, -1);
      updateActive();
      items[activeIndex]?.scrollIntoView({ block: 'nearest' });
    } else if (e.key === 'Enter') {
      if (activeIndex >= 0 && items[activeIndex]) {
        e.preventDefault();
        window.location.href = items[activeIndex].href;
      }
    } else if (e.key === 'Escape') {
      close();
      input.blur();
    }
  });

  document.addEventListener('click', function(e) {
    if (!input.contains(e.target) && !dropdown.contains(e.target)) close();
  });

  input.addEventListener('blur', function() {
    setTimeout(() => {
      if (!dropdown.matches(':hover')) close();
    }, 200);
  });

})();
</script>

</body>
</html>

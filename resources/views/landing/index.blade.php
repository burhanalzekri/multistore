<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>MultiStore — منصة التجارة الإلكترونية اليمنية</title>
<meta name="description" content="MultiStore — منصة متاجر إلكترونية متعددة لأصحاب المتاجر اليمنيين. أنشئ متجرك في دقائق، أدر منتجاتك وطلباتك، واستقبل المدفوعات تلقائياً عبر SMS.">
<meta name="theme-color" content="#f59e0b">

<meta property="og:title" content="MultiStore — منصة التجارة الإلكترونية">
<meta property="og:description" content="أنشئ متجرك في دقائق. إدارة ذكية، دفع تلقائي، تجربة عالمية.">
<meta property="og:image" content="/icon-512.png">
<meta property="og:type" content="website">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Tajawal:wght@400;700;900&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: { cairo: ['Cairo','sans-serif'], tajawal: ['Tajawal','sans-serif'] },
      colors: {
        primary: '#F59E0B',
        secondary: '#F97316',
        amber: { 500: '#f59e0b', 600: '#d97706' },
        orange: { 500: '#f97316', 600: '#ea580c' }
      }
    }
  }
}
</script>

<style>
  /* ═══════════════════════════════════════════
     🎨 ROOT & RESET
     ═══════════════════════════════════════════ */
  *{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
  html{scroll-behavior:smooth}
  body{
    font-family:'Cairo',sans-serif;
    background:#ffffff;
    color:#0f172a;
    overflow-x:hidden;
    line-height:1.6;
    -webkit-font-smoothing:antialiased;
  }
  body.loading{overflow:hidden}
  ::selection{background:#f59e0b;color:#fff}

  /* Custom Scrollbar */
  ::-webkit-scrollbar{width:8px;height:8px}
  ::-webkit-scrollbar-track{background:#f8fafc}
  ::-webkit-scrollbar-thumb{background:linear-gradient(180deg,#f59e0b,#f97316);border-radius:4px}
  ::-webkit-scrollbar-thumb:hover{background:linear-gradient(180deg,#d97706,#ea580c)}

  /* ═══════════════════════════════════════════
     🎬 LOADING SCREEN
     ═══════════════════════════════════════════ */
  .loader-screen{
    position:fixed;inset:0;z-index:9999;
    background:linear-gradient(135deg,#fef3c7 0%,#ffedd5 50%,#fed7aa 100%);
    display:grid;place-items:center;
    transition:opacity .6s ease,visibility .6s ease;
  }
  .loader-screen.hidden{opacity:0;visibility:hidden}
  .loader-logo{
    width:80px;height:80px;border-radius:22px;
    background:linear-gradient(135deg,#f59e0b,#f97316);
    display:grid;place-items:center;color:#fff;
    font-size:36px;font-weight:900;font-family:'Cairo',sans-serif;
    box-shadow:0 20px 60px rgba(245,158,11,.4);
    animation:loaderPulse 1.5s ease-in-out infinite;
  }
  @keyframes loaderPulse{
    0%,100%{transform:scale(1);box-shadow:0 20px 60px rgba(245,158,11,.4)}
    50%{transform:scale(1.1);box-shadow:0 30px 80px rgba(245,158,11,.6)}
  }
  .loader-text{
    margin-top:20px;text-align:center;
    font-weight:900;font-size:18px;
    color:#78350f;
    animation:fadeInOut 1.8s ease-in-out infinite;
  }
  @keyframes fadeInOut{0%,100%{opacity:.5}50%{opacity:1}}
  .loader-dots{
    display:flex;gap:6px;margin-top:16px;justify-content:center;
  }
  .loader-dot{
    width:10px;height:10px;border-radius:50%;
    background:#f59e0b;
    animation:dotBounce 1.4s ease-in-out infinite;
  }
  .loader-dot:nth-child(2){animation-delay:.2s;background:#f97316}
  .loader-dot:nth-child(3){animation-delay:.4s;background:#ea580c}
  @keyframes dotBounce{
    0%,80%,100%{transform:scale(.6);opacity:.5}
    40%{transform:scale(1);opacity:1}
  }

  /* ═══════════════════════════════════════════
     📊 PROGRESS BAR
     ═══════════════════════════════════════════ */
  .progress-bar{
    position:fixed;top:0;left:0;right:0;height:3px;z-index:999;
    background:transparent;
  }
  .progress-fill{
    height:100%;
    background:linear-gradient(90deg,#f59e0b,#f97316,#ea580c,#f59e0b);
    background-size:200% 100%;
    width:0;
    transition:width .1s ease;
    animation:progressGlow 2s linear infinite;
  }
  @keyframes progressGlow{
    0%{background-position:0% 50%}
    100%{background-position:200% 50%}
  }

  /* ═══════════════════════════════════════════
     🎨 ANIMATED BACKGROUND
     ═══════════════════════════════════════════ */
  .bg-canvas{
    position:fixed;inset:0;z-index:-1;overflow:hidden;pointer-events:none;
  }
  .bg-blob{
    position:absolute;border-radius:50%;filter:blur(80px);opacity:.35;
    will-change:transform;
  }
  .blob-1{
    width:500px;height:500px;
    background:radial-gradient(circle,#fbbf24,transparent 70%);
    top:-100px;right:-100px;
    animation:blobFloat1 20s ease-in-out infinite;
  }
  .blob-2{
    width:600px;height:600px;
    background:radial-gradient(circle,#fb923c,transparent 70%);
    bottom:-200px;left:-200px;
    animation:blobFloat2 25s ease-in-out infinite;
  }
  .blob-3{
    width:400px;height:400px;
    background:radial-gradient(circle,#fcd34d,transparent 70%);
    top:40%;left:50%;
    animation:blobFloat3 30s ease-in-out infinite;
  }
  @keyframes blobFloat1{
    0%,100%{transform:translate(0,0) scale(1)}
    33%{transform:translate(-60px,80px) scale(1.1)}
    66%{transform:translate(60px,40px) scale(.9)}
  }
  @keyframes blobFloat2{
    0%,100%{transform:translate(0,0) scale(1)}
    50%{transform:translate(100px,-80px) scale(1.2)}
  }
  @keyframes blobFloat3{
    0%,100%{transform:translate(0,0) rotate(0deg)}
    50%{transform:translate(-80px,-60px) rotate(180deg)}
  }
  .grid-overlay{
    position:absolute;inset:0;
    background-image:
      linear-gradient(rgba(245,158,11,.04) 1px,transparent 1px),
      linear-gradient(90deg,rgba(245,158,11,.04) 1px,transparent 1px);
    background-size:60px 60px;
    mask-image:radial-gradient(ellipse at center,black 40%,transparent 80%);
    -webkit-mask-image:radial-gradient(ellipse at center,black 40%,transparent 80%);
  }
  .noise-overlay{
    position:absolute;inset:0;opacity:.015;
    background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  }

  /* ═══════════════════════════════════════════
     🎯 NAVBAR
     ═══════════════════════════════════════════ */
  .nav{
    position:fixed;top:0;left:0;right:0;z-index:100;
    padding:16px 0;
    transition:all .3s ease;
  }
  .nav.scrolled{
    padding:10px 0;
    background:rgba(255,255,255,.85);
    backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
    border-bottom:1px solid rgba(245,158,11,.1);
    box-shadow:0 4px 24px rgba(15,23,42,.06);
  }
  .nav-inner{
    max-width:1280px;margin:0 auto;padding:0 20px;
    display:flex;align-items:center;justify-content:space-between;
  }
  .nav-brand{
    display:flex;align-items:center;gap:10px;text-decoration:none;
  }
  .nav-logo{
    width:44px;height:44px;border-radius:14px;
    background:linear-gradient(135deg,#f59e0b,#f97316);
    display:grid;place-items:center;color:#fff;
    font-weight:900;font-size:22px;
    box-shadow:0 8px 24px rgba(245,158,11,.35);
    transition:all .3s ease;
  }
  .nav-brand:hover .nav-logo{transform:rotate(-8deg) scale(1.05)}
  .nav-title{font-size:20px;font-weight:900;color:#0f172a;line-height:1.1}
  .nav-sub{font-size:10px;color:#94a3b8;font-weight:700;margin-top:2px}
  .nav-links{
    display:flex;align-items:center;gap:4px;
    background:rgba(255,255,255,.6);
    border:1px solid rgba(245,158,11,.1);
    border-radius:99px;padding:4px;
  }
  .nav-link{
    padding:8px 16px;border-radius:99px;
    font-size:13px;font-weight:800;color:#475569;text-decoration:none;
    transition:all .2s ease;
  }
  .nav-link:hover{color:#f59e0b;background:rgba(245,158,11,.08)}
  .nav-cta{
    display:inline-flex;align-items:center;gap:6px;
    padding:10px 20px;border-radius:12px;
    background:linear-gradient(135deg,#f59e0b,#f97316);
    color:#fff;font-size:13px;font-weight:900;text-decoration:none;
    box-shadow:0 6px 20px rgba(245,158,11,.35);
    transition:all .25s ease;
  }
  .nav-cta:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(245,158,11,.5)}
  .nav-burger{
    width:44px;height:44px;border-radius:12px;
    background:rgba(255,255,255,.9);
    border:1px solid rgba(245,158,11,.15);
    display:none;place-items:center;cursor:pointer;
    color:#334155;transition:all .2s;
  }
  .nav-burger:hover{background:#fff;border-color:#f59e0b}

  .mobile-menu{
    position:fixed;inset:0;z-index:99;
    background:rgba(255,255,255,.98);
    backdrop-filter:blur(20px);
    padding:100px 24px 24px;
    transform:translateX(100%);
    transition:transform .35s cubic-bezier(.4,0,.2,1);
    overflow-y:auto;
  }
  .mobile-menu.open{transform:translateX(0)}
  .mobile-menu a{
    display:flex;align-items:center;justify-content:space-between;
    padding:16px 20px;margin-bottom:8px;
    border-radius:14px;
    font-size:15px;font-weight:800;color:#1e293b;text-decoration:none;
    transition:all .2s ease;
  }
  .mobile-menu a:hover{background:rgba(245,158,11,.08);color:#f59e0b;transform:translateX(-4px)}
  .mobile-menu .divider{height:1px;background:rgba(245,158,11,.15);margin:16px 0}
  .mobile-menu .mobile-cta{
    background:linear-gradient(135deg,#f59e0b,#f97316);
    color:#fff;justify-content:center;
    box-shadow:0 8px 24px rgba(245,158,11,.35);
  }
  .mobile-menu .mobile-cta:hover{color:#fff}

  /* ═══════════════════════════════════════════
     📦 CONTAINER & UTILITIES
     ═══════════════════════════════════════════ */
  .container-x{max-width:1280px;margin:0 auto;padding:0 20px}
  @media(min-width:768px){.container-x{padding:0 40px}}

  .reveal{opacity:0;transform:translateY(40px);transition:all .8s cubic-bezier(.2,.9,.3,1)}
  .reveal.show{opacity:1;transform:none}
  .reveal.delay-1{transition-delay:.1s}
  .reveal.delay-2{transition-delay:.2s}
  .reveal.delay-3{transition-delay:.3s}
  .reveal.delay-4{transition-delay:.4s}
  .reveal.delay-5{transition-delay:.5s}

  .gradient-text{
    background:linear-gradient(135deg,#f59e0b,#f97316,#ea580c,#f59e0b);
    background-size:200% 200%;
    -webkit-background-clip:text;background-clip:text;
    -webkit-text-fill-color:transparent;
    animation:gradientShift 6s ease infinite;
  }
  @keyframes gradientShift{
    0%,100%{background-position:0% 50%}
    50%{background-position:100% 50%}
  }

  /* ═══════════════════════════════════════════
     🎯 BUTTONS
     ═══════════════════════════════════════════ */
  .btn-primary{
    display:inline-flex;align-items:center;justify-content:center;gap:8px;
    padding:16px 28px;border-radius:14px;
    background:linear-gradient(135deg,#f59e0b,#f97316);
    color:#fff;font-size:15px;font-weight:900;text-decoration:none;
    box-shadow:0 12px 32px rgba(245,158,11,.35);
    position:relative;overflow:hidden;
    transition:all .3s ease;
    cursor:pointer;border:0;font-family:inherit;
  }
  .btn-primary::before{
    content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.4),transparent);
    transition:left .6s ease;
  }
  .btn-primary:hover::before{left:100%}
  .btn-primary:hover{transform:translateY(-3px);box-shadow:0 20px 48px rgba(245,158,11,.5)}

  .btn-outline{
    display:inline-flex;align-items:center;justify-content:center;gap:8px;
    padding:16px 28px;border-radius:14px;
    background:rgba(255,255,255,.9);
    backdrop-filter:blur(10px);
    color:#334155;font-size:15px;font-weight:900;text-decoration:none;
    border:2px solid rgba(245,158,11,.2);
    transition:all .3s ease;
    cursor:pointer;font-family:inherit;
  }
  .btn-outline:hover{
    transform:translateY(-3px);
    border-color:#f59e0b;
    color:#f59e0b;
    box-shadow:0 12px 32px rgba(245,158,11,.2);
  }

  /* ═══════════════════════════════════════════
     🎯 BADGE
     ═══════════════════════════════════════════ */
  .badge{
    display:inline-flex;align-items:center;gap:8px;
    padding:8px 16px;border-radius:99px;
    background:linear-gradient(135deg,rgba(245,158,11,.1),rgba(249,115,22,.08));
    border:1px solid rgba(245,158,11,.25);
    color:#c2410c;
    font-size:12.5px;font-weight:900;
  }
  .pulse-dot{
    width:8px;height:8px;border-radius:50%;
    background:#10b981;
    box-shadow:0 0 0 0 rgba(16,185,129,.6);
    animation:pulseDot 2s ease infinite;
  }
  @keyframes pulseDot{
    0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,.6)}
    70%{box-shadow:0 0 0 10px rgba(16,185,129,0)}
  }

  /* ═══════════════════════════════════════════
     🎴 CARDS
     ═══════════════════════════════════════════ */
  .card-glow{
    background:#fff;
    border:1px solid rgba(245,158,11,.1);
    border-radius:24px;
    padding:32px 28px;
    position:relative;overflow:hidden;
    transition:all .4s cubic-bezier(.2,.9,.3,1.1);
  }
  .card-glow::before{
    content:'';position:absolute;inset:0;
    background:radial-gradient(circle at var(--x,50%) var(--y,50%),rgba(245,158,11,.08),transparent 40%);
    opacity:0;transition:opacity .4s;
  }
  .card-glow:hover::before{opacity:1}
  .card-glow:hover{
    transform:translateY(-8px);
    border-color:rgba(245,158,11,.3);
    box-shadow:0 30px 60px -20px rgba(245,158,11,.25),0 0 0 1px rgba(245,158,11,.1);
  }
  .card-icon{
    width:64px;height:64px;border-radius:20px;
    display:grid;place-items:center;
    background:linear-gradient(135deg,#fff7ed,#ffedd5);
    color:#ea580c;
    margin-bottom:20px;
    transition:all .4s ease;
  }
  .card-glow:hover .card-icon{
    transform:rotate(-8deg) scale(1.1);
    background:linear-gradient(135deg,#f59e0b,#f97316);
    color:#fff;
    box-shadow:0 12px 32px rgba(245,158,11,.35);
  }

  /* ═══════════════════════════════════════════
     📱 PHONE MOCKUP
     ═══════════════════════════════════════════ */
  .phone-frame{
    width:280px;max-width:85vw;
    padding:12px;border-radius:44px;
    background:linear-gradient(145deg,#1e293b,#0f172a);
    box-shadow:
      0 40px 100px -20px rgba(15,23,42,.4),
      0 0 0 2px rgba(245,158,11,.15),
      inset 0 1px 0 rgba(255,255,255,.1);
    position:relative;
    animation:phoneFloat 6s ease-in-out infinite;
  }
  @keyframes phoneFloat{
    0%,100%{transform:translateY(0) rotate(-1deg)}
    50%{transform:translateY(-16px) rotate(1deg)}
  }
  .phone-notch{
    position:absolute;top:12px;left:50%;transform:translateX(-50%);
    width:100px;height:24px;background:#000;border-radius:0 0 20px 20px;
    z-index:2;
  }
  .phone-screen{
    background:#f8fafc;border-radius:34px;overflow:hidden;
    min-height:560px;position:relative;
  }
  .phone-status{
    padding:14px 24px 8px;display:flex;justify-content:space-between;
    font-size:11px;font-weight:800;color:#0f172a;
  }
  .phone-hd{
    background:linear-gradient(135deg,#f59e0b,#f97316);
    padding:20px;color:#fff;
  }
  .phone-kpi{
    background:rgba(255,255,255,.2);border-radius:12px;padding:12px;
    backdrop-filter:blur(10px);
  }

  /* ═══════════════════════════════════════════
     💫 FLOATING CARDS
     ═══════════════════════════════════════════ */
  .float-card{
    position:absolute;
    background:#fff;
    border-radius:16px;
    padding:12px 16px;
    box-shadow:0 20px 50px -12px rgba(15,23,42,.2);
    border:1px solid rgba(245,158,11,.15);
    display:flex;align-items:center;gap:10px;
    z-index:5;
  }
  .float-1{top:10%;right:-30px;animation:floatY 4s ease-in-out infinite}
  .float-2{bottom:15%;left:-40px;animation:floatY 4s ease-in-out infinite 1s}
  .float-3{top:45%;right:-50px;animation:floatY 4s ease-in-out infinite 2s}
  @keyframes floatY{
    0%,100%{transform:translateY(0)}
    50%{transform:translateY(-12px)}
  }

  /* ═══════════════════════════════════════════
     🎯 SECTIONS
     ═══════════════════════════════════════════ */
  .section{padding:100px 0;position:relative}
  .section-sm{padding:60px 0}
  .section-title{
    font-size:clamp(28px,5vw,52px);
    font-weight:900;line-height:1.15;
    letter-spacing:-1px;
  }
  .section-sub{
    font-size:clamp(15px,2vw,18px);
    color:#64748b;line-height:1.8;
    max-width:640px;margin:16px auto 0;
  }

  /* ═══════════════════════════════════════════
     📊 STATS
     ═══════════════════════════════════════════ */
  .stat-num{
    font-size:clamp(36px,6vw,56px);
    font-weight:900;
    background:linear-gradient(135deg,#f59e0b,#f97316);
    -webkit-background-clip:text;background-clip:text;
    -webkit-text-fill-color:transparent;
    line-height:1;
  }
  .stat-label{
    font-size:13px;color:#64748b;font-weight:800;margin-top:8px;
  }

  /* ═══════════════════════════════════════════
     🎪 RESPONSIVE
     ═══════════════════════════════════════════ */
  @media(max-width:900px){
    .nav-links{display:none}
    .nav-burger{display:grid}
    .section{padding:70px 0}
  }
  @media(max-width:640px){
    .float-1,.float-2,.float-3{display:none}
    .section{padding:60px 0}
  }

  /* Scroll indicator */
  .scroll-ind{
    position:absolute;bottom:30px;left:50%;transform:translateX(-50%);
    display:flex;flex-direction:column;align-items:center;gap:8px;
    color:#94a3b8;font-size:11px;font-weight:800;
  }
  .scroll-ind-mouse{
    width:26px;height:42px;border:2px solid rgba(245,158,11,.4);
    border-radius:14px;position:relative;
  }
  .scroll-ind-mouse::after{
    content:'';position:absolute;top:8px;left:50%;transform:translateX(-50%);
    width:4px;height:8px;background:#f59e0b;border-radius:2px;
    animation:scrollDot 1.6s ease-in-out infinite;
  }
  @keyframes scrollDot{
    0%{top:8px;opacity:1}
    100%{top:24px;opacity:0}
  }

  /* Rotating gradient ring */
  .gradient-ring{
    position:absolute;border-radius:50%;
    background:conic-gradient(from 0deg,#f59e0b,#f97316,#ea580c,#fbbf24,#f59e0b);
    animation:ringRotate 8s linear infinite;
    filter:blur(30px);opacity:.4;
  }
  @keyframes ringRotate{to{transform:rotate(360deg)}}
</style>
</head>
<body class="loading">

<!-- 🎬 Loading Screen -->
<div class="loader-screen" id="loader">
  <div style="text-align:center">
    <div class="loader-logo">M</div>
    <div class="loader-text">MultiStore</div>
    <div class="loader-dots">
      <span class="loader-dot"></span>
      <span class="loader-dot"></span>
      <span class="loader-dot"></span>
    </div>
  </div>
</div>

<!-- 📊 Progress Bar -->
<div class="progress-bar"><div class="progress-fill" id="progressFill"></div></div>

<!-- 🎨 Animated Background -->
<div class="bg-canvas">
  <div class="bg-blob blob-1"></div>
  <div class="bg-blob blob-2"></div>
  <div class="bg-blob blob-3"></div>
  <div class="grid-overlay"></div>
  <div class="noise-overlay"></div>
</div>


<!-- 🎯 Navbar -->
<nav class="nav" id="nav">
  <div class="nav-inner">
    <x-shop-logo
      :size="48"
      :link="'/'"
      :showText="true"
      name="MultiStore"
      subtitle="منصة التجارة الإلكترونية" />

    <div class="nav-links">
      <a href="#features" class="nav-link">المميزات</a>
      <a href="#how" class="nav-link">كيف يعمل؟</a>
      <a href="#dashboard" class="nav-link">لوحة التحكم</a>
      <a href="#marketing" class="nav-link">المتجر</a>
      <a href="#pricing" class="nav-link">الأسعار</a>
      <a href="#faq" class="nav-link">الأسئلة</a>
    </div>

    <div style="display:flex;gap:8px;align-items:center">
      <a href="/login" class="nav-link" style="display:none" id="navLogin">دخول</a>
      <a href="/register" class="nav-cta">
        ابدأ الآن
        <i data-lucide="arrow-left" style="width:15px;height:15px"></i>
      </a>
      <button class="nav-burger" onclick="toggleMobileMenu()" aria-label="القائمة">
        <i data-lucide="menu" style="width:22px;height:22px"></i>
      </button>
    </div>
  </div>
</nav>

<!-- 📱 Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
  <a href="#features" onclick="closeMobileMenu()">
    <span>✨ المميزات</span>
    <i data-lucide="chevron-left" style="width:18px;height:18px"></i>
  </a>
  <a href="#how" onclick="closeMobileMenu()">
    <span>🚀 كيف يعمل؟</span>
    <i data-lucide="chevron-left" style="width:18px;height:18px"></i>
  </a>
  <a href="#dashboard" onclick="closeMobileMenu()">
    <span>📊 لوحة التحكم</span>
    <i data-lucide="chevron-left" style="width:18px;height:18px"></i>
  </a>
  <a href="#marketing" onclick="closeMobileMenu()">
    <span>🛍️ المتجر</span>
    <i data-lucide="chevron-left" style="width:18px;height:18px"></i>
  </a>
  <a href="#pricing" onclick="closeMobileMenu()">
    <span>💰 الأسعار</span>
    <i data-lucide="chevron-left" style="width:18px;height:18px"></i>
  </a>
  <a href="#faq" onclick="closeMobileMenu()">
    <span>❓ الأسئلة الشائعة</span>
    <i data-lucide="chevron-left" style="width:18px;height:18px"></i>
  </a>
  <div class="divider"></div>
  <a href="/login" onclick="closeMobileMenu()">
    <span>🔐 تسجيل الدخول</span>
    <i data-lucide="arrow-left" style="width:18px;height:18px"></i>
  </a>
  <a href="/register" class="mobile-cta" onclick="closeMobileMenu()">
    <span>🎯 ابدأ متجرك مجاناً</span>
  </a>
</div>

<!-- 🎯 HERO SECTION -->
<section style="padding:140px 0 80px;position:relative;overflow:hidden" id="hero">
  <div class="container-x" style="position:relative;z-index:2">
    <div style="display:grid;grid-template-columns:1fr;gap:60px;align-items:center;text-align:center" class="hero-grid">
      <style>
        @media(min-width:1024px){
          .hero-grid{grid-template-columns:1.05fr .95fr!important;text-align:right!important}
        }
      </style>

      <!-- Left: Text -->
      <div class="reveal">
        <div class="badge" style="margin-bottom:24px">
          <span class="pulse-dot"></span>
          <span>منصة متاجر إلكترونية يمنية 🇾🇪</span>
        </div>

        <h1 style="font-size:clamp(36px,6vw,72px);font-weight:900;line-height:1.05;letter-spacing:-2px;color:#0f172a;margin-bottom:20px">
          أنشئ متجرك الإلكتروني
          <span class="gradient-text" style="display:block;margin-top:8px">
            وابدأ البيع بثقة
          </span>
        </h1>

        <p style="font-size:clamp(15px,2vw,19px);color:#475569;line-height:1.9;max-width:600px;margin-bottom:32px">
          منصة <strong style="color:#f59e0b;font-weight:900">MultiStore</strong> تمنحك الأدوات التي تحتاجها لإدارة المنتجات، الطلبات، العملاء، والمدفوعات — من لوحة تحكم واحدة، بواجهة بسيطة وسريعة ومتوافقة مع الجوال.
        </p>

        <div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;margin-bottom:32px" class="hero-actions">
          <style>
            @media(min-width:1024px){
              .hero-actions{justify-content:flex-start!important}
            }
          </style>
          <a href="/register" class="btn-primary">
            <i data-lucide="rocket" style="width:18px;height:18px"></i>
            ابدأ متجرك مجاناً
          </a>
          <a href="#dashboard" class="btn-outline">
            <i data-lucide="play-circle" style="width:18px;height:18px"></i>
            شاهد كيف يعمل
          </a>
        </div>

        <div style="display:flex;gap:20px;flex-wrap:wrap;justify-content:center;font-size:13px;color:#64748b;font-weight:800" class="hero-trust">
          <style>
            @media(min-width:1024px){
              .hero-trust{justify-content:flex-start!important}
            }
          </style>
          <div style="display:flex;align-items:center;gap:6px">
            <i data-lucide="check-circle" style="width:16px;height:16px;color:#10b981"></i>
            بدون بطاقة بنكية
          </div>
          <div style="display:flex;align-items:center;gap:6px">
            <i data-lucide="check-circle" style="width:16px;height:16px;color:#10b981"></i>
            إعداد في دقائق
          </div>
          <div style="display:flex;align-items:center;gap:6px">
            <i data-lucide="check-circle" style="width:16px;height:16px;color:#10b981"></i>
            دعم يمني
          </div>
        </div>
      </div>

      <!-- Right: Phone Mockup -->
      <div class="reveal delay-2" style="position:relative;display:flex;justify-content:center">
        <div style="position:relative">
          <!-- Rotating gradient ring behind -->
          <div class="gradient-ring" style="width:400px;height:400px;top:50%;left:50%;transform:translate(-50%,-50%)"></div>

          <!-- Floating Cards -->
          <div class="float-card float-1">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#10b981,#059669);display:grid;place-items:center">
              <i data-lucide="trending-up" style="width:20px;height:20px;color:#fff"></i>
            </div>
            <div>
              <div style="font-size:10px;color:#94a3b8;font-weight:800">نمو المبيعات</div>
              <div style="font-size:15px;font-weight:900;color:#059669">+ 18.4%</div>
            </div>
          </div>

          <div class="float-card float-2">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#f59e0b,#f97316);display:grid;place-items:center">
              <i data-lucide="shopping-bag" style="width:20px;height:20px;color:#fff"></i>
            </div>
            <div>
              <div style="font-size:10px;color:#94a3b8;font-weight:800">طلب جديد</div>
              <div style="font-size:13px;font-weight:900;color:#0f172a">ORD-1024</div>
            </div>
          </div>

          <div class="float-card float-3">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#8b5cf6,#7c3aed);display:grid;place-items:center">
              <i data-lucide="zap" style="width:20px;height:20px;color:#fff"></i>
            </div>
            <div>
              <div style="font-size:10px;color:#94a3b8;font-weight:800">دفع فوري</div>
              <div style="font-size:13px;font-weight:900;color:#7c3aed">SMS تلقائي</div>
            </div>
          </div>

          <!-- Phone -->
          <div class="phone-frame">
            <div class="phone-notch"></div>
            <div class="phone-screen">
              <div class="phone-status">
                <span>9:41</span>
                <span style="display:flex;gap:4px;align-items:center">
                  <i data-lucide="signal" style="width:12px;height:12px"></i>
                  <i data-lucide="wifi" style="width:12px;height:12px"></i>
                  <i data-lucide="battery-full" style="width:14px;height:14px"></i>
                </span>
              </div>

              <div class="phone-hd">
                <div style="font-size:11px;opacity:.9;font-weight:800">مرحباً بك 👋</div>
                <div style="font-size:18px;font-weight:900;margin-top:4px">متجر العسل</div>
                <div style="margin-top:16px;background:rgba(255,255,255,.2);border-radius:14px;padding:14px;backdrop-filter:blur(10px)">
                  <div style="font-size:10px;opacity:.9;font-weight:800">إجمالي المبيعات</div>
                  <div style="font-size:24px;font-weight:900;margin-top:4px">24,850 ر.س</div>
                  <div style="font-size:10px;opacity:.9;margin-top:4px">↑ 18.4% هذا الشهر</div>
                </div>
              </div>

              <div style="padding:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px">
                  <div style="background:#fff;border-radius:14px;padding:12px;border:1px solid #e2e8f0">
                    <div style="font-size:10px;color:#94a3b8;font-weight:800">الطلبات</div>
                    <div style="font-size:18px;font-weight:900;color:#0f172a;margin-top:2px">128</div>
                  </div>
                  <div style="background:#fff;border-radius:14px;padding:12px;border:1px solid #e2e8f0">
                    <div style="font-size:10px;color:#94a3b8;font-weight:800">المنتجات</div>
                    <div style="font-size:18px;font-weight:900;color:#0f172a;margin-top:2px">356</div>
                  </div>
                </div>

                <div style="background:#fff;border-radius:14px;padding:14px;border:1px solid #e2e8f0">
                  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                    <span style="font-size:12px;font-weight:900;color:#0f172a">آخر الطلبات</span>
                    <span style="font-size:10px;color:#f59e0b;font-weight:900">عرض الكل</span>
                  </div>

                  <div style="display:flex;flex-direction:column;gap:8px">
                    <div style="display:flex;justify-content:space-between;align-items:center">
                      <div style="display:flex;gap:8px;align-items:center">
                        <div style="width:30px;height:30px;border-radius:8px;background:#fef3c7"></div>
                        <div>
                          <div style="font-size:10px;font-weight:800;color:#0f172a">#1024</div>
                          <div style="font-size:9px;color:#94a3b8">منذ 5 دقائق</div>
                        </div>
                      </div>
                      <span style="font-size:9px;padding:3px 8px;border-radius:99px;background:#dcfce7;color:#166534;font-weight:800">مكتمل</span>
                    </div>

                    <div style="display:flex;justify-content:space-between;align-items:center">
                      <div style="display:flex;gap:8px;align-items:center">
                        <div style="width:30px;height:30px;border-radius:8px;background:#dbeafe"></div>
                        <div>
                          <div style="font-size:10px;font-weight:800;color:#0f172a">#1023</div>
                          <div style="font-size:9px;color:#94a3b8">منذ 12 دقيقة</div>
                        </div>
                      </div>
                      <span style="font-size:9px;padding:3px 8px;border-radius:99px;background:#fef3c7;color:#92400e;font-weight:800">جديد</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scroll Indicator -->
  <div class="scroll-ind" style="display:none">
    <span>اكتشف المزيد</span>
    <div class="scroll-ind-mouse"></div>
  </div>
</section>


<!-- ✨ FEATURES -->
<section id="features" class="section">
  <div class="container-x">
    <div style="text-align:center;margin-bottom:60px" class="reveal">
      <div class="badge" style="margin-bottom:20px">
        <i data-lucide="sparkles" style="width:14px;height:14px"></i>
        كل ما تحتاجه في مكان واحد
      </div>
      <h2 class="section-title">
        أدوات <span class="gradient-text">احترافية</span>
        <br>لإدارة تجارتك
      </h2>
      <p class="section-sub">
        صممنا MultiStore لتكون عملية، واضحة وسريعة — سواء كنت تدير متجراً واحداً أو عشرات المتاجر.
      </p>
    </div>

    <div style="display:grid;grid-template-columns:1fr;gap:20px" class="features-grid">
      <style>
        @media(min-width:640px){.features-grid{grid-template-columns:repeat(2,1fr)!important}}
        @media(min-width:1024px){.features-grid{grid-template-columns:repeat(3,1fr)!important}}
      </style>

      <div class="card-glow reveal" onmousemove="trackCursor(event,this)">
        <div class="card-icon">
          <i data-lucide="store" style="width:30px;height:30px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">متاجر متعددة</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          أنشئ وأدر أكثر من متجر من منصة واحدة — كل متجر ببياناته ومنتجاته وعملائه.
        </p>
      </div>

      <div class="card-glow reveal delay-1" onmousemove="trackCursor(event,this)">
        <div class="card-icon">
          <i data-lucide="package" style="width:30px;height:30px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">إدارة المنتجات</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          أضف منتجاتك بصور احترافية، متغيرات، مخزون، وتصنيفات — بضغطة زر.
        </p>
      </div>

      <div class="card-glow reveal delay-2" onmousemove="trackCursor(event,this)">
        <div class="card-icon">
          <i data-lucide="shopping-bag" style="width:30px;height:30px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">إدارة الطلبات</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          تابع الطلبات، حالاتها، وتتبع الشحنات من لوحة تحكم واحدة شاملة.
        </p>
      </div>

      <div class="card-glow reveal delay-1" onmousemove="trackCursor(event,this)">
        <div class="card-icon">
          <i data-lucide="message-square" style="width:30px;height:30px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">دفع SMS تلقائي</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          النظام يقرأ رسائل التحويل البنكي ويؤكد الطلب تلقائياً في ثوانٍ.
        </p>
      </div>

      <div class="card-glow reveal delay-2" onmousemove="trackCursor(event,this)">
        <div class="card-icon">
          <i data-lucide="bar-chart-3" style="width:30px;height:30px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">تقارير وتحليلات</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          راقب أداء متجرك — مبيعات، أرباح، عملاء، وأفضل المنتجات لحظياً.
        </p>
      </div>

      <div class="card-glow reveal delay-3" onmousemove="trackCursor(event,this)">
        <div class="card-icon">
          <i data-lucide="shield-check" style="width:30px;height:30px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">أمان وصلاحيات</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          تحكم كامل بالصلاحيات، الأدوار، والوصول لكل قسم في النظام.
        </p>
      </div>

    </div>
  </div>
</section>

<!-- 🚀 HOW IT WORKS -->
<section id="how" class="section" style="background:linear-gradient(180deg,#fffbeb 0%,#ffffff 100%)">
  <div class="container-x">
    <div style="text-align:center;margin-bottom:60px" class="reveal">
      <div class="badge" style="margin-bottom:20px">
        <i data-lucide="rocket" style="width:14px;height:14px"></i>
        ابدأ في 3 خطوات
      </div>
      <h2 class="section-title">
        من الفكرة إلى <span class="gradient-text">متجر حقيقي</span>
        <br>بخطوات بسيطة
      </h2>
      <p class="section-sub">
        لا تحتاج خبرة تقنية. سجّل، أضف منتجاتك، وابدأ البيع.
      </p>
    </div>

    <div style="display:grid;grid-template-columns:1fr;gap:24px;position:relative" class="steps-grid">
      <style>
        @media(min-width:768px){.steps-grid{grid-template-columns:repeat(3,1fr)!important}}
      </style>

      <div class="reveal" style="background:#fff;border-radius:24px;padding:36px 28px;text-align:center;border:1px solid rgba(245,158,11,.15);position:relative;box-shadow:0 4px 20px rgba(15,23,42,.04);transition:all .3s">
        <div style="position:absolute;top:-20px;left:50%;transform:translateX(-50%);width:56px;height:56px;border-radius:18px;background:linear-gradient(135deg,#f59e0b,#f97316);display:grid;place-items:center;color:#fff;font-size:22px;font-weight:900;box-shadow:0 12px 32px rgba(245,158,11,.4)">1</div>
        <div style="width:70px;height:70px;margin:20px auto 16px;border-radius:20px;background:linear-gradient(135deg,#fff7ed,#ffedd5);display:grid;place-items:center;color:#ea580c">
          <i data-lucide="user-plus" style="width:32px;height:32px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">أنشئ حسابك</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          سجّل بياناتك في أقل من دقيقة، وابدأ إعداد متجرك مباشرة.
        </p>
      </div>

      <div class="reveal delay-1" style="background:#fff;border-radius:24px;padding:36px 28px;text-align:center;border:1px solid rgba(245,158,11,.15);position:relative;box-shadow:0 4px 20px rgba(15,23,42,.04);transition:all .3s">
        <div style="position:absolute;top:-20px;left:50%;transform:translateX(-50%);width:56px;height:56px;border-radius:18px;background:linear-gradient(135deg,#f59e0b,#f97316);display:grid;place-items:center;color:#fff;font-size:22px;font-weight:900;box-shadow:0 12px 32px rgba(245,158,11,.4)">2</div>
        <div style="width:70px;height:70px;margin:20px auto 16px;border-radius:20px;background:linear-gradient(135deg,#fff7ed,#ffedd5);display:grid;place-items:center;color:#ea580c">
          <i data-lucide="package-plus" style="width:32px;height:32px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">أضف منتجاتك</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          ارفع صور المنتجات، حدد الأسعار، المخزون، والتصنيفات.
        </p>
      </div>

      <div class="reveal delay-2" style="background:#fff;border-radius:24px;padding:36px 28px;text-align:center;border:1px solid rgba(245,158,11,.15);position:relative;box-shadow:0 4px 20px rgba(15,23,42,.04);transition:all .3s">
        <div style="position:absolute;top:-20px;left:50%;transform:translateX(-50%);width:56px;height:56px;border-radius:18px;background:linear-gradient(135deg,#f59e0b,#f97316);display:grid;place-items:center;color:#fff;font-size:22px;font-weight:900;box-shadow:0 12px 32px rgba(245,158,11,.4)">3</div>
        <div style="width:70px;height:70px;margin:20px auto 16px;border-radius:20px;background:linear-gradient(135deg,#fff7ed,#ffedd5);display:grid;place-items:center;color:#ea580c">
          <i data-lucide="rocket" style="width:32px;height:32px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">ابدأ البيع</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          شارك رابط متجرك، استقبل الطلبات، وتابع كل شيء من لوحة واحدة.
        </p>
      </div>

    </div>
  </div>
</section>


<!-- 📊 DASHBOARD SHOWCASE -->
<section id="dashboard" class="section" style="background:#0f172a;color:#fff;position:relative;overflow:hidden">
  <div style="position:absolute;top:-100px;right:-100px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(245,158,11,.3),transparent 70%);filter:blur(60px)"></div>
  <div style="position:absolute;bottom:-100px;left:-100px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(249,115,22,.25),transparent 70%);filter:blur(60px)"></div>

  <div class="container-x" style="position:relative;z-index:2">
    <div style="text-align:center;margin-bottom:60px" class="reveal">
      <div class="badge" style="background:rgba(245,158,11,.15);border-color:rgba(245,158,11,.3);color:#fbbf24;margin-bottom:20px">
        <i data-lucide="layout-dashboard" style="width:14px;height:14px"></i>
        لوحة تحكم احترافية
      </div>
      <h2 class="section-title" style="color:#fff">
        كل أرقام متجرك
        <span class="gradient-text" style="display:block;margin-top:8px">أمامك في مكان واحد</span>
      </h2>
      <p class="section-sub" style="color:#94a3b8">
        لوحة مصممة لتعرض لك أهم المعلومات بدون تعقيد — إحصائيات، رسوم بيانية، وتقارير لحظية.
      </p>
    </div>

    <div class="reveal" style="max-width:1000px;margin:0 auto;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:24px;padding:20px;backdrop-filter:blur(20px);box-shadow:0 40px 100px -20px rgba(0,0,0,.5)">
      <div style="display:flex;gap:8px;padding-bottom:14px;border-bottom:1px solid rgba(255,255,255,.08);margin-bottom:20px">
        <span style="width:12px;height:12px;border-radius:50%;background:#ef4444"></span>
        <span style="width:12px;height:12px;border-radius:50%;background:#f59e0b"></span>
        <span style="width:12px;height:12px;border-radius:50%;background:#10b981"></span>
        <div style="flex:1;background:rgba(0,0,0,.3);border-radius:8px;padding:6px 14px;text-align:center;font-size:11px;color:#94a3b8;font-family:monospace">dashboard.multistore.ye</div>
      </div>

      <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px" class="dash-kpis">
        <style>
          @media(min-width:640px){.dash-kpis{grid-template-columns:repeat(4,1fr)!important}}
        </style>
        <div style="background:linear-gradient(135deg,rgba(245,158,11,.15),rgba(245,158,11,.05));border:1px solid rgba(245,158,11,.2);border-radius:16px;padding:16px">
          <div style="font-size:11px;color:#fbbf24;font-weight:800">إجمالي المبيعات</div>
          <div style="font-size:22px;font-weight:900;color:#fff;margin-top:4px">48,250</div>
          <div style="font-size:11px;color:#10b981;font-weight:800;margin-top:4px">↑ 18.4%</div>
        </div>
        <div style="background:rgba(59,130,246,.15);border:1px solid rgba(59,130,246,.2);border-radius:16px;padding:16px">
          <div style="font-size:11px;color:#93c5fd;font-weight:800">الطلبات</div>
          <div style="font-size:22px;font-weight:900;color:#fff;margin-top:4px">428</div>
          <div style="font-size:11px;color:#10b981;font-weight:800;margin-top:4px">↑ 12.1%</div>
        </div>
        <div style="background:rgba(139,92,246,.15);border:1px solid rgba(139,92,246,.2);border-radius:16px;padding:16px">
          <div style="font-size:11px;color:#c4b5fd;font-weight:800">العملاء</div>
          <div style="font-size:22px;font-weight:900;color:#fff;margin-top:4px">1,284</div>
          <div style="font-size:11px;color:#10b981;font-weight:800;margin-top:4px">↑ 8.7%</div>
        </div>
        <div style="background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.2);border-radius:16px;padding:16px">
          <div style="font-size:11px;color:#6ee7b7;font-weight:800">المنتجات</div>
          <div style="font-size:22px;font-weight:900;color:#fff;margin-top:4px">356</div>
          <div style="font-size:11px;color:#fbbf24;font-weight:800;margin-top:4px">12 منخفض</div>
        </div>
      </div>

      <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:16px;padding:20px;margin-top:16px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
          <span style="font-size:14px;font-weight:900;color:#fff">📈 المبيعات — آخر 7 أيام</span>
          <span style="font-size:11px;color:#94a3b8">هذا الأسبوع</span>
        </div>
        <div style="display:flex;align-items:flex-end;gap:8px;height:120px">
          <div style="flex:1;background:linear-gradient(to top,#f59e0b,#fbbf24);height:45%;border-radius:6px 6px 0 0;animation:barGrow 1.5s ease"></div>
          <div style="flex:1;background:linear-gradient(to top,#f59e0b,#fbbf24);height:62%;border-radius:6px 6px 0 0;animation:barGrow 1.5s ease .1s both"></div>
          <div style="flex:1;background:linear-gradient(to top,#f59e0b,#fbbf24);height:50%;border-radius:6px 6px 0 0;animation:barGrow 1.5s ease .2s both"></div>
          <div style="flex:1;background:linear-gradient(to top,#f59e0b,#fbbf24);height:78%;border-radius:6px 6px 0 0;animation:barGrow 1.5s ease .3s both"></div>
          <div style="flex:1;background:linear-gradient(to top,#f59e0b,#fbbf24);height:64%;border-radius:6px 6px 0 0;animation:barGrow 1.5s ease .4s both"></div>
          <div style="flex:1;background:linear-gradient(to top,#f59e0b,#fbbf24);height:88%;border-radius:6px 6px 0 0;animation:barGrow 1.5s ease .5s both"></div>
          <div style="flex:1;background:linear-gradient(to top,#f59e0b,#fbbf24);height:72%;border-radius:6px 6px 0 0;animation:barGrow 1.5s ease .6s both"></div>
        </div>
      </div>
      <style>
        @keyframes barGrow{from{height:0!important}}
      </style>
    </div>

    <div style="text-align:center;margin-top:40px" class="reveal">
      <a href="/demo" class="btn-primary">
        <i data-lucide="play-circle" style="width:18px;height:18px"></i>
        جرّب المتجر التجريبي الآن
      </a>
    </div>
  </div>
</section>


<!-- 🎨 MARKETING IMAGES SHOWCASE -->
<section id="marketing" class="section" style="background:#fafafa;position:relative;overflow:hidden">
  <div class="container-x">
    <div style="text-align:center;margin-bottom:60px" class="reveal">
      <div class="badge" style="margin-bottom:20px">
        <i data-lucide="image" style="width:14px;height:14px"></i>
        تجربة بصرية حقيقية
      </div>
      <h2 class="section-title">
        شاهد <span class="gradient-text">قوة المنصة</span>
        <br>في صور حقيقية
      </h2>
      <p class="section-sub">
        عرض ثلاثي الأبعاد، لوحة تحكم احترافية، ومتجر إلكتروني جاهز — كل ذلك في MultiStore.
      </p>
    </div>

    <div style="display:grid;grid-template-columns:1fr;gap:20px" class="marketing-grid">
      <style>
        @media(min-width:768px){.marketing-grid{grid-template-columns:repeat(3,1fr)!important}}
      </style>

      <!-- Image 1: 3D Store -->
      <div class="reveal" style="position:relative;border-radius:24px;overflow:hidden;background:#0f172a;box-shadow:0 20px 60px -20px rgba(15,23,42,.3);transition:all .5s cubic-bezier(.2,.9,.3,1.1);min-height:420px" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 30px 80px -20px rgba(15,23,42,.4)'" onmouseout="this.style.transform='';this.style.boxShadow='0 20px 60px -20px rgba(15,23,42,.3)'">
        <img src="{{ asset('images/marketing/store-3d.png') }}" alt="عرض ثلاثي الأبعاد" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;transition:transform .6s" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform=''" loading="lazy" onerror="this.style.display='none'">
        <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(15,23,42,.95) 0%,rgba(15,23,42,.4) 40%,transparent 70%)"></div>
        <div style="position:absolute;bottom:0;left:0;right:0;padding:28px;color:#fff">
          <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:99px;background:linear-gradient(135deg,#f59e0b,#f97316);font-size:11px;font-weight:900;box-shadow:0 8px 20px rgba(245,158,11,.5)">
            <i data-lucide="box" style="width:12px;height:12px"></i>
            3D EXPERIENCE
          </span>
          <h3 style="font-size:22px;font-weight:900;margin-top:14px;letter-spacing:-.5px">عرض المنتجات باحترافية</h3>
          <p style="font-size:13px;color:rgba(255,255,255,.75);margin-top:8px;line-height:1.7">تجربة بصرية حديثة تُبرز منتجاتك بأبهى صورة.</p>
        </div>
      </div>

      <!-- Image 2: Dashboard -->
      <div class="reveal delay-1" style="position:relative;border-radius:24px;overflow:hidden;background:#0f172a;box-shadow:0 20px 60px -20px rgba(15,23,42,.3);transition:all .5s cubic-bezier(.2,.9,.3,1.1);min-height:420px" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 30px 80px -20px rgba(15,23,42,.4)'" onmouseout="this.style.transform='';this.style.boxShadow='0 20px 60px -20px rgba(15,23,42,.3)'">
        <img src="{{ asset('images/marketing/dashboard.png') }}" alt="لوحة تحكم MultiStore" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;transition:transform .6s" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform=''" loading="lazy" onerror="this.style.display='none'">
        <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(15,23,42,.95) 0%,rgba(15,23,42,.4) 40%,transparent 70%)"></div>
        <div style="position:absolute;bottom:0;left:0;right:0;padding:28px;color:#fff">
          <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:99px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);font-size:11px;font-weight:900;box-shadow:0 8px 20px rgba(59,130,246,.5)">
            <i data-lucide="layout-dashboard" style="width:12px;height:12px"></i>
            SMART DASHBOARD
          </span>
          <h3 style="font-size:22px;font-weight:900;margin-top:14px;letter-spacing:-.5px">إدارة متجرك من مكان واحد</h3>
          <p style="font-size:13px;color:rgba(255,255,255,.75);margin-top:8px;line-height:1.7">الطلبات، المنتجات، العملاء، والإحصائيات — بلوحة واحدة.</p>
        </div>
      </div>

      <!-- Image 3: Store Marketing -->
      <div class="reveal delay-2" style="position:relative;border-radius:24px;overflow:hidden;background:#0f172a;box-shadow:0 20px 60px -20px rgba(15,23,42,.3);transition:all .5s cubic-bezier(.2,.9,.3,1.1);min-height:420px" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 30px 80px -20px rgba(15,23,42,.4)'" onmouseout="this.style.transform='';this.style.boxShadow='0 20px 60px -20px rgba(15,23,42,.3)'">
        <img src="{{ asset('images/marketing/store-marketing.png') }}" alt="واجهة المتجر الإلكتروني" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;transition:transform .6s" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform=''" loading="lazy" onerror="this.style.display='none'">
        <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(15,23,42,.95) 0%,rgba(15,23,42,.4) 40%,transparent 70%)"></div>
        <div style="position:absolute;bottom:0;left:0;right:0;padding:28px;color:#fff">
          <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:99px;background:linear-gradient(135deg,#10b981,#059669);font-size:11px;font-weight:900;box-shadow:0 8px 20px rgba(16,185,129,.5)">
            <i data-lucide="shopping-bag" style="width:12px;height:12px"></i>
            ONLINE STORE
          </span>
          <h3 style="font-size:22px;font-weight:900;margin-top:14px;letter-spacing:-.5px">متجر جاهز للعرض والبيع</h3>
          <p style="font-size:13px;color:rgba(255,255,255,.75);margin-top:8px;line-height:1.7">تصميم متجاوب وسريع — على الجوال والحاسوب.</p>
        </div>
      </div>

    </div>

    <div style="text-align:center;margin-top:40px" class="reveal">
      <a href="/demo" class="btn-outline">
        <i data-lucide="eye" style="width:18px;height:18px"></i>
        شاهد المتجر التجريبي
      </a>
    </div>
  </div>
</section>

<!-- 💰 PRICING -->
<section id="pricing" class="section">
  <div class="container-x">
    <div style="text-align:center;margin-bottom:60px" class="reveal">
      <div class="badge" style="margin-bottom:20px">
        <i data-lucide="badge-dollar-sign" style="width:14px;height:14px"></i>
        خطط مرنة
      </div>
      <h2 class="section-title">
        اختر الخطة
        <span class="gradient-text">المناسبة لك</span>
      </h2>
      <p class="section-sub">
        ابدأ مجاناً وترقّى عندما ينمو متجرك — بدون التزامات.
      </p>
    </div>

    <div style="display:grid;grid-template-columns:1fr;gap:20px;max-width:1000px;margin:0 auto" class="pricing-grid">
      <style>
        @media(min-width:768px){.pricing-grid{grid-template-columns:repeat(3,1fr)!important}}
      </style>

      <!-- مجاني -->
      <div class="reveal" style="background:#fff;border-radius:24px;padding:36px 28px;border:2px solid #f1f5f9;transition:all .3s">
        <div style="font-size:13px;font-weight:900;color:#64748b;margin-bottom:8px">مجاني</div>
        <div style="font-size:42px;font-weight:900;color:#0f172a;line-height:1">0<span style="font-size:16px;color:#94a3b8">/شهر</span></div>
        <p style="color:#64748b;font-size:13.5px;margin-top:12px;line-height:1.7">مناسب لتجربة المنصة والبدء بمشروع صغير.</p>
        <a href="/register" class="btn-outline" style="width:100%;margin-top:24px">ابدأ مجاناً</a>
        <div style="border-top:1px solid #f1f5f9;padding-top:20px;margin-top:24px">
          <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#475569;margin-bottom:10px"><i data-lucide="check" style="width:16px;height:16px;color:#10b981"></i> متجر واحد</div>
          <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#475569;margin-bottom:10px"><i data-lucide="check" style="width:16px;height:16px;color:#10b981"></i> منتجات غير محدودة</div>
          <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#475569"><i data-lucide="check" style="width:16px;height:16px;color:#10b981"></i> دعم فني</div>
        </div>
      </div>

      <!-- أساسي -->
      <div class="reveal delay-1" style="background:linear-gradient(135deg,#fff7ed,#ffedd5);border-radius:24px;padding:36px 28px;border:2px solid #f59e0b;position:relative;box-shadow:0 20px 60px -20px rgba(245,158,11,.4);transform:scale(1.02)">
        <div style="position:absolute;top:-14px;right:24px;background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;font-size:11px;font-weight:900;padding:6px 14px;border-radius:99px;box-shadow:0 8px 20px rgba(245,158,11,.4)">⭐ الأكثر طلباً</div>
        <div style="font-size:13px;font-weight:900;color:#c2410c;margin-bottom:8px">أساسي</div>
        <div style="font-size:42px;font-weight:900;color:#0f172a;line-height:1">—<span style="font-size:16px;color:#94a3b8">/شهر</span></div>
        <p style="color:#78350f;font-size:13.5px;margin-top:12px;line-height:1.7">للمتاجر الناشئة التي تحتاج أدوات احترافية.</p>
        <a href="/register" class="btn-primary" style="width:100%;margin-top:24px">ابدأ الآن</a>
        <div style="border-top:1px solid rgba(245,158,11,.3);padding-top:20px;margin-top:24px">
          <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#78350f;margin-bottom:10px"><i data-lucide="check" style="width:16px;height:16px;color:#ea580c"></i> كل مزايا المجاني</div>
          <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#78350f;margin-bottom:10px"><i data-lucide="check" style="width:16px;height:16px;color:#ea580c"></i> متاجر متعددة</div>
          <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#78350f;margin-bottom:10px"><i data-lucide="check" style="width:16px;height:16px;color:#ea580c"></i> تقارير وتحليلات</div>
          <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#78350f"><i data-lucide="check" style="width:16px;height:16px;color:#ea580c"></i> دفع SMS تلقائي</div>
        </div>
      </div>

      <!-- احترافي -->
      <div class="reveal delay-2" style="background:#fff;border-radius:24px;padding:36px 28px;border:2px solid #f1f5f9;transition:all .3s">
        <div style="font-size:13px;font-weight:900;color:#64748b;margin-bottom:8px">احترافي</div>
        <div style="font-size:42px;font-weight:900;color:#0f172a;line-height:1">—<span style="font-size:16px;color:#94a3b8">/شهر</span></div>
        <p style="color:#64748b;font-size:13.5px;margin-top:12px;line-height:1.7">للشركات والمتاجر الكبيرة بإمكانيات متقدمة.</p>
        <a href="/register" class="btn-outline" style="width:100%;margin-top:24px">تواصل معنا</a>
        <div style="border-top:1px solid #f1f5f9;padding-top:20px;margin-top:24px">
          <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#475569;margin-bottom:10px"><i data-lucide="check" style="width:16px;height:16px;color:#10b981"></i> كل مزايا الأساسي</div>
          <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#475569;margin-bottom:10px"><i data-lucide="check" style="width:16px;height:16px;color:#10b981"></i> نطاق مخصص</div>
          <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#475569;margin-bottom:10px"><i data-lucide="check" style="width:16px;height:16px;color:#10b981"></i> دعم أولوية 24/7</div>
          <div style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#475569"><i data-lucide="check" style="width:16px;height:16px;color:#10b981"></i> تكامل مخصص</div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ❓ FAQ -->
<section id="faq" class="section" style="background:#fafafa">
  <div class="container-x" style="max-width:800px">
    <div style="text-align:center;margin-bottom:50px" class="reveal">
      <div class="badge" style="margin-bottom:20px">
        <i data-lucide="help-circle" style="width:14px;height:14px"></i>
        الأسئلة الشائعة
      </div>
      <h2 class="section-title">لديك <span class="gradient-text">سؤال؟</span></h2>
    </div>

    <div id="faqList">
      <div class="faq-item reveal" style="background:#fff;border-radius:18px;margin-bottom:12px;border:1px solid rgba(245,158,11,.1);overflow:hidden;transition:all .3s">
        <button onclick="toggleFaq(this)" style="width:100%;padding:22px 24px;display:flex;justify-content:space-between;align-items:center;background:none;border:0;cursor:pointer;font-family:inherit;text-align:right">
          <span style="font-size:15.5px;font-weight:900;color:#0f172a">هل أحتاج إلى خبرة تقنية؟</span>
          <i data-lucide="chevron-down" style="width:20px;height:20px;color:#f59e0b;transition:transform .3s"></i>
        </button>
        <div class="faq-answer" style="max-height:0;overflow:hidden;transition:max-height .4s ease,padding .4s ease;padding:0 24px">
          <p style="color:#64748b;font-size:14.5px;line-height:1.9;padding-bottom:22px;border-top:1px solid #f1f5f9;padding-top:18px">
            لا. الواجهة مصممة لتكون بسيطة وسهلة — يمكنك إدارة المنتجات والطلبات والعملاء بضغطة زر، بدون أي خبرة برمجية.
          </p>
        </div>
      </div>

      <div class="faq-item reveal delay-1" style="background:#fff;border-radius:18px;margin-bottom:12px;border:1px solid rgba(245,158,11,.1);overflow:hidden;transition:all .3s">
        <button onclick="toggleFaq(this)" style="width:100%;padding:22px 24px;display:flex;justify-content:space-between;align-items:center;background:none;border:0;cursor:pointer;font-family:inherit;text-align:right">
          <span style="font-size:15.5px;font-weight:900;color:#0f172a">هل يمكنني إدارة أكثر من متجر؟</span>
          <i data-lucide="chevron-down" style="width:20px;height:20px;color:#f59e0b;transition:transform .3s"></i>
        </button>
        <div class="faq-answer" style="max-height:0;overflow:hidden;transition:max-height .4s ease,padding .4s ease;padding:0 24px">
          <p style="color:#64748b;font-size:14.5px;line-height:1.9;padding-bottom:22px;border-top:1px solid #f1f5f9;padding-top:18px">
            نعم، النظام مُصمم لدعم بيئة متعددة المتاجر — كل متجر ببياناته ومنتجاته وعملائه، مع إدارة مركزية من حساب واحد.
          </p>
        </div>
      </div>

      <div class="faq-item reveal delay-2" style="background:#fff;border-radius:18px;margin-bottom:12px;border:1px solid rgba(245,158,11,.1);overflow:hidden;transition:all .3s">
        <button onclick="toggleFaq(this)" style="width:100%;padding:22px 24px;display:flex;justify-content:space-between;align-items:center;background:none;border:0;cursor:pointer;font-family:inherit;text-align:right">
          <span style="font-size:15.5px;font-weight:900;color:#0f172a">هل يعمل على الجوال؟</span>
          <i data-lucide="chevron-down" style="width:20px;height:20px;color:#f59e0b;transition:transform .3s"></i>
        </button>
        <div class="faq-answer" style="max-height:0;overflow:hidden;transition:max-height .4s ease,padding .4s ease;padding:0 24px">
          <p style="color:#64748b;font-size:14.5px;line-height:1.9;padding-bottom:22px;border-top:1px solid #f1f5f9;padding-top:18px">
            نعم، الواجهة متجاوبة بالكامل — تعمل بسلاسة على الجوال والتابلت والحاسوب، ويمكنك إدارة متجرك من أي مكان.
          </p>
        </div>
      </div>

      <div class="faq-item reveal delay-3" style="background:#fff;border-radius:18px;margin-bottom:12px;border:1px solid rgba(245,158,11,.1);overflow:hidden;transition:all .3s">
        <button onclick="toggleFaq(this)" style="width:100%;padding:22px 24px;display:flex;justify-content:space-between;align-items:center;background:none;border:0;cursor:pointer;font-family:inherit;text-align:right">
          <span style="font-size:15.5px;font-weight:900;color:#0f172a">كيف يعمل الدفع عبر SMS؟</span>
          <i data-lucide="chevron-down" style="width:20px;height:20px;color:#f59e0b;transition:transform .3s"></i>
        </button>
        <div class="faq-answer" style="max-height:0;overflow:hidden;transition:max-height .4s ease,padding .4s ease;padding:0 24px">
          <p style="color:#64748b;font-size:14.5px;line-height:1.9;padding-bottom:22px;border-top:1px solid #f1f5f9;padding-top:18px">
            النظام يقرأ رسائل التحويل البنكي (بنك الكريمي، جوالي، وغيرها) ويطابقها مع الطلبات تلقائياً — فيتم تأكيد الطلب وإشعار العميل خلال ثوانٍ.
          </p>
        </div>
      </div>

      <div class="faq-item reveal delay-4" style="background:#fff;border-radius:18px;margin-bottom:12px;border:1px solid rgba(245,158,11,.1);overflow:hidden;transition:all .3s">
        <button onclick="toggleFaq(this)" style="width:100%;padding:22px 24px;display:flex;justify-content:space-between;align-items:center;background:none;border:0;cursor:pointer;font-family:inherit;text-align:right">
          <span style="font-size:15.5px;font-weight:900;color:#0f172a">هل يمكنني تجربة النظام قبل الدفع؟</span>
          <i data-lucide="chevron-down" style="width:20px;height:20px;color:#f59e0b;transition:transform .3s"></i>
        </button>
        <div class="faq-answer" style="max-height:0;overflow:hidden;transition:max-height .4s ease,padding .4s ease;padding:0 24px">
          <p style="color:#64748b;font-size:14.5px;line-height:1.9;padding-bottom:22px;border-top:1px solid #f1f5f9;padding-top:18px">
            بالتأكيد! يمكنك تجربة المتجر التجريبي مباشرة من الصفحة الرئيسية، بدون تسجيل ولا بطاقة بنكية.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- 🎯 FINAL CTA -->
<section style="padding:80px 0;background:linear-gradient(135deg,#f59e0b,#f97316,#ea580c);position:relative;overflow:hidden">
  <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 20% 30%,rgba(255,255,255,.15),transparent 40%),radial-gradient(circle at 80% 70%,rgba(255,255,255,.1),transparent 40%)"></div>

  <div class="container-x" style="text-align:center;position:relative;z-index:2">
    <div class="reveal">
      <div style="font-size:64px;margin-bottom:16px">🚀</div>
      <h2 style="font-size:clamp(28px,5vw,48px);font-weight:900;color:#fff;margin-bottom:16px;letter-spacing:-1px">
        جاهز لإنشاء متجرك؟
      </h2>
      <p style="font-size:clamp(15px,2vw,19px);color:rgba(255,255,255,.9);margin-bottom:32px;max-width:600px;margin-inline:auto;line-height:1.8">
        انضم إلى التجار اليمنيين الذين بدأوا رحلتهم الرقمية مع MultiStore. ابدأ مجاناً — بدون بطاقة بنكية.
      </p>
      <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="/register" style="display:inline-flex;align-items:center;gap:8px;padding:18px 32px;border-radius:14px;background:#fff;color:#c2410c;font-size:16px;font-weight:900;text-decoration:none;box-shadow:0 20px 48px rgba(0,0,0,.2);transition:all .3s" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
          <i data-lucide="rocket" style="width:20px;height:20px"></i>
          ابدأ مجاناً الآن
        </a>
        <a href="/login" style="display:inline-flex;align-items:center;gap:8px;padding:18px 32px;border-radius:14px;background:rgba(255,255,255,.15);color:#fff;font-size:16px;font-weight:900;text-decoration:none;border:2px solid rgba(255,255,255,.3);backdrop-filter:blur(10px);transition:all .3s" onmouseover="this.style.background='rgba(255,255,255,.25)'" onmouseout="this.style.background='rgba(255,255,255,.15)'">
          <i data-lucide="log-in" style="width:20px;height:20px"></i>
          تسجيل الدخول
        </a>
      </div>
    </div>
  </div>
</section>

<!-- 🦶 FOOTER -->
<footer style="background:#0f172a;color:#94a3b8;padding:60px 0 30px">
  <div class="container-x">
    <div style="display:grid;grid-template-columns:1fr;gap:40px;margin-bottom:40px" class="foot-grid">
      <style>
        @media(min-width:768px){.foot-grid{grid-template-columns:2fr 1fr 1fr 1fr!important}}
      </style>

      <div>
        <div style="margin-bottom:16px">
          <x-shop-logo
            :size="48"
            variant="dark"
            :showText="true"
            name="MultiStore"
            subtitle="منصة التجارة الإلكترونية" />
        </div>
        <p style="font-size:14px;line-height:1.9;max-width:400px;color:#94a3b8">
          منصة متكاملة لإنشاء وإدارة المتاجر الإلكترونية في اليمن. صُممت بواسطة يمنيين لليمنيين 🇾🇪
        </p>
      </div>

      <div>
        <h3 style="font-size:15px;font-weight:900;color:#fff;margin-bottom:16px">المنصة</h3>
        <div style="display:flex;flex-direction:column;gap:10px;font-size:13.5px">
          <a href="#features" style="color:#94a3b8;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#94a3b8'">المميزات</a>
          <a href="#dashboard" style="color:#94a3b8;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#94a3b8'">لوحة التحكم</a>
          <a href="#pricing" style="color:#94a3b8;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#94a3b8'">الأسعار</a>
          <a href="#faq" style="color:#94a3b8;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#94a3b8'">الأسئلة الشائعة</a>
        </div>
      </div>

      <div>
        <h3 style="font-size:15px;font-weight:900;color:#fff;margin-bottom:16px">الحساب</h3>
        <div style="display:flex;flex-direction:column;gap:10px;font-size:13.5px">
          <a href="/login" style="color:#94a3b8;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#94a3b8'">تسجيل الدخول</a>
          <a href="/register" style="color:#94a3b8;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#94a3b8'">إنشاء حساب</a>
          <a href="/demo" style="color:#94a3b8;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#94a3b8'">المتجر التجريبي</a>
          <a href="/dashboard" style="color:#94a3b8;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#94a3b8'">لوحة التحكم</a>
        </div>
      </div>

      <div>
        <h3 style="font-size:15px;font-weight:900;color:#fff;margin-bottom:16px">تواصل معنا</h3>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          <a href="tel:+967777999926" style="width:40px;height:40px;border-radius:12px;background:rgba(245,158,11,.15);display:grid;place-items:center;color:#f59e0b;text-decoration:none;transition:all .2s" onmouseover="this.style.background='#f59e0b';this.style.color='#fff'" onmouseout="this.style.background='rgba(245,158,11,.15)';this.style.color='#f59e0b'" title="اتصال">
            <i data-lucide="phone" style="width:18px;height:18px"></i>
          </a>
          <a href="mailto:info@multistore.ye" style="width:40px;height:40px;border-radius:12px;background:rgba(245,158,11,.15);display:grid;place-items:center;color:#f59e0b;text-decoration:none;transition:all .2s" onmouseover="this.style.background='#f59e0b';this.style.color='#fff'" onmouseout="this.style.background='rgba(245,158,11,.15)';this.style.color='#f59e0b'" title="بريد">
            <i data-lucide="mail" style="width:18px;height:18px"></i>
          </a>
          <a href="https://wa.me/967777999926" target="_blank" style="width:40px;height:40px;border-radius:12px;background:rgba(37,211,102,.15);display:grid;place-items:center;color:#25D366;text-decoration:none;transition:all .2s" onmouseover="this.style.background='#25D366';this.style.color='#fff'" onmouseout="this.style.background='rgba(37,211,102,.15)';this.style.color='#25D366'" title="واتساب">
            <i data-lucide="message-circle" style="width:18px;height:18px"></i>
          </a>
        </div>
      </div>
    </div>

    <div style="border-top:1px solid rgba(255,255,255,.08);padding-top:24px;text-align:center;font-size:12.5px;color:#64748b">
      © {{ date('Y') }} <strong style="color:#f59e0b">MultiStore</strong> — جميع الحقوق محفوظة · صُنع بـ ❤️ في اليمن 🇾🇪
    </div>
  </div>
</footer>

<!-- ⬆️ Back to Top -->
<button id="backTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" style="position:fixed;bottom:24px;left:24px;width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;border:0;cursor:pointer;display:none;place-items:center;box-shadow:0 12px 32px rgba(245,158,11,.4);z-index:50;transition:all .3s" aria-label="عودة للأعلى">
  <i data-lucide="arrow-up" style="width:22px;height:22px"></i>
</button>

<!-- ═══════════════════════════════════════════
     🎬 JAVASCRIPT
     ═══════════════════════════════════════════ -->
<script>
  // ═══ 1) إخفاء Loading Screen فوراً ═══
  window.addEventListener('load', function() {
    const loader = document.getElementById('loader');
    if (loader) {
      setTimeout(function() {
        loader.classList.add('hidden');
        document.body.classList.remove('loading');
      }, 800);
    }
    if (window.lucide) lucide.createIcons();
  });

  // ═══ 2) Navbar Scroll Effect ═══
  const nav = document.getElementById('nav');
  window.addEventListener('scroll', function() {
    if (window.scrollY > 30) {
      nav.classList.add('scrolled');
    } else {
      nav.classList.remove('scrolled');
    }
    // Progress Bar
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const progress = (window.scrollY / docHeight) * 100;
    document.getElementById('progressFill').style.width = progress + '%';

    // Back to Top
    const bt = document.getElementById('backTop');
    if (window.scrollY > 500) {
      bt.style.display = 'grid';
    } else {
      bt.style.display = 'none';
    }
  }, { passive: true });

  // ═══ 3) Mobile Menu ═══
  window.toggleMobileMenu = function() {
    document.getElementById('mobileMenu').classList.toggle('open');
    document.body.style.overflow = document.getElementById('mobileMenu').classList.contains('open') ? 'hidden' : '';
  };
  window.closeMobileMenu = function() {
    document.getElementById('mobileMenu').classList.remove('open');
    document.body.style.overflow = '';
  };

  // ═══ 4) Reveal on Scroll ═══
  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('show');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.reveal').forEach(function(el) {
    observer.observe(el);
  });

  // ═══ 5) FAQ Toggle ═══
  window.toggleFaq = function(btn) {
    const item = btn.closest('.faq-item');
    const answer = item.querySelector('.faq-answer');
    const icon = btn.querySelector('i');
    const isOpen = item.classList.contains('open');

    // إغلاق الكل
    document.querySelectorAll('.faq-item.open').forEach(function(openItem) {
      openItem.classList.remove('open');
      openItem.querySelector('.faq-answer').style.maxHeight = '0';
      openItem.querySelector('button i').style.transform = '';
    });

    // فتح المحدد
    if (!isOpen) {
      item.classList.add('open');
      answer.style.maxHeight = answer.scrollHeight + 40 + 'px';
      icon.style.transform = 'rotate(180deg)';
    }
  };

  // ═══ 6) Card Cursor Tracking ═══
  window.trackCursor = function(e, el) {
    const rect = el.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;
    el.style.setProperty('--x', x + '%');
    el.style.setProperty('--y', y + '%');
  };

  // ═══ 7) Smooth Scroll ═══
  document.querySelectorAll('a[href^="#"]').forEach(function(link) {
    link.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href === '#') return;
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        const offset = 80;
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });

  // ═══ 8) Console Signature ═══
  console.log('%c🎨 MultiStore — منصة التجارة الإلكترونية اليمنية', 'color:#f59e0b;font-size:18px;font-weight:900;padding:10px');
  console.log('%cصُنع بـ ❤️ في اليمن 🇾🇪', 'color:#ea580c;font-size:14px;font-weight:700');
</script>

</body>
</html>

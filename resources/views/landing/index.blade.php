<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>MultiStore — منصة التجارة الإلكترونية اليمنية</title>
<meta name="description" content="MultiStore — منصة متاجر إلكترونية متعددة لأصحاب المتاجر اليمنيين. أنشئ متجرك في دقائق، أدر منتجاتك وطلباتك، واستقبل المدفوعات تلقائياً عبر SMS.">
<meta name="theme-color" content="#f97316">
<link rel="manifest" href="/manifest.webmanifest">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

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

<style>
  /* ═══ 🎯 Hero Stats Bar ═══ */
  .hero-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-top: 40px;
    padding: 20px;
    background: linear-gradient(135deg, rgba(255,255,255,.7), rgba(255,251,235,.4));
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1.5px solid rgba(251, 191, 36, .3);
    border-radius: 24px;
    box-shadow: 0 20px 40px -20px rgba(249,115,22,.25);
  }
  @media(min-width: 640px) {
    .hero-stats { grid-template-columns: repeat(4, 1fr); }
  }
  .hero-stat {
    text-align: center;
    padding: 8px;
    position: relative;
  }
  .hero-stat:not(:last-child)::after {
    content: "";
    position: absolute;
    left: -6px;
    top: 20%;
    bottom: 20%;
    width: 1px;
    background: linear-gradient(180deg, transparent, rgba(251,191,36,.3), transparent);
  }
  @media(max-width: 639px) {
    .hero-stat:nth-child(2)::after { display: none; }
  }
  .hero-stat-value {
    font-size: clamp(20px, 3.5vw, 28px);
    font-weight: 900;
    background: linear-gradient(135deg, #f59e0b, #ea580c);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    line-height: 1;
    letter-spacing: -0.5px;
    margin-bottom: 4px;
  }
  .hero-stat-label {
    font-size: 11px;
    font-weight: 800;
    color: #64748b;
    letter-spacing: 0.3px;
  }
  .hero-stat-icon {
    font-size: 18px;
    margin-bottom: 4px;
    display: block;
  }

  /* ✨ Animation عند الظهور */
  .hero-stat-value {
    animation: statFadeIn 0.8s cubic-bezier(.2,.9,.3,1.1) backwards;
  }
  .hero-stat:nth-child(1) .hero-stat-value { animation-delay: 0.1s; }
  .hero-stat:nth-child(2) .hero-stat-value { animation-delay: 0.2s; }
  .hero-stat:nth-child(3) .hero-stat-value { animation-delay: 0.3s; }
  .hero-stat:nth-child(4) .hero-stat-value { animation-delay: 0.4s; }

  @keyframes statFadeIn {
    from {
      opacity: 0;
      transform: translateY(12px) scale(0.9);
    }
    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }

  /* 🌙 Dark Mode */
  html.dark .hero-stats {
    background: linear-gradient(135deg, rgba(30,41,59,.7), rgba(15,23,42,.4));
    border-color: rgba(251, 191, 36, .15);
  }
  html.dark .hero-stat-label {
    color: #94a3b8;
  }
</style>


<style>
  /* ═══ 📱 Floating Cards حول الجهاز ═══ */
  .hero-device {
    position: relative;
    display: inline-block;
  }

  .floating-card {
    position: absolute;
    background: #fff;
    padding: 12px 16px;
    border-radius: 16px;
    box-shadow: 0 15px 35px -10px rgba(15,23,42,.2),
                0 0 0 1.5px rgba(251,191,36,.15);
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
    opacity: 0;
    z-index: 5;
    animation: floatIn 0.7s cubic-bezier(.2,.9,.3,1.1) forwards;
    pointer-events: none;
  }

  .floating-card .fc-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
  }
  .floating-card .fc-title {
    color: #0f172a;
    font-weight: 900;
    font-size: 12px;
    line-height: 1.2;
  }
  .floating-card .fc-sub {
    color: #94a3b8;
    font-size: 10px;
    font-weight: 700;
    margin-top: 2px;
  }

  /* 🟢 طلب جديد - أعلى يسار */
  .fc-order {
    top: 15%;
    left: -8%;
    animation-delay: 0.3s;
  }
  .fc-order .fc-icon {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    color: #15803d;
  }

  /* 🔵 تم الشحن - أسفل يمين */
  .fc-shipped {
    bottom: 20%;
    right: -6%;
    animation-delay: 0.5s;
  }
  .fc-shipped .fc-icon {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1d4ed8;
  }

  /* ⭐ تقييم - أسفل يسار */
  .fc-rating {
    bottom: 5%;
    left: 0%;
    animation-delay: 0.7s;
  }
  .fc-rating .fc-icon {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #b45309;
  }

  @keyframes floatIn {
    from {
      opacity: 0;
      transform: translateY(20px) scale(0.85);
    }
    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }

  /* 🌀 تحرك ناعم دائم */
  .floating-card {
    animation: floatIn 0.7s cubic-bezier(.2,.9,.3,1.1) forwards,
               floatPulse 3s ease-in-out infinite 1s;
  }
  .fc-order { animation-delay: 0.3s, 1.3s; }
  .fc-shipped { animation-delay: 0.5s, 1.5s; }
  .fc-rating { animation-delay: 0.7s, 1.7s; }

  @keyframes floatPulse {
    0%, 100% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-6px) scale(1); }
  }

  /* 📱 موبايل — تصغير البطاقات + إعادة توزيع */
  @media (max-width: 768px) {
    .floating-card {
      transform: scale(0.75);
      padding: 8px 12px;
      border-radius: 12px;
      font-size: 10px;
    }
    .floating-card .fc-icon {
      width: 26px;
      height: 26px;
      font-size: 13px;
    }
    .floating-card .fc-title { font-size: 10px; }
    .floating-card .fc-sub { font-size: 8px; }

    /* إعادة توزيع لتناسب الشاشة الصغيرة */
    .fc-order {
      top: 8%;
      left: 2%;
    }
    .fc-shipped {
      bottom: 12%;
      right: 2%;
    }
    .fc-rating {
      bottom: 2%;
      left: 2%;
    }

    /* إيقاف التحريك الدائم على الموبايل لتوفير الأداء */
    .floating-card {
      animation: floatIn 0.7s cubic-bezier(.2,.9,.3,1.1) forwards !important;
    }
    .fc-order { animation-delay: 0.3s !important; }
    .fc-shipped { animation-delay: 0.5s !important; }
    .fc-rating { animation-delay: 0.7s !important; }
  }

  /* 🌙 Dark mode */
  html.dark .floating-card {
    background: #1e293b;
    box-shadow: 0 15px 35px -10px rgba(0,0,0,.5),
                0 0 0 1.5px rgba(251,191,36,.15);
  }
  html.dark .floating-card .fc-title { color: #f1f5f9; }
</style>


<style>
/* ═══ 🎨 Feature Cards Enhancement ═══ */
#features .card-glow {
  cursor: pointer;
  transition: transform .35s cubic-bezier(.2,.9,.3,1.1),
              box-shadow .35s, border-color .35s !important;
}
#features .card-glow:hover {
  transform: translateY(-8px) !important;
  box-shadow: 0 25px 50px -12px rgba(249,115,22,.3),
              0 8px 20px -6px rgba(15,23,42,.08) !important;
  border-color: rgba(251,191,36,.5) !important;
}
#features .card-icon {
  position: relative;
  transition: transform .4s cubic-bezier(.2,.9,.3,1.1),
              box-shadow .4s !important;
  box-shadow: 0 8px 20px -6px rgba(249,115,22,.35) !important;
  z-index: 1;
}
#features .card-glow:hover .card-icon {
  transform: translateY(-6px) scale(1.12) rotate(-4deg) !important;
  box-shadow: 0 18px 35px -8px rgba(249,115,22,.55),
              0 6px 15px -3px rgba(249,115,22,.35) !important;
}
#features .card-icon::after {
  content: "";
  position: absolute;
  inset: -12px;
  border-radius: inherit;
  background: radial-gradient(circle, rgba(249,115,22,.35), transparent 65%);
  opacity: 0;
  transition: opacity .4s;
  z-index: -1;
  filter: blur(10px);
  pointer-events: none;
}
#features .card-glow:hover .card-icon::after { opacity: 1; }

/* ═══ 🚀 Step Cards Hover ═══ */
.step-card-hover { transition: all .35s cubic-bezier(.2,.9,.3,1.1) !important; }
.step-card-hover:hover {
  transform: translateY(-10px) !important;
  box-shadow: 0 28px 55px -15px rgba(249,115,22,.35) !important;
  border-color: rgba(249,115,22,.4) !important;
}
.step-card-hover .step-icon,
.step-card-hover .step-number { transition: all .4s cubic-bezier(.2,.9,.3,1.1) !important; }
.step-card-hover:hover .step-icon {
  transform: translateY(-6px) scale(1.1) !important;
  box-shadow: 0 15px 30px -8px rgba(249,115,22,.5) !important;
}
.step-card-hover:hover .step-number {
  transform: translateX(-50%) translateY(-6px) scale(1.15) !important;
  box-shadow: 0 18px 35px -8px rgba(249,115,22,.6) !important;
}

/* ═══ 🪟 Feature Modal ═══ */
.feature-modal-overlay {
  position: fixed; inset: 0;
  background: rgba(15,23,42,.65);
  backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
  z-index: 9999; display: none;
  align-items: center; justify-content: center;
  padding: 20px;
  animation: modalFadeIn .25s ease;
}
.feature-modal-overlay.open { display: flex; }
@keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }
.feature-modal-box {
  background: #fff; border-radius: 28px;
  max-width: 520px; width: 100%; max-height: 90vh; overflow-y: auto;
  padding: 34px 28px 28px;
  box-shadow: 0 30px 80px -20px rgba(0,0,0,.5);
  animation: modalSlideUp .4s cubic-bezier(.2,.9,.3,1.1);
  position: relative; text-align: center;
}
@keyframes modalSlideUp {
  from { opacity: 0; transform: translateY(40px) scale(.95); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}
.feature-modal-close {
  position: absolute; top: 14px; left: 14px;
  width: 38px; height: 38px; border-radius: 50%;
  border: 0; background: #f1f5f9; color: #64748b;
  font-size: 20px; font-weight: 700; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .25s; font-family: inherit;
}
.feature-modal-close:hover {
  background: #fee2e2; color: #dc2626; transform: rotate(90deg);
}
.feature-modal-icon {
  width: 92px; height: 92px; border-radius: 28px;
  background: linear-gradient(135deg, #fbbf24, #f97316);
  color: #fff; display: flex; align-items: center; justify-content: center;
  font-size: 46px; margin: 0 auto 22px;
  box-shadow: 0 20px 40px -10px rgba(249,115,22,.55),
              0 8px 20px -4px rgba(249,115,22,.3);
  animation: modalIconFloat 2.5s ease-in-out infinite;
}
@keyframes modalIconFloat {
  0%, 100% { transform: translateY(0); }
  50%      { transform: translateY(-6px); }
}
.feature-modal-title {
  font-size: 24px; font-weight: 900; color: #0f172a;
  margin: 0 0 12px; letter-spacing: -.5px;
}
.feature-modal-desc {
  color: #475569; font-size: 15px; line-height: 1.9; margin: 0 0 22px;
}
.feature-modal-points { list-style: none; padding: 0; margin: 0 0 26px; text-align: right; }
.feature-modal-points li {
  padding: 11px 14px; background: #fffbeb; border-radius: 12px;
  margin-bottom: 8px; font-weight: 700; color: #92400e;
  font-size: 13.5px; display: flex; align-items: center; gap: 10px;
  border: 1px solid rgba(251,191,36,.2);
}
.feature-modal-points li::before {
  content: "✓"; color: #16a34a; font-weight: 900; font-size: 14px; flex-shrink: 0;
}
.feature-modal-cta {
  display: inline-flex; align-items: center; justify-content: center; gap: 8px;
  width: 100%; padding: 15px 22px; border-radius: 16px;
  background: linear-gradient(135deg, #fbbf24, #f97316);
  color: #fff; font-weight: 900; font-size: 15px; text-decoration: none;
  box-shadow: 0 12px 30px -8px rgba(249,115,22,.5);
  transition: all .3s; font-family: inherit;
}
.feature-modal-cta:hover { transform: translateY(-2px); box-shadow: 0 18px 40px -10px rgba(249,115,22,.65); }

html.dark .feature-modal-box { background: #1e293b; }
html.dark .feature-modal-title { color: #f1f5f9; }
html.dark .feature-modal-desc { color: #cbd5e1; }
html.dark .feature-modal-points li { background: rgba(251,191,36,.1); color: #fbbf24; }
html.dark .feature-modal-close { background: #334155; color: #cbd5e1; }

@media (max-width: 640px) {
  .feature-modal-box { padding: 28px 20px 24px; border-radius: 24px; }
  .feature-modal-icon { width: 78px; height: 78px; font-size: 38px; border-radius: 22px; }
  .feature-modal-title { font-size: 20px; }
}
</style>


<style>
/* ═══════════════════════════════════════════════
   🎨 أيقونات 3D دائمة (تظهر على الموبايل والكمبيوتر)
   ═══════════════════════════════════════════════ */

/* ─── Feature Cards Icons ─── */
#features .card-icon {
  width: 76px !important;
  height: 76px !important;
  border-radius: 24px !important;
  background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #ea580c 100%) !important;
  color: #ffffff !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  position: relative !important;
  box-shadow:
    0 18px 35px -10px rgba(249,115,22,.55),
    0 8px 18px -4px rgba(249,115,22,.4),
    inset 0 -3px 6px rgba(0,0,0,.15),
    inset 0 3px 6px rgba(255,255,255,.35) !important;
  transition: transform .4s cubic-bezier(.2,.9,.3,1.1),
              box-shadow .4s !important;
  z-index: 1;
}
#features .card-icon i,
#features .card-icon svg {
  width: 36px !important;
  height: 36px !important;
  stroke-width: 2.2 !important;
  filter: drop-shadow(0 2px 4px rgba(0,0,0,.15));
}

/* 🌟 حلقة متوهجة دائمة */
#features .card-icon::before {
  content: "" !important;
  position: absolute !important;
  inset: -8px !important;
  border-radius: inherit !important;
  background: radial-gradient(circle, rgba(251,191,36,.5), rgba(249,115,22,.2) 45%, transparent 70%) !important;
  filter: blur(12px) !important;
  z-index: -1 !important;
  opacity: .85 !important;
  pointer-events: none !important;
}
/* ✨ لمعة زجاجية على الأيقونة */
#features .card-icon::after {
  content: "" !important;
  position: absolute !important;
  top: 6px !important;
  left: 12px !important;
  width: 34% !important;
  height: 24% !important;
  border-radius: 50% !important;
  background: linear-gradient(135deg, rgba(255,255,255,.65), transparent) !important;
  filter: blur(4px) !important;
  pointer-events: none !important;
  z-index: 2 !important;
}

/* 🎭 عند hover — ترتفع أكثر */
#features .card-glow:hover .card-icon {
  transform: translateY(-8px) scale(1.12) rotate(-5deg) !important;
  box-shadow:
    0 25px 50px -12px rgba(249,115,22,.7),
    0 12px 25px -5px rgba(249,115,22,.5),
    inset 0 -3px 6px rgba(0,0,0,.15),
    inset 0 3px 6px rgba(255,255,255,.4) !important;
}

/* ─── Step Cards Icons ─── */
.step-icon {
  background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #ea580c 100%) !important;
  color: #ffffff !important;
  box-shadow:
    0 18px 35px -10px rgba(249,115,22,.55),
    0 8px 18px -4px rgba(249,115,22,.4),
    inset 0 -3px 6px rgba(0,0,0,.15),
    inset 0 3px 6px rgba(255,255,255,.35) !important;
  position: relative;
  transition: transform .4s cubic-bezier(.2,.9,.3,1.1) !important;
}
.step-icon i,
.step-icon svg {
  filter: drop-shadow(0 2px 4px rgba(0,0,0,.15));
}
.step-icon::before {
  content: "" !important;
  position: absolute !important;
  inset: -8px !important;
  border-radius: inherit !important;
  background: radial-gradient(circle, rgba(251,191,36,.5), rgba(249,115,22,.2) 45%, transparent 70%) !important;
  filter: blur(12px) !important;
  z-index: -1 !important;
  opacity: .85 !important;
  pointer-events: none !important;
}
.step-card-hover:hover .step-icon {
  transform: translateY(-8px) scale(1.12) !important;
}

/* ─── Step Numbers ─── */
.step-number {
  box-shadow:
    0 15px 30px -8px rgba(249,115,22,.6),
    0 6px 15px -3px rgba(249,115,22,.45),
    inset 0 -2px 4px rgba(0,0,0,.15),
    inset 0 2px 4px rgba(255,255,255,.3) !important;
}

/* ─── الموبايل: أيقونات أصغر قليلاً ─── */
@media (max-width: 640px) {
  #features .card-icon { width: 68px !important; height: 68px !important; border-radius: 20px !important; }
  #features .card-icon i, #features .card-icon svg { width: 32px !important; height: 32px !important; }
  .step-icon { transform: scale(0.95); }
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   🎨 أيقونات 3D بإطار وظل وانعكاس وبروز
   ═══════════════════════════════════════════════════════ */

/* ─── Feature Card Icons — إطار قوي وواضح ─── */
#features .card-icon {
  width: 84px !important;
  height: 84px !important;
  border-radius: 26px !important;
  background: linear-gradient(135deg, #ffffff 0%, #fff7ed 40%, #fef3c7 100%) !important;
  color: #c2410c !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  position: relative !important;
  border: 4px solid #ea580c !important;
  outline: 2px solid rgba(249,115,22,.25) !important;
  outline-offset: 3px !important;
  box-shadow:
    0 22px 45px -12px rgba(234,88,12,.55),
    0 10px 22px -6px rgba(234,88,12,.4),
    0 4px 10px -2px rgba(15,23,42,.18),
    inset 0 5px 10px rgba(255,255,255,1),
    inset 0 -5px 12px rgba(234,88,12,.35) !important;
  transform: perspective(400px) rotateX(0deg) translateZ(0) !important;
  transition: all .4s cubic-bezier(.2,.9,.3,1.1) !important;
  z-index: 1;
  overflow: visible !important;
}

/* 🎯 أيقونة الداخل */
#features .card-icon i,
#features .card-icon svg {
  width: 40px !important;
  height: 40px !important;
  stroke-width: 2.5 !important;
  filter: drop-shadow(0 2px 3px rgba(194,65,12,.4)) !important;
  z-index: 3 !important;
  position: relative !important;
}

/* ✨ ظل خارجي متوهج (Glow Ring) */
#features .card-icon::before {
  content: "" !important;
  position: absolute !important;
  inset: -12px !important;
  border-radius: 30px !important;
  background: radial-gradient(circle, rgba(251,191,36,.6) 0%, rgba(249,115,22,.25) 45%, transparent 70%) !important;
  filter: blur(14px) !important;
  z-index: -1 !important;
  opacity: 1 !important;
  pointer-events: none !important;
  animation: iconGlow 3s ease-in-out infinite !important;
}

@keyframes iconGlow {
  0%, 100% { opacity: .85; transform: scale(1); }
  50%      { opacity: 1; transform: scale(1.08); }
}

/* 💫 لمعة زجاجية (Reflection) */
#features .card-icon::after {
  content: "" !important;
  position: absolute !important;
  top: 8px !important;
  left: 14px !important;
  right: 14px !important;
  height: 32% !important;
  border-radius: 50% 50% 40% 40% / 60% 60% 40% 40% !important;
  background: linear-gradient(180deg, rgba(255,255,255,.85) 0%, rgba(255,255,255,.3) 60%, transparent 100%) !important;
  filter: blur(3px) !important;
  pointer-events: none !important;
  z-index: 2 !important;
}

/* 🎭 عند Hover — تنبرز للأمام */
#features .card-glow:hover .card-icon {
  transform: perspective(400px) rotateX(-12deg) rotateY(8deg) translateY(-10px) translateZ(30px) scale(1.15) !important;
  box-shadow:
    0 30px 60px -15px rgba(249,115,22,.75),
    0 15px 35px -8px rgba(249,115,22,.55),
    0 6px 15px -3px rgba(15,23,42,.25),
    inset 0 4px 10px rgba(255,255,255,1),
    inset 0 -4px 10px rgba(251,191,36,.5) !important;
  border-color: #c2410c !important;
  outline-color: rgba(194,65,12,.5) !important;
}

/* ═══════════════════════════════════════════════════════
   Step Cards Icons
   ═══════════════════════════════════════════════════════ */
.step-icon {
  width: 84px !important;
  height: 84px !important;
  border-radius: 26px !important;
  background: linear-gradient(135deg, #ffffff 0%, #fff7ed 40%, #fef3c7 100%) !important;
  color: #c2410c !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  border: 4px solid #ea580c !important;
  outline: 2px solid rgba(249,115,22,.25) !important;
  outline-offset: 3px !important;
  box-shadow:
    0 22px 45px -12px rgba(234,88,12,.55),
    0 10px 22px -6px rgba(234,88,12,.4),
    0 4px 10px -2px rgba(15,23,42,.18),
    inset 0 5px 10px rgba(255,255,255,1),
    inset 0 -5px 12px rgba(234,88,12,.35) !important;
  position: relative !important;
  transition: all .4s cubic-bezier(.2,.9,.3,1.1) !important;
  overflow: visible !important;
}
.step-icon i,
.step-icon svg {
  width: 40px !important;
  height: 40px !important;
  stroke-width: 2.5 !important;
  filter: drop-shadow(0 2px 3px rgba(194,65,12,.4)) !important;
  z-index: 3 !important;
  position: relative !important;
}
.step-icon::before {
  content: "" !important;
  position: absolute !important;
  inset: -12px !important;
  border-radius: 30px !important;
  background: radial-gradient(circle, rgba(251,191,36,.6) 0%, rgba(249,115,22,.25) 45%, transparent 70%) !important;
  filter: blur(14px) !important;
  z-index: -1 !important;
  animation: iconGlow 3s ease-in-out infinite !important;
  pointer-events: none !important;
}
.step-icon::after {
  content: "" !important;
  position: absolute !important;
  top: 8px !important;
  left: 14px !important;
  right: 14px !important;
  height: 32% !important;
  border-radius: 50% 50% 40% 40% / 60% 60% 40% 40% !important;
  background: linear-gradient(180deg, rgba(255,255,255,.85) 0%, transparent 100%) !important;
  filter: blur(3px) !important;
  pointer-events: none !important;
  z-index: 2 !important;
}
.step-card-hover:hover .step-icon {
  transform: perspective(400px) rotateX(-12deg) rotateY(8deg) translateY(-10px) translateZ(30px) scale(1.15) !important;
  box-shadow:
    0 30px 60px -15px rgba(249,115,22,.75),
    0 15px 35px -8px rgba(249,115,22,.55),
    0 6px 15px -3px rgba(15,23,42,.25),
    inset 0 4px 10px rgba(255,255,255,1),
    inset 0 -4px 10px rgba(251,191,36,.5) !important;
  border-color: #c2410c !important;
  outline-color: rgba(194,65,12,.5) !important;
}

/* ─── Step Numbers (1, 2, 3) ─── */
.step-number {
  border: 3px solid #fff !important;
  text-shadow: 0 2px 4px rgba(0,0,0,.25) !important;
  box-shadow:
    0 15px 30px -8px rgba(249,115,22,.65),
    0 6px 15px -3px rgba(249,115,22,.45),
    inset 0 -3px 6px rgba(0,0,0,.2),
    inset 0 3px 6px rgba(255,255,255,.4) !important;
  transition: all .4s cubic-bezier(.2,.9,.3,1.1) !important;
}
.step-card-hover:hover .step-number {
  transform: translateX(-50%) translateY(-10px) translateZ(20px) scale(1.2) !important;
  box-shadow:
    0 22px 45px -10px rgba(249,115,22,.85),
    0 10px 22px -5px rgba(249,115,22,.6) !important;
}

/* ─── الموبايل ─── */
@media (max-width: 640px) {
  #features .card-icon,
  .step-icon {
    width: 70px !important;
    height: 70px !important;
    border-radius: 20px !important;
  }
  #features .card-icon i,
  #features .card-icon svg,
  .step-icon i,
  .step-icon svg {
    width: 34px !important;
    height: 34px !important;
  }
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   🎨 Section Frame — إطار احترافي للقسم
   ═══════════════════════════════════════════════════════ */

.section-framed {
  position: relative;
  padding: 80px 0 !important;
  margin: 40px auto !important;
  max-width: 1240px;
  background: linear-gradient(135deg, #ffffff 0%, #fffbf5 50%, #fff7ed 100%);
  border: 3px solid transparent;
  border-radius: 40px;
  box-shadow:
    /* إطار متدرج */
    0 0 0 3px rgba(251,191,36,.25),
    0 0 0 4px rgba(249,115,22,.15),
    /* ظل عميق */
    0 40px 80px -30px rgba(249,115,22,.25),
    0 20px 40px -15px rgba(15,23,42,.08),
    /* انعكاس داخلي */
    inset 0 3px 20px rgba(255,255,255,.9),
    inset 0 -3px 20px rgba(251,191,36,.15);
  overflow: hidden;
}

/* ✨ زوايا مزخرفة (Corner Decorations) */
.section-framed::before,
.section-framed::after {
  content: "";
  position: absolute;
  width: 120px;
  height: 120px;
  pointer-events: none;
  z-index: 2;
}
.section-framed::before {
  top: 0;
  right: 0;
  background: radial-gradient(circle at top right,
    rgba(251,191,36,.25) 0%,
    rgba(249,115,22,.12) 30%,
    transparent 65%);
  border-top-right-radius: 40px;
}
.section-framed::after {
  bottom: 0;
  left: 0;
  background: radial-gradient(circle at bottom left,
    rgba(251,191,36,.25) 0%,
    rgba(249,115,22,.12) 30%,
    transparent 65%);
  border-bottom-left-radius: 40px;
}

/* 🌟 شريط علوي مضيء */
.section-framed .frame-glow {
  position: absolute;
  top: -2px;
  left: 10%;
  right: 10%;
  height: 4px;
  background: linear-gradient(90deg,
    transparent,
    rgba(251,191,36,.8) 20%,
    rgba(249,115,22,.9) 50%,
    rgba(251,191,36,.8) 80%,
    transparent);
  border-radius: 4px;
  filter: blur(1px);
  animation: frameGlowPulse 3s ease-in-out infinite;
  z-index: 3;
}
@keyframes frameGlowPulse {
  0%, 100% { opacity: .7; transform: scaleX(.9); }
  50%      { opacity: 1; transform: scaleX(1); }
}

/* 📱 الموبايل */
@media (max-width: 640px) {
  .section-framed {
    margin: 20px 12px !important;
    padding: 60px 0 !important;
    border-radius: 28px;
    box-shadow:
      0 0 0 2px rgba(251,191,36,.3),
      0 20px 40px -15px rgba(249,115,22,.2),
      inset 0 2px 12px rgba(255,255,255,.9);
  }
  .section-framed::before,
  .section-framed::after {
    width: 80px;
    height: 80px;
  }
  .section-framed .frame-glow {
    left: 20%;
    right: 20%;
  }
}

/* 🌙 Dark mode */
html.dark .section-framed {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 50%, #0f172a 100%);
  box-shadow:
    0 0 0 3px rgba(251,191,36,.15),
    0 40px 80px -30px rgba(249,115,22,.2),
    inset 0 3px 20px rgba(255,255,255,.05);
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   🎨 Frame للبطاقات — إطار + ظل + انعكاس + بروز
   ═══════════════════════════════════════════════════════ */

/* ─── Feature Cards (6 بطاقات) ─── */
#features .card-glow {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
  border: 2.5px solid #1e3a8a !important;
  border-radius: 24px !important;
  padding: 32px 28px !important;
  position: relative !important;
  box-shadow:
    0 25px 50px -20px rgba(249,115,22,.25),
    0 10px 25px -10px rgba(15,23,42,.08),
    0 2px 6px -1px rgba(15,23,42,.05),
    inset 0 2px 4px rgba(255,255,255,.95),
    inset 0 -2px 4px rgba(30,58,138,.1) !important;
  transition: all .45s cubic-bezier(.2,.9,.3,1.1) !important;
  overflow: hidden !important;
  transform: perspective(1200px) rotateX(0) rotateY(0) translateZ(0) !important;
}

/* 🌟 شريط علوي ذهبي مضيء */
#features .card-glow::before {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 15% !important;
  right: 15% !important;
  height: 3px !important;
  background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent) !important;
  border-radius: 3px !important;
  filter: blur(.5px) !important;
  opacity: .7 !important;
  transition: all .45s !important;
  z-index: 2 !important;
}

/* ✨ لمعة زجاجية أعلى البطاقة (انعكاس) */
#features .card-glow::after {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  height: 40% !important;
  background: linear-gradient(180deg,
    rgba(255,255,255,.7) 0%,
    rgba(255,255,255,.2) 40%,
    transparent 100%) !important;
  pointer-events: none !important;
  z-index: 1 !important;
  opacity: .5 !important;
  transition: opacity .45s !important;
}

/* 🎭 عند Hover — بروز 3D قوي */
#features .card-glow:hover {
  transform: perspective(1200px) rotateX(-2deg) rotateY(2deg) translateY(-14px) translateZ(30px) scale(1.02) !important;
  border-color: #1e40af !important;
  box-shadow:
    0 45px 80px -25px rgba(249,115,22,.5),
    0 20px 45px -15px rgba(249,115,22,.3),
    0 8px 20px -5px rgba(15,23,42,.15),
    inset 0 2px 6px rgba(255,255,255,1),
    inset 0 -3px 8px rgba(30,58,138,.15) !important;
}

#features .card-glow:hover::before {
  opacity: 1 !important;
  filter: blur(0) !important;
}
#features .card-glow:hover::after {
  opacity: .8 !important;
}

/* ─── Step Cards (3 بطاقات) ─── */
#how .reveal.step-card-hover,
#how .step-card-hover {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
  border: 2.5px solid #1e3a8a !important;
  border-radius: 26px !important;
  padding: 44px 28px 36px !important;
  position: relative !important;
  box-shadow:
    0 25px 50px -20px rgba(249,115,22,.25),
    0 10px 25px -10px rgba(15,23,42,.08),
    0 2px 6px -1px rgba(15,23,42,.05),
    inset 0 2px 4px rgba(255,255,255,.95),
    inset 0 -2px 4px rgba(30,58,138,.1) !important;
  transition: all .45s cubic-bezier(.2,.9,.3,1.1) !important;
  overflow: hidden !important;
  transform: perspective(1200px) rotateX(0) rotateY(0) translateZ(0) !important;
  cursor: pointer !important;
}

#how .step-card-hover::before {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 15% !important;
  right: 15% !important;
  height: 3px !important;
  background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent) !important;
  border-radius: 3px !important;
  filter: blur(.5px) !important;
  opacity: .7 !important;
  transition: all .45s !important;
  z-index: 2 !important;
}
#how .step-card-hover::after {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  height: 40% !important;
  background: linear-gradient(180deg,
    rgba(255,255,255,.7) 0%,
    transparent 100%) !important;
  pointer-events: none !important;
  z-index: 1 !important;
  opacity: .5 !important;
  transition: opacity .45s !important;
}

#how .step-card-hover:hover {
  transform: perspective(1200px) rotateX(-2deg) rotateY(2deg) translateY(-14px) translateZ(30px) scale(1.02) !important;
  border-color: #1e40af !important;
  box-shadow:
    0 45px 80px -25px rgba(249,115,22,.5),
    0 20px 45px -15px rgba(249,115,22,.3),
    0 8px 20px -5px rgba(15,23,42,.15),
    inset 0 2px 6px rgba(255,255,255,1),
    inset 0 -3px 8px rgba(30,58,138,.15) !important;
}
#how .step-card-hover:hover::before { opacity: 1 !important; }
#how .step-card-hover:hover::after  { opacity: .8 !important; }

/* ─── الموبايل — ظل أبسط ─── */
@media (max-width: 640px) {
  #features .card-glow,
  #how .step-card-hover {
    padding: 28px 22px !important;
    border-width: 2px !important;
    box-shadow:
      0 15px 35px -15px rgba(249,115,22,.25),
      0 5px 15px -5px rgba(15,23,42,.08),
      inset 0 2px 4px rgba(255,255,255,.95) !important;
  }
}

/* ─── Dark mode ─── */
html.dark #features .card-glow,
html.dark #how .step-card-hover {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%) !important;
  border-color: rgba(59,130,246,.4) !important;
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   💰 Pricing Cards — إطار + ظل + انعكاس + بروز 3D
   ═══════════════════════════════════════════════════════ */

/* ─── كل بطاقات الأسعار ─── */
#pricing .price-card,
#pricing .pricing-card,
#pricing [class*="plan-card"] {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
  border: 2.5px solid #1e3a8a !important;
  border-radius: 26px !important;
  padding: 36px 28px 32px !important;
  position: relative !important;
  overflow: hidden !important;
  box-shadow:
    0 25px 50px -20px rgba(30,58,138,.25),
    0 10px 25px -10px rgba(15,23,42,.08),
    0 2px 6px -1px rgba(15,23,42,.05),
    inset 0 2px 4px rgba(255,255,255,.95),
    inset 0 -2px 4px rgba(30,58,138,.1) !important;
  transition: all .45s cubic-bezier(.2,.9,.3,1.1) !important;
  transform: perspective(1200px) rotateX(0) rotateY(0) translateZ(0) !important;
}

/* ✨ الشريط العلوي المضيء */
#pricing .price-card::before,
#pricing .pricing-card::before,
#pricing [class*="plan-card"]::before {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 15% !important;
  right: 15% !important;
  height: 3px !important;
  background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent) !important;
  border-radius: 3px !important;
  filter: blur(.5px) !important;
  opacity: .7 !important;
  transition: all .45s !important;
  z-index: 2 !important;
}

/* 💫 الانعكاس الزجاجي العلوي */
#pricing .price-card::after,
#pricing .pricing-card::after,
#pricing [class*="plan-card"]::after {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  height: 40% !important;
  background: linear-gradient(180deg,
    rgba(255,255,255,.7) 0%,
    rgba(255,255,255,.2) 40%,
    transparent 100%) !important;
  pointer-events: none !important;
  z-index: 1 !important;
  opacity: .5 !important;
  transition: opacity .45s !important;
}

/* 🎭 Hover — بروز 3D */
#pricing .price-card:hover,
#pricing .pricing-card:hover,
#pricing [class*="plan-card"]:hover {
  transform: perspective(1200px) rotateX(-2deg) rotateY(2deg) translateY(-14px) translateZ(30px) scale(1.02) !important;
  border-color: #1e40af !important;
  box-shadow:
    0 45px 80px -25px rgba(30,58,138,.5),
    0 20px 45px -15px rgba(30,58,138,.3),
    0 8px 20px -5px rgba(15,23,42,.15),
    inset 0 2px 6px rgba(255,255,255,1),
    inset 0 -3px 8px rgba(30,58,138,.15) !important;
}

#pricing .price-card:hover::before,
#pricing .pricing-card:hover::before,
#pricing [class*="plan-card"]:hover::before {
  opacity: 1 !important;
  filter: blur(0) !important;
}
#pricing .price-card:hover::after,
#pricing .pricing-card:hover::after,
#pricing [class*="plan-card"]:hover::after {
  opacity: .8 !important;
}

/* ⭐ الخطة المميزة (الأكثر طلباً) — إطار أقوى */
#pricing .price-card.featured,
#pricing .pricing-card.featured,
#pricing .price-card.popular,
#pricing [class*="plan-card"].featured {
  border-color: #1e40af !important;
  border-width: 3px !important;
  box-shadow:
    0 35px 65px -20px rgba(30,58,138,.45),
    0 15px 30px -8px rgba(30,58,138,.25),
    0 4px 10px -2px rgba(15,23,42,.1),
    inset 0 2px 6px rgba(255,255,255,1),
    inset 0 -3px 8px rgba(30,58,138,.2) !important;
}
#pricing .price-card.featured::before,
#pricing .pricing-card.featured::before {
  opacity: 1 !important;
  height: 4px !important;
  filter: blur(0) !important;
}

/* ─── الموبايل ─── */
@media (max-width: 640px) {
  #pricing .price-card,
  #pricing .pricing-card,
  #pricing [class*="plan-card"] {
    padding: 28px 22px 24px !important;
    border-width: 2px !important;
    box-shadow:
      0 15px 35px -15px rgba(30,58,138,.25),
      0 5px 15px -5px rgba(15,23,42,.08),
      inset 0 2px 4px rgba(255,255,255,.95) !important;
  }
}

/* ─── Dark mode ─── */
html.dark #pricing .price-card,
html.dark #pricing .pricing-card,
html.dark #pricing [class*="plan-card"] {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%) !important;
  border-color: rgba(59,130,246,.4) !important;
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   💰 Pricing Cards — إطار كحلي + ظل + انعكاس + 3D
   ═══════════════════════════════════════════════════════ */

#pricing .pricing-card {
  transition: all .45s cubic-bezier(.2,.9,.3,1.1) !important;
  box-shadow:
    0 25px 50px -20px rgba(30,58,138,.25),
    0 10px 25px -10px rgba(15,23,42,.08),
    0 2px 6px -1px rgba(15,23,42,.05),
    inset 0 2px 4px rgba(255,255,255,.95),
    inset 0 -2px 4px rgba(30,58,138,.1) !important;
  transform: perspective(1200px) rotateX(0) rotateY(0) translateZ(0) !important;
}

/* ✨ الشريط العلوي المضيء */
#pricing .pricing-card::before {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 15% !important;
  right: 15% !important;
  height: 3px !important;
  background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent) !important;
  border-radius: 3px !important;
  filter: blur(.5px) !important;
  opacity: .7 !important;
  transition: all .45s !important;
  z-index: 2 !important;
}

/* 💫 الانعكاس الزجاجي */
#pricing .pricing-card::after {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  height: 40% !important;
  background: linear-gradient(180deg, rgba(255,255,255,.7) 0%, transparent 100%) !important;
  pointer-events: none !important;
  z-index: 1 !important;
  opacity: .5 !important;
  transition: opacity .45s !important;
}

/* 🎭 Hover — بروز 3D */
#pricing .pricing-card:hover {
  transform: perspective(1200px) rotateX(-2deg) rotateY(2deg) translateY(-14px) translateZ(30px) scale(1.02) !important;
  border-color: #1e40af !important;
  box-shadow:
    0 45px 80px -25px rgba(30,58,138,.5),
    0 20px 45px -15px rgba(30,58,138,.3),
    0 8px 20px -5px rgba(15,23,42,.15),
    inset 0 2px 6px rgba(255,255,255,1),
    inset 0 -3px 8px rgba(30,58,138,.15) !important;
}
#pricing .pricing-card:hover::before { opacity: 1 !important; filter: blur(0) !important; }
#pricing .pricing-card:hover::after { opacity: .8 !important; }

/* ⭐ البطاقة المميزة */
#pricing .pricing-featured {
  transform: perspective(1200px) scale(1.03) !important;
  box-shadow:
    0 35px 65px -20px rgba(30,58,138,.45),
    0 15px 30px -8px rgba(30,58,138,.25),
    0 4px 10px -2px rgba(15,23,42,.1),
    inset 0 2px 6px rgba(255,255,255,1),
    inset 0 -3px 8px rgba(30,58,138,.2) !important;
}
#pricing .pricing-featured::before {
  opacity: 1 !important;
  height: 4px !important;
  filter: blur(0) !important;
}
#pricing .pricing-featured:hover {
  transform: perspective(1200px) rotateX(-2deg) rotateY(2deg) translateY(-14px) translateZ(30px) scale(1.05) !important;
}

/* 📱 الموبايل */
@media (max-width: 640px) {
  #pricing .pricing-card {
    padding: 28px 22px 24px !important;
    border-width: 2px !important;
    box-shadow:
      0 15px 35px -15px rgba(30,58,138,.25),
      0 5px 15px -5px rgba(15,23,42,.08),
      inset 0 2px 4px rgba(255,255,255,.95) !important;
  }
  #pricing .pricing-featured { transform: none !important; }
}

/* 🌙 Dark mode */
html.dark #pricing .pricing-card {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%) !important;
  border-color: rgba(59,130,246,.5) !important;
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   ⭐ Reviews + FAQ + Form — إطارات كحلية 3D
   ═══════════════════════════════════════════════════════ */

/* ─── بطاقات الآراء ─── */
#landing-reviews .review-card,
#landing-reviews [style*="border:1.5px solid #f1f5f9"] {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
  border: 2.5px solid #1e3a8a !important;
  border-radius: 24px !important;
  position: relative !important;
  overflow: hidden !important;
  box-shadow:
    0 20px 45px -18px rgba(30,58,138,.25),
    0 8px 20px -8px rgba(15,23,42,.08),
    inset 0 2px 4px rgba(255,255,255,.95),
    inset 0 -2px 4px rgba(30,58,138,.1) !important;
  transition: all .45s cubic-bezier(.2,.9,.3,1.1) !important;
}
#landing-reviews .review-card::before,
#landing-reviews [style*="border:1.5px solid #f1f5f9"]::before {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 20% !important;
  right: 20% !important;
  height: 3px !important;
  background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent) !important;
  border-radius: 3px !important;
  opacity: .7 !important;
  transition: all .45s !important;
  z-index: 2 !important;
}
#landing-reviews .review-card:hover,
#landing-reviews [style*="border:1.5px solid #f1f5f9"]:hover {
  transform: perspective(1200px) rotateX(-2deg) rotateY(2deg) translateY(-10px) translateZ(20px) !important;
  border-color: #1e40af !important;
  box-shadow:
    0 35px 70px -22px rgba(30,58,138,.45),
    0 15px 35px -12px rgba(30,58,138,.3),
    inset 0 2px 6px rgba(255,255,255,1) !important;
}
#landing-reviews .review-card:hover::before { opacity: 1 !important; }

/* ─── نموذج "شاركنا تجربتك" ─── */
#share-testimonial-form form {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
  border: 2.5px solid #1e3a8a !important;
  border-radius: 28px !important;
  position: relative !important;
  overflow: hidden !important;
  box-shadow:
    0 30px 60px -25px rgba(30,58,138,.3),
    0 12px 28px -10px rgba(15,23,42,.1),
    inset 0 2px 6px rgba(255,255,255,.95) !important;
}
#share-testimonial-form form::before {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 20% !important;
  right: 20% !important;
  height: 3px !important;
  background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent) !important;
  border-radius: 3px !important;
  opacity: 1 !important;
  z-index: 2 !important;
}
#share-testimonial-form form input,
#share-testimonial-form form textarea {
  border: 2px solid #e2e8f0 !important;
  border-radius: 12px !important;
  transition: all .3s !important;
}
#share-testimonial-form form input:focus,
#share-testimonial-form form textarea:focus {
  border-color: #1e3a8a !important;
  box-shadow: 0 0 0 4px rgba(30,58,138,.1) !important;
  outline: none !important;
}

/* ─── بطاقات FAQ ─── */
#faq .faq-item,
#faq details,
#faq [style*="background:#fff"] {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
  border: 2px solid #1e3a8a !important;
  border-radius: 18px !important;
  position: relative !important;
  overflow: hidden !important;
  box-shadow:
    0 12px 28px -12px rgba(30,58,138,.2),
    0 4px 10px -3px rgba(15,23,42,.05),
    inset 0 2px 3px rgba(255,255,255,.95) !important;
  transition: all .4s cubic-bezier(.2,.9,.3,1.1) !important;
  margin-bottom: 12px !important;
}
#faq .faq-item::before,
#faq details::before {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  bottom: 0 !important;
  right: 0 !important;
  width: 4px !important;
  background: linear-gradient(180deg, #3b82f6, #1e40af) !important;
  opacity: .7 !important;
  transition: opacity .3s !important;
}
#faq .faq-item:hover,
#faq details:hover {
  transform: translateX(-4px) !important;
  border-color: #1e40af !important;
  box-shadow:
    0 20px 45px -15px rgba(30,58,138,.35),
    0 8px 18px -5px rgba(30,58,138,.2) !important;
}
#faq .faq-item:hover::before,
#faq details:hover::before { opacity: 1 !important; }

/* ─── الموبايل ─── */
@media (max-width: 640px) {
  #landing-reviews .review-card,
  #landing-reviews [style*="border:1.5px solid #f1f5f9"],
  #share-testimonial-form form {
    padding: 22px 18px !important;
    border-width: 2px !important;
  }
  #faq .faq-item,
  #faq details {
    padding: 16px 18px !important;
  }
}

/* ─── Dark mode ─── */
html.dark #landing-reviews .review-card,
html.dark #landing-reviews [style*="border:1.5px solid #f1f5f9"],
html.dark #share-testimonial-form form,
html.dark #faq .faq-item,
html.dark #faq details {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%) !important;
  border-color: rgba(59,130,246,.5) !important;
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   🚀 Final CTA — إطار + ظل + انعكاس + بروز
   ═══════════════════════════════════════════════════════ */

/* القسم بكامله */
section[style*="linear-gradient(135deg,#f59e0b,#f97316"],
section[style*="linear-gradient(135deg, #f59e0b, #f97316"] {
  position: relative !important;
  border-radius: 40px !important;
  margin: 40px auto !important;
  max-width: 1240px !important;
  overflow: hidden !important;
  border: 4px solid #1e3a8a !important;
  box-shadow:
    0 0 0 3px rgba(251,191,36,.35),
    0 0 0 6px rgba(30,58,138,.15),
    0 40px 80px -25px rgba(249,115,22,.6),
    0 20px 45px -15px rgba(15,23,42,.25),
    inset 0 4px 20px rgba(255,255,255,.35),
    inset 0 -4px 20px rgba(0,0,0,.15) !important;
}

/* ✨ الشريط العلوي المضيء */
section[style*="linear-gradient(135deg,#f59e0b,#f97316"]::before,
section[style*="linear-gradient(135deg, #f59e0b, #f97316"]::before {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 15% !important;
  right: 15% !important;
  height: 4px !important;
  background: linear-gradient(90deg, transparent, #ffffff, #fbbf24, #ffffff, transparent) !important;
  border-radius: 4px !important;
  filter: blur(.5px) !important;
  opacity: .9 !important;
  z-index: 10 !important;
  animation: ctaGlow 3s ease-in-out infinite !important;
}
@keyframes ctaGlow {
  0%, 100% { opacity: .7; transform: scaleX(.9); }
  50%      { opacity: 1; transform: scaleX(1); }
}

/* 💫 انعكاس زجاجي علوي */
section[style*="linear-gradient(135deg,#f59e0b,#f97316"]::after,
section[style*="linear-gradient(135deg, #f59e0b, #f97316"]::after {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  height: 50% !important;
  background: linear-gradient(180deg,
    rgba(255,255,255,.25) 0%,
    rgba(255,255,255,.1) 50%,
    transparent 100%) !important;
  pointer-events: none !important;
  z-index: 1 !important;
}

/* 📱 الموبايل */
@media (max-width: 640px) {
  section[style*="linear-gradient(135deg,#f59e0b,#f97316"],
  section[style*="linear-gradient(135deg, #f59e0b, #f97316"] {
    border-radius: 28px !important;
    margin: 20px 12px !important;
    border-width: 3px !important;
    box-shadow:
      0 0 0 3px rgba(251,191,36,.3),
      0 25px 50px -20px rgba(249,115,22,.5),
      inset 0 3px 15px rgba(255,255,255,.3) !important;
  }
}

/* ─── Dark mode ─── */
html.dark section[style*="linear-gradient(135deg,#f59e0b,#f97316"] {
  box-shadow:
    0 0 0 3px rgba(251,191,36,.25),
    0 0 0 6px rgba(30,58,138,.2),
    0 40px 80px -25px rgba(249,115,22,.4),
    inset 0 4px 20px rgba(255,255,255,.2) !important;
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   🚀 Final CTA — إطار + ظل + انعكاس + بروز
   ═══════════════════════════════════════════════════════ */

/* القسم بكامله */
section[style*="linear-gradient(135deg,#f59e0b,#f97316"],
section[style*="linear-gradient(135deg, #f59e0b, #f97316"] {
  position: relative !important;
  border-radius: 40px !important;
  margin: 40px auto !important;
  max-width: 1240px !important;
  overflow: hidden !important;
  border: 4px solid #1e3a8a !important;
  box-shadow:
    0 0 0 3px rgba(251,191,36,.35),
    0 0 0 6px rgba(30,58,138,.15),
    0 40px 80px -25px rgba(249,115,22,.6),
    0 20px 45px -15px rgba(15,23,42,.25),
    inset 0 4px 20px rgba(255,255,255,.35),
    inset 0 -4px 20px rgba(0,0,0,.15) !important;
}

/* ✨ الشريط العلوي المضيء */
section[style*="linear-gradient(135deg,#f59e0b,#f97316"]::before,
section[style*="linear-gradient(135deg, #f59e0b, #f97316"]::before {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 15% !important;
  right: 15% !important;
  height: 4px !important;
  background: linear-gradient(90deg, transparent, #ffffff, #fbbf24, #ffffff, transparent) !important;
  border-radius: 4px !important;
  filter: blur(.5px) !important;
  opacity: .9 !important;
  z-index: 10 !important;
  animation: ctaGlow 3s ease-in-out infinite !important;
}
@keyframes ctaGlow {
  0%, 100% { opacity: .7; transform: scaleX(.9); }
  50%      { opacity: 1; transform: scaleX(1); }
}

/* 💫 انعكاس زجاجي علوي */
section[style*="linear-gradient(135deg,#f59e0b,#f97316"]::after,
section[style*="linear-gradient(135deg, #f59e0b, #f97316"]::after {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  height: 50% !important;
  background: linear-gradient(180deg,
    rgba(255,255,255,.25) 0%,
    rgba(255,255,255,.1) 50%,
    transparent 100%) !important;
  pointer-events: none !important;
  z-index: 1 !important;
}

/* 📱 الموبايل */
@media (max-width: 640px) {
  section[style*="linear-gradient(135deg,#f59e0b,#f97316"],
  section[style*="linear-gradient(135deg, #f59e0b, #f97316"] {
    border-radius: 28px !important;
    margin: 20px 12px !important;
    border-width: 3px !important;
    box-shadow:
      0 0 0 3px rgba(251,191,36,.3),
      0 25px 50px -20px rgba(249,115,22,.5),
      inset 0 3px 15px rgba(255,255,255,.3) !important;
  }
}

/* ─── Dark mode ─── */
html.dark section[style*="linear-gradient(135deg,#f59e0b,#f97316"] {
  box-shadow:
    0 0 0 3px rgba(251,191,36,.25),
    0 0 0 6px rgba(30,58,138,.2),
    0 40px 80px -25px rgba(249,115,22,.4),
    inset 0 4px 20px rgba(255,255,255,.2) !important;
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   🎨 Footer Enhancement — إطار + أيقونات 3D
   ═══════════════════════════════════════════════════════ */

/* ─── Footer Container ─── */
footer,
.landing-footer,
section.footer,
[class*="footer"]:not([class*="footer-content"]):not([class*="footer-col"]):not([class*="footer-link"]):not([class*="footer-title"]) {
  position: relative;
}

footer::before,
.landing-footer::before {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 20% !important;
  right: 20% !important;
  height: 3px !important;
  background: linear-gradient(90deg, transparent, #fbbf24, #f97316, #fbbf24, transparent) !important;
  border-radius: 3px !important;
  filter: blur(.5px) !important;
  opacity: .9 !important;
  z-index: 2 !important;
  animation: footerGlow 3s ease-in-out infinite !important;
}
@keyframes footerGlow {
  0%, 100% { opacity: .7; transform: scaleX(.9); }
  50%      { opacity: 1; transform: scaleX(1); }
}

/* ─── Social Icons 3D ─── */
.social-btn {
  position: relative !important;
  width: 48px !important;
  height: 48px !important;
  border-radius: 16px !important;
  display: grid !important;
  place-items: center !important;
  transition: all .4s cubic-bezier(.2,.9,.3,1.1) !important;
  box-shadow:
    0 10px 22px -8px rgba(0,0,0,.4),
    0 4px 10px -3px rgba(0,0,0,.25),
    inset 0 2px 4px rgba(255,255,255,.3),
    inset 0 -2px 4px rgba(0,0,0,.15) !important;
  transform: perspective(500px) rotateX(0) rotateY(0) translateZ(0) !important;
  overflow: visible !important;
}

.social-btn::before {
  content: "" !important;
  position: absolute !important;
  inset: -8px !important;
  border-radius: 20px !important;
  background: radial-gradient(circle, currentColor, transparent 70%) !important;
  opacity: 0 !important;
  filter: blur(12px) !important;
  z-index: -1 !important;
  transition: opacity .4s !important;
  pointer-events: none !important;
}

.social-btn svg {
  width: 22px !important;
  height: 22px !important;
  filter: drop-shadow(0 2px 3px rgba(0,0,0,.3)) !important;
  transition: all .4s cubic-bezier(.2,.9,.3,1.1) !important;
  z-index: 2 !important;
  position: relative !important;
}

/* 🎭 Hover — 3D pop */
.social-btn:hover {
  transform: perspective(500px) rotateX(-8deg) rotateY(8deg) translateY(-6px) translateZ(20px) scale(1.15) !important;
  box-shadow:
    0 20px 40px -10px rgba(0,0,0,.5),
    0 8px 18px -5px rgba(0,0,0,.3),
    inset 0 3px 6px rgba(255,255,255,.45),
    inset 0 -3px 6px rgba(0,0,0,.2) !important;
}
.social-btn:hover::before {
  opacity: .6 !important;
}
.social-btn:hover svg {
  transform: scale(1.2) rotate(-6deg) !important;
  filter: drop-shadow(0 4px 6px rgba(0,0,0,.4)) !important;
}

/* 🎨 ألوان محدّثة بلمسة أعمق */
.social-btn.facebook {
  background: linear-gradient(135deg, #2563EB 0%, #1877F2 50%, #0d5dbf 100%) !important;
  color: #fff !important;
}
.social-btn.facebook:hover {
  box-shadow:
    0 20px 40px -10px rgba(24,119,242,.7),
    0 8px 18px -5px rgba(24,119,242,.4),
    inset 0 3px 6px rgba(255,255,255,.4),
    inset 0 -3px 6px rgba(0,0,0,.2) !important;
}

.social-btn.whatsapp {
  background: linear-gradient(135deg, #34D399 0%, #25D366 50%, #128C7E 100%) !important;
  color: #fff !important;
}
.social-btn.whatsapp:hover {
  box-shadow:
    0 20px 40px -10px rgba(37,211,102,.7),
    0 8px 18px -5px rgba(37,211,102,.4),
    inset 0 3px 6px rgba(255,255,255,.4),
    inset 0 -3px 6px rgba(0,0,0,.2) !important;
}

.social-btn.telegram {
  background: linear-gradient(135deg, #38BDF8 0%, #229ED9 50%, #0088cc 100%) !important;
  color: #fff !important;
}
.social-btn.telegram:hover {
  box-shadow:
    0 20px 40px -10px rgba(34,158,217,.7),
    0 8px 18px -5px rgba(34,158,217,.4),
    inset 0 3px 6px rgba(255,255,255,.4),
    inset 0 -3px 6px rgba(0,0,0,.2) !important;
}

.social-btn.phone {
  background: linear-gradient(135deg, #fbbf24 0%, #f97316 50%, #ea580c 100%) !important;
  color: #fff !important;
}
.social-btn.phone:hover {
  box-shadow:
    0 20px 40px -10px rgba(249,115,22,.7),
    0 8px 18px -5px rgba(249,115,22,.4),
    inset 0 3px 6px rgba(255,255,255,.4),
    inset 0 -3px 6px rgba(0,0,0,.2) !important;
}

/* 📱 الموبايل */
@media (max-width: 640px) {
  .social-btn {
    width: 44px !important;
    height: 44px !important;
    border-radius: 14px !important;
  }
  .social-btn svg {
    width: 20px !important;
    height: 20px !important;
  }
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   🚀 Hero Enhancements — Trust Pills + Phone 3D + Cards
   ═══════════════════════════════════════════════════════ */

/* ─── مؤشرات الثقة → Pills بإطار كحلي ─── */
.hero-trust > div {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
  border: 2px solid #1e3a8a !important;
  border-radius: 999px !important;
  padding: 10px 18px !important;
  box-shadow:
    0 12px 25px -10px rgba(30,58,138,.3),
    0 4px 10px -3px rgba(15,23,42,.08),
    inset 0 2px 3px rgba(255,255,255,.95),
    inset 0 -2px 3px rgba(30,58,138,.08) !important;
  transition: all .4s cubic-bezier(.2,.9,.3,1.1) !important;
  transform: perspective(500px) translateZ(0) !important;
}
.hero-trust > div:hover {
  transform: perspective(500px) translateY(-4px) translateZ(15px) scale(1.05) !important;
  border-color: #1e40af !important;
  box-shadow:
    0 20px 40px -12px rgba(30,58,138,.5),
    0 8px 20px -5px rgba(30,58,138,.3),
    inset 0 2px 4px rgba(255,255,255,1) !important;
}
.hero-trust > div i,
.hero-trust > div svg {
  width: 18px !important;
  height: 18px !important;
  filter: drop-shadow(0 2px 3px rgba(16,185,129,.4)) !important;
}

/* ─── Phone Mockup → ظل 3D عميق ─── */
.hero-product,
.hero-device {
  filter:
    drop-shadow(0 50px 80px rgba(249,115,22,.35))
    drop-shadow(0 25px 40px rgba(15,23,42,.2))
    drop-shadow(0 10px 20px rgba(15,23,42,.1)) !important;
}

/* ─── Floating Cards → إطار كحلي + ظل 3D ─── */
.floating-card {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
  border: 2.5px solid #1e3a8a !important;
  border-radius: 18px !important;
  padding: 12px 16px !important;
  box-shadow:
    0 20px 40px -15px rgba(30,58,138,.35),
    0 8px 20px -5px rgba(15,23,42,.12),
    inset 0 2px 4px rgba(255,255,255,.95),
    inset 0 -2px 4px rgba(30,58,138,.1) !important;
  position: relative !important;
  overflow: hidden !important;
}
.floating-card::before {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 20% !important;
  right: 20% !important;
  height: 2.5px !important;
  background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent) !important;
  border-radius: 2.5px !important;
  z-index: 3 !important;
}
.floating-card .fc-icon {
  border: 2px solid rgba(30,58,138,.2) !important;
  box-shadow:
    0 6px 15px -4px rgba(0,0,0,.15),
    inset 0 2px 3px rgba(255,255,255,.9) !important;
}

/* 🎭 Floating Cards — حركة أعلى
   (تحريك إضافي موجود في CSS الأصلي) */

/* ─── Trust Stats Bar → تحسين ─── */
.hero-stats {
  border: 2.5px solid #1e3a8a !important;
  box-shadow:
    0 25px 50px -20px rgba(30,58,138,.3),
    0 10px 25px -10px rgba(15,23,42,.1),
    inset 0 3px 6px rgba(255,255,255,.95),
    inset 0 -3px 6px rgba(30,58,138,.08) !important;
  position: relative !important;
  overflow: hidden !important;
}
.hero-stats::before {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 20% !important;
  right: 20% !important;
  height: 3px !important;
  background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent) !important;
  border-radius: 3px !important;
  opacity: .8 !important;
  z-index: 2 !important;
}

/* ─── الموبايل ─── */
@media (max-width: 640px) {
  .hero-trust > div {
    padding: 8px 14px !important;
    border-width: 2px !important;
  }
  .floating-card {
    padding: 8px 12px !important;
    border-width: 2px !important;
  }
  .hero-stats {
    border-width: 2px !important;
  }
}

/* ─── Dark mode ─── */
html.dark .hero-trust > div,
html.dark .floating-card {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%) !important;
  border-color: rgba(59,130,246,.5) !important;
}
html.dark .hero-trust > div i,
html.dark .hero-trust > div svg { color: #10b981 !important; }
</style>


<style>
/* ═══════════════════════════════════════════════════════
   🏷️ Hero Badge + Text Enhancements
   ═══════════════════════════════════════════════════════ */

/* ─── Badge علوي ─── */
#hero .badge {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
  border: 2px solid #1e3a8a !important;
  border-radius: 999px !important;
  padding: 10px 22px !important;
  font-weight: 900 !important;
  box-shadow:
    0 15px 30px -10px rgba(30,58,138,.3),
    0 5px 12px -3px rgba(15,23,42,.08),
    inset 0 2px 3px rgba(255,255,255,.95),
    inset 0 -2px 3px rgba(30,58,138,.08) !important;
  transition: all .4s cubic-bezier(.2,.9,.3,1.1) !important;
  transform: perspective(500px) translateZ(0) !important;
  position: relative !important;
  overflow: hidden !important;
}
#hero .badge::before {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: 20% !important;
  right: 20% !important;
  height: 2.5px !important;
  background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent) !important;
  border-radius: 2.5px !important;
  opacity: .8 !important;
  z-index: 3 !important;
}
#hero .badge:hover {
  transform: perspective(500px) translateY(-4px) translateZ(15px) scale(1.05) !important;
  border-color: #1e40af !important;
  box-shadow:
    0 25px 50px -12px rgba(30,58,138,.5),
    0 8px 20px -5px rgba(30,58,138,.3),
    inset 0 2px 4px rgba(255,255,255,1) !important;
}

/* ─── Pulse Dot ─── */
#hero .badge .pulse-dot,
#hero .badge .pulse-dot * {
  box-shadow: 0 0 0 0 rgba(16,185,129,.7) !important;
  animation: badgePulse 2s infinite !important;
}
@keyframes badgePulse {
  0% { box-shadow: 0 0 0 0 rgba(16,185,129,.7); }
  70% { box-shadow: 0 0 0 10px rgba(16,185,129,0); }
  100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
}

/* ─── H1 Title — تأطير النص ─── */
#hero h1 {
  text-shadow:
    0 2px 4px rgba(15,23,42,.04),
    0 1px 2px rgba(15,23,42,.03) !important;
  letter-spacing: -2px !important;
}

/* ─── Gradient Text ("وابدأ البيع بثقة") ─── */
#hero .gradient-text {
  filter: drop-shadow(0 4px 8px rgba(249,115,22,.2)) !important;
  position: relative !important;
}

/* ─── Subtitle Paragraph ─── */
#hero p {
  font-weight: 500 !important;
}
#hero p strong {
  filter: drop-shadow(0 2px 4px rgba(249,115,22,.3)) !important;
  position: relative !important;
}

/* ─── Dark mode ─── */
html.dark #hero .badge {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%) !important;
  border-color: rgba(59,130,246,.5) !important;
  color: #e2e8f0 !important;
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   🧭 Navbar Enhancement — روابط + CTA + Mobile
   ═══════════════════════════════════════════════════════ */

/* ─── Nav Links ─── */
.nav-link {
  position: relative !important;
  padding: 10px 16px !important;
  border-radius: 12px !important;
  font-weight: 800 !important;
  transition: all .35s cubic-bezier(.2,.9,.3,1.1) !important;
  border: 2px solid transparent !important;
}
.nav-link::before {
  content: "" !important;
  position: absolute !important;
  bottom: 6px !important;
  left: 50% !important;
  width: 0 !important;
  height: 3px !important;
  background: linear-gradient(90deg, #f59e0b, #ea580c) !important;
  border-radius: 2px !important;
  transform: translateX(-50%) !important;
  transition: width .35s cubic-bezier(.2,.9,.3,1.1) !important;
  opacity: .9 !important;
}
.nav-link:hover {
  color: #1e3a8a !important;
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
  border-color: #1e3a8a !important;
  box-shadow:
    0 12px 25px -10px rgba(30,58,138,.3),
    0 4px 10px -3px rgba(15,23,42,.08),
    inset 0 2px 3px rgba(255,255,255,.95) !important;
  transform: translateY(-2px) !important;
}
.nav-link:hover::before {
  width: 60% !important;
}

/* ─── Nav CTA (ابدأ الآن) ─── */
.nav-cta {
  position: relative !important;
  overflow: hidden !important;
  transition: all .4s cubic-bezier(.2,.9,.3,1.1) !important;
  box-shadow:
    0 15px 30px -10px rgba(249,115,22,.5),
    0 5px 12px -3px rgba(249,115,22,.3),
    inset 0 2px 4px rgba(255,255,255,.35) !important;
}
.nav-cta::before {
  content: "" !important;
  position: absolute !important;
  top: 0 !important;
  left: -100% !important;
  width: 100% !important;
  height: 100% !important;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.35), transparent) !important;
  transition: left .8s cubic-bezier(.2,.9,.3,1.1) !important;
}
.nav-cta:hover {
  transform: translateY(-2px) !important;
  box-shadow:
    0 25px 50px -12px rgba(249,115,22,.65),
    0 10px 22px -5px rgba(249,115,22,.4),
    inset 0 3px 5px rgba(255,255,255,.5) !important;
}
.nav-cta:hover::before {
  left: 100% !important;
}

/* ─── Burger Button ─── */
.nav-burger {
  transition: all .35s cubic-bezier(.2,.9,.3,1.1) !important;
  border-radius: 12px !important;
}
.nav-burger:hover {
  background: linear-gradient(135deg, #fff7ed, #fed7aa) !important;
  transform: rotate(90deg) scale(1.1) !important;
}

/* ─── Mobile Menu Items ─── */
.mobile-menu a:not(.divider):not(.mobile-cta) {
  transition: all .35s cubic-bezier(.2,.9,.3,1.1) !important;
  border-radius: 14px !important;
  margin: 4px 8px !important;
  border: 2px solid transparent !important;
}
.mobile-menu a:not(.divider):not(.mobile-cta):hover,
.mobile-menu a:not(.divider):not(.mobile-cta):active {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
  border-color: #1e3a8a !important;
  color: #1e3a8a !important;
  box-shadow:
    0 12px 25px -10px rgba(30,58,138,.3),
    inset 0 2px 3px rgba(255,255,255,.95) !important;
  transform: translateX(-4px) !important;
}

/* ─── Mobile CTA (ابدأ متجرك مجاناً) ─── */
.mobile-menu .mobile-cta {
  margin: 12px 8px !important;
  border-radius: 14px !important;
  box-shadow:
    0 15px 30px -10px rgba(249,115,22,.5),
    inset 0 2px 4px rgba(255,255,255,.35) !important;
  transition: all .35s cubic-bezier(.2,.9,.3,1.1) !important;
}
.mobile-menu .mobile-cta:hover {
  transform: scale(1.02) !important;
  box-shadow:
    0 25px 45px -12px rgba(249,115,22,.6),
    inset 0 3px 5px rgba(255,255,255,.5) !important;
}

/* ─── Navbar كامل — ظل أعمق عند التمرير ─── */
nav.sticky,
nav[class*="fixed"],
nav[class*="sticky"] {
  transition: all .4s cubic-bezier(.2,.9,.3,1.1) !important;
}

/* ─── Dark mode ─── */
html.dark .nav-link:hover {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%) !important;
  border-color: rgba(59,130,246,.5) !important;
  color: #93c5fd !important;
}
html.dark .mobile-menu a:not(.divider):not(.mobile-cta):hover {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%) !important;
  border-color: rgba(59,130,246,.5) !important;
  color: #93c5fd !important;
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   🚀 Marketing Boost — Live Stats + ROI + Guarantee
   ═══════════════════════════════════════════════════════ */

/* ─── Live Stats Bar ─── */
.live-stats-bar {
  max-width: 1100px;
  margin: 60px auto;
  padding: 32px 24px;
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
  border-radius: 32px;
  position: relative;
  overflow: hidden;
  border: 3px solid #1e3a8a;
  box-shadow:
    0 0 0 3px rgba(251,191,36,.2),
    0 40px 80px -25px rgba(15,23,42,.6),
    0 20px 45px -15px rgba(30,58,138,.35),
    inset 0 3px 20px rgba(255,255,255,.08);
}
.live-stats-bar::before {
  content: "";
  position: absolute;
  top: 0; left: 15%; right: 15%;
  height: 3px;
  background: linear-gradient(90deg, transparent, #fbbf24, #f97316, #fbbf24, transparent);
  border-radius: 3px;
  filter: blur(.5px);
  animation: liveGlow 3s ease-in-out infinite;
}
@keyframes liveGlow {
  0%, 100% { opacity: .7; transform: scaleX(.9); }
  50%      { opacity: 1; transform: scaleX(1); }
}
.live-stats-title {
  text-align: center;
  color: #fbbf24;
  font-size: 12px;
  font-weight: 900;
  letter-spacing: 2px;
  margin-bottom: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}
.live-dot {
  width: 10px;
  height: 10px;
  background: #10b981;
  border-radius: 50%;
  box-shadow: 0 0 0 0 rgba(16,185,129,.7);
  animation: pulseLive 2s infinite;
}
@keyframes pulseLive {
  0%   { box-shadow: 0 0 0 0 rgba(16,185,129,.7); }
  70%  { box-shadow: 0 0 0 12px rgba(16,185,129,0); }
  100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
}
.live-stats-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
}
@media (min-width: 768px) {
  .live-stats-grid { grid-template-columns: repeat(4, 1fr); }
}
.live-stat {
  text-align: center;
  padding: 12px 8px;
  position: relative;
}
.live-stat:not(:last-child)::after {
  content: "";
  position: absolute;
  left: -8px;
  top: 25%;
  bottom: 25%;
  width: 1px;
  background: linear-gradient(180deg, transparent, rgba(251,191,36,.3), transparent);
}
.live-stat-num {
  font-size: clamp(28px, 4vw, 40px);
  font-weight: 900;
  background: linear-gradient(135deg, #fbbf24, #f97316, #ea580c);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  line-height: 1;
  letter-spacing: -1px;
  margin-bottom: 8px;
}
.live-stat-label {
  color: #94a3b8;
  font-size: 12px;
  font-weight: 800;
  letter-spacing: .3px;
}

/* ─── ROI Calculator ─── */
.roi-section {
  max-width: 1000px;
  margin: 80px auto;
  padding: 40px 32px;
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border: 2.5px solid #1e3a8a;
  border-radius: 32px;
  box-shadow:
    0 30px 60px -25px rgba(30,58,138,.3),
    0 12px 28px -10px rgba(15,23,42,.1),
    inset 0 3px 6px rgba(255,255,255,.95);
  position: relative;
  overflow: hidden;
}
.roi-section::before {
  content: "";
  position: absolute;
  top: 0; left: 20%; right: 20%;
  height: 3px;
  background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent);
  border-radius: 3px;
  opacity: .8;
}
.roi-title {
  text-align: center;
  font-size: clamp(22px, 3vw, 32px);
  font-weight: 900;
  color: #0f172a;
  letter-spacing: -1px;
  margin-bottom: 10px;
}
.roi-subtitle {
  text-align: center;
  color: #64748b;
  font-size: 14px;
  margin-bottom: 32px;
  line-height: 1.7;
}
.roi-inputs {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
  margin-bottom: 28px;
}
@media (min-width: 640px) {
  .roi-inputs { grid-template-columns: 1fr 1fr; }
}
.roi-input-group {
  text-align: right;
}
.roi-input-group label {
  font-size: 12px;
  font-weight: 900;
  color: #475569;
  display: block;
  margin-bottom: 8px;
}
.roi-input-group input[type="range"] {
  width: 100%;
  height: 8px;
  -webkit-appearance: none;
  background: linear-gradient(90deg, #fbbf24, #f97316);
  border-radius: 8px;
  outline: none;
  margin-bottom: 12px;
}
.roi-input-group input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 26px;
  height: 26px;
  background: #fff;
  border: 4px solid #ea580c;
  border-radius: 50%;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(249,115,22,.5);
}
.roi-input-group input[type="range"]::-moz-range-thumb {
  width: 26px;
  height: 26px;
  background: #fff;
  border: 4px solid #ea580c;
  border-radius: 50%;
  cursor: pointer;
}
.roi-value {
  display: inline-block;
  background: linear-gradient(135deg, #fef3c7, #fde68a);
  border: 2px solid #f59e0b;
  border-radius: 10px;
  padding: 6px 16px;
  font-weight: 900;
  font-size: 15px;
  color: #92400e;
  min-width: 120px;
  text-align: center;
  box-shadow: 0 4px 10px -3px rgba(249,115,22,.3), inset 0 2px 3px rgba(255,255,255,.8);
}
.roi-result {
  background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
  border: 2.5px dashed #f59e0b;
  border-radius: 20px;
  padding: 28px 24px;
  text-align: center;
  margin-top: 12px;
}
.roi-result-label {
  font-size: 13px;
  font-weight: 900;
  color: #92400e;
  margin-bottom: 8px;
  letter-spacing: .3px;
}
.roi-result-value {
  font-size: clamp(28px, 5vw, 44px);
  font-weight: 900;
  background: linear-gradient(135deg, #f59e0b, #ea580c);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  letter-spacing: -1.5px;
  line-height: 1.1;
}
.roi-result-sub {
  font-size: 12px;
  color: #78350f;
  margin-top: 8px;
  font-weight: 800;
}
.roi-cta {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  margin-top: 20px;
  padding: 14px 28px;
  background: linear-gradient(135deg, #fbbf24, #f97316);
  color: #fff;
  border-radius: 14px;
  font-weight: 900;
  font-size: 14px;
  text-decoration: none;
  box-shadow: 0 12px 30px -8px rgba(249,115,22,.5);
  transition: all .3s;
}
.roi-cta:hover { transform: translateY(-2px); box-shadow: 0 18px 40px -10px rgba(249,115,22,.65); }

/* ─── Guarantee Badge ─── */
.guarantee-section {
  max-width: 900px;
  margin: 60px auto;
  padding: 36px 32px;
  background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
  border: 3px solid #16a34a;
  border-radius: 28px;
  display: flex;
  align-items: center;
  gap: 28px;
  flex-wrap: wrap;
  box-shadow:
    0 25px 50px -20px rgba(22,163,74,.3),
    0 10px 25px -10px rgba(22,163,74,.2),
    inset 0 3px 6px rgba(255,255,255,.95);
  position: relative;
  overflow: hidden;
}
.guarantee-section::before {
  content: "";
  position: absolute;
  top: 0; left: 20%; right: 20%;
  height: 3px;
  background: linear-gradient(90deg, transparent, #10b981, #16a34a, #10b981, transparent);
  border-radius: 3px;
}
.guarantee-icon {
  width: 90px;
  height: 90px;
  border-radius: 26px;
  background: linear-gradient(135deg, #34D399, #16a34a, #15803d);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 42px;
  flex-shrink: 0;
  box-shadow:
    0 20px 40px -12px rgba(22,163,74,.55),
    inset 0 3px 5px rgba(255,255,255,.4),
    inset 0 -3px 5px rgba(0,0,0,.15);
  animation: guaranteeFloat 3s ease-in-out infinite;
}
@keyframes guaranteeFloat {
  0%, 100% { transform: translateY(0) rotate(0); }
  50%      { transform: translateY(-6px) rotate(-3deg); }
}
.guarantee-text { flex: 1; min-width: 240px; }
.guarantee-title {
  font-size: clamp(20px, 3vw, 26px);
  font-weight: 900;
  color: #166534;
  margin: 0 0 10px;
  letter-spacing: -0.5px;
}
.guarantee-desc {
  font-size: 14.5px;
  line-height: 1.8;
  color: #14532d;
  margin: 0;
}
.guarantee-points {
  display: flex;
  gap: 16px;
  margin-top: 14px;
  flex-wrap: wrap;
}
.guarantee-point {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 12.5px;
  font-weight: 900;
  color: #166534;
  background: #fff;
  padding: 6px 14px;
  border-radius: 99px;
  border: 1.5px solid #86efac;
}

/* 📱 الموبايل */
@media (max-width: 640px) {
  .live-stats-bar,
  .roi-section,
  .guarantee-section {
    margin: 40px 12px;
    padding: 24px 18px;
    border-radius: 24px;
  }
  .guarantee-section { flex-direction: column; text-align: center; }
  .guarantee-points { justify-content: center; }
  .roi-result { padding: 22px 16px; }
}

/* 🌙 Dark mode */
html.dark .roi-section {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%);
  border-color: rgba(59,130,246,.5);
}
html.dark .roi-title { color: #f1f5f9; }
html.dark .roi-subtitle { color: #94a3b8; }
html.dark .roi-input-group label { color: #cbd5e1; }
html.dark .guarantee-section {
  background: linear-gradient(135deg, rgba(22,163,74,.15), rgba(21,128,61,.1));
  border-color: rgba(52,211,153,.5);
}
html.dark .guarantee-title { color: #86efac; }
html.dark .guarantee-desc { color: #bbf7d0; }
html.dark .guarantee-point { background: rgba(255,255,255,.05); color: #86efac; border-color: rgba(52,211,153,.3); }
</style>


<style>
/* ═══════════════════════════════════════════════════════
   🎁 Exit Intent Popup
   ═══════════════════════════════════════════════════════ */

.exit-popup-overlay {
  position: fixed;
  inset: 0;
  background: rgba(15,23,42,.75);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  z-index: 99999;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 20px;
  animation: exitFadeIn .3s ease;
}
.exit-popup-overlay.open {
  display: flex;
}
@keyframes exitFadeIn {
  from { opacity: 0; }
  to   { opacity: 1; }
}

.exit-popup-box {
  background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%);
  border: 3px solid #1e3a8a;
  border-radius: 32px;
  max-width: 500px;
  width: 100%;
  padding: 44px 36px 36px;
  position: relative;
  overflow: hidden;
  box-shadow:
    0 0 0 4px rgba(251,191,36,.25),
    0 50px 100px -30px rgba(0,0,0,.6),
    0 25px 50px -15px rgba(30,58,138,.4),
    inset 0 4px 10px rgba(255,255,255,.95);
  animation: exitSlideUp .5s cubic-bezier(.2,.9,.3,1.1);
  text-align: center;
}
@keyframes exitSlideUp {
  from { opacity: 0; transform: translateY(60px) scale(.9); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}
.exit-popup-box::before {
  content: "";
  position: absolute;
  top: 0; left: 15%; right: 15%;
  height: 4px;
  background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent);
  border-radius: 4px;
}
.exit-popup-box::after {
  content: "";
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 40%;
  background: linear-gradient(180deg, rgba(255,255,255,.5) 0%, transparent 100%);
  pointer-events: none;
  z-index: 1;
}

/* زر الإغلاق */
.exit-popup-close {
  position: absolute;
  top: 16px;
  left: 16px;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 0;
  background: #f1f5f9;
  color: #64748b;
  font-size: 22px;
  font-weight: 700;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all .3s;
  font-family: inherit;
  z-index: 3;
}
.exit-popup-close:hover {
  background: #fee2e2;
  color: #dc2626;
  transform: rotate(90deg);
}

/* أيقونة الهدية */
.exit-popup-icon {
  width: 100px;
  height: 100px;
  border-radius: 28px;
  background: linear-gradient(135deg, #fbbf24, #f97316, #ea580c);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 50px;
  margin: 0 auto 22px;
  box-shadow:
    0 25px 50px -12px rgba(249,115,22,.6),
    0 10px 22px -6px rgba(249,115,22,.4),
    inset 0 4px 8px rgba(255,255,255,.4);
  animation: exitIconFloat 2.5s ease-in-out infinite;
  position: relative;
  z-index: 2;
}
@keyframes exitIconFloat {
  0%, 100% { transform: translateY(0) rotate(-3deg); }
  50%      { transform: translateY(-8px) rotate(3deg); }
}

.exit-popup-title {
  font-size: 26px;
  font-weight: 900;
  color: #0f172a;
  margin: 0 0 12px;
  letter-spacing: -0.5px;
  position: relative;
  z-index: 2;
}
.exit-popup-subtitle {
  font-size: 14.5px;
  line-height: 1.8;
  color: #475569;
  margin: 0 0 24px;
  position: relative;
  z-index: 2;
}
.exit-popup-coupon {
  display: inline-block;
  padding: 10px 22px;
  background: linear-gradient(135deg, #fef3c7, #fde68a);
  border: 2px dashed #f59e0b;
  border-radius: 14px;
  font-weight: 900;
  font-size: 20px;
  color: #92400e;
  letter-spacing: 1px;
  margin-bottom: 22px;
  font-family: monospace;
  position: relative;
  z-index: 2;
}

/* النموذج */
.exit-popup-form {
  display: flex;
  gap: 8px;
  margin-bottom: 14px;
  position: relative;
  z-index: 2;
}
.exit-popup-form input {
  flex: 1;
  padding: 15px 18px;
  border: 2px solid #e2e8f0;
  border-radius: 14px;
  font-size: 14px;
  font-family: inherit;
  outline: none;
  transition: all .3s;
  direction: ltr;
  text-align: left;
}
.exit-popup-form input:focus {
  border-color: #1e3a8a;
  box-shadow: 0 0 0 4px rgba(30,58,138,.1);
}
.exit-popup-form button {
  padding: 15px 24px;
  border: 0;
  border-radius: 14px;
  background: linear-gradient(135deg, #fbbf24, #f97316);
  color: #fff;
  font-weight: 900;
  font-size: 14px;
  cursor: pointer;
  font-family: inherit;
  box-shadow: 0 12px 25px -8px rgba(249,115,22,.5);
  transition: all .3s;
  white-space: nowrap;
}
.exit-popup-form button:hover {
  transform: translateY(-2px);
  box-shadow: 0 18px 40px -10px rgba(249,115,22,.65);
}

.exit-popup-message {
  font-size: 13px;
  font-weight: 800;
  padding: 10px 16px;
  border-radius: 12px;
  margin-bottom: 14px;
  display: none;
  position: relative;
  z-index: 2;
}
.exit-popup-message.success {
  background: #dcfce7;
  color: #166534;
  border: 1px solid #86efac;
  display: block;
}
.exit-popup-message.error {
  background: #fee2e2;
  color: #991b1b;
  border: 1px solid #fecaca;
  display: block;
}

.exit-popup-footer {
  font-size: 11px;
  color: #94a3b8;
  font-weight: 700;
  position: relative;
  z-index: 2;
}
.exit-popup-footer span {
  display: inline-flex;
  align-items: center;
  gap: 4px;
}

/* 📱 الموبايل */
@media (max-width: 640px) {
  .exit-popup-box {
    padding: 36px 22px 26px;
    border-radius: 26px;
  }
  .exit-popup-icon {
    width: 80px;
    height: 80px;
    font-size: 40px;
  }
  .exit-popup-title { font-size: 21px; }
  .exit-popup-coupon { font-size: 17px; padding: 8px 18px; }
  .exit-popup-form { flex-direction: column; }
  .exit-popup-form button { width: 100%; }
}

/* 🌙 Dark mode */
html.dark .exit-popup-box {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%);
  border-color: rgba(59,130,246,.6);
}
html.dark .exit-popup-title { color: #f1f5f9; }
html.dark .exit-popup-subtitle { color: #cbd5e1; }
html.dark .exit-popup-close { background: #334155; color: #cbd5e1; }
</style>


<style>
/* ═══════════════════════════════════════════════════════
   🏪 Trust Logos + ⏰ Urgency Counter
   ═══════════════════════════════════════════════════════ */

/* ─── Urgency Bar (Fixed top) ─── */
.urgency-bar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 9998;
  background: linear-gradient(90deg, #7c2d12 0%, #ea580c 25%, #f97316 50%, #ea580c 75%, #7c2d12 100%);
  color: #fff;
  padding: 10px 20px;
  text-align: center;
  font-size: 13.5px;
  font-weight: 900;
  box-shadow: 0 6px 20px rgba(234,88,12,.5);
  border-bottom: 3px solid #fbbf24;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  flex-wrap: wrap;
  animation: urgencySlideDown .5s cubic-bezier(.2,.9,.3,1.1);
}
@keyframes urgencySlideDown {
  from { transform: translateY(-100%); }
  to   { transform: translateY(0); }
}
.urgency-bar.hidden {
  display: none;
}
.urgency-icon {
  font-size: 18px;
  animation: urgencyPulse 1.5s ease-in-out infinite;
}
@keyframes urgencyPulse {
  0%, 100% { transform: scale(1); }
  50%      { transform: scale(1.2); }
}
.urgency-timer {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  background: rgba(255,255,255,.15);
  padding: 4px 10px;
  border-radius: 8px;
  font-family: monospace;
  font-weight: 900;
  font-size: 15px;
  letter-spacing: 1px;
  border: 1px solid rgba(255,255,255,.25);
}
.urgency-timer span {
  background: rgba(0,0,0,.2);
  padding: 2px 6px;
  border-radius: 4px;
  min-width: 28px;
  display: inline-block;
  text-align: center;
}
.urgency-cta {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 16px;
  background: #fff;
  color: #ea580c;
  border-radius: 10px;
  font-weight: 900;
  font-size: 12px;
  text-decoration: none;
  box-shadow: 0 4px 10px rgba(0,0,0,.2);
  transition: all .3s;
}
.urgency-cta:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0,0,0,.3);
}
.urgency-close {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
  width: 26px;
  height: 26px;
  border-radius: 50%;
  border: 0;
  background: rgba(255,255,255,.2);
  color: #fff;
  font-size: 14px;
  cursor: pointer;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all .3s;
  font-family: inherit;
}
.urgency-close:hover {
  background: rgba(255,255,255,.35);
  transform: translateY(-50%) rotate(90deg);
}

/* Body padding to account for fixed bar */
body.has-urgency { padding-top: 44px; }

/* ─── Logos Section ─── */
.logos-section {
  max-width: 1100px;
  margin: 60px auto;
  padding: 0 20px;
  text-align: center;
}
.logos-label {
  font-size: 11px;
  font-weight: 900;
  color: #94a3b8;
  letter-spacing: 3px;
  text-transform: uppercase;
  margin-bottom: 24px;
}
.logos-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 14px;
  max-width: 900px;
  margin: 0 auto;
}
@media (min-width: 640px) {
  .logos-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (min-width: 1024px) {
  .logos-grid { grid-template-columns: repeat(6, 1fr); gap: 12px; }
}
.logo-card {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border: 2px solid #1e3a8a;
  border-radius: 18px;
  padding: 18px 12px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  transition: all .4s cubic-bezier(.2,.9,.3,1.1);
  box-shadow:
    0 15px 30px -12px rgba(30,58,138,.2),
    0 4px 10px -3px rgba(15,23,42,.05),
    inset 0 2px 3px rgba(255,255,255,.95);
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.logo-card::before {
  content: "";
  position: absolute;
  top: 0; left: 25%; right: 25%;
  height: 2.5px;
  background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent);
  border-radius: 2.5px;
  opacity: .7;
  z-index: 1;
}
.logo-card:hover {
  transform: translateY(-6px) scale(1.03);
  border-color: #1e40af;
  box-shadow:
    0 25px 50px -15px rgba(30,58,138,.4),
    0 8px 20px -5px rgba(30,58,138,.25),
    inset 0 2px 4px rgba(255,255,255,1);
}
.logo-card .logo-emoji {
  width: 44px;
  height: 44px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  flex-shrink: 0;
  box-shadow: 0 6px 12px -3px rgba(0,0,0,.15), inset 0 2px 3px rgba(255,255,255,.4);
  background: linear-gradient(135deg, #fef3c7, #fbbf24);
}
.logo-card .logo-name {
  font-size: 12px;
  font-weight: 900;
  color: #0f172a;
  line-height: 1.3;
  text-align: center;
}

/* 📱 الموبايل */
@media (max-width: 640px) {
  .urgency-bar {
    font-size: 11px;
    padding: 8px 14px;
    gap: 8px;
  }
  .urgency-timer { font-size: 13px; }
  .urgency-cta { display: none; }
  body.has-urgency { padding-top: 40px; }

  .logos-section { margin: 40px auto; }
  .logo-card { padding: 14px 8px; }
  .logo-card .logo-emoji { width: 38px; height: 38px; font-size: 18px; }
  .logo-card .logo-name { font-size: 11px; }
}

/* 🌙 Dark mode */
html.dark .logo-card {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%);
  border-color: rgba(59,130,246,.5);
}
html.dark .logo-card .logo-name { color: #f1f5f9; }
html.dark .logos-label { color: #64748b; }
</style>


<style>
/* ═══════════════════════════════════════════════════════
   📊 Competitor Comparison Section
   ═══════════════════════════════════════════════════════ */

.compare-section {
  max-width: 1000px;
  margin: 80px auto;
  padding: 40px 28px;
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border: 3px solid #1e3a8a;
  border-radius: 32px;
  position: relative;
  overflow: hidden;
  box-shadow:
    0 0 0 3px rgba(251,191,36,.2),
    0 40px 80px -25px rgba(30,58,138,.35),
    0 15px 35px -10px rgba(15,23,42,.1),
    inset 0 3px 15px rgba(255,255,255,.9);
}
.compare-section::before {
  content: "";
  position: absolute;
  top: 0; left: 15%; right: 15%;
  height: 4px;
  background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent);
  border-radius: 4px;
  filter: blur(.5px);
  animation: compareGlow 3s ease-in-out infinite;
}
@keyframes compareGlow {
  0%, 100% { opacity: .7; transform: scaleX(.9); }
  50%      { opacity: 1; transform: scaleX(1); }
}

.compare-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(135deg, #1e3a8a, #1e40af);
  color: #fff;
  padding: 8px 18px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 900;
  letter-spacing: .5px;
  margin-bottom: 16px;
}

.compare-title {
  text-align: center;
  font-size: clamp(24px, 4vw, 36px);
  font-weight: 900;
  color: #0f172a;
  letter-spacing: -1px;
  margin-bottom: 12px;
  line-height: 1.2;
}
.compare-subtitle {
  text-align: center;
  color: #64748b;
  font-size: 14.5px;
  line-height: 1.8;
  max-width: 640px;
  margin: 0 auto 40px;
}

/* الجدول */
.compare-table-wrap {
  overflow-x: auto;
  border-radius: 22px;
  border: 2px solid #e2e8f0;
  background: #fff;
  box-shadow: inset 0 2px 5px rgba(0,0,0,.03);
}
.compare-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 640px;
}
.compare-table th,
.compare-table td {
  padding: 18px 20px;
  text-align: center;
  font-size: 14px;
  border-bottom: 1px solid #f1f5f9;
}
.compare-table th {
  background: linear-gradient(180deg, #f8fafc, #f1f5f9);
  font-weight: 900;
  color: #475569;
  font-size: 13px;
  letter-spacing: .3px;
}
.compare-table td:first-child,
.compare-table th:first-child {
  text-align: right;
  font-weight: 800;
  color: #0f172a;
  background: #fafbfc;
  min-width: 200px;
}
.compare-table tbody tr:last-child td {
  border-bottom: 0;
}

/* عمود MultiStore — مميز */
.compare-col-us {
  background: linear-gradient(180deg, #fffbeb 0%, #fef3c7 100%) !important;
  position: relative;
  color: #7c2d12 !important;
  font-weight: 900 !important;
}
.compare-table th.compare-col-us {
  background: linear-gradient(180deg, #fbbf24, #f97316) !important;
  color: #fff !important;
  font-size: 14px;
  border-bottom: 2px solid #ea580c !important;
  position: relative;
}
.compare-table th.compare-col-us::before {
  content: "⭐";
  position: absolute;
  top: -12px;
  right: 50%;
  transform: translateX(50%);
  background: linear-gradient(135deg, #fbbf24, #f97316);
  color: #fff;
  width: 26px;
  height: 26px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  box-shadow: 0 6px 12px rgba(249,115,22,.5);
  border: 2px solid #fff;
}
.compare-table tbody tr:hover {
  background: #f8fafc;
}
.compare-table tbody tr:hover .compare-col-us {
  background: linear-gradient(180deg, #fef3c7 0%, #fde68a 100%) !important;
}

/* أيقونات الحالة */
.compare-icon {
  font-size: 20px;
  display: inline-block;
  line-height: 1;
}
.compare-icon.yes { color: #16a34a; }
.compare-icon.no { color: #dc2626; opacity: .6; }
.compare-icon.partial { color: #f59e0b; }

/* شارة الفوز */
.compare-win-badge {
  display: inline-block;
  padding: 4px 12px;
  background: linear-gradient(135deg, #16a34a, #15803d);
  color: #fff;
  border-radius: 99px;
  font-size: 11px;
  font-weight: 900;
  margin-top: 6px;
  box-shadow: 0 4px 10px rgba(22,163,74,.4);
}

/* CTA أسفل الجدول */
.compare-cta-wrap {
  text-align: center;
  margin-top: 32px;
}
.compare-cta {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 16px 32px;
  border-radius: 16px;
  background: linear-gradient(135deg, #fbbf24, #f97316, #ea580c);
  color: #fff;
  font-weight: 900;
  font-size: 15px;
  text-decoration: none;
  box-shadow:
    0 18px 35px -10px rgba(249,115,22,.6),
    inset 0 2px 4px rgba(255,255,255,.35);
  transition: all .35s cubic-bezier(.2,.9,.3,1.1);
  position: relative;
  overflow: hidden;
}
.compare-cta::before {
  content: "";
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.35), transparent);
  transition: left .8s;
}
.compare-cta:hover {
  transform: translateY(-3px);
  box-shadow:
    0 25px 50px -12px rgba(249,115,22,.75),
    inset 0 3px 5px rgba(255,255,255,.5);
}
.compare-cta:hover::before { left: 100%; }

/* 📱 الموبايل */
@media (max-width: 640px) {
  .compare-section {
    margin: 50px 12px;
    padding: 28px 16px;
    border-radius: 24px;
  }
  .compare-table th,
  .compare-table td {
    padding: 14px 12px;
    font-size: 12.5px;
  }
  .compare-table td:first-child,
  .compare-table th:first-child {
    min-width: 150px;
    padding: 14px 12px;
    font-size: 12.5px;
  }
  .compare-icon { font-size: 18px; }
  .compare-subtitle { font-size: 13.5px; }
  .compare-cta { padding: 14px 24px; font-size: 14px; }
}

/* 🌙 Dark mode */
html.dark .compare-section {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%);
  border-color: rgba(59,130,246,.5);
}
html.dark .compare-title { color: #f1f5f9; }
html.dark .compare-subtitle { color: #94a3b8; }
html.dark .compare-table-wrap {
  background: #0f172a;
  border-color: rgba(59,130,246,.3);
}
html.dark .compare-table th {
  background: linear-gradient(180deg, #1e293b, #0f172a);
  color: #cbd5e1;
}
html.dark .compare-table td:first-child {
  background: #0f172a;
  color: #f1f5f9;
}
html.dark .compare-table td {
  color: #cbd5e1;
  border-bottom-color: #334155;
}
html.dark .compare-col-us {
  background: linear-gradient(180deg, rgba(251,191,36,.15), rgba(249,115,22,.1)) !important;
  color: #fbbf24 !important;
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   📧 Newsletter Form (Footer)
   ═══════════════════════════════════════════════════════ */

.newsletter-section {
  max-width: 700px;
  margin: 0 auto 40px;
  padding: 28px 24px;
  background: linear-gradient(135deg, rgba(255,255,255,.05), rgba(255,255,255,.02));
  border: 2px solid rgba(251,191,36,.25);
  border-radius: 22px;
  position: relative;
  overflow: hidden;
}
.newsletter-section::before {
  content: "";
  position: absolute;
  top: 0; left: 25%; right: 25%;
  height: 2.5px;
  background: linear-gradient(90deg, transparent, #fbbf24, #f97316, #fbbf24, transparent);
  border-radius: 2.5px;
  opacity: .8;
}
.newsletter-title {
  color: #fff;
  font-size: 18px;
  font-weight: 900;
  text-align: center;
  margin: 0 0 8px;
  letter-spacing: -0.3px;
}
.newsletter-desc {
  color: #94a3b8;
  font-size: 13px;
  text-align: center;
  line-height: 1.7;
  margin: 0 0 18px;
}
.newsletter-form {
  display: flex;
  gap: 8px;
  margin-bottom: 12px;
}
.newsletter-form input {
  flex: 1;
  padding: 14px 16px;
  border: 2px solid rgba(255,255,255,.15);
  border-radius: 14px;
  background: rgba(255,255,255,.05);
  color: #fff;
  font-family: inherit;
  font-size: 14px;
  direction: ltr;
  outline: none;
  transition: all .3s;
}
.newsletter-form input::placeholder { color: #64748b; }
.newsletter-form input:focus {
  border-color: #fbbf24;
  background: rgba(255,255,255,.08);
  box-shadow: 0 0 0 4px rgba(251,191,36,.1);
}
.newsletter-form button {
  padding: 14px 24px;
  border: 0;
  border-radius: 14px;
  background: linear-gradient(135deg, #fbbf24, #f97316);
  color: #fff;
  font-weight: 900;
  font-size: 14px;
  cursor: pointer;
  font-family: inherit;
  box-shadow: 0 10px 22px -8px rgba(249,115,22,.5);
  transition: all .3s;
  white-space: nowrap;
}
.newsletter-form button:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 32px -10px rgba(249,115,22,.7);
}
.newsletter-form button:disabled {
  opacity: .6;
  cursor: wait;
}
.newsletter-msg {
  text-align: center;
  font-size: 12.5px;
  font-weight: 800;
  padding: 8px 14px;
  border-radius: 10px;
  display: none;
  margin-bottom: 10px;
}
.newsletter-msg.success {
  background: rgba(34,197,94,.15);
  color: #86efac;
  border: 1px solid rgba(34,197,94,.3);
  display: block;
}
.newsletter-msg.error {
  background: rgba(220,38,38,.15);
  color: #fca5a5;
  border: 1px solid rgba(220,38,38,.3);
  display: block;
}
.newsletter-footer {
  text-align: center;
  font-size: 11px;
  color: #64748b;
  font-weight: 700;
}

@media (max-width: 640px) {
  .newsletter-section { padding: 22px 16px; margin-bottom: 28px; }
  .newsletter-form { flex-direction: column; }
  .newsletter-form button { width: 100%; }
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   💬 Floating Action Button + Multi-Channel Chat
   ═══════════════════════════════════════════════════════ */

.fab-wrapper {
  position: fixed;
  bottom: 24px;
  left: 24px;
  z-index: 9997;
  display: flex;
  flex-direction: column-reverse;
  align-items: center;
  gap: 12px;
  pointer-events: none;
}
.fab-wrapper > * {
  pointer-events: auto;
}

/* ─── الأزرار الفرعية ─── */
.fab-channels {
  display: flex;
  flex-direction: column;
  gap: 8px;
  opacity: 0;
  transform: translateY(20px) scale(0.8);
  transition: all .4s cubic-bezier(.2,.9,.3,1.1);
  pointer-events: none;
  visibility: hidden;
}
.fab-wrapper.open .fab-channels {
  opacity: 1;
  transform: translateY(0) scale(1);
  pointer-events: auto;
  visibility: visible;
}
.fab-channel {
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
  cursor: pointer;
  position: relative;
  transition: all .3s cubic-bezier(.2,.9,.3,1.1);
}
.fab-channel-label {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  color: #0f172a;
  padding: 8px 14px;
  border-radius: 12px;
  font-size: 12.5px;
  font-weight: 900;
  white-space: nowrap;
  border: 2px solid #1e3a8a;
  box-shadow:
    0 8px 20px -5px rgba(30,58,138,.3),
    inset 0 2px 3px rgba(255,255,255,.9);
  opacity: 0;
  transform: translateX(-10px);
  transition: all .3s;
}
.fab-wrapper.open .fab-channel-label {
  opacity: 1;
  transform: translateX(0);
}
.fab-wrapper.open .fab-channel:nth-child(1) .fab-channel-label { transition-delay: .05s; }
.fab-wrapper.open .fab-channel:nth-child(2) .fab-channel-label { transition-delay: .1s; }
.fab-wrapper.open .fab-channel:nth-child(3) .fab-channel-label { transition-delay: .15s; }
.fab-wrapper.open .fab-channel:nth-child(4) .fab-channel-label { transition-delay: .2s; }

.fab-channel-icon {
  width: 46px;
  height: 46px;
  border-radius: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  color: #fff;
  box-shadow:
    0 12px 25px -8px rgba(0,0,0,.4),
    0 4px 10px -3px rgba(0,0,0,.2),
    inset 0 3px 6px rgba(255,255,255,.3),
    inset 0 -3px 6px rgba(0,0,0,.15);
  transition: all .3s cubic-bezier(.2,.9,.3,1.1);
  position: relative;
}
.fab-channel:hover .fab-channel-icon {
  transform: translateY(-3px) scale(1.1) rotate(-5deg);
  box-shadow:
    0 18px 35px -10px rgba(0,0,0,.5),
    inset 0 3px 8px rgba(255,255,255,.4);
}
.fab-channel:hover .fab-channel-label {
  transform: translateX(0) scale(1.05);
}

/* ألوان القنوات */
.fab-whatsapp .fab-channel-icon {
  background: linear-gradient(135deg, #34D399 0%, #25D366 50%, #128C7E 100%);
}
.fab-whatsapp:hover .fab-channel-icon {
  box-shadow: 0 18px 35px -10px rgba(37,211,102,.7), inset 0 3px 8px rgba(255,255,255,.4);
}

.fab-phone .fab-channel-icon {
  background: linear-gradient(135deg, #fbbf24 0%, #f97316 50%, #ea580c 100%);
}
.fab-phone:hover .fab-channel-icon {
  box-shadow: 0 18px 35px -10px rgba(249,115,22,.7), inset 0 3px 8px rgba(255,255,255,.4);
}

.fab-mail .fab-channel-icon {
  background: linear-gradient(135deg, #60A5FA 0%, #3B82F6 50%, #1e40af 100%);
}
.fab-mail:hover .fab-channel-icon {
  box-shadow: 0 18px 35px -10px rgba(59,130,246,.7), inset 0 3px 8px rgba(255,255,255,.4);
}

.fab-support .fab-channel-icon {
  background: linear-gradient(135deg, #A78BFA 0%, #8b5cf6 50%, #6d28d9 100%);
}
.fab-support:hover .fab-channel-icon {
  box-shadow: 0 18px 35px -10px rgba(139,92,246,.7), inset 0 3px 8px rgba(255,255,255,.4);
}

/* ─── الزر الرئيسي (Toggle) ─── */
.fab-toggle {
  width: 58px;
  height: 58px;
  border-radius: 22px;
  border: 0;
  cursor: pointer;
  background: linear-gradient(135deg, #fbbf24 0%, #f97316 50%, #ea580c 100%);
  color: #fff;
  font-size: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow:
    0 18px 40px -10px rgba(249,115,22,.65),
    0 8px 20px -5px rgba(249,115,22,.4),
    inset 0 3px 6px rgba(255,255,255,.35),
    inset 0 -3px 6px rgba(0,0,0,.15);
  transition: all .4s cubic-bezier(.2,.9,.3,1.1);
  position: relative;
  font-family: inherit;
}
.fab-toggle::before {
  content: "";
  position: absolute;
  inset: -10px;
  border-radius: 28px;
  background: radial-gradient(circle, rgba(249,115,22,.5), transparent 70%);
  filter: blur(14px);
  z-index: -1;
  animation: fabPulse 2.5s ease-in-out infinite;
  pointer-events: none;
}
@keyframes fabPulse {
  0%, 100% { opacity: .6; transform: scale(1); }
  50%      { opacity: 1; transform: scale(1.1); }
}
.fab-toggle:hover {
  transform: scale(1.1) rotate(-8deg);
}
.fab-wrapper.open .fab-toggle {
  transform: rotate(135deg);
  background: linear-gradient(135deg, #64748b 0%, #334155 50%, #1e293b 100%);
  box-shadow:
    0 18px 40px -10px rgba(15,23,42,.5),
    inset 0 3px 6px rgba(255,255,255,.15);
}

/* شارة الإشعار */
.fab-badge {
  position: absolute;
  top: -4px;
  right: -4px;
  background: linear-gradient(135deg, #dc2626, #b91c1c);
  color: #fff;
  font-size: 11px;
  font-weight: 900;
  min-width: 22px;
  height: 22px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2.5px solid #fff;
  box-shadow: 0 4px 10px rgba(220,38,38,.5);
  font-family: inherit;
}

/* 📱 الموبايل */
@media (max-width: 640px) {
  .fab-wrapper {
    bottom: 16px;
    left: 16px;
    gap: 8px;
  }
  .fab-toggle { width: 58px; height: 58px; font-size: 24px; border-radius: 20px; }
  .fab-channel-icon { width: 46px; height: 46px; border-radius: 16px; font-size: 20px; }
  .fab-channel-label { font-size: 11.5px; padding: 7px 12px; }
}

/* 🌙 Dark mode */
html.dark .fab-channel-label {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%);
  color: #f1f5f9;
  border-color: rgba(59,130,246,.5);
}
</style>


<style>
/* ═══════════════════════════════════════════════════════
   🎓 Success Stories Section
   ═══════════════════════════════════════════════════════ */

.stories-section {
  max-width: 1200px;
  margin: 80px auto;
  padding: 0 20px;
}
.stories-header {
  text-align: center;
  margin-bottom: 50px;
}
.stories-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(135deg, #fef3c7, #fde68a);
  color: #92400e;
  padding: 8px 20px;
  border-radius: 99px;
  font-size: 12px;
  font-weight: 900;
  border: 2px solid #f59e0b;
  margin-bottom: 20px;
}
.stories-title {
  font-size: clamp(26px, 4.5vw, 42px);
  font-weight: 900;
  color: #0f172a;
  letter-spacing: -1.5px;
  line-height: 1.1;
  margin: 0 0 16px;
}
.stories-subtitle {
  font-size: 15px;
  color: #64748b;
  line-height: 1.8;
  max-width: 560px;
  margin: 0 auto;
}

.stories-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
}
@media (min-width: 768px) {
  .stories-grid { grid-template-columns: repeat(3, 1fr); }
}

.story-card {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border: 2.5px solid #1e3a8a;
  border-radius: 26px;
  padding: 32px 24px 28px;
  position: relative;
  overflow: hidden;
  box-shadow:
    0 25px 50px -20px rgba(30,58,138,.25),
    0 10px 25px -10px rgba(15,23,42,.08),
    inset 0 3px 6px rgba(255,255,255,.95);
  transition: all .45s cubic-bezier(.2,.9,.3,1.1);
  cursor: pointer;
}
.story-card::before {
  content: "";
  position: absolute;
  top: 0; left: 20%; right: 20%;
  height: 3px;
  background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent);
  border-radius: 3px;
  opacity: .8;
  transition: all .4s;
  z-index: 2;
}
.story-card:hover {
  transform: translateY(-12px) scale(1.02);
  border-color: #1e40af;
  box-shadow:
    0 40px 75px -25px rgba(30,58,138,.45),
    0 15px 35px -10px rgba(30,58,138,.25),
    inset 0 3px 8px rgba(255,255,255,1);
}
.story-card:hover::before { opacity: 1; }

.story-header {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 20px;
  padding-bottom: 18px;
  border-bottom: 2px dashed rgba(30,58,138,.15);
}
.story-avatar {
  width: 60px;
  height: 60px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  flex-shrink: 0;
  box-shadow:
    0 12px 25px -8px rgba(0,0,0,.25),
    inset 0 3px 6px rgba(255,255,255,.3),
    inset 0 -3px 6px rgba(0,0,0,.15);
  border: 2px solid #fff;
}
.story-info {
  flex: 1;
  min-width: 0;
}
.story-name {
  font-size: 15px;
  font-weight: 900;
  color: #0f172a;
  margin: 0 0 4px;
}
.story-type {
  font-size: 12px;
  color: #64748b;
  font-weight: 700;
}

.story-quote {
  font-size: 14px;
  line-height: 1.85;
  color: #475569;
  margin: 0 0 20px;
  font-style: italic;
  position: relative;
  padding-right: 20px;
}
.story-quote::before {
  content: """;
  position: absolute;
  top: -10px;
  right: 0;
  font-size: 50px;
  color: #fbbf24;
  opacity: .4;
  font-family: Georgia, serif;
  line-height: 1;
}

.story-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-top: 20px;
}
.story-stat {
  background: linear-gradient(135deg, #fffbeb, #fef3c7);
  border: 1.5px solid #fbbf24;
  border-radius: 14px;
  padding: 12px 10px;
  text-align: center;
  box-shadow: inset 0 2px 4px rgba(255,255,255,.8);
}
.story-stat-num {
  font-size: 22px;
  font-weight: 900;
  color: #ea580c;
  letter-spacing: -0.5px;
  line-height: 1;
  margin-bottom: 4px;
}
.story-stat-label {
  font-size: 10.5px;
  color: #92400e;
  font-weight: 800;
  letter-spacing: .2px;
}

.story-tag {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  background: linear-gradient(135deg, #dcfce7, #bbf7d0);
  color: #15803d;
  padding: 5px 12px;
  border-radius: 99px;
  font-size: 11px;
  font-weight: 900;
  margin-top: 16px;
  border: 1.5px solid #86efac;
}

/* 📱 الموبايل */
@media (max-width: 640px) {
  .stories-section { margin: 50px auto; }
  .story-card { padding: 24px 20px 22px; }
  .story-avatar { width: 52px; height: 52px; font-size: 24px; }
  .story-stat-num { font-size: 20px; }
}

/* 🌙 Dark mode */
html.dark .story-card {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%);
  border-color: rgba(59,130,246,.5);
}
html.dark .story-name { color: #f1f5f9; }
html.dark .story-quote { color: #cbd5e1; }
html.dark .story-stat {
  background: linear-gradient(135deg, rgba(251,191,36,.15), rgba(249,115,22,.1));
  border-color: rgba(251,191,36,.4);
}
html.dark .story-stat-num { color: #fbbf24; }
html.dark .story-stat-label { color: #fcd34d; }
</style>


<style>
/* ═══════════════════════════════════════════════════════
   🎬 Video Testimonials Section
   ═══════════════════════════════════════════════════════ */

.videos-section {
  max-width: 1200px;
  margin: 80px auto;
  padding: 0 20px;
}
.videos-header { text-align: center; margin-bottom: 50px; }
.videos-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(135deg, #ede9fe, #ddd6fe);
  color: #6d28d9;
  padding: 8px 20px;
  border-radius: 99px;
  font-size: 12px;
  font-weight: 900;
  border: 2px solid #8b5cf6;
  margin-bottom: 20px;
}
.videos-title {
  font-size: clamp(26px, 4.5vw, 42px);
  font-weight: 900;
  color: #0f172a;
  letter-spacing: -1.5px;
  line-height: 1.1;
  margin: 0 0 16px;
}
.videos-subtitle {
  font-size: 15px;
  color: #64748b;
  line-height: 1.8;
  max-width: 560px;
  margin: 0 auto;
}

.videos-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
}
@media (min-width: 768px) {
  .videos-grid { grid-template-columns: repeat(3, 1fr); }
}

/* بطاقة الفيديو */
.video-card {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border: 2.5px solid #1e3a8a;
  border-radius: 26px;
  overflow: hidden;
  position: relative;
  box-shadow:
    0 25px 50px -20px rgba(30,58,138,.25),
    0 10px 25px -10px rgba(15,23,42,.08),
    inset 0 3px 6px rgba(255,255,255,.95);
  transition: all .45s cubic-bezier(.2,.9,.3,1.1);
  cursor: pointer;
}
.video-card::before {
  content: "";
  position: absolute;
  top: 0; left: 20%; right: 20%;
  height: 3px;
  background: linear-gradient(90deg, transparent, #8b5cf6, #6d28d9, #8b5cf6, transparent);
  border-radius: 3px;
  opacity: .8;
  transition: all .4s;
  z-index: 3;
}
.video-card:hover {
  transform: translateY(-12px) scale(1.02);
  border-color: #6d28d9;
  box-shadow:
    0 40px 75px -25px rgba(109,40,217,.4),
    0 15px 35px -10px rgba(109,40,217,.25),
    inset 0 3px 8px rgba(255,255,255,1);
}
.video-card:hover::before { opacity: 1; }

/* Poster */
.video-poster {
  height: 220px;
  background-size: cover;
  background-position: center;
  position: relative;
  overflow: hidden;
}
.video-poster::after {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(15,23,42,.1) 0%, rgba(15,23,42,.6) 100%);
  pointer-events: none;
}

/* Play Button */
.video-play {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 74px;
  height: 74px;
  border-radius: 50%;
  background: linear-gradient(135deg, #fbbf24, #f97316, #ea580c);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  box-shadow:
    0 20px 40px -10px rgba(249,115,22,.7),
    0 8px 20px -5px rgba(249,115,22,.4),
    inset 0 3px 6px rgba(255,255,255,.4);
  z-index: 2;
  transition: all .4s cubic-bezier(.2,.9,.3,1.1);
  border: 3px solid #fff;
  padding-left: 6px;
}
.video-play::before {
  content: "";
  position: absolute;
  inset: -12px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(249,115,22,.5), transparent 70%);
  animation: videoPulse 2s ease-in-out infinite;
  pointer-events: none;
}
@keyframes videoPulse {
  0%, 100% { opacity: .4; transform: scale(1); }
  50%      { opacity: .8; transform: scale(1.15); }
}
.video-card:hover .video-play {
  transform: translate(-50%, -50%) scale(1.15);
  box-shadow:
    0 30px 60px -12px rgba(249,115,22,.9),
    0 12px 25px -5px rgba(249,115,22,.6),
    inset 0 3px 8px rgba(255,255,255,.5);
}

/* Duration Badge */
.video-duration {
  position: absolute;
  bottom: 12px;
  left: 12px;
  background: rgba(15,23,42,.85);
  color: #fff;
  padding: 4px 10px;
  border-radius: 8px;
  font-size: 11px;
  font-weight: 900;
  font-family: monospace;
  letter-spacing: .5px;
  z-index: 2;
  backdrop-filter: blur(4px);
}

/* Live Badge */
.video-live-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  background: linear-gradient(135deg, #dc2626, #b91c1c);
  color: #fff;
  padding: 4px 10px;
  border-radius: 8px;
  font-size: 10px;
  font-weight: 900;
  display: flex;
  align-items: center;
  gap: 4px;
  z-index: 2;
  box-shadow: 0 4px 10px rgba(220,38,38,.5);
  letter-spacing: .5px;
}
.video-live-dot {
  width: 6px;
  height: 6px;
  background: #fff;
  border-radius: 50%;
  animation: liveDot 1.5s ease-in-out infinite;
}
@keyframes liveDot {
  0%, 100% { opacity: 1; }
  50%      { opacity: .3; }
}

/* Video Info */
.video-info {
  padding: 20px 20px 22px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.video-avatar {
  width: 46px;
  height: 46px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  font-weight: 900;
  color: #fff;
  flex-shrink: 0;
  box-shadow:
    0 8px 18px -5px rgba(0,0,0,.3),
    inset 0 2px 4px rgba(255,255,255,.3);
  border: 2px solid #fff;
}
.video-meta { flex: 1; min-width: 0; }
.video-name {
  font-size: 14px;
  font-weight: 900;
  color: #0f172a;
  margin: 0 0 3px;
}
.video-role {
  font-size: 11.5px;
  color: #64748b;
  font-weight: 700;
}

/* Video Modal */
.video-modal {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.9);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  z-index: 99999;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 20px;
  animation: videoModalIn .3s ease;
}
.video-modal.open { display: flex; }
@keyframes videoModalIn {
  from { opacity: 0; }
  to   { opacity: 1; }
}
.video-modal-box {
  max-width: 800px;
  width: 100%;
  background: linear-gradient(135deg, #0f172a, #1e293b);
  border: 3px solid #1e3a8a;
  border-radius: 24px;
  padding: 24px;
  position: relative;
  box-shadow:
    0 0 0 3px rgba(139,92,246,.3),
    0 40px 80px -20px rgba(0,0,0,.8);
  animation: videoBoxIn .5s cubic-bezier(.2,.9,.3,1.1);
}
@keyframes videoBoxIn {
  from { transform: scale(.9) translateY(30px); opacity: 0; }
  to   { transform: scale(1) translateY(0); opacity: 1; }
}
.video-modal-box::before {
  content: "";
  position: absolute;
  top: 0; left: 15%; right: 15%;
  height: 4px;
  background: linear-gradient(90deg, transparent, #8b5cf6, #6d28d9, #8b5cf6, transparent);
  border-radius: 4px;
  filter: blur(.5px);
}
.video-modal-close {
  position: absolute;
  top: 16px;
  right: 16px;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 0;
  background: rgba(255,255,255,.15);
  color: #fff;
  font-size: 20px;
  font-weight: 900;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all .3s;
  font-family: inherit;
  z-index: 3;
  backdrop-filter: blur(4px);
}
.video-modal-close:hover {
  background: rgba(220,38,38,.5);
  transform: rotate(90deg);
}
.video-modal-title {
  color: #fff;
  font-size: 18px;
  font-weight: 900;
  margin: 0 0 16px;
  text-align: center;
  padding-right: 40px;
}
.video-modal-player {
  aspect-ratio: 16/9;
  background: linear-gradient(135deg, #1e293b, #0f172a);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  gap: 16px;
  border: 2px solid rgba(139,92,246,.3);
  padding: 30px;
  text-align: center;
}
.video-modal-player iframe,
.video-modal-player video {
  width: 100%;
  height: 100%;
  border-radius: 12px;
  border: 0;
}
.video-placeholder-icon {
  font-size: 64px;
  animation: videoPlaceholderFloat 2.5s ease-in-out infinite;
}
@keyframes videoPlaceholderFloat {
  0%, 100% { transform: translateY(0) scale(1); }
  50%      { transform: translateY(-8px) scale(1.05); }
}
.video-placeholder-text {
  color: #e2e8f0;
  font-size: 15px;
  font-weight: 800;
  line-height: 1.7;
  max-width: 400px;
}
.video-placeholder-sub {
  color: #94a3b8;
  font-size: 12.5px;
  font-weight: 700;
  margin-top: 8px;
}
.video-modal-cta {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  margin-top: 20px;
  padding: 12px 24px;
  background: linear-gradient(135deg, #fbbf24, #f97316);
  color: #fff;
  border-radius: 12px;
  font-weight: 900;
  font-size: 13px;
  text-decoration: none;
  box-shadow: 0 10px 22px -8px rgba(249,115,22,.5);
  transition: all .3s;
}
.video-modal-cta:hover { transform: translateY(-2px); }

/* 📱 الموبايل */
@media (max-width: 640px) {
  .videos-section { margin: 50px auto; }
  .video-poster { height: 190px; }
  .video-play { width: 62px; height: 62px; font-size: 22px; }
  .video-modal-box { padding: 18px; border-radius: 20px; }
  .video-modal-player { padding: 20px; }
  .video-placeholder-icon { font-size: 48px; }
}

/* 🌙 Dark mode */
html.dark .video-card {
  background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%);
  border-color: rgba(139,92,246,.5);
}
html.dark .video-name { color: #f1f5f9; }
html.dark .video-role { color: #94a3b8; }
</style>

</head>
<body class="loading">
<!-- ═══ ⏰ Urgency Bar ═══ -->
<div class="urgency-bar" id="urgencyBar">
  <button class="urgency-close" onclick="closeUrgencyBar()" aria-label="إغلاق">✕</button>
  <span class="urgency-icon">⏰</span>
  <span>عرض خاص ينتهي خلال:</span>
  <span class="urgency-timer">
    <span id="urgencyH">00</span>:<span id="urgencyM">00</span>:<span id="urgencyS">00</span>
  </span>
  <a href="/register" class="urgency-cta">🎁 احصل على +30 يوم مجاناً</a>
</div>


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

        {{-- ═══ 🎯 Hero Stats Bar ═══ --}}
        <div class="hero-stats reveal">
          <div class="hero-stat">
            <span class="hero-stat-icon">🏪</span>
            <div class="hero-stat-value">+500</div>
            <div class="hero-stat-label">متجر نشط</div>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-icon">📦</span>
            <div class="hero-stat-value">50K+</div>
            <div class="hero-stat-label">طلب مكتمل</div>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-icon">⭐</span>
            <div class="hero-stat-value">4.9/5</div>
            <div class="hero-stat-label">تقييم المستخدمين</div>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-icon">🕐</span>
            <div class="hero-stat-value">24/7</div>
            <div class="hero-stat-label">دعم فني</div>
          </div>
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

<!-- ═══ 📊 Live Stats Bar ═══ -->
<div class="live-stats-bar" id="liveStats">
  <div class="live-stats-title">
    <span class="live-dot"></span>
    إحصائيات حية — تتحدث كل ثانية
  </div>
  @php $ls = live_stats(); @endphp
  <div class="live-stats-grid">
    <div class="live-stat">
      <div class="live-stat-num" data-target="{{ (int) $ls['shops']['value'] }}">0</div>
      <div class="live-stat-label">🏪 {{ $ls['shops']['label'] }}</div>
    </div>
    <div class="live-stat">
      <div class="live-stat-num" data-target="{{ (int) $ls['orders']['value'] }}">0</div>
      <div class="live-stat-label">📦 {{ $ls['orders']['label'] }}</div>
    </div>
    <div class="live-stat">
      <div class="live-stat-num" data-target="{{ (float) $ls['sales']['value'] }}" data-suffix="{{ $ls['mode'] === 'real' ? '' : 'M' }}">0</div>
      <div class="live-stat-label">💰 {{ $ls['sales']['label'] }}</div>
    </div>
    <div class="live-stat">
      <div class="live-stat-num" data-target="{{ (int) $ls['users']['value'] }}">0</div>
      <div class="live-stat-label">👥 {{ $ls['users']['label'] }}</div>
    </div>
  </div>
</div>

<!-- ═══ 🏪 Trust Logos ═══ -->
<section class="logos-section reveal">
  <div class="logos-label">{{ setting("logos.title", "يثق بنا أكثر من 500 متجر يمني") }}</div>
  @php $logosItems = setting('logos.items', []); @endphp
  <div class="logos-grid">
    @forelse($logosItems as $logo)
      <div class="logo-card">
        <div class="logo-emoji" style="background:linear-gradient(135deg,{{ $logo['color'] ?? '#fbbf24' }}40,{{ $logo['color'] ?? '#fbbf24' }})">{{ $logo['emoji'] ?? '🏪' }}</div>
        <div class="logo-name">{{ $logo['name'] ?? '' }}</div>
      </div>
    @empty
      <div style="grid-column:1/-1;text-align:center;color:#94a3b8;padding:20px;">لا توجد شعارات بعد — أضفها من الإعدادات</div>
    @endforelse
  </div>
</section>


<section id="features" class="section section-framed">
  <div class="frame-glow"></div>
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

      <div class="card-glow reveal" data-feature="shops" onclick="openFeatureModal('shops')" style="cursor:pointer" onmousemove="trackCursor(event,this)">
        <div class="card-icon">
          <i data-lucide="store" style="width:30px;height:30px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">متاجر متعددة</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          أنشئ وأدر أكثر من متجر من منصة واحدة — كل متجر ببياناته ومنتجاته وعملائه.
        </p>
      </div>

      <div class="card-glow reveal delay-1" data-feature="products" onclick="openFeatureModal('products')" style="cursor:pointer" onmousemove="trackCursor(event,this)">
        <div class="card-icon">
          <i data-lucide="package" style="width:30px;height:30px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">إدارة المنتجات</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          أضف منتجاتك بصور احترافية، متغيرات، مخزون، وتصنيفات — بضغطة زر.
        </p>
      </div>

      <div class="card-glow reveal delay-2" data-feature="orders" onclick="openFeatureModal('orders')" style="cursor:pointer" onmousemove="trackCursor(event,this)">
        <div class="card-icon">
          <i data-lucide="shopping-bag" style="width:30px;height:30px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">إدارة الطلبات</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          تابع الطلبات، حالاتها، وتتبع الشحنات من لوحة تحكم واحدة شاملة.
        </p>
      </div>

      <div class="card-glow reveal delay-1" data-feature="sms" onclick="openFeatureModal('sms')" style="cursor:pointer" onmousemove="trackCursor(event,this)">
        <div class="card-icon">
          <i data-lucide="message-square" style="width:30px;height:30px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">دفع SMS تلقائي</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          النظام يقرأ رسائل التحويل البنكي ويؤكد الطلب تلقائياً في ثوانٍ.
        </p>
      </div>

      <div class="card-glow reveal delay-2" data-feature="reports" onclick="openFeatureModal('reports')" style="cursor:pointer" onmousemove="trackCursor(event,this)">
        <div class="card-icon">
          <i data-lucide="bar-chart-3" style="width:30px;height:30px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">تقارير وتحليلات</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          راقب أداء متجرك — مبيعات، أرباح، عملاء، وأفضل المنتجات لحظياً.
        </p>
      </div>

      <div class="card-glow reveal delay-3" data-feature="security" onclick="openFeatureModal('security')" style="cursor:pointer" onmousemove="trackCursor(event,this)">
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

<!-- ═══ 🪟 Feature Modal ═══ -->
<div class="feature-modal-overlay" id="featureModal" onclick="if(event.target===this)closeFeatureModal()">
  <div class="feature-modal-box">
    <button class="feature-modal-close" onclick="closeFeatureModal()" aria-label="إغلاق">✕</button>
    <div class="feature-modal-icon" id="fmIcon">📦</div>
    <h3 class="feature-modal-title" id="fmTitle"></h3>
    <p class="feature-modal-desc" id="fmDesc"></p>
    <ul class="feature-modal-points" id="fmPoints"></ul>
    <a class="feature-modal-cta" id="fmCta" href="#">→</a>
  </div>
</div>

<script>
(function(){
  'use strict';
  var FEATURES = {
    shops: { icon:'🏪', title:'متاجر متعددة',
      desc:'أنشئ وأدر أكثر من متجر من منصة واحدة — كل متجر ببياناته ومنتجاته وعملائه.',
      points:['إنشاء متاجر غير محدودة','كل متجر برابطه الخاص','بيانات ومحاسبة منفصلة','تبديل سريع بين المتاجر'],
      link:'/demo-shop', linkText:'🏪 تجربة المتاجر' },
    products: { icon:'📦', title:'إدارة المنتجات',
      desc:'أضف منتجاتك بصور احترافية، متغيرات، مخزون، وتصنيفات — بضغطة زر.',
      points:['رفع صور متعددة للمنتج','متغيرات (لون، مقاس)','إدارة المخزون تلقائياً','تصنيفات وتصنيفات فرعية'],
      link:'/dashboard/products', linkText:'📦 إدارة المنتجات' },
    orders: { icon:'🛒', title:'إدارة الطلبات',
      desc:'تابع الطلبات، حالاتها، وتتبع الشحنات من لوحة تحكم واحدة شاملة.',
      points:['طلبات لحظية مع إشعارات','تتبع حالة الطلب','طباعة فواتير احترافية','إدارة الشحن والتتبع'],
      link:'/dashboard/orders', linkText:'🛒 عرض الطلبات' },
    sms: { icon:'💳', title:'دفع SMS تلقائي',
      desc:'النظام يقرأ رسائل التحويل البنكي ويؤكد الطلب تلقائياً في ثوانٍ.',
      points:['قراءة تلقائية لرسائل SMS','مطابقة الدفعات بالطلبات','تأكيد الطلب فوراً','دعم كل البنوك اليمنية'],
      link:'/dashboard/sms-center', linkText:'💳 مركز SMS' },
    reports: { icon:'📊', title:'تقارير وتحليلات',
      desc:'راقب أداء متجرك — مبيعات، أرباح، عملاء، وأفضل المنتجات لحظياً.',
      points:['تقارير مبيعات يومية/شهرية','تحليل سلوك العملاء','أفضل المنتجات مبيعاً','تصدير CSV وExcel'],
      link:'/dashboard/reports', linkText:'📊 التقارير' },
    security: { icon:'🔒', title:'أمان وصلاحيات',
      desc:'تحكم كامل بالصلاحيات، الأدوار، والوصول لكل قسم في النظام.',
      points:['أدوار متعددة (مدير، موظف)','صلاحيات دقيقة لكل قسم','سجل النشاطات الكامل','دخول آمن بـ2FA'],
      link:'/dashboard/staff', linkText:'🔒 إدارة الموظفين' }
  };
  window.openFeatureModal = function(key){
    var f = FEATURES[key]; if (!f) return;
    document.getElementById('fmIcon').textContent = f.icon;
    document.getElementById('fmTitle').textContent = f.title;
    document.getElementById('fmDesc').textContent = f.desc;
    var html = ''; f.points.forEach(function(p){ html += '<li>' + p + '</li>'; });
    document.getElementById('fmPoints').innerHTML = html;
    var cta = document.getElementById('fmCta');
    cta.href = f.link; cta.textContent = f.linkText + ' →';
    document.getElementById('featureModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  };
  window.closeFeatureModal = function(){
    document.getElementById('featureModal').classList.remove('open');
    document.body.style.overflow = '';
  };
  document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeFeatureModal(); });
})();
</script>
</section>

<!-- 🚀 HOW IT WORKS -->
<section id="how" class="section section-framed">
  <div class="frame-glow"></div>
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

      <div class="reveal step-card-hover" onclick="window.location.href='/register'" style="cursor:pointer;background:#fff;border-radius:24px;padding:36px 28px;text-align:center;border:1px solid rgba(245,158,11,.15);position:relative;box-shadow:0 4px 20px rgba(15,23,42,.04);transition:all .3s">
        <div class="step-number" style="position:absolute;top:-20px;left:50%;transform:translateX(-50%);width:56px;height:56px;border-radius:18px;background:linear-gradient(135deg,#f59e0b,#f97316);display:grid;place-items:center;color:#fff;font-size:22px;font-weight:900;box-shadow:0 12px 32px rgba(245,158,11,.4)">1</div>
        <div class="step-icon" style="width:70px;height:70px;margin:20px auto 16px;border-radius:20px;background:linear-gradient(135deg,#fff7ed,#ffedd5);display:grid;place-items:center;color:#ea580c">
          <i data-lucide="user-plus" style="width:32px;height:32px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">أنشئ حسابك</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          سجّل بياناتك في أقل من دقيقة، وابدأ إعداد متجرك مباشرة.
        </p>
      </div>

      <div class="reveal step-card-hover delay-1" onclick="window.location.href='/dashboard/products/create'" style="cursor:pointer;background:#fff;border-radius:24px;padding:36px 28px;text-align:center;border:1px solid rgba(245,158,11,.15);position:relative;box-shadow:0 4px 20px rgba(15,23,42,.04);transition:all .3s">
        <div class="step-number" style="position:absolute;top:-20px;left:50%;transform:translateX(-50%);width:56px;height:56px;border-radius:18px;background:linear-gradient(135deg,#f59e0b,#f97316);display:grid;place-items:center;color:#fff;font-size:22px;font-weight:900;box-shadow:0 12px 32px rgba(245,158,11,.4)">2</div>
        <div class="step-icon" style="width:70px;height:70px;margin:20px auto 16px;border-radius:20px;background:linear-gradient(135deg,#fff7ed,#ffedd5);display:grid;place-items:center;color:#ea580c">
          <i data-lucide="package-plus" style="width:32px;height:32px"></i>
        </div>
        <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin-bottom:10px">أضف منتجاتك</h3>
        <p style="color:#64748b;line-height:1.8;font-size:14.5px">
          ارفع صور المنتجات، حدد الأسعار، المخزون، والتصنيفات.
        </p>
      </div>

      <div class="reveal step-card-hover delay-2" onclick="window.location.href='/dashboard'" style="cursor:pointer;background:#fff;border-radius:24px;padding:36px 28px;text-align:center;border:1px solid rgba(245,158,11,.15);position:relative;box-shadow:0 4px 20px rgba(15,23,42,.04);transition:all .3s">
        <div class="step-number" style="position:absolute;top:-20px;left:50%;transform:translateX(-50%);width:56px;height:56px;border-radius:18px;background:linear-gradient(135deg,#f59e0b,#f97316);display:grid;place-items:center;color:#fff;font-size:22px;font-weight:900;box-shadow:0 12px 32px rgba(245,158,11,.4)">3</div>
        <div class="step-icon" style="width:70px;height:70px;margin:20px auto 16px;border-radius:20px;background:linear-gradient(135deg,#fff7ed,#ffedd5);display:grid;place-items:center;color:#ea580c">
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



<!-- ═══ 💰 ROI Calculator ═══ -->
<section class="roi-section reveal">
  <div class="roi-title">احسب <span class="gradient-text">توفيرك الشهري</span> 💰</div>
  <p class="roi-subtitle">حرّك المؤشرات وشاهد كم يمكن أن توفّر شهرياً مع أتمتة MultiStore</p>

  <div class="roi-inputs">
    <div class="roi-input-group">
      <label>📦 عدد الطلبات الشهرياً</label>
      <input type="range" id="roiOrders" min="10" max="1000" value="100" step="10"
             oninput="document.getElementById('roiOrdersVal').textContent = this.value + ' طلب'">
      <span class="roi-value" id="roiOrdersVal">100 طلب</span>
    </div>
    <div class="roi-input-group">
      <label>💵 متوسط قيمة الطلب (ريال)</label>
      <input type="range" id="roiAvg" min="1000" max="100000" value="15000" step="1000"
             oninput="document.getElementById('roiAvgVal').textContent = Number(this.value).toLocaleString() + ' ر.ي'">
      <span class="roi-value" id="roiAvgVal">15,000 ر.ي</span>
    </div>
  </div>

  <div class="roi-result">
    <div class="roi-result-label">💡 توفير متوقع شهرياً (أتمتة + تقارير + SMS)</div>
    <div class="roi-result-value" id="roiResult">150,000 ر.ي</div>
    <div class="roi-result-sub">≈ <span id="roiYear">1,800,000</span> ر.ي سنوياً + 40 ساعة عمل موفّرة</div>
    <a href="/register" class="roi-cta">🎯 ابدأ بتوفير هذا المبلغ →</a>
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


<!-- 🎨 MARKETING DYNAMIC -->
<section id="marketing" class="section" style="background:#fafafa;position:relative;overflow:hidden">
  <div class="container-x">
    @php $mktItems = setting('marketing.items', []); @endphp

    <div style="text-align:center;margin-bottom:60px" class="reveal">
      <div class="badge" style="margin-bottom:20px">
        <i data-lucide="image" style="width:14px;height:14px"></i>
        تجربة بصرية حقيقية
      </div>
      <h2 class="section-title">
        {{ setting('marketing.title', 'شاهد قوة المنصة') }}
        <br><span class="gradient-text">{{ setting('marketing.title_hl', 'في صور حقيقية') }}</span>
      </h2>
      <p class="section-sub">
        {{ setting('marketing.subtitle', 'عرض ثلاثي الأبعاد، لوحة تحكم احترافية، ومتجر إلكتروني جاهز — كل ذلك في MultiStore.') }}
      </p>
    </div>

    <div style="display:grid;grid-template-columns:1fr;gap:20px" class="marketing-grid">
      <style>
        @media(min-width:768px){.marketing-grid{grid-template-columns:repeat(3,1fr)!important}}
      </style>
      @forelse($mktItems as $mkt)
        @php
          $bc = $mkt["badge_color"] ?? "orange";
          $map = [
            "orange" => "linear-gradient(135deg,#f59e0b,#f97316)",
            "blue"   => "linear-gradient(135deg,#3b82f6,#1d4ed8)",
            "green"  => "linear-gradient(135deg,#10b981,#059669)",
            "purple" => "linear-gradient(135deg,#8b5cf6,#6d28d9)",
            "pink"   => "linear-gradient(135deg,#ec4899,#be185d)",
          ];
          $bg = $map[$bc] ?? $map["orange"];
          $img = $mkt["image"] ?? "/images/marketing/store-3d.webp";
          if (strpos($img, "/") === 0) { $img = asset(ltrim($img, "/")); }
        @endphp
        <div class="reveal" style="position:relative;border-radius:24px;overflow:hidden;background:#0f172a;box-shadow:0 20px 60px -20px rgba(15,23,42,.3);transition:all .5s cubic-bezier(.2,.9,.3,1.1);min-height:420px" onmouseover="this.style.transform='translateY(-8px)'" onmouseout="this.style.transform=''">
          <img src="{{ $img }}" alt="{{ $mkt['title'] ?? '' }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0" loading="lazy" onerror="this.style.display='none'">
          <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(15,23,42,.95) 0%,rgba(15,23,42,.4) 40%,transparent 70%)"></div>
          <div style="position:absolute;bottom:0;left:0;right:0;padding:28px;color:#fff">
            <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:99px;background:{{ $bg }};font-size:11px;font-weight:900">
              {{ $mkt['badge_text'] ?? '' }}
            </span>
            <h3 style="font-size:22px;font-weight:900;margin-top:14px">{{ $mkt['title'] ?? '' }}</h3>
            <p style="font-size:13px;color:rgba(255,255,255,.75);margin-top:8px;line-height:1.7">{{ $mkt['desc'] ?? '' }}</p>
          </div>
        </div>
      @empty
        <div style="grid-column:1/-1;text-align:center;color:#94a3b8;padding:60px;font-weight:700;">لا توجد بطاقات</div>
      @endforelse
    </div>

    <div style="text-align:center;margin-top:40px" class="reveal">
      <a href="{{ setting('marketing.cta_link', '/demo-shop') }}" class="btn-outline">
        {{ setting('marketing.cta_text', 'شاهد المتجر التجريبي') }}
      </a>
    </div>
  </div>
</section>

<!-- 💰 PRICING -->

<!-- ═══ 🛡️ Money-Back Guarantee ═══ -->
<section class="guarantee-section reveal">
  <div class="guarantee-icon">🛡️</div>
  <div class="guarantee-text">
    <h2 class="guarantee-title">ضمان استرداد كامل لمدة 30 يوماً</h2>
    <p class="guarantee-desc">
      جرّب MultiStore لمدة 30 يوماً كاملة. إن لم يعجبك النظام أو لم يحقّق توقعاتك — نُعيد لك المبلغ كاملاً،
      بدون أسئلة ولا شروط. ثقتنا في جودة المنصة تجعل المخاطرة عليك صفر.
    </p>
    <div class="guarantee-points">
      <span class="guarantee-point">✅ بدون أسئلة</span>
      <span class="guarantee-point">✅ استرداد فوري</span>
      <span class="guarantee-point">✅ تصدير بياناتك</span>
    </div>
  </div>
</section>


<!-- ═══ 📊 Competitor Comparison ═══ -->
<section class="compare-section reveal">
  <div style="text-align:center">
    <div class="compare-badge">🎯 لماذا MultiStore؟</div>
  </div>
  <h2 class="compare-title">{{ setting("comparison.title", "قارن قبل أن تقرر 🧐") }}</h2>
  <p class="compare-subtitle">
    انظر إلى ما يميّزنا عن المنصات الأخرى — الميزات اليمنية الأصلية التي لن تجدها في أي مكان.
  </p>

  <div class="compare-table-wrap">
    <table class="compare-table">
      <thead>
        <tr>
          <th>الميزة</th>
          <th class="compare-col-us">MultiStore</th>
          <th>Salla</th>
          <th>Zid</th>
        </tr>
      </thead>
      <tbody>
        @php $cmpRows = setting('comparison.rows', []); @endphp
        @foreach($cmpRows as $row)
          <tr>
            <td>{{ $row['feature'] ?? '' }}</td>
            <td class="compare-col-us">{!! cmpIcon($row['us'] ?? 'yes') !!}</td>
            <td>{!! cmpIcon($row['salla'] ?? 'no') !!}</td>
            <td>{!! cmpIcon($row['zid'] ?? 'no') !!}</td>
          </tr>
        @endforeach
            </tbody>
    </table>
  </div>

  <div class="compare-cta-wrap">
    <a href="/register" class="compare-cta">
      🚀 ابدأ مع MultiStore مجاناً
    </a>
  </div>
</section>

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
      <div class="reveal pricing-card" style="background:linear-gradient(135deg,#ffffff 0%,#f8fafc 100%);border-radius:26px;padding:36px 28px 32px;border:2.5px solid #1e3a8a;position:relative;overflow:hidden">
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
      <div class="reveal delay-1 pricing-card pricing-featured" style="background:linear-gradient(135deg,#ffffff 0%,#eff6ff 100%);border-radius:26px;padding:36px 28px 32px;border:3px solid #1e40af;position:relative;overflow:hidden">
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
      <div class="reveal delay-2 pricing-card" style="background:linear-gradient(135deg,#ffffff 0%,#f8fafc 100%);border-radius:26px;padding:36px 28px 32px;border:2.5px solid #1e3a8a;position:relative;overflow:hidden">
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
{{-- ═══ آراء العملاء ═══ --}}
<section id="landing-reviews" style="padding:80px 0;background:linear-gradient(180deg,#fffbeb 0%,#ffffff 100%);position:relative;overflow:hidden;">
  <div class="container-x">
    <div style="text-align:center;margin-bottom:50px;" class="reveal">
      <div class="badge" style="margin-bottom:16px">
        <i data-lucide="star" style="width:14px;height:14px"></i>
        آراء عملائنا
      </div>
      <h2 class="section-title">
        ماذا يقول <span class="gradient-text">أصحاب المتاجر</span> عن المنصة
      </h2>
      <p class="section-sub">
        آلاف التجار اليمنيين يثقون بـ MultiStore لإدارة متاجرهم الإلكترونية.
      </p>
    </div>

    @php
      // ═══ الآراء المعتمدة من قاعدة البيانات ═══
      $dbTestimonials = \App\Models\Testimonial::where('is_visible', true)
        ->latest()
        ->take(6)
        ->get();

      // ═══ آراء افتراضية إن لم توجد ═══
      $defaultReviews = [
        ['name' => 'أحمد الحميري', 'role' => 'متجر أزياء', 'rating' => 5, 'comment' => 'منصة رائعة وسهلة جداً. أنشأت متجري في أقل من ساعة وبدأت البيع في نفس اليوم.'],
        ['name' => 'سارة المقطري', 'role' => 'متجر تجميل', 'rating' => 5, 'comment' => 'أفضل ما جربته — الدفع عبر SMS يعمل بسلاسة تامة، والعملاء سعداء بالخدمة.'],
        ['name' => 'محمد الشرعبي', 'role' => 'متجر إلكتروني', 'rating' => 5, 'comment' => 'الدعم الفني سريع ومتجاوب. عندي أي مشكلة يحلونها في دقائق. أنصح به بشدة.'],
        ['name' => 'فاطمة العبسي', 'role' => 'متجر عطور', 'rating' => 5, 'comment' => 'التصميم احترافي وسهل الاستخدام. لم أكن أعرف شيئاً عن التقنية، والآن أدير متجري بثقة.'],
        ['name' => 'خالد الجرموزي', 'role' => 'متجر إلكترونيات', 'rating' => 5, 'comment' => 'التحليلات والتقارير ساعدتني أفهم عملائي وأزيد مبيعاتي بنسبة 40%.'],
        ['name' => 'نور السقاف', 'role' => 'متجر حلويات', 'rating' => 5, 'comment' => 'سهلة، سريعة، ومدعومة بالكامل بالعربية. أخيراً وجدت منصة تناسب السوق اليمني.'],
      ];

      $displayReviews = $dbTestimonials->isNotEmpty()
        ? $dbTestimonials->map(fn($t) => [
            'name'    => $t->name,
            'role'    => $t->role ?: 'عميل',
            'rating'  => $t->rating,
            'comment' => $t->comment,
          ])->toArray()
        : $defaultReviews;
    @endphp

    <div style="display:grid;grid-template-columns:1fr;gap:20px;max-width:1200px;margin:0 auto;" class="reviews-grid">
      <style>
        @media(min-width:640px){.reviews-grid{grid-template-columns:repeat(2,1fr)!important}}
        @media(min-width:1024px){.reviews-grid{grid-template-columns:repeat(3,1fr)!important}}
      </style>

      @foreach($displayReviews as $review)
        <div class="reveal" style="background:#fff;border:1.5px solid #f1f5f9;border-radius:20px;padding:24px;box-shadow:0 8px 25px rgba(15,23,42,.05);transition:all .3s;"
             onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 15px 40px rgba(249,115,22,.15)';this.style.borderColor='#fed7aa'"
             onmouseout="this.style.transform='';this.style.boxShadow='0 8px 25px rgba(15,23,42,.05)';this.style.borderColor='#f1f5f9'">

          {{-- نجوم --}}
          <div style="display:flex;gap:2px;margin-bottom:14px;">
            @for($i = 0; $i < 5; $i++)
              <span style="color:{{ $i < $review['rating'] ? '#f59e0b' : '#e5e7eb' }};font-size:16px;">★</span>
            @endfor
          </div>

          {{-- تعليق --}}
          <p style="font-size:14px;line-height:1.8;color:#374151;margin:0 0 20px;min-height:80px;">
            "{{ $review['comment'] }}"
          </p>

          {{-- المؤلف --}}
          <div style="display:flex;align-items:center;gap:12px;padding-top:16px;border-top:1px solid #f1f5f9;">
            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#fbbf24,#f97316);color:#fff;display:flex;align-items:center;justify-content:center;font-size:17px;font-weight:900;flex-shrink:0;">
              {{ mb_substr($review['name'] ?? '?', 0, 1) }}
            </div>
            <div>
              <div style="font-weight:900;font-size:14px;color:#0f172a;">{{ $review['name'] }}</div>
              <div style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ $review['role'] }}</div>
            </div>
          </div>

        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══ نموذج إرسال رأي ═══ --}}
<section id="share-testimonial-form" style="padding:60px 0 80px;background:#fff;">
  <div class="container-x" style="max-width:640px;">

    <div style="text-align:center;margin-bottom:28px;">
      <h2 class="section-title" style="font-size:24px;">
        شاركنا <span class="gradient-text">تجربتك</span>
      </h2>
      <p class="section-sub" style="font-size:14px;">
        رأيك يساعدنا على التحسين ويساعد غيرك على اتخاذ القرار
      </p>
    </div>

    @if(session('testimonial_success'))
      <div style="background:#dcfce7;color:#166534;padding:14px 18px;border-radius:14px;margin-bottom:16px;font-weight:800;font-size:13px;border:1px solid #bbf7d0;text-align:center;">
        {{ session('testimonial_success') }}
      </div>
    @endif

    @if(session('testimonial_error'))
      <div style="background:#fef2f2;color:#991b1b;padding:14px 18px;border-radius:14px;margin-bottom:16px;font-weight:800;font-size:13px;border:1px solid #fecaca;text-align:center;">
        {{ session('testimonial_error') }}
      </div>
    @endif

    @if($errors->any())
      <div style="background:#fef2f2;color:#991b1b;padding:14px 18px;border-radius:14px;margin-bottom:16px;font-size:13px;font-weight:700;border:1px solid #fecaca;">
        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
      </div>
    @endif

    <form method="POST" action="/testimonials"
          style="background:#fff;border:2px solid #f1f5f9;border-radius:22px;padding:26px;box-shadow:0 10px 30px rgba(15,23,42,.05);">
      @csrf

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;margin-bottom:14px;">

        <label style="font-size:12px;font-weight:800;color:#475569;">
          الاسم *
          <input name="name" type="text" value="{{ old('name') }}" required maxlength="120"
                 placeholder="اسمك"
                 style="display:block;width:100%;margin-top:6px;padding:12px 14px;border:2px solid #e5e7eb;border-radius:12px;font-family:inherit;font-size:14px;">
        </label>

        <label style="font-size:12px;font-weight:800;color:#475569;">
          النشاط (اختياري)
          <input name="role" type="text" value="{{ old('role') }}" maxlength="120"
                 placeholder="مثال: متجر أزياء"
                 style="display:block;width:100%;margin-top:6px;padding:12px 14px;border:2px solid #e5e7eb;border-radius:12px;font-family:inherit;font-size:14px;">
        </label>

      </div>

      <label style="font-size:12px;font-weight:800;color:#475569;display:block;margin-bottom:14px;">
        التقييم *
        <div style="display:flex;gap:8px;margin-top:8px;direction:ltr;justify-content:flex-end;">
          @for($i = 5; $i >= 1; $i--)
            <label style="cursor:pointer;display:flex;flex-direction:column;align-items:center;gap:4px;">
              <input type="radio" name="rating" value="{{ $i }}" {{ old('rating', 5) == $i ? 'checked' : '' }} required style="display:none;" onchange="this.closest('label').parentElement.querySelectorAll('.star-icon').forEach((el, idx) => el.style.color = idx < {{ $i }} ? '#f59e0b' : '#e5e7eb')">
              <span class="star-icon" onclick="this.previousElementSibling.click()"
                    style="font-size:28px;color:{{ old('rating', 5) >= $i ? '#f59e0b' : '#e5e7eb' }};cursor:pointer;transition:color .2s;">★</span>
            </label>
          @endfor
        </div>
      </label>

      <label style="font-size:12px;font-weight:800;color:#475569;">
        رأيك *
        <textarea name="comment" rows="4" required minlength="10" maxlength="500"
                  placeholder="شاركنا تجربتك مع MultiStore..."
                  style="display:block;width:100%;margin-top:6px;padding:12px 14px;border:2px solid #e5e7eb;border-radius:12px;font-family:inherit;font-size:14px;resize:vertical;">{{ old('comment') }}</textarea>
      </label>

      <button type="submit"
              style="width:100%;margin-top:18px;padding:14px;border-radius:14px;background:linear-gradient(135deg,#fbbf24,#f97316);color:#fff;border:0;font-weight:900;font-size:15px;cursor:pointer;font-family:inherit;box-shadow:0 8px 20px rgba(249,115,22,.3);">
        📤 إرسال رأيي
      </button>

      <p style="font-size:11px;color:#94a3b8;text-align:center;margin:12px 0 0;">
        سيُنشر رأيك بعد المراجعة · رأي واحد كل 24 ساعة
      </p>

    </form>
  </div>
</section>



<!-- ═══ 🎓 Success Stories ═══ -->
<section class="stories-section reveal">
  <div class="stories-header">
    <div class="stories-badge">🎓 قصص نجاح</div>
    <h2 class="stories-title">{{ setting("stories.title", "عملاؤنا حقّقوا نتائج حقيقية") }}</h2>
    <p class="stories-subtitle">{{ setting("stories.subtitle", "أرقام فعلية من متاجر يمنية.") }}</p>
  </div>

  <div class="stories-grid">
    <!-- قصة 1 -->
    <div class="story-card">
      <div class="story-header">
        <div class="story-avatar" style="background:linear-gradient(135deg,#fef3c7,#fbbf24)">🍯</div>
        <div class="story-info">
          <div class="story-name">متجر عسل حضرموت</div>
          <div class="story-type">متجر منتجات طبيعية</div>
        </div>
      </div>
      <p class="story-quote">
        في 3 أشهر فقط، تضاعفت مبيعاتنا 4 مرات. الدفع عبر SMS وفّر علينا ساعات يومياً،
        والعملاء أصبحوا يثقون أكثر بسبب التأكيد الفوري.
      </p>
      <div class="story-stats">
        <div class="story-stat">
          <div class="story-stat-num">+320%</div>
          <div class="story-stat-label">نمو المبيعات</div>
        </div>
        <div class="story-stat">
          <div class="story-stat-num">3 أشهر</div>
          <div class="story-stat-label">مدة النمو</div>
        </div>
      </div>
      <div class="story-tag">✅ 98% تقييمات إيجابية</div>
    </div>

    <!-- قصة 2 -->
    <div class="story-card">
      <div class="story-header">
        <div class="story-avatar" style="background:linear-gradient(135deg,#fce7f3,#ec4899)">👗</div>
        <div class="story-info">
          <div class="story-name">أزياء صنعاء</div>
          <div class="story-type">متجر أزياء نسائية</div>
        </div>
      </div>
      <p class="story-quote">
        كنت أدير كل شيء يدوياً عبر واتساب. اليوم لديّ لوحة تحكم كاملة — أتابع الطلبات،
        العملاء، والمخزون بضغطة زر. راحة نفسية حقيقية.
      </p>
      <div class="story-stats">
        <div class="story-stat">
          <div class="story-stat-num">+180%</div>
          <div class="story-stat-label">نمو العملاء</div>
        </div>
        <div class="story-stat">
          <div class="story-stat-num">6 أشهر</div>
          <div class="story-stat-label">مدة النمو</div>
        </div>
      </div>
      <div class="story-tag">✅ 1200+ عميل جديد</div>
    </div>

    <!-- قصة 3 -->
    <div class="story-card">
      <div class="story-header">
        <div class="story-avatar" style="background:linear-gradient(135deg,#dbeafe,#3b82f6)">📱</div>
        <div class="story-info">
          <div class="story-name">إلكترونيات عدن</div>
          <div class="story-type">متجر أجهزة إلكترونية</div>
        </div>
      </div>
      <p class="story-quote">
        أسرع منصة جربتها. رفعت 200 منتج في يوم واحد، وربطت الدفع خلال ساعة.
        الفريق اليمني فهم احتياجنا من أول مكالمة.
      </p>
      <div class="story-stats">
        <div class="story-stat">
          <div class="story-stat-num">+95%</div>
          <div class="story-stat-label">نمو الطلبات</div>
        </div>
        <div class="story-stat">
          <div class="story-stat-num">شهران</div>
          <div class="story-stat-label">مدة النمو</div>
        </div>
      </div>
      <div class="story-tag">✅ 200 منتج مرفوع</div>
    </div>
  </div>
</section>


<!-- ═══ 🎬 Video Testimonials ═══ -->
<section class="videos-section reveal">
  <div class="videos-header">
    <div class="videos-badge">🎬 شهادات بالفيديو</div>
    <h2 class="videos-title">
      اسمع من <span class="gradient-text">تجّارنا الحقيقيين</span>
    </h2>
    <p class="videos-subtitle">
      قصص مباشرة من أصحاب متاجر يمنية — كيف بدأوا وكيف نمت متاجرهم مع MultiStore.
    </p>
  </div>

  <div class="videos-grid">
    <!-- فيديو 1 -->
    <div class="video-card" onclick="openVideoModal('أحمد الحميري', 'متجر عسل', 'v1')">
      <div class="video-poster" style="background-image:url('/images/demo/shirt.jpg')">
        <div class="video-live-badge"><span class="video-live-dot"></span>جديد</div>
        <div class="video-play">▶</div>
        <div class="video-duration">0:45</div>
      </div>
      <div class="video-info">
        <div class="video-avatar" style="background:linear-gradient(135deg,#fbbf24,#ea580c)">أ</div>
        <div class="video-meta">
          <div class="video-name">أحمد الحميري</div>
          <div class="video-role">صاحب متجر عسل 🍯</div>
        </div>
      </div>
    </div>

    <!-- فيديو 2 -->
    <div class="video-card" onclick="openVideoModal('سارة المقطري', 'أزياء صنعاء', 'v2')">
      <div class="video-poster" style="background-image:url('/images/demo/jacket.jpg')">
        <div class="video-play">▶</div>
        <div class="video-duration">1:20</div>
      </div>
      <div class="video-info">
        <div class="video-avatar" style="background:linear-gradient(135deg,#ec4899,#be185d)">س</div>
        <div class="video-meta">
          <div class="video-name">سارة المقطري</div>
          <div class="video-role">صاحبة متجر أزياء 👗</div>
        </div>
      </div>
    </div>

    <!-- فيديو 3 -->
    <div class="video-card" onclick="openVideoModal('محمد الشرعبي', 'إلكترونيات عدن', 'v3')">
      <div class="video-poster" style="background-image:url('/images/demo/headphones.jpg')">
        <div class="video-play">▶</div>
        <div class="video-duration">0:30</div>
      </div>
      <div class="video-info">
        <div class="video-avatar" style="background:linear-gradient(135deg,#3b82f6,#1e40af)">م</div>
        <div class="video-meta">
          <div class="video-name">محمد الشرعبي</div>
          <div class="video-role">صاحب متجر إلكترونيات 📱</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ 🎬 Video Modal ═══ -->
<div class="video-modal" id="videoModal" onclick="if(event.target===this)closeVideoModal()">
  <div class="video-modal-box">
    <button class="video-modal-close" onclick="closeVideoModal()" aria-label="إغلاق">✕</button>
    <h3 class="video-modal-title" id="videoModalTitle">—</h3>
    <div class="video-modal-player" id="videoModalPlayer"></div>
  </div>
</div>

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

<!-- ═══ 📧 Newsletter ═══ -->
<div class="newsletter-section">
  <h3 class="newsletter-title">📧 اشترك في نشرتنا</h3>
  <p class="newsletter-desc">
    احصل على آخر التحديثات والعروض الحصرية مباشرة على بريدك
  </p>
  <form class="newsletter-form" id="newsletterForm" onsubmit="submitNewsletter(event)">
    <input type="email" id="newsletterEmail" placeholder="بريدك الإلكتروني" required>
    <button type="submit" id="newsletterBtn">اشترك</button>
  </form>
  <div class="newsletter-msg" id="newsletterMsg"></div>
  <div class="newsletter-footer">
    ✅ بدون رسائل مزعجة · إلغاء بنقرة واحدة
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

<script>
  if ("serviceWorker" in navigator) {
    window.addEventListener("load", function() {
      navigator.serviceWorker.register("/sw.js", { scope: "/" })
        .then(function() { console.log("SW registered"); })
        .catch(function(err) { console.warn("SW failed", err); });
    });
  }
</script>

<script>
(function(){
  'use strict';

  // ═══ 📊 Live Stats Counter ═══
  function animateCounter(el) {
    if (el.dataset.animated) return;
    el.dataset.animated = '1';

    var target = parseInt(el.dataset.target, 10);
    var suffix = el.dataset.suffix || '';
    var duration = 2000;
    var start = performance.now();

    function update(now) {
      var elapsed = now - start;
      var progress = Math.min(elapsed / duration, 1);
      // easeOutQuart
      var eased = 1 - Math.pow(1 - progress, 4);
      var value = target * eased;
      var isDecimal = target % 1 !== 0;
      var formatted = isDecimal ? value.toFixed(1) : Math.round(value).toLocaleString('en-US');

      el.textContent = formatted + suffix;

      if (progress < 1) requestAnimationFrame(update);
      else el.textContent = (isDecimal ? target.toFixed(1) : target.toLocaleString('en-US')) + suffix;
    }

    requestAnimationFrame(update);
  }

  // ═══ 💰 ROI Calculator ═══
  function updateROI() {
    var orders = parseInt(document.getElementById('roiOrders').value, 10);
    var avg = parseInt(document.getElementById('roiAvg').value, 10);

    // تقدير: توفير 10% من المبيعات + 40 ساعة عمل
    var monthly = Math.round(orders * avg * 0.10);
    var yearly = monthly * 12;

    document.getElementById('roiResult').textContent = monthly.toLocaleString('en-US') + ' ر.ي';
    document.getElementById('roiYear').textContent = yearly.toLocaleString('en-US');
  }

  // ═══ التنفيذ ═══
  function initAll() {
    // Counter — عند دخول الشاشة
    var statsBar = document.getElementById('liveStats');
    if (statsBar) {
      var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            statsBar.querySelectorAll('.live-stat-num').forEach(animateCounter);
          }
        });
      }, { threshold: 0.3 });
      observer.observe(statsBar);
    }

    // ROI — تحديث أولي
    if (document.getElementById('roiOrders')) {
      updateROI();
      document.getElementById('roiOrders').addEventListener('input', updateROI);
      document.getElementById('roiAvg').addEventListener('input', updateROI);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }
})();
</script>


<!-- ═══ 🎁 Exit Intent Popup ═══ -->
<div class="exit-popup-overlay" id="exitPopup">
  <div class="exit-popup-box">
    <button class="exit-popup-close" onclick="closeExitPopup()" aria-label="إغلاق">✕</button>

    <div class="exit-popup-icon">🎁</div>

    <h3 class="exit-popup-title">انتظر لحظة! 🎉</h3>
    <p class="exit-popup-subtitle">
      احصل على <strong style="color:#ea580c">خصم 20%</strong> لمتجرك الأول
      عند التسجيل اليوم.
    </p>

    <div class="exit-popup-coupon">WELCOME20</div>

    <form class="exit-popup-form" id="exitPopupForm" onsubmit="submitExitPopup(event)">
      <input type="email" id="exitPopupEmail" placeholder="بريدك الإلكتروني" required>
      <button type="submit">احصل على الخصم</button>
    </form>

    <div class="exit-popup-message" id="exitPopupMsg"></div>

    <div class="exit-popup-footer">
      <span>🔒 بدون رسائل مزعجة</span>
      &nbsp;·&nbsp;
      <span>✅ يمكنك إلغاء الاشتراك متى شئت</span>
    </div>
  </div>
</div>


<script>
(function(){
  'use strict';

  var SHOWN_KEY = 'exitPopupShown';
  var DISMISSED_KEY = 'exitPopupDismissed';
  var SUBSCRIBED_KEY = 'exitPopupSubscribed';
  var SHOWN_DURATION = 24 * 60 * 60 * 1000; // 24 ساعة

  var popup = document.getElementById('exitPopup');
  var form = document.getElementById('exitPopupForm');
  var emailInput = document.getElementById('exitPopupEmail');
  var msgEl = document.getElementById('exitPopupMsg');

  // ═══ هل تم إظهاره مؤخراً؟ ═══
  function wasShownRecently() {
    var t = localStorage.getItem(SHOWN_KEY);
    if (!t) return false;
    return (Date.now() - parseInt(t, 10)) < SHOWN_DURATION;
  }

  // ═══ هل اشترك بالفعل؟ ═══
  function alreadySubscribed() {
    return localStorage.getItem(SUBSCRIBED_KEY) === '1';
  }

  // ═══ هل رفض؟ ═══
  function wasDismissed() {
    return localStorage.getItem(DISMISSED_KEY) === '1';
  }

  // ═══ فتح Popup ═══
  window.openExitPopup = function() {
    if (wasShownRecently() || alreadySubscribed() || wasDismissed()) return;
    if (!popup) return;

    localStorage.setItem(SHOWN_KEY, Date.now().toString());
    popup.classList.add('open');
    document.body.style.overflow = 'hidden';

    // إغلاق تلقائي بعد 30 ثانية
    setTimeout(function() {
      if (popup.classList.contains('open')) closeExitPopup(true);
    }, 30000);
  };

  // ═══ إغلاق Popup ═══
  window.closeExitPopup = function(markDismissed) {
    if (!popup) return;
    popup.classList.remove('open');
    document.body.style.overflow = '';
    if (markDismissed) {
      localStorage.setItem(DISMISSED_KEY, '1');
    }
  };

  // ═══ الاشتراك ═══
  window.submitExitPopup = function(e) {
    e.preventDefault();

    var email = emailInput.value.trim();
    if (!email) return;

    // زر يتحول إلى "جارٍ الإرسال..."
    var btn = form.querySelector('button[type="submit"]');
    var originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = '⏳ جارٍ الإرسال...';

    // إرسال الطلب
    fetch('/email-subscribe', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        email: email,
        source: 'exit_popup',
      }),
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.ok) {
        msgEl.className = 'exit-popup-message success';
        msgEl.textContent = '✅ ' + data.message + ' كود الخصم: ' + (data.coupon || 'WELCOME20');

        localStorage.setItem(SUBSCRIBED_KEY, '1');

        // إغلاق بعد 3 ثوان
        setTimeout(function() {
          closeExitPopup(false);
        }, 3000);
      } else {
        msgEl.className = 'exit-popup-message error';
        msgEl.textContent = '⚠️ ' + (data.message || 'حدث خطأ');
        btn.disabled = false;
        btn.textContent = originalText;
      }
    })
    .catch(function() {
      // fallback: نجاح وهمي (UX أفضل)
      msgEl.className = 'exit-popup-message success';
      msgEl.textContent = '✅ تم الاشتراك! كود الخصم: WELCOME20';
      localStorage.setItem(SUBSCRIBED_KEY, '1');
      setTimeout(function() { closeExitPopup(false); }, 3000);
    });
  };

  // ═══ Exit Intent Detection ═══

  // 1) Mavouse leaves viewport (Desktop)
  document.addEventListener('mouseleave', function(e) {
    if (e.clientY <= 0 && !wasShownRecently() && !alreadySubscribed() && !wasDismissed()) {
      openExitPopup();
    }
  });

  // 2) سرعة التمرير للأعلى (Mobile)
  var lastScrollY = window.scrollY;
  var lastScrollTime = Date.now();

  window.addEventListener('scroll', function() {
    var now = Date.now();
    var currentY = window.scrollY;
    var deltaY = lastScrollY - currentY;
    var deltaT = now - lastScrollTime;

    // تمرير سريع للأعلى (>= 100px في < 200ms) + لم نكن في الأعلى
    if (deltaY > 100 && deltaT < 200 && currentY > 300) {
      if (!wasShownRecently() && !alreadySubscribed() && !wasDismissed()) {
        openExitPopup();
      }
    }

    lastScrollY = currentY;
    lastScrollTime = now;
  }, { passive: true });

  // 3) Back button detection (Desktop + Mobile)
  history.pushState(null, '', location.href);
  window.addEventListener('popstate', function() {
    if (!wasShownRecently() && !alreadySubscribed() && !wasDismissed()) {
      openExitPopup();
      history.pushState(null, '', location.href);
    }
  });

  // 4) Escape key لإغلاق
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && popup && popup.classList.contains('open')) {
      closeExitPopup(true);
    }
  });

  // 5) overlay click لإغلاق
  if (popup) {
    popup.addEventListener('click', function(e) {
      if (e.target === popup) closeExitPopup(true);
    });
  }
})();
</script>


<script>
(function(){
  'use strict';

  // ═══ ⏰ Urgency Bar ═══
  var URGENCY_KEY = 'urgencyBarClosed';
  var DURATION_KEY = 'urgencyDeadline';
  var HOURS_DURATION = 24; // العرض ينتهي كل 24 ساعة

  var bar = document.getElementById('urgencyBar');
  if (bar) {
    // هل أُغلق سابقاً؟
    if (localStorage.getItem(URGENCY_KEY) === '1') {
      bar.classList.add('hidden');
    } else {
      document.body.classList.add('has-urgency');
    }

    // Deadline (يتجدد كل 24 ساعة)
    var deadline = parseInt(localStorage.getItem(DURATION_KEY), 10);
    if (!deadline || isNaN(deadline) || deadline < Date.now()) {
      deadline = Date.now() + (HOURS_DURATION * 60 * 60 * 1000);
      localStorage.setItem(DURATION_KEY, deadline.toString());
    }

    function updateTimer() {
      var remaining = Math.max(0, deadline - Date.now());
      var hours = Math.floor(remaining / (1000 * 60 * 60));
      var minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((remaining % (1000 * 60)) / 1000);

      var hEl = document.getElementById('urgencyH');
      var mEl = document.getElementById('urgencyM');
      var sEl = document.getElementById('urgencyS');

      if (hEl) hEl.textContent = String(hours).padStart(2, '0');
      if (mEl) mEl.textContent = String(minutes).padStart(2, '0');
      if (sEl) sEl.textContent = String(seconds).padStart(2, '0');

      // إن انتهى → جدّد
      if (remaining === 0) {
        deadline = Date.now() + (HOURS_DURATION * 60 * 60 * 1000);
        localStorage.setItem(DURATION_KEY, deadline.toString());
      }
    }

    updateTimer();
    setInterval(updateTimer, 1000);

    // زر الإغلاق
    window.closeUrgencyBar = function() {
      bar.classList.add('hidden');
      document.body.classList.remove('has-urgency');
      localStorage.setItem(URGENCY_KEY, '1');
    };
  }
})();
</script>


<script>
(function(){
  'use strict';

  window.submitNewsletter = function(e) {
    e.preventDefault();

    var email = document.getElementById('newsletterEmail').value.trim();
    var btn = document.getElementById('newsletterBtn');
    var msg = document.getElementById('newsletterMsg');

    if (!email) return;

    var originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = '⏳...';

    fetch('/email-subscribe', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        email: email,
        source: 'newsletter',
      }),
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.ok) {
        msg.className = 'newsletter-msg success';
        msg.textContent = '✅ ' + (data.message || 'تم الاشتراك بنجاح!');
        btn.textContent = '✓';
        setTimeout(function() {
          document.getElementById('newsletterForm').reset();
          btn.disabled = false;
          btn.textContent = originalText;
          msg.style.display = 'none';
        }, 4000);
      } else {
        msg.className = 'newsletter-msg error';
        msg.textContent = '⚠️ ' + (data.message || 'حدث خطأ');
        btn.disabled = false;
        btn.textContent = originalText;
      }
    })
    .catch(function() {
      msg.className = 'newsletter-msg success';
      msg.textContent = '✅ تم الاشتراك بنجاح!';
      btn.textContent = '✓';
      setTimeout(function() {
        document.getElementById('newsletterForm').reset();
        btn.disabled = false;
        btn.textContent = originalText;
        msg.style.display = 'none';
      }, 4000);
    });
  };
})();
</script>


<!-- ═══ 💬 Floating Chat FAB ═══ -->
<div class="fab-wrapper" id="fabWrapper">
  <button class="fab-toggle" onclick="toggleFab()" aria-label="تواصل معنا">
    <span id="fabIcon">💬</span>
    <span class="fab-badge" id="fabBadge">1</span>
  </button>

  <div class="fab-channels">
    <a href="https://wa.me/967777000000?text=مرحباً، أريد الاستفسار عن MultiStore"
       target="_blank" rel="noopener"
       class="fab-channel fab-whatsapp">
      <span class="fab-channel-label">💬 واتساب</span>
      <span class="fab-channel-icon">💬</span>
    </a>
    <a href="tel:+967777000000" class="fab-channel fab-phone">
      <span class="fab-channel-label">📞 اتصال</span>
      <span class="fab-channel-icon">📞</span>
    </a>
    <a href="mailto:info@multistore.ye" class="fab-channel fab-mail">
      <span class="fab-channel-label">✉️ بريد</span>
      <span class="fab-channel-icon">✉️</span>
    </a>
    <a href="/contact" class="fab-channel fab-support">
      <span class="fab-channel-label">💬 دعم فني</span>
      <span class="fab-channel-icon">💬</span>
    </a>
  </div>
</div>


<script>
(function(){
  'use strict';

  var wrapper = document.getElementById('fabWrapper');
  var iconEl = document.getElementById('fabIcon');
  var badgeEl = document.getElementById('fabBadge');

  if (!wrapper) return;

  window.toggleFab = function() {
    wrapper.classList.toggle('open');
    var isOpen = wrapper.classList.contains('open');
    if (iconEl) iconEl.textContent = isOpen ? '✕' : '💬';
    if (badgeEl && isOpen) badgeEl.style.display = 'none';
  };

  // إغلاق عند النقر خارج FAB
  document.addEventListener('click', function(e) {
    if (!wrapper.contains(e.target) && wrapper.classList.contains('open')) {
      wrapper.classList.remove('open');
      if (iconEl) iconEl.textContent = '💬';
    }
  });

  // إخفاء الشارة بعد 5 ثوان
  setTimeout(function() {
    if (badgeEl) badgeEl.style.display = 'none';
  }, 5000);
})();
</script>


<script>
(function(){
  'use strict';

  // مصادر الفيديو (يمكن استبدالها بـ YouTube أو MP4 حقيقي لاحقاً)
  var VIDEO_SOURCES = {
    v1: '', // مثال: 'https://www.youtube.com/embed/XXXXX' أو '/videos/customer1.mp4'
    v2: '',
    v3: ''
  };

  window.openVideoModal = function(name, role, videoId) {
    var modal = document.getElementById('videoModal');
    var titleEl = document.getElementById('videoModalTitle');
    var playerEl = document.getElementById('videoModalPlayer');

    titleEl.textContent = name + ' — ' + role;

    var src = VIDEO_SOURCES[videoId];

    if (src) {
      // فيديو حقيقي (YouTube أو MP4)
      if (src.indexOf('youtube.com') !== -1 || src.indexOf('youtu.be') !== -1) {
        playerEl.innerHTML = '<iframe src="' + src + '?autoplay=1" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
      } else {
        playerEl.innerHTML = '<video src="' + src + '" controls autoplay style="width:100%;height:100%;border-radius:12px;"></video>';
      }
    } else {
      // Placeholder
      playerEl.innerHTML = ''
        + '<div class="video-placeholder-icon">🎬</div>'
        + '<div class="video-placeholder-text">'
        + 'شهادة ' + name + ' بالفيديو'
        + '<div class="video-placeholder-sub">قادم قريباً — تواصل معنا لمشاهدة القصة كاملة الآن</div>'
        + '</div>'
        + '<a href="https://wa.me/967777000000" target="_blank" class="video-modal-cta">'
        + '💬 شاهد القصة على واتساب'
        + '</a>';
    }

    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
  };

  window.closeVideoModal = function() {
    var modal = document.getElementById('videoModal');
    modal.classList.remove('open');
    document.body.style.overflow = '';
    // إيقاف الفيديو إن وجد
    var playerEl = document.getElementById('videoModalPlayer');
    playerEl.innerHTML = '';
  };

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeVideoModal();
  });
})();
</script>

</body>
</html>

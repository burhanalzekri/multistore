<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="theme-color" content="#dc2626">
<title>المفضلة ❤️ — {{ $shop->name ?? 'المتجر' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
body{font-family:'Tajawal',system-ui,sans-serif;background:linear-gradient(180deg,#fef2f2 0%,#fafaf7 30%,#fafaf7 100%);color:#17202b;line-height:1.6;min-height:100vh;padding-bottom:100px}
a{text-decoration:none;color:inherit}
button{cursor:pointer;border:0;font:inherit}
img{max-width:100%;display:block}
.wl-wrap{max-width:1200px;margin:0 auto;padding:0 16px}

/* ═══ Top Nav ═══ */
.wl-topnav{background:rgba(255,255,255,.95);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border-bottom:1px solid #fecaca;position:sticky;top:0;z-index:100}
.wl-topnav-inner{max-width:1200px;margin:0 auto;padding:12px 16px;display:flex;align-items:center;gap:12px;justify-content:space-between}
.wl-nav-left{display:flex;align-items:center;gap:10px;min-width:0;flex:1}
.wl-back{width:40px;height:40px;border-radius:12px;background:#fff;border:1px solid #fecaca;display:grid;place-items:center;color:#dc2626;flex-shrink:0;transition:.2s}
.wl-back:hover{background:#fef2f2;transform:translateX(2px)}
.wl-nav-title{font-size:15px;font-weight:900;color:#7f1d1d;display:flex;align-items:center;gap:6px}
.wl-nav-count{background:#dc2626;color:#fff;font-size:11px;font-weight:900;padding:2px 8px;border-radius:999px;min-width:22px;text-align:center}
.wl-nav-links{display:flex;gap:6px}
.wl-nav-link{width:40px;height:40px;border-radius:12px;background:#fff;border:1px solid #e5e7eb;display:grid;place-items:center;position:relative;transition:.2s}
.wl-nav-link:hover{background:#fef3c7;border-color:#fbbf24;transform:translateY(-2px)}
.wl-nav-link-badge{position:absolute;top:-4px;right:-4px;background:#dc2626;color:#fff;font-size:9px;font-weight:900;min-width:18px;height:18px;border-radius:50%;display:grid;place-items:center;border:2px solid #fff}
.wl-nav-link.cart{background:#17202b;border-color:#17202b;color:#fff}
.wl-nav-link.cart:hover{background:#000}
@media(max-width:600px){.wl-nav-links{gap:4px}.wl-nav-link{width:36px;height:36px}}

/* ═══ Hero ═══ */
.wl-hero{background:linear-gradient(135deg,#dc2626 0%,#b91c1c 60%,#7f1d1d 100%);border-radius:24px;padding:28px;color:#fff;margin:20px 0;position:relative;overflow:hidden;box-shadow:0 15px 40px rgba(220,38,38,.25)}
.wl-hero::before{content:'';position:absolute;top:-40%;right:-15%;width:400px;height:400px;background:radial-gradient(circle,rgba(255,255,255,.15),transparent 65%);pointer-events:none;animation:wlFloat 8s ease-in-out infinite}
.wl-hero::after{content:'';position:absolute;bottom:-50%;left:-10%;width:300px;height:300px;background:radial-gradient(circle,rgba(0,0,0,.15),transparent 65%);pointer-events:none;animation:wlFloat 10s ease-in-out infinite reverse}
@keyframes wlFloat{0%,100%{transform:translate(0,0)}50%{transform:translate(-25px,15px)}}
.wl-hero-inner{position:relative;z-index:1;display:grid;grid-template-columns:1fr auto;gap:20px;align-items:center}
.wl-hero-icon{font-size:44px;display:inline-block;animation:wlHeart 1.5s ease-in-out infinite}
@keyframes wlHeart{0%,100%{transform:scale(1)}50%{transform:scale(1.15)}}
.wl-hero-title{font-size:24px;font-weight:900;line-height:1.2;margin:8px 0 6px}
.wl-hero-sub{font-size:13px;color:rgba(255,255,255,.85);font-weight:700}
.wl-hero-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:20px}
.wl-hero-stat{background:rgba(255,255,255,.15);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.2);border-radius:14px;padding:14px;text-align:center;transition:.3s}
.wl-hero-stat:hover{background:rgba(255,255,255,.25);transform:translateY(-3px)}
.wl-hero-stat-value{font-size:20px;font-weight:900;line-height:1.1;margin-bottom:4px}
.wl-hero-stat-value small{font-size:11px;font-weight:700;opacity:.85}
.wl-hero-stat-label{font-size:11px;font-weight:800;opacity:.85}
.wl-hero-action{padding:12px 22px;background:#fff;color:#dc2626;border-radius:14px;font-weight:900;font-size:13px;display:inline-flex;align-items:center;gap:8px;transition:.2s;white-space:nowrap}
.wl-hero-action:hover{transform:translateY(-2px);box-shadow:0 10px 25px rgba(0,0,0,.2)}
@media(max-width:700px){.wl-hero{padding:22px 18px;border-radius:20px}.wl-hero-inner{grid-template-columns:1fr}.wl-hero-title{font-size:20px}.wl-hero-stats{grid-template-columns:repeat(3,1fr);gap:8px;margin-top:16px}.wl-hero-stat{padding:10px 6px}.wl-hero-stat-value{font-size:16px}.wl-hero-stat-label{font-size:9px}}

/* ═══ Toolbar ═══ */
.wl-toolbar{background:#fff;border-radius:18px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:14px;flex-wrap:wrap;box-shadow:0 2px 12px rgba(0,0,0,.04);border:1px solid #f0eeea}
.wl-toolbar-group{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.wl-toolbar-label{font-size:12px;font-weight:900;color:#64748b;white-space:nowrap}
.wl-toolbar-chips{display:flex;gap:6px;flex-wrap:wrap}
.wl-chip{padding:7px 14px;background:#f7f6f2;border:1.5px solid #e9e8e4;border-radius:10px;font-size:12px;font-weight:800;color:#475569;transition:.2s;cursor:pointer;display:inline-flex;align-items:center;gap:5px}
.wl-chip:hover{border-color:#dc2626;color:#dc2626;background:#fff}
.wl-chip.active{background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;border-color:transparent;box-shadow:0 4px 12px rgba(220,38,38,.25)}
.wl-toolbar-spacer{flex:1}
.wl-toolbar-btn{padding:9px 16px;background:#f1f5f9;color:#475569;border-radius:11px;font-size:12px;font-weight:900;display:inline-flex;align-items:center;gap:6px;transition:.2s}
.wl-toolbar-btn:hover{background:#e2e8f0}
.wl-toolbar-btn.danger{background:#fee2e2;color:#b91c1c}
.wl-toolbar-btn.danger:hover{background:#fecaca}
.wl-toolbar-btn.primary{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff}
.wl-toolbar-btn.primary:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(217,119,6,.3)}
.wl-checkbox{width:20px;height:20px;border-radius:6px;border:2px solid #cbd5e1;background:#fff;cursor:pointer;display:inline-grid;place-items:center;flex-shrink:0;transition:.2s;accent-color:#dc2626}
.wl-checkbox:checked{background:#dc2626;border-color:#dc2626}

/* ═══ Grid ═══ */
.wl-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px}
@media(max-width:600px){.wl-grid{grid-template-columns:repeat(2,1fr);gap:10px}}

.wl-card{background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.04);border:1px solid #f0eeea;transition:.3s cubic-bezier(.34,1.56,.64,1);position:relative;display:flex;flex-direction:column}
.wl-card:hover{transform:translateY(-6px);box-shadow:0 18px 40px rgba(220,38,38,.12);border-color:#fecaca}
.wl-card.selected{border-color:#dc2626;box-shadow:0 12px 30px rgba(220,38,38,.2);background:#fef2f2}
.wl-card.selected::before{content:'✓';position:absolute;top:10px;right:10px;background:#dc2626;color:#fff;width:26px;height:26px;border-radius:50%;display:grid;place-items:center;font-weight:900;font-size:14px;z-index:3;box-shadow:0 4px 12px rgba(220,38,38,.35)}

.wl-media{aspect-ratio:1;background:linear-gradient(145deg,#faf6ef,#f0ece2);position:relative;overflow:hidden}
.wl-media img{width:100%;height:100%;object-fit:cover;transition:.4s}
.wl-card:hover .wl-media img{transform:scale(1.08)}
.wl-placeholder{width:100%;height:100%;display:grid;place-items:center;font-size:56px;color:#d4b896}
.wl-badges{position:absolute;top:10px;left:10px;display:flex;gap:6px;flex-direction:column;z-index:2}
.wl-discount{background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;font-size:10px;font-weight:900;padding:4px 9px;border-radius:8px;box-shadow:0 4px 10px rgba(220,38,38,.35)}
.wl-new{background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;font-size:10px;font-weight:900;padding:4px 9px;border-radius:8px}
.wl-checkbox-wrap{position:absolute;top:10px;right:10px;z-index:3}
.wl-checkbox-wrap .wl-checkbox{background:rgba(255,255,255,.95);border-color:transparent;box-shadow:0 2px 8px rgba(0,0,0,.15);backdrop-filter:blur(6px)}

.wl-body{padding:14px;flex:1;display:flex;flex-direction:column}
.wl-name{font-size:13px;font-weight:800;line-height:1.4;margin-bottom:8px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:36px;color:#17202b;transition:.2s}
.wl-name:hover{color:#dc2626}
.wl-price-row{display:flex;align-items:baseline;gap:6px;margin-bottom:12px;flex-wrap:wrap}
.wl-price{font-size:17px;font-weight:900;color:#dc2626}
.wl-price small{font-size:10px;color:#94a3b8;font-weight:700}
.wl-price-old{font-size:11px;color:#94a3b8;text-decoration:line-through}
.wl-actions{display:flex;gap:6px;margin-top:auto}
.wl-action{flex:1;padding:10px;border-radius:11px;font-size:11px;font-weight:900;display:inline-flex;align-items:center;justify-content:center;gap:5px;transition:.2s;border:0;cursor:pointer}
.wl-cart{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff}
.wl-cart:hover{transform:translateY(-2px);box-shadow:0 8px 18px rgba(217,119,6,.35)}
.wl-icon-btn{width:38px;padding:0;flex:0 0 38px;background:#f7f6f2;color:#64748b}
.wl-icon-btn:hover{background:#fee2e2;color:#dc2626}
.wl-icon-btn.alert-active{background:#fef3c7;color:#d97706}
.wl-icon-btn.remove:hover{background:#dc2626;color:#fff}

/* ═══ Empty ═══ */
.wl-empty{background:#fff;border-radius:24px;padding:70px 30px;text-align:center;max-width:560px;margin:40px auto;border:2px dashed #fecaca}
.wl-empty-icon{font-size:80px;margin-bottom:16px;display:block;animation:wlBob 2s ease-in-out infinite}
@keyframes wlBob{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
.wl-empty h3{font-size:22px;font-weight:900;margin-bottom:8px;color:#7f1d1d}
.wl-empty p{color:#94a3b8;font-size:14px;margin-bottom:24px;font-weight:600;line-height:1.7}

/* ═══ Toast ═══ */
.wl-toast{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%) scale(.85);background:#17202b;color:#fff;padding:22px 32px;border-radius:20px;font-size:15px;font-weight:800;box-shadow:0 25px 70px rgba(0,0,0,.5),0 0 0 100vmax rgba(15,23,42,.5);transition:.35s cubic-bezier(.34,1.56,.64,1),opacity .3s;z-index:9999;max-width:90vw;min-width:240px;text-align:center;opacity:0;pointer-events:none;line-height:1.6}
.wl-toast.show{transform:translate(-50%,-50%) scale(1);opacity:1}
.wl-toast.success{background:linear-gradient(135deg,#16a34a,#15803d)}
.wl-toast.error{background:linear-gradient(135deg,#dc2626,#991b1b)}
.wl-toast::before{content:'';display:block;width:60px;height:60px;margin:0 auto 14px;border-radius:50%;background:rgba(255,255,255,.2);background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:center;background-size:32px}

/* ═══ Modal ═══ */
.wl-modal{position:fixed;inset:0;background:rgba(15,23,42,.6);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;z-index:1000;padding:20px;opacity:0;transition:.3s}
.wl-modal.show{display:flex;opacity:1}
.wl-modal-content{background:#fff;border-radius:24px;padding:32px;max-width:420px;width:100%;text-align:center;transform:scale(.9);transition:.3s;box-shadow:0 25px 80px rgba(0,0,0,.35)}
.wl-modal.show .wl-modal-content{transform:scale(1)}
.wl-modal-icon{font-size:56px;margin-bottom:12px;display:block}
.wl-modal-title{font-size:18px;font-weight:900;margin-bottom:8px;color:#17202b}
.wl-modal-sub{font-size:13px;color:#64748b;margin-bottom:20px;font-weight:700}
.wl-modal-input{width:100%;padding:14px;border:2px solid #e2e8f0;border-radius:14px;font-size:16px;font-weight:900;text-align:center;margin-bottom:16px;outline:none;font-family:inherit;transition:.2s;background:#fff}
.wl-modal-input:focus{border-color:#f59e0b;box-shadow:0 0 0 4px rgba(245,158,11,.1)}
.wl-modal-hint{font-size:11px;color:#94a3b8;margin-bottom:16px;font-weight:700}
.wl-modal-actions{display:flex;gap:10px}
.wl-modal-btn{flex:1;padding:13px;border-radius:13px;font-size:13px;font-weight:900;cursor:pointer;border:0;transition:.2s;font-family:inherit}
.wl-modal-btn.confirm{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff}
.wl-modal-btn.confirm:hover{transform:translateY(-2px);box-shadow:0 10px 24px rgba(217,119,6,.3)}
.wl-modal-btn.cancel{background:#f1f5f9;color:#475569}

/* ═══ Bottom Nav (Mobile) ═══ */
.wl-bottomnav{position:fixed;bottom:0;left:0;right:0;background:rgba(255,255,255,.98);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border-top:1px solid #e5e7eb;padding:8px 12px calc(8px + env(safe-area-inset-bottom));display:none;z-index:90;box-shadow:0 -4px 20px rgba(0,0,0,.06)}
.wl-bottomnav-inner{display:grid;grid-template-columns:repeat(5,1fr);gap:4px;max-width:500px;margin:0 auto}
.wl-bnav-item{display:flex;flex-direction:column;align-items:center;gap:3px;padding:8px 4px;border-radius:12px;font-size:10px;font-weight:800;color:#94a3b8;text-decoration:none;transition:.2s;position:relative}
.wl-bnav-item.active{background:#fef2f2;color:#dc2626}
.wl-bnav-item:hover{color:#dc2626}
.wl-bnav-icon{font-size:20px;line-height:1}
.wl-bnav-badge{position:absolute;top:2px;right:calc(50% - 18px);background:#dc2626;color:#fff;font-size:9px;font-weight:900;min-width:16px;height:16px;border-radius:50%;display:grid;place-items:center;border:2px solid #fff}
@media(max-width:760px){.wl-bottomnav{display:block}.wl-topnav .wl-nav-links{display:none}}

/* Floating cart */
.wl-fab{position:fixed;bottom:90px;left:20px;width:56px;height:56px;border-radius:20px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;display:none;place-items:center;box-shadow:0 12px 30px rgba(217,119,6,.4);z-index:80;transition:.2s;font-size:22px}
.wl-fab:hover{transform:translateY(-3px) scale(1.05)}
.wl-fab-badge{position:absolute;top:-4px;right:-4px;background:#17202b;color:#fff;min-width:22px;height:22px;border-radius:50%;font-size:10px;font-weight:900;display:grid;place-items:center;border:2px solid #fff}
@media(max-width:760px){.wl-fab{display:grid}}
</style>
</head>
<body>

{{-- ═══ Top Nav ═══ --}}
<nav class="wl-topnav">
  <div class="wl-topnav-inner">
    <div class="wl-nav-left">
      <a href="/demo-shop" class="wl-back" title="رجوع">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
      </a>
      <div class="wl-nav-title">
        المفضلة
        <span class="wl-nav-count">{{ $products->count() }}</span>
      </div>
    </div>
    <div class="wl-nav-links">
      <a href="/" class="wl-nav-link" title="الرئيسية">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      </a>
      <a href="/account/login" class="wl-nav-link" title="حسابي">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      </a>
      <a href="/cart" class="wl-nav-link cart" title="السلة">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <span class="wl-nav-link-badge" id="cartNavBadge">0</span>
      </a>
    </div>
  </div>
</nav>

<div class="wl-wrap">

{{-- ═══ Hero ═══ --}}
<div class="wl-hero">
  <div class="wl-hero-inner">
    <div>
      <span class="wl-hero-icon">❤️</span>
      <h1 class="wl-hero-title">مفضلتك الشخصية</h1>
      <p class="wl-hero-sub">احتفظ بالمنتجات التي تحبها، وأضفها للسلة بنقرة واحدة</p>
    </div>
    <a href="/demo-shop" class="wl-hero-action">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
      تسوق المزيد
    </a>
  </div>

  @if($products->count() > 0)
    <div class="wl-hero-stats">
      <div class="wl-hero-stat">
        <div class="wl-hero-stat-value">{{ $products->count() }}</div>
        <div class="wl-hero-stat-label">منتج محفوظ</div>
      </div>
      <div class="wl-hero-stat">
        <div class="wl-hero-stat-value">{{ number_format($products->sum('price')) }} <small>ر.ي</small></div>
        <div class="wl-hero-stat-label">القيمة الإجمالية</div>
      </div>
      <div class="wl-hero-stat">
        @php
          $totalSave = $products->sum(function($p) {
            return $p->compare_price && $p->compare_price > $p->price ? ($p->compare_price - $p->price) : 0;
          });
        @endphp
        <div class="wl-hero-stat-value">{{ number_format($totalSave) }} <small>ر.ي</small></div>
        <div class="wl-hero-stat-label">وفّرت</div>
      </div>
    </div>
  @endif
</div>

@if($products->count() > 0)
  {{-- ═══ Toolbar ═══ --}}
  <div class="wl-toolbar">
    <div class="wl-toolbar-group">
      <label class="wl-checkbox-wrap" style="position:relative;top:auto;right:auto">
        <input type="checkbox" class="wl-checkbox" id="selectAll" onchange="toggleSelectAll(this)" title="تحديد الكل">
      </label>
      <span class="wl-toolbar-label">تحديد الكل</span>
    </div>

    <div class="wl-toolbar-group">
      <span class="wl-toolbar-label">ترتيب:</span>
      <div class="wl-toolbar-chips">
        <button class="wl-chip active" onclick="sortProducts('default', this)">الأحدث</button>
        <button class="wl-chip" onclick="sortProducts('price-asc', this)">الأرخص</button>
        <button class="wl-chip" onclick="sortProducts('price-desc', this)">الأغلى</button>
        <button class="wl-chip" onclick="sortProducts('discount', this)">الأعلى خصم</button>
      </div>
    </div>

    <div class="wl-toolbar-spacer"></div>

    <div class="wl-toolbar-group" id="bulkActions" style="display:none">
      <button class="wl-toolbar-btn primary" onclick="addSelectedToCart()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        أضف المحدد (<span id="selectedCount">0</span>)
      </button>
      <button class="wl-toolbar-btn danger" onclick="removeSelected()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
        حذف
      </button>
    </div>

    <div class="wl-toolbar-group" id="singleActions">
      <button class="wl-toolbar-btn danger" onclick="clearWishlist()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
        إفراغ الكل
      </button>
    </div>
  </div>

  {{-- ═══ Products Grid ═══ --}}
  <div class="wl-grid" id="wishlistGrid">
    @foreach($products as $p)
      @php
        $img = $p->image;
        $isExt = $img && str_starts_with($img, 'http');
        $src = $isExt ? $img : ($img ? \Storage::url($img) : null);
        $disc = ($p->compare_price && $p->compare_price > $p->price)
          ? round((1 - $p->price / $p->compare_price) * 100) : 0;
        $isNew = $p->created_at && $p->created_at->diffInDays(now()) <= 7;
      @endphp
      <article class="wl-card" data-product-id="{{ $p->id }}" data-price="{{ $p->price }}" data-discount="{{ $disc }}">
        <div class="wl-media">
          <div class="wl-checkbox-wrap">
            <input type="checkbox" class="wl-checkbox product-checkbox" value="{{ $p->id }}" onchange="updateSelection()">
          </div>
          <div class="wl-badges">
            @if($disc > 0)<span class="wl-discount">-{{ $disc }}%</span>@endif
            @if($isNew && $disc === 0)<span class="wl-new">جديد</span>@endif
          </div>
          <a href="/product/{{ $p->id }}">
            @if($src)<img src="{{ $src }}" alt="{{ $p->name }}" loading="lazy">@else<div class="wl-placeholder">📦</div>@endif
          </a>
        </div>
        <div class="wl-body">
          <a href="/product/{{ $p->id }}" class="wl-name">{{ $p->name }}</a>
          <div class="wl-price-row">
            <span class="wl-price">{{ number_format($p->price) }} <small>ر.ي</small></span>
            @if($disc > 0)<span class="wl-price-old">{{ number_format($p->compare_price) }}</span>@endif
          </div>
          <div class="wl-actions">
            <button type="button" class="wl-action wl-cart" onclick="addToCart({{ $p->id }})">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
              أضف
            </button>
            <button type="button" class="wl-action wl-icon-btn" onclick="openAlertModal({{ $p->id }}, {{ $p->price }}, '{{ addslashes($p->name) }}')" title="نبّهني عند انخفاض السعر">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </button>
            <button type="button" class="wl-action wl-icon-btn remove" onclick="toggleWishlist({{ $p->id }})" title="إزالة من المفضلة">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
          </div>
        </div>
      </article>
    @endforeach
  </div>
@else
  <div class="wl-empty">
    <span class="wl-empty-icon">💔</span>
    <h3>قائمة المفضلة فارغة</h3>
    <p>تصفح المنتجات وأضف ما يعجبك للرجوع إليه لاحقاً</p>
    <a href="/demo-shop" class="wl-hero-action" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:14px 28px">
      🛍️ ابدأ التسوق
    </a>
  </div>
@endif

</div>

{{-- ═══ Bottom Nav ═══ --}}
<nav class="wl-bottomnav">
  <div class="wl-bottomnav-inner">
    <a href="/demo-shop" class="wl-bnav-item">
      <span class="wl-bnav-icon">🏠</span>
      <span>الرئيسية</span>
    </a>
    <a href="/demo-shop" class="wl-bnav-item">
      <span class="wl-bnav-icon">🛍️</span>
      <span>المنتجات</span>
    </a>
    <a href="/account/login" class="wl-bnav-item">
      <span class="wl-bnav-icon">👤</span>
      <span>حسابي</span>
    </a>
    <a href="/wishlist" class="wl-bnav-item active">
      <span class="wl-bnav-icon">❤️</span>
      <span>المفضلة</span>
    </a>
    <a href="/cart" class="wl-bnav-item">
      <span class="wl-bnav-icon">🛒</span>
      <span>السلة</span>
      <span class="wl-bnav-badge" id="cartBottomBadge">0</span>
    </a>
  </div>
</nav>

{{-- ═══ Toast ═══ --}}
<div class="wl-toast" id="toast"></div>

{{-- ═══ Modal ═══ --}}
<div class="wl-modal" id="alertModal">
  <div class="wl-modal-content">
    <span class="wl-modal-icon">🔔</span>
    <div class="wl-modal-title">نبّهني عند انخفاض السعر</div>
    <div class="wl-modal-sub" id="alertProductName"></div>
    <input type="number" id="alertPrice" class="wl-modal-input" placeholder="السعر المستهدف" min="1">
    <div class="wl-modal-hint">سنُعلمك عندما يصل السعر لهذا المبلغ أو أقل</div>
    <div class="wl-modal-actions">
      <button type="button" class="wl-modal-btn cancel" onclick="closeAlertModal()">إلغاء</button>
      <button type="button" class="wl-modal-btn confirm" onclick="submitAlert()">تفعيل التنبيه</button>
    </div>
  </div>
</div>

<script>
var csrfToken = '{{ csrf_token() }}';

// ═══ Toast ═══
function showToast(msg, type) {
  var t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'wl-toast show ' + (type || '');
  clearTimeout(window.__t);
  window.__t = setTimeout(function(){t.classList.remove('show');}, 3000);
}

// ═══ Wishlist Toggle ═══
function toggleWishlist(id) {
  fetch('/wishlist/toggle/' + id, {
    method: 'POST',
    headers: {'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
  })
  .then(function(r) { return r.json(); })
  .then(function(d) {
    if (d.ok) {
      showToast('💔 أُزيل من المفضلة', 'success');
      var card = document.querySelector('[data-product-id="' + id + '"]');
      if (card) {
        card.style.transform = 'scale(.9)';
        card.style.opacity = '0';
        setTimeout(function(){ card.remove(); checkEmpty(); }, 400);
      }
    }
  })
  .catch(function(){ showToast('تعذر التحديث', 'error'); });
}

// ═══ Add to Cart ═══
function addToCart(id) {
  fetch('/cart/add/' + id, {
    method: 'POST',
    headers: {'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
  })
  .then(function(r) { return r.json(); })
  .then(function(d) {
    showToast('✓ أُضيف للسلة', 'success');
    updateCartBadges();
  })
  .catch(function(){ 
    showToast('✓ أُضيف للسلة', 'success');
    updateCartBadges();
  });
}

// ═══ Cart Badges ═══
function updateCartBadges() {
  fetch('/cart', {headers: {'X-Requested-With': 'XMLHttpRequest'}})
    .then(function(r) { return r.text(); })
    .then(function() {
      // placeholder — count from session
    })
    .catch(function(){});
}
updateCartBadges();

// ═══ Select All ═══
function toggleSelectAll(el) {
  document.querySelectorAll('.product-checkbox').forEach(function(c) {
    c.checked = el.checked;
  });
  updateSelection();
}

function updateSelection() {
  var selected = document.querySelectorAll('.product-checkbox:checked');
  var count = selected.length;
  document.getElementById('selectedCount').textContent = count;
  document.getElementById('bulkActions').style.display = count > 0 ? 'flex' : 'none';
  document.getElementById('singleActions').style.display = count > 0 ? 'none' : 'flex';
  
  document.querySelectorAll('.wl-card').forEach(function(card) {
    var cb = card.querySelector('.product-checkbox');
    card.classList.toggle('selected', cb && cb.checked);
  });
  
  var selectAll = document.getElementById('selectAll');
  var total = document.querySelectorAll('.product-checkbox').length;
  selectAll.checked = count === total && total > 0;
}

// ═══ Bulk Actions ═══
function addSelectedToCart() {
  var selected = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(function(c){return c.value;});
  if (selected.length === 0) return;
  
  var done = 0;
  selected.forEach(function(id, idx) {
    setTimeout(function() {
      fetch('/cart/add/' + id, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
      }).then(function() {
        done++;
        if (done === selected.length) {
          showToast('✓ أُضيف ' + selected.length + ' منتجات للسلة', 'success');
        }
      });
    }, idx * 200);
  });
}

function removeSelected() {
  var selected = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(function(c){return c.value;});
  if (selected.length === 0) return;
  if (!confirm('حذف ' + selected.length + ' منتج من المفضلة؟')) return;
  
  var done = 0;
  selected.forEach(function(id, idx) {
    setTimeout(function() {
      fetch('/wishlist/toggle/' + id, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
      }).then(function() {
        done++;
        var card = document.querySelector('[data-product-id="' + id + '"]');
        if (card) card.remove();
        if (done === selected.length) {
          showToast('✅ تم الحذف', 'success');
          setTimeout(function(){ location.reload(); }, 1000);
        }
      });
    }, idx * 150);
  });
}

// ═══ Clear Wishlist ═══
function clearWishlist() {
  if (!confirm('هل تريد إفراغ المفضلة بالكامل؟')) return;
  fetch('/wishlist/clear', {
    method: 'POST',
    headers: {'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
  })
  .then(function(r) { return r.json(); })
  .then(function(d) {
    if (d.ok) {
      showToast('تم إفراغ المفضلة ✓', 'success');
      setTimeout(function(){ location.reload(); }, 1200);
    }
  });
}

// ═══ Sorting ═══
function sortProducts(type, el) {
  document.querySelectorAll('.wl-chip').forEach(function(c){c.classList.remove('active');});
  el.classList.add('active');
  
  var grid = document.getElementById('wishlistGrid');
  var cards = Array.from(grid.querySelectorAll('.wl-card'));
  
  cards.sort(function(a, b) {
    var pa = parseFloat(a.dataset.price) || 0;
    var pb = parseFloat(b.dataset.price) || 0;
    var da = parseFloat(a.dataset.discount) || 0;
    var db = parseFloat(b.dataset.discount) || 0;
    var ia = parseInt(a.dataset.productId) || 0;
    var ib = parseInt(b.dataset.productId) || 0;
    
    if (type === 'price-asc') return pa - pb;
    if (type === 'price-desc') return pb - pa;
    if (type === 'discount') return db - da;
    return ib - ia;
  });
  
  cards.forEach(function(c) { grid.appendChild(c); });
}

// ═══ Alert Modal ═══
var pendingAlert = { id: null, price: 0 };

function openAlertModal(id, price, name) {
  pendingAlert.id = id;
  pendingAlert.price = price;
  document.getElementById('alertProductName').textContent = name;
  document.getElementById('alertPrice').value = Math.floor(price * 0.9);
  document.getElementById('alertModal').classList.add('show');
}

function closeAlertModal() {
  document.getElementById('alertModal').classList.remove('show');
}

function submitAlert() {
  var price = parseFloat(document.getElementById('alertPrice').value);
  if (!price || price < 1) {
    showToast('أدخل سعراً صحيحاً', 'error');
    return;
  }
  fetch('/price-alert/' + pendingAlert.id, {
    method: 'POST',
    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'},
    body: JSON.stringify({ target_price: price })
  })
  .then(function(r) { return r.json(); })
  .then(function(d) {
    closeAlertModal();
    if (d.ok) showToast(d.message, 'success');
    else showToast(d.message || 'تعذر', 'error');
  })
  .catch(function(){ closeAlertModal(); showToast('تعذر الاتصال', 'error'); });
}

document.getElementById('alertModal').addEventListener('click', function(e) {
  if (e.target === this) closeAlertModal();
});

// ═══ Empty Check ═══
function checkEmpty() {
  var remaining = document.querySelectorAll('.wl-card').length;
  if (remaining === 0) location.reload();
}
</script>

@include('components.floating-actions')
</body>
</html>

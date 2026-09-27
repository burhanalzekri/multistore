@php
  $img = $product->image;
  $isExternal = $img && (str_starts_with($img, 'http://') || str_starts_with($img, 'https://'));
  $mainImage = $isExternal ? $img : ($img ? \Storage::url($img) : null);
  $extraImages = is_array($product->images) ? $product->images : [];
  $discount = ($product->compare_price && $product->compare_price > $product->price)
    ? round((1 - $product->price / $product->compare_price) * 100)
    : 0;
  $avgRating = $ratingStats['avg'] ?? 0;
  $ratingCount = $ratingStats['count'] ?? 0;

  $shopLabel1 = $shop->variantLabel1() ?? 'اللون';
  $shopLabel2 = $shop->variantLabel2() ?? 'المقاس';
  $shopIcon1  = $shop->variantIcon1()  ?? '🎨';
  $shopIcon2  = $shop->variantIcon2()  ?? '📏';
@endphp

<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="theme-color" content="{{ $shop->primary_color ?? '#d97706' }}">
<title>{{ $product->name }} — {{ $shop->name ?? 'المتجر' }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Tajawal',system-ui,sans-serif;background:#fafaf7;color:#17202b;line-height:1.65;-webkit-font-smoothing:antialiased}
a{text-decoration:none;color:inherit}
button{cursor:pointer;border:0;font:inherit}
img{max-width:100%;display:block}
.pd-wrap{max-width:1200px;margin:0 auto;padding:20px 16px 100px}

/* ═══ Header ═══ */
.pd-header{background:rgba(255,255,255,.92);backdrop-filter:blur(12px);border-bottom:1px solid #eee;padding:14px 16px;display:flex;align-items:center;gap:12px;position:sticky;top:0;z-index:50}
.pd-header-back{width:42px;height:42px;border-radius:12px;background:#f7f6f2;display:grid;place-items:center;flex-shrink:0}
.pd-header-title{flex:1;font-size:14px;font-weight:800;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-align:center}
.pd-header-actions{display:flex;gap:8px;flex-shrink:0}
.pd-icon-btn{width:42px;height:42px;border-radius:12px;background:#f7f6f2;display:grid;place-items:center;transition:.2s}
.pd-icon-btn:hover{background:#efece6}

/* ═══ Gallery ═══ */
.pd-gallery{margin-bottom:24px}
.pd-gallery-main{position:relative;aspect-ratio:1;background:#f5f3ee;border-radius:24px;overflow:hidden;margin-bottom:12px}
.pd-gallery-main img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
.pd-gallery-main:hover img{transform:scale(1.03)}
.pd-placeholder{width:100%;height:100%;display:grid;place-items:center;font-size:120px;color:#d4b896}
.pd-gallery-nav{position:absolute;inset:0;display:flex;align-items:center;justify-content:space-between;padding:0 12px;pointer-events:none}
.pd-gallery-nav button{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.9);backdrop-filter:blur(8px);display:grid;place-items:center;pointer-events:auto;transition:.2s}
.pd-gallery-nav button:hover{background:#fff;transform:scale(1.1)}
.pd-gallery-dots{position:absolute;bottom:12px;left:50%;transform:translateX(-50%);display:flex;gap:6px;background:rgba(0,0,0,.35);padding:6px 12px;border-radius:999px;backdrop-filter:blur(8px)}
.pd-gallery-dot{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.4);transition:.2s;cursor:pointer}
.pd-gallery-dot.active{background:#f59e0b;width:22px;border-radius:5px}
.pd-gallery-thumbs{display:flex;gap:10px;overflow-x:auto;padding-bottom:4px}
.pd-gallery-thumbs::-webkit-scrollbar{height:4px}
.pd-gallery-thumbs::-webkit-scrollbar-thumb{background:#ddd;border-radius:2px}
.pd-thumb{width:70px;height:70px;border-radius:14px;overflow:hidden;background:#f5f3ee;flex-shrink:0;cursor:pointer;border:2px solid transparent;transition:.2s}
.pd-thumb.active{border-color:#d97706}
.pd-thumb img{width:100%;height:100%;object-fit:cover}

/* ═══ Info ═══ */
.pd-info{background:#fff;border-radius:24px;padding:22px;margin-bottom:16px;box-shadow:0 2px 12px rgba(0,0,0,.03)}
.pd-category{display:inline-block;font-size:11px;font-weight:800;color:#d97706;background:#fef3c7;padding:5px 12px;border-radius:999px;margin-bottom:12px}
.pd-title{font-size:22px;font-weight:900;line-height:1.35;margin-bottom:12px}
.pd-rating-row{display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap}
.pd-stars{color:#f59e0b;font-size:16px;letter-spacing:1px}
.pd-rating-text{font-size:12px;color:#64748b;font-weight:700}

.pd-price-block{background:#faf7f2;border-radius:16px;padding:16px;margin-bottom:16px}
.pd-price-row{display:flex;align-items:baseline;gap:10px;margin-bottom:6px}
.pd-price{font-size:30px;font-weight:900;color:#d97706}
.pd-price small{font-size:14px;color:#999;font-weight:700}
.pd-price-old{font-size:16px;color:#aaa;text-decoration:line-through}
.pd-discount{display:inline-block;background:#dc2626;color:#fff;font-size:12px;font-weight:900;padding:4px 10px;border-radius:8px}
.pd-save-text{font-size:12px;color:#16a34a;font-weight:800;margin-top:4px}

.pd-stock{display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:800;padding:6px 12px;border-radius:10px;margin-bottom:16px}
.pd-stock.in{background:#dcfce7;color:#166534}
.pd-stock.out{background:#fee2e2;color:#991b1b}

/* ═══ Options ═══ */
.pd-options{margin-bottom:18px}
.pd-option-label{font-size:12px;font-weight:900;color:#475569;margin-bottom:10px;display:flex;align-items:center;gap:6px}
.pd-size-grid{display:flex;gap:8px;flex-wrap:wrap;direction:ltr;justify-content:flex-end}
.pd-size{min-width:48px;height:42px;padding:0 14px;border:1.5px solid #e2e8f0;background:#fff;border-radius:12px;font-size:13px;font-weight:800;color:#475569;transition:.2s}
.pd-size:hover{border-color:#d97706}
.pd-size.active{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border-color:transparent;box-shadow:0 4px 12px rgba(217,119,6,.25)}

.pd-color-grid{display:flex;gap:10px;flex-wrap:wrap}
.pd-color{width:38px;height:38px;border-radius:50%;border:3px solid #fff;box-shadow:0 0 0 1.5px #e2e8f0;transition:.2s}
.pd-color:hover{transform:scale(1.08)}
.pd-color.active{box-shadow:0 0 0 2.5px #d97706}

/* ═══ Quantity ═══ */
.pd-qty-row{display:flex;align-items:center;justify-content:space-between;padding:14px;background:#faf7f2;border-radius:14px;margin-bottom:18px}
.pd-qty-label{font-size:13px;font-weight:800;color:#475569}
.pd-qty{display:flex;align-items:center;gap:8px;background:#fff;border-radius:12px;padding:4px;border:1px solid #e2e8f0}
.pd-qty button{width:34px;height:34px;border-radius:9px;background:#f7f6f2;font-size:18px;font-weight:900;color:#475569;transition:.2s}
.pd-qty button:hover{background:#d97706;color:#fff}
.pd-qty input{width:44px;text-align:center;border:0;background:transparent;font-size:15px;font-weight:900;outline:none}

/* ═══ Actions ═══ */
.pd-actions{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px}
.pd-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;height:54px;border-radius:16px;font-size:15px;font-weight:900;transition:.25s;border:0}
.pd-btn-primary{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;box-shadow:0 8px 20px rgba(217,119,6,.3)}
.pd-btn-primary:hover{transform:translateY(-2px);box-shadow:0 12px 28px rgba(217,119,6,.4)}
.pd-btn-dark{background:#17202b;color:#fff}
.pd-btn-dark:hover{background:#000;transform:translateY(-2px)}
.pd-btn:disabled{opacity:.5;cursor:not-allowed}

.pd-wishlist{width:54px;height:54px;border-radius:16px;background:#f7f6f2;display:grid;place-items:center;transition:.2s}
.pd-wishlist.active{background:#fee2e2;color:#dc2626}
.pd-wishlist:hover{transform:scale(1.05)}

/* ═══ Info Badges ═══ */
.pd-badges{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:16px}
.pd-badge{display:flex;flex-direction:column;align-items:center;gap:6px;background:#fff;border:1px solid #eee;border-radius:14px;padding:14px 8px;text-align:center}
.pd-badge-icon{color:#d97706}
.pd-badge-title{font-size:11px;font-weight:900;color:#17202b}
.pd-badge-sub{font-size:10px;color:#999}

/* ═══ Description ═══ */
.pd-section{background:#fff;border-radius:20px;padding:22px;margin-bottom:16px;box-shadow:0 2px 12px rgba(0,0,0,.03)}
.pd-section-title{font-size:17px;font-weight:900;margin-bottom:14px;display:flex;align-items:center;gap:10px}
.pd-section-title::before{content:'';width:4px;height:20px;background:#d97706;border-radius:2px}
.pd-desc-text{font-size:14px;line-height:1.9;color:#475569}

/* ═══ Reviews ═══ */
.pd-reviews-header{display:flex;gap:20px;align-items:center;padding:16px;background:#faf7f2;border-radius:16px;margin-bottom:18px}
.pd-rating-big{text-align:center}
.pd-rating-big strong{display:block;font-size:36px;font-weight:900;color:#d97706;line-height:1}
.pd-rating-big small{display:block;font-size:11px;color:#999;margin-top:4px}
.pd-rating-bars{flex:1;display:flex;flex-direction:column;gap:4px}
.pd-rating-bar-row{display:flex;align-items:center;gap:8px;font-size:11px;color:#64748b;font-weight:700}
.pd-rating-bar{flex:1;height:6px;background:#e5e7eb;border-radius:3px;overflow:hidden}
.pd-rating-bar-fill{height:100%;background:linear-gradient(90deg,#f59e0b,#d97706);border-radius:3px}
.pd-review{padding:16px 0;border-bottom:1px solid #f0eeea}
.pd-review:last-child{border:0}
.pd-review-head{display:flex;align-items:center;gap:10px;margin-bottom:8px}
.pd-review-avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;display:grid;place-items:center;font-size:14px;font-weight:900}
.pd-review-name{font-size:13px;font-weight:800}
.pd-review-date{font-size:11px;color:#999}
.pd-review-stars{color:#f59e0b;font-size:13px;margin-bottom:6px}
.pd-review-text{font-size:13px;line-height:1.7;color:#475569}
.pd-empty{text-align:center;padding:30px 20px;color:#999}
.pd-empty-icon{font-size:48px;margin-bottom:10px}

/* ═══ Related ═══ */
.pd-related-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
@media(min-width:600px){.pd-related-grid{grid-template-columns:repeat(4,1fr)}}
.pd-related-card{background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.04);transition:.25s;border:1px solid #f0eeea}
.pd-related-card:hover{transform:translateY(-4px);box-shadow:0 12px 30px rgba(0,0,0,.08);border-color:transparent}
.pd-related-media{aspect-ratio:1;background:#f5f3ee;position:relative;overflow:hidden}
.pd-related-media img{width:100%;height:100%;object-fit:cover;transition:.4s}
.pd-related-card:hover .pd-related-media img{transform:scale(1.05)}
.pd-related-placeholder{width:100%;height:100%;display:grid;place-items:center;font-size:44px;color:#d4b896}
.pd-related-discount{position:absolute;top:8px;right:8px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;font-size:10px;font-weight:900;padding:4px 8px;border-radius:8px}
.pd-related-body{padding:12px}
.pd-related-name{font-size:12px;font-weight:800;line-height:1.4;margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:34px}
.pd-related-price{font-size:15px;font-weight:900;color:#d97706}
.pd-related-old{font-size:11px;color:#999;text-decoration:line-through;margin-right:6px}

/* ═══ Toast ═══ */
.pd-toast{position:fixed;bottom:24px;left:50%;transform:translate(-50%,80px);background:#17202b;color:#fff;padding:14px 22px;border-radius:14px;font-size:14px;font-weight:800;box-shadow:0 15px 40px rgba(0,0,0,.25);transition:transform .35s cubic-bezier(.34,1.56,.64,1);z-index:999;display:flex;align-items:center;gap:10px;max-width:90vw}
.pd-toast.show{transform:translate(-50%,0)}
.pd-toast.success{background:linear-gradient(135deg,#16a34a,#15803d)}
.pd-toast.error{background:linear-gradient(135deg,#dc2626,#b91c1c)}

/* ═══ Floating Cart ═══ */
.pd-floating-cart{position:fixed;bottom:24px;left:20px;width:56px;height:56px;border-radius:20px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;display:grid;place-items:center;box-shadow:0 12px 30px rgba(217,119,6,.35);z-index:80;transition:.2s}
.pd-floating-cart:hover{transform:translateY(-3px) scale(1.05)}
.pd-floating-cart-badge{position:absolute;top:-4px;right:-4px;background:#17202b;color:#fff;min-width:22px;height:22px;border-radius:50%;font-size:10px;font-weight:900;display:grid;place-items:center;border:2px solid #fff}

/* ═══ Responsive ═══ */
@media(min-width:900px){
  .pd-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start}
  .pd-gallery{position:sticky;top:80px;margin-bottom:0}
  .pd-title{font-size:28px}
  .pd-price{font-size:36px}
}
@media(max-width:480px){
  .pd-title{font-size:19px}
  .pd-price{font-size:26px}
  .pd-related-grid{grid-template-columns:repeat(2,1fr);gap:10px}
}

  .pd-review-toast {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.85);
    background: #17202b;
    color: #fff;
    padding: 22px 32px;
    border-radius: 20px;
    font-size: 15px;
    font-weight: 800;
    box-shadow: 0 25px 70px rgba(0,0,0,.45), 0 0 0 100vmax rgba(15,23,42,.5);
    transition: transform .35s cubic-bezier(.34,1.56,.64,1), opacity .3s;
    z-index: 9999;
    max-width: 90vw;
    width: max-content;
    min-width: 240px;
    text-align: center;
    opacity: 0;
    pointer-events: none;
    line-height: 1.6;
  }
  .pd-review-toast.show {
    transform: translate(-50%, -50%) scale(1);
    opacity: 1;
  }
  .pd-review-toast.success {
    background: linear-gradient(135deg, #16a34a, #15803d);
    box-shadow: 0 25px 70px rgba(22,163,74,.5), 0 0 0 100vmax rgba(15,23,42,.5);
  }
  .pd-review-toast.error {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    box-shadow: 0 25px 70px rgba(220,38,38,.5), 0 0 0 100vmax rgba(15,23,42,.5);
  }
  .pd-review-toast::before {
    content: '';
    display: block;
    width: 60px;
    height: 60px;
    margin: 0 auto 14px;
    border-radius: 50%;
    background: rgba(255,255,255,.2);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: center;
    background-size: 32px;
  }
  .pd-review-toast.error::before {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cline x1='18' y1='6' x2='6' y2='18'/%3E%3Cline x1='6' y1='6' x2='18' y2='18'/%3E%3C/svg%3E");
  }

  /* ═══ Video Thumb ═══ */
  .pd-video-thumb {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 900;
    cursor: pointer;
    margin-top: 10px;
    box-shadow: 0 6px 16px rgba(217,119,6,.25);
    transition: .2s;
  }
  .pd-video-thumb:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(217,119,6,.35);
  }
  .pd-video-thumb svg { display: block; }

  #productVideo {
    width: 100% !important;
    height: auto !important;
    max-height: 100% !important;
    border-radius: 24px;
  }


  /* ═══ 🎨 Color Grid ═══ */
  .pd-color-grid{display:flex;gap:10px;flex-wrap:wrap}
  .pd-color-dot{width:44px;height:44px;border-radius:50%;border:3px solid #fff;box-shadow:0 0 0 1.5px #cbd5e1;cursor:pointer;transition:.2s;position:relative}
  .pd-color-dot:hover{transform:scale(1.08)}
  .pd-color-dot.active{box-shadow:0 0 0 2.5px #d97706, 0 0 0 5px rgba(217,119,6,.2);transform:scale(1.08)}
  .pd-color-dot.active::after{content:'✓';position:absolute;inset:0;display:grid;place-items:center;color:#fff;font-weight:900;font-size:18px;text-shadow:0 2px 4px rgba(0,0,0,.5)}

  /* ═══ 📏 Size Grid ═══ */
  .pd-size{position:relative;padding:10px 14px;min-width:60px;border:2px solid #e2e8f0;border-radius:12px;background:#fff;font-weight:800;font-size:14px;color:#334155;cursor:pointer;transition:.2s;font-family:inherit}
  .pd-size:hover:not(:disabled){border-color:#f59e0b;color:#d97706;transform:translateY(-1px)}
  .pd-size.active{border-color:#d97706;background:#fff7ed;color:#d97706;box-shadow:0 4px 12px rgba(217,119,6,.15)}
  .pd-size:disabled,.pd-size.disabled{opacity:.35;cursor:not-allowed;text-decoration:line-through;background:#f8fafc}
  .pd-size-stock{display:block;font-size:9px;color:#94a3b8;font-weight:700;margin-top:2px}
  .pd-size.active .pd-size-stock{color:#d97706}
  .pd-size.low-stock .pd-size-stock{color:#dc2626;font-weight:900}

  /* ═══ ⚠️ Stock Alert ═══ */
  .pd-stock-alert{display:flex;align-items:center;gap:6px;margin-top:10px;padding:8px 12px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;color:#dc2626;font-size:12px;font-weight:800}
  .pd-stock-alert.ok{background:#f0fdf4;border-color:#bbf7d0;color:#16a34a}

  /* ═══ 🧭 Breadcrumbs ═══ */
  .pd-crumbs {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 700;
    color: #94a3b8;
    margin-bottom: 14px;
    flex-wrap: wrap;
  }
  .pd-crumbs a { color: #64748b; text-decoration: none; transition: .2s; }
  .pd-crumbs a:hover { color: #d97706; }
  .pd-crumbs-sep { color: #cbd5e1; }
  .pd-crumbs-current { color: #17202b; font-weight: 800; }

  /* ═══ 📤 Share Bar ═══ */
  .pd-share-bar {
    display: flex; gap: 8px; margin-top: 14px; margin-bottom: 16px;
    padding: 10px;
    background: #f8fafc;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
  }
  .pd-share-btn {
    flex: 1;
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    padding: 10px 12px;
    border: 0; border-radius: 10px;
    font-size: 12px; font-weight: 900;
    cursor: pointer; font-family: inherit;
    text-decoration: none;
    transition: .2s;
  }
  .pd-share-btn.wa { background: linear-gradient(135deg, #25D366, #128C7E); color: #fff; }
  .pd-share-btn.cp { background: #17202b; color: #fff; }
  .pd-share-btn.native { background: #f59e0b; color: #fff; }
  .pd-share-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(0,0,0,0.15); }

  /* ═══ 💰 Discount Ribbon ═══ */
  .pd-ribbon {
    position: absolute;
    top: 14px; right: 14px;
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: #fff;
    padding: 8px 14px;
    border-radius: 12px;
    font-size: 12px; font-weight: 900;
    box-shadow: 0 8px 20px rgba(220,38,38,0.4);
    z-index: 10;
    display: flex; align-items: center; gap: 4px;
  }
  .pd-ribbon .pct { font-size: 15px; font-weight: 900; }

  /* ═══ 🚚 Delivery Box ═══ */
  .pd-delivery {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 14px;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border: 1px solid #bbf7d0;
    border-radius: 14px;
    margin-bottom: 14px;
  }
  .pd-delivery-icon {
    width: 38px; height: 38px;
    border-radius: 12px;
    background: #fff;
    display: grid; place-items: center;
    font-size: 18px;
    flex-shrink: 0;
  }
  .pd-delivery-body { flex: 1; }
  .pd-delivery-title { font-size: 13px; font-weight: 900; color: #166534; margin-bottom: 2px; }
  .pd-delivery-sub { font-size: 11px; font-weight: 700; color: #16a34a; }

  /* ═══ 🔴 Live Viewers ═══ */
  .pd-viewers {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 99px;
    font-size: 11px; font-weight: 900;
    color: #dc2626;
    margin-top: 10px;
  }
  .pd-viewers-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #dc2626;
    animation: pdPulse 1.5s infinite;
  }
  @keyframes pdPulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50%      { transform: scale(1.5); opacity: .6; }
  }

  /* ═══ 🔍 Lightbox ═══ */
  .pd-lightbox {
    display: none !important;
    position: fixed !important;
    top: 0 !important; left: 0 !important;
    right: 0 !important; bottom: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(0,0,0,0.96) !important;
    z-index: 2147483647 !important;
    align-items: center; justify-content: center;
    cursor: zoom-out;
  }
  .pd-lightbox.show {
    display: flex !important;
  }
  .pd-lightbox.show { display: flex; }
  @keyframes pdLbIn { from { opacity: 0; } to { opacity: 1; } }
  .pd-lightbox img {
    max-width: 95vw; max-height: 90vh;
    object-fit: contain;
    border-radius: 8px;
    transition: transform .3s;
  }
  .pd-lb-close {
    position: absolute;
    top: 20px; left: 20px;
    width: 44px; height: 44px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    border: 0; color: #fff;
    font-size: 22px; font-weight: 900;
    cursor: pointer;
    display: grid; place-items: center;
  }
  .pd-lb-close:hover { background: rgba(255,255,255,0.25); }
  .pd-lb-nav {
    position: absolute;
    top: 50%; transform: translateY(-50%);
    width: 50px; height: 50px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    border: 0; color: #fff;
    font-size: 24px; font-weight: 900;
    cursor: pointer;
    display: grid; place-items: center;
  }
  .pd-lb-nav.prev { left: 20px; }
  .pd-lb-nav.next { right: 20px; }
  .pd-lb-nav:hover { background: rgba(255,255,255,0.25); }

  /* ═══ 🏪 Store Card ═══ */
  .pd-store-card {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 14px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    margin-bottom: 16px;
    text-decoration: none;
    color: inherit;
    transition: .2s;
  }
  .pd-store-card:hover { border-color: #f59e0b; background: #fffdf5; }
  .pd-store-avatar {
    width: 42px; height: 42px;
    border-radius: 12px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    display: grid; place-items: center;
    font-size: 20px;
    font-weight: 900;
    flex-shrink: 0;
  }
  .pd-store-body { flex: 1; min-width: 0; }
  .pd-store-name { font-size: 13px; font-weight: 900; color: #17202b; }
  .pd-store-sub { font-size: 11px; font-weight: 700; color: #64748b; margin-top: 2px; }
  .pd-store-arrow { color: #94a3b8; font-size: 18px; }

  /* Thumbs with video */
  .pd-gallery-thumb-video {
    position: relative;
    aspect-ratio: 1;
    border-radius: 14px;
    overflow: hidden;
    flex-shrink: 0;
    width: 74px;
    background: #17202b;
    display: grid; place-items: center;
    cursor: pointer;
    border: 2px solid transparent;
    transition: .2s;
  }
  .pd-gallery-thumb-video:hover { border-color: #f59e0b; }
  .pd-gallery-thumb-video::after {
    content: '▶';
    color: #fff;
    font-size: 20px;
  }



  /* ═══ ✨ Premium Design System ═══ */
  :root {
    --pd-primary: #d97706;
    --pd-primary-2: #f59e0b;
    --pd-dark: #0f172a;
    --pd-text: #17202b;
    --pd-muted: #64748b;
    --pd-border: #e5e7eb;
    --pd-bg-soft: #fafaf7;
    --pd-radius-lg: 24px;
    --pd-radius-md: 16px;
    --pd-radius-sm: 12px;
    --pd-shadow-sm: 0 1px 3px rgba(15,23,42,0.04), 0 4px 12px -4px rgba(15,23,42,0.06);
    --pd-shadow-md: 0 4px 12px rgba(15,23,42,0.05), 0 12px 32px -8px rgba(15,23,42,0.12);
    --pd-shadow-lg: 0 8px 24px rgba(15,23,42,0.08), 0 24px 48px -12px rgba(15,23,42,0.18);
    --pd-shadow-primary: 0 8px 20px -4px rgba(217,119,6,0.3), 0 16px 40px -8px rgba(217,119,6,0.2);
  }

  body { background: linear-gradient(180deg, #fafaf7 0%, #f5f3ee 100%); }

  /* ═══ Gallery Card ═══ */
  .pd-gallery {
    background: #fff;
    border-radius: var(--pd-radius-lg);
    padding: 16px;
    box-shadow: var(--pd-shadow-md);
    border: 1px solid rgba(255,255,255,0.8);
  }
  .pd-gallery-main {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.5);
  }
  .pd-gallery-main img { transition: transform .5s cubic-bezier(.2,.9,.3,1.1); }
  .pd-gallery-main:hover img { transform: scale(1.05); }

  .pd-gallery-thumbs {
    margin-top: 14px;
    padding: 8px;
    background: #f8fafc;
    border-radius: 14px;
  }
  .pd-thumb, .pd-gallery-thumb {
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid transparent;
    transition: .25s;
    cursor: pointer;
  }
  .pd-thumb:hover, .pd-gallery-thumb:hover { border-color: var(--pd-primary-2); transform: translateY(-2px); }
  .pd-thumb.active, .pd-gallery-thumb.active { border-color: var(--pd-primary); box-shadow: var(--pd-shadow-primary); }

  /* ═══ Info Card (تفاصيل المنتج) ═══ */
  .pd-info {
    background: #fff;
    border-radius: var(--pd-radius-lg);
    padding: 24px;
    box-shadow: var(--pd-shadow-md);
    border: 1px solid rgba(255,255,255,0.8);
    position: relative;
  }

  /* ═══ Breadcrumbs ═══ */
  .pd-crumbs {
    background: #fff;
    padding: 10px 16px;
    border-radius: 14px;
    box-shadow: var(--pd-shadow-sm);
    margin-bottom: 16px;
    border: 1px solid rgba(255,255,255,0.8);
  }

  /* ═══ Title ═══ */
  .pd-title {
    font-size: 26px;
    font-weight: 900;
    line-height: 1.3;
    color: var(--pd-text);
    letter-spacing: -0.5px;
    margin-bottom: 12px;
  }

  /* ═══ Store Card ═══ */
  .pd-store-card {
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border: 1px solid #fde68a;
    border-radius: var(--pd-radius-md);
    padding: 14px;
    box-shadow: var(--pd-shadow-sm);
    transition: .25s;
  }
  .pd-store-card:hover {
    box-shadow: var(--pd-shadow-md);
    transform: translateY(-1px);
    border-color: var(--pd-primary-2);
  }
  .pd-store-avatar {
    background: linear-gradient(135deg, var(--pd-primary-2), var(--pd-primary));
    box-shadow: 0 6px 16px rgba(217,119,6,0.35);
  }

  /* ═══ Viewers Badge ═══ */
  .pd-viewers {
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    border: 1px solid #fecaca;
    box-shadow: 0 4px 12px rgba(220,38,38,0.15);
    animation: pdViewerPulse 2.5s infinite;
  }
  @keyframes pdViewerPulse {
    0%, 100% { box-shadow: 0 4px 12px rgba(220,38,38,0.15); }
    50%      { box-shadow: 0 4px 20px rgba(220,38,38,0.3); }
  }

  /* ═══ Delivery Box ═══ */
  .pd-delivery {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border: 1px solid #bbf7d0;
    box-shadow: 0 4px 12px rgba(16,163,74,0.1);
    border-radius: var(--pd-radius-md);
  }
  .pd-delivery-icon {
    background: #fff;
    box-shadow: 0 4px 12px rgba(16,163,74,0.2);
  }

  /* ═══ Price Block ═══ */
  .pd-price-block {
    background: linear-gradient(135deg, #fff9e6, #fff7ed);
    border: 1.5px solid #fde68a;
    border-radius: var(--pd-radius-md);
    box-shadow: var(--pd-shadow-sm);
    padding: 18px;
  }
  .pd-price {
    font-size: 34px;
    letter-spacing: -1px;
    text-shadow: 0 2px 8px rgba(217,119,6,0.15);
  }

  /* ═══ Color/Size Sections ═══ */
  .pd-options {
    padding: 16px;
    background: #f8fafc;
    border-radius: var(--pd-radius-md);
    border: 1px solid var(--pd-border);
    box-shadow: inset 0 1px 2px rgba(15,23,42,0.02);
  }
  .pd-option-label {
    font-weight: 900;
    font-size: 13px;
    color: var(--pd-text);
    margin-bottom: 12px;
  }

  .pd-color-dot {
    box-shadow: 0 2px 8px rgba(15,23,42,0.15);
    transition: .25s cubic-bezier(.2,.9,.3,1.1);
  }
  .pd-color-dot:hover {
    transform: scale(1.15) translateY(-2px);
    box-shadow: 0 8px 20px rgba(15,23,42,0.2);
  }
  .pd-color-dot.active {
    transform: scale(1.15);
    box-shadow: 0 0 0 3px #fff, 0 0 0 5.5px var(--pd-primary-2), 0 8px 20px rgba(217,119,6,0.3);
  }

  .pd-size {
    background: #fff;
    box-shadow: var(--pd-shadow-sm);
    transition: .2s;
  }
  .pd-size:hover:not(.disabled):not([disabled]) {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(245,158,11,0.2);
    border-color: var(--pd-primary-2);
  }
  .pd-size.active {
    box-shadow: 0 6px 16px rgba(217,119,6,0.25);
    transform: translateY(-1px);
  }

  /* ═══ Quantity ═══ */
  .pd-qty-row {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border: 1px solid var(--pd-border);
    box-shadow: var(--pd-shadow-sm);
    border-radius: var(--pd-radius-md);
  }
  .pd-qty {
    background: #fff;
    box-shadow: 0 2px 6px rgba(15,23,42,0.06);
  }

  /* ═══ Add to Cart Button ═══ */
  .pd-btn-primary {
    background: linear-gradient(135deg, var(--pd-primary-2) 0%, var(--pd-primary) 100%);
    box-shadow: var(--pd-shadow-primary);
    font-size: 16px;
    height: 58px;
    position: relative;
    overflow: hidden;
  }
  .pd-btn-primary::before {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left .6s;
  }
  .pd-btn-primary:hover:not(:disabled)::before { left: 100%; }
  .pd-btn-primary:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px -4px rgba(217,119,6,0.4), 0 20px 48px -8px rgba(217,119,6,0.25);
  }

  .pd-wishlist {
    background: #fff;
    box-shadow: var(--pd-shadow-sm);
    border: 1.5px solid #fecaca;
    transition: .25s;
  }
  .pd-wishlist:hover { background: #fef2f2; transform: scale(1.05); }
  .pd-wishlist.active { background: #fee2e2; box-shadow: 0 6px 16px rgba(220,38,38,0.25); }

  /* ═══ Share Bar ═══ */
  .pd-share-bar {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border: 1px solid var(--pd-border);
    box-shadow: var(--pd-shadow-sm);
    border-radius: var(--pd-radius-md);
    padding: 12px;
    gap: 10px;
  }
  .pd-share-btn {
    box-shadow: 0 4px 12px rgba(15,23,42,0.08);
    transition: .25s;
    padding: 12px;
    font-size: 13px;
  }
  .pd-share-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(15,23,42,0.15);
  }
  .pd-share-btn.wa { box-shadow: 0 4px 12px rgba(37,211,102,0.3); }
  .pd-share-btn.wa:hover { box-shadow: 0 8px 20px rgba(37,211,102,0.45); }

  /* ═══ Info Badges (الضمانات) ═══ */
  .pd-badges {
    gap: 12px;
    margin-top: 18px;
  }
  .pd-badge {
    background: #fff;
    border: 1px solid var(--pd-border);
    border-radius: var(--pd-radius-md);
    box-shadow: var(--pd-shadow-sm);
    padding: 16px 12px;
    transition: .25s;
    cursor: default;
  }
  .pd-badge:hover {
    box-shadow: var(--pd-shadow-md);
    transform: translateY(-3px);
    border-color: #fde68a;
  }
  .pd-badge-icon {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-radius: 14px;
    width: 48px; height: 48px;
    display: grid; place-items: center;
    margin: 0 auto 8px;
    box-shadow: 0 4px 12px rgba(217,119,6,0.15);
  }
  .pd-badge-icon svg { color: var(--pd-primary); }

  /* ═══ Sections (وصف، تقييمات) ═══ */
  .pd-section {
    background: #fff;
    border-radius: var(--pd-radius-lg);
    padding: 24px;
    box-shadow: var(--pd-shadow-md);
    border: 1px solid rgba(255,255,255,0.8);
    margin-bottom: 20px;
  }
  .pd-section-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 18px;
    font-weight: 900;
    color: var(--pd-text);
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 2px dashed #f1f5f9;
  }
  .pd-section-title::before {
    content: '';
    width: 4px; height: 24px;
    background: linear-gradient(180deg, var(--pd-primary-2), var(--pd-primary));
    border-radius: 2px;
  }

  /* ═══ Reviews Summary ═══ */
  .pd-reviews-header {
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border: 1px solid #fde68a;
    border-radius: var(--pd-radius-md);
    box-shadow: var(--pd-shadow-sm);
    padding: 20px;
  }

  /* ═══ Related Products ═══ */
  .pd-related-card {
    background: #fff;
    border-radius: var(--pd-radius-md);
    box-shadow: var(--pd-shadow-sm);
    transition: .3s cubic-bezier(.2,.9,.3,1.1);
    border: 1px solid var(--pd-border);
    overflow: hidden;
  }
  .pd-related-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--pd-shadow-lg);
    border-color: var(--pd-primary-2);
  }

  /* ═══ Mobile ═══ */
  @media (max-width: 900px) {
    .pd-title { font-size: 22px; }
    .pd-price { font-size: 28px; }
    .pd-section { padding: 18px; border-radius: 20px; }
    .pd-info, .pd-gallery { padding: 16px; }
  }

  @media (max-width: 640px) {
    .pd-share-btn { padding: 10px 8px; font-size: 11px; }
    .pd-badge { padding: 12px 8px; }
    .pd-badge-icon { width: 40px; height: 40px; }
  }

</style>
</head>
<body>

{{-- ═══ Header ═══ --}}
<header class="pd-header">
  <a href="{{ url()->previous() ?: '/demo-shop' }}" class="pd-header-back" aria-label="رجوع">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
  </a>
  <div class="pd-header-title">{{ $product->name }}</div>
  <div class="pd-header-actions">
    <button class="pd-icon-btn" onclick="toggleWishlist({{ $product->id }}, this)" aria-label="المفضلة" id="wishBtn">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="{{ $inWishlist ? '#dc2626' : 'none' }}" stroke="{{ $inWishlist ? '#dc2626' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
    </button>
    <a href="/cart" class="pd-icon-btn" aria-label="السلة">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
    </a>
  </div>
</header>

<main class="pd-wrap">

    {{-- 🧭 Breadcrumbs --}}
    <nav class="pd-crumbs">
      <a href="/">الرئيسية</a>
      <span class="pd-crumbs-sep">›</span>
      <a href="/shop">المتجر</a>
      @if($product->category_id)
        @php $__cat = \App\Models\Category::find($product->category_id); @endphp
        @if($__cat)
          <span class="pd-crumbs-sep">›</span>
          <a href="/shop?category={{ $__cat->id }}">{{ $__cat->name }}</a>
        @endif
      @endif
      <span class="pd-crumbs-sep">›</span>
      <span class="pd-crumbs-current">{{ Str::limit($product->name, 40) }}</span>
    </nav>
  <div class="pd-grid">

    {{-- ═══ Gallery ═══ --}}
    <div class="pd-gallery">
      <div class="pd-gallery-main" id="galleryMain">
        @if($discount > 0)
          <div class="pd-ribbon">
            <span>🔥 وفّر</span>
            <span class="pct">{{ $discount }}%</span>
          </div>
        @endif
        @if($mainImage)
          <img src="{{ $mainImage }}" alt="{{ $product->name }}" id="mainImg" style="cursor:zoom-in;" onclick="openLightbox()">
        @else
          <div class="pd-placeholder">✦</div>
        @endif

        @if(count($extraImages) > 0 || $mainImage)
          <div class="pd-gallery-nav">
            <button type="button" onclick="galleryPrev()" aria-label="السابق">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M15 18l-6-6 6-6"/></svg>
            </button>
            <button type="button" onclick="galleryNext()" aria-label="التالي">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
            </button>
          </div>
        @endif
      </div>

      @if(count($extraImages) > 0)
        <div class="pd-gallery-thumbs">
          <div class="pd-thumb active" onclick="galleryGo(0, this)">
            @if($mainImage)<img src="{{ $mainImage }}" alt="">@endif
          </div>
          @foreach($extraImages as $i => $ei)
            @php
              $eiSrc = str_starts_with($ei, 'http') ? $ei : \Storage::url($ei);
            @endphp
            <div class="pd-thumb" onclick="galleryGo({{ $i + 1 }}, this)">
              <img src="{{ $eiSrc }}" alt="" loading="lazy">
            </div>
          @endforeach
        
      {{-- 🎥 زر الفيديو في المعرض --}}
      @if($product->video)
        <div class="pd-video-thumb" onclick="showVideoInGallery()">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
            <path d="M8 5v14l11-7z"/>
          </svg>
          فيديو
        </div>
        <video id="productVideo" preload="metadata" playsinline controls style="display:none;width:100%;height:100%;object-fit:cover;background:#000">
          <source src="{{ $product->videoUrl() }}" type="video/mp4">
        </video>
        <script>
        function showVideoInGallery() {
          var v = document.getElementById('productVideo');
          var img = document.getElementById('mainImg');
          var main = document.getElementById('galleryMain');
          if (!v) return;
          if (v.style.display === 'none') {
            v.style.display = 'block';
            if (img) img.style.display = 'none';
            v.play();
          } else {
            v.style.display = 'none';
            if (img) img.style.display = 'block';
            v.pause();
          }
        }
        </script>
      @endif

</div>
      @endif
    </div>

    {{-- ═══ Info ═══ --}}
    <div>
      <div class="pd-info">
        @if($product->category)
          <span class="pd-category">{{ $product->category->name }}</span>
        @endif
        <h1 class="pd-title">{{ $product->name }}</h1>

        {{-- 🏪 بطاقة المتجر --}}
        @if($shop)
        <a href="/shop" class="pd-store-card">
          <div class="pd-store-avatar">{{ mb_substr($shop->name ?? 'M', 0, 1) }}</div>
          <div class="pd-store-body">
            <div class="pd-store-name">{{ $shop->name ?? 'المتجر' }}</div>
            <div class="pd-store-sub">🏪 تصفح كل منتجات المتجر</div>
          </div>
          <span class="pd-store-arrow">‹</span>
        </a>
        @endif

        {{-- 🔴 مشاهدون الآن --}}
        <div class="pd-viewers">
          <span class="pd-viewers-dot"></span>
          <span id="viewersCount">5</span> شخصاً يشاهدون الآن
        </div>

        {{-- Rating --}}
        @if($ratingCount > 0)
          <div class="pd-rating-row">
            <div class="pd-stars">
              @for($i = 1; $i <= 5; $i++)
                {{ $i <= round($avgRating) ? '★' : '☆' }}
              @endfor
            </div>
            <span class="pd-rating-text">{{ number_format($avgRating, 1) }} ({{ $ratingCount }} تقييم)</span>
          </div>
        @endif

        {{-- Price --}}
        {{-- 🚚 التوصيل --}}
        <div class="pd-delivery">
          <div class="pd-delivery-icon">🚚</div>
          <div class="pd-delivery-body">
            <div class="pd-delivery-title">توصيل سريع لجميع المحافظات</div>
            <div class="pd-delivery-sub">يصل خلال 2-4 أيام عمل</div>
          </div>
        </div>

        <div class="pd-price-block">
          <div class="pd-price-row">
            <strong class="pd-price">{{ number_format($product->price) }} <small>ر.ي</small></strong>
            @if($product->compare_price && $product->compare_price > $product->price)
              <span class="pd-price-old">{{ number_format($product->compare_price) }}</span>
              <span class="pd-discount">-{{ $discount }}%</span>
            @endif
          </div>
          @if($discount > 0)
            <div class="pd-save-text">💰 توفير {{ number_format($product->compare_price - $product->price) }} ر.ي</div>
          @endif
        </div>

        {{-- Stock --}}
        <div class="pd-stock {{ $product->stock > 0 ? 'in' : 'out' }}">
          @if($product->stock > 0)
            ✓ متوفر — {{ $product->stock }} قطعة
          @else
            ✗ غير متوفر حالياً
          @endif
        </div>

        {{-- 🎨 Colors --}}
                @if(!empty($colors) && count($colors) > 1)
                <div class="pd-options">
                  <div class="pd-option-label">
                    <span>{{ $shopIcon1 }} اختر {{ $shopLabel1 }}:</span>
                    <span id="selectedColorName" style="color:#d97706;font-weight:900;font-size:13px;"></span>
                  </div>
                  <div class="pd-color-grid" id="colorGrid">
                    @foreach($colors as $col)
                      <button type="button"
                        class="pd-color-dot"
                        data-color="{{ $col['name'] }}"
                        data-hex="{{ $col['hex'] }}"
                        style="background:{{ $col['hex'] }}"
                        title="{{ $col['name'] }} — {{ $col['stock'] }} متوفر"
                        onclick="selectColor('{{ $col['name'] }}', this)"
                        aria-label="{{ $col['name'] }}">
                      </button>
                    @endforeach
                  </div>
                </div>
                @endif

                {{-- 📏 Sizes --}}
                @if(!empty($sizes) && count($sizes) > 1)
                <div class="pd-options">
                  <div class="pd-option-label">
                    <span>{{ $shopIcon2 }} اختر {{ $shopLabel2 }}:</span>
                    <span id="selectedSizeName" style="color:#d97706;font-weight:900;font-size:13px;"></span>
                  </div>
                  <div class="pd-size-grid" id="sizeGrid">
                    @foreach($sizes as $sz)
                      <button type="button"
                        class="pd-size"
                        data-size="{{ $sz['name'] }}"
                        data-stock="{{ $sz['stock'] }}"
                        onclick="selectSize('{{ $sz['name'] }}', this)">
                        <span>{{ $sz['name'] }}</span>
                        <span class="pd-size-stock">{{ $sz['stock'] }}</span>
                      </button>
                    @endforeach
                  </div>
                  <div id="stockAlert" class="pd-stock-alert" style="display:none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                    <span id="stockAlertText"></span>
                  </div>
                </div>
                @endif

                {{-- Quantity --}}
        <div class="pd-qty-row">
          <span class="pd-qty-label">الكمية:</span>
          <div class="pd-qty">
            <button type="button" onclick="changeQty(-1)">−</button>
            <input type="text" id="qtyInput" value="1" readonly>
            <button type="button" onclick="changeQty(1)">+</button>
          </div>
        </div>

        {{-- Actions --}}
        <div style="display:flex;gap:10px">
          <button class="pd-btn pd-btn-primary" id="addToCartBtn" style="flex:1" onclick="addToCart({{ $product->id }})" {{ $product->stock < 1 ? 'disabled' : '' }}>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            أضف للسلة
          </button>
          <button class="pd-wishlist {{ $inWishlist ? 'active' : '' }}" onclick="toggleWishlist({{ $product->id }}, this)" id="wishBtn2">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="{{ $inWishlist ? '#dc2626' : 'none' }}" stroke="{{ $inWishlist ? '#dc2626' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
          </button>
        </div>

          {{-- 📤 مشاركة --}}
          <div class="pd-share-bar">
            <a href="https://wa.me/?text={{ urlencode($product->name . ' — ' . request()->fullUrl()) }}"
               target="_blank" rel="noopener" class="pd-share-btn wa">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
              <span>واتساب</span>
            </a>
            <button type="button" class="pd-share-btn cp" onclick="copyProductLink(this)">
              <span>📋 نسخ</span>
            </button>
            <button type="button" class="pd-share-btn native" onclick="nativeShareProduct(this)">
              <span>📤 مشاركة</span>
            </button>
          </div>

      </div>


        {{-- 💬 استفسر عبر واتساب --}}
        <div style="margin-top:10px">
          <x-whatsapp-button :product="$product" :shop="$shop" class="wa-button" />
        </div>
      {{-- Info Badges --}}
      <div class="pd-badges">
        <div class="pd-badge">
          <div class="pd-badge-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 18H3a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-2"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/><path d="M15 8h4l3 3v5a2 2 0 0 1-2 2h-1"/></svg></div>
          <div class="pd-badge-title">شحن سريع</div>
          <div class="pd-badge-sub">توصيل خلال أيام</div>
        </div>
        <div class="pd-badge">
          <div class="pd-badge-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 12a9 9 0 1 0 9-9"/><path d="M3 4v5h5"/></svg></div>
          <div class="pd-badge-title">إرجاع مجاني</div>
          <div class="pd-badge-sub">خلال 15 يوم</div>
        </div>
        <div class="pd-badge">
          <div class="pd-badge-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
          <div class="pd-badge-title">دفع آمن</div>
          <div class="pd-badge-sub">حماية 100%</div>
        </div>
      </div>
    </div>
  </div>

  {{-- ═══ 📐 صف بعمودين: وصف + تقييمات ═══ --}}
  <div class="pd-row-2col">

    {{-- 📝 العمود 1: وصف المنتج --}}
    <div class="pd-col-desc">
      <div class="pd-section pd-desc-section">
        <h2 class="pd-section-title">📝 وصف المنتج</h2>
        <div class="pd-desc-content">
          {!! nl2br(e($product->description ?? 'منتج مختار بعناية ضمن تشكيلة المتجر. جودة عالية وتجربة مميزة تنتظرك.')) !!}
        </div>
      </div>
    </div>

    {{-- 💬 العمود 2: التقييمات --}}
    <div class="pd-col-rev">
      {{-- ═══ Reviews — موحّد ومرتّب ═══ --}}
  <div class="pd-section pd-reviews-compact">

    {{-- 🎯 رأس موحّد --}}
    <div class="rev-head">
      <div class="rev-head-title">
        <span class="rev-head-icon">💬</span>
        <span>التقييمات</span>
        <span class="rev-count-badge">{{ $ratingCount }}</span>
      </div>
      @if($ratingCount > 0)
        <div class="rev-head-rating">
          <strong>{{ number_format($avgRating, 1) }}</strong>
          <span class="rev-stars">@for($i=1;$i<=5;$i++){{ $i <= round($avgRating) ? '★' : '☆' }}@endfor</span>
        </div>
      @endif
    </div>

    <div class="rev-scroll-area">


    {{-- 📊 محتوى التقييمات --}}
    @if($ratingCount > 0)

      {{-- شريط توزيع النجوم --}}
      <div class="rev-bars-compact">
        @foreach([5,4,3,2,1] as $star)
          @php $count = $ratingStats['bars'][$star] ?? 0; $pct = $ratingCount > 0 ? ($count / $ratingCount) * 100 : 0; @endphp
          <div class="rev-bar-row">
            <span class="rev-bar-label">{{ $star }}★</span>
            <div class="rev-bar-track"><div class="rev-bar-fill" style="width:{{ $pct }}%"></div></div>
            <span class="rev-bar-count">{{ $count }}</span>
          </div>
        @endforeach
      </div>

      {{-- قائمة التقييمات --}}
      <div class="rev-list">
        @foreach($reviews as $r)
          <div class="rev-item">
            <div class="rev-item-avatar">{{ mb_substr($r->customer_name ?? 'ع', 0, 1) }}</div>
            <div class="rev-item-body">
              <div class="rev-item-top">
                <span class="rev-item-name">{{ $r->customer_name ?? 'عميل' }}</span>
                <span class="rev-item-date">· {{ $r->created_at->diffForHumans() }}</span>
              </div>
              <div class="rev-item-stars">@for($i=1;$i<=5;$i++){{ $i <= $r->rating ? '★' : '☆' }}@endfor</div>
              @if($r->comment)<div class="rev-item-text">{{ $r->comment }}</div>@endif
            </div>
          </div>
        @endforeach
      </div>

    @else
      {{-- لا توجد تقييمات — عرض مبسّط --}}
      <div class="rev-empty-mini">
        <div class="rev-empty-stars">☆☆☆☆☆</div>
        <div class="rev-empty-text">كن أول من يقيّم هذا المنتج ✨</div>
      </div>
    @endif

    {{-- ➕ زر "أضف تقييمك" --}}
    
    </div>{{-- /rev-scroll-area --}}

    <button type="button" class="rev-add-toggle" id="revAddToggle" onclick="toggleReviewForm()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
      <span id="revToggleText">أضف تقييمك</span>
    </button>

    {{-- 📝 النموذج (مخفي في البداية) --}}
    <div class="rev-form-wrap" id="revFormWrap">
      <form method="POST" action="/product/{{ $product->id }}/review" enctype="multipart/form-data" id="reviewFormEl" onsubmit="submitReview(event)">
        @csrf

        {{-- النجوم --}}
        <div class="rev-form-stars">
          <div class="rev-form-label">⭐ التقييم</div>
          <div class="pd-star-radio">
            @for($i = 5; $i >= 1; $i--)
              <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" required>
              <label for="star{{ $i }}" title="{{ $i }} نجوم">★</label>
            @endfor
          </div>
        </div>

        {{-- الاسم + الجوال --}}
        <div class="rev-form-grid">
          <div>
            <label class="rev-form-label">الاسم *</label>
            <input type="text" name="customer_name" required maxlength="100" placeholder="اسمك" class="rev-input">
          </div>
          <div>
            <label class="rev-form-label">الجوال <span style="color:#94a3b8">(اختياري)</span></label>
            <input type="tel" name="customer_phone" maxlength="30" placeholder="7XXXXXXXX" class="rev-input">
          </div>
        </div>

        {{-- التعليق --}}
        <div style="margin-bottom:12px">
          <label class="rev-form-label">تعليقك <span style="color:#94a3b8">(اختياري)</span></label>
          <textarea name="comment" rows="3" maxlength="1000" placeholder="شاركنا تجربتك..." class="rev-textarea"></textarea>
        </div>

        {{-- الصورة + الإرسال في صف واحد --}}
        <div class="rev-form-footer">
          <input type="file" name="image" accept="image/*" id="imageInput" style="display:none">
          <label for="imageInput" class="rev-upload-btn">
            📷 <span id="uploadText">صورة</span>
          </label>
          <button type="submit" class="rev-submit-btn">
            📤 إرسال التقييم
          </button>
        </div>

        <div id="imagePreview" style="margin-top:10px;display:none">
          <img id="previewImg" style="max-width:100px;border-radius:12px;border:2px solid #fbbf24">
        </div>
      </form>
    </div>

  </div>

  {{-- ═══ أنماط قسم التقييمات ═══ --}}
  <style>
    .pd-reviews-compact { padding: 20px; }

    .rev-head {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 16px;
      padding-bottom: 14px;
      border-bottom: 1px dashed #e2e8f0;
    }
    .rev-head-title {
      display: flex; align-items: center; gap: 8px;
      font-size: 17px; font-weight: 900; color: #17202b;
    }
    .rev-head-icon { font-size: 20px; }
    .rev-count-badge {
      background: #f1f5f9;
      color: #475569;
      padding: 3px 10px;
      border-radius: 99px;
      font-size: 12px;
      font-weight: 900;
    }
    .rev-head-rating {
      display: flex; align-items: center; gap: 6px;
    }
    .rev-head-rating strong {
      font-size: 20px; font-weight: 900; color: #d97706;
    }
    .rev-stars { font-size: 14px; color: #f59e0b; letter-spacing: 1px; }

    .rev-bars-compact {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 6px;
      margin-bottom: 16px;
      padding: 12px;
      background: linear-gradient(135deg, #fffbeb, #fff7ed);
      border: 1px solid #fde68a;
      border-radius: 14px;
    }
    @media (max-width: 560px) {
      .rev-bars-compact {
        grid-template-columns: repeat(5, 1fr);
        gap: 4px;
      }
    }
    .rev-bar-row {
      display: flex; align-items: center; gap: 3px;
      font-size: 9px; font-weight: 900; color: #64748b;
      padding: 4px 6px;
      background: #fff;
      border-radius: 8px;
      flex-direction: row;
    }
    .rev-bar-row .rev-bar-label { min-width: 0; font-size: 10px; }
    .rev-bar-row .rev-bar-track { display: none; }
    .rev-bar-row .rev-bar-count {
      flex: 1;
      text-align: center;
      font-size: 13px;
      font-weight: 900;
      color: #d97706;
    }
    .rev-bar-label { min-width: 24px; text-align: left; }
    .rev-bar-track {
      flex: 1; height: 6px; background: #e2e8f0; border-radius: 3px; overflow: hidden;
    }
    .rev-bar-fill {
      height: 100%;
      background: linear-gradient(90deg, #f59e0b, #d97706);
      border-radius: 3px;
      transition: width .6s ease;
    }
    .rev-bar-count { min-width: 20px; text-align: left; color: #94a3b8; }

    /* 🎯 شبكة عمودين للتقييمات */
    .rev-list {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
      margin-bottom: 16px;
    }
    .rev-item {
      display: flex; gap: 10px;
      padding: 12px;
      background: #fff;
      border: 1px solid #f1f5f9;
      border-radius: 14px;
      box-shadow: 0 1px 3px rgba(15,23,42,0.04);
      transition: .2s;
      min-width: 0;
    }
    .rev-item:hover {
      border-color: #fde68a;
      box-shadow: 0 4px 12px rgba(217,119,6,0.08);
      transform: translateY(-1px);
    }
    .rev-item-avatar {
      width: 34px; height: 34px;
      border-radius: 50%;
      background: linear-gradient(135deg, #f59e0b, #d97706);
      color: #fff;
      display: grid; place-items: center;
      font-size: 13px; font-weight: 900;
      flex-shrink: 0;
      box-shadow: 0 4px 10px rgba(217,119,6,0.25);
    }
    .rev-item-body { flex: 1; min-width: 0; }
    .rev-item-top {
      display: flex; align-items: center; gap: 6px;
      margin-bottom: 2px;
      flex-wrap: wrap;
    }
    .rev-item-name { font-size: 12px; font-weight: 900; color: #17202b; }
    .rev-item-date { font-size: 10px; color: #94a3b8; font-weight: 700; }
    .rev-item-stars { font-size: 11px; color: #f59e0b; letter-spacing: 1px; margin-bottom: 4px; }
    .rev-item-text {
      font-size: 11px; line-height: 1.6; color: #475569;
      margin-top: 4px;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    /* على الجوال: عمود واحد */
    @media (max-width: 560px) {
      .rev-list {
        grid-template-columns: 1fr;
      }
    }

    .rev-empty-mini {
      text-align: center;
      padding: 20px;
      background: #f8fafc;
      border-radius: 14px;
      margin-bottom: 16px;
    }
    .rev-empty-stars {
      font-size: 22px;
      color: #cbd5e1;
      letter-spacing: 3px;
      margin-bottom: 6px;
    }
    .rev-empty-text {
      font-size: 13px;
      font-weight: 800;
      color: #64748b;
    }

    .rev-add-toggle {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #fff9e6, #fff7ed);
      color: #b45309;
      border: 1.5px dashed #fbbf24;
      border-radius: 14px;
      font-size: 13px;
      font-weight: 900;
      cursor: pointer;
      font-family: inherit;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: .25s;
    }
    .rev-add-toggle:hover {
      background: linear-gradient(135deg, #fef3c7, #fde68a);
      border-color: #f59e0b;
      transform: translateY(-1px);
    }
    .rev-add-toggle.active {
      background: #17202b;
      color: #fff;
      border-style: solid;
      border-color: #17202b;
    }

    .rev-form-wrap {
      max-height: 0;
      overflow: hidden;
      opacity: 0;
      transition: max-height .4s ease, opacity .3s ease, padding .3s;
      padding: 0 0;
    }
    .rev-form-wrap.show {
      max-height: 800px;
      opacity: 1;
      padding-top: 16px;
    }

    .rev-form-stars {
      display: flex; align-items: center; justify-content: space-between;
      gap: 10px;
      padding: 12px 14px;
      background: #f8fafc;
      border-radius: 12px;
      margin-bottom: 12px;
      flex-wrap: wrap;
    }
    .pd-star-radio {
      display: flex; gap: 4px;
      direction: ltr;
    }
    .pd-star-radio input { display: none; }
    .pd-star-radio label {
      width: 38px; height: 38px;
      border: 1.5px solid #e2e8f0;
      background: #fff;
      border-radius: 10px;
      font-size: 20px;
      color: #cbd5e1;
      cursor: pointer;
      transition: .2s;
      display: grid; place-items: center;
      user-select: none;
    }
    .pd-star-radio label:hover,
    .pd-star-radio label:hover ~ label {
      background: #fef3c7;
      color: #fbbf24;
      border-color: #fbbf24;
    }
    .pd-star-radio input:checked ~ label,
    .pd-star-radio label:has(~ input:checked) {
      background: linear-gradient(135deg, #f59e0b, #d97706);
      color: #fff;
      border-color: #d97706;
    }
    /* ترتيب صحيح للنجوم المختارة (RTL->LTR) */
    .pd-star-radio input:checked + label,
    .pd-star-radio input:checked + label ~ label {
      background: linear-gradient(135deg, #f59e0b, #d97706);
      color: #fff;
      border-color: #d97706;
    }

    .rev-form-label {
      font-size: 12px; font-weight: 900;
      color: #475569;
      display: block;
      margin-bottom: 6px;
    }
    .rev-form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-bottom: 12px;
    }
    .rev-input, .rev-textarea {
      width: 100%;
      padding: 11px 14px;
      border: 1.5px solid #e2e8f0;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 700;
      outline: none;
      font-family: inherit;
      color: #17202b;
      background: #fff;
      transition: .2s;
    }
    .rev-input:focus, .rev-textarea:focus {
      border-color: #f59e0b;
      box-shadow: 0 0 0 4px rgba(245,158,11,0.12);
    }
    .rev-textarea { resize: vertical; min-height: 70px; line-height: 1.6; }
    .rev-input::placeholder, .rev-textarea::placeholder { color: #94a3b8; font-weight: 600; }

    .rev-form-footer {
      display: grid;
      grid-template-columns: auto 1fr;
      gap: 10px;
      align-items: center;
    }
    .rev-upload-btn {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 12px 18px;
      background: #f8fafc;
      border: 1.5px dashed #cbd5e1;
      border-radius: 12px;
      font-size: 12px; font-weight: 900;
      color: #475569;
      cursor: pointer;
      transition: .2s;
      font-family: inherit;
    }
    .rev-upload-btn:hover {
      border-color: #f59e0b;
      background: #fffbeb;
      color: #d97706;
    }
    .rev-submit-btn {
      padding: 12px 20px;
      background: linear-gradient(135deg, #f59e0b, #d97706);
      color: #fff;
      border: 0;
      border-radius: 12px;
      font-size: 13px; font-weight: 900;
      cursor: pointer;
      font-family: inherit;
      box-shadow: 0 6px 16px rgba(217,119,6,0.3);
      transition: .25s;
    }
    .rev-submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 24px rgba(217,119,6,0.4);
    }

    @media (max-width: 560px) {
      .rev-form-grid { grid-template-columns: 1fr; }
      .pd-star-radio label { width: 34px; height: 34px; font-size: 18px; }
      .rev-head-title { font-size: 15px; }
      .rev-head-rating strong { font-size: 17px; }
    }
  </style>
    </div>

  </div>

  <style>
    .pd-row-2col {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 24px;
      align-items: start;
    }
    @media (max-width: 900px) {
      .pd-row-2col {
        grid-template-columns: 1fr;
        gap: 16px;
      }
    }

    /* 🎯 كل عمود له ارتفاع مرن + scroll ذكي */
    .pd-desc-section,
    .pd-reviews-compact {
      min-height: 480px;
      max-height: 700px;
      display: flex;
      flex-direction: column;
      padding: 20px;
      transition: max-height .3s ease;
    }

    /* عندما يُفتح النموذج — نجعل العمود يتمدد */
    .pd-reviews-compact.form-open {
      max-height: none;
      height: auto;
      min-height: auto;
    }

    /* 📜 منطقة الوصف — scrollable */
    .pd-desc-content {
      flex: 1;
      min-height: 0;
      overflow-y: auto;
      font-size: 14px;
      line-height: 1.9;
      color: #475569;
      font-weight: 600;
      padding: 4px 8px 4px 0;
      margin-top: 4px;
      /* شريط تمرير مخصص */
      scrollbar-width: thin;
      scrollbar-color: #cbd5e1 #f8fafc;
    }
    .pd-desc-content::-webkit-scrollbar {
      width: 6px;
    }
    .pd-desc-content::-webkit-scrollbar-track {
      background: #f8fafc;
      border-radius: 3px;
    }
    .pd-desc-content::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 3px;
    }
    .pd-desc-content::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
    .pd-desc-content p { margin-bottom: 10px; }
    .pd-desc-content p:last-child { margin-bottom: 0; }

    /* 📜 منطقة التقييمات — scrollable */
    .rev-scroll-area {
      flex: 1;
      min-height: 0;
      max-height: 320px;
      overflow-y: auto;
      padding-right: 8px;
      scrollbar-width: thin;
      scrollbar-color: #cbd5e1 #f8fafc;
    }
    .rev-scroll-area::-webkit-scrollbar {
      width: 6px;
    }
    .rev-scroll-area::-webkit-scrollbar-track {
      background: #f8fafc;
      border-radius: 3px;
    }
    .rev-scroll-area::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 3px;
    }
    .rev-scroll-area::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }

    /* 📱 على الجوال — ارتفاع تلقائي */
    @media (max-width: 900px) {
      .pd-desc-section,
      .pd-reviews-compact {
        height: auto;
        max-height: 500px;
      }
    }
  </style>

  


  <style>
  .pd-star-radio{display:flex;gap:6px;direction:ltr;justify-content:flex-end;flex-wrap:wrap}
  .pd-star-radio input{display:none}
  .pd-star-radio label{width:48px;height:48px;border:1.5px solid #e2e8f0;background:#fff;border-radius:12px;font-size:26px;color:#cbd5e1;cursor:pointer;transition:.2s;display:grid;place-items:center;user-select:none}
  .pd-star-radio label:hover{background:#fef3c7;color:#fbbf24;border-color:#fbbf24;transform:scale(1.08)}
  .pd-star-radio label:hover ~ label{background:#fef3c7;color:#fbbf24;border-color:#fbbf24}
  .pd-star-radio input:checked ~ label{background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#fff;border-color:transparent;box-shadow:0 4px 12px rgba(245,158,11,.3)}
  .pd-upload-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 18px;background:#f8fafc;border:1.5px dashed #cbd5e1;border-radius:12px;font-size:13px;font-weight:800;color:#475569;cursor:pointer;transition:.2s}
  .pd-upload-btn:hover{border-color:#f59e0b;color:#d97706;background:#fffbeb}
  </style>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('imageInput');
    if (input) {
      input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('uploadText').textContent = 'تغيير الصورة ✓';
          };
          reader.readAsDataURL(this.files[0]);
        }
      });
    }
  });

  // ═══ Toast ═══
  function showReviewToast(msg, type) {
    var t = document.getElementById('reviewToast');
    if (!t) {
      t = document.createElement('div');
      t.id = 'reviewToast';
      t.className = 'pd-review-toast';
      document.body.appendChild(t);
    }
    t.textContent = msg;
    t.className = 'pd-review-toast show ' + (type || '');
    clearTimeout(window.__rt);
    window.__rt = setTimeout(function() { t.classList.remove('show'); }, 3500);
  }

  // ═══ Submit AJAX ═══
  function submitReview(e) {
    e.preventDefault();
    var form = e.target;
    var btn = form.querySelector('button[type="submit"]');
    var originalText = btn.innerHTML;

    // تحقق
    var rating = form.querySelector('input[name="rating"]:checked');
    var name = form.querySelector('input[name="customer_name"]').value.trim();

    if (!name) {
      showReviewToast('❌ الرجاء إدخال الاسم', 'error');
      return;
    }
    if (!rating) {
      showReviewToast('❌ الرجاء اختيار التقييم', 'error');
      return;
    }

    btn.disabled = true;
    btn.innerHTML = '⏳ جاري الإرسال...';

    var fd = new FormData(form);

    fetch(form.action, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      body: fd
    })
    .then(function(r) {
      return r.json().then(function(d) { return { ok: r.ok, status: r.status, data: d }; });
    })
    .then(function(res) {
      btn.disabled = false;
      btn.innerHTML = originalText;

      if (res.ok && res.data.ok) {
        showReviewToast('✅ ' + res.data.message, 'success');
        form.reset();
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('uploadText').textContent = 'اختر صورة';

        // أضف التقييم للقائمة فوراً
        setTimeout(function() { location.reload(); }, 1800);
      } else {
        var msg = res.data.message || 'فشل الإرسال';
        if (res.status === 422 && res.data.errors) {
          var first = Object.values(res.data.errors)[0];
          msg = Array.isArray(first) ? first[0] : first;
        }
        showReviewToast('❌ ' + msg, 'error');
      }
    })
    .catch(function(e) {
      btn.disabled = false;
      btn.innerHTML = originalText;
      showReviewToast('❌ تعذر الاتصال بالسيرفر', 'error');
    });
  }
</script>

  {{-- ═══ Related ═══ --}}
  @if($related->count() > 0)
    <div class="pd-section">
      <h2 class="pd-section-title">🔥 قد يعجبك أيضاً</h2>
      <div class="pd-related-grid">
        @foreach($related as $rp)
          @php
            $rpImg = $rp->image;
            $rpExt = $rpImg && str_starts_with($rpImg, 'http');
            $rpSrc = $rpExt ? $rpImg : ($rpImg ? \Storage::url($rpImg) : null);
            $rpDisc = ($rp->compare_price && $rp->compare_price > $rp->price)
              ? round((1 - $rp->price / $rp->compare_price) * 100) : 0;
          @endphp
          <a href="/product/{{ $rp->id }}" class="pd-related-card">
            <div class="pd-related-media">
              @if($rpSrc)
                <img src="{{ $rpSrc }}" alt="{{ $rp->name }}" loading="lazy">
              @else
                <div class="pd-related-placeholder">📦</div>
              @endif
              @if($rpDisc > 0)
                <span class="pd-related-discount">-{{ $rpDisc }}%</span>
              @endif
            </div>
            <div class="pd-related-body">
              <div class="pd-related-name">{{ $rp->name }}</div>
              <div>
                <span class="pd-related-price">{{ number_format($rp->price) }} ر.ي</span>
                @if($rp->compare_price && $rp->compare_price > $rp->price)
                  <span class="pd-related-old">{{ number_format($rp->compare_price) }}</span>
                @endif
              </div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  @endif

</main>

{{-- ═══ Floating Cart ═══ --}}
<a href="/cart" class="pd-floating-cart" aria-label="السلة">
  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
  <span class="pd-floating-cart-badge" id="cartBadge">0</span>
</a>

{{-- ═══ Toast ═══ --}}
<div class="pd-toast" id="toast"></div>

<script>
// ═══ Gallery ═══
@php
  $allImages = [];
  if ($mainImage) $allImages[] = $mainImage;
  foreach ($extraImages as $ei) {
    $allImages[] = str_starts_with($ei, 'http') ? $ei : \Storage::url($ei);
  }
@endphp
var galleryImages = {!! json_encode($allImages) !!};
var currentImg = 0;

function galleryGo(idx, el) {
  if (!galleryImages.length) return;
  currentImg = idx;
  var mainImg = document.getElementById('mainImg');
  if (mainImg) mainImg.src = galleryImages[idx];
  document.querySelectorAll('.pd-thumb').forEach(function(t){t.classList.remove('active');});
  if (el) el.classList.add('active');
}
function galleryNext(){ if (!galleryImages.length) return; currentImg = (currentImg + 1) % galleryImages.length; galleryGo(currentImg, document.querySelectorAll('.pd-thumb')[currentImg]); }
function galleryPrev(){ if (!galleryImages.length) return; currentImg = (currentImg - 1 + galleryImages.length) % galleryImages.length; galleryGo(currentImg, document.querySelectorAll('.pd-thumb')[currentImg]); }

// ═══ Size ═══
function selectSize(el) {
  document.querySelectorAll('.pd-size').forEach(function(b){b.classList.remove('active');});
  el.classList.add('active');
}

// ═══ Quantity ═══
var qty = 1;
function changeQty(d) {
  qty = Math.max(1, Math.min({{ $product->stock ?: 99 }}, qty + d));
  document.getElementById('qtyInput').value = qty;
}

// ═══ Toast ═══
function showToast(msg, type) {
  var t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'pd-toast show ' + (type || '');
  clearTimeout(window.__t);
  window.__t = setTimeout(function(){t.classList.remove('show');}, 2500);
}

// ═══ Add to Cart ═══
// ═══ 🎨 حالة الاختيار ═══
  var selectedColor = null;
  var selectedSize = null;
  var selectedVariantId = null;
  var variantsMap = @json($variantsMap ?? []);
  var hasVariants = {{ !empty($hasVariants) ? 'true' : 'false' }};
  var basePrice = {{ (float) $product->price }};
  var baseStock = {{ (int) $product->stock }};

  window.selectColor = function(name, el) {
    document.querySelectorAll('.pd-color-dot').forEach(function(b){b.classList.remove('active')});
    el.classList.add('active');
    selectedColor = name;
    var el2 = document.getElementById('selectedColorName');
    if (el2) el2.textContent = name;
    updateSizeAvailability();
    updateVariantSelection();
  };

  window.selectSize = function(name, el) {
    if (el && el.classList.contains('disabled')) return;
    document.querySelectorAll('.pd-size').forEach(function(b){b.classList.remove('active')});
    if (el) el.classList.add('active');
    selectedSize = name;
    var el2 = document.getElementById('selectedSizeName');
    if (el2) el2.textContent = name;
    updateVariantSelection();
  };

  function updateSizeAvailability() {
    if (!hasVariants) return;
    var sizeButtons = document.querySelectorAll('.pd-size');
    sizeButtons.forEach(function(btn) {
      var sizeName = btn.dataset.size;
      var key = (selectedColor || '') + '|' + sizeName;
      var v = variantsMap[key];
      if (selectedColor && (!v || v.stock < 1)) {
        btn.classList.add('disabled');
        btn.disabled = true;
      } else {
        btn.classList.remove('disabled');
        btn.disabled = false;
      }
    });
    // إذا كان المقاس المختار أصبح غير متاح → إلغاء الاختيار
    if (selectedSize) {
      var key2 = (selectedColor || '') + '|' + selectedSize;
      var v2 = variantsMap[key2];
      if (selectedColor && (!v2 || v2.stock < 1)) {
        selectedSize = null;
        var el3 = document.getElementById('selectedSizeName');
        if (el3) el3.textContent = '';
        document.querySelectorAll('.pd-size').forEach(function(b){b.classList.remove('active')});
      }
    }
  }

  function updateVariantSelection() {
    if (!hasVariants) return;

    var key = (selectedColor || '') + '|' + (selectedSize || '');
    var v = variantsMap[key];

    // إذا لا يوجد اختيار بعد
    if (!selectedColor && !selectedSize) {
      selectedVariantId = null;
      showStockAlert(null);
      return;
    }

    // إذا اخترنا لوناً فقط، نستخدم أول variant مطابق
    if (selectedColor && !selectedSize) {
      var found = null;
      for (var k in variantsMap) {
        var parts = k.split('|');
        if (parts[0] === selectedColor && variantsMap[k].stock > 0) {
          found = variantsMap[k];
          break;
        }
      }
      v = found;
    }

    if (v && v.stock > 0) {
      selectedVariantId = v.id;
      showStockAlert(v.stock, true);
      updatePrice(v.price);
    } else {
      selectedVariantId = null;
      if (selectedSize || selectedColor) {
        showStockAlert(0, false);
      }
    }
    if (typeof refreshAddBtn === 'function') setTimeout(refreshAddBtn, 30);
  }

  function showStockAlert(stock, ok) {
    var el = document.getElementById('stockAlert');
    var txt = document.getElementById('stockAlertText');
    if (!el || !txt) return;
    if (stock === null) { el.style.display = 'none'; return; }
    el.style.display = 'flex';
    if (!ok || stock < 1) {
      el.classList.remove('ok');
      txt.textContent = 'غير متوفر بهذا الاختيار — جرب لوناً أو مقاساً آخر';
    } else if (stock <= 3) {
      el.classList.remove('ok');
      txt.textContent = 'متبقي ' + stock + ' فقط — اطلب الآن!';
    } else {
      el.classList.add('ok');
      txt.textContent = 'متوفر ' + stock + ' قطعة';
    }
  }

  function updatePrice(variantPrice) {
    var priceEl = document.querySelector('.pd-price');
    if (!priceEl) return;
    var p = variantPrice && variantPrice > 0 ? variantPrice : basePrice;
    priceEl.innerHTML = Number(p).toLocaleString('ar-EG') + ' <small>ر.ي</small>';
  }

  // ═══ 🛒 Add to Cart ═══
  window.addToCart = function(pid) {
    if (hasVariants && !selectedVariantId) {
      var needColor = {{ !empty($colors) ? 'true' : 'false' }};
      var needSize = {{ !empty($sizes) ? 'true' : 'false' }};
      var msg = 'الرجاء اختيار ';
      var label1 = '{{ $shopLabel1 ?? "اللون" }}';
      var label2 = '{{ $shopLabel2 ?? "المقاس" }}';
      if (needColor && !selectedColor) msg += label1 + ' ';
      if (needSize && !selectedSize) msg += 'و' + label2 + ' ';
      showToast(msg, 'error');
      return;
    }

    var csrf = '{{ csrf_token() }}';
    var form = new FormData();
    form.append('_token', csrf);
    form.append('qty', qty);
    if (selectedVariantId) {
      form.append('variant_id', selectedVariantId);
    }
    if (selectedColor) form.append('selected_color', selectedColor);
    if (selectedSize) form.append('selected_size', selectedSize);

    fetch('/cart/add/' + pid, {
      method: 'POST',
      headers: {'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest'},
      body: form
    })
    .then(function(r){ return r.json().catch(function(){return {}}) })
    .then(function(d) {
      if (d.success || d.count !== undefined) {
        showToast('✓ تم إضافة المنتج للسلة', 'success');
      } else {
        showToast(d.message || 'تعذر الإضافة', 'error');
      }
      updateCartBadge();
    })
    .catch(function(){ showToast('✓ تم إضافة المنتج', 'success'); });
    updateCartBadge();
  };

  function updateCartBadge() {
  fetch('/cart/count', {headers: {'X-Requested-With': 'XMLHttpRequest'}})
    .then(r => r.json().catch(() => ({})))
    .then(d => {
      var b = document.getElementById('cartBadge');
      if (b && d.count !== undefined) b.textContent = d.count;
    })
    .catch(() => {});
}

// ═══ Wishlist ═══
function toggleWishlist(pid, btn) {
  fetch('/wishlist/toggle/' + pid, {
    method: 'POST',
    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest'}
  })
  .then(r => r.json().catch(() => ({})))
  .then(d => {
    var isIn = d.in_wishlist;
    document.querySelectorAll('#wishBtn, #wishBtn2').forEach(function(el){
      el.classList.toggle('active', isIn);
      var svg = el.querySelector('svg');
      if (svg) {
        svg.setAttribute('fill', isIn ? '#dc2626' : 'none');
        svg.setAttribute('stroke', isIn ? '#dc2626' : 'currentColor');
      }
    });
    showToast(isIn ? '❤️ أُضيف للمفضلة' : '💔 أُزيل من المفضلة', isIn ? 'success' : '');
  })
  .catch(() => showToast('تعذر التحديث', 'error'));
}

document.addEventListener('DOMContentLoaded', function(){
  updateCartBadge();
  if (window.lucide) lucide.createIcons();
});


  // ═══ 🎯 Auto-select: إذا كان هناك لون واحد أو مقاس واحد فقط ═══
  (function() {
    var singleColorBtn = document.querySelector('.pd-color-dot');
    var allColors = document.querySelectorAll('.pd-color-dot');
    var allSizes = document.querySelectorAll('.pd-size');
    var serverColors = @json($colors ?? []);
    var serverSizes = @json($sizes ?? []);

    // إذا كان هناك لون واحد فقط → اختره تلقائياً
    if (serverColors.length === 1 && allColors.length === 0) {
      // القسم مخفي (لأنه أحادي) → نضبط الحالة يدوياً
      selectedColor = serverColors[0].name;
    } else if (serverColors.length === 1 && allColors.length === 1) {
      // احتياطي: الزر ظاهر → اضغطه
      allColors[0].click();
    }

    // إذا كان هناك مقاس واحد فقط → اختره تلقائياً
    if (serverSizes.length === 1 && allSizes.length === 0) {
      selectedSize = serverSizes[0].name;
    } else if (serverSizes.length === 1 && allSizes.length === 1) {
      allSizes[0].click();
    }

    // حدث الاختيار النهائي
    if (typeof updateVariantSelection === 'function') {
      updateVariantSelection();
    }
  })();


  

  // ═══ 🛡️ إدارة زر الإضافة للسلة ═══
  window.refreshAddBtn = function() {
    var btn = document.getElementById('addToCartBtn');
    if (!btn) return;

    var colorsCount = document.querySelectorAll('.pd-color-dot').length;
    var sizesCount = document.querySelectorAll('.pd-size').length;

    // إذا ما فيه variants ظاهرة → الزر مفعّل دائماً
    if (colorsCount === 0 && sizesCount === 0) {
      btn.disabled = false;
      btn.style.opacity = '1';
      btn.style.cursor = 'pointer';
      return;
    }

    // نحتاج: كل قسم ظاهر يجب اختياره
    var needColor = colorsCount > 0;
    var needSize = sizesCount > 0;

    var ok = true;
    if (needColor && !selectedColor) ok = false;
    if (needSize && !selectedSize) ok = false;
    if (!selectedVariantId) ok = false;

    if (ok) {
      btn.disabled = false;
      btn.style.opacity = '1';
      btn.style.cursor = 'pointer';
    } else {
      btn.disabled = true;
      btn.style.opacity = '0.5';
      btn.style.cursor = 'not-allowed';
    }
  };

  // نراقب النقرات على الألوان والمقاسات
  document.addEventListener('click', function(e) {
    if (e.target.closest('.pd-color-dot') || e.target.closest('.pd-size')) {
      setTimeout(refreshAddBtn, 30);
      setTimeout(refreshAddBtn, 200);
    }
  });

  // فترة فحص مبكر + متأخر
  setTimeout(refreshAddBtn, 200);
  setTimeout(refreshAddBtn, 500);
  setTimeout(refreshAddBtn, 1000);
  setTimeout(refreshAddBtn, 2000);


  // ═══ 📊 Live Viewers (تحديث تلقائي) ═══
  (function() {
    var el = document.getElementById('viewersCount');
    if (!el) return;
    var base = Math.floor(Math.random() * 8) + 3;
    el.textContent = base;
    setInterval(function() {
      var delta = Math.floor(Math.random() * 5) - 2;
      base = Math.max(2, base + delta);
      el.textContent = base;
    }, 8000);
  })();

  // ═══ 📤 Copy Link ═══
  window.copyProductLink = function(btn) {
    var url = window.location.href;
    if (navigator.clipboard) {
      navigator.clipboard.writeText(url).then(function() {
        var orig = btn.innerHTML;
        btn.innerHTML = '<span>✅ تم النسخ</span>';
        setTimeout(function() { btn.innerHTML = orig; }, 1500);
      });
    } else {
      var tmp = document.createElement('textarea');
      tmp.value = url;
      document.body.appendChild(tmp);
      tmp.select();
      document.execCommand('copy');
      document.body.removeChild(tmp);
      var orig = btn.innerHTML;
      btn.innerHTML = '<span>✅ تم النسخ</span>';
      setTimeout(function() { btn.innerHTML = orig; }, 1500);
    }
  };

  // ═══ 📤 Native Share ═══
  window.nativeShareProduct = function(btn) {
    var data = {
      title: '{{ addslashes($product->name) }}',
      text: 'شاهد هذا المنتج: {{ addslashes($product->name) }}',
      url: window.location.href,
    };
    if (navigator.share) {
      navigator.share(data).catch(function(){});
    } else {
      window.copyProductLink(btn);
    }
  };

  
  (function() {
    var lb = document.getElementById('pdLightbox');
    if (!lb) return;
    var startX = 0;
    lb.addEventListener('touchstart', function(e) {
      startX = e.touches[0].clientX;
    }, { passive: true });
    lb.addEventListener('touchend', function(e) {
      var dx = e.changedTouches[0].clientX - startX;
      if (Math.abs(dx) > 50) navigateLightbox(dx > 0 ? 1 : -1);
    }, { passive: true });
  })();


  // ═══ 🔍 Lightbox — نسخة محسّنة ═══
  window._lbImages = [];
  window._lbIndex = 0;

  window.openLightbox = function() {
    var mainImg = document.getElementById('mainImg');
    var lb = document.getElementById('pdLightbox');
    var lbImg = document.getElementById('pdLightboxImg');

    if (!mainImg || !lb || !lbImg) {
      console.warn('Lightbox: عنصر مفقود', {mainImg, lb, lbImg});
      return;
    }

    window._lbImages = [mainImg.src];

    // نجمع كل الصور المصغرة
    document.querySelectorAll('.pd-thumb img, .pd-gallery-thumbs img').forEach(function(t) {
      if (t.src && t.src !== mainImg.src && window._lbImages.indexOf(t.src) === -1) {
        window._lbImages.push(t.src);
      }
    });

    window._lbIndex = 0;
    lbImg.src = window._lbImages[0];
    lb.classList.add('show');
    document.body.style.overflow = 'hidden';
  };

  window.closeLightbox = function() {
    var lb = document.getElementById('pdLightbox');
    if (lb) lb.classList.remove('show');
    document.body.style.overflow = '';
  };

  window.closeLightboxOnBg = function(e) {
    if (e.target.id === 'pdLightbox') closeLightbox();
  };

  window.navigateLightbox = function(dir) {
    if (!window._lbImages.length) return;
    window._lbIndex = (window._lbIndex + dir + window._lbImages.length) % window._lbImages.length;
    document.getElementById('pdLightboxImg').src = window._lbImages[window._lbIndex];
  };

  document.addEventListener('keydown', function(e) {
    var lb = document.getElementById('pdLightbox');
    if (!lb || !lb.classList.contains('show')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft')  navigateLightbox(-1);
    if (e.key === 'ArrowRight') navigateLightbox(1);
  });

  // Swipe للجوال
  (function() {
    var lb = document.getElementById('pdLightbox');
    if (!lb) return;
    var startX = 0;
    lb.addEventListener('touchstart', function(e) {
      startX = e.touches[0].clientX;
    }, { passive: true });
    lb.addEventListener('touchend', function(e) {
      var dx = e.changedTouches[0].clientX - startX;
      if (Math.abs(dx) > 50) navigateLightbox(dx > 0 ? -1 : 1);
    }, { passive: true });
  })();


  // ═══ 📝 toggle نموذج التقييم — نسخة قوية ═══
  window.toggleReviewForm = function() {
    var wrap = document.getElementById('revFormWrap');
    var btn = document.getElementById('revAddToggle');
    var txt = document.getElementById('revToggleText');

    if (!wrap || !btn) {
      console.warn('Review toggle: عنصر مفقود', { wrap: !!wrap, btn: !!btn });
      return;
    }

    var isOpen = wrap.classList.contains('show');
    var card = wrap.closest('.pd-reviews-compact');

    if (isOpen) {
      wrap.classList.remove('show');
      btn.classList.remove('active');
      if (card) card.classList.remove('form-open');
      if (txt) txt.textContent = 'أضف تقييمك';
    } else {
      wrap.classList.add('show');
      btn.classList.add('active');
      if (card) card.classList.add('form-open');
      if (txt) txt.textContent = 'إغلاق النموذج';
      // نمرر إلى النموذج
      setTimeout(function() {
        wrap.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }, 250);
    }
  };

  // ربط الزر كـ fallback
  document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('revAddToggle');
    if (btn && !btn.dataset.bound) {
      btn.dataset.bound = '1';
      // نحذف onclick القديم ونستخدم addEventListener
      btn.removeAttribute('onclick');
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        window.toggleReviewForm();
      });
      console.log('✅ review toggle bound');
    }
  });

</script>


{{-- 🕐 Recently Viewed data --}}
<div id="recentlyViewedData" 
     data-id="{{ $product->id }}"
     data-name="{{ $product->name }}"
     data-price="{{ number_format($product->price, 0) }}"
     data-image="@php echo $mainImage ?? ''; @endphp"
     data-url="{{ url('/product/' . $product->id) }}"
     style="display:none"></div>

@include('components.recently-viewed')
@include('components.floating-actions')

{{-- 🔍 Lightbox --}}
<div id="pdLightbox" class="pd-lightbox" onclick="closeLightboxOnBg(event)">
  <button type="button" class="pd-lb-close" onclick="closeLightbox()">×</button>
  <button type="button" class="pd-lb-nav prev" onclick="event.stopPropagation();navigateLightbox(-1)">›</button>
  <img id="pdLightboxImg" src="" alt="">
  <button type="button" class="pd-lb-nav next" onclick="event.stopPropagation();navigateLightbox(1)">‹</button>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>سلة التسوق — {{ $shop->name ?? 'المتجر' }}</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; font-family: Cairo, sans-serif; margin: 0; padding: 0; }
  body { background: #f5f7fa; min-height: 100vh; padding-bottom: 120px; }

  /* ═══ Header ثابت مع زر الرجوع ═══ */
  .cart-header {
    background: white;
    padding: 12px 16px;
    border-bottom: 1px solid #e8ecf1;
    position: sticky;
    top: 0;
    z-index: 100;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
  }
  .back-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #f59e0b;
    text-decoration: none;
    font-weight: 900;
    font-size: 14px;
    padding: 8px 14px;
    background: #fef3c7;
    border-radius: 12px;
    transition: all 0.25s;
  }
  .back-btn:hover {
    background: #f59e0b;
    color: white;
    transform: translateX(3px);
  }
  .cart-header h1 {
    font-size: 16px;
    font-weight: 900;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .cart-count-badge {
    background: #ef4444;
    color: white;
    font-size: 11px;
    font-weight: 900;
    padding: 2px 8px;
    border-radius: 999px;
  }

  /* ═══ Container ═══ */
  .cart-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 16px;
  }

  /* ═══ بطاقة المنتج ═══ */
  .cart-item {
    background: white;
    border-radius: 16px;
    padding: 14px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    border: 1px solid #f1f5f9;
    transition: all 0.25s;
  }
  .cart-item:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    transform: translateY(-2px);
  }

  .cart-item-image {
    width: 80px;
    height: 80px;
    border-radius: 14px;
    overflow: hidden;
    flex-shrink: 0;
    background: linear-gradient(135deg, #fef3c7, #fed7aa);
  }
  .cart-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .cart-item-info {
    flex: 1;
    min-width: 0;
  }
  .cart-item-name {
    font-weight: 900;
    font-size: 14px;
    color: #1f2937;
    margin-bottom: 4px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
  }
  .cart-item-price {
    font-size: 13px;
    color: #6b7280;
    font-weight: 700;
    margin-bottom: 8px;
  }
  .cart-item-price strong {
    color: #f59e0b;
    font-size: 15px;
  }

  /* ═══ أزرار الكمية ═══ */
  .qty-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8fafc;
    border-radius: 10px;
    padding: 4px;
    width: fit-content;
  }
  .qty-btn {
    width: 30px;
    height: 30px;
    border: none;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    font-size: 18px;
    font-weight: 900;
    color: #f59e0b;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    transition: all 0.15s;
    font-family: inherit;
  }
  .qty-btn:hover {
    background: #f59e0b;
    color: white;
    transform: scale(1.1);
  }
  .qty-value {
    font-weight: 900;
    font-size: 15px;
    min-width: 26px;
    text-align: center;
    color: #1f2937;
  }

  /* ═══ زر الحذف ═══ */
  .cart-item-remove {
    width: 32px;
    height: 32px;
    border: none;
    background: #fef2f2;
    border-radius: 10px;
    cursor: pointer;
    color: #ef4444;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.2s;
  }
  .cart-item-remove:hover {
    background: #ef4444;
    color: white;
    transform: scale(1.1);
  }

  /* ═══ الإجمالي ═══ */
  .cart-summary {
    background: white;
    border-radius: 20px;
    padding: 20px;
    margin-top: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 2px solid #fef3c7;
  }
  .cart-summary-header {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 900;
    font-size: 16px;
    color: #1f2937;
    margin-bottom: 14px;
    padding-bottom: 12px;
    border-bottom: 2px dashed #e5e7eb;
  }
  .cart-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    font-size: 14px;
    color: #4b5563;
    font-weight: 700;
  }
  .cart-summary-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 0;
    margin-top: 8px;
    border-top: 2px solid #fef3c7;
    font-size: 18px;
    font-weight: 900;
    color: #1f2937;
  }
  .cart-summary-total strong {
    color: #f59e0b;
    font-size: 24px;
  }

  .btn-checkout {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #fbbf24, #f97316);
    color: white;
    border: none;
    border-radius: 14px;
    font-weight: 900;
    font-size: 16px;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.3s;
    box-shadow: 0 6px 20px rgba(245,158,11,0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 12px;
    text-decoration: none;
  }
  .btn-checkout:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(245,158,11,0.5);
  }

  /* ═══ السلة الفارغة ═══ */
  .empty-cart {
    text-align: center;
    padding: 50px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    margin-top: 20px;
  }
  .empty-cart-icon {
    font-size: 80px;
    margin-bottom: 16px;
    display: inline-block;
    animation: float 3s ease-in-out infinite;
  }
  @keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
  }
  .empty-cart h2 {
    font-size: 20px;
    font-weight: 900;
    color: #1f2937;
    margin-bottom: 8px;
  }
  .empty-cart p {
    color: #6b7280;
    font-size: 14px;
    margin-bottom: 20px;
  }
  .btn-shop {
    display: inline-block;
    padding: 12px 28px;
    background: linear-gradient(135deg, #fbbf24, #f97316);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 900;
    font-size: 14px;
    box-shadow: 0 6px 20px rgba(245,158,11,0.35);
    transition: all 0.3s;
  }
  .btn-shop:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(245,158,11,0.5);
  }

  /* ═══ بانر الشحن ═══ */
  .shipping-banner {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border: 2px solid #6ee7b7;
    border-radius: 14px;
    padding: 14px 16px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 900;
    font-size: 13px;
    color: #065f46;
  }

  /* ═══ 🛒 Floating Cart Button ═══ */
  .floating-cart {
    position: fixed;
    bottom: 20px;
    left: 20px;
    z-index: 999;
    background: linear-gradient(135deg, #fbbf24, #f97316);
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 8px 24px rgba(245,158,11,0.5);
    transition: all 0.3s;
    animation: float 3s ease-in-out infinite;
  }
  .floating-cart:hover {
    transform: scale(1.15);
    box-shadow: 0 12px 32px rgba(245,158,11,0.7);
  }
  .floating-cart-icon {
    font-size: 26px;
  }
  .floating-cart-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #ef4444;
    color: white;
    font-size: 11px;
    font-weight: 900;
    min-width: 22px;
    height: 22px;
    border-radius: 999px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
  }

  @media (max-width: 480px) {
    .cart-item-image { width: 70px; height: 70px; }
    .cart-item-name { font-size: 13px; }
    .cart-header h1 { font-size: 14px; }
    .back-btn { font-size: 12px; padding: 6px 10px; }
  }
</style>
</head>
<body>

<style>
  /* ═══ 🎨 Premium Cart Design v2 ═══ */
  * { box-sizing: border-box; }
  :root {
    --c-bg: linear-gradient(180deg, #fafaf7 0%, #f5f3ee 50%, #fafaf7 100%);
    --c-primary: #d97706;
    --c-primary-2: #f59e0b;
    --c-dark: #0f172a;
    --c-text: #17202b;
    --c-muted: #64748b;
    --c-border: #e5e7eb;
    --c-shadow-sm: 0 2px 4px rgba(15,23,42,0.04), 0 6px 16px -4px rgba(15,23,42,0.06);
    --c-shadow-md: 0 4px 12px rgba(15,23,42,0.05), 0 16px 40px -12px rgba(15,23,42,0.12);
    --c-shadow-lg: 0 8px 24px rgba(15,23,42,0.08), 0 32px 64px -16px rgba(15,23,42,0.18);
    --c-shadow-primary: 0 8px 24px -4px rgba(217,119,6,0.3), 0 20px 48px -12px rgba(217,119,6,0.25);
  }
  body {
    font-family: 'Cairo', 'Tajawal', system-ui, sans-serif;
    background: var(--c-bg);
    margin: 0;
    color: var(--c-text);
    -webkit-font-smoothing: antialiased;
    padding-bottom: 120px;
    min-height: 100vh;
  }

  .cart-wrap {
    max-width: 920px;
    margin: 0 auto;
    padding: 16px;
  }

  /* ═══════════════════════════════════════
     🎯 Header Premium
     ═══════════════════════════════════════ */
  .c-header {
    background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%);
    border: 1px solid rgba(229,231,235,0.8);
    border-radius: 20px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
    box-shadow: var(--c-shadow-md);
    position: sticky;
    top: 8px;
    z-index: 50;
    backdrop-filter: blur(12px);
  }
  .c-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 14px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    color: #475569;
    font-size: 12px;
    font-weight: 900;
    text-decoration: none;
    transition: .25s;
    box-shadow: 0 2px 4px rgba(15,23,42,0.04);
  }
  .c-back:hover {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #b45309;
    border-color: #fbbf24;
    transform: translateX(-2px);
    box-shadow: 0 6px 16px rgba(217,119,6,0.2);
  }
  .c-back svg { transition: .25s; }
  .c-back:hover svg { transform: translateX(-2px); }

  .c-header-title {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    justify-content: center;
  }
  .c-header-icon {
    width: 40px;
    height: 40px;
    border-radius: 14px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    display: grid;
    place-items: center;
    font-size: 20px;
    box-shadow: 0 6px 16px rgba(217,119,6,0.35);
  }
  .c-header-text {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    line-height: 1.2;
  }
  .c-header-text-main {
    font-size: 16px;
    font-weight: 900;
    color: var(--c-text);
  }
  .c-header-text-sub {
    font-size: 11px;
    font-weight: 700;
    color: var(--c-muted);
    margin-top: 2px;
  }
  .c-header-count {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color: #fff;
    padding: 3px 10px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 900;
    box-shadow: 0 4px 12px rgba(220,38,38,0.35);
    animation: cPulse 2s infinite;
  }
  @keyframes cPulse {
    0%, 100% { transform: scale(1); }
    50%      { transform: scale(1.08); }
  }

  /* ═══════════════════════════════════════
     🛒 Empty Cart
     ═══════════════════════════════════════ */
  .c-empty {
    background: #fff;
    border: 1px solid rgba(229,231,235,0.8);
    border-radius: 28px;
    padding: 60px 30px;
    text-align: center;
    box-shadow: var(--c-shadow-lg);
    position: relative;
    overflow: hidden;
  }
  .c-empty::before {
    content: '';
    position: absolute;
    top: -50%; right: -20%;
    width: 300px; height: 300px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(245,158,11,0.08) 0%, transparent 70%);
    pointer-events: none;
  }
  .c-empty-icon {
    font-size: 88px;
    margin-bottom: 20px;
    filter: drop-shadow(0 12px 24px rgba(217,119,6,0.2));
  }
  .c-empty-title {
    font-size: 24px;
    font-weight: 900;
    color: var(--c-text);
    margin-bottom: 10px;
    letter-spacing: -0.5px;
  }
  .c-empty-desc {
    font-size: 14px;
    color: var(--c-muted);
    font-weight: 700;
    margin-bottom: 28px;
    line-height: 1.6;
  }
  .c-empty-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 32px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    text-decoration: none;
    border-radius: 16px;
    font-weight: 900;
    font-size: 15px;
    box-shadow: var(--c-shadow-primary);
    transition: .3s cubic-bezier(.2,.9,.3,1.1);
    position: relative;
    overflow: hidden;
  }
  .c-empty-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 40px -8px rgba(217,119,6,0.4);
  }

  /* ═══════════════════════════════════════
     🚚 Free Shipping Alert
     ═══════════════════════════════════════ */
  .c-ship-alert {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 18px;
    border-radius: 18px;
    margin-bottom: 18px;
    font-size: 13px;
    font-weight: 800;
    position: relative;
    overflow: hidden;
    transition: .3s;
  }
  .c-ship-alert.ok {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border: 1.5px solid #bbf7d0;
    color: #166534;
    box-shadow: 0 8px 24px -8px rgba(16,163,74,0.25);
  }
  .c-ship-alert.warn {
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border: 1.5px solid #fde68a;
    color: #b45309;
    box-shadow: 0 8px 24px -8px rgba(217,119,6,0.25);
  }
  .c-ship-alert-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: #fff;
    display: grid;
    place-items: center;
    font-size: 22px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }
  .c-ship-alert-body { flex: 1; line-height: 1.5; }
  .c-ship-alert-body strong { font-weight: 900; }

  /* ═══════════════════════════════════════
     📦 Cart Items — Premium Cards
     ═══════════════════════════════════════ */
  .c-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 16px;
  }

  .c-item {
    background: #ffffff;
    border: 1.5px solid rgba(229,231,235,0.9);
    border-radius: 22px;
    padding: 14px;
    display: grid;
    grid-template-columns: 96px 1fr auto;
    gap: 14px;
    align-items: center;
    box-shadow: var(--c-shadow-sm);
    transition: .3s cubic-bezier(.2,.9,.3,1.1);
    position: relative;
    overflow: hidden;
  }
  .c-item::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 22px;
    padding: 1.5px;
    background: linear-gradient(135deg, transparent, transparent);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events: none;
    transition: .3s;
  }
  .c-item:hover {
    transform: translateY(-3px);
    box-shadow: var(--c-shadow-lg);
    border-color: #fbbf24;
  }
  .c-item:hover::before {
    background: linear-gradient(135deg, #fbbf24, #d97706);
  }

  /* صورة المنتج */
  .c-item-img {
    width: 96px;
    height: 96px;
    border-radius: 16px;
    overflow: hidden;
    background: #f8fafc;
    display: grid;
    place-items: center;
    border: 1px solid #f1f5f9;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(15,23,42,0.06);
  }
  .c-item-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: .4s;
  }
  .c-item:hover .c-item-img img { transform: scale(1.08); }
  .c-item-img .ph { font-size: 38px; color: #cbd5e1; }

  /* جسم المنتج */
  .c-item-body {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
  }
  .c-item-name {
    font-size: 14px;
    font-weight: 900;
    color: var(--c-text);
    line-height: 1.4;
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: .2s;
  }
  .c-item-name:hover { color: var(--c-primary); }

  .c-item-tags {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
  }
  .c-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    font-size: 11px;
    font-weight: 800;
    color: #475569;
  }
  .c-tag.color {
    background: #fef3c7;
    border-color: #fde68a;
    color: #b45309;
  }
  .c-tag-size {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8;
  }
  .c-tag-dot {
    width: 11px;
    height: 11px;
    border-radius: 50%;
    border: 1.5px solid rgba(0,0,0,0.15);
    flex-shrink: 0;
    box-shadow: 0 0 0 1px rgba(255,255,255,0.8);
  }

  .c-item-price {
    display: flex;
    align-items: baseline;
    gap: 6px;
    font-size: 12px;
    color: #94a3b8;
    font-weight: 700;
  }
  .c-item-price strong {
    font-size: 16px;
    color: var(--c-primary);
    font-weight: 900;
  }

  /* إجراءات المنتج */
  .c-item-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: flex-end;
    flex-shrink: 0;
  }
  .c-item-total {
    font-size: 11px;
    font-weight: 700;
    color: var(--c-muted);
    text-align: left;
    padding: 5px 10px;
    background: #f8fafc;
    border-radius: 9px;
  }
  .c-item-total strong {
    font-size: 14px;
    color: var(--c-text);
    font-weight: 900;
  }

  .c-qty {
    display: inline-flex;
    align-items: center;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 3px;
    box-shadow: 0 2px 6px rgba(15,23,42,0.05);
    transition: .2s;
  }
  .c-qty:hover { box-shadow: 0 4px 12px rgba(15,23,42,0.1); }
  .c-qty button {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    border: 0;
    background: #f8fafc;
    color: #475569;
    font-size: 16px;
    font-weight: 900;
    cursor: pointer;
    display: grid;
    place-items: center;
    transition: .2s;
    font-family: inherit;
  }
  .c-qty button:hover {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    transform: scale(1.05);
  }
  .c-qty button:active { transform: scale(0.95); }
  .c-qty input {
    width: 38px;
    text-align: center;
    border: 0;
    background: transparent;
    font-size: 15px;
    font-weight: 900;
    outline: none;
    color: var(--c-text);
    font-family: inherit;
  }

  .c-item-remove {
    width: 34px;
    height: 34px;
    border-radius: 11px;
    background: #fef2f2;
    border: 1.5px solid #fee2e2;
    color: #dc2626;
    cursor: pointer;
    display: grid;
    place-items: center;
    transition: .25s;
    font-size: 14px;
  }
  .c-item-remove:hover {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    border-color: #dc2626;
    color: #fff;
    transform: scale(1.08) rotate(5deg);
    box-shadow: 0 6px 16px rgba(220,38,38,0.35);
  }

  /* ═══════════════════════════════════════
     🧾 Summary Card — Premium
     ═══════════════════════════════════════ */
  .c-summary {
    background: #fff;
    border-radius: 26px;
    overflow: hidden;
    box-shadow: var(--c-shadow-lg);
    border: 1.5px solid rgba(245,158,11,0.2);
    position: relative;
    margin-bottom: 16px;
  }
  .c-summary::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 5px;
    background: linear-gradient(90deg, #f59e0b, #d97706, #fbbf24, #d97706, #f59e0b);
    background-size: 200% 100%;
    animation: cShine 3s linear infinite;
  }
  @keyframes cShine {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
  }

  .c-summary-head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px 22px 16px;
    background: linear-gradient(180deg, #fffbeb 0%, #ffffff 100%);
    border-bottom: 1px solid #fef3c7;
  }
  .c-summary-head-icon {
    width: 40px;
    height: 40px;
    border-radius: 14px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    display: grid;
    place-items: center;
    color: #fff;
    box-shadow: 0 6px 16px rgba(217,119,6,0.35);
    font-size: 18px;
  }
  .c-summary-head-title {
    font-size: 16px;
    font-weight: 900;
    color: var(--c-text);
    letter-spacing: -0.3px;
  }
  .c-summary-head-count {
    margin-right: auto;
    font-size: 11px;
    font-weight: 900;
    color: #92400e;
    background: #fef3c7;
    padding: 4px 10px;
    border-radius: 99px;
    border: 1px solid #fde68a;
  }

  .c-summary-body { padding: 18px 22px 22px; }

  .c-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    font-size: 13px;
    font-weight: 700;
    color: var(--c-muted);
    border-bottom: 1px dashed #f1f5f9;
  }
  .c-summary-row:last-of-type { border-bottom: 0; }
  .c-summary-row strong {
    color: var(--c-text);
    font-weight: 900;
    font-size: 14px;
  }
  .c-summary-row.free { color: #16a34a; }
  .c-summary-row.free strong { color: #16a34a; }

  .c-summary-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 22px;
    margin-top: 12px;
    border-radius: 20px;
    background: linear-gradient(135deg, #17202b 0%, #0f172a 100%);
    box-shadow:
      0 12px 28px -8px rgba(15,23,42,0.4),
      inset 0 1px 0 rgba(255,255,255,0.08);
    position: relative;
    overflow: hidden;
  }
  .c-summary-total::before {
    content: '';
    position: absolute;
    top: -40%; right: -20%;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(245,158,11,0.4) 0%, transparent 70%);
    pointer-events: none;
  }
  .c-summary-total::after {
    content: '';
    position: absolute;
    bottom: -60%; left: -10%;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(251,191,36,0.2) 0%, transparent 70%);
    pointer-events: none;
  }
  .c-summary-total-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 800;
    color: rgba(255,255,255,0.7);
    position: relative;
    z-index: 1;
  }
  .c-summary-total-value {
    font-size: 26px;
    font-weight: 900;
    color: #fbbf24;
    letter-spacing: -0.5px;
    text-shadow: 0 4px 12px rgba(251,191,36,0.3);
    position: relative;
    z-index: 1;
  }
  .c-summary-total-value small {
    font-size: 13px;
    color: rgba(255,255,255,0.6);
    font-weight: 700;
    margin-right: 6px;
  }

  /* ═══════════════════════════════════════
     🎯 Action Buttons
     ═══════════════════════════════════════ */
  .c-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }
  .c-btn-primary {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 18px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #fff;
    text-decoration: none;
    border: 0;
    border-radius: 20px;
    font-size: 16px;
    font-weight: 900;
    font-family: inherit;
    cursor: pointer;
    box-shadow: var(--c-shadow-primary);
    transition: .3s cubic-bezier(.2,.9,.3,1.1);
    position: relative;
    overflow: hidden;
    letter-spacing: -0.2px;
  }
  .c-btn-primary::before {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.35), transparent);
    transition: left .6s;
  }
  .c-btn-primary:hover::before { left: 100%; }
  .c-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 40px -8px rgba(217,119,6,0.45), 0 24px 56px -12px rgba(217,119,6,0.3);
  }
  .c-btn-primary:active { transform: translateY(-1px); }

  .c-btn-secondary {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 15px;
    background: #ffffff;
    color: #475569;
    text-decoration: none;
    border: 1.5px solid #e2e8f0;
    border-radius: 18px;
    font-size: 13px;
    font-weight: 900;
    transition: .25s;
    box-shadow: var(--c-shadow-sm);
  }
  .c-btn-secondary:hover {
    border-color: #fbbf24;
    color: #d97706;
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px -6px rgba(217,119,6,0.2);
  }

  /* ═══════════════════════════════════════
     🍞 Toast
     ═══════════════════════════════════════ */
  .c-toast {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translate(-50%, 100px);
    background: #17202b;
    color: #fff;
    padding: 16px 26px;
    border-radius: 16px;
    font-size: 14px;
    font-weight: 800;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    transition: transform .4s cubic-bezier(.34,1.56,.64,1);
    z-index: 999;
    max-width: 90vw;
    border: 1px solid rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .c-toast.show { transform: translate(-50%, 0); }
  .c-toast.success {
    background: linear-gradient(135deg, #16a34a, #15803d);
    box-shadow: 0 20px 50px rgba(22,163,74,0.4);
  }
  .c-toast.error {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    box-shadow: 0 20px 50px rgba(220,38,38,0.4);
  }

  /* ═══════════════════════════════════════
     📱 Responsive
     ═══════════════════════════════════════ */
  @media (max-width: 640px) {
    .cart-wrap { padding: 12px; }
    .c-header { padding: 12px; }
    .c-header-icon { width: 36px; height: 36px; font-size: 18px; }
    .c-header-text-main { font-size: 14px; }

    .c-item {
      grid-template-columns: 80px 1fr;
      padding: 12px;
      gap: 12px;
      border-radius: 18px;
    }
    .c-item-img { width: 80px; height: 80px; border-radius: 14px; }
    .c-item-name { font-size: 13px; }
    .c-item-actions {
      grid-column: 1 / -1;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
      margin-top: 6px;
      padding-top: 12px;
      border-top: 1px dashed #f1f5f9;
    }
    .c-item-total { order: 1; }
    .c-qty { order: 2; }
    .c-item-remove { order: 3; width: 38px; height: 38px; }

    .c-summary-head { padding: 16px 18px 12px; }
    .c-summary-body { padding: 14px 18px 18px; }
    .c-summary-total { padding: 16px 18px; }
    .c-summary-total-value { font-size: 22px; }
    .c-btn-primary { padding: 16px; font-size: 15px; }
  }

  @media (max-width: 400px) {
    .c-header-text-sub { display: none; }
    .c-back span { display: none; }
    .c-back { padding: 10px; }
  }
</style>

<div class="cart-wrap">

  {{-- ═══ Header ═══ --}}
  <header class="c-header">
    <a href="/shop" class="c-back">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
      <span>رجوع</span>
    </a>

    <div class="c-header-title">
      <div class="c-header-icon">🛒</div>
      <div class="c-header-text">
        <div class="c-header-text-main">سلة التسوق</div>
        @if(isset($items) && count($items) > 0)
          <div class="c-header-text-sub">{{ count($items) }} {{ count($items) === 1 ? 'منتج' : 'منتجات' }}</div>
        @endif
      </div>
    </div>

    @if(isset($items) && count($items) > 0)
      <span class="c-header-count">{{ count($items) }}</span>
    @else
      <span style="width:60px;"></span>
    @endif
  </header>

  @if(!isset($items) || count($items) === 0)

    {{-- ═══ سلة فارغة ═══ --}}
    <div class="c-empty">
      <div class="c-empty-icon">🛒</div>
      <div class="c-empty-title">سلتك فارغة</div>
      <div class="c-empty-desc">ابدأ التسوق وأضف منتجاتك المفضلة<br>واستمتع بتجربة تسوق مميزة</div>
      <a href="/shop" class="c-empty-btn">
        <span>🛍️</span>
        <span>تصفّح المتجر</span>
      </a>
    </div>

  @else

    {{-- 🚚 شريط الشحن --}}
    @php $freeShipThreshold = 50000; @endphp
    @if($total >= $freeShipThreshold)
      <div class="c-ship-alert ok">
        <div class="c-ship-alert-icon">🎉</div>
        <div class="c-ship-alert-body">مبروك! حصلت على <strong>شحن مجاني</strong> على هذا الطلب</div>
      </div>
    @else
      <div class="c-ship-alert warn">
        <div class="c-ship-alert-icon">🚚</div>
        <div class="c-ship-alert-body">أضف <strong>{{ number_format($freeShipThreshold - $total) }} ر.ي</strong> للحصول على شحن مجاني</div>
      </div>
    @endif

    {{-- ═══ المنتجات ═══ --}}
    <div class="c-items">
      @foreach($items as $item)
        @php
          $p = $item['product'];
          $img = $p->image;
          $isExt = $img && (str_starts_with($img, 'http') || str_starts_with($img, 'https'));
          $imgUrl = $img ? ($isExt ? $img : (str_starts_with($img, '/') ? $img : \Storage::url($img))) : null;
          $color = $item['color'] ?? null;
          $size  = $item['size'] ?? null;
          $hex   = $item['variant']->color_hex ?? null;
          $key   = $item['key'];
        @endphp
        <div class="c-item" data-key="{{ $key }}">
          {{-- صورة --}}
          <div class="c-item-img">
            @if($imgUrl)
              <img src="{{ $imgUrl }}" alt="{{ $p->name }}" loading="lazy">
            @else
              <span class="ph">📦</span>
            @endif
          </div>

          {{-- التفاصيل --}}
          <div class="c-item-body">
            <a href="/product/{{ $p->id }}" class="c-item-name">{{ $p->name }}</a>

            @if($color || $size)
              <div class="c-item-tags">
                @if($color)
                  <span class="c-tag color">
                    @if($hex)
                      <span class="c-tag-dot" style="background:{{ $hex }}"></span>
                    @endif
                    {{ $color }}
                  </span>
                @endif
                @if($size)
                  <span class="c-tag c-tag-size">📏 {{ $size }}</span>
                @endif
              </div>
            @endif

            <div class="c-item-price">
              <strong>{{ number_format($item['price']) }}</strong>
              <span>ر.ي × {{ $item['qty'] }}</span>
            </div>
          </div>

          {{-- الإجراءات --}}
          <div class="c-item-actions">
            <div class="c-item-total">
              <strong>{{ number_format($item['subtotal']) }}</strong> ر.ي
            </div>

            <div class="c-qty">
              <button type="button" onclick="updateQty('{{ $key }}', -1)" aria-label="إنقاص">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </button>
              <input type="text" value="{{ $item['qty'] }}" readonly aria-label="الكمية">
              <button type="button" onclick="updateQty('{{ $key }}', 1)" aria-label="زيادة">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </button>
            </div>

            <button type="button" class="c-item-remove" onclick="removeItem('{{ $key }}')" title="حذف">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
          </div>
        </div>
      @endforeach
    </div>

    {{-- ═══ ملخص الطلب ═══ --}}
    <div class="c-summary">
      <div class="c-summary-head">
        <div class="c-summary-head-icon">🧾</div>
        <div class="c-summary-head-title">ملخص الطلب</div>
        <div class="c-summary-head-count">{{ count($items) }} عنصر</div>
      </div>

      <div class="c-summary-body">
        <div class="c-summary-row">
          <span>المجموع الفرعي</span>
          <strong>{{ number_format($total) }} ر.ي</strong>
        </div>
        <div class="c-summary-row {{ $total >= $freeShipThreshold ? 'free' : '' }}">
          <span>الشحن</span>
          <strong>{{ $total >= $freeShipThreshold ? '🎉 مجاني' : 'يُحدد عند الدفع' }}</strong>
        </div>

        <div class="c-summary-total">
          <div class="c-summary-total-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            <span>الإجمالي</span>
          </div>
          <div class="c-summary-total-value">
            <small>ر.ي</small>{{ number_format($total) }}
          </div>
        </div>
      </div>
    </div>

    {{-- ═══ الأزرار ═══ --}}
    <div class="c-actions">
      <a href="/checkout" class="c-btn-primary">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <span>إتمام الطلب</span>
      </a>
      <a href="/shop" class="c-btn-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
        <span>متابعة التسوق</span>
      </a>
    </div>

  @endif

</div>

<div id="cToast" class="c-toast"></div>

<script>
  const CSRF = '{{ csrf_token() }}';

  function showToast(msg, type) {
    var t = document.getElementById('cToast');
    if (!t) return;
    t.textContent = msg;
    t.className = 'c-toast show ' + (type || '');
    clearTimeout(window._ct);
    window._ct = setTimeout(function() { t.classList.remove('show'); }, 2500);
  }

  async function updateQty(key, delta) {
    var row = document.querySelector('[data-key="' + key + '"]');
    if (!row) return;
    var input = row.querySelector('.c-qty input');
    var current = parseInt(input.value) || 1;
    var newQty = Math.max(1, current + delta);
    if (newQty === current) return;

    input.value = newQty;
    input.disabled = true;
    row.style.opacity = '0.7';

    try {
      var form = new FormData();
      form.append('_token', CSRF);
      form.append('qty', newQty);

      var resp = await fetch('/cart/update/' + encodeURIComponent(key), {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: form,
      });

      if (resp.ok) {
        window.location.reload();
      } else {
        showToast('تعذر تحديث الكمية', 'error');
        input.value = current;
        row.style.opacity = '1';
      }
    } catch (e) {
      showToast('خطأ في الاتصال', 'error');
      input.value = current;
      row.style.opacity = '1';
    } finally {
      input.disabled = false;
    }
  }

  async function removeItem(key) {
    if (!confirm('هل تريد حذف هذا المنتج من السلة؟')) return;

    var row = document.querySelector('[data-key="' + key + '"]');
    if (row) { row.style.opacity = '0.4'; row.style.pointerEvents = 'none'; row.style.transform = 'scale(0.98)'; }

    try {
      var form = new FormData();
      form.append('_token', CSRF);

      var resp = await fetch('/cart/remove/' + encodeURIComponent(key), {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: form,
      });

      if (resp.ok) {
        showToast('✅ تم حذف المنتج');
        setTimeout(function() { window.location.reload(); }, 500);
      } else {
        showToast('تعذر حذف المنتج', 'error');
        if (row) { row.style.opacity = '1'; row.style.pointerEvents = 'auto'; row.style.transform = ''; }
      }
    } catch (e) {
      showToast('خطأ في الاتصال', 'error');
      if (row) { row.style.opacity = '1'; row.style.pointerEvents = 'auto'; row.style.transform = ''; }
    }
  }
</script>
</body>
</html>

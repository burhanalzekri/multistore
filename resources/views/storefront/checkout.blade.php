<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>إتمام الطلب</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/app.css">
<style>body { font-family: 'Cairo', sans-serif; }
    /* ═══ ✨ ملخص الطلب الأنيق ═══ */
    .co-summary {
      position: relative;
      background: #ffffff;
      border-radius: 24px;
      overflow: hidden;
      box-shadow:
        0 1px 3px rgba(15, 23, 42, 0.04),
        0 12px 32px -8px rgba(217, 119, 6, 0.18),
        0 32px 64px -16px rgba(15, 23, 42, 0.12);
      border: 1px solid rgba(245, 158, 11, 0.15);
    }
    .co-summary::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 4px;
      background: linear-gradient(90deg, #f59e0b 0%, #d97706 50%, #f59e0b 100%);
    }

    .co-summary-head {
      display: flex; align-items: center; justify-content: space-between;
      padding: 18px 22px 14px;
      background: linear-gradient(180deg, #fffbeb 0%, #ffffff 100%);
    }
    .co-summary-title {
      display: flex; align-items: center; gap: 10px;
      font-weight: 900; font-size: 15px;
      color: #17202b;
    }
    .co-summary-title .icon-wrap {
      width: 34px; height: 34px;
      border-radius: 12px;
      background: linear-gradient(135deg, #f59e0b, #d97706);
      display: grid; place-items: center;
      color: #fff;
      box-shadow: 0 4px 12px rgba(217, 119, 6, 0.35);
    }
    .co-summary-count {
      font-size: 11px; font-weight: 900;
      padding: 5px 12px;
      background: #fef3c7;
      color: #b45309;
      border-radius: 99px;
      border: 1px solid #fde68a;
    }

    .co-items { padding: 6px 22px 0; }
    .co-item {
      display: flex; gap: 12px; align-items: flex-start;
      padding: 12px 0;
      border-bottom: 1px dashed #f1f5f9;
    }
    .co-item:last-of-type { border-bottom: 0; }
    .co-item-thumb {
      width: 56px; height: 56px;
      border-radius: 14px;
      overflow: hidden;
      flex-shrink: 0;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      display: grid; place-items: center;
    }
    .co-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .co-item-thumb .ph {
      font-size: 22px; color: #cbd5e1;
    }
    .co-item-body { flex: 1; min-width: 0; }
    .co-item-name {
      font-weight: 900; font-size: 13px; color: #17202b;
      line-height: 1.5;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .co-item-meta {
      display: flex; gap: 8px; flex-wrap: wrap;
      margin-top: 6px;
      font-size: 11px; font-weight: 800;
    }
    .co-item-tag {
      display: inline-flex; align-items: center; gap: 4px;
      padding: 3px 8px;
      border-radius: 8px;
      background: #f1f5f9;
      color: #475569;
    }
    .co-item-tag.color {
      background: #fef3c7;
      color: #b45309;
    }
    .co-item-side {
      text-align: left;
      flex-shrink: 0;
    }
    .co-item-qty {
      font-size: 11px; font-weight: 900;
      color: #94a3b8;
      margin-bottom: 4px;
    }
    .co-item-price {
      font-size: 14px; font-weight: 900;
      color: #d97706;
    }

    /* Coupon */
    .co-coupon {
      margin: 14px 22px 0;
      padding: 14px;
      background: #f8fafc;
      border: 1.5px dashed #cbd5e1;
      border-radius: 14px;
    }
    .co-coupon-label {
      display: flex; align-items: center; gap: 6px;
      font-size: 12px; font-weight: 900;
      color: #334155;
      margin-bottom: 8px;
    }
    .co-coupon-row {
      display: flex; gap: 8px;
    }
    .co-coupon input {
      flex: 1;
      padding: 10px 14px;
      border: 1.5px solid #e2e8f0;
      border-radius: 12px;
      font-size: 13px; font-weight: 800;
      text-transform: uppercase;
      outline: none;
      transition: .2s;
      background: #fff;
      font-family: inherit;
      color: #17202b;
    }
    .co-coupon input:focus {
      border-color: #f59e0b;
      box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.12);
    }
    .co-coupon-btn {
      padding: 10px 18px;
      background: linear-gradient(135deg, #17202b, #0f172a);
      color: #fff;
      border: 0;
      border-radius: 12px;
      font-size: 12px; font-weight: 900;
      cursor: pointer;
      font-family: inherit;
      white-space: nowrap;
      transition: .2s;
    }
    .co-coupon-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(15,23,42,0.25); }
    .co-coupon-msg {
      font-size: 11px; font-weight: 800;
      margin-top: 8px;
      padding: 6px 10px;
      border-radius: 8px;
      display: none;
    }
    .co-coupon-msg.ok { display: block; background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .co-coupon-msg.err { display: block; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    /* Totals */
    .co-totals {
      margin: 16px 22px 0;
      padding: 14px 0 0;
      border-top: 1px solid #f1f5f9;
    }
    .co-total-row {
      display: flex; justify-content: space-between;
      align-items: center;
      padding: 6px 0;
      font-size: 13px; font-weight: 700;
      color: #64748b;
    }
    .co-total-row strong {
      color: #17202b;
      font-weight: 900;
      font-size: 13px;
    }
    .co-total-row.discount { color: #16a34a; }
    .co-total-row.discount strong { color: #16a34a; }

    /* Final total box */
    .co-final {
      margin: 14px 22px 22px;
      padding: 16px 18px;
      border-radius: 18px;
      background: linear-gradient(135deg, #17202b 0%, #0f172a 100%);
      display: flex; justify-content: space-between; align-items: center;
      box-shadow:
        0 10px 24px -8px rgba(15, 23, 42, 0.35),
        inset 0 1px 0 rgba(255, 255, 255, 0.06);
      position: relative;
      overflow: hidden;
    }
    .co-final::before {
      content: '';
      position: absolute;
      top: -40%; right: -20%;
      width: 140px; height: 140px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(245,158,11,0.35) 0%, transparent 70%);
      pointer-events: none;
    }
    .co-final-label {
      display: flex; align-items: center; gap: 6px;
      font-size: 12px; font-weight: 800;
      color: rgba(255,255,255,0.7);
    }
    .co-final-value {
      font-size: 22px; font-weight: 900;
      color: #fbbf24;
      letter-spacing: -0.5px;
    }
    .co-final-value small {
      font-size: 12px; color: rgba(255,255,255,0.6);
      font-weight: 700;
      margin-right: 4px;
    }

    /* Desktop: sticky */
    @media (min-width: 900px) {
      .co-summary-wrap {
        position: sticky;
        top: 84px;
      }
    }

    /* Mobile tweaks */
    @media (max-width: 640px) {
      .co-item-thumb { width: 48px; height: 48px; }
      .co-item-name { font-size: 12px; }
      .co-final-value { font-size: 20px; }
    }


    /* ═══ ✨ بطاقة موحّدة أنيقة ═══ */
    .co-card {
      position: relative;
      background: #ffffff;
      border-radius: 24px;
      overflow: hidden;
      box-shadow:
        0 1px 3px rgba(15, 23, 42, 0.04),
        0 12px 32px -8px rgba(15, 23, 42, 0.12);
      border: 1px solid #eef2f6;
    }
    .co-card + .co-card { margin-top: 16px; }

    .co-card-head {
      display: flex; align-items: center; justify-content: space-between;
      padding: 18px 22px 14px;
      background: linear-gradient(180deg, #fafbfc 0%, #ffffff 100%);
      border-bottom: 1px solid #f1f5f9;
    }
    .co-card-title {
      display: flex; align-items: center; gap: 10px;
      font-weight: 900; font-size: 15px;
      color: #17202b;
    }
    .co-card-icon {
      width: 34px; height: 34px;
      border-radius: 12px;
      display: grid; place-items: center;
      color: #fff;
    }
    .co-card-icon.blue {
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    .co-card-icon.green {
      background: linear-gradient(135deg, #10b981, #059669);
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .co-card-body { padding: 20px 22px 22px; }

    /* ═══ حقول الإدخال ═══ */
    .co-field { margin-bottom: 14px; }
    .co-field:last-child { margin-bottom: 0; }

    .co-label {
      display: flex; align-items: center; gap: 6px;
      font-size: 12px; font-weight: 900;
      color: #334155;
      margin-bottom: 8px;
    }
    .co-label .req { color: #dc2626; }

    .co-input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }
    .co-input-icon {
      position: absolute;
      right: 14px;
      width: 18px; height: 18px;
      color: #94a3b8;
      pointer-events: none;
      transition: .2s;
    }
    .co-input-wrap:focus-within .co-input-icon {
      color: #f59e0b;
    }

    .co-input, .co-textarea {
      width: 100%;
      padding: 12px 46px 12px 14px;
      border: 1.5px solid #e2e8f0;
      border-radius: 14px;
      font-size: 14px;
      font-weight: 700;
      color: #17202b;
      background: #fff;
      outline: none;
      transition: .2s;
      font-family: inherit;
    }
    .co-textarea {
      padding: 12px 46px 12px 14px;
      resize: vertical;
      min-height: 84px;
      line-height: 1.6;
    }
    .co-textarea.no-icon { padding-right: 14px; }

    .co-input::placeholder, .co-textarea::placeholder {
      color: #cbd5e1;
      font-weight: 600;
    }
    .co-input:focus, .co-textarea:focus {
      border-color: #f59e0b;
      box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.12);
    }

    /* ═══ طرق الدفع ═══ */
    .co-pay-list { display: flex; flex-direction: column; gap: 10px; }

    .co-pay {
      display: flex; align-items: center; gap: 14px;
      padding: 16px;
      border: 2px solid #e2e8f0;
      border-radius: 18px;
      cursor: pointer;
      transition: .25s;
      background: #fff;
      position: relative;
    }
    .co-pay:hover {
      border-color: #fbbf24;
      background: #fffdf5;
      transform: translateY(-1px);
    }
    .co-pay.active {
      border-color: #f59e0b;
      background: linear-gradient(135deg, #fff9e6, #fff7ed);
      box-shadow: 0 6px 20px -6px rgba(217, 119, 6, 0.25);
    }
    .co-pay input { display: none; }

    .co-pay-icon {
      width: 44px; height: 44px;
      border-radius: 14px;
      display: grid; place-items: center;
      flex-shrink: 0;
      font-size: 20px;
    }
    .co-pay-icon.amber { background: #fef3c7; color: #d97706; }
    .co-pay-icon.green { background: #d1fae5; color: #059669; }
    .co-pay-icon.blue  { background: #dbeafe; color: #1d4ed8; }

    .co-pay-body { flex: 1; min-width: 0; }
    .co-pay-title {
      font-weight: 900; font-size: 14px;
      color: #17202b;
      margin-bottom: 2px;
    }
    .co-pay-desc {
      font-size: 11px; font-weight: 700;
      color: #64748b;
      line-height: 1.5;
    }

    .co-pay-check {
      width: 22px; height: 22px;
      border-radius: 50%;
      border: 2px solid #cbd5e1;
      display: grid; place-items: center;
      flex-shrink: 0;
      transition: .2s;
      background: #fff;
    }
    .co-pay.active .co-pay-check {
      border-color: #f59e0b;
      background: #f59e0b;
    }
    .co-pay.active .co-pay-check::after {
      content: '✓';
      color: #fff;
      font-size: 13px;
      font-weight: 900;
    }

    /* Note box */
    .co-note {
      margin-top: 14px;
      padding: 14px 16px;
      border-radius: 14px;
      font-size: 12px;
      font-weight: 700;
      line-height: 1.6;
      display: flex; gap: 10px;
      animation: coFadeIn .3s ease;
    }
    .co-note.blue {
      background: linear-gradient(135deg, #eff6ff, #dbeafe);
      border: 1px solid #bfdbfe;
      color: #1e40af;
    }
    @keyframes coFadeIn {
      from { opacity: 0; transform: translateY(-4px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 640px) {
      .co-card-body { padding: 16px 18px 20px; }
      .co-pay { padding: 14px; gap: 12px; }
      .co-pay-icon { width: 40px; height: 40px; font-size: 18px; }
      .co-pay-title { font-size: 13px; }
    }


    /* ═══ 🎁 استبدال النقاط ═══ */
    .co-points {
      margin: 14px 22px 0;
      padding: 16px;
      background: linear-gradient(135deg, #fef3c7, #fde68a);
      border: 1.5px solid #fbbf24;
      border-radius: 16px;
      box-shadow: 0 8px 24px -8px rgba(245, 158, 11, 0.25);
    }
    .co-points-head {
      display: flex; align-items: center; gap: 12px;
    }
    .co-points-icon {
      width: 44px; height: 44px;
      border-radius: 14px;
      background: linear-gradient(135deg, #f59e0b, #d97706);
      display: grid; place-items: center;
      font-size: 22px;
      flex-shrink: 0;
      box-shadow: 0 4px 12px rgba(217, 119, 6, 0.35);
    }
    .co-points-info { flex: 1; min-width: 0; }
    .co-points-title {
      font-size: 14px; font-weight: 900; color: #92400e;
      margin-bottom: 3px;
    }
    .co-points-sub {
      font-size: 11px; color: #b45309; font-weight: 700;
    }
    .co-points-switch {
      position: relative;
      width: 48px; height: 28px;
      flex-shrink: 0;
      cursor: pointer;
    }
    .co-points-switch input { display: none; }
    .co-points-slider {
      position: absolute; inset: 0;
      background: #fcd34d;
      border-radius: 99px;
      transition: .25s;
    }
    .co-points-slider::after {
      content: '';
      position: absolute;
      top: 3px; right: 3px;
      width: 22px; height: 22px;
      border-radius: 50%;
      background: #fff;
      transition: .25s;
      box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }
    .co-points-switch input:checked + .co-points-slider {
      background: linear-gradient(135deg, #10b981, #059669);
    }
    .co-points-switch input:checked + .co-points-slider::after {
      transform: translateX(-20px);
    }
    .co-points-body {
      margin-top: 14px;
      padding-top: 14px;
      border-top: 1px dashed rgba(180, 83, 9, 0.25);
    }
    .co-points-row {
      display: flex; justify-content: space-between; align-items: center;
      font-size: 13px; font-weight: 800; color: #92400e;
      margin-bottom: 10px;
    }
    .co-points-row strong {
      font-size: 16px; color: #d97706;
      font-variant-numeric: tabular-nums;
    }
    #pointsRange {
      width: 100%;
      height: 6px;
      border-radius: 99px;
      background: rgba(180, 83, 9, 0.15);
      outline: none;
      -webkit-appearance: none;
      appearance: none;
      cursor: pointer;
    }
    #pointsRange::-webkit-slider-thumb {
      -webkit-appearance: none;
      width: 24px; height: 24px;
      border-radius: 50%;
      background: linear-gradient(135deg, #f59e0b, #d97706);
      border: 3px solid #fff;
      box-shadow: 0 4px 12px rgba(217, 119, 6, 0.4);
      cursor: pointer;
    }
    #pointsRange::-moz-range-thumb {
      width: 24px; height: 24px;
      border-radius: 50%;
      background: linear-gradient(135deg, #f59e0b, #d97706);
      border: 3px solid #fff;
      cursor: pointer;
    }
    .co-points-hint {
      display: flex; justify-content: space-between;
      font-size: 10px; color: #b45309; font-weight: 700;
      margin-top: 6px;
    }
    .co-points-value {
      margin-top: 12px;
      padding: 10px 14px;
      background: #fff;
      border-radius: 10px;
      font-size: 13px; font-weight: 800;
      color: #92400e;
      text-align: center;
    }
    .co-points-value strong {
      color: #d97706; font-size: 16px;
    }

</style>
</head>
<body class="bg-slate-50">

<header class="bg-white/80 backdrop-blur-lg shadow-sm sticky top-0 z-50">
  <div class="max-w-4xl mx-auto px-4 py-3 flex justify-between items-center">
    <a href="/cart" class="flex items-center gap-2 text-slate-600 hover:text-amber-600 transition font-bold text-sm">
      <i data-lucide="arrow-right" class="w-5 h-5"></i>
      <span>السلة</span>
    </a>
    <h1 class="font-black">💳 إتمام الطلب</h1>
  </div>
</header>

<!-- Progress Steps -->
<div class="max-w-4xl mx-auto px-4 pt-6">
  <div class="flex items-center justify-center gap-2 text-xs">
    <div class="flex items-center gap-2">
      <span class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-black">✓</span>
      <span class="hidden sm:inline font-bold text-green-600">السلة</span>
    </div>
    <div class="w-8 h-0.5 bg-amber-500"></div>
    <div class="flex items-center gap-2">
      <span class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center font-black animate-pulse">2</span>
      <span class="hidden sm:inline font-bold text-amber-600">البيانات</span>
    </div>
    <div class="w-8 h-0.5 bg-slate-200"></div>
    <div class="flex items-center gap-2">
      <span class="w-8 h-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-black">3</span>
      <span class="hidden sm:inline font-bold text-slate-400">تأكيد</span>
    </div>
  </div>
</div>

<form method="POST" action="/checkout" class="max-w-4xl mx-auto p-4 py-6 space-y-4">
  @csrf

  @if($errors->any())
  <div class="bg-red-100 text-red-700 p-4 rounded-2xl text-sm flex items-start gap-2 animate-slideUp">
    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
    <div>
      @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
    </div>
  </div>
  @endif

  <!-- ✨ Order Summary — Redesigned -->
<div class="co-summary-wrap">
  <div class="co-summary">

    {{-- رأس الملخص --}}
    <div class="co-summary-head">
      <div class="co-summary-title">
        <div class="icon-wrap">
          <i data-lucide="shopping-bag" class="w-4 h-4"></i>
        </div>
        <span>ملخص الطلب</span>
      </div>
      <span class="co-summary-count">{{ count($items) }} {{ count($items) == 1 ? 'منتج' : 'منتجات' }}</span>
    </div>

    {{-- عناصر الطلب --}}
    <div class="co-items">
      @forelse($items as $item)
        @php
          $_img = $item['product']->image;
          $_isExt = $_img && (str_starts_with($_img, 'http') || str_starts_with($_img, 'https'));
          $_imgUrl = $_img ? ($_isExt ? $_img : (str_starts_with($_img, '/') ? $_img : \Storage::url($_img))) : null;
          $_color = $item['color'] ?? null;
          $_size = $item['size'] ?? null;
          $_hex = $item['variant']->color_hex ?? null;
        @endphp
        <div class="co-item">
          <div class="co-item-thumb">
            @if($_imgUrl)
              <img src="{{ $_imgUrl }}" alt="{{ $item['product']->name }}">
            @else
              <span class="ph">📦</span>
            @endif
          </div>
          <div class="co-item-body">
            <div class="co-item-name">{{ $item['product']->name }}</div>
            @if($_color || $_size)
              <div class="co-item-meta">
                @if($_color)
                  <span class="co-item-tag color">
                    @if($_hex)
                      <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{ $_hex }};border:1px solid rgba(0,0,0,.15);vertical-align:middle;"></span>
                    @endif
                    {{ $_color }}
                  </span>
                @endif
                @if($_size)
                  <span class="co-item-tag">📏 {{ $_size }}</span>
                @endif
              </div>
            @endif
          </div>
          <div class="co-item-side">
            <div class="co-item-qty">× {{ $item['qty'] }}</div>
            <div class="co-item-price">{{ number_format($item['subtotal']) }} <small style="font-size:10px;opacity:.7;">ر.ي</small></div>
          </div>
        </div>
      @empty
        <div style="text-align:center;padding:30px;color:#94a3b8;font-weight:700;font-size:13px;">
          🛒 سلتك فارغة
        </div>
      @endforelse
    </div>

    {{-- كود الخصم --}}
    @if($items && count($items) > 0)
    <div class="co-coupon">
      <div class="co-coupon-label">
        <i data-lucide="ticket-percent" class="w-4 h-4 text-amber-600"></i>
        <span>كود الخصم</span>
      </div>
      <div class="co-coupon-row">
        <input type="text" id="couponInput" placeholder="مثال: WELCOME20" autocomplete="off">
        <button type="button" id="couponBtn" onclick="applyCoupon()" class="co-coupon-btn">تطبيق</button>
      </div>
      <div id="couponMsg" class="co-coupon-msg"></div>
      <input type="hidden" name="coupon_code" id="couponCodeInput" value="">
    </div>
    @php
  // 🎁 حساب رصيد النقاط للمستخدم
  $__loyalty = null;
  $__userPoints = 0;
  $__userPointsValue = 0;
  $__pointsStep = 100;
  $__pointsValue = 10;
  if (auth()->check()) {
      try {
          $__loyalty = app(\App\Services\Loyalty\LoyaltyService::class)
              ->getBalance(auth()->id(), $shop->id ?? null);
          $__userPoints = (int) ($__loyalty->balance ?? 0);
          $__userPointsValue = floor($__userPoints / $__pointsStep) * $__pointsValue;
      } catch (\Throwable $e) {
          $__userPoints = 0;
      }
  }
@endphp

{{-- 🎁 استبدال النقاط --}}
    @auth
      @if($__userPoints >= 100)
      <div class="co-points" id="pointsSection">
        <div class="co-points-head">
          <div class="co-points-icon">🎁</div>
          <div class="co-points-info">
            <div class="co-points-title">لديك {{ number_format($__userPoints) }} نقطة ولاء</div>
            <div class="co-points-sub">قيمتها {{ number_format($__userPointsValue) }} ر.ي · كل 100 نقطة = 10 ر.ي</div>
          </div>
          <label class="co-points-switch">
            <input type="checkbox" id="usePointsToggle" onchange="toggleUsePoints()">
            <span class="co-points-slider"></span>
          </label>
        </div>

        <div class="co-points-body" id="pointsBody" style="display:none;">
          <div class="co-points-row">
            <span>عدد النقاط المستبدلة:</span>
            <strong id="pointsUsedLabel">0</strong>
          </div>
          <input type="range"
            id="pointsRange"
            min="0"
            max="{{ floor($__userPoints / 100) * 100 }}"
            step="100"
            value="0"
            oninput="onPointsSliderChange()">
          <div class="co-points-hint">
            <span>0</span>
            <span>{{ number_format(floor($__userPoints / 100) * 100) }} نقطة</span>
          </div>
          <div class="co-points-value">
            💰 قيمة الخصم: <strong id="pointsValueLabel">0</strong> ر.ي
          </div>
          <input type="hidden" name="points_used" id="pointsUsedInput" value="0">
        </div>
      </div>
      @endif
    @endauth


    {{-- الإجماليات --}}
    <div class="co-totals">
      <div class="co-total-row">
        <span>المجموع الفرعي</span>
        <strong data-subtotal>{{ number_format($total) }} ر.ي</strong>
      </div>
      <div class="co-total-row" id="shippingRow" style="display:none;">
        <span>الشحن</span>
        <strong id="shippingAmount">0 ر.ي</strong>
      </div>
      <div class="co-total-row discount" id="discountRow" style="display:none;">
        <span>🎁 الخصم</span>
        <strong id="discountAmount">−0 ر.ي</strong>
      </div>
    </div>

    {{-- الإجمالي النهائي --}}
    <div class="co-final">
      <div class="co-final-label">
        <i data-lucide="wallet" class="w-4 h-4"></i>
        <span>الإجمالي النهائي</span>
      </div>
      <div class="co-final-value">
        <small>ر.ي</small><span id="finalTotal" data-final-total>{{ number_format($total) }}</span>
      </div>
    </div>
    @endif

  </div>
</div>

<!-- ✨ Customer Info — Redesigned -->
<div class="co-card">
  <div class="co-card-head">
    <div class="co-card-title">
      <div class="co-card-icon blue">
        <i data-lucide="user" class="w-4 h-4"></i>
      </div>
      <span>بياناتك</span>
    </div>
    <span style="font-size:11px;font-weight:800;color:#94a3b8;">الخطوة 2</span>
  </div>

  <div class="co-card-body">
    @auth
      @php $authUser = auth()->user(); @endphp
    @else
      @php $authUser = null; @endphp
    @endauth

    <div class="co-field">
      <label class="co-label">
        <span>الاسم الكامل</span>
        <span class="req">*</span>
      </label>
      <div class="co-input-wrap">
        <input type="text" name="customer_name"
          value="{{ old('customer_name', $authUser->name ?? '') }}"
          required
          placeholder="اكتب اسمك الكامل"
          class="co-input">
        <i data-lucide="user" class="co-input-icon"></i>
      </div>
    </div>

    <div class="co-field">
      <label class="co-label">
        <span>رقم الجوال</span>
        <span class="req">*</span>
      </label>
      <div class="co-input-wrap">
        <input type="tel" name="customer_phone"
          value="{{ old('customer_phone') }}"
          required
          placeholder="7XXXXXXXX"
          inputmode="tel"
          class="co-input">
        <i data-lucide="phone" class="co-input-icon"></i>
      </div>
    </div>

    <div class="co-field">
      <label class="co-label">
        <span>العنوان الكامل</span>
        <span class="req">*</span>
      </label>
      <div class="co-input-wrap">
        <textarea name="customer_address"
          required
          rows="3"
          placeholder="المدينة، الحي، الشارع، أقرب معلم..."
          class="co-textarea">{{ old('customer_address') }}</textarea>

      {{-- 🚚 منطقة التوصيل --}}
      <div>
        <label class="co-label">
          <i data-lucide="map-pin"></i>
          <span>منطقة التوصيل</span>
          <span class="co-req">*</span>
        </label>
        <select name="zone_id" id="zoneSelect" class="co-input" onchange="updateShipping()" style="cursor:pointer;">
          <option value="">اختر منطقتك...</option>
          @if(isset($zones))
            @foreach($zones as $zone)
              <option value="{{ $zone->id }}" 
                      data-fee="{{ $zone->fee }}"
                      data-max="{{ $zone->max_order_amount ?? '' }}">
                {{ $zone->name }}
                @if((float)$zone->fee === 0.0)
                  — 🎁 مجاني
                @else
                  — {{ number_format($zone->fee) }} ر.ي
                @endif
                @if($zone->eta)
                  ({{ $zone->eta }})
                @endif
              </option>
            @endforeach
          @endif
        </select>
        <div id="shippingDisplay" style="display:none;"></div>
      </div>


        <i data-lucide="map-pin" class="co-input-icon" style="top:14px;transform:none;"></i>
      </div>
    </div>

    <div class="co-field">
      <label class="co-label">
        <span>ملاحظات</span>
        <span style="font-size:10px;color:#94a3b8;font-weight:700;">(اختياري)</span>
      </label>
      <textarea name="notes"
        rows="2"
        placeholder="أي تفاصيل إضافية..."
        class="co-textarea no-icon">{{ old('notes') }}</textarea>
    </div>
  </div>
</div>

<!-- ✨ Payment — Redesigned -->
<div class="co-card">
  <div class="co-card-head">
    <div class="co-card-title">
      <div class="co-card-icon green">
        <i data-lucide="credit-card" class="w-4 h-4"></i>
      </div>
      <span>طريقة الدفع</span>
    </div>
    <span style="font-size:11px;font-weight:800;color:#94a3b8;">الخطوة 3</span>
  </div>

  <div class="co-card-body">
    <div class="co-pay-list">

      {{-- Wallet --}}
      <label class="co-pay active" onclick="selectPayment(this, 'wallet')">
        <input type="radio" name="payment_method" value="wallet" checked>
        <div class="co-pay-icon amber">💰</div>
        <div class="co-pay-body">
          <div class="co-pay-title">تحويل إلى محفظة</div>
          <div class="co-pay-desc">أسرع خيار — يتم التأكيد تلقائياً</div>
        </div>
        <div class="co-pay-check"></div>
      </label>

      {{-- COD --}}
      <label class="co-pay" onclick="selectPayment(this, 'cod')">
        <input type="radio" name="payment_method" value="cod">
        <div class="co-pay-icon green">💵</div>
        <div class="co-pay-body">
          <div class="co-pay-title">الدفع عند الاستلام</div>
          <div class="co-pay-desc">تدفع نقداً عند وصول الطلب</div>
        </div>
        <div class="co-pay-check"></div>
      </label>

      {{-- Bank --}}
      <label class="co-pay" onclick="selectPayment(this, 'bank')">
        <input type="radio" name="payment_method" value="bank">
        <div class="co-pay-icon blue">🏦</div>
        <div class="co-pay-body">
          <div class="co-pay-title">حوالة بنكية</div>
          <div class="co-pay-desc">أرسل المبلغ عبر شبكة الحوالات المحلية</div>
        </div>
        <div class="co-pay-check"></div>
      </label>

    </div>

    <div id="bankNote" class="co-note blue" style="display:none;">
      <span style="font-size:18px;">📋</span>
      <div>
        <div style="font-weight:900;margin-bottom:4px;">تفاصيل الحوالة</div>
        <div>سيتم إرسال بيانات الحساب البنكي عبر SMS بعد تأكيد الطلب.</div>
        <div style="font-size:11px;opacity:.8;margin-top:4px;">احتفظ برقم الطلب لمتابعة التحويل.</div>
      </div>
    </div>
  </div>
</div>

<script>
  function selectPayment(el, method) {
    document.querySelectorAll('.co-pay').forEach(p => p.classList.remove('active'));
    el.classList.add('active');
    el.querySelector('input[type="radio"]').checked = true;
    const note = document.getElementById('bankNote');
    if (note) note.style.display = (method === 'bank') ? 'flex' : 'none';
  }
</script>

<button type="submit" class="w-full py-4 bg-gradient-to-l from-amber-500 to-orange-500 text-white font-black rounded-2xl hover:shadow-2xl transition btn-primary flex items-center justify-center gap-2 text-lg animate-slideUp delay-300">
    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
    تأكيد الطلب
  </button>

<input type="hidden" name="shipping_fee" id="shippingFee" value="0">
</form>

<script>
window.updateShipping = function() {
    const select = document.getElementById("zoneSelect");
    if (!select) return;

    const zone = select.value;
    const totalText = document.querySelector("[data-order-total]")?.textContent || "0";
    const total = parseInt(totalText.replace(/[^\d]/g, "")) || 0;

    const feeInput = document.getElementById("shippingFee");
    let feeEl = document.getElementById("shippingDisplay");
    if (!feeEl) {
        feeEl = document.createElement("div");
        feeEl.id = "shippingDisplay";
        select.closest("div").after(feeEl);
    }

    if (!zone) {
        feeEl.style.display = "none";
        feeInput.value = 0;
        if (window.recalcFinalTotal) window.recalcFinalTotal();
        return;
    }

    feeEl.style.display = "block";
    feeEl.style.cssText = "padding:12px 16px;border-radius:12px;margin:12px 0;font-weight:800;font-size:14px;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;";
    feeEl.innerHTML = "&#9203; جاري حساب الشحن...";

    const csrfToken = document.querySelector("input[name=_token]")?.value || document.querySelector("meta[name=csrf-token]")?.content;

    fetch("/api/shipping-zones/calculate", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({ zone_id: parseInt(zone) || 0, total: total })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            feeEl.style.cssText = "padding:12px 16px;border-radius:12px;margin:12px 0;font-weight:800;font-size:14px;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;";
            feeEl.innerHTML = "&#9888; " + (data.message || "تعذر حساب الشحن");
            feeInput.value = 0;
            if (window.recalcFinalTotal) window.recalcFinalTotal();
            return;
        }

        if (data.requires_quote) {
            feeInput.value = 0;
            feeEl.style.cssText = "padding:14px 16px;border-radius:12px;margin:12px 0;font-weight:800;font-size:14px;background:linear-gradient(135deg,#fff7ed,#fed7aa);color:#9a3412;border:2px dashed #fdba74;";
            feeEl.innerHTML = "&#128222; <b>سيتواصل معك فريقنا</b><br><span style='font-size:12px;font-weight:700;opacity:.9;'>طلبك كبير — سنُحدد تكلفة الشحن الإضافية ونُبلغك قريباً</span>";
        }
        else if (data.free_shipping) {
            feeInput.value = 0;
            feeEl.style.cssText = "padding:12px 16px;border-radius:12px;margin:12px 0;font-weight:800;font-size:14px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);color:#15803d;border:1px solid #86efac;";
            feeEl.innerHTML = "&#127881; <b>شحن مجاني!</b> <span style='font-size:12px;opacity:.9;'>(" + (data.zone_name || '') + ")</span>";
        }
        else {
            feeInput.value = data.fee || 0;
            feeEl.style.cssText = "padding:12px 16px;border-radius:12px;margin:12px 0;font-weight:800;font-size:14px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#1e40af;border:1px solid #93c5fd;";
            feeEl.innerHTML = "&#128666; <b>الشحن:</b> " + (data.fee || 0).toLocaleString() + " ريال <span style='font-size:12px;opacity:.9;'>(" + (data.zone_name || '') + (data.eta ? " • " + data.eta : "") + ")</span>";
        }

        if (window.recalcFinalTotal) window.recalcFinalTotal();
    })
    .catch(err => {
        console.error("Shipping error:", err);
        feeEl.style.cssText = "padding:12px 16px;border-radius:12px;margin:12px 0;font-weight:800;font-size:14px;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;";
        feeEl.innerHTML = "&#9888; تعذر الاتصال بالخادم";
        feeInput.value = 0;
        if (window.recalcFinalTotal) window.recalcFinalTotal();
    });
};

document.addEventListener('DOMContentLoaded', function() {
    if (window.lucide) lucide.createIcons();
    const select = document.getElementById("zoneSelect");
    if (select && select.value) {
        setTimeout(window.updateShipping, 300);
    }
});
</script>


  {{-- 🔔 التنبيهات --}}
  @include('components.toast')


{{-- 🛒 السلة العائمة --}}
@include('components.floating-cart')


<script>
document.addEventListener('DOMContentLoaded', function() {
  var bankNote = document.getElementById('bankNote');
  var radios = document.querySelectorAll('input[name="payment_method"]');
  radios.forEach(function(r) {
    r.addEventListener('change', function() {
      if (bankNote) {
        bankNote.classList.toggle('hidden', this.value !== 'bank');
      }
    });
  });
});
</script>

{{-- ═══ 🎫 الكوبونات ═══ --}}
<script>
var appliedCoupon = null;
var originalTotal = {{ $total ?? 0 }};

function applyCoupon() {
  var input = document.getElementById('couponInput');
  var btn = document.getElementById('couponBtn');
  var msg = document.getElementById('couponMsg');
  var codeInput = document.getElementById('couponCodeInput');
  var finalTotalEl = document.getElementById('finalTotal');
  var discountRow = document.getElementById('discountRow');
  var discountAmount = document.getElementById('discountAmount');

  var code = (input.value || '').trim().toUpperCase();

  if (!code) {
    msg.className = 'co-coupon-msg err';
    msg.textContent = '⚠️ يرجى إدخال كود الكوبون';
    return;
  }

  btn.disabled = true;
  btn.textContent = '⏳ جاري التحقق...';

  var total = 0;
  if (finalTotalEl) {
    var raw = (finalTotalEl.getAttribute('data-original') || finalTotalEl.textContent || '0');
    total = parseFloat(String(raw).replace(/[^0-9.]/g, '')) || 0;
  }

  fetch('/coupon/validate', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json',
    },
    body: JSON.stringify({ code: code, total: total })
  })
  .then(function(r){ return r.json().then(function(d){ return {status: r.status, data: d}; }); })
  .then(function(resp) {
    var d = resp.data || {};

    if (d.ok) {
      msg.className = 'co-coupon-msg ok';
      msg.textContent = '✅ ' + (d.message || 'تم تطبيق الكوبون');

      if (codeInput) codeInput.value = d.code;

      // إظهار الخصم
      if (d.discount > 0) {
        if (discountRow) discountRow.style.display = 'flex';
        if (discountAmount) {
          discountAmount.textContent = '−' + Number(d.discount).toLocaleString('ar-EG') + ' ر.ي';
        }
        if (finalTotalEl) {
          finalTotalEl.textContent = Number(d.new_total || 0).toLocaleString('ar-EG');
        }
      }

      btn.textContent = '✓ مُطبَّق';
      btn.style.background = 'linear-gradient(135deg,#10b981,#059669)';
    } else {
      msg.className = 'co-coupon-msg err';
      msg.textContent = '⚠️ ' + (d.message || 'الكوبون غير صالح');

      if (codeInput) codeInput.value = '';
      if (discountRow) discountRow.style.display = 'none';

      btn.textContent = 'تطبيق';
      btn.style.background = '';
    }
  })
  .catch(function(e) {
    msg.className = 'co-coupon-msg err';
    msg.textContent = '⚠️ خطأ في الاتصال';
    btn.textContent = 'تطبيق';
  })
  .finally(function() {
    btn.disabled = false;
  });
}

// تفعيل Enter للكوبون
document.addEventListener('DOMContentLoaded', function() {
  var inp = document.getElementById('couponInput');
  if (inp) {
    inp.addEventListener('keydown', function(e){
      if (e.key === 'Enter') { e.preventDefault(); applyCoupon(); }
    });
  }
});

function removeCoupon() {
  appliedCoupon = null;
  var input = document.getElementById('couponInput');
  if (input) { input.value = ''; input.disabled = false; }
  var cci = document.getElementById('couponCodeInput');
  if (cci) cci.value = '';
  var dr = document.getElementById('discountRow');
  if (dr) dr.style.display = 'none';

  var btn = document.getElementById('couponBtn');
  if (btn) { btn.textContent = 'تطبيق'; btn.style.background = '#17202b'; btn.onclick = applyCoupon; }

  var ft = document.getElementById('finalTotal');
  if (ft) ft.textContent = number_format(originalTotal) + ' ريال';

  var msg = document.getElementById('couponMsg');
  if (msg) msg.style.display = 'none';
}

function showCouponMsg(text, type) {
  var msg = document.getElementById('couponMsg');
  if (!msg) return;
  msg.textContent = text;
  msg.style.display = 'block';
  msg.style.color = type === 'error' ? '#dc2626' : '#16a34a';
}

function number_format(n) {
  return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

document.addEventListener('DOMContentLoaded', function() {
  var input = document.getElementById('couponInput');
  if (input) {
    input.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') { e.preventDefault(); applyCoupon(); }
    });
  }
});
</script>

@include('components.floating-actions')
</body>
</html>

{{-- 🔍 التحقق الفوري + تنبيهات --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('form').forEach(function(form) {
    if (form.dataset.validationReady) return;
    form.dataset.validationReady = '1';
    form.setAttribute('novalidate', 'novalidate');

    form.querySelectorAll('input, textarea, select').forEach(function(field) {
      field.addEventListener('input', function() {
        this.style.borderColor = '';
        this.style.background = '';
      });
    });

    form.addEventListener('submit', function(e) {
      let errors = [];
      let firstInvalid = null;

      form.querySelectorAll('[required]').forEach(function(field) {
        field.style.borderColor = '';
        field.style.background = '';

        if (!field.value.trim()) {
          let label = field.name;
          let labelEl = field.closest('div')?.querySelector('label');
          if (labelEl) {
            label = labelEl.textContent.replace('*', '').replace('مطلوب', '').trim();
          }
          errors.push('📌 ' + label + ' مطلوب');
          field.style.borderColor = '#ef4444';
          field.style.background = '#fef2f2';
          if (!firstInvalid) firstInvalid = field;
        }
      });

      form.querySelectorAll('input[type="number"]').forEach(function(field) {
        if (field.value && isNaN(parseFloat(field.value))) {
          errors.push('🔢 ' + (field.name === 'price' ? 'السعر' : field.name) + ' يجب أن يكون رقماً');
          field.style.borderColor = '#ef4444';
          if (!firstInvalid) firstInvalid = field;
        }
      });

      let email = form.querySelector('input[type="email"]');
      if (email && email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        errors.push('📧 البريد الإلكتروني غير صحيح');
        email.style.borderColor = '#ef4444';
        if (!firstInvalid) firstInvalid = email;
      }

      if (errors.length > 0) {
        e.preventDefault();
        e.stopPropagation();
        errors.forEach(function(err, i) {
          setTimeout(function() {
            if (window.showToast) window.showToast(err, 'error', 5000);
            else alert(err);
          }, i * 200);
        });
        if (firstInvalid) {
          firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
          setTimeout(function() { firstInvalid.focus(); }, 300);
        }
        return false;
      }
    });
  });

  @if($errors->any())
    @foreach($errors->all() as $error)
      setTimeout(function() {
        if (window.showToast) window.showToast("{{ addslashes($error) }}", 'error', 6000);
      }, {{ $loop->index * 200 }});
    @endforeach
  @endif

  @if(session('success'))
    setTimeout(function() {
      if (window.showToast) window.showToast("{{ addslashes(session('success')) }}", 'success', 5000);
    }, 300);
  @endif

  @if(session('error'))
    setTimeout(function() {
      if (window.showToast) window.showToast("{{ addslashes(session('error')) }}", 'error', 6000);
    }, 300);
  @endif
});

// ═══ 🎁 استبدال النقاط ═══
window.toggleUsePoints = function() {
  var toggle = document.getElementById('usePointsToggle');
  var body = document.getElementById('pointsBody');
  var slider = document.getElementById('pointsRange');
  if (!toggle || !body) return;

  if (toggle.checked) {
    body.style.display = 'block';
    if (slider && parseInt(slider.value) === 0) {
      slider.value = slider.min === '0' ? 100 : slider.min;
      onPointsSliderChange();
    }
  } else {
    body.style.display = 'none';
    var input = document.getElementById('pointsUsedInput');
    var label = document.getElementById('pointsUsedLabel');
    var valueLabel = document.getElementById('pointsValueLabel');
    if (input) input.value = '0';
    if (label) label.textContent = '0';
    if (valueLabel) valueLabel.textContent = '0';
    recalcFinalTotal();
  }
};

window.onPointsSliderChange = function() {
  var slider = document.getElementById('pointsRange');
  var input = document.getElementById('pointsUsedInput');
  var label = document.getElementById('pointsUsedLabel');
  var valueLabel = document.getElementById('pointsValueLabel');
  if (!slider) return;

  var points = parseInt(slider.value) || 0;
  // كل 100 نقطة = 10 ر.ي
  var value = Math.floor(points / 100) * 10;

  if (input) input.value = points;
  if (label) label.textContent = points.toLocaleString('ar-EG');
  if (valueLabel) valueLabel.textContent = value.toLocaleString('ar-EG');

  recalcFinalTotal();
};

// حساب الإجمالي النهائي = (الأصلي - كوبون - نقاط)
window.recalcFinalTotal = function() {
  var finalEl = document.getElementById('finalTotal');
  var discountRow = document.getElementById('discountRow');
  var discountAmount = document.getElementById('discountAmount');
  var shippingRow = document.getElementById('shippingRow');
  if (!finalEl) return;

  // احفظ القيمة الأصلية أول مرة
  if (!finalEl.dataset.originalTotal) {
    var rawOriginal = (finalEl.getAttribute('data-original') || finalEl.textContent || '0');
    var origNum = parseFloat(String(rawOriginal).replace(/[^0-9.]/g, '')) || 0;
    finalEl.dataset.originalTotal = origNum;
  }

  var original = parseFloat(finalEl.dataset.originalTotal) || 0;
  var couponDiscount = 0;
  var pointsDiscount = 0;

  // خصم الكوبون
  if (discountRow && discountRow.style.display !== 'none' && discountAmount) {
    couponDiscount = parseFloat(String(discountAmount.textContent).replace(/[^0-9.]/g, '')) || 0;
  }

  // خصم النقاط
  var pts = 0;
  var ptsInput = document.getElementById('pointsUsedInput');
  if (ptsInput) pts = parseInt(ptsInput.value) || 0;
  pointsDiscount = Math.floor(pts / 100) * 10;

  var shipping = 0;
  if (shippingRow) {
    var shipStrong = shippingRow.querySelector('strong');
    if (shipStrong) {
      shipping = parseFloat(String(shipStrong.textContent).replace(/[^0-9.]/g, '')) || 0;
    }
  }

  var total = Math.max(0, original + shipping - couponDiscount - pointsDiscount);
  finalEl.textContent = total.toLocaleString('ar-EG');

  // إظهار سطر خصم النقاط
  updatePointsDiscountRow(pointsDiscount);
};

window.updatePointsDiscountRow = function(pointsDiscount) {
  var row = document.getElementById('pointsDiscountRow');
  var amount = document.getElementById('pointsDiscountAmount');
  if (!row) {
    // نضيف السطر ديناميكياً في place مناسب
    var totals = document.querySelector('.co-totals');
    if (totals) {
      row = document.createElement('div');
      row.className = 'co-total-row discount';
      row.id = 'pointsDiscountRow';
      row.style.display = 'none';
      row.innerHTML = '<span>🎁 خصم النقاط</span><strong id="pointsDiscountAmount">−0 ر.ي</strong>';
      totals.appendChild(row);
    }
  }
  if (row && amount) {
    if (pointsDiscount > 0) {
      row.style.display = 'flex';
      amount.textContent = '−' + pointsDiscount.toLocaleString('ar-EG') + ' ر.ي';
    } else {
      row.style.display = 'none';
    }
  }
};

</script>

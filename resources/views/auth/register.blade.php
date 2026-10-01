<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>سجّل متجرك — MultiStore</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; font-family: Cairo, sans-serif; }
  body { background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%); min-height: 100vh; margin: 0; padding: 20px; }
  .form-card {
    background: white;
    border-radius: 28px;
    padding: 32px;
    max-width: 620px;
    margin: 0 auto;
    position: relative;
    /* ✨ بروز + ظل متعدد الطبقات */
    box-shadow:
      0 1px 2px rgba(15,23,42,.04),
      0 4px 12px rgba(15,23,42,.05),
      0 16px 32px rgba(245,158,11,.12),
      0 32px 64px -12px rgba(245,158,11,.2);
    transition: all .5s cubic-bezier(.2,.9,.3,1.1);
    overflow: hidden;
    /* انعكاس ضوئي */
    border: 1px solid rgba(245,158,11,.08);
  }
  .form-card::before {
    content: "";
    position: absolute;
    top: -50%;
    left: -100%;
    width: 70%;
    height: 200%;
    background: linear-gradient(135deg, transparent, rgba(255,255,255,.6), transparent);
    transform: rotate(25deg);
    transition: left 1s cubic-bezier(.2,.9,.3,1.1);
    pointer-events: none;
    z-index: 1;
  }
  .form-card:hover::before {
    left: 150%;
  }
  .form-card:hover {
    box-shadow:
      0 2px 4px rgba(15,23,42,.05),
      0 8px 20px rgba(245,158,11,.15),
      0 24px 48px rgba(245,158,11,.2),
      0 48px 96px -20px rgba(245,158,11,.3);
    transform: translateY(-4px);
  }
  .form-card > * {
    position: relative;
    z-index: 2;
  }
  
  /* ✨ بروز ورفع للـ Input */
  .input-group input, .input-group textarea {
    transition: all .25s cubic-bezier(.2,.9,.3,1.1);
    box-shadow: 0 1px 2px rgba(15,23,42,.03);
  }
  .input-group input:hover, .input-group textarea:hover {
    border-color: rgba(245,158,11,.4);
    box-shadow: 0 2px 8px rgba(245,158,11,.1);
  }
  .input-group input:focus, .input-group textarea:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(245,158,11,.18);
  }
  
  /* ✨ بروز زر الإرسال */
  .btn-primary {
    box-shadow: 0 8px 20px rgba(245,158,11,.3), 0 2px 6px rgba(245,158,11,.2), inset 0 1px 0 rgba(255,255,255,.3);
  }
  .btn-primary:hover {
    box-shadow: 0 16px 36px rgba(245,158,11,.45), 0 4px 12px rgba(245,158,11,.3), inset 0 1px 0 rgba(255,255,255,.4);
  }
  .input-group { margin-bottom: 16px; }
  .input-group label { display: block; font-weight: 800; font-size: 13px; color: #374151; margin-bottom: 6px; }
  .input-group input, .input-group textarea {
    width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px;
    font-size: 14px; font-family: inherit; transition: all 0.2s; outline: none;
  }
  .input-group input:focus, .input-group textarea:focus {
    border-color: #f59e0b; box-shadow: 0 0 0 4px rgba(245,158,11,0.1);
  }
  .input-group input.is-invalid, .input-group textarea.is-invalid {
    border-color: #ef4444; background: #fef2f2;
  }
  .btn-primary {
    width: 100%; padding: 14px; background: linear-gradient(135deg,#fbbf24,#f97316);
    color: white; border: none; border-radius: 14px; font-weight: 900; font-size: 16px;
    cursor: pointer; font-family: inherit; transition: all 0.3s;
  }
  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(245,158,11,0.4); }
  .btn-primary:active { transform: translateY(0); }

  /* ═══ 🎁 Welcome Banner ═══ */
  .welcome-banner {
    background: linear-gradient(135deg, #fef3c7, #fed7aa);
    border: 2px dashed #f59e0b;
    border-radius: 20px;
    padding: 20px 24px;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
    animation: welcomeSlideIn 0.6s cubic-bezier(.2,.9,.3,1.1);
  }
  .welcome-banner::before {
    content: "";
    position: absolute;
    top: -30px;
    right: -30px;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(245,158,11,.15);
    pointer-events: none;
  }
  .welcome-title {
    font-size: 16px;
    font-weight: 900;
    color: #78350f;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-direction: row-reverse;
    justify-content: flex-end;
    direction: rtl;
    text-align: right;
  }
  .welcome-text {
    font-size: 13px;
    color: #92400e;
    line-height: 1.7;
    font-weight: 700;
    direction: rtl;
    text-align: right;
  }
  .welcome-close {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 3;
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: rgba(255,255,255,0.6);
    border: 0;
    cursor: pointer;
    display: grid;
    place-items: center;
    font-size: 14px;
    color: #92400e;
    transition: all .2s;
  }
  .welcome-close:hover {
    background: #fff;
    transform: scale(1.1);
  }
  @keyframes welcomeSlideIn {
    from { opacity: 0; transform: translateY(-20px) scale(.95); }
    to { opacity: 1; transform: none; }
  }

  /* ═══ ⚠️ Error Modal ═══ */
  .error-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 9998;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    visibility: hidden;
    transition: all .3s ease;
  }
  .error-modal-overlay.show {
    opacity: 1;
    visibility: visible;
  }
  .error-modal {
    background: #fff;
    border-radius: 24px;
    max-width: 440px;
    width: 100%;
    padding: 0;
    overflow: hidden;
    box-shadow: 0 40px 100px rgba(0,0,0,.4);
    transform: scale(.9) translateY(20px);
    transition: all .35s cubic-bezier(.2,.9,.3,1.1);
  }
  .error-modal-overlay.show .error-modal {
    transform: scale(1) translateY(0);
  }
  .error-modal-header {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    padding: 24px;
    color: #fff;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .error-modal-header::before {
    content: "";
    position: absolute;
    top: -50px;
    right: -50px;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: rgba(255,255,255,.15);
  }
  .error-modal-icon {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    background: rgba(255,255,255,.25);
    backdrop-filter: blur(10px);
    display: inline-grid;
    place-items: center;
    font-size: 30px;
    margin-bottom: 12px;
    position: relative;
    z-index: 1;
  }
  .error-modal-title {
    font-size: 18px;
    font-weight: 900;
    position: relative;
    z-index: 1;
  }
  .error-modal-sub {
    font-size: 12.5px;
    opacity: .9;
    margin-top: 4px;
    position: relative;
    z-index: 1;
  }
  .error-modal-body {
    padding: 24px;
    max-height: 50vh;
    overflow-y: auto;
  }
  .error-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  .error-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 14px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 12px;
    margin-bottom: 10px;
    font-size: 13.5px;
    font-weight: 700;
    color: #991b1b;
    animation: errorSlide 0.3s ease;
  }
  .error-item:last-child { margin-bottom: 0; }
  .error-item::before {
    content: "✕";
    width: 20px;
    height: 20px;
    border-radius: 6px;
    background: #ef4444;
    color: #fff;
    display: grid;
    place-items: center;
    font-size: 11px;
    font-weight: 900;
    flex-shrink: 0;
    margin-top: 1px;
  }
  @keyframes errorSlide {
    from { opacity: 0; transform: translateX(-10px); }
    to { opacity: 1; transform: none; }
  }
  .error-modal-footer {
    padding: 16px 24px 24px;
    text-align: center;
  }
  .error-modal-btn {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #f59e0b, #f97316);
    color: #fff;
    border: 0;
    border-radius: 12px;
    font-weight: 900;
    font-size: 14px;
    font-family: inherit;
    cursor: pointer;
    box-shadow: 0 8px 20px rgba(245,158,11,.35);
    transition: all .25s;
  }
  .error-modal-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(245,158,11,.5);
  }

  /* ═══ Success Modal ═══ */
  .error-modal.success .error-modal-header {
    background: linear-gradient(135deg, #10b981, #059669);
  }
  .error-modal.success .error-modal-icon::before { content: "✓"; }
  .error-modal.success .error-item {
    background: #f0fdf4;
    border-color: #86efac;
    color: #166534;
  }
  .error-modal.success .error-item::before {
    content: "✓";
    background: #10b981;
  }


  /* ═══════════════════════════════════════════
     ✨ تحسينات بصرية متقدمة
     ═══════════════════════════════════════════ */
  
  /* ✨ بروز ثلاثي الطبقات + انعكاس ضوئي */
  .form-card {
    transform-style: preserve-3d;
    perspective: 1200px;
  }
  
  .form-card::after {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 28px;
    background: linear-gradient(135deg, transparent 40%, rgba(255,255,255,.4) 50%, transparent 60%);
    opacity: 0;
    pointer-events: none;
    transition: opacity .5s;
    z-index: 3;
  }
  
  .form-card:hover::after {
    opacity: 1;
    animation: cardShine 1.2s ease-out;
  }
  
  @keyframes cardShine {
    from { background-position: -200% 0; }
    to { background-position: 200% 0; }
  }
  
  /* ✨ قسم رفع الشعار */
  .logo-upload-section {
    margin-bottom: 20px;
    padding: 18px;
    background: linear-gradient(135deg, #fef9f0, #fff7ed);
    border: 2px dashed #fdba74;
    border-radius: 18px;
    transition: all .35s cubic-bezier(.2,.9,.3,1.1);
    position: relative;
    overflow: hidden;
  }
  
  .logo-upload-section::before {
    content: "";
    position: absolute;
    top: -50%;
    left: -100%;
    width: 60%;
    height: 200%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.4), transparent);
    transform: rotate(25deg);
    transition: left .8s ease;
    pointer-events: none;
  }
  
  .logo-upload-section:hover::before {
    left: 150%;
  }
  
  .logo-upload-section:hover {
    border-color: #f59e0b;
    background: linear-gradient(135deg, #fff7ed, #ffedd5);
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(245,158,11,.2), 0 4px 8px rgba(15,23,42,.06);
  }
  
  .logo-upload-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(245,158,11,.15);
  }
  
  .logo-upload-title {
    font-size: 13.5px;
    font-weight: 900;
    color: #78350f;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  
  .logo-upload-hint {
    font-size: 11.5px;
    color: #a16207;
    font-weight: 700;
    margin-top: 2px;
  }
  
  .logo-upload-body {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
  }
  
  /* المعاينة */
  .logo-preview-box {
    width: 100px;
    height: 100px;
    border-radius: 22px;
    background: #fff;
    border: 3px solid #fef3c7;
    display: grid;
    place-items: center;
    overflow: hidden;
    flex-shrink: 0;
    transition: all .35s cubic-bezier(.2,.9,.3,1.1);
    box-shadow: 0 8px 24px rgba(245,158,11,.15), 0 2px 6px rgba(15,23,42,.06);
    position: relative;
  }
  
  .logo-preview-box:hover {
    transform: scale(1.05) rotate(-2deg);
    box-shadow: 0 12px 32px rgba(245,158,11,.3), 0 4px 12px rgba(15,23,42,.1);
    border-color: #f59e0b;
  }
  
  .logo-preview-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all .35s;
  }
  
  .logo-preview-empty {
    font-size: 36px;
    color: #cbd5e1;
    font-weight: 900;
  }
  
  /* زر الرفع */
  .logo-upload-trigger {
    flex: 1;
    min-width: 180px;
    padding: 14px 18px;
    background: #fff;
    border: 2px solid rgba(245,158,11,.25);
    border-radius: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: all .3s cubic-bezier(.2,.9,.3,1.1);
    font-family: inherit;
  }
  
  .logo-upload-trigger:hover {
    border-color: #f59e0b;
    background: #fffbeb;
    transform: translateX(-3px);
    box-shadow: 0 8px 20px rgba(245,158,11,.15);
  }
  
  .logo-upload-trigger:active {
    transform: translateX(-3px) scale(.98);
  }
  
  .logo-upload-trigger-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: linear-gradient(135deg, #f59e0b, #f97316);
    display: grid;
    place-items: center;
    color: #fff;
    font-size: 20px;
    flex-shrink: 0;
    box-shadow: 0 6px 16px rgba(245,158,11,.35), inset 0 1px 0 rgba(255,255,255,.3);
    transition: all .3s;
  }
  
  .logo-upload-trigger:hover .logo-upload-trigger-icon {
    transform: rotate(-8deg) scale(1.08);
    box-shadow: 0 10px 24px rgba(245,158,11,.5), inset 0 1px 0 rgba(255,255,255,.4);
  }
  
  .logo-upload-trigger-text {
    flex: 1;
    min-width: 0;
  }
  
  .logo-upload-trigger-title {
    font-size: 13.5px;
    font-weight: 900;
    color: #78350f;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  
  .logo-upload-trigger-sub {
    font-size: 11px;
    color: #a16207;
    font-weight: 700;
  }
  
  /* زر حذف */
  .logo-remove-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fca5a5;
    display: grid;
    place-items: center;
    cursor: pointer;
    transition: all .2s;
    flex-shrink: 0;
    font-size: 16px;
    font-family: inherit;
  }
  
  .logo-remove-btn:hover {
    background: #fee2e2;
    transform: scale(1.08);
    box-shadow: 0 6px 16px rgba(239,68,68,.2);
  }
  
  /* ═══ اختياري badge ═══ */
  .optional-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    background: #f3f4f6;
    color: #6b7280;
    border-radius: 99px;
    font-size: 10px;
    font-weight: 900;
    margin-right: 6px;
  }
  
  /* ═══ تحسين زر الإرسال ═══ */
  .btn-primary {
    position: relative;
    overflow: hidden;
  }
  
  .btn-primary::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.35), transparent);
    transition: left .8s ease;
  }
  
  .btn-primary:hover::before {
    left: 100%;
  }
  
  /* ═══ تحسين حقل الإدخال ═══ */
  .input-group input, .input-group textarea {
    position: relative;
    z-index: 1;
  }
  
  /* ═══ تحسين الـ Welcome Banner ═══ */
  .welcome-banner {
    animation: welcomeSlideIn .6s cubic-bezier(.2,.9,.3,1.1), welcomeGlow 3s ease-in-out infinite 1s;
  }
  
  @keyframes welcomeGlow {
    0%, 100% { box-shadow: 0 4px 16px rgba(245,158,11,.1); }
    50% { box-shadow: 0 8px 28px rgba(245,158,11,.25); }
  }
  
  /* ═══ الشعار في الرأس — تأثير عائم ═══ */
  .form-card > div:first-child > div:first-child {
    transition: all .4s cubic-bezier(.2,.9,.3,1.1);
  }
  
  .form-card > div:first-child > div:first-child:hover {
    transform: translateY(-4px) scale(1.05);
  }

</style>
</head>
<body>

<div style="text-align:center; margin-bottom:24px;">
  <div style="margin-bottom:12px;">
    <x-shop-logo :size="80" rounded="2xl" />
  </div>
  <h1 style="font-size:28px;font-weight:900;margin:16px 0 8px 0;">سجّل متجرك</h1>
  <p style="color:#6b7280;font-size:14px;margin:0;">ابدأ البيع خلال دقيقتين — مجاناً</p>
</div>

      {{-- 🎁 Welcome Banner --}}
      <div class="welcome-banner" id="welcomeBanner" dir="rtl">
        <button type="button" class="welcome-close" onclick="document.getElementById('welcomeBanner').style.display='none'" aria-label="إغلاق">✕</button>
        <div class="welcome-title" style="display:block;direction:rtl;text-align:right;font-size:16px;font-weight:900;color:#78350f;margin-bottom:6px;">
          🎉 مرحباً بك في MultiStore
        </div>
        <div class="welcome-text" style="direction:rtl;text-align:right;font-size:13px;color:#92400e;line-height:1.7;font-weight:700;">
          أنشئ متجرك الإلكتروني في أقل من دقيقة، وابدأ البيع اليوم.
          <strong style="color:#7c2d12;">14 يوم تجربة مجانية</strong> — بدون بطاقة بنكية.
        </div>
      </div>

      <div class="form-card">

  <form method="POST" action="/register" id="registerForm" novalidate>
    @csrf

    <h2 style="font-size:16px;font-weight:900;color:#1f2937;margin:0 0 14px 0;padding-bottom:8px;border-bottom:2px solid #fef3c7;">🏪 معلومات المتجر</h2>

    <div class="input-group">
      <label>اسم المتجر *</label>
      <input type="text" name="shop_name" value="{{ old('shop_name') }}" placeholder="مثال: متجر العسل الأصلي" required>
    </div>

    <div class="input-group">
      <label>وصف قصير للمتجر</label>
      <textarea name="description" rows="2" placeholder="اكتب وصفاً مختصراً لمتجرك (اختياري)">{{ old('description') }}</textarea>
    </div>


    {{-- 🎨 شعار المتجر (اختياري) --}}
    <div class="logo-upload-section" id="logoSection">
      <div class="logo-upload-header">
        <span style="font-size:20px;">🎨</span>
        <div style="flex:1;">
          <div class="logo-upload-title">
            شعار متجرك
            <span class="optional-badge">اختياري</span>
          </div>
          <div class="logo-upload-hint">ارفع شعارك — إن لم ترفع، سيظهر الشعار الافتراضي</div>
        </div>
      </div>

      <div class="logo-upload-body">
        <div class="logo-preview-box" id="logoPreviewBox">
          <span class="logo-preview-empty" id="logoPreviewEmpty">M</span>
          <img id="logoPreviewImg" style="display:none;" alt="معاينة الشعار">
        </div>

        <label for="logoInput" class="logo-upload-trigger">
          <input type="file" name="logo" id="logoInput" accept="image/png,image/jpeg,image/svg+xml,image/webp" style="display:none;" onchange="handleLogoSelect(this)">
          <div class="logo-upload-trigger-icon">📤</div>
          <div class="logo-upload-trigger-text">
            <div class="logo-upload-trigger-title" id="logoTargetText">اضغط لاختيار شعار</div>
            <div class="logo-upload-trigger-sub">PNG · JPG · SVG · WebP — حد أقصى 2MB</div>
          </div>
        </label>

        <button type="button" class="logo-remove-btn" id="logoRemoveBtn" onclick="removeLogo()" style="display:none;" title="حذف الشعار">🗑️</button>
      </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
      <div class="input-group">
        <label>رقم التواصل *</label>
        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="777123456" required>
      </div>

      <div class="input-group">
        <label>واتساب</label>
        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="777123456">
      </div>
    </div>

    <div class="input-group">
      <label>عنوان المتجر *</label>
      <input type="text" name="address" value="{{ old('address') }}" placeholder="شارع الحوبان، أمام السوق المركزي" required>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
      <div class="input-group">
        <label>المدينة *</label>
        <input type="text" name="city" value="{{ old('city') }}" placeholder="تعز" required>
      </div>

      <div class="input-group">
        <label>الدولة</label>
        <input type="text" name="country" value="{{ old('country', 'اليمن') }}">
      </div>
    </div>

    <h2 style="font-size:16px;font-weight:900;color:#1f2937;margin:24px 0 14px 0;padding-bottom:8px;border-bottom:2px solid #fef3c7;">👤 بيانات المدير</h2>

    <div class="input-group">
      <label>الاسم الكامل *</label>
      <input type="text" name="name" value="{{ old('name') }}" placeholder="مثال: أحمد محمد" required>
    </div>

    <div class="input-group">
      <label>البريد الإلكتروني *</label>
      <input type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required>
    </div>

    <div class="input-group">
      <label>كلمة المرور *</label>
      <input type="password" name="password" placeholder="6 أحرف على الأقل" required minlength="6">
    </div>

    <div class="input-group">
      <label>تأكيد كلمة المرور *</label>
      <input type="password" name="password_confirmation" placeholder="أعد إدخال كلمة المرور" required>
    </div>

    <button type="submit" class="btn-primary">🚀 إنشاء متجري الآن</button>

    <div style="text-align:center;margin-top:16px;font-size:13px;color:#6b7280;">
      لديك حساب؟ <a href="/login" style="color:#f59e0b;font-weight:900;text-decoration:none;">سجّل دخول</a>
    </div>

    <div style="margin-top:16px;padding:12px;background:#f0fdf4;border-radius:10px;font-size:12px;color:#166534;font-weight:700;text-align:center;">
      ✅ 14 يوم تجربة مجانية — بدون بطاقة ائتمانية
    </div>
  </form>
</div>

{{-- 🔔 التنبيهات المنبثقة --}}
@include('components.toast')

{{-- 🔍 التحقق من الحقول قبل الإرسال --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('registerForm');
  const fields = {
    shop_name: 'اسم المتجر',
    phone: 'رقم التواصل',
    address: 'عنوان المتجر',
    city: 'المدينة',
    name: 'الاسم الكامل',
    email: 'البريد الإلكتروني',
    password: 'كلمة المرور',
    password_confirmation: 'تأكيد كلمة المرور'
  };

  // إزالة تنسيق الخطأ عند الكتابة
  form.querySelectorAll('input, textarea').forEach(field => {
    field.addEventListener('input', function() {
      this.classList.remove('is-invalid');
    });
  });

  // التحقق قبل الإرسال
  form.addEventListener('submit', function(e) {
    let errors = [];
    let firstInvalid = null;

    // مسح التنسيقات القديمة
    form.querySelectorAll('input, textarea').forEach(f => f.classList.remove('is-invalid'));

    // فحص الحقول المطلوبة
    for (const [name, label] of Object.entries(fields)) {
      const field = form.querySelector(`[name="${name}"]`);
      if (!field) continue;

      if (!field.value.trim()) {
        errors.push(`${label} مطلوب`);
        field.classList.add('is-invalid');
        if (!firstInvalid) firstInvalid = field;
      }
    }

    // فحص البريد
    const email = form.querySelector('[name="email"]');
    if (email && email.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
      errors.push('البريد الإلكتروني غير صحيح');
      email.classList.add('is-invalid');
      if (!firstInvalid) firstInvalid = email;
    }

    // فحص كلمة المرور
    const password = form.querySelector('[name="password"]');
    const confirm = form.querySelector('[name="password_confirmation"]');
    if (password && password.value && password.value.length < 6) {
      errors.push('كلمة المرور يجب أن تكون 6 أحرف على الأقل');
      password.classList.add('is-invalid');
      if (!firstInvalid) firstInvalid = password;
    }
    if (password && confirm && password.value !== confirm.value) {
      errors.push('كلمتا المرور غير متطابقتين');
      confirm.classList.add('is-invalid');
      if (!firstInvalid) firstInvalid = confirm;
    }

    // فحص رقم الهاتف
    const phone = form.querySelector('[name="phone"]');
    if (phone && phone.value.trim() && !/^\d{7,15}$/.test(phone.value.replace(/\D/g, ''))) {
      errors.push('رقم التواصل غير صحيح (7-15 رقم)');
      phone.classList.add('is-invalid');
      if (!firstInvalid) firstInvalid = phone;
    }

    // إذا كان هناك أخطاء
    if (errors.length > 0) {
      e.preventDefault();

      // أظهر كل الأخطاء كتنبيهات
      errors.forEach((err, i) => {
        setTimeout(() => {
          if (window.showToast) {
            window.showToast(err, 'error', 5500);
          } else {
            // fallback إذا لم يعمل المكوّن
            alert(err);
          }
        }, i * 150);
      });

      // مرر لأول حقل خطأ
      if (firstInvalid) {
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => firstInvalid.focus(), 300);
      }

      return false;
    }

    // تعطيل الزر أثناء الإرسال
    const btn = form.querySelector('.btn-primary');
    btn.disabled = true;
    btn.innerHTML = '⏳ جاري الإنشاء...';
    btn.style.opacity = '0.7';
  });

  // عرض أخطاء الخادم (إن وُجدت) كنوافذ منبثقة
  @if($errors->any())
    @foreach($errors->all() as $error)
      setTimeout(() => {
        if (window.showToast) {
          window.showToast("{{ $error }}", 'error', 6000);
        }
      }, {{ $loop->index * 150 }});
    @endforeach
  @endif
});
</script>


<!-- ═══ ⚠️ Error Modal ═══ -->
<div class="error-modal-overlay" id="errorModalOverlay" onclick="if(event.target===this)closeErrorModal()">
  <div class="error-modal" id="errorModal">
    <div class="error-modal-header">
      <div class="error-modal-icon">⚠️</div>
      <div class="error-modal-title" id="errorModalTitle">يوجد خطأ في البيانات</div>
      <div class="error-modal-sub">يُرجى تصحيح ما يلي</div>
    </div>
    <div class="error-modal-body">
      <ul class="error-list" id="errorList"></ul>
    </div>
    <div class="error-modal-footer">
      <button type="button" class="error-modal-btn" onclick="closeErrorModal()">
        حسناً — سأصحح
      </button>
    </div>
  </div>
</div>

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

// ═══════════════════════════════════════════════════════
// 🎨 Logo Upload Handler
// ═══════════════════════════════════════════════════════
window.handleLogoSelect = function(input) {
    const file = input.files[0];
    if (!file) return;

    // تحقق من الحجم
    if (file.size > 2 * 1024 * 1024) {
        window.showErrorModal('حجم الصورة أكبر من 2MB — يرجى اختيار صورة أصغر');
        input.value = '';
        return;
    }

    // تحقق من النوع
    const allowed = ['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp'];
    if (!allowed.includes(file.type)) {
        window.showErrorModal('نوع الملف غير مدعوم — يرجى استخدام PNG أو JPG أو SVG أو WebP');
        input.value = '';
        return;
    }

    // عرض المعاينة
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('logoPreviewImg');
        const empty = document.getElementById('logoPreviewEmpty');
        const removeBtn = document.getElementById('logoRemoveBtn');
        const targetText = document.getElementById('logoTargetText');

        img.src = e.target.result;
        img.style.display = 'block';
        if (empty) empty.style.display = 'none';
        if (removeBtn) removeBtn.style.display = 'grid';
        if (targetText) targetText.textContent = '✅ ' + file.name.substring(0, 25);
    };
    reader.readAsDataURL(file);
};

window.removeLogo = function() {
    const input = document.getElementById('logoInput');
    const img = document.getElementById('logoPreviewImg');
    const empty = document.getElementById('logoPreviewEmpty');
    const removeBtn = document.getElementById('logoRemoveBtn');
    const targetText = document.getElementById('logoTargetText');

    input.value = '';
    img.src = '';
    img.style.display = 'none';
    if (empty) empty.style.display = 'block';
    if (removeBtn) removeBtn.style.display = 'none';
    if (targetText) targetText.textContent = 'اضغط لاختيار شعار';
};

</script>

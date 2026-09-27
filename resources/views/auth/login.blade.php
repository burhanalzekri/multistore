<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>تسجيل الدخول — MultiStore</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; font-family: Cairo, sans-serif; margin: 0; padding: 0; }

  body {
    background: #fef9f0;
    min-height: 100vh;
    color: #1f2937;
  }

  /* ═══════════════════════════════════════
     📱 جوال — تصميم عمودي
     ═══════════════════════════════════════ */
  .page-wrapper {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  .mobile-header {
    background: linear-gradient(135deg, #fbbf24 0%, #f97316 50%, #ea580c 100%);
    padding: 32px 20px 40px;
    color: white;
    position: relative;
    overflow: hidden;
  }
  .mobile-header::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
  }
  .mobile-header::after {
    content: '';
    position: absolute;
    bottom: -100px; left: -60px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
  }
  .mobile-header-content {
    position: relative;
    z-index: 1;
    max-width: 480px;
    margin: 0 auto;
    text-align: center;
  }

  .brand-logo {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(12px);
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    margin-bottom: 14px;
    border: 2px solid rgba(255,255,255,0.25);
  }

  .brand-title {
    font-size: 22px;
    font-weight: 900;
    margin-bottom: 6px;
    letter-spacing: -0.5px;
  }

  .brand-subtitle {
    font-size: 13px;
    opacity: 0.92;
    font-weight: 600;
  }

  /* ═══════════════════════════════════════
     📦 نموذج — الكل
     ═══════════════════════════════════════ */
  .form-section {
    flex: 1;
    padding: 20px;
    margin-top: -20px;
    position: relative;
    z-index: 2;
  }

  .form-inner {
    max-width: 480px;
    margin: 0 auto;
  }

  .card {
    background: white;
    border-radius: 22px;
    padding: 22px;
    box-shadow: 0 12px 40px rgba(245,158,11,0.12);
    border: 1px solid #f5f7fa;
  }

  /* 🎯 زر المتجر التجريبي */
  .demo-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, #8b5cf6, #6d28d9);
    padding: 14px 16px;
    border-radius: 16px;
    color: white;
    text-decoration: none;
    margin-bottom: 12px;
    box-shadow: 0 8px 24px rgba(139,92,246,0.28);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }
  .demo-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 32px rgba(139,92,246,0.45);
  }
  .demo-btn::before {
    content: '';
    position: absolute;
    top: -50px; left: -50px;
    width: 120px; height: 120px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    transition: all 0.5s;
  }
  .demo-btn:hover::before {
    top: -30px; left: -30px;
    width: 160px; height: 160px;
  }
  .demo-btn-content {
    display: flex;
    align-items: center;
    gap: 11px;
    position: relative;
    z-index: 1;
  }
  .demo-btn-icon {
    width: 42px;
    height: 42px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    backdrop-filter: blur(10px);
    flex-shrink: 0;
  }

  /* 🎁 بانر التسجيل */
  .promo-banner {
    background: linear-gradient(135deg, #10b981, #059669);
    border-radius: 16px;
    padding: 16px;
    color: white;
    margin-bottom: 12px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(16,185,129,0.28);
  }
  .promo-banner::before {
    content: '';
    position: absolute;
    top: -40px; left: -40px;
    width: 120px; height: 120px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
  }
  .promo-content { position: relative; z-index: 1; }

  /* 🔐 النموذج */
  .form-header {
    text-align: center;
    margin-bottom: 20px;
  }

  .form-header-icon {
    width: 54px;
    height: 54px;
    background: linear-gradient(135deg, #fbbf24, #f97316);
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(245,158,11,0.35);
    margin-bottom: 12px;
  }

  .form-title {
    font-size: 19px;
    font-weight: 900;
    margin-bottom: 4px;
    letter-spacing: -0.3px;
  }

  .form-subtitle {
    color: #6b7280;
    font-size: 12px;
    font-weight: 600;
  }

  .field {
    margin-bottom: 14px;
  }

  .field label {
    display: block;
    font-weight: 800;
    font-size: 12px;
    color: #374151;
    margin-bottom: 6px;
  }

  .field input {
    width: 100%;
    padding: 12px 14px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 14px;
    font-family: inherit;
    transition: all 0.2s;
    outline: none;
    background: #fafbfc;
  }

  .field input:focus {
    border-color: #f59e0b;
    background: white;
    box-shadow: 0 0 0 4px rgba(245,158,11,0.1);
  }

  .form-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }

  .checkbox-wrapper {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 12px;
    color: #6b7280;
    font-weight: 700;
    cursor: pointer;
  }

  .checkbox-wrapper input[type="checkbox"] {
    width: 15px;
    height: 15px;
    accent-color: #f59e0b;
    cursor: pointer;
  }

  .btn-submit {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg,#fbbf24,#f97316);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 900;
    font-size: 15px;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.3s;
    box-shadow: 0 6px 18px rgba(245,158,11,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 26px rgba(245,158,11,0.5);
  }

  .btn-submit:active {
    transform: translateY(0);
  }

  .register-link {
    text-align: center;
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid #f1f5f9;
    font-size: 12px;
    color: #6b7280;
  }

  .register-link a {
    color: #f59e0b;
    font-weight: 900;
    text-decoration: none;
    font-size: 13px;
  }

  .register-link a:hover {
    text-decoration: underline;
  }

  .footer {
    text-align: center;
    margin-top: 16px;
    padding: 12px;
    color: #9ca3af;
    font-size: 11px;
    font-weight: 700;
  }

  .footer strong {
    color: #92400e;
    display: block;
    margin-bottom: 3px;
    font-size: 12px;
  }

  /* ═══════════════════════════════════════
     💻 كمبيوتر — تصميم أفقي (Split)
     ═══════════════════════════════════════ */
  @media (min-width: 900px) {

    body {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      background: #fef9f0;
    }

    .page-wrapper {
      display: grid;
      grid-template-columns: 1fr 1fr;
      max-width: 1100px;
      width: 100%;
      min-height: 640px;
      background: white;
      border-radius: 28px;
      overflow: hidden;
      box-shadow: 0 30px 80px rgba(245,158,11,0.15);
    }

    /* الجانب الأيسر — Branding */
    .mobile-header {
      padding: 0;
      border-radius: 0;
      min-height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      order: 1; /* على اليسار بصرياً في RTL */
    }

    .mobile-header-content {
      text-align: center;
      padding: 40px;
    }

    .brand-logo {
      width: 76px;
      height: 76px;
      border-radius: 22px;
      margin-bottom: 20px;
    }

    .brand-title {
      font-size: 30px;
      margin-bottom: 10px;
    }

    .brand-subtitle {
      font-size: 15px;
      line-height: 1.7;
      max-width: 300px;
      margin: 0 auto;
    }

    /* مميزات إضافية للكمبيوتر */
    .desktop-features {
      display: block;
      margin-top: 32px;
      text-align: right;
      max-width: 300px;
      margin-right: auto;
      margin-left: auto;
    }

    .desktop-features li {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 0;
      font-size: 13px;
      opacity: 0.95;
      font-weight: 700;
    }

    .desktop-features li span:first-child {
      width: 24px;
      height: 24px;
      background: rgba(255,255,255,0.2);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      flex-shrink: 0;
    }

    /* الجانب الأيمن — النموذج */
    .form-section {
      padding: 40px;
      margin-top: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      order: 2;
      background: white;
      overflow-y: auto;
      max-height: 100vh;
    }

    .form-inner {
      max-width: 380px;
      width: 100%;
    }

    .card {
      padding: 0;
      box-shadow: none;
      border: none;
      border-radius: 0;
    }

    /* إخفاء اللوجو المكرر في الكمبيوتر — لأن الجانب الأيسر يحتويه */
    .form-header-icon {
      display: none;
    }

    .form-title {
      font-size: 22px;
      margin-bottom: 6px;
      text-align: right;
    }

    .form-subtitle {
      text-align: right;
      margin-bottom: 24px;
      font-size: 13px;
    }

    .form-header {
      text-align: right;
      margin-bottom: 24px;
    }

    .field input {
      padding: 13px 16px;
      font-size: 14px;
    }

    .btn-submit {
      padding: 14px;
      font-size: 15px;
    }

    /* إعادة ترتيب: النموذج يمين، Branding يسار */
    .form-section { order: 2; }
  }

  /* مميزات سطح المكتب — مخفية على الجوال */
  .desktop-features { display: none; }

  @media (min-width: 900px) {
    .desktop-features { display: block; }
  }

  /* حجم كبير للشاشات الكبيرة */
  @media (min-width: 1400px) {
    .page-wrapper {
      max-width: 1200px;
      min-height: 680px;
    }
  }

  /* ═══ 📞 قسم التواصل ═══ */
  .contact-section {
    margin-top: 14px;
    padding: 14px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 16px;
    border: 1px solid #e2e8f0;
  }

  .contact-title {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 900;
    color: #475569;
    margin-bottom: 10px;
  }

  .contact-buttons {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .contact-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 12px;
    text-decoration: none;
    color: white;
    transition: all 0.25s;
    position: relative;
    overflow: hidden;
  }

  .contact-btn:hover {
    transform: translateY(-2px);
  }

  .contact-whatsapp {
    background: linear-gradient(135deg, #25D366, #128C7E);
    box-shadow: 0 4px 14px rgba(37,211,102,0.3);
  }
  .contact-whatsapp:hover {
    box-shadow: 0 8px 22px rgba(37,211,102,0.5);
  }

  .contact-email {
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    box-shadow: 0 4px 14px rgba(239,68,68,0.3);
  }
  .contact-email:hover {
    box-shadow: 0 8px 22px rgba(239,68,68,0.5);
  }

  .contact-btn-icon {
    width: 36px;
    height: 36px;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
    backdrop-filter: blur(10px);
  }

  .contact-btn-info {
    flex: 1;
    min-width: 0;
  }

  .contact-btn-label {
    font-size: 10px;
    opacity: 0.9;
    font-weight: 700;
    margin-bottom: 1px;
  }

  .contact-btn-value {
    font-size: 12px;
    font-weight: 900;
    letter-spacing: 0.3px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  @media (min-width: 900px) {
    .contact-section {
      background: linear-gradient(135deg, #fef9f0, #fef3c7);
      border-color: #fcd34d;
    }
    .contact-title {
      color: #92400e;
    }
    .contact-buttons {
      flex-direction: row;
      gap: 8px;
    }
    .contact-btn {
      flex: 1;
      padding: 10px 10px;
    }
    .contact-btn-label {
      font-size: 9px;
    }
    .contact-btn-value {
      font-size: 10px;
    }
    .contact-btn-icon {
      width: 32px;
      height: 32px;
      font-size: 16px;
    }
  }


  /* ═══ 🎨 MS Brand Logo ═══ */
  .ms-logo {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    background: linear-gradient(135deg, #fbbf24 0%, #f97316 50%, #ea580c 100%);
    color: #fff;
    display: grid;
    place-items: center;
    font-weight: 900;
    font-size: 28px;
    letter-spacing: -1px;
    box-shadow: 0 8px 24px rgba(249, 115, 22, 0.35), inset 0 1px 0 rgba(255,255,255,0.3);
    text-shadow: 0 1px 2px rgba(0,0,0,0.15);
    font-family: Cairo, sans-serif;
  }

  @media (max-width: 680px) {
    .ms-logo { width: 50px; height: 50px; font-size: 24px; border-radius: 15px; }
  }

</style>
</head>
<body>

<div class="page-wrapper">

  {{-- ═══════════════════════════════════════
       🎨 الجانب الأيسر — Branding
       ═══════════════════════════════════════ --}}
  <div class="mobile-header">
    <div class="mobile-header-content">

      <div class="ms-logo">M</div>

      <h1 class="brand-title">MultiStore</h1>
      <p class="brand-subtitle">
        منصة متاجر إلكترونية متعددة<br>
        لأصحاب المتاجر اليمنيين 🇾🇪
      </p>

      {{-- مميزات سطح المكتب --}}
      <ul class="desktop-features">
        <li>
          <span>⚡</span>
          <span>إعداد متجرك في دقائق</span>
        </li>
        <li>
          <span>💳</span>
          <span>دفع تلقائي عبر SMS</span>
        </li>
        <li>
          <span>📊</span>
          <span>لوحة تحكم ذكية</span>
        </li>
        <li>
          <span>🎁</span>
          <span>شهر مجاني بدون بطاقة</span>
        </li>
      </ul>

    </div>
  </div>

  {{-- ═══════════════════════════════════════
       📝 الجانب الأيمن — النموذج
       ═══════════════════════════════════════ --}}
  <div class="form-section">
    <div class="form-inner">

      {{-- 🎯 المتجر التجريبي --}}
      <a href="/demo" class="demo-btn">
        <div class="demo-btn-content">
          <div class="demo-btn-icon">🎯</div>
          <div>
            <div style="font-weight:900;font-size:13px;margin-bottom:1px;">جرب المتجر التجريبي</div>
            <div style="font-size:10px;opacity:0.9;">شاهد الإمكانيات بدون تسجيل</div>
          </div>
        </div>
        <div style="position:relative;z-index:1;display:flex;align-items:center;gap:5px;">
          <span style="background:rgba(255,255,255,0.25);padding:3px 9px;border-radius:999px;font-size:9px;font-weight:900;">جديد</span>
          <span style="font-size:18px;">←</span>
        </div>
      </a>

      {{-- 🎁 إعلان التسجيل --}}
      <div class="promo-banner">
        <div class="promo-content">
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
            <div style="width:36px;height:36px;background:rgba(255,255,255,0.2);border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:18px;backdrop-filter:blur(10px);flex-shrink:0;">
              🎁
            </div>
            <div>
              <div style="font-weight:900;font-size:13px;margin-bottom:1px;">سجّل متجرك مجاناً!</div>
              <div style="font-size:10px;opacity:0.9;">شهر كامل تجربة مجانية</div>
            </div>
          </div>
          <div style="display:flex;gap:6px;margin-bottom:10px;font-size:10px;flex-wrap:wrap;">
            <span style="background:rgba(255,255,255,0.15);padding:4px 8px;border-radius:6px;font-weight:800;">✅ بدون رسوم</span>
            <span style="background:rgba(255,255,255,0.15);padding:4px 8px;border-radius:6px;font-weight:800;">⚡ تفعيل فوري</span>
            <span style="background:rgba(255,255,255,0.15);padding:4px 8px;border-radius:6px;font-weight:800;">💳 بدون بطاقة</span>
          </div>
          <a href="/register" style="display:block;text-align:center;background:white;color:#059669;padding:9px;border-radius:10px;text-decoration:none;font-weight:900;font-size:12px;">
            🚀 ابدأ الآن مجاناً ←
          </a>
        </div>
      </div>

      {{-- 🔐 نموذج تسجيل الدخول --}}
      <div class="card">

        <div class="form-header">
          <div class="form-header-icon">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l1.5-6h15L21 9"/>
              <path d="M3 9v11a1 1 0 001 1h16a1 1 0 001-1V9"/>
              <path d="M9 22V12h6v10"/>
            </svg>
          </div>
          <h2 class="form-title">تسجيل الدخول</h2>
          <p class="form-subtitle">لأصحاب المتاجر والمشرفين</p>
        </div>

                {{-- ═══ عرض الأخطاء ═══ --}}
        @if ($errors->any())
            <div style="
                background: linear-gradient(135deg, #fee2e2, #fecaca);
                border: 2px solid #f87171;
                color: #991b1b;
                padding: 14px 18px;
                border-radius: 14px;
                font-weight: 800;
                font-size: 13.5px;
                margin-bottom: 18px;
                display: flex;
                align-items: flex-start;
                gap: 10px;
                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
                animation: shakeError 0.4s ease;
            ">
                <span style="font-size: 22px; line-height: 1;">⚠️</span>
                <div style="flex: 1;">
                    @foreach ($errors->all() as $error)
                        <div style="margin-bottom: 4px;">{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ═══ رسالة النجاح ═══ --}}
        @if (session('status'))
            <div style="
                background: linear-gradient(135deg, #dcfce7, #bbf7d0);
                border: 2px solid #4ade80;
                color: #166534;
                padding: 14px 18px;
                border-radius: 14px;
                font-weight: 800;
                font-size: 13.5px;
                margin-bottom: 18px;
                display: flex;
                align-items: center;
                gap: 10px;
                box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
            ">
                <span style="font-size: 22px;">✓</span>
                <div>{{ session('status') }}</div>
            </div>
        @endif

      {{-- 🎮 بطاقة بيانات الدخول التجريبي --}}
      <div class="demo-credentials-card" style="
          background: linear-gradient(135deg, #eff6ff, #dbeafe);
          border: 2px dashed #60a5fa;
          border-radius: 16px;
          padding: 16px 18px;
          margin-bottom: 20px;
          position: relative;
          overflow: hidden;
      ">
          <div style="
              position: absolute;
              top: -30px;
              left: -30px;
              width: 100px;
              height: 100px;
              background: rgba(96, 165, 250, 0.15);
              border-radius: 50%;
          "></div>
          <div style="position: relative; z-index: 1;">
              <div style="
                  display: flex;
                  align-items: center;
                  gap: 8px;
                  margin-bottom: 12px;
                  font-weight: 900;
                  font-size: 14px;
                  color: #1e40af;
              ">
                  <span style="font-size: 18px;">🎮</span>
                  <span>تجربة سريعة — دخول تجريبي</span>
              </div>
              <div style="
                  background: #fff;
                  border-radius: 12px;
                  padding: 12px 14px;
                  display: flex;
                  flex-direction: column;
                  gap: 8px;
              ">
                  <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12.5px;">
                      <span style="color: #6b7280; font-weight: 700;">📧 البريد:</span>
                      <code style="
                          font-family: 'Courier New', monospace;
                          font-weight: 900;
                          color: #1e40af;
                          background: #eff6ff;
                          padding: 3px 8px;
                          border-radius: 6px;
                          font-size: 11.5px;
                      ">demo@multistore.ye</code>
                  </div>
                  <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12.5px;">
                      <span style="color: #6b7280; font-weight: 700;">🔑 كلمة المرور:</span>
                      <code style="
                          font-family: 'Courier New', monospace;
                          font-weight: 900;
                          color: #1e40af;
                          background: #eff6ff;
                          padding: 3px 8px;
                          border-radius: 6px;
                          font-size: 11.5px;
                      ">demo123</code>
                  </div>
              </div>
              <button type="button" onclick="fillDemoLogin()" style="
                  width: 100%;
                  margin-top: 10px;
                  padding: 10px;
                  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
                  color: #fff;
                  border: 0;
                  border-radius: 10px;
                  font-weight: 900;
                  font-size: 12.5px;
                  cursor: pointer;
                  font-family: inherit;
                  transition: all .2s;
                  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
              ">
                  🚀 ملء البيانات والدخول
              </button>
          </div>
      </div>

      {{-- JavaScript --}}
      <script>
      window.fillDemoLogin = function() {
          var emailInput = document.querySelector('input[name="email"]');
          var passInput = document.querySelector('input[name="password"]');
          
          if (emailInput && passInput) {
              emailInput.value = 'demo@multistore.ye';
              passInput.value = 'demo123';
              
              // تأثير بصري
              emailInput.style.transition = 'all 0.3s';
              emailInput.style.background = '#fef3c7';
              passInput.style.transition = 'all 0.3s';
              passInput.style.background = '#fef3c7';
              
              setTimeout(function() {
                  emailInput.style.background = '';
                  passInput.style.background = '';
              }, 1000);
              
              // التمرير للزر
              var submitBtn = document.querySelector('button[type="submit"]');
              if (submitBtn) {
                  submitBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                  submitBtn.style.animation = 'pulse 0.8s ease 2';
              }
          }
      };
      </script>

      <form method="POST" action="/login">
          @csrf

          <div class="field">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required>
          </div>

          <div class="field">
            <label>كلمة المرور</label>
            <input type="password" name="password" placeholder="••••••••" required>
          </div>

          <div class="form-options">
            <label class="checkbox-wrapper">
              <input type="checkbox" name="remember">
              تذكرني
            </label>
          </div>

          <button type="submit" class="btn-submit">
            <span>🔐</span>
            <span>دخول</span>
          </button>
        </form>

        <div class="register-link">
          ليس لديك متجر؟ <a href="/register">سجّل الآن مجاناً →</a>
        </div>

      </div>

      {{-- 📞 معلومات التواصل --}}
      <div class="contact-section">
        <div class="contact-title">
          <span>💬</span>
          <span>للاستفسار أو التواصل</span>
        </div>

        <div class="contact-buttons">
          <a href="https://wa.me/967775799926?text=مرحباً، أرغب بالاستفسار عن منصة MultiStore" target="_blank" class="contact-btn contact-whatsapp">
            <div class="contact-btn-icon">💬</div>
            <div class="contact-btn-info">
              <div class="contact-btn-label">واتساب</div>
              <div class="contact-btn-value" dir="ltr">+967 775 799 926</div>
            </div>
          </a>

          <a href="mailto:burhanalzekri77@gmail.com?subject=استفسار عن منصة MultiStore" class="contact-btn contact-email">
            <div class="contact-btn-icon">📧</div>
            <div class="contact-btn-info">
              <div class="contact-btn-label">البريد الإلكتروني</div>
              <div class="contact-btn-value" style="font-size:10px;font-family:monospace;">burhanalzekri77@gmail.com</div>
            </div>
          </a>
        </div>
      </div>

      <div class="footer">
        <strong>MultiStore © 2026</strong>
        منصة متاجر إلكترونية يمنية 🇾🇪
      </div>

    </div>
  </div>

</div>

{{-- 🔔 التنبيهات --}}
@include('components.toast')

</body>
</html>

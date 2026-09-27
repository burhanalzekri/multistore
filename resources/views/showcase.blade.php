<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="استعراض التحسينات في منصة MultiStore">
<title>استعراض التطوير — MultiStore</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/showcase.css') }}">
</head>
<body class="sc-showcase">

{{-- ═══════════ Navigation ═══════════ --}}
<nav class="sc-nav">
  <div class="sc-nav-inner">
    <a href="/" class="sc-brand">
      <span class="sc-brand-mark">M</span>
      <span>MultiStore</span>
    </a>
    <div class="sc-nav-links">
      <a href="#visual">التصميم</a>
      <a href="#security">الأمان</a>
      <a href="#dashboard">لوحة التحكم</a>
    </div>
    <a href="/demo-shop" class="sc-btn sc-btn-primary">تجربة المتجر ←</a>
  </div>
</nav>

{{-- ═══════════ Hero ═══════════ --}}
<section class="sc-hero">
  <div class="sc-container">
    <div class="sc-hero-grid">
      <div>
        <span class="sc-kicker"><i></i> جولة داخل النسخة الجديدة</span>
        <h1>واجهة <em>تليق</em> بمشروعك،<br>ونظام يعمل بثقة.</h1>
        <p class="sc-hero-copy">
          استعرض أبرز ما تم تطويره في MultiStore: من الهوية البصرية والتجربة العربية،
          إلى الأمان والصلاحيات وعزل بيانات المتاجر.
        </p>
        <div class="sc-hero-actions">
          <a href="/demo-shop" class="sc-btn sc-btn-gold">مشاهدة المتجر ←</a>
          <a href="#visual" class="sc-btn sc-btn-soft">استعراض التحسينات</a>
        </div>
        <div class="sc-metrics">
          <div class="sc-metric"><strong>RTL</strong><span>تجربة عربية</span></div>
          <div class="sc-metric"><strong>24/7</strong><span>مراقبة</span></div>
          <div class="sc-metric"><strong>4</strong><span>أدوار</span></div>
          <div class="sc-metric"><strong>100%</strong><span>متجاوب</span></div>
        </div>
      </div>

      <div class="sc-preview">
        <div class="sc-preview-top">
          <div class="sc-preview-dots"><i></i><i></i><i></i></div>
          <span class="sc-preview-live">مباشر</span>
        </div>
        <div class="sc-preview-body">
          <div class="sc-preview-header">
            <b>نظرة سريعة</b>
            <span>هذا الشهر</span>
          </div>
          <div class="sc-preview-cards">
            <div class="sc-preview-card"><small>المبيعات</small><strong>١٢,٤٥٠</strong></div>
            <div class="sc-preview-card"><small>الطلبات</small><strong>١٢٨</strong></div>
            <div class="sc-preview-card"><small>النمو</small><strong>+٢٤٪</strong></div>
          </div>
          <div class="sc-preview-chart">
            <svg viewBox="0 0 400 100" preserveAspectRatio="none">
              <defs>
                <linearGradient id="sc-gradient" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stop-color="#f59e0b" stop-opacity=".3"/>
                  <stop offset="100%" stop-color="#f59e0b" stop-opacity="0"/>
                </linearGradient>
              </defs>
              <path d="M0,82 C45,70 62,78 98,60 S150,69 184,45 S235,56 274,35 S330,40 400,9 L400,100 L0,100 Z" fill="url(#sc-gradient)"/>
              <path d="M0,82 C45,70 62,78 98,60 S150,69 184,45 S235,56 274,35 S330,40 400,9" fill="none" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round"/>
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════ Visual Design ═══════════ --}}
<section id="visual" class="sc-section">
  <div class="sc-container">
    <div class="sc-section-head">
      <span class="sc-eyebrow">01 — التصميم</span>
      <h2>هوية بصرية أوضح وأكثر جاذبية</h2>
      <p class="sc-section-lead">
        تم توحيد الألوان والمسافات والبطاقات والأزرار لتبدو كل شاشة وكأنها جزء من منتج واحد متكامل.
      </p>
    </div>
    <div class="sc-grid">
      <article class="sc-card">
        <div class="sc-card-icon">✦</div>
        <h3>واجهة رئيسية تسويقية</h3>
        <p>عنوان قوي، دعوات واضحة للإجراء، معاينة مرئية للوحة التحكم، ومحتوى مرتب يقود الزائر من التعرف إلى البدء.</p>
      </article>
      <article class="sc-card">
        <div class="sc-card-icon">◈</div>
        <h3>تجربة عربية أصلية</h3>
        <p>اتجاه RTL، نصوص عربية واضحة، توزيع مريح للمحتوى، وتدرج بصري يجعل القراءة والتنقل أكثر سهولة.</p>
      </article>
      <article class="sc-card">
        <div class="sc-card-icon">▦</div>
        <h3>استجابة لكل شاشة</h3>
        <p>تصميم متجاوب للهواتف والأجهزة اللوحية وسطح المكتب مع قائمة جانبية مرنة في لوحة التحكم.</p>
      </article>
    </div>
  </div>
</section>

{{-- ═══════════ Security Split ═══════════ --}}
<section id="security" class="sc-split">
  <div class="sc-container">
    <div class="sc-split-inner">
      <div>
        <span class="sc-eyebrow">02 — البرمجة والأمان</span>
        <h2>الجمال في الواجهة، والثقة في الخلفية</h2>
        <p class="sc-split-lead">
          التحسين لم يتوقف عند الشكل. تم تنظيم الوصول إلى النظام وتقوية العمليات الحساسة مع الحفاظ على قابلية التوسع.
        </p>
        <div class="sc-list">
          <div class="sc-list-item"><span class="sc-check">✓</span><span>تسجيل دخول وخروج آمن مع تجديد الجلسة وCSRF.</span></div>
          <div class="sc-list-item"><span class="sc-check">✓</span><span>صلاحيات واضحة للأدوار: مدير عام، مدير متجر، موظف، عميل.</span></div>
          <div class="sc-list-item"><span class="sc-check">✓</span><span>عزل بيانات المتاجر عبر Policies وTenant Scopes.</span></div>
          <div class="sc-list-item"><span class="sc-check">✓</span><span>Rate Limiting لتسجيل الدخول وWebhook الرسائل.</span></div>
          <div class="sc-list-item"><span class="sc-check">✓</span><span>سجل تدقيق للعمليات الإدارية المهمة.</span></div>
        </div>
      </div>
      <div class="sc-code">
        <div class="sc-code-head">
          <span class="sc-code-dot"><i></i><i></i><i></i></span>
          <span>routes/web.php</span>
        </div>
        <div class="sc-code-body">
<span class="cls">Route</span>::<span class="fn">middleware</span>([<span class="str">'auth'</span>, <span class="str">'role:shop_admin,staff'</span>])<br>
&nbsp;&nbsp;-&gt;<span class="fn">prefix</span>(<span class="str">'dashboard'</span>)<br>
&nbsp;&nbsp;-&gt;<span class="fn">group</span>(<span class="kw">function</span> () {<br>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="cls">Route</span>::<span class="fn">get</span>(<span class="str">'orders'</span>, [<span class="cls">OrderController</span>::class, <span class="str">'index'</span>]);<br>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="cls">Route</span>::<span class="fn">post</span>(<span class="str">'orders/{order}/status'</span>, ...);<br>
&nbsp;&nbsp;});<br><br>
<span class="cmt">// كل متجر يرى بياناته فقط</span>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════ Dashboard Section ═══════════ --}}
<section id="dashboard" class="sc-section">
  <div class="sc-container">
    <div class="sc-section-head">
      <span class="sc-eyebrow">03 — لوحة التحكم</span>
      <h2>إدارة يومية أبسط وقرارات أوضح</h2>
      <p class="sc-section-lead">
        لوحة تحكم مصممة للعمل الحقيقي: معلومات مهمة في مكانها، وتنقل واضح بدون ازدحام.
      </p>
    </div>
    <div class="sc-grid">
      <article class="sc-card">
        <div class="sc-card-icon">⌂</div>
        <h3>نظرة سريعة</h3>
        <p>المبيعات والطلبات والعملاء في بطاقات مختصرة تساعدك على فهم حالة المتجر من أول نظرة.</p>
      </article>
      <article class="sc-card">
        <div class="sc-card-icon">◉</div>
        <h3>تشغيل منظم</h3>
        <p>الطلبات والمدفوعات ورسائل SMS مرتبة في مسارات واضحة للوصول السريع إلى العمل المطلوب.</p>
      </article>
      <article class="sc-card">
        <div class="sc-card-icon">⚙</div>
        <h3>إعدادات تحت السيطرة</h3>
        <p>إعدادات المتجر والصلاحيات وسجل العمليات ضمن بنية أوضح وأكثر أمانًا.</p>
      </article>
    </div>
  </div>
</section>

{{-- ═══════════ CTA ═══════════ --}}
<section class="sc-section">
  <div class="sc-container">
    <div class="sc-cta">
      <div class="sc-cta-content">
        <h2>جاهز تبدأ بشكل مختلف؟</h2>
        <p>افتح المتجر واستعرض التجربة كاملة.</p>
      </div>
      <div class="sc-cta-actions">
        <a href="/demo-shop" class="sc-btn sc-btn-gold">فتح المتجر ←</a>
        <a href="/dashboard" class="sc-btn sc-btn-soft">لوحة التحكم</a>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════ Footer ═══════════ --}}
<footer class="sc-footer">
  <div class="sc-footer-inner">
    <span>© {{ date('Y') }} <strong>MultiStore</strong> — منصة متاجر متعددة</span>
    <span>مصمم ليبدو جميلاً ويعمل بثقة</span>
  </div>
</footer>

</body>
</html>

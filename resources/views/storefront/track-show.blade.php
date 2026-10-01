<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>تتبع {{ $order->order_number }} — {{ $order->shop->name ?? 'المتجر' }}</title>
<script src="https://cdn.tailwindcss.com">
// ── معالجة تحويل المخططات إلى صور قبل الطباعة لضمان ظهورها ──
window.addEventListener('beforeprint', () => {
  ['chartDaily', 'chartDow'].forEach(id => {
    const canvas = document.getElementById(id);
    if (!canvas) return;
    let img = document.getElementById(id + '_print_img');
    if (!img) {
      img = document.createElement('img');
      img.id = id + '_print_img';
      img.className = 'print-chart-img';
      img.style.width = '100%';
      img.style.height = 'auto';
      canvas.parentNode.insertBefore(img, canvas.nextSibling);
    }
    try {
      img.src = canvas.toDataURL('image/png');
      canvas.style.display = 'none';
      img.style.display = 'block';
    } catch(e) { console.error(e); }
  });
});

window.addEventListener('afterprint', () => {
  ['chartDaily', 'chartDow'].forEach(id => {
    const canvas = document.getElementById(id);
    const img = document.getElementById(id + '_print_img');
    if (canvas) canvas.style.display = 'block';
    if (img) img.style.display = 'none';
  });
});

</script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Cairo', sans-serif; background: #f5f7fa; margin: 0; }
  @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
  @keyframes slideIn { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
  @keyframes pulseRing { 0% { box-shadow: 0 0 0 0 rgba(16,185,129,0.6); } 50% { box-shadow: 0 0 0 14px rgba(16,185,129,0); } 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); } }
  @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
  .animate-slideUp { animation: slideUp 0.5s ease-out; }
  .animate-slideIn { animation: slideIn 0.4s ease-out; }
  .animate-bounce { animation: bounce 1s ease-in-out infinite; }
  .timeline-dot { width: 52px; height: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; position: relative; z-index: 2; transition: all 0.4s; }
  .timeline-dot.current { animation: pulseRing 2s infinite; }
  .card { background: white; border-radius: 20px; padding: 22px; margin-bottom: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; transition: all 0.3s; }
  .marquee { overflow: hidden; white-space: nowrap; }
  .marquee-content { display: inline-block; animation: marquee 35s linear infinite; }
  @keyframes marquee { from { transform: translateX(100%); } to { transform: translateX(-100%); } }
  .pulse-dot { display: inline-block; width: 8px; height: 8px; background: #10b981; border-radius: 50%; animation: pulseRing 2s infinite; }

  /* ═══ Print Styles ═══ */
  @media print {
    body { background: white !important; }
    
    /* إخفاء كل ما لا يجب طباعته */
    .no-print,
    .marquee,
    header .pulse-dot,
    #toast,
    .action-buttons { display: none !important; }
    
    /* إخفاء الإعلانات والاقتراحات */
    .ads-section,
    .recommendations-section,
    .celebration-banner { display: none !important; }
    
    .card {
      box-shadow: none !important;
      border: 1px solid #d1d5db !important;
      page-break-inside: avoid;
      margin-bottom: 10px !important;
    }
    
    /* إظهار كل خطوات الـ Timeline بشكل واضح */
    .timeline-dot {
      animation: none !important;
      box-shadow: none !important;
    }
    
    /* إظهار الإحصائيات بوضوح */
    .stats-section {
      background: #f9fafb !important;
      border: 1px solid #e5e7eb !important;
    }
    
    /* الطباعة بالألوان */
    * {
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }
    
    header { position: static !important; }
  }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>



<!-- ═══ ترويسة الطباعة فقط ═══ -->
<div class="print-header" style="display:none;padding:16px 0 12px 0;border-bottom:3px double #1f2937;margin-bottom:18px;">
  <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;">
    <div>
      <div style="font-size:22px;font-weight:900;color:#1f2937;line-height:1.2;">📊 تقرير المبيعات المتقدم</div>
      <div style="font-size:13px;color:#6b7280;margin-top:6px;font-weight:700;">
        {{ $order->shop->name ?? 'المتجر' }}
      </div>
    </div>
    <div style="text-align:left;font-size:11px;color:#6b7280;line-height:1.7;">
      <div>تاريخ التقرير: <b style="color:#1f2937;">{{ now()->format('Y-m-d H:i') }}</b></div>
      <div>رقم الطلب: <b style="font-family:monospace;color:#1f2937;">{{ $order->order_number }}</b></div>
      <div>الفترة: <b id="printRangeLabel" style="color:#1f2937;">—</b></div>
    </div>
  </div>
</div>

<!-- ═══ تذييل الطباعة ═══ -->
<div class="print-footer" style="display:none;padding-top:12px;margin-top:20px;border-top:1px solid #e5e7eb;text-align:center;font-size:10px;color:#9ca3af;">
  {{ $order->shop->name ?? 'المتجر' }} — تقرير المبيعات — {{ now()->format('Y-m-d') }} — صفحة <span class="page-num"></span>
</div>
<!-- ═══ ترويسة الطباعة فقط ═══ -->
<div class="print-header" style="display:none;padding:16px 0 12px 0;border-bottom:3px double #1f2937;margin-bottom:18px;">
  <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;">
    <div>
      <div style="font-size:22px;font-weight:900;color:#1f2937;line-height:1.2;">📊 تقرير المبيعات المتقدم</div>
      <div style="font-size:13px;color:#6b7280;margin-top:6px;font-weight:700;">
        {{ $order->shop->name ?? 'المتجر' }}
      </div>
    </div>
    <div style="text-align:left;font-size:11px;color:#6b7280;line-height:1.7;">
      <div>تاريخ التقرير: <b style="color:#1f2937;">{{ now()->format('Y-m-d H:i') }}</b></div>
      <div>رقم الطلب: <b style="font-family:monospace;color:#1f2937;">{{ $order->order_number }}</b></div>
      <div>الفترة: <b id="printRangeLabel" style="color:#1f2937;">—</b></div>
    </div>
  </div>
</div>

<!-- ═══ تذييل الطباعة ═══ -->
<div class="print-footer" style="display:none;padding-top:12px;margin-top:20px;border-top:1px solid #e5e7eb;text-align:center;font-size:10px;color:#9ca3af;">
  {{ $order->shop->name ?? 'المتجر' }} — تقرير المبيعات — {{ now()->format('Y-m-d') }} — صفحة <span class="page-num"></span>
</div>
<!-- شريط الإعلانات المتحرك -->
<div class="no-print" style="background:linear-gradient(to left,#f59e0b,#ea580c);color:white;padding:8px 0;font-size:13px;font-weight:800;">
  <div class="marquee">
    <div class="marquee-content">
      🎁 شحن مجاني للطلبات فوق 50,000 ريال &nbsp;•&nbsp;
      🎉 خصم 10% على طلبك التالي &nbsp;•&nbsp;
      ⚡ توصيل سريع خلال 24 ساعة &nbsp;•&nbsp;
      💚 كوبون WELCOME10 لأول طلب &nbsp;•&nbsp;
      🔥 عروض حصرية كل يوم
    </div>
  </div>
</div>

<!-- الهيدر -->
<header style="background:white;padding:14px 20px;box-shadow:0 2px 8px rgba(0,0,0,0.04);display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:50;">
  <a href="/shop" style="display:flex;align-items:center;gap:10px;text-decoration:none;color:inherit;">
    <div style="width:42px;height:42px;background:linear-gradient(135deg,#fbbf24,#f97316);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;box-shadow:0 4px 12px rgba(245,158,11,0.3);">🍯</div>
    <div>
      <div style="font-weight:900;font-size:15px;color:#1f2937;">{{ $order->shop->name ?? 'المتجر' }}</div>
      <div style="font-size:11px;color:#10b981;font-weight:800;display:flex;align-items:center;gap:4px;">
        <span class="pulse-dot"></span> متصل الآن
      </div>
    </div>
  </a>
  <a href="/track" style="color:#f59e0b;font-weight:800;font-size:13px;text-decoration:none;display:flex;align-items:center;gap:4px;">
    <i data-lucide="search" style="width:16px;height:16px;"></i> تتبع آخر
  </a>
</header>

<div style="max-width:680px;margin:0 auto;padding:20px 16px;">

  <!-- بطاقة الاحتفال -->
  @if($order->status === 'delivered')
  <div class="animate-slideUp no-print celebration-banner" style="background:linear-gradient(135deg,#10b981,#059669);border-radius:24px;padding:28px 24px;color:white;text-align:center;margin-bottom:20px;box-shadow:0 12px 32px rgba(16,185,129,0.35);position:relative;overflow:hidden;">
    <div style="position:relative;">
      <div class="animate-bounce" style="font-size:64px;margin-bottom:8px;">🎉</div>
      <h1 style="font-size:26px;font-weight:900;margin:0 0 8px 0;">تم توصيل طلبك بنجاح!</h1>
      <p style="opacity:0.95;font-size:14px;margin:0 0 16px 0;">نتمنى أن تكون سعيدًا بمشترياتك — شكرًا لثقتك بنا! 🌟</p>
      <div style="background:rgba(255,255,255,0.2);border-radius:16px;padding:16px;display:inline-block;">
        <div style="font-size:13px;margin-bottom:8px;font-weight:800;">قيّم تجربتك:</div>
        <div id="quickRating" style="display:flex;gap:6px;justify-content:center;">
          @for($i = 1; $i <= 5; $i++)
          <button onclick="rateOrder({{ $i }})"
            style="width:40px;height:40px;border:none;background:rgba(255,255,255,0.15);border-radius:10px;cursor:pointer;font-size:24px;color:white;"
            onmouseover="this.style.background='rgba(255,255,255,0.35)';this.style.transform='scale(1.1)'"
            onmouseout="this.style.background='rgba(255,255,255,0.15)';this.style.transform='scale(1)'">⭐</button>
          @endfor
        </div>
      </div>
    </div>
  </div>
  @endif

  <!-- بطاقة الطلب -->
  <div class="animate-slideUp" style="background:linear-gradient(135deg,#fbbf24,#f97316);border-radius:24px;padding:24px;color:white;margin-bottom:20px;box-shadow:0 12px 32px rgba(245,158,11,0.35);position:relative;overflow:hidden;">
    <div style="position:relative;">
      <div style="font-size:12px;opacity:0.9;margin-bottom:4px;">رقم الطلب</div>
      <div style="font-size:26px;font-weight:900;font-family:monospace;margin-bottom:16px;letter-spacing:1px;">{{ $order->order_number }}</div>
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;font-size:12px;">
        <div>
          <div style="opacity:0.85;margin-bottom:2px;">التاريخ</div>
          <div style="font-weight:900;font-size:14px;">{{ $order->created_at ? $order->created_at->format('Y-m-d') : '—' }}</div>
        </div>
        <div>
          <div style="opacity:0.85;margin-bottom:2px;">الإجمالي</div>
          <div style="font-weight:900;font-size:14px;">{{ number_format($order->total) }} ريال</div>
        </div>
        <div>
          <div style="opacity:0.85;margin-bottom:2px;">العميل</div>
          <div style="font-weight:900;font-size:14px;">{{ $order->customer_name }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- الـ Timeline -->
  @php
    $steps = [
      ['awaiting_payment', 'بانتظار الدفع', '⏳', '#f59e0b', 'سنتحقق من الدفع'],
      ['processing', 'قيد التحضير', '⚙️', '#3b82f6', 'نحضّر طلبك الآن'],
      ['shipped', 'قيد الشحن', '🚚', '#8b5cf6', 'في الطريق إليك'],
      ['delivered', 'تم التسليم', '✅', '#10b981', 'وصل بسلام'],
    ];
    $statuses = array_column($steps, 0);
    $currentIndex = array_search($order->status, $statuses);
    if ($currentIndex === false) $currentIndex = -1;
    $isCancelled = $order->status === 'cancelled';
  @endphp

  <div class="card animate-slideUp">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
      <h2 style="font-size:17px;font-weight:900;color:#1f2937;margin:0;">📊 حالة الطلب</h2>
      @if(!$isCancelled)
      <span style="background:#f0fdf4;color:#15803d;font-size:11px;font-weight:900;padding:4px 12px;border-radius:999px;">
        المرحلة {{ $currentIndex + 1 }} من 4
      </span>
      @endif
    </div>

    @if($isCancelled)
    <div style="background:#fee2e2;border:2px solid #fecaca;border-radius:16px;padding:24px;text-align:center;">
      <div style="font-size:48px;margin-bottom:12px;">❌</div>
      <div style="font-weight:900;font-size:18px;color:#b91c1c;">تم إلغاء هذا الطلب</div>
      <p style="font-size:13px;color:#991b1b;margin:8px 0 0 0;">نأسف لإبلاغك — للاستفسار تواصل معنا</p>
    </div>
    @else
    <div style="position:relative;">
      @foreach($steps as $i => $step)
      @php
        $isActive = $i <= $currentIndex;
        $isCurrent = $i === $currentIndex;
        $isLast = $i === count($steps) - 1;
      @endphp
      <div class="animate-slideIn" style="display:flex;gap:16px;position:relative;padding-bottom:{{ $isLast ? '0' : '28px' }};animation-delay:{{ $i * 0.1 }}s;">
        @if(!$isLast)
        <div style="position:absolute;right:25px;top:52px;bottom:0;width:3px;background:{{ $i < $currentIndex ? 'linear-gradient(180deg,#10b981,#059669)' : '#e5e7eb' }};border-radius:2px;z-index:1;"></div>
        @endif
        <div class="timeline-dot {{ $isCurrent ? 'current' : '' }}"
             style="background:{{ $isActive ? $step[3] : '#e5e7eb' }};color:white;box-shadow:{{ $isActive ? '0 8px 20px ' . $step[3] . '40' : 'none' }};">
          {{ $step[2] }}
        </div>
        <div style="flex:1;padding-top:8px;">
          <div style="font-weight:900;font-size:16px;color:{{ $isActive ? '#1f2937' : '#9ca3af' }};">{{ $step[1] }}</div>
          <div style="font-size:12px;color:{{ $isActive ? $step[3] : '#9ca3af' }};margin-top:4px;font-weight:700;">{{ $step[4] }}</div>
          @if($isCurrent)
          <div style="display:inline-flex;align-items:center;gap:6px;background:{{ $step[3] }};color:white;font-size:11px;font-weight:900;padding:4px 12px;border-radius:999px;margin-top:8px;">
            <span class="pulse-dot" style="background:white;width:6px;height:6px;"></span> الحالة الحالية
          </div>
          @endif
        </div>
      </div>
      @endforeach
    </div>
    @endif

    @if($order->tracking_number)
    <div style="margin-top:20px;padding:16px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);border-radius:14px;border:1px solid #93c5fd;">
      <div style="display:flex;align-items:center;gap:8px;font-size:11px;color:#1d4ed8;font-weight:900;margin-bottom:8px;">
        <i data-lucide="package-search" style="width:14px;height:14px;"></i> رقم التتبع
      </div>
      <div style="font-family:monospace;font-weight:900;font-size:19px;color:#1e40af;letter-spacing:2px;">{{ $order->tracking_number }}</div>
      @if($order->carrier)
      <div style="font-size:12px;color:#1d4ed8;margin-top:6px;font-weight:800;display:flex;align-items:center;gap:4px;">
        <i data-lucide="truck" style="width:14px;height:14px;"></i> {{ $order->carrier }}
      </div>
      @endif
    </div>
    @endif

    @if(!$isCancelled && $currentIndex >= 0)
    <div style="margin-top:16px;">
      <div style="height:8px;background:#e5e7eb;border-radius:999px;overflow:hidden;">
        <div style="height:100%;width:{{ (($currentIndex + 1) / 4) * 100 }}%;background:linear-gradient(to left,#10b981,#059669);border-radius:999px;transition:width 1s;"></div>
      </div>
      <div style="text-align:center;font-size:11px;color:#6b7280;margin-top:6px;font-weight:800;">
        تقدم الطلب: {{ round((($currentIndex + 1) / 4) * 100) }}%
      </div>
    </div>
    @endif
  </div>

  <!-- المنتجات -->
  @if($order->items && $order->items->count() > 0)
  <div class="card animate-slideUp">
    <h2 style="font-size:17px;font-weight:900;color:#1f2937;margin:0 0 16px 0;display:flex;align-items:center;gap:8px;">
      <i data-lucide="shopping-bag" style="width:18px;height:18px;color:#f59e0b;"></i>
      المنتجات ({{ $order->items->count() }})
    </h2>
    @foreach($order->items as $item)
    <div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid #f3f4f6;">
      <div style="width:48px;height:48px;background:linear-gradient(135deg,#fef3c7,#fed7aa);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;">📦</div>
      <div style="flex:1;min-width:0;">
        <div style="font-weight:900;font-size:14px;color:#1f2937;">{{ $item->product_name }}</div>
        <div style="font-size:12px;color:#9ca3af;margin-top:2px;">الكمية: {{ $item->quantity }} × {{ number_format($item->unit_price) }}</div>
      </div>
      <div style="font-weight:900;font-size:15px;color:#f59e0b;flex-shrink:0;">{{ number_format($item->line_total) }}</div>
    </div>
    @endforeach
    <div style="margin-top:14px;padding-top:14px;border-top:2px dashed #e5e7eb;">
      <div style="display:flex;justify-content:space-between;font-size:19px;font-weight:900;">
        <span style="color:#1f2937;">الإجمالي:</span>
        <span style="color:#f59e0b;">{{ number_format($order->total) }} ريال</span>
      </div>
    </div>
  </div>
  @endif

  
  <!-- ════════════════════════════════════════════ -->
  <!-- 📊 لوحة الإحصائيات -->
  <!-- ════════════════════════════════════════════ -->
  <div id="statsDashboard" class="animate-slideUp" style="margin-bottom:16px;">

    <!-- الفلاتر والأدوات -->
    <div class="card no-print" style="padding:14px;">
      <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px;">
        <button onclick="setPeriod('today')" data-period="today" class="period-btn">اليوم</button>
        <button onclick="setPeriod('7d')"    data-period="7d"    class="period-btn">7 أيام</button>
        <button onclick="setPeriod('30d')"   data-period="30d"   class="period-btn active">30 يوم</button>
        <button onclick="setPeriod('month')" data-period="month" class="period-btn">هذا الشهر</button>
        <button onclick="setPeriod('year')"  data-period="year"  class="period-btn">هذه السنة</button>
        <button onclick="toggleCustom()"     data-period="custom" class="period-btn">📅 مخصص</button>
      </div>

      <!-- حقول التاريخ المخصص -->
      <div id="customRange" class="no-print" style="display:none;gap:8px;margin-bottom:12px;">
        <input type="date" id="fromDate" style="flex:1;padding:10px;border:1px solid #e5e7eb;border-radius:10px;font-family:inherit;font-weight:700;">
        <input type="date" id="toDate"   style="flex:1;padding:10px;border:1px solid #e5e7eb;border-radius:10px;font-family:inherit;font-weight:700;">
        <button onclick="applyCustom()" style="padding:10px 18px;background:linear-gradient(135deg,#f59e0b,#ea580c);color:white;border:none;border-radius:10px;font-weight:900;cursor:pointer;font-family:inherit;">تطبيق</button>
      </div>

      <div style="display:flex;gap:8px;">
        <button onclick="window.print()" class="tool-btn">
          <i data-lucide="printer" style="width:15px;height:15px;"></i> طباعة
        </button>
        <button onclick="exportCSV()" class="tool-btn">
          <i data-lucide="download" style="width:15px;height:15px;"></i> تصدير CSV
        </button>
        <span id="rangeLabel" style="margin-inline-start:auto;align-self:center;font-size:11px;color:#6b7280;font-weight:800;background:#f3f4f6;padding:6px 12px;border-radius:999px;"></span>
      </div>
    </div>

    <!-- بطاقات KPI -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;">
      <div class="kpi-card" style="--c1:#10b981;--c2:#059669;">
        <div class="kpi-emoji">💰</div>
        <div class="kpi-label">إجمالي الإيرادات</div>
        <div class="kpi-value" id="kpiRevenue">— <span class="kpi-unit">YER</span></div>
        <div class="kpi-trend" id="trendRevenue">▲ 100%</div>
      </div>
      <div class="kpi-card" style="--c1:#3b82f6;--c2:#1d4ed8;">
        <div class="kpi-emoji">🛒</div>
        <div class="kpi-label">عدد الطلبات</div>
        <div class="kpi-value" id="kpiOrders">—</div>
        <div class="kpi-trend" id="trendOrders">▲ 100%</div>
      </div>
      <div class="kpi-card" style="--c1:#8b5cf6;--c2:#6d28d9;">
        <div class="kpi-emoji">📊</div>
        <div class="kpi-label">متوسط قيمة الطلب</div>
        <div class="kpi-value" id="kpiAvg">— <span class="kpi-unit">YER</span></div>
        <div class="kpi-trend" id="trendAvg">▲ 100%</div>
      </div>
      <div class="kpi-card" style="--c1:#f59e0b;--c2:#d97706;">
        <div class="kpi-emoji">👥</div>
        <div class="kpi-label">عدد العملاء</div>
        <div class="kpi-value" id="kpiCustomers">—</div>
        <div class="kpi-trend" id="trendCustomers">▲ 100%</div>
      </div>
    </div>

    <!-- رسم المبيعات اليومية -->
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
        <h3 style="font-size:15px;font-weight:900;color:#1f2937;margin:0;display:flex;align-items:center;gap:6px;">
          📈 المبيعات اليومية
        </h3>
        <span id="dailyLabel" style="font-size:11px;color:#6b7280;font-weight:800;">30 يوم</span>
      </div>
      <div style="height:220px;position:relative;"><canvas id="chartDaily"></canvas><img id="printDaily" class="print-img" alt="" style="width:100%;height:auto;display:none;"></div>
    </div>

    <!-- رسم أيام الأسبوع -->
    <div class="card">
      <h3 style="font-size:15px;font-weight:900;color:#1f2937;margin:0 0 14px 0;">📅 المبيعات حسب أيام الأسبوع</h3>
      <div style="height:200px;position:relative;"><canvas id="chartDow"></canvas><img id="printDow" class="print-img" alt="" style="width:100%;height:auto;display:none;"></div>
    </div>

    <!-- أفضل 5 أيام -->
    <div class="card">
      <h3 style="font-size:15px;font-weight:900;color:#1f2937;margin:0 0 14px 0;">🏆 أفضل 5 أيام</h3>
      <div style="overflow-x:auto;">
        <table id="topDaysTable" style="width:100%;border-collapse:collapse;font-size:13px;">
          <thead>
            <tr style="background:#f9fafb;">
              <th style="padding:10px;text-align:right;font-weight:900;color:#6b7280;font-size:11px;">#</th>
              <th style="padding:10px;text-align:right;font-weight:900;color:#6b7280;font-size:11px;">التاريخ</th>
              <th style="padding:10px;text-align:right;font-weight:900;color:#6b7280;font-size:11px;">الطلبات</th>
              <th style="padding:10px;text-align:right;font-weight:900;color:#6b7280;font-size:11px;">الإيرادات</th>
            </tr>
          </thead>
          <tbody id="topDaysBody"></tbody>
        </table>
      </div>
    <!-- أفضل 10 منتجات -->
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
        <h3 style="font-size:15px;font-weight:900;color:#1f2937;margin:0;">🏆 أفضل 10 منتجات مبيعاً</h3>
        <span id="productsCount" style="font-size:11px;color:#6b7280;font-weight:800;background:#f3f4f6;padding:4px 10px;border-radius:999px;"></span>
      </div>
      <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
          <thead>
            <tr style="background:#f9fafb;">
              <th style="padding:10px;text-align:right;font-weight:900;color:#6b7280;font-size:11px;">#</th>
              <th style="padding:10px;text-align:right;font-weight:900;color:#6b7280;font-size:11px;">المنتج</th>
              <th style="padding:10px;text-align:right;font-weight:900;color:#6b7280;font-size:11px;">الكمية</th>
              <th style="padding:10px;text-align:right;font-weight:900;color:#6b7280;font-size:11px;">الإيرادات</th>
            </tr>
          </thead>
          <tbody id="topProductsBody"></tbody>
        </table>
      </div>
    </div>

    <!-- أفضل 10 عملاء -->
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
        <h3 style="font-size:15px;font-weight:900;color:#1f2937;margin:0;">🏆 أفضل 10 عملاء</h3>
        <span id="customersCount" style="font-size:11px;color:#6b7280;font-weight:800;background:#f3f4f6;padding:4px 10px;border-radius:999px;"></span>
      </div>
      <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
          <thead>
            <tr style="background:#f9fafb;">
              <th style="padding:10px;text-align:right;font-weight:900;color:#6b7280;font-size:11px;">#</th>
              <th style="padding:10px;text-align:right;font-weight:900;color:#6b7280;font-size:11px;">العميل</th>
              <th style="padding:10px;text-align:right;font-weight:900;color:#6b7280;font-size:11px;">الجوال</th>
              <th style="padding:10px;text-align:right;font-weight:900;color:#6b7280;font-size:11px;">الطلبات</th>
              <th style="padding:10px;text-align:right;font-weight:900;color:#6b7280;font-size:11px;">إجمالي الإنفاق</th>
            </tr>
          </thead>
          <tbody id="topCustomersBody"></tbody>
        </table>
      </div>
    </div>

  </div>

  <style>
    .period-btn {
      padding: 8px 14px; border-radius: 10px; border: 1px solid #e5e7eb;
      background: white; color: #6b7280; font-weight: 800; font-size: 12px;
      cursor: pointer; font-family: inherit; transition: all 0.2s;
    }
    .period-btn:hover { background: #f9fafb; color: #1f2937; }
    .period-btn.active {
      background: linear-gradient(135deg,#fbbf24,#f97316);
      color: white; border-color: transparent;
      box-shadow: 0 4px 12px rgba(245,158,11,0.35);
    }
    .tool-btn {
      padding: 10px 14px; border-radius: 10px; border: 1px solid #e5e7eb;
      background: white; color: #475569; font-weight: 800; font-size: 12px;
      cursor: pointer; font-family: inherit; display: inline-flex;
      align-items: center; gap: 6px; transition: all 0.2s;
    }
    .tool-btn:hover { background: #f3f4f6; }

    .kpi-card {
      background: white; border-radius: 16px; padding: 14px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.04); border: 1px solid #f1f5f9;
      position: relative; overflow: hidden;
    }
    .kpi-card::before {
      content: ''; position: absolute; top: 0; right: 0; left: 0; height: 3px;
      background: linear-gradient(90deg, var(--c1), var(--c2));
    }
    .kpi-emoji { font-size: 22px; margin-bottom: 6px; }
    .kpi-label { font-size: 11px; color: #6b7280; font-weight: 800; margin-bottom: 4px; }
    .kpi-value { font-size: 20px; font-weight: 900; color: #1f2937; line-height: 1.2; }
    .kpi-unit { font-size: 11px; color: #9ca3af; font-weight: 700; }
    .kpi-trend {
      margin-top: 6px; display: inline-block; font-size: 10px; font-weight: 900;
      padding: 2px 8px; border-radius: 999px;
      background: #f0fdf4; color: #15803d;
    }
    .kpi-trend.down { background: #fef2f2; color: #b91c1c; }

    @media print {
      #statsDashboard .no-print { display: none !important; }
      #statsDashboard .card { page-break-inside: avoid; }
      .kpi-card { border: 1px solid #e5e7eb !important; }

      /* إخفاء canvas وإظهار الصورة الثابتة */
      #chartDaily, #chartDow { display: none !important; }
      .print-img {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        max-height: 260px;
        object-fit: contain;
      }
      /* إزالة fixed height للـ container */
      #chartDaily, #chartDow,
      #chartDaily + .print-img, #chartDow + .print-img { page-break-inside: avoid; }
    }
    /* إخفاء صور الطباعة على الشاشة */
    @media screen {
      .print-img { display: none !important; }
    }
    @media (max-width: 480px) {
      .kpi-value { font-size: 16px; }
    }
  </style>

  <script>
  (function(){
    const SHOP_ID = {{ $order->shop_id ?? 'null' }};
    let currentPeriod = '30d';
    let currentFrom = null, currentTo = null;
    let dailyChart = null, dowChart = null;
    let lastData = null;

    // ── تحديث الفترة ──
    window.setPeriod = function(p) {
      currentPeriod = p;
      document.querySelectorAll('.period-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.period === p);
      });
      document.getElementById('customRange').style.display = p === 'custom' ? 'flex' : 'none';
      if (p !== 'custom') loadStats();
    };

    window.toggleCustom = function() {
      setPeriod('custom');
      document.getElementById('fromDate').value ||= new Date(Date.now() - 29*864e5).toISOString().slice(0,10);
      document.getElementById('toDate').value   ||= new Date().toISOString().slice(0,10);
    };

    window.applyCustom = function() {
      currentFrom = document.getElementById('fromDate').value;
      currentTo   = document.getElementById('toDate').value;
      loadStats();
    };

    // ── تحميل البيانات ──
    async function loadStats() {
      const params = new URLSearchParams({ period: currentPeriod });
      if (SHOP_ID) params.append('shop_id', SHOP_ID);
      if (currentPeriod === 'custom') {
        params.append('from', currentFrom);
        params.append('to', currentTo);
      }
      try {
        const res = await fetch('/api/shop-stats?' + params);
        const data = await res.json();
        lastData = data;
        render(data);
      } catch (e) {
        console.error('Stats error:', e);
      }
    }

    function fmt(n) { return new Intl.NumberFormat('en-US').format(Math.round(n)); }

    function render(data) {
      const k = data.kpi;
      document.getElementById('kpiRevenue').innerHTML   = fmt(k.revenue) + ' <span class="kpi-unit">YER</span>';
      document.getElementById('kpiOrders').textContent  = fmt(k.orders);
      document.getElementById('kpiAvg').innerHTML       = fmt(k.avg) + ' <span class="kpi-unit">YER</span>';
      document.getElementById('kpiCustomers').textContent = fmt(k.customers);

      document.getElementById('rangeLabel').textContent =
        data.period.from + ' → ' + data.period.to;
      const printRange = document.getElementById('printRangeLabel');
      if (printRange) printRange.textContent = data.period.from + ' → ' + data.period.to;
      const printRange = document.getElementById('printRangeLabel');
      if (printRange) printRange.textContent = data.period.from + ' → ' + data.period.to;

      const labels = { today:'اليوم', '7d':'7 أيام', '30d':'30 يوم', month:'هذا الشهر', year:'هذه السنة', custom:'مخصص' };
      document.getElementById('dailyLabel').textContent = labels[currentPeriod] || '30 يوم';

      // Chart 1: Daily
      const dailyLabels = data.daily.map(d => d.date.slice(5));
      const dailyValues = data.daily.map(d => d.revenue);
      if (dailyChart) dailyChart.destroy();
      dailyChart = new Chart(document.getElementById('chartDaily'), {
        type: 'line',
        data: {
          labels: dailyLabels,
          datasets: [{
            label: 'الإيرادات',
            data: dailyValues,
            borderColor: '#f59e0b',
            backgroundColor: 'rgba(245,158,11,0.15)',
            borderWidth: 3, fill: true, tension: 0.35,
            pointBackgroundColor: '#f59e0b', pointRadius: 4, pointHoverRadius: 6,
          }]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          plugins: { legend: { display: false },
            tooltip: { callbacks: { label: c => fmt(c.parsed.y) + ' YER' } } },
          scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' },
              ticks: { callback: v => fmt(v), font: { family: 'Cairo', weight: '700' } } },
            x: { grid: { display: false },
              ticks: { font: { family: 'Cairo', weight: '700' }, maxRotation: 0, autoSkip: true, maxTicksLimit: 8 } }
          }
        }
      });

      // Chart 2: DOW
      const dowLabels = Object.keys(data.by_dow);
      const dowValues = dowLabels.map(n => data.by_dow[n].revenue);
      if (dowChart) dowChart.destroy();
      dowChart = new Chart(document.getElementById('chartDow'), {
        type: 'bar',
        data: {
          labels: dowLabels,
          datasets: [{
            label: 'الإيرادات', data: dowValues,
            backgroundColor: ['#fbbf24','#f59e0b','#f97316','#ea580c','#dc2626','#8b5cf6','#3b82f6'],
            borderRadius: 8, borderSkipped: false,
          }]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          plugins: { legend: { display: false },
            tooltip: { callbacks: { label: c => fmt(c.parsed.y) + ' YER' } } },
          scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' },
              ticks: { callback: v => fmt(v), font: { family: 'Cairo', weight: '700' } } },
            x: { grid: { display: false },
              ticks: { font: { family: 'Cairo', weight: '700' } } }
          }
        }
      });

      // ═══ تحويل المخططات إلى صور للطباعة ═══
      // تأخير بسيط ليُكمل Chart.js الرسم
      setTimeout(() => {
        try {
          const dailyCanvas = document.getElementById('chartDaily');
          const dowCanvas = document.getElementById('chartDow');
          if (dailyCanvas) {
            const url = dailyCanvas.toDataURL('image/png', 1.0);
            const img = document.getElementById('printDaily');
            if (img) img.src = url;
          }
          if (dowCanvas) {
            const url = dowCanvas.toDataURL('image/png', 1.0);
            const img = document.getElementById('printDow');
            if (img) img.src = url;
          }
        } catch(e) { console.warn('Chart→Image failed:', e); }
      }, 800);

      // جدول أفضل 5 أيام
      const tb = document.getElementById('topDaysBody');
      tb.innerHTML = data.top.map((d, i) => `
        <tr style="border-bottom:1px solid #f3f4f6;">
          <td style="padding:12px;font-weight:900;color:${i<3?'#f59e0b':'#9ca3af'};">${i+1}</td>
          <td style="padding:12px;font-weight:800;color:#1f2937;">${d.date}</td>
          <td style="padding:12px;font-weight:800;color:#3b82f6;">${d.orders}</td>
          <td style="padding:12px;font-weight:900;color:#10b981;">${fmt(d.revenue)} YER</td>
        </tr>
      `).join('') || '<tr><td colspan="4" style="padding:20px;text-align:center;color:#9ca3af;">لا توجد بيانات</td></tr>';

      // أفضل المنتجات
      const tp = document.getElementById('topProductsBody');
      document.getElementById('productsCount').textContent = data.top_products.length + ' منتج';
      tp.innerHTML = data.top_products.map((p, i) => `
        <tr style="border-bottom:1px solid #f3f4f6;">
          <td style="padding:12px;font-weight:900;color:${i<3?'#f59e0b':'#9ca3af'};">${i+1}</td>
          <td style="padding:12px;font-weight:800;color:#1f2937;">${p.name}</td>
          <td style="padding:12px;font-weight:800;color:#3b82f6;">${p.qty}</td>
          <td style="padding:12px;font-weight:900;color:#10b981;">${fmt(p.revenue)} YER</td>
        </tr>
      `).join('') || '<tr><td colspan="4" style="padding:20px;text-align:center;color:#9ca3af;">لا توجد بيانات</td></tr>';

      // أفضل العملاء
      const tc = document.getElementById('topCustomersBody');
      document.getElementById('customersCount').textContent = data.top_customers.length + ' عميل';
      tc.innerHTML = data.top_customers.map((cu, i) => `
        <tr style="border-bottom:1px solid #f3f4f6;">
          <td style="padding:12px;font-weight:900;color:${i<3?'#f59e0b':'#9ca3af'};">${i+1}</td>
          <td style="padding:12px;font-weight:800;color:#1f2937;">${cu.name}</td>
          <td style="padding:12px;font-weight:700;color:#6b7280;font-family:monospace;">${cu.phone || '—'}</td>
          <td style="padding:12px;font-weight:800;color:#3b82f6;">${cu.orders}</td>
          <td style="padding:12px;font-weight:900;color:#10b981;">${fmt(cu.revenue)} YER</td>
        </tr>
      `).join('') || '<tr><td colspan="5" style="padding:20px;text-align:center;color:#9ca3af;">لا توجد بيانات</td></tr>';
    }

    // ── تصدير CSV ──
    window.exportCSV = function() {
      if (!lastData) return;
      let csv = '\uFEFF'; // BOM للعربية في Excel
      csv += `إحصائيات المتجر - الفترة: ${lastData.period.from} إلى ${lastData.period.to}\n\n`;
      csv += 'الملخص\n';
      csv += 'المؤشر,القيمة\n';
      csv += `إجمالي الإيرادات,${lastData.kpi.revenue}\n`;
      csv += `عدد الطلبات,${lastData.kpi.orders}\n`;
      csv += `متوسط قيمة الطلب,${lastData.kpi.avg}\n`;
      csv += `عدد العملاء,${lastData.kpi.customers}\n\n`;
      csv += 'المبيعات اليومية\n';
      csv += 'التاريخ,الطلبات,الإيرادات\n';
      lastData.daily.forEach(d => { csv += `${d.date},${d.orders},${d.revenue}\n`; });
      csv += '\nأفضل 5 أيام\n';
      csv += '#,التاريخ,الطلبات,الإيرادات\n';
      lastData.top.forEach((d, i) => { csv += `${i+1},${d.date},${d.orders},${d.revenue}\n`; });

      const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
      const a = document.createElement('a');
      a.href = URL.createObjectURL(blob);
      a.download = `stats_${lastData.period.from}_to_${lastData.period.to}.csv`;
      a.click();
      showToast('تم تصدير الملف', '📥');
    };

    // ── Toast (إن لم يوجد) ──
    if (typeof showToast !== 'function') {
      window.showToast = function(msg, e='✅') {
        const t = document.getElementById('toast');
        if (!t) { alert(msg); return; }
        t.innerHTML = `<span style="font-size:20px;">${e}</span> <span>${msg}</span>`;
        t.style.transform = 'translateX(-50%) translateY(0)';
        clearTimeout(window._tt);
        window._tt = setTimeout(() => t.style.transform = 'translateX(-50%) translateY(100px)', 2500);
      };
    }


    // ── تحميل أولي ──
    loadStats();
  })();
  </script>


<!-- الاقتراحات الذكية (لا تُطبع) -->
  @php
    try {
      $recommended = \App\Services\Recommendation\RecommendationEngine::forCustomer(4);
    } catch (\Exception $e) {
      $recommended = collect();
    }
  @endphp

  @if($recommended->count() > 0)
  <div class="card animate-slideUp no-print recommendations-section">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
      <div style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,#8b5cf6,#6d28d9);display:flex;align-items:center;justify-content:center;font-size:22px;box-shadow:0 4px 12px rgba(139,92,246,0.35);">✨</div>
      <div>
        <h2 style="font-size:17px;font-weight:900;color:#1f2937;margin:0;">مختار لك</h2>
        <p style="font-size:12px;color:#6b7280;margin:2px 0 0 0;">منتجات قد تعجبك</p>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;">
      @foreach($recommended as $p)
      <a href="/product/{{ $p->id }}" style="background:#fafbfc;border:1px solid #f1f5f9;border-radius:14px;overflow:hidden;text-decoration:none;color:inherit;transition:all 0.3s;display:block;">
        <div style="aspect-ratio:1;background:linear-gradient(135deg,#ede9fe,#ddd6fe);position:relative;overflow:hidden;">
          @if($p->image)
            <img src="{{ ($p->image_url ?? Storage::url($p->image)) }}" style="width:100%;height:100%;object-fit:cover;">
          @else
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:42px;">📦</div>
          @endif
          <div style="position:absolute;top:8px;right:8px;background:linear-gradient(135deg,#8b5cf6,#6d28d9);color:white;font-size:9px;font-weight:900;padding:3px 8px;border-radius:999px;">✨ لك</div>
        </div>
        <div style="padding:10px;">
          <div style="font-weight:900;font-size:12px;color:#1f2937;margin-bottom:4px;">{{ $p->name }}</div>
          <div style="color:#8b5cf6;font-weight:900;font-size:14px;">{{ number_format($p->price) }} <span style="font-size:10px;color:#9ca3af;">ريال</span></div>
        </div>
      </a>
      @endforeach
    </div>
    <a href="/shop" style="display:block;text-align:center;margin-top:16px;padding:12px;background:linear-gradient(135deg,#8b5cf6,#6d28d9);color:white;border-radius:12px;text-decoration:none;font-weight:900;font-size:14px;">🛍️ تصفح كل المنتجات</a>
  </div>
  @endif

  <!-- الإعلانات الجديدة -->
  <div class="card animate-slideUp no-print ads-section" style="background:linear-gradient(135deg,#fef3c7,#fed7aa);border:2px solid #fcd34d;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
      <div style="font-size:28px;">📢</div>
      <h2 style="font-size:17px;font-weight:900;color:#92400e;margin:0;">إعلانات جديدة</h2>
    </div>
    <div style="display:flex;flex-direction:column;gap:10px;">
      <div style="background:white;padding:14px;border-radius:12px;display:flex;align-items:center;gap:12px;">
        <div style="font-size:24px;">🎁</div>
        <div style="flex:1;">
          <div style="font-weight:900;font-size:14px;color:#1f2937;">خصم 15% على طلبك القادم</div>
          <div style="font-size:12px;color:#6b7280;margin-top:2px;">استخدم كود <b style="background:#fef3c7;padding:2px 8px;border-radius:6px;font-family:monospace;">THANK15</b></div>
        </div>
      </div>
      <div style="background:white;padding:14px;border-radius:12px;display:flex;align-items:center;gap:12px;">
        <div style="font-size:24px;">🚚</div>
        <div style="flex:1;">
          <div style="font-weight:900;font-size:14px;color:#1f2937;">شحن مجاني للطلبات فوق 50,000 ريال</div>
          <div style="font-size:12px;color:#6b7280;margin-top:2px;">وفّر على توصيل طلبك القادم</div>
        </div>
      </div>
      <div style="background:white;padding:14px;border-radius:12px;display:flex;align-items:center;gap:12px;">
        <div style="font-size:24px;">💚</div>
        <div style="flex:1;">
          <div style="font-weight:900;font-size:14px;color:#1f2937;">انضم لنظام الولاء واكسب نقاطًا</div>
          <div style="font-size:12px;color:#6b7280;margin-top:2px;">نقطة لكل ريال تشتريه</div>
        </div>
      </div>
    </div>
  </div>

  <!-- الشكر والتواصل -->
  <div class="card animate-slideUp no-print" style="background:linear-gradient(135deg,#1f2937,#111827);color:white;border:none;">
    <div style="text-align:center;">
      <div style="font-size:44px;margin-bottom:10px;">🙏</div>
      <h3 style="font-size:19px;font-weight:900;margin:0 0 8px 0;">شكرًا لثقتك بنا!</h3>
      <p style="font-size:13px;opacity:0.85;margin:0 0 18px 0;line-height:1.7;">
        نحن سعداء بخدمتك يا {{ explode(' ', $order->customer_name)[0] }} 🌟<br>
        فريقنا جاهز لمساعدتك في أي وقت
      </p>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <a href="https://wa.me/" style="padding:12px;background:linear-gradient(135deg,#25d366,#128c7e);color:white;border-radius:12px;text-decoration:none;font-weight:900;font-size:13px;display:flex;align-items:center;justify-content:center;gap:6px;">
          <i data-lucide="message-circle" style="width:16px;height:16px;"></i> واتساب
        </a>
        <a href="/shop" style="padding:12px;background:rgba(255,255,255,0.1);color:white;border-radius:12px;text-decoration:none;font-weight:900;font-size:13px;display:flex;align-items:center;justify-content:center;gap:6px;">
          <i data-lucide="shopping-bag" style="width:16px;height:16px;"></i> تسوق مجددًا
        </a>
      </div>
    </div>
  </div>

  <!-- أزرار الإجراءات -->
  <div class="no-print action-buttons" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-top:16px;">
    <button onclick="shareOrder()" style="padding:14px;background:white;border:1px solid #e5e7eb;border-radius:14px;cursor:pointer;font-family:inherit;font-weight:900;font-size:12px;color:#475569;display:flex;flex-direction:column;align-items:center;gap:6px;">
      <i data-lucide="share-2" style="width:20px;height:20px;color:#3b82f6;"></i> مشاركة
    </button>
    <button onclick="copyTracking()" style="padding:14px;background:white;border:1px solid #e5e7eb;border-radius:14px;cursor:pointer;font-family:inherit;font-weight:900;font-size:12px;color:#475569;display:flex;flex-direction:column;align-items:center;gap:6px;">
      <i data-lucide="copy" style="width:20px;height:20px;color:#8b5cf6;"></i> نسخ الرقم
    </button>
    <button onclick="window.print()" style="padding:14px;background:white;border:1px solid #e5e7eb;border-radius:14px;cursor:pointer;font-family:inherit;font-weight:900;font-size:12px;color:#475569;display:flex;flex-direction:column;align-items:center;gap:6px;">
      <i data-lucide="printer" style="width:20px;height:20px;color:#10b981;"></i> طباعة
    </button>
  </div>

  <!-- Footer -->
  <div style="text-align:center;padding:24px 0 12px 0;font-size:12px;color:#9ca3af;">
    <div style="display:flex;align-items:center;justify-content:center;gap:6px;margin-bottom:6px;">
      <span class="pulse-dot"></span>
      <span style="font-weight:800;">يتم التحديث تلقائيًا</span>
    </div>
    <div>© {{ date('Y') }} {{ $order->shop->name ?? 'المتجر' }} — جميع الحقوق محفوظة</div>
  </div>

</div>

<!-- Toast -->
<div id="toast" style="position:fixed;bottom:20px;left:50%;transform:translateX(-50%) translateY(100px);background:#1f2937;color:white;padding:14px 22px;border-radius:14px;font-weight:800;font-size:14px;box-shadow:0 12px 32px rgba(0,0,0,0.3);transition:all 0.4s;z-index:9999;display:flex;align-items:center;gap:8px;"></div>

<script>
  setTimeout(() => lucide.createIcons(), 100);

  function showToast(msg, emoji = '✅') {
    const t = document.getElementById('toast');
    t.innerHTML = `<span style="font-size:20px;">${emoji}</span> <span>${msg}</span>`;
    t.style.transform = 'translateX(-50%) translateY(0)';
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => {
      t.style.transform = 'translateX(-50%) translateY(100px)';
    }, 2800);
  }

  function shareOrder() {
    const url = window.location.href;
    const title = 'تتبع طلبي {{ $order->order_number }}';
    if (navigator.share) {
      navigator.share({ title, url }).catch(()=>{});
    } else {
      navigator.clipboard.writeText(url);
      showToast('تم نسخ رابط التتبع', '🔗');
    }
  }

  function copyTracking() {
    const txt = '{{ $order->order_number }}' + '{{ $order->tracking_number ? " - " . $order->tracking_number : "" }}';
    navigator.clipboard.writeText(txt);
    showToast('تم نسخ رقم الطلب', '📋');
  }

  function rateOrder(rating) {
    fetch('/api/orders/{{ $order->id }}/rate', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ rating })
    })
    .then(r => r.json())
    .then(() => {
      showToast(`شكرًا لتقييمك ${rating} نجوم! 🌟`, '💚');
      document.querySelectorAll('#quickRating button').forEach((b, i) => {
        b.style.background = i < rating ? '#fbbf24' : 'rgba(255,255,255,0.15)';
        b.style.transform = i < rating ? 'scale(1.05)' : 'scale(1)';
      });
    })
    .catch(() => showToast('تم التقييم محليًا', '⭐'));
  }

  // تحديث تلقائي كل 30 ثانية
  @if(!in_array($order->status, ['delivered', 'cancelled']))
  setTimeout(() => location.reload(), 30000);
  @endif
</script>

</body>
</html>

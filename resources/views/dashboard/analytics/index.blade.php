@extends('layouts.app')
@section('title', 'التحليلات')
@section('page-title', '📊 التحليلات')
@section('page-subtitle', 'رؤية شاملة لأداء متجرك')

@section('content')

{{-- ═══ Hero ═══ --}}
<div class="an-hero">
  <div class="an-hero-content">
    <div class="an-hero-icon">📊</div>
    <div>
      <div class="an-hero-title">لوحة التحليلات</div>
      <div class="an-hero-sub">تتبع أداء متجرك واتخذ قرارات مبنية على البيانات</div>
    </div>
  </div>
  <div class="an-hero-badge">آخر تحديث: الآن</div>
</div>

{{-- ═══ KPI Cards ═══ --}}
<div class="an-kpis">
  <div class="an-kpi">
    <div class="an-kpi-icon" style="background:#dcfce7;color:#15803d">💰</div>
    <div class="an-kpi-body">
      <div class="an-kpi-label">مبيعات هذا الشهر</div>
      <div class="an-kpi-value">{{ number_format($stats['sales']) }} <small>ر.ي</small></div>
      <div class="an-kpi-change {{ $stats['growth'] >= 0 ? 'up' : 'down' }}">
        {{ $stats['growth'] >= 0 ? '▲' : '▼' }} {{ abs($stats['growth']) }}%
        <span>مقارنة بالشهر السابق</span>
      </div>
    </div>
  </div>

  <div class="an-kpi">
    <div class="an-kpi-icon" style="background:#dbeafe;color:#1e40af">📦</div>
    <div class="an-kpi-body">
      <div class="an-kpi-label">الطلبات</div>
      <div class="an-kpi-value">{{ number_format($stats['orders']) }}</div>
      <div class="an-kpi-change neutral">هذا الشهر</div>
    </div>
  </div>

  <div class="an-kpi">
    <div class="an-kpi-icon" style="background:#fef3c7;color:#d97706">👥</div>
    <div class="an-kpi-body">
      <div class="an-kpi-label">عملاء جدد</div>
      <div class="an-kpi-value">{{ number_format($stats['customers']) }}</div>
      <div class="an-kpi-change neutral">هذا الشهر</div>
    </div>
  </div>

  <div class="an-kpi">
    <div class="an-kpi-icon" style="background:#fee2e2;color:#b91c1c">📈</div>
    <div class="an-kpi-body">
      <div class="an-kpi-label">متوسط قيمة الطلب</div>
      <div class="an-kpi-value">{{ number_format($stats['avg_order']) }} <small>ر.ي</small></div>
      <div class="an-kpi-change neutral">للكل طلب</div>
    </div>
  </div>
</div>

{{-- ═══ Sales Chart ═══ --}}
<div class="an-card">
  <div class="an-card-head">
    <h2 class="an-card-title">📈 مبيعات آخر 7 أيام</h2>
    <div class="an-card-total">الإجمالي: {{ number_format(collect($stats['chart'])->sum('value')) }} ر.ي</div>
  </div>
  <div class="an-chart">
    @php $maxVal = max(1, collect($stats['chart'])->max('value')); @endphp
    <div class="an-chart-bars">
      @foreach($stats['chart'] as $day)
        @php $pct = ($day['value'] / $maxVal) * 100; @endphp
        <div class="an-chart-col">
          <div class="an-chart-value">{{ number_format($day['value']) }}</div>
          <div class="an-chart-bar" style="height:{{ max(8, $pct) }}%">
            <div class="an-chart-fill"></div>
          </div>
          <div class="an-chart-label">{{ $day['label'] }}</div>
        </div>
      @endforeach
    </div>
  </div>
</div>

{{-- ═══ Top Products ═══ --}}
<div class="an-card">
  <div class="an-card-head">
    <h2 class="an-card-title">🏆 أفضل 5 منتجات</h2>
    <a href="/dashboard/products" class="an-card-link">كل المنتجات ←</a>
  </div>

  @if($stats['top_products']->count() > 0)
    <div class="an-products">
      @foreach($stats['top_products'] as $i => $p)
        <div class="an-product">
          <div class="an-product-rank an-rank-{{ $i < 3 ? $i + 1 : 'n' }}">
            @if($i === 0) 🥇
            @elseif($i === 1) 🥈
            @elseif($i === 2) 🥉
            @else {{ $i + 1 }}
            @endif
          </div>
          <div class="an-product-info">
            <div class="an-product-name">{{ $p->product_name }}</div>
            <div class="an-product-meta">{{ number_format($p->total_qty) }} مبيع • {{ number_format($p->total_revenue) }} ر.ي</div>
          </div>
          <div class="an-product-bar">
            @php $barPct = $stats['top_products']->max('total_qty') > 0 ? ($p->total_qty / $stats['top_products']->max('total_qty')) * 100 : 0; @endphp
            <div class="an-product-bar-fill" style="width:{{ $barPct }}%"></div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="an-empty">
      <div style="font-size:48px;margin-bottom:12px">📦</div>
      <div style="font-weight:900;margin-bottom:6px">لا توجد مبيعات بعد</div>
      <div style="color:#94a3b8;font-size:13px">ستظهر أفضل المنتجات هنا عندما تبدأ المبيعات</div>
    </div>
  @endif
</div>

<style>
.an-hero{background:linear-gradient(135deg,#0f172a,#1e293b,#334155);border-radius:22px;padding:28px;color:#fff;margin-bottom:20px;position:relative;overflow:hidden;display:flex;justify-content:space-between;align-items:center;gap:16px}
.an-hero::before{content:'';position:absolute;top:-50%;right:-15%;width:400px;height:400px;background:radial-gradient(circle,rgba(245,158,11,.3),transparent 65%);pointer-events:none}
.an-hero-content{position:relative;z-index:1;display:flex;align-items:center;gap:16px}
.an-hero-icon{font-size:44px}
.an-hero-title{font-size:20px;font-weight:900}
.an-hero-sub{font-size:13px;color:#94a3b8;font-weight:700;margin-top:4px}
.an-hero-badge{position:relative;z-index:1;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);padding:8px 14px;border-radius:12px;font-size:11px;font-weight:800;color:#cbd5e1;backdrop-filter:blur(10px)}

.an-kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px}
@media(max-width:1000px){.an-kpis{grid-template-columns:repeat(2,1fr)}}
.an-kpi{background:#fff;border:1px solid #e9edf2;border-radius:20px;padding:20px;display:flex;gap:14px;box-shadow:0 4px 16px rgba(0,0,0,.04);transition:.25s;position:relative;overflow:hidden}
.an-kpi::after{content:'';position:absolute;bottom:-30%;left:-10%;width:100px;height:100px;border-radius:50%;background:rgba(245,158,11,.05);pointer-events:none}
.an-kpi:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(0,0,0,.08)}
.an-kpi-icon{width:52px;height:52px;border-radius:15px;display:grid;place-items:center;font-size:24px;flex-shrink:0}
.an-kpi-body{flex:1;min-width:0;position:relative;z-index:1}
.an-kpi-label{font-size:11px;color:#94a3b8;font-weight:800;text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px}
.an-kpi-value{font-size:22px;font-weight:900;color:#17202b;line-height:1.1;margin-bottom:6px}
.an-kpi-value small{font-size:12px;color:#94a3b8;font-weight:700}
.an-kpi-change{font-size:11px;font-weight:800;display:flex;align-items:center;gap:6px;flex-wrap:wrap}
.an-kpi-change.up{color:#16a34a}
.an-kpi-change.down{color:#dc2626}
.an-kpi-change.neutral{color:#94a3b8}
.an-kpi-change span{color:#94a3b8;font-weight:700}

.an-card{background:#fff;border:1px solid #e9edf2;border-radius:20px;padding:22px;margin-bottom:20px;box-shadow:0 4px 16px rgba(0,0,0,.04)}
.an-card-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;gap:10px;flex-wrap:wrap}
.an-card-title{font-size:15px;font-weight:900;display:flex;align-items:center;gap:8px;margin:0}
.an-card-total{font-size:12px;color:#64748b;font-weight:800;background:#f1f5f9;padding:6px 12px;border-radius:10px}
.an-card-link{font-size:12px;color:#d97706;font-weight:900;text-decoration:none}
.an-card-link:hover{text-decoration:underline}

/* ═══ Chart ═══ */
.an-chart{height:260px;padding-top:20px}
.an-chart-bars{display:flex;align-items:flex-end;justify-content:space-between;gap:8px;height:100%}
.an-chart-col{flex:1;display:flex;flex-direction:column;align-items:center;height:100%;justify-content:flex-end;gap:8px;position:relative}
.an-chart-value{font-size:10px;font-weight:900;color:#d97706;opacity:0;transition:.3s}
.an-chart-col:hover .an-chart-value{opacity:1;transform:translateY(-4px)}
.an-chart-bar{width:100%;max-width:60px;background:#f1f5f9;border-radius:10px 10px 4px 4px;position:relative;overflow:hidden;transition:.3s;min-height:8px}
.an-chart-col:hover .an-chart-bar{background:#e2e8f0}
.an-chart-fill{position:absolute;bottom:0;left:0;right:0;height:100%;background:linear-gradient(180deg,#fbbf24,#d97706);border-radius:10px 10px 4px 4px;transform:scaleY(0);transform-origin:bottom;animation:anBarGrow 1s ease-out forwards}
@keyframes anBarGrow{to{transform:scaleY(1)}}
.an-chart-label{font-size:11px;font-weight:800;color:#64748b;text-transform:capitalize}

/* ═══ Products ═══ */
.an-products{display:flex;flex-direction:column;gap:10px}
.an-product{display:flex;align-items:center;gap:14px;padding:14px;border-radius:14px;background:#f8fafc;transition:.2s;position:relative;overflow:hidden}
.an-product:hover{background:#fff7ed;transform:translateX(-4px)}
.an-product-rank{width:40px;height:40px;border-radius:12px;display:grid;place-items:center;font-weight:900;font-size:15px;background:#e2e8f0;color:#475569;flex-shrink:0}
.an-rank-1{background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#fff;font-size:18px}
.an-rank-2{background:linear-gradient(135deg,#cbd5e1,#94a3b8);color:#fff;font-size:18px}
.an-rank-3{background:linear-gradient(135deg,#cd7f32,#92400e);color:#fff;font-size:18px}
.an-product-info{flex:1;min-width:0}
.an-product-name{font-size:13px;font-weight:900;color:#17202b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:4px}
.an-product-meta{font-size:11px;color:#94a3b8;font-weight:700}
.an-product-bar{width:100px;height:6px;background:#e2e8f0;border-radius:3px;overflow:hidden;flex-shrink:0}
.an-product-bar-fill{height:100%;background:linear-gradient(90deg,#fbbf24,#d97706);border-radius:3px;transition:width 1s ease-out}
@media(max-width:600px){.an-product-bar{display:none}}

.an-empty{text-align:center;padding:40px 20px;color:#94a3b8}

/* Dark Mode */
html.dark .an-kpi,
html.dark .an-card{background:#1a1d21;border-color:#2a2e33}
html.dark .an-kpi-value,
html.dark .an-card-title,
html.dark .an-product-name{color:#e5e7eb}
html.dark .an-chart-bar{background:#24282d}
html.dark .an-product{background:#24282d}
html.dark .an-product:hover{background:#2a2e33}
html.dark .an-card-total{background:#24282d;color:#94a3b8}
</style>

@endsection

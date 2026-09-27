@extends('layouts.app')
@section('title', 'التقارير')
@section('page-title', 'التقارير')
@section('page-subtitle', 'تحليل شامل لأداء متجرك')

@section('content')

<div class="shine-stats">
  <div class="shine-stat s-green">
    <div class="shine-stat-top">
      <div class="shine-stat-icon"><i data-lucide="calendar"></i></div>
    </div>
    <div class="shine-stat-label">اليوم</div>
    <div class="shine-stat-value">{{ number_format($stats['today']['sales']) }} <small>ريال</small></div>
    <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">{{ $stats['today']['orders'] }} طلب</div>
  </div>

  <div class="shine-stat s-blue">
    <div class="shine-stat-top">
      <div class="shine-stat-icon"><i data-lucide="trending-up"></i></div>
    </div>
    <div class="shine-stat-label">هذا الأسبوع</div>
    <div class="shine-stat-value">{{ number_format($stats['week']['sales']) }} <small>ريال</small></div>
    <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">{{ $stats['week']['orders'] }} طلب</div>
  </div>

  <div class="shine-stat s-amber">
    <div class="shine-stat-top">
      <div class="shine-stat-icon"><i data-lucide="bar-chart"></i></div>
    </div>
    <div class="shine-stat-label">هذا الشهر</div>
    <div class="shine-stat-value">{{ number_format($stats['month']['sales']) }} <small>ريال</small></div>
    <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">{{ $stats['month']['orders'] }} طلب</div>
  </div>

  <div class="shine-stat s-purple">
    <div class="shine-stat-top">
      <div class="shine-stat-icon"><i data-lucide="award"></i></div>
    </div>
    <div class="shine-stat-label">الإجمالي</div>
    <div class="shine-stat-value">{{ number_format($stats['total']['sales']) }} <small>ريال</small></div>
    <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">{{ $stats['total']['orders'] }} طلب</div>
  </div>
</div>

<!-- Chart -->
<div class="shine-card">
  <div class="shine-card-header">
    <div class="shine-card-title"><i data-lucide="activity"></i> المبيعات — آخر 7 أيام</div>
  </div>
  <div style="padding:24px;">
    @php $max = max(array_column($chart, 'sales')) ?: 1; @endphp
    <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:6px;height:180px;">
      @foreach($chart as $c)
      <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:8px;">
        <div style="font-size:10px;font-weight:900;color:var(--text-muted);">{{ number_format($c['sales']) }}</div>
        <div style="width:100%;background:linear-gradient(to top, #f59e0b, #fbbf24);border-radius:8px 8px 0 0;height:{{ max(8, ($c['sales'] / $max) * 130) }}px;transition:all 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'"></div>
        <div style="font-size:10px;color:var(--text-muted);">{{ $c['date'] }}</div>
      </div>
      @endforeach
    </div>
  </div>
</div>

<!-- Top Products -->
<div class="shine-card">
  <div class="shine-card-header">
    <div class="shine-card-title"><i data-lucide="package"></i> المنتجات الأكثر توفرًا</div>
  </div>
  @foreach($topProducts as $p)
  <div class="shine-list-item">
    <div class="shine-item-avatar">📦</div>
    <div class="shine-item-content">
      <h4>{{ $p->name }}</h4>
      <p>{{ number_format($p->price) }} ريال</p>
    </div>
    <div class="shine-item-meta">
      <div class="shine-item-amount" style="color:#15803d;">{{ $p->stock }}</div>
      <span class="shine-item-time">قطعة</span>
    </div>
  </div>
  @endforeach
</div>

@endsection

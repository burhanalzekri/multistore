@extends('layouts.app')

@section('title', 'تحليلات Variants — نظرة شاملة')

@push('styles')
<style>
  .oa-wrap { padding: 24px; max-width: 1400px; margin: 0 auto; }
  .oa-title { font-size: 24px; font-weight: 900; color: #111827; margin: 0 0 6px; }
  .oa-sub { font-size: 13px; color: #6b7280; margin-bottom: 24px; }
  .oa-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
  .oa-stat { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 18px; display: flex; align-items: center; gap: 14px; }
  .oa-stat-icon { width: 48px; height: 48px; border-radius: 12px; display: grid; place-items: center; font-size: 22px; }
  .oa-stat-icon.orange { background: #fff7ed; }
  .oa-stat-icon.blue { background: #eff6ff; }
  .oa-stat-icon.green { background: #f0fdf4; }
  .oa-stat-icon.red { background: #fef2f2; }
  .oa-stat-label { font-size: 12px; color: #6b7280; font-weight: 600; }
  .oa-stat-value { font-size: 20px; font-weight: 900; color: #111827; margin-top: 2px; }
  .oa-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px; margin-bottom: 24px; }
  .oa-card-title { font-size: 16px; font-weight: 800; color: #111827; margin: 0 0 16px; display: flex; align-items: center; gap: 8px; }
  .oa-table { width: 100%; border-collapse: collapse; font-size: 13px; }
  .oa-table th { background: #f9fafb; padding: 12px 10px; text-align: right; font-weight: 800; color: #374151; border-bottom: 2px solid #e5e7eb; font-size: 12px; }
  .oa-table td { padding: 12px 10px; border-bottom: 1px solid #f3f4f6; color: #111827; }
  .oa-table tr:hover td { background: #fafafa; }
  .oa-rank { display: inline-grid; place-items: center; width: 26px; height: 26px; border-radius: 8px; background: #f3f4f6; color: #374151; font-weight: 800; font-size: 12px; }
  .oa-rank.gold { background: #fef3c7; color: #92400e; }
  .oa-rank.silver { background: #e5e7eb; color: #374151; }
  .oa-rank.bronze { background: #fed7aa; color: #9a3412; }
  .oa-charts { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
  .oa-color-dot { display: inline-block; width: 14px; height: 14px; border-radius: 50%; vertical-align: middle; margin-left: 6px; }
  .oa-empty { text-align: center; padding: 40px; color: #9ca3af; }

  /* ═══ أزرار الطباعة والتصدير ═══ */
  .oa-header { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; }
  .oa-actions { display: flex; gap: 8px; flex-wrap: wrap; }
  .oa-btn { display: inline-flex; align-items: center; gap: 7px; padding: 10px 18px; border-radius: 11px; font-size: 13px; font-weight: 800; text-decoration: none; border: 1px solid; cursor: pointer; transition: all .2s; }
  .oa-btn.print { background: #fff; color: #374151; border-color: #d1d5db; }
  .oa-btn.print:hover { background: #f9fafb; }
  .oa-btn.pdf { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
  .oa-btn.pdf:hover { background: #fecaca; }
  .oa-btn.excel { background: #dcfce7; color: #166534; border-color: #86efac; }
  .oa-btn.excel:hover { background: #bbf7d0; }

  /* ═══ طباعة ═══ */
  @media print {
    .oa-actions, .admin-sidebar, .admin-overlay, .admin-header, .admin-bottom-nav, .admin-menu-btn, .toast-container { display: none !important; }
    .oa-wrap { padding: 0; max-width: 100%; }
    .oa-main { margin: 0 !important; }
    body { background: #fff !important; }
    .oa-card { break-inside: avoid; box-shadow: none; border: 1px solid #ddd; }
    .oa-stat { break-inside: avoid; }
    .oa-charts { grid-template-columns: 1fr 1fr; }
    canvas { max-width: 100%; }
    @page { margin: 1cm; size: A4; }
  }

  @media (max-width: 768px) {
    .oa-charts { grid-template-columns: 1fr; }
    .oa-wrap { padding: 16px; }
    .oa-table { font-size: 11px; }
    .oa-table th, .oa-table td { padding: 8px 6px; }
  }
</style>
@endpush

@section('content')
<div class="oa-wrap print-report">

  {{-- 🖨️ رأس التقرير (يظهر عند الطباعة فقط) --}}
  <div class="print-only print-header">
    <div class="print-header-left">
      <h1 class="print-title">📊 تحليلات Variants</h1>
      <p class="print-subtitle">تقرير شامل لأداء الألوان والأحجام</p>
    </div>
    <div class="print-header-right">
      <div class="print-meta"><strong>التاريخ:</strong> {{ now()->format('Y-m-d H:i') }}</div>
      <div class="print-meta"><strong>المتجر:</strong> {{ auth()->user()->shop?->name ?? 'متجر العسل' }}</div>
    </div>
  </div>

  <div class="oa-header">
    <div>
      <h1 class="oa-title">📊 تحليلات Variants</h1>
      <div class="oa-sub">نظرة شاملة على أداء كل الألوان والأحجام</div>
    </div>
    <div class="oa-actions">
      <button class="oa-btn print" onclick="window.print()">
        🖨️ طباعة
      </button>
      <a href="{{ route('variants.analytics.export.details') }}" class="oa-btn excel">
        📥 Variants (CSV)
      </a>
      <a href="{{ route('variants.analytics.export.byColor') }}" class="oa-btn excel" style="background:#fff7ed;color:#c2410c;border-color:#fdba74;">
        🎨 الألوان (CSV)
      </a>
      <a href="{{ route('variants.analytics.export.bySize') }}" class="oa-btn excel" style="background:#eff6ff;color:#1e40af;border-color:#93c5fd;">
        📏 الأحجام (CSV)
      </a>
    </div>
  </div>

  {{-- Stats --}}
  <div class="oa-stats">
    <div class="oa-stat">
      <div class="oa-stat-icon orange">📦</div>
      <div>
        <div class="oa-stat-label">إجمالي Variants</div>
        <div class="oa-stat-value">{{ $stats['total_variants'] }}</div>
      </div>
    </div>
    <div class="oa-stat">
      <div class="oa-stat-icon green">🛒</div>
      <div>
        <div class="oa-stat-label">إجمالي المبيعات</div>
        <div class="oa-stat-value">{{ number_format($stats['total_sold']) }}</div>
      </div>
    </div>
    <div class="oa-stat">
      <div class="oa-stat-icon blue">💰</div>
      <div>
        <div class="oa-stat-label">إجمالي الإيرادات</div>
        <div class="oa-stat-value">{{ number_format($stats['total_revenue']) }} ر.ي</div>
      </div>
    </div>
    <div class="oa-stat">
      <div class="oa-stat-icon red">💤</div>
      <div>
        <div class="oa-stat-label">Variants راكدة</div>
        <div class="oa-stat-value">{{ $stats['stagnant_count'] }}</div>
      </div>
    </div>
  </div>

  {{-- Charts --}}
  <div class="oa-charts">
    <div class="oa-card">
      <h2 class="oa-card-title">🎨 المبيعات حسب اللون</h2>
      <canvas id="chartColor" height="220"></canvas>
    </div>
    <div class="oa-card">
      <h2 class="oa-card-title">📏 المبيعات حسب الحجم</h2>
      <canvas id="chartSize" height="220"></canvas>
    </div>
  </div>

  {{-- Top 10 --}}
  <div class="oa-card">
    <h2 class="oa-card-title">🏆 أكثر 10 Variants مبيعاً</h2>
    <div style="overflow-x:auto;">
      <table class="oa-table">
        <thead>
          <tr>
            <th>#</th>
            <th>المنتج</th>
            <th>اللون</th>
            <th>الحجم</th>
            <th>الكمية</th>
            <th>الإيرادات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($topVariants as $i => $v)
          <tr>
            <td>
              <span class="oa-rank {{ $i === 0 ? 'gold' : ($i === 1 ? 'silver' : ($i === 2 ? 'bronze' : '')) }}">
                {{ $i + 1 }}
              </span>
            </td>
            <td>{{ $v->product_name }}</td>
            <td>{{ $v->color ?: '—' }}</td>
            <td>{{ $v->size ?: '—' }}</td>
            <td><strong>{{ $v->total_qty }}</strong></td>
            <td>{{ number_format($v->total_revenue) }} ر.ي</td>
          </tr>
          @empty
          <tr><td colspan="6" class="oa-empty">لا توجد بيانات بعد</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- By Color --}}
  <div class="oa-card">
    <h2 class="oa-card-title">🎨 تفاصيل المبيعات حسب اللون</h2>
    <div style="overflow-x:auto;">
      <table class="oa-table">
        <thead>
          <tr>
            <th>اللون</th>
            <th>الكمية المباعة</th>
            <th>الإيرادات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($byColor as $c)
          <tr>
            <td>{{ $c->color }}</td>
            <td><strong>{{ $c->total_qty }}</strong></td>
            <td>{{ number_format($c->total_revenue) }} ر.ي</td>
          </tr>
          @empty
          <tr><td colspan="3" class="oa-empty">لا توجد بيانات</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Stagnant --}}
  @if($stagnant->count() > 0)
  <div class="oa-card">
    <h2 class="oa-card-title">💤 Variants راكدة (مخزون > 0 بدون مبيعات)</h2>
    <div style="overflow-x:auto;">
      <table class="oa-table">
        <thead>
          <tr>
            <th>#</th>
            <th>المنتج</th>
            <th>اللون</th>
            <th>الحجم</th>
            <th>المخزون</th>
          </tr>
        </thead>
        <tbody>
          @foreach($stagnant as $i => $s)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $s->product?->name ?? '—' }}</td>
            <td>{{ $s->color ?: '—' }}</td>
            <td>{{ $s->size ?: '—' }}</td>
            <td><strong>{{ $s->stock }}</strong></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif

  {{-- 🖨️ تذييل التقرير (يظهر عند الطباعة فقط) --}}
  <div class="print-only print-footer">
    تقرير تحليلات Variants — {{ auth()->user()->shop?->name ?? 'متجر العسل' }} — {{ now()->format('Y-m-d') }}
  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
  const colors = ['#f97316','#3b82f6','#10b981','#8b5cf6','#ef4444','#eab308','#ec4899','#14b8a6','#6366f1','#84cc16'];

  // Chart: By Color
  new Chart(document.getElementById('chartColor'), {
    type: 'doughnut',
    data: {
      labels: @json($chartData['colorLabels']),
      datasets: [{
        data: @json($chartData['colorQty']),
        backgroundColor: colors,
        borderWidth: 2,
        borderColor: '#fff',
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } }
    }
  });

  // Chart: By Size
  new Chart(document.getElementById('chartSize'), {
    type: 'bar',
    data: {
      labels: @json($chartData['sizeLabels']),
      datasets: [{
        label: 'الكمية',
        data: @json($chartData['sizeQty']),
        backgroundColor: '#f97316',
        borderRadius: 8,
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });
</script>
@endpush

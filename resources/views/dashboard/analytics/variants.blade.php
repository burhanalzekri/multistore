@extends('layouts.app')

@section('title', 'تحليلات Variants — ' . $product->name)

@push('styles')
<style>
  .va-wrap { padding: 24px; max-width: 1400px; margin: 0 auto; }
  .va-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }

  .va-header-wrap { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; }
  .va-actions { display: flex; gap: 8px; flex-wrap: wrap; }
  .va-btn { display: inline-flex; align-items: center; gap: 7px; padding: 10px 18px; border-radius: 11px; font-size: 13px; font-weight: 800; text-decoration: none; border: 1px solid; cursor: pointer; transition: all .2s; }
  .va-btn.print { background: #fff; color: #374151; border-color: #d1d5db; }
  .va-btn.print:hover { background: #f9fafb; }
  .va-btn.excel { background: #dcfce7; color: #166534; border-color: #86efac; }
  .va-btn.excel:hover { background: #bbf7d0; }

  .va-back { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; color: #374151; text-decoration: none; font-size: 13px; font-weight: 700; }
  .va-back:hover { background: #f9fafb; }
  .va-title { font-size: 22px; font-weight: 900; color: #111827; margin: 0; }
  .va-sub { font-size: 13px; color: #6b7280; margin-top: 4px; }
  .va-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
  .va-stat { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 18px; display: flex; align-items: center; gap: 14px; }
  .va-stat-icon { width: 48px; height: 48px; border-radius: 12px; display: grid; place-items: center; font-size: 22px; }
  .va-stat-icon.orange { background: #fff7ed; }
  .va-stat-icon.blue { background: #eff6ff; }
  .va-stat-icon.green { background: #f0fdf4; }
  .va-stat-icon.purple { background: #faf5ff; }
  .va-stat-label { font-size: 12px; color: #6b7280; font-weight: 600; }
  .va-stat-value { font-size: 20px; font-weight: 900; color: #111827; margin-top: 2px; }
  .va-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px; margin-bottom: 24px; }
  .va-card-title { font-size: 16px; font-weight: 800; color: #111827; margin: 0 0 16px; display: flex; align-items: center; gap: 8px; }
  .va-table { width: 100%; border-collapse: collapse; font-size: 13px; }
  .va-table th { background: #f9fafb; padding: 12px 10px; text-align: right; font-weight: 800; color: #374151; border-bottom: 2px solid #e5e7eb; font-size: 12px; }
  .va-table td { padding: 12px 10px; border-bottom: 1px solid #f3f4f6; color: #111827; }
  .va-table tr:hover td { background: #fafafa; }
  .va-color-dot { display: inline-block; width: 16px; height: 16px; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 0 1px #d1d5db; vertical-align: middle; margin-left: 6px; }
  .va-badge { display: inline-block; padding: 3px 9px; border-radius: 99px; font-size: 11px; font-weight: 700; }
  .va-badge.ok { background: #dcfce7; color: #166534; }
  .va-badge.low { background: #fef3c7; color: #92400e; }
  .va-badge.out { background: #fee2e2; color: #991b1b; }
  .va-badge.inactive { background: #f3f4f6; color: #6b7280; }
  .va-charts { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  @media (max-width: 768px) {
    .va-charts { grid-template-columns: 1fr; }
    .va-wrap { padding: 16px; }
    .va-table { font-size: 11px; }
    .va-table th, .va-table td { padding: 8px 6px; }
  }
</style>
@endpush

@section('content')
<div class="va-wrap print-report">

  {{-- 🖨️ رأس التقرير (يظهر عند الطباعة فقط) --}}
  <div class="print-only print-header">
    <div class="print-header-left">
      <h1 class="print-title">📊 تحليلات Variants — {{ $product->name }}</h1>
      <p class="print-subtitle">تقرير تفصيلي لكل الألوان والأحجام</p>
    </div>
    <div class="print-header-right">
      <div class="print-meta"><strong>التاريخ:</strong> {{ now()->format('Y-m-d H:i') }}</div>
      <div class="print-meta"><strong>المتجر:</strong> {{ auth()->user()->shop?->name ?? 'متجر العسل' }}</div>
      <div class="print-meta"><strong>المنتج:</strong> {{ $product->name }}</div>
    </div>
  </div>

  {{-- Header --}}
  <div class="va-header-wrap">
    <div class="va-header" style="margin:0;">
      <a href="{{ url('/dashboard/products/' . $product->id) }}" class="va-back">→ عودة للمنتج</a>
      <div>
        <h1 class="va-title">📊 تحليلات Variants</h1>
        <div class="va-sub">{{ $product->name }}</div>
      </div>
    </div>
    <div class="va-actions">
      <button class="va-btn print" onclick="window.print()">
        🖨️ طباعة
      </button>
      <a href="{{ route('variants.analytics.product.export', $product->id) }}" class="va-btn excel">
        📥 تصدير Variants (CSV)
      </a>
    </div>
  </div>

  {{-- Stats --}}
  <div class="va-stats">
    <div class="va-stat">
      <div class="va-stat-icon orange">📦</div>
      <div>
        <div class="va-stat-label">إجمالي Variants</div>
        <div class="va-stat-value">{{ $stats['total_variants'] }}</div>
      </div>
    </div>
    <div class="va-stat">
      <div class="va-stat-icon blue">🏪</div>
      <div>
        <div class="va-stat-label">المخزون الكلي</div>
        <div class="va-stat-value">{{ number_format($stats['total_stock']) }}</div>
      </div>
    </div>
    <div class="va-stat">
      <div class="va-stat-icon green">🛒</div>
      <div>
        <div class="va-stat-label">إجمالي المبيعات (كمية)</div>
        <div class="va-stat-value">{{ number_format($stats['total_sold']) }}</div>
      </div>
    </div>
    <div class="va-stat">
      <div class="va-stat-icon purple">💰</div>
      <div>
        <div class="va-stat-label">إجمالي الإيرادات</div>
        <div class="va-stat-value">{{ number_format($stats['total_revenue']) }} ر.ي</div>
      </div>
    </div>
  </div>

  {{-- Table --}}
  <div class="va-card">
    <h2 class="va-card-title">📋 تفاصيل كل Variant</h2>
    <div style="overflow-x:auto;">
      <table class="va-table">
        <thead>
          <tr>
            <th>#</th>
            <th>اللون</th>
            <th>الحجم</th>
            <th>SKU</th>
            <th>المخزون</th>
            <th>الكمية المباعة</th>
            <th>الإيرادات</th>
            <th>الحالة</th>
          </tr>
        </thead>
        <tbody>
          @forelse($variantsData as $i => $v)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>
              <span class="va-color-dot" style="background: {{ $v['color_hex'] }}"></span>
              {{ $v['color'] }}
            </td>
            <td>{{ $v['size'] }}</td>
            <td style="color:#6b7280; font-size:11px;">{{ $v['sku'] ?: '—' }}</td>
            <td>
              @if($v['stock'] === 0)
                <span class="va-badge out">نفذ</span>
              @elseif($v['stock'] <= 3)
                <span class="va-badge low">{{ $v['stock'] }}</span>
              @else
                <span class="va-badge ok">{{ $v['stock'] }}</span>
              @endif
            </td>
            <td><strong>{{ $v['qty_sold'] }}</strong></td>
            <td>{{ number_format($v['revenue']) }} ر.ي</td>
            <td>
              @if($v['is_active'])
                <span class="va-badge ok">نشط</span>
              @else
                <span class="va-badge inactive">معطل</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" style="text-align:center; padding:40px; color:#9ca3af;">
              لا توجد variants لهذا المنتج
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Charts --}}
  @if($variantsData->count() > 0)
  <div class="va-charts">
    <div class="va-card">
      <h2 class="va-card-title">📊 المبيعات حسب Variant</h2>
      <canvas id="chartQty" height="220"></canvas>
    </div>
    <div class="va-card">
      <h2 class="va-card-title">💰 الإيرادات حسب Variant</h2>
      <canvas id="chartRevenue" height="220"></canvas>
    </div>
  </div>
  @endif

  {{-- 🖨️ تذييل التقرير (يظهر عند الطباعة فقط) --}}
  <div class="print-only print-footer">
    تقرير تحليلات Variants — {{ $product->name }} — {{ now()->format('Y-m-d') }}
  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
@if($variantsData->count() > 0)
  const chartLabels = @json($chartData['labels']);
  const chartQty = @json($chartData['qty']);
  const chartRevenue = @json($chartData['revenue']);
  const colors = ['#f97316','#3b82f6','#10b981','#8b5cf6','#ef4444','#eab308','#ec4899','#14b8a6','#6366f1','#84cc16'];

  new Chart(document.getElementById('chartQty'), {
    type: 'bar',
    data: {
      labels: chartLabels,
      datasets: [{
        label: 'الكمية المباعة',
        data: chartQty,
        backgroundColor: colors,
        borderRadius: 8,
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });

  new Chart(document.getElementById('chartRevenue'), {
    type: 'doughnut',
    data: {
      labels: chartLabels,
      datasets: [{
        data: chartRevenue,
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
@endif
</script>
@endpush

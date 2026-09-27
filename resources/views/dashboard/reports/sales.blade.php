@extends('layouts.app')

@section('title', 'تقارير المبيعات')

@push('styles')
<style>
  .sr-wrap { padding: 24px; max-width: 1400px; margin: 0 auto; }
  .sr-header { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 20px; }
  .sr-title { font-size: 24px; font-weight: 900; color: #111827; margin: 0 0 6px; }
  .sr-sub { font-size: 13px; color: #6b7280; }

  /* ═══ الفلتر ═══ */
  .sr-filter { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 16px; margin-bottom: 20px; display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
  .sr-filter-group { display: flex; flex-direction: column; gap: 6px; }
  .sr-filter-label { font-size: 11px; font-weight: 800; color: #6b7280; text-transform: uppercase; }
  .sr-filter-input { padding: 9px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 13px; font-weight: 700; background: #fff; color: #111827; font-family: inherit; min-width: 140px; }
  .sr-filter-input:focus { outline: 2px solid #f97316; }
  .sr-filter-btns { display: flex; gap: 4px; flex-wrap: wrap; }
  .sr-period-btn { padding: 9px 16px; border: 1px solid #d1d5db; background: #fff; color: #374151; border-radius: 10px; font-size: 12.5px; font-weight: 700; cursor: pointer; text-decoration: none; transition: all .2s; font-family: inherit; }
  .sr-period-btn:hover { background: #f9fafb; }
  .sr-period-btn.active { background: #ea580c; color: #fff; border-color: #ea580c; }
  .sr-actions { display: flex; gap: 8px; margin-right: auto; }
  .sr-btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 10px; font-size: 12.5px; font-weight: 800; text-decoration: none; border: 1px solid; cursor: pointer; font-family: inherit; }
  .sr-btn.print { background: #fff; color: #374151; border-color: #d1d5db; }
  .sr-btn.print:hover { background: #f9fafb; }
  .sr-btn.excel { background: #dcfce7; color: #166534; border-color: #86efac; }
  .sr-btn.excel:hover { background: #bbf7d0; }

  /* ═══ KPIs ═══ */
  .sr-kpis { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px; }
  .sr-kpi { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px; position: relative; overflow: hidden; }
  .sr-kpi-icon { width: 44px; height: 44px; border-radius: 12px; display: grid; place-items: center; font-size: 20px; margin-bottom: 12px; }
  .sr-kpi-icon.orange { background: #fff7ed; }
  .sr-kpi-icon.green { background: #f0fdf4; }
  .sr-kpi-icon.blue { background: #eff6ff; }
  .sr-kpi-icon.purple { background: #faf5ff; }
  .sr-kpi-label { font-size: 12px; color: #6b7280; font-weight: 700; margin-bottom: 6px; }
  .sr-kpi-value { font-size: 24px; font-weight: 900; color: #111827; line-height: 1; }
  .sr-kpi-change { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 99px; margin-top: 8px; }
  .sr-kpi-change.up { background: #dcfce7; color: #166534; }
  .sr-kpi-change.down { background: #fee2e2; color: #991b1b; }
  .sr-kpi-change.neutral { background: #f3f4f6; color: #6b7280; }

  /* ═══ البطاقات ═══ */
  .sr-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px; margin-bottom: 20px; }
  .sr-card-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 8px; }
  .sr-card-title { font-size: 16px; font-weight: 800; color: #111827; margin: 0; display: flex; align-items: center; gap: 8px; }
  .sr-card-badge { display: inline-flex; padding: 4px 12px; background: #fff7ed; color: #c2410c; border-radius: 99px; font-size: 11px; font-weight: 800; border: 1px solid #fdba74; }
  .sr-card-desc { font-size: 12.5px; color: #6b7280; margin: 0 0 16px; }

  /* ═══ الجداول ═══ */
  .sr-table-wrap { overflow-x: auto; }
  .sr-table { width: 100%; border-collapse: collapse; font-size: 13px; }
  .sr-table th { background: #f9fafb; padding: 12px 10px; text-align: right; font-weight: 800; color: #374151; border-bottom: 2px solid #e5e7eb; font-size: 12px; white-space: nowrap; }
  .sr-table td { padding: 12px 10px; border-bottom: 1px solid #f3f4f6; color: #111827; }
  .sr-table tr:hover td { background: #fafafa; }

  /* ═══ Charts Grid ═══ */
  .sr-charts { display: grid; grid-template-columns: 1fr; gap: 20px; margin-bottom: 20px; }
  .sr-charts-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }

  /* ═══ Mobile ═══ */
  @media (max-width: 768px) {
    .sr-charts-2 { grid-template-columns: 1fr; }
    .sr-wrap { padding: 16px; }
    .sr-table { font-size: 11px; }
    .sr-table th, .sr-table td { padding: 8px 6px; }
    .sr-kpi-value { font-size: 20px; }
  }
</style>
@endpush

@section('content')
<div class="sr-wrap print-report">

  {{-- 🖨️ رأس الطباعة --}}
  <div class="print-only print-header">
    <div class="print-header-left">
      <h1 class="print-title">📈 تقرير المبيعات</h1>
      <p class="print-subtitle">{{ $period['label'] }}</p>
    </div>
    <div class="print-header-right">
      <div class="print-meta"><strong>المتجر:</strong> {{ $shop->name }}</div>
      <div class="print-meta"><strong>الفترة:</strong> {{ $period['start']->format('Y-m-d') }} → {{ $period['end']->format('Y-m-d') }}</div>
      <div class="print-meta"><strong>تاريخ التقرير:</strong> {{ now()->format('Y-m-d H:i') }}</div>
    </div>
  </div>

  {{-- Header --}}
  <div class="sr-header">
    <div>
      <h1 class="sr-title">📈 تقرير المبيعات المتقدم</h1>
      <div class="sr-sub">{{ $period['label'] }} — {{ $period['start']->format('Y-m-d') }} → {{ $period['end']->format('Y-m-d') }}</div>
    </div>
  </div>

  {{-- الفلتر --}}
  <form method="GET" action="{{ route('reports.sales') }}" class="sr-filter">
    <div class="sr-filter-group">
      <label class="sr-filter-label">الفترة</label>
      <div class="sr-filter-btns">
        <a href="{{ route('reports.sales', ['period' => 'today']) }}" class="sr-period-btn {{ $period['key'] === 'today' ? 'active' : '' }}">اليوم</a>
        <a href="{{ route('reports.sales', ['period' => '7days']) }}" class="sr-period-btn {{ $period['key'] === '7days' ? 'active' : '' }}">7 أيام</a>
        <a href="{{ route('reports.sales', ['period' => '30days']) }}" class="sr-period-btn {{ $period['key'] === '30days' ? 'active' : '' }}">30 يوم</a>
        <a href="{{ route('reports.sales', ['period' => 'month']) }}" class="sr-period-btn {{ $period['key'] === 'month' ? 'active' : '' }}">هذا الشهر</a>
        <a href="{{ route('reports.sales', ['period' => 'year']) }}" class="sr-period-btn {{ $period['key'] === 'year' ? 'active' : '' }}">هذه السنة</a>
        <a href="{{ route('reports.sales', ['period' => 'custom']) }}" class="sr-period-btn {{ $period['key'] === 'custom' ? 'active' : '' }}">مخصص</a>
      </div>
    </div>
    <div class="sr-actions">
      <button type="button" class="sr-btn print" onclick="window.print()">🖨️ طباعة</button>
      <a href="{{ route('reports.sales.export', request()->all()) }}" class="sr-btn excel">📥 تصدير CSV</a>
    </div>
  </form>

  {{-- KPIs --}}
  <div class="sr-kpis">
    <div class="sr-kpi">
      <div class="sr-kpi-icon orange">💰</div>
      <div class="sr-kpi-label">إجمالي الإيرادات</div>
      <div class="sr-kpi-value">{{ number_format($current['revenue']) }} {{ $currency }}</div>
      <div class="sr-kpi-change {{ $comparison['revenue']['direction'] }}">
        @if($comparison['revenue']['direction'] === 'up') ▲ @elseif($comparison['revenue']['direction'] === 'down') ▼ @endif
        {{ abs($comparison['revenue']['value']) }}%
      </div>
    </div>
    <div class="sr-kpi">
      <div class="sr-kpi-icon green">🛒</div>
      <div class="sr-kpi-label">عدد الطلبات</div>
      <div class="sr-kpi-value">{{ number_format($current['orders']) }}</div>
      <div class="sr-kpi-change {{ $comparison['orders']['direction'] }}">
        @if($comparison['orders']['direction'] === 'up') ▲ @elseif($comparison['orders']['direction'] === 'down') ▼ @endif
        {{ abs($comparison['orders']['value']) }}%
      </div>
    </div>
    <div class="sr-kpi">
      <div class="sr-kpi-icon blue">📊</div>
      <div class="sr-kpi-label">متوسط قيمة الطلب</div>
      <div class="sr-kpi-value">{{ number_format($current['avg_order']) }} {{ $currency }}</div>
      <div class="sr-kpi-change {{ $comparison['avg_order']['direction'] }}">
        @if($comparison['avg_order']['direction'] === 'up') ▲ @elseif($comparison['avg_order']['direction'] === 'down') ▼ @endif
        {{ abs($comparison['avg_order']['value']) }}%
      </div>
    </div>
    <div class="sr-kpi">
      <div class="sr-kpi-icon purple">👥</div>
      <div class="sr-kpi-label">عدد العملاء</div>
      <div class="sr-kpi-value">{{ number_format($current['customers']) }}</div>
      <div class="sr-kpi-change {{ $comparison['customers']['direction'] }}">
        @if($comparison['customers']['direction'] === 'up') ▲ @elseif($comparison['customers']['direction'] === 'down') ▼ @endif
        {{ abs($comparison['customers']['value']) }}%
      </div>
    </div>
  </div>

  {{-- الرسم البياني الخطي --}}
  <div class="sr-card">
    <div class="sr-card-head">
      <h2 class="sr-card-title">📈 المبيعات اليومية</h2>
      <span class="sr-card-badge">{{ count($dailyData) }} يوم</span>
    </div>
    <div style="height: 300px;">
      <canvas id="chartDaily"></canvas>
    </div>
  </div>

  {{-- توزيع أيام الأسبوع --}}
  <div class="sr-charts-2">
    <div class="sr-card">
      <h2 class="sr-card-title">📅 المبيعات حسب أيام الأسبوع</h2>
      <div style="height: 260px;">
        <canvas id="chartWeekday"></canvas>
      </div>
    </div>
    <div class="sr-card">
      <h2 class="sr-card-title">🏆 أفضل 5 أيام</h2>
      <div class="sr-table-wrap">
        <table class="sr-table">
          <thead>
            <tr><th>#</th><th>التاريخ</th><th>الطلبات</th><th>الإيرادات</th></tr>
          </thead>
          <tbody>
            @forelse($topDays as $i => $d)
            <tr>
              <td>{{ $i + 1 }}</td>
              <td>{{ $d['date'] }}</td>
              <td>{{ $d['orders'] }}</td>
              <td><strong>{{ number_format($d['revenue']) }} {{ $currency }}</strong></td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;padding:20px;color:#9ca3af;">لا توجد بيانات</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- أفضل المنتجات --}}
  <div class="sr-card">
    <div class="sr-card-head">
      <h2 class="sr-card-title">🥇 أفضل 10 منتجات مبيعاً</h2>
      <span class="sr-card-badge">{{ count($topProducts) }} منتج</span>
    </div>
    <div class="sr-table-wrap">
      <table class="sr-table">
        <thead>
          <tr><th>#</th><th>المنتج</th><th>الطلبات</th><th>الكمية</th><th>الإيرادات</th></tr>
        </thead>
        <tbody>
          @forelse($topProducts as $i => $p)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $p->product_name }}</td>
            <td>{{ $p->orders_count }}</td>
            <td><strong>{{ $p->total_qty }}</strong></td>
            <td>{{ number_format($p->total_revenue) }} {{ $currency }}</td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;padding:20px;color:#9ca3af;">لا توجد بيانات</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- أفضل العملاء --}}
  <div class="sr-card">
    <div class="sr-card-head">
      <h2 class="sr-card-title">👑 أفضل 10 عملاء</h2>
      <span class="sr-card-badge">{{ count($topCustomers) }} عميل</span>
    </div>
    <div class="sr-table-wrap">
      <table class="sr-table">
        <thead>
          <tr><th>#</th><th>العميل</th><th>الجوال</th><th>الطلبات</th><th>إجمالي الإنفاق</th></tr>
        </thead>
        <tbody>
          @forelse($topCustomers as $i => $c)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $c->customer_name ?? '—' }}</td>
            <td style="font-family: monospace; direction: ltr; text-align: left;">{{ $c->customer_phone ?? '—' }}</td>
            <td>{{ $c->orders_count }}</td>
            <td><strong>{{ number_format($c->total_spent) }} {{ $currency }}</strong></td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;padding:20px;color:#9ca3af;">لا توجد بيانات</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- تذييل الطباعة --}}
  <div class="print-only print-footer">
    تقرير المبيعات — {{ $shop->name }} — {{ now()->format('Y-m-d') }}
  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
  // ═══ المبيعات اليومية ═══
  const dailyLabels = @json(array_column($dailyData, 'label'));
  const dailyRevenue = @json(array_column($dailyData, 'revenue'));
  const dailyOrders = @json(array_column($dailyData, 'orders'));

  new Chart(document.getElementById('chartDaily'), {
    type: 'line',
    data: {
      labels: dailyLabels,
      datasets: [
        {
          label: 'الإيرادات',
          data: dailyRevenue,
          borderColor: '#f97316',
          backgroundColor: 'rgba(249, 115, 22, 0.1)',
          fill: true,
          tension: 0.4,
          borderWidth: 3,
          pointRadius: 4,
          pointBackgroundColor: '#f97316',
          yAxisID: 'y',
        },
        {
          label: 'الطلبات',
          data: dailyOrders,
          borderColor: '#3b82f6',
          backgroundColor: 'transparent',
          tension: 0.4,
          borderWidth: 2,
          borderDash: [5, 5],
          pointRadius: 3,
          pointBackgroundColor: '#3b82f6',
          yAxisID: 'y1',
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: { legend: { position: 'top' } },
      scales: {
        y: { type: 'linear', position: 'right', beginAtZero: true, ticks: { callback: v => v.toLocaleString() } },
        y1: { type: 'linear', position: 'left', beginAtZero: true, grid: { drawOnChartArea: false }, ticks: { precision: 0 } }
      }
    }
  });

  // ═══ أيام الأسبوع ═══
  const weekdayLabels = @json(array_column($byWeekday, 'day'));
  const weekdayRevenue = @json(array_column($byWeekday, 'revenue'));

  new Chart(document.getElementById('chartWeekday'), {
    type: 'bar',
    data: {
      labels: weekdayLabels,
      datasets: [{
        label: 'الإيرادات',
        data: weekdayRevenue,
        backgroundColor: '#f97316',
        borderRadius: 8,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString() } } }
    }
  });
</script>
@endpush

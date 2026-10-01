@extends('layouts.app')

@section('title', 'تحليلات الأصناف')

@push('styles')
<style>
  /* ------------------- الشاشة العادية ------------------- */
  .uv-wrap { padding: 24px; max-width: 1400px; margin: 0 auto; }
  .uv-header { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 20px; }
  .uv-title { font-size: 24px; font-weight: 900; color: #111827; margin: 0 0 6px; }
  .uv-sub { font-size: 13px; color: #6b7280; }
  .uv-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
  .uv-select { padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 11px; font-size: 13px; font-weight: 700; background: #fff; color: #111827; cursor: pointer; font-family: inherit; min-width: 200px; }
  .uv-btn { display: inline-flex; align-items: center; gap: 7px; padding: 10px 18px; border-radius: 11px; font-size: 13px; font-weight: 800; text-decoration: none; border: 1px solid; cursor: pointer; transition: all .2s; font-family: inherit; }
  .uv-btn.print { background: #fff; color: #374151; border-color: #d1d5db; }
  .uv-btn.print:hover { background: #f9fafb; }
  .uv-btn.excel { background: #dcfce7; color: #166534; border-color: #86efac; }
  .uv-btn.excel:hover { background: #bbf7d0; }

  .uv-tabs { display: flex; gap: 4px; border-bottom: 2px solid #e5e7eb; margin-bottom: 20px; overflow-x: auto; }
  .uv-tab { padding: 12px 20px; background: transparent; border: 0; border-bottom: 3px solid transparent; font-size: 14px; font-weight: 800; color: #6b7280; cursor: pointer; transition: all .2s; white-space: nowrap; font-family: inherit; }
  .uv-tab:hover { color: #111827; }
  .uv-tab.active { color: #ea580c; border-bottom-color: #ea580c; }
  .uv-panel { display: none; }
  .uv-panel.active { display: block; animation: fadeIn .2s; }
  @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: none; } }

  .uv-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
  .uv-stat { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 18px; display: flex; align-items: center; gap: 14px; }
  .uv-stat-icon { width: 48px; height: 48px; border-radius: 12px; display: grid; place-items: center; font-size: 22px; }
  .uv-stat-icon.orange { background: #fff7ed; }
  .uv-stat-icon.blue { background: #eff6ff; }
  .uv-stat-icon.green { background: #f0fdf4; }
  .uv-stat-icon.purple { background: #faf5ff; }
  .uv-stat-label { font-size: 12px; color: #6b7280; font-weight: 600; }
  .uv-stat-value { font-size: 20px; font-weight: 900; color: #111827; margin-top: 2px; }

  .uv-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px; margin-bottom: 20px; }
  .uv-card-title { font-size: 16px; font-weight: 800; color: #111827; margin: 0 0 16px; display: flex; align-items: center; gap: 8px; }

  .uv-chart-container { position: relative; height: 280px; width: 100%; }

  .uv-table-wrap { overflow-x: auto; }
  .uv-table { width: 100%; border-collapse: collapse; font-size: 13px; }
  .uv-table th { background: #f9fafb; padding: 12px 10px; text-align: right; font-weight: 800; color: #374151; border-bottom: 2px solid #e5e7eb; font-size: 12px; white-space: nowrap; }
  .uv-table td { padding: 12px 10px; border-bottom: 1px solid #f3f4f6; color: #111827; }
  .uv-table tr:hover td { background: #fafafa; }
  .uv-badge { display: inline-block; padding: 3px 9px; border-radius: 99px; font-size: 11px; font-weight: 700; }
  .uv-badge.ok { background: #dcfce7; color: #166534; }
  .uv-badge.low { background: #fef3c7; color: #92400e; }
  .uv-badge.out { background: #fee2e2; color: #991b1b; }
  .uv-badge.inactive { background: #f3f4f6; color: #6b7280; }

  .uv-charts { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }

  /* ------------------- 🖨️ تنسيق الطباعة A4 المخصص ------------------- */
  @media print {
    @page {
      size: A4 portrait;
      margin: 12mm 10mm;
    }
    body {
      background: #fff !important;
      color: #000 !important;
      font-family: system-ui, -apple-system, sans-serif !important;
      font-size: 11pt !important;
    }
    nav, sidebar, header, footer, .uv-actions, .uv-tabs, .btn, button {
      display: none !important;
    }
    .uv-wrap {
      padding: 0 !important;
      max-width: 100% !important;
      margin: 0 !important;
    }
    .uv-header {
      border-bottom: 2px solid #111827;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }
    .uv-title { font-size: 18pt !important; }
    .uv-panel { display: block !important; opacity: 1 !important; transform: none !important; }
    .uv-stats {
      grid-template-columns: repeat(4, 1fr) !important;
      gap: 10px !important;
      margin-bottom: 15px !important;
    }
    .uv-stat {
      border: 1px solid #cbd5e1 !important;
      padding: 10px !important;
      border-radius: 6px !important;
    }
    .uv-stat-value { font-size: 14pt !important; }
    .uv-card {
      border: 1px solid #e2e8f0 !important;
      box-shadow: none !important;
      padding: 12px !important;
      margin-bottom: 15px !important;
      page-break-inside: avoid;
    }
    .uv-charts {
      grid-template-columns: 1fr 1fr !important;
      gap: 10px !important;
    }
    .uv-chart-container { height: 200px !important; }
    .uv-table th, .uv-table td {
      padding: 6px 8px !important;
      font-size: 9pt !important;
      border: 1px solid #cbd5e1 !important;
    }
    .uv-table th {
      background-color: #f1f5f9 !important;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }
    .uv-badge {
      border: 1px solid #94a3b8 !important;
      background: transparent !important;
      color: #000 !important;
    }
  }

  @media (max-width: 768px) {
    .uv-charts { grid-template-columns: 1fr; }
    .uv-wrap { padding: 16px; }
  }
  /* ═══ طباعة التبويب الحالي فقط ═══ */
  @media print {
    .uv-actions, .uv-select, .uv-tabs, .admin-sidebar, .admin-overlay,
    .admin-header, .admin-bottom-nav, .admin-menu-btn, .toast-container,
    #adminMenuBtn, #adminSidebar, #adminOverlay, button {
      display: none !important;
    }
    .uv-main, .uv-wrap { padding: 0 !important; margin: 0 !important; max-width: 100% !important; }
    body { background: #fff !important; font-size: 11pt; }
    
    /* إخفاء التبويبات غير النشطة فقط */
    .uv-panel { display: none !important; }
    .uv-panel.active { display: block !important; }
    
    .uv-card { break-inside: avoid; box-shadow: none !important; border: 1px solid #ccc !important; }
    .uv-table { width: 100%; border-collapse: collapse !important; }
    .uv-table th, .uv-table td { border: 1px solid #ddd !important; padding: 6px !important; }
    th { background: #f3f4f6 !important; -webkit-print-color-adjust: exact; }
    canvas { max-width: 100% !important; max-height: 250px !important; }
    @page { margin: 10mm; size: A4 portrait; }
  }

<style>
  /* تحسينات الواجهة الحديثة */
  .uv-wrap { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f8fafc; padding: 20px; border-radius: 12px; }
  
  /* كروت الإحصائيات */
  .uv-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px; }
  .uv-stat { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; display: flex; align-items: center; gap: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: transform 0.2s; }
  .uv-stat:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
  .uv-stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; background: #eff6ff; color: #2563eb; }
  .uv-stat-value { font-size: 1.4rem; font-weight: 700; color: #0f172a; margin-top: 2px; }
  .uv-stat-label { font-size: 0.85rem; color: #64748b; font-weight: 500; }

  /* شريط التبويبات */
  .uv-tabs { display: flex; gap: 8px; border-bottom: 2px solid #e2e8f0; margin-bottom: 20px; }
  .uv-tab { padding: 10px 18px; border: none; background: transparent; font-size: 0.95rem; font-weight: 600; color: #64748b; cursor: pointer; border-bottom: 3px solid transparent; margin-bottom: -2px; }
  .uv-tab.active { color: #2563eb; border-bottom-color: #2563eb; }

  /* الجداول */
  .uv-table-wrapper { background: #fff; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; }
  .uv-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; text-align: right; }
  .uv-table th { background: #f8fafc; padding: 12px 16px; color: #475569; font-weight: 600; border-bottom: 1px solid #e2e8f0; }
  .uv-table td { padding: 12px 16px; border-bottom: 1px solid #f1f5f9; color: #334155; }
  .uv-table tr:nth-child(even) td { background-color: #f8fafc; }
  .uv-table tr:hover td { background-color: #f1f5f9; }

  /* الأزرار والفلاتر */
  .uv-actions { display: flex; gap: 10px; align-items: center; justify-content: space-between; margin-bottom: 20px; background: #fff; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0; }
  .uv-btn { padding: 8px 16px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
  .uv-btn.print { background: #2563eb; color: #fff; }
  .uv-btn.print:hover { background: #1d4ed8; }

<style>
  /* تحسينات وضوح الواجهة */
  .uv-help-banner {
    background: #f0f9ff;
    border-right: 4px solid #0284c7;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 0.9rem;
    color: #0369a1;
  }
  
  .uv-card-header {
    margin-bottom: 15px;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 10px;
  }
  .uv-card-title { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0; }
  .uv-card-sub { font-size: 0.82rem; color: #64748b; margin-top: 3px; }

  /* شارات حالة المخزون */
  .badge-stock-low { background: #fee2e2; color: #dc2626; padding: 3px 8px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; }
  .badge-stock-ok { background: #dcfce7; color: #16a34a; padding: 3px 8px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; }
  .badge-stagnant { background: #fef3c7; color: #d97706; padding: 3px 8px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; }

  .uv-table th { background: #f8fafc; color: #334155; font-weight: 700; }
</style>

</style>

</style>
@endpush

@section('content')
<div class="uv-wrap">

  <div class="uv-header">
    <div>
      <h1 class="uv-title">📊 تقرير تحليلات المتغيرات (الأصناف)</h1>
      <div class="uv-sub">
        @if($selectedProduct)
          عرض خاص بالمنتج: <strong>{{ $selectedProduct->name }}</strong>
        @else
          تقرير شامل لكل المنتجات والمتغيرات
        @endif
        | تاريخ التقرير: {{ date('Y-m-d') }}
      </div>
    </div>
    <div class="uv-actions">
      <form method="GET" action="{{ route('variants.analytics.index') }}" style="display:inline;">
        <select name="product" class="uv-select" onchange="this.form.submit()">
          <option value="">🏠 كل المنتجات</option>
          @foreach($productsWithVariants as $p)
            <option value="{{ $p->id }}" {{ $selectedProductId == $p->id ? 'selected' : '' }}>
              {{ $p->name }}
            </option>
          @endforeach
        </select>
      </form>
      <button class="uv-btn print" onclick="window.print()">
        🖨️ طباعة A4
      </button>
      <a href="{{ route('variants.analytics.export.details', $selectedProductId ? ['product' => $selectedProductId] : []) }}" class="uv-btn excel">
        📥 تصدير Excel (CSV)
      </a>
    </div>
  </div>

  <div class="uv-stats">
    <div class="uv-stat">
      <div class="uv-stat-icon orange">📦</div>
      <div>
        <div class="uv-stat-label">إجمالي الأصناف</div>
        <div class="uv-stat-value">{{ number_format($stats['total_variants']) }}</div>
      </div>
    </div>
    <div class="uv-stat">
      <div class="uv-stat-icon blue">🏪</div>
      <div>
        <div class="uv-stat-label">المخزون الكلي</div>
        <div class="uv-stat-value">{{ number_format($stats['total_stock']) }}</div>
      </div>
    </div>
    <div class="uv-stat">
      <div class="uv-stat-icon green">🛒</div>
      <div>
        <div class="uv-stat-label">الكمية المباعة</div>
        <div class="uv-stat-value">{{ number_format($stats['total_sold']) }}</div>
      </div>
    </div>
    <div class="uv-stat">
      <div class="uv-stat-icon purple">💰</div>
      <div>
        <div class="uv-stat-label">الإيرادات</div>
        <div class="uv-stat-value">{{ number_format($stats['total_revenue']) }} ر.ي</div>
      </div>
    </div>
  </div>

  <div class="uv-tabs">
    <button class="uv-tab active" onclick="switchTab(event, 'tab-overall')">🏠 نظرة شاملة</button>
    <button class="uv-tab" onclick="switchTab(event, 'tab-details')">📋 تفاصيل الأصناف</button>
  </div>

  <div class="uv-panel active" id="tab-overall">
    <div class="uv-charts">
      <div class="uv-card">
        <h2 class="uv-card-title">🎨 المبيعات حسب اللون</h2>
        @if(count($byColor) > 0)
          <div class="uv-chart-container"><canvas id="chartColor"></canvas></div>
        @else
          <div style="text-align:center;padding:40px;color:#9ca3af;">لا توجد بيانات</div>
        @endif
      </div>
      <div class="uv-card">
        <h2 class="uv-card-title">📏 المبيعات حسب الحجم</h2>
        @if(count($bySize) > 0)
          <div class="uv-chart-container"><canvas id="chartSize"></canvas></div>
        @else
          <div style="text-align:center;padding:40px;color:#9ca3af;">لا توجد بيانات</div>
        @endif
      </div>
    </div>

    @if(count($stagnant) > 0)
    <div class="uv-card">
      <div class="uv-card-head">
        <h2 class="uv-card-title">💤 الراكدة — بحاجة لتحفيز</h2>
        <span class="uv-card-badge" style="background:#fee2e2;color:#991b1b;border-color:#fca5a5;">{{ count($stagnant) }} صنف</span>
      </div>
      <p class="uv-card-desc">أصناف متوفرة في المخزون لكن لم تُبع بعد — راجع أسعارها أو أطلق عليها عرضاً</p>
      <div class="uv-table-wrap">
        <table class="uv-table">
          <thead>
            <tr><th>#</th><th>المنتج</th><th>اللون</th><th>الحجم</th><th>المخزون</th></tr>
          </thead>
          <tbody>
            @foreach($stagnant as $i => $s)
            <tr>
              <td>{{ $i + 1 }}</td>
              <td>{{ $s['product_name'] }}</td>
              <td>{{ $s['color'] ?: '—' }}</td>
              <td>{{ $s['size'] ?: '—' }}</td>
              <td><strong>{{ $s['stock'] }}</strong></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
  </div>

  <div class="uv-panel" id="tab-details">
    <div class="uv-card">
      <div class="uv-card-head">
        <h2 class="uv-card-title">📋 أداء كل صنف</h2>
        <span class="uv-card-badge">{{ count($variantsList) }} صنف</span>
      </div>
      <p class="uv-card-desc">تفاصيل كاملة: المخزون، الطلبات، الكمية المباعة، السعر، الإيرادات</p>
      <div class="uv-card-mini">
        <div class="uv-card-mini-item">📦 المخزون: <strong>{{ number_format($stats['total_stock']) }}</strong></div>
        <div class="uv-card-mini-item">🛒 المبيعات: <strong>{{ number_format($stats['total_sold']) }}</strong></div>
        <div class="uv-card-mini-item">💰 الإيرادات: <strong>{{ number_format($stats['total_revenue']) }} ر.ي</strong></div>
      </div>
      <div class="uv-table-wrap">
        <table class="uv-table">
          <thead>
            <tr>
              <th>#</th>
              @if(!$selectedProduct)<th>المنتج</th>@endif
              <th>اللون</th>
              <th>الحجم</th>
              <th>الرمز</th>
              <th>المخزون</th>
              <th>الطلبات</th>
              <th>الكمية المباعة</th>
              <th>النسبة %</th>
              <th>السعر</th>
              <th>الإيرادات</th>
              <th>الحالة</th>
            </tr>
          </thead>
          <tbody>
            @php $totalQty = array_sum(array_column($variantsList, 'qty_sold')) ?: 1; @endphp
            @forelse($variantsList as $i => $v)
            <tr>
              <td>{{ $i + 1 }}</td>
              @if(!$selectedProduct)<td>{{ $v['product_name'] }}</td>@endif
              <td>{{ $v['color'] ?: '—' }}</td>
              <td>{{ $v['size'] ?: '—' }}</td>
              <td style="color:#6b7280;font-size:11px;">{{ $v['sku'] ?: '—' }}</td>
              <td>
                @if($v['stock'] === 0)
                  <span class="uv-badge out">نفذ</span>
                @elseif($v['stock'] <= 3)
                  <span class="uv-badge low">{{ $v['stock'] }}</span>
                @else
                  <span class="uv-badge ok">{{ $v['stock'] }}</span>
                @endif
              </td>
              <td>{{ $v['orders_count'] }}</td>
              <td><strong>{{ $v['qty_sold'] }}</strong></td>
              <td>{{ round(($v['qty_sold'] / $totalQty) * 100, 1) }}%</td>
              <td>{{ number_format($v['price']) }} ر.ي</td>
              <td>{{ number_format($v['revenue']) }} ر.ي</td>
              <td>
                @if($v['is_active'])
                  <span class="uv-badge ok">نشط</span>
                @else
                  <span class="uv-badge inactive">معطل</span>
                @endif
              </td>
            </tr>
            @empty
            <tr><td colspan="12" style="text-align:center;padding:40px;color:#9ca3af;">لا توجد بيانات</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>



</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
  function switchTab(ev, tabId) {
    ev.preventDefault();
    document.querySelectorAll('.uv-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.uv-panel').forEach(p => p.classList.remove('active'));
    ev.currentTarget.classList.add('active');
    document.getElementById(tabId).classList.add('active');
    if (tabId === 'tab-overall' && window.chartColorRef) {
      setTimeout(() => {
        window.chartColorRef.resize();
        window.chartSizeRef.resize();
      }, 50);
    }
  }

  const colors = ['#f97316','#3b82f6','#10b981','#8b5cf6','#ef4444','#eab308','#ec4899','#14b8a6','#6366f1','#84cc16'];

  @if(count($byColor) > 0)
  window.chartColorRef = new Chart(document.getElementById('chartColor'), {
    type: 'doughnut',
    data: {
      labels: @json(array_column($byColor, 'color')),
      datasets: [{
        data: @json(array_column($byColor, 'qty')),
        backgroundColor: colors,
        borderWidth: 2,
        borderColor: '#fff',
      }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
  });
  @endif

  @if(count($bySize) > 0)
  window.chartSizeRef = new Chart(document.getElementById('chartSize'), {
    type: 'bar',
    data: {
      labels: @json(array_column($bySize, 'size')),
      datasets: [{
        label: 'الكمية',
        data: @json(array_column($bySize, 'qty')),
        backgroundColor: '#f97316',
        borderRadius: 8,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });
  @endif

  </script>
@endpush
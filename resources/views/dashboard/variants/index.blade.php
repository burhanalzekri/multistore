@extends('layouts.app')
@section('title', 'إدارة المتغيرات')
@section('page-title', '📊 إدارة المتغيرات')
@section('page-subtitle', 'تحكم كامل بالمتغيرات (ألوان / مقاسات) عبر كل متاجرك')

@section('content')

<style>
  .var-wrap { max-width: 1280px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; }

  /* ═══ Stats Cards ═══ */
  .var-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
  }
  .var-stat {
    background: #fff;
    border-radius: 16px;
    padding: 16px 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 12px rgba(15,23,42,0.03);
    display: flex; align-items: center; gap: 12px;
    transition: .2s;
  }
  .var-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(15,23,42,0.08); }
  .var-stat-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: grid; place-items: center;
    font-size: 20px;
    flex-shrink: 0;
  }
  .var-stat-icon.blue  { background: #eff6ff; color: #1d4ed8; }
  .var-stat-icon.green { background: #f0fdf4; color: #16a34a; }
  .var-stat-icon.amber { background: #fff7ed; color: #d97706; }
  .var-stat-icon.red   { background: #fef2f2; color: #dc2626; }
  .var-stat-body { min-width: 0; }
  .var-stat-value { font-size: 20px; font-weight: 900; color: #0f172a; line-height: 1.1; }
  .var-stat-label { font-size: 11px; font-weight: 800; color: #64748b; margin-top: 2px; }

  /* ═══ Filters Bar ═══ */
  .var-filters {
    background: #fff;
    border-radius: 16px;
    padding: 14px 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 12px rgba(15,23,42,0.03);
    display: flex; gap: 10px; flex-wrap: wrap; align-items: center;
  }
  .var-search {
    flex: 1; min-width: 180px;
    display: flex; align-items: center; gap: 8px;
    padding: 10px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    background: #f8fafc;
    transition: .2s;
  }
  .var-search:focus-within { border-color: #f59e0b; background: #fff; box-shadow: 0 0 0 4px rgba(245,158,11,0.1); }
  .var-search input {
    flex: 1; border: 0; outline: 0; background: transparent;
    font-size: 13px; font-weight: 700; font-family: inherit; color: #0f172a;
  }
  .var-search input::placeholder { color: #94a3b8; }

  .var-select {
    padding: 10px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 13px; font-weight: 800;
    background: #fff; cursor: pointer;
    font-family: inherit;
    color: #334155;
    outline: none;
    transition: .2s;
    min-width: 110px;
  }
  .var-select:focus { border-color: #f59e0b; }

  .var-toggle {
    padding: 10px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 12px; font-weight: 800;
    background: #fff; cursor: pointer;
    font-family: inherit;
    color: #334155;
    transition: .2s;
    display: inline-flex; align-items: center; gap: 6px;
  }
  .var-toggle:hover { border-color: #f59e0b; color: #d97706; }
  .var-toggle.active { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; border-color: transparent; }

  .var-btn-export {
    padding: 10px 16px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
    border: 0; border-radius: 12px;
    font-size: 12px; font-weight: 900;
    cursor: pointer; font-family: inherit;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 6px;
    box-shadow: 0 4px 12px rgba(16,185,129,0.25);
    transition: .2s;
  }
  .var-btn-export:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(16,185,129,0.35); }

  /* ═══ Table ═══ */
  .var-table-wrap {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px -8px rgba(15,23,42,0.08);
    overflow: hidden;
  }
  .var-table {
    width: 100%; border-collapse: collapse;
  }
  .var-table thead {
    background: #f8fafc;
    border-bottom: 1.5px solid #e2e8f0;
  }
  .var-table th {
    padding: 14px 14px;
    text-align: right;
    font-size: 11px; font-weight: 900;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    white-space: nowrap;
  }
  .var-table td {
    padding: 12px 14px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px; font-weight: 700;
    color: #334155;
    vertical-align: middle;
  }
  .var-table tbody tr:hover { background: #f8fafc; }
  .var-table tbody tr:last-child td { border-bottom: 0; }

  .var-checkbox {
    width: 18px; height: 18px;
    accent-color: #f59e0b;
    cursor: pointer;
  }

  .var-color-dot {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 10px;
    background: #f1f5f9;
    border-radius: 8px;
    font-size: 11px; font-weight: 900;
    color: #334155;
  }
  .var-color-swatch {
    width: 12px; height: 12px;
    border-radius: 50%;
    border: 1.5px solid rgba(0,0,0,0.1);
    flex-shrink: 0;
  }

  .var-size-chip {
    display: inline-block;
    padding: 4px 10px;
    background: #eff6ff;
    color: #1d4ed8;
    border-radius: 8px;
    font-size: 11px; font-weight: 900;
  }

  .var-stock-badge {
    display: inline-block;
    min-width: 42px;
    padding: 5px 10px;
    text-align: center;
    border-radius: 10px;
    font-size: 12px; font-weight: 900;
  }
  .var-stock-badge.ok   { background: #f0fdf4; color: #16a34a; }
  .var-stock-badge.low  { background: #fff7ed; color: #d97706; }
  .var-stock-badge.out  { background: #fef2f2; color: #dc2626; }

  .var-sku {
    font-family: monospace;
    font-size: 10px;
    color: #94a3b8;
    font-weight: 700;
  }

  .var-actions {
    display: flex; gap: 6px; justify-content: flex-end;
  }
  .var-action-btn {
    width: 32px; height: 32px;
    border: 0; border-radius: 8px;
    display: grid; place-items: center;
    cursor: pointer;
    font-size: 14px;
    transition: .2s;
    font-family: inherit;
  }
  .var-action-btn.edit { background: #eff6ff; color: #1d4ed8; }
  .var-action-btn.edit:hover { background: #dbeafe; }
  .var-action-btn.del  { background: #fef2f2; color: #dc2626; }
  .var-action-btn.del:hover { background: #fee2e2; }

  /* ═══ Bulk bar ═══ */
  .var-bulk-bar {
    display: none;
    position: sticky;
    top: 8px;
    z-index: 30;
    background: linear-gradient(135deg, #17202b, #0f172a);
    color: #fff;
    padding: 12px 18px;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(15,23,42,0.3);
    display: none; align-items: center; gap: 12px;
  }
  .var-bulk-bar.show { display: flex; }
  .var-bulk-bar strong { font-size: 13px; font-weight: 900; }
  .var-bulk-btn {
    padding: 8px 14px;
    border-radius: 10px;
    border: 0;
    font-size: 12px; font-weight: 900;
    cursor: pointer;
    font-family: inherit;
    transition: .2s;
  }
  .var-bulk-btn.green { background: #16a34a; color: #fff; }
  .var-bulk-btn.red   { background: #dc2626; color: #fff; }
  .var-bulk-btn.amber { background: #f59e0b; color: #fff; }

  /* ═══ Empty state ═══ */
  .var-empty {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
  }
  .var-empty-icon { font-size: 60px; margin-bottom: 12px; }
  .var-empty-title { font-size: 16px; font-weight: 900; color: #475569; margin-bottom: 6px; }
  .var-empty-desc { font-size: 13px; font-weight: 700; }

  /* ═══ Modal ═══ */
  .var-modal {
    display: none;
    position: fixed; inset: 0;
    background: rgba(15,23,42,0.65);
    backdrop-filter: blur(6px);
    z-index: 1000;
    align-items: center; justify-content: center;
    padding: 20px;
  }
  .var-modal.show { display: flex; }
  .var-modal-box {
    background: #fff;
    border-radius: 20px;
    padding: 24px;
    max-width: 460px;
    width: 100%;
    box-shadow: 0 25px 70px rgba(0,0,0,0.3);
    animation: varFade .25s ease;
  }
  @keyframes varFade {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .var-modal-head {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 18px;
  }
  .var-modal-title {
    font-size: 16px; font-weight: 900; color: #0f172a;
    display: flex; align-items: center; gap: 8px;
  }
  .var-modal-close {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: #f1f5f9;
    border: 0; cursor: pointer;
    font-size: 18px; color: #64748b;
  }
  .var-modal-close:hover { background: #e2e8f0; }

  .var-modal-field { margin-bottom: 14px; }
  .var-modal-label {
    display: block;
    font-size: 12px; font-weight: 900;
    color: #475569;
    margin-bottom: 6px;
  }
  .var-modal-input {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px; font-weight: 700;
    font-family: inherit;
    color: #0f172a;
    outline: none;
    transition: .2s;
    background: #fff;
  }
  .var-modal-input:focus { border-color: #f59e0b; box-shadow: 0 0 0 4px rgba(245,158,11,0.12); }

  .var-modal-switch {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 14px;
    background: #f8fafc;
    border-radius: 12px;
    cursor: pointer;
    border: 1.5px solid #e2e8f0;
  }
  .var-modal-switch input { width: 18px; height: 18px; accent-color: #16a34a; cursor: pointer; }
  .var-modal-switch span { font-size: 13px; font-weight: 800; color: #334155; }

  .var-modal-footer {
    display: flex; gap: 10px;
    margin-top: 20px;
  }
  .var-modal-btn {
    flex: 1;
    padding: 12px;
    border: 0;
    border-radius: 12px;
    font-size: 13px; font-weight: 900;
    font-family: inherit;
    cursor: pointer;
    transition: .2s;
  }
  .var-modal-btn.cancel { background: #f1f5f9; color: #475569; }
  .var-modal-btn.save {
    flex: 2;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    box-shadow: 0 8px 20px rgba(217,119,6,0.25);
  }
  .var-modal-btn.save:hover { transform: translateY(-1px); }
  .var-modal-btn.save:disabled { opacity: .6; cursor: wait; }

  /* ═══ Pagination ═══ */
  .var-pagination {
    display: flex; justify-content: center;
    margin-top: 20px;
  }
  .var-pagination nav { display: flex; gap: 4px; }
  .var-pagination a, .var-pagination span {
    min-width: 34px; height: 34px;
    padding: 0 10px;
    border-radius: 10px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 900;
    text-decoration: none;
    color: #475569;
    background: #fff;
    border: 1px solid #e2e8f0;
    transition: .2s;
  }
  .var-pagination a:hover { border-color: #f59e0b; color: #d97706; }
  .var-pagination .active span { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; border-color: transparent; }
  .var-pagination .disabled span { opacity: .4; cursor: not-allowed; }

  @media (max-width: 760px) {
    .var-stats { grid-template-columns: repeat(2, 1fr); }
    .var-table-wrap { overflow-x: auto; }
    .var-table { min-width: 720px; }
    .var-filters { padding: 12px; }
  }

  .var-stat.clickable { cursor: pointer; position: relative; }
  .var-stat.clickable::after {
    content: '↗';
    position: absolute;
    top: 10px; left: 12px;
    font-size: 12px;
    color: #cbd5e1;
    font-weight: 900;
    transition: .2s;
  }
  .var-stat.clickable:hover::after { color: #f59e0b; transform: translate(2px, -2px); }
  .var-stat.clickable:active { transform: scale(0.98); }

  /* Modal تفاصيل الإحصائيات */
  .stat-modal-list {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 4px;
    background: #f8fafc;
  }
  .stat-row {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 14px;
    background: #fff;
    border-radius: 12px;
    margin-bottom: 6px;
    border: 1px solid #f1f5f9;
    transition: .2s;
  }
  .stat-row:last-child { margin-bottom: 0; }
  .stat-row:hover { border-color: #fde68a; background: #fffdf5; }
  .stat-row-thumb {
    width: 42px; height: 42px;
    border-radius: 10px;
    overflow: hidden;
    background: #f1f5f9;
    flex-shrink: 0;
    display: grid; place-items: center;
    font-size: 18px;
  }
  .stat-row-thumb img { width: 100%; height: 100%; object-fit: cover; }
  .stat-row-body { flex: 1; min-width: 0; }
  .stat-row-title { font-size: 13px; font-weight: 900; color: #0f172a; line-height: 1.4; }
  .stat-row-meta { font-size: 11px; font-weight: 700; color: #64748b; margin-top: 2px; }
  .stat-row-value {
    font-size: 15px; font-weight: 900;
    color: #d97706;
    flex-shrink: 0;
  }
  .stat-empty {
    text-align: center;
    padding: 30px 20px;
    color: #94a3b8;
    font-size: 13px;
    font-weight: 700;
  }

</style>

<div class="var-wrap">

  {{-- 📈 إحصائيات تفاعلية --}}
  <div class="var-stats">
    <div class="var-stat clickable" onclick="openStatModal('total')">
      <div class="var-stat-icon blue">🎨</div>
      <div class="var-stat-body">
        <div class="var-stat-value">{{ number_format($stats['total']) }}</div>
        <div class="var-stat-label">إجمالي المتغيرات</div>
      </div>
    </div>
    <div class="var-stat clickable" onclick="openStatModal('stock')">
      <div class="var-stat-icon green">📦</div>
      <div class="var-stat-body">
        <div class="var-stat-value">{{ number_format($stats['stock_sum']) }}</div>
        <div class="var-stat-label">إجمالي المخزون</div>
      </div>
    </div>
    <div class="var-stat clickable" onclick="openStatModal('products')">
      <div class="var-stat-icon amber">🛍️</div>
      <div class="var-stat-body">
        <div class="var-stat-value">{{ number_format($stats['products_count']) }}</div>
        <div class="var-stat-label">منتجات لها متغيرات</div>
      </div>
    </div>
    <div class="var-stat clickable" onclick="openStatModal('low')">
      <div class="var-stat-icon red">⚠️</div>
      <div class="var-stat-body">
        <div class="var-stat-value">{{ number_format($stats['low_stock']) }}</div>
        <div class="var-stat-label">مخزون منخفض (≤ 3)</div>
      </div>
    </div>
  </div>

  {{-- 🔍 الفلاتر --}}
  <form method="GET" action="{{ route('variants.index') }}" class="var-filters" id="filterForm" autocomplete="off">
    <div class="var-search">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث بـ SKU، الباركوود، اللون، المقاس، أو اسم المنتج...">
    </div>

    <select name="product_id" class="var-select">
      <option value="">كل المنتجات</option>
      @foreach($products as $p)
        <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>{{ Str::limit($p->name, 30) }}</option>
      @endforeach
    </select>

    @if($colorsList->count())
    <select name="color" class="var-select">
      <option value="">كل الألوان</option>
      @foreach($colorsList as $c)
        <option value="{{ $c }}" {{ request('color') === $c ? 'selected' : '' }}>{{ $c }}</option>
      @endforeach
    </select>
    @endif

    @if($sizesList->count())
    <select name="size" class="var-select">
      <option value="">كل المقاسات</option>
      @foreach($sizesList as $s)
        <option value="{{ $s }}" {{ request('size') === $s ? 'selected' : '' }}>{{ $s }}</option>
      @endforeach
    </select>
    @endif

    <div id="liveSearchIndicator" class="var-toggle" style="background:#f0fdf4;color:#16a34a;border-color:#bbf7d0;display:none;">
      ⚡ يُحدّث...
    </div>

    @if(request()->hasAny(['search','product_id','color','size','low_stock','inactive','no_stock']))
      <a href="{{ route('variants.index') }}" class="var-toggle">✕ مسح</a>
    @endif

    <a href="{{ route('variants.export') }}?{{ http_build_query(request()->all()) }}" class="var-btn-export">
      📥 CSV
    </a>
  </form>

  {{-- 🎯 شريط الإجراءات الجماعية --}}
  <div id="bulkBar" class="var-bulk-bar">
    <strong><span id="bulkCount">0</span> محدد</strong>
    <div style="flex:1;"></div>
    <button type="button" class="var-bulk-btn green" onclick="bulkAction('activate')">✅ تفعيل</button>
    <button type="button" class="var-bulk-btn amber" onclick="bulkAction('deactivate')">⏸️ تعطيل</button>
    <button type="button" class="var-bulk-btn red" onclick="bulkAction('delete')">🗑️ حذف</button>
  </div>

  {{-- 📋 الجدول --}}
  <div class="var-table-wrap">
    @if($variants->count() > 0)
      <table class="var-table">
        <thead>
          <tr>
            <th style="width:40px;"><input type="checkbox" id="checkAll" class="var-checkbox" onchange="toggleAll(this)"></th>
            <th>المنتج</th>
            <th>🎨 اللون</th>
            <th>📏 المقاس</th>
            <th>📦 المخزون</th>
            <th>SKU</th>
            <th>الحالة</th>
            <th style="text-align:left;">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @foreach($variants as $v)
            <tr data-id="{{ $v->id }}">
              <td><input type="checkbox" class="var-checkbox row-check" value="{{ $v->id }}" onchange="updateBulkBar()"></td>
              <td style="max-width:220px;">
                @if($v->product)
                  <a href="/dashboard/products/{{ $v->product->id }}/edit" style="color:#0f172a;text-decoration:none;font-weight:800;">
                    {{ Str::limit($v->product->name, 40) }}
                  </a>
                @else
                  <span style="color:#94a3b8;">منتج محذوف</span>
                @endif
              </td>
              <td>
                @if($v->color)
                  <span class="var-color-dot">
                    @if($v->color_hex)
                      <span class="var-color-swatch" style="background:{{ $v->color_hex }}"></span>
                    @endif
                    {{ $v->color }}
                  </span>
                @else
                  <span style="color:#cbd5e1;">—</span>
                @endif
              </td>
              <td>
                @if($v->size)
                  <span class="var-size-chip">{{ $v->size }}</span>
                @else
                  <span style="color:#cbd5e1;">—</span>
                @endif
              </td>
              <td>
                <span class="var-stock-badge {{ $v->stock <= 0 ? 'out' : ($v->stock <= 3 ? 'low' : 'ok') }}">
                  {{ $v->stock }}
                </span>
              </td>
              <td><span class="var-sku">{{ $v->sku ?? '—' }}</span></td>
              <td>
                @if($v->is_active)
                  <span style="color:#16a34a;font-size:12px;font-weight:900;">● مفعّل</span>
                @else
                  <span style="color:#94a3b8;font-size:12px;font-weight:900;">● معطّل</span>
                @endif
              </td>
              <td>
                <div class="var-actions">
                  <button type="button" class="var-action-btn edit" title="تعديل" onclick="openEditModal({{ $v->id }}, {{ $v->stock }}, {{ $v->price ?? 0 }}, {{ $v->is_active ? 'true' : 'false' }})">
                    ✏️
                  </button>
                  <button type="button" class="var-action-btn del" title="حذف" onclick="deleteVariant({{ $v->id }})">
                    🗑️
                  </button>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <div class="var-empty">
        <div class="var-empty-icon">📊</div>
        <div class="var-empty-title">لا توجد متغيرات</div>
        <div class="var-empty-desc">
          @if(request()->hasAny(['search','product_id','color','size','low_stock','inactive','no_stock']))
            جرّب تعديل الفلاتر أو <a href="{{ route('variants.index') }}" style="color:#d97706;font-weight:900;">مسح البحث</a>
          @else
            لم يُضف أي منتج متغيرات بعد. ابدأ بإضافة متغيرات من صفحة تعديل أي منتج.
          @endif
        </div>
      </div>
    @endif
  </div>

  {{-- صفحات --}}
  @if($variants->hasPages())
    <div class="var-pagination">
      {{ $variants->links() }}
    </div>
  @endif

</div>


{{-- 📊 Modal تفاصيل الإحصائيات --}}
<div id="statModal" class="var-modal">
  <div class="var-modal-box" style="max-width:560px;">
    <div class="var-modal-head">
      <div class="var-modal-title">
        <span id="statModalIcon">📊</span>
        <span id="statModalTitle">تفاصيل</span>
      </div>
      <button type="button" class="var-modal-close" onclick="closeStatModal()">×</button>
    </div>

    <div id="statModalBody" class="stat-modal-list"></div>

    <div class="var-modal-footer">
      <button type="button" class="var-modal-btn cancel" onclick="closeStatModal()">إغلاق</button>
      <button type="button" id="statModalFilterBtn" class="var-modal-btn save" style="display:none;" onclick="applyStatFilter()">
        🎯 عرض في الجدول
      </button>
    </div>
  </div>
</div>

{{-- ✏️ Modal تعديل --}}
<div id="editModal" class="var-modal">
  <div class="var-modal-box">
    <div class="var-modal-head">
      <div class="var-modal-title">
        <span>✏️</span>
        <span>تعديل سريع</span>
      </div>
      <button type="button" class="var-modal-close" onclick="closeEditModal()">×</button>
    </div>

    <input type="hidden" id="editId">

    <div class="var-modal-field">
      <label class="var-modal-label">📦 المخزون</label>
      <input type="number" id="editStock" min="0" class="var-modal-input" placeholder="0">
    </div>

    <div class="var-modal-field">
      <label class="var-modal-label">💰 السعر (اختياري)</label>
      <input type="number" id="editPrice" min="0" step="0.01" class="var-modal-input" placeholder="اتركه فارغاً لاستخدام سعر المنتج">
    </div>

    <div class="var-modal-field">
      <label class="var-modal-switch">
        <input type="checkbox" id="editActive">
        <span>متغير مفعّل (يظهر للعملاء)</span>
      </label>
    </div>

    <div class="var-modal-footer">
      <button type="button" class="var-modal-btn cancel" onclick="closeEditModal()">إلغاء</button>
      <button type="button" id="saveEditBtn" class="var-modal-btn save" onclick="saveEdit()">💾 حفظ التعديلات</button>
    </div>
  </div>
</div>

<form id="deleteForm" method="POST" style="display:none;">
  @csrf
  @method('DELETE')
</form>

<form id="bulkForm" method="POST" action="{{ route('variants.bulk') }}" style="display:none;">
  @csrf
  <input type="hidden" name="action" id="bulkActionInput">
  <div id="bulkIdsContainer"></div>
</form>

<script>
  // ═══ Checkboxes ═══
  function toggleAll(el) {
    document.querySelectorAll('.row-check').forEach(c => c.checked = el.checked);
    updateBulkBar();
  }
  function updateBulkBar() {
    const ids = Array.from(document.querySelectorAll('.row-check:checked')).map(c => c.value);
    const bar = document.getElementById('bulkBar');
    document.getElementById('bulkCount').textContent = ids.length;
    if (ids.length > 0) bar.classList.add('show');
    else bar.classList.remove('show');
  }

  // ═══ Bulk Action ═══
  async function bulkAction(action) {
    const ids = Array.from(document.querySelectorAll('.row-check:checked')).map(c => parseInt(c.value));
    if (!ids.length) return;

    const labels = { activate: 'تفعيل', deactivate: 'تعطيل', delete: 'حذف' };
    if (!confirm(`هل تريد ${labels[action]} ${ids.length} متغير؟`)) return;

    const form = document.getElementById('bulkForm');
    document.getElementById('bulkActionInput').value = action;
    const container = document.getElementById('bulkIdsContainer');
    container.innerHTML = '';
    ids.forEach(id => {
      const inp = document.createElement('input');
      inp.type = 'hidden';
      inp.name = 'ids[]';
      inp.value = id;
      container.appendChild(inp);
    });
    form.submit();
  }

  // ═══ Modal ═══
  const CSRF = '{{ csrf_token() }}';

  function openEditModal(id, stock, price, isActive) {
    document.getElementById('editId').value = id;
    document.getElementById('editStock').value = stock;
    document.getElementById('editPrice').value = price || '';
    document.getElementById('editActive').checked = isActive;
    document.getElementById('editModal').classList.add('show');
    setTimeout(() => document.getElementById('editStock').focus(), 100);
  }
  function closeEditModal() {
    document.getElementById('editModal').classList.remove('show');
  }

  async function saveEdit() {
    const id = document.getElementById('editId').value;
    const btn = document.getElementById('saveEditBtn');
    btn.disabled = true;
    btn.textContent = '⏳ جاري الحفظ...';

    try {
      const resp = await fetch(`/dashboard/variants/${id}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          _method: 'PATCH',
          stock: parseInt(document.getElementById('editStock').value) || 0,
          price: document.getElementById('editPrice').value || null,
          is_active: document.getElementById('editActive').checked ? 1 : 0,
        }),
      });

      const data = await resp.json();
      if (data.success) {
        location.reload();
      } else {
        alert(data.message || 'خطأ في الحفظ');
        btn.disabled = false;
        btn.textContent = '💾 حفظ التعديلات';
      }
    } catch (e) {
      alert('خطأ في الاتصال: ' + e.message);
      btn.disabled = false;
      btn.textContent = '💾 حفظ التعديلات';
    }
  }

  // ═══ Delete ═══
  function deleteVariant(id) {
    if (!confirm('هل تريد حذف هذا المتغير؟')) return;
    const form = document.getElementById('deleteForm');
    form.action = `/dashboard/variants/${id}`;
    form.submit();
  }

  // ═══ ESC لإغلاق Modal ═══
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeEditModal();
  });

  // ═══ 🚀 فلترة مباشرة ═══
  (function() {
    const form = document.getElementById('filterForm');
    if (!form) return;

    const indicator = document.getElementById('liveSearchIndicator');
    let debounceTimer = null;
    let isFirstLoad = true;

    // نمنع submit الافتراضي
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      submitFilters();
    });

    function showIndicator() {
      if (indicator) indicator.style.display = 'inline-flex';
    }
    function hideIndicator() {
      if (indicator) indicator.style.display = 'none';
    }

    function submitFilters() {
      showIndicator();
      // نبني URL ونحدّث بدون إعادة تحميل كامل
      const params = new URLSearchParams(new FormData(form));
      // نحذف القيم الفارغة
      for (const [k, v] of [...params.entries()]) {
        if (!v) params.delete(k);
      }

      const newUrl = form.action + (params.toString() ? '?' + params.toString() : '');
      history.replaceState({}, '', newUrl);

      // نحمّل الصفحة بالكامل (أسرع من fetch+parse حالياً)
      window.location.href = newUrl;
    }

    // 1) قوائم select — تحديث فوري
    form.querySelectorAll('select').forEach(sel => {
      sel.addEventListener('change', submitFilters);
    });

    // 2) حقل البحث — debounce 600ms
    const searchInput = form.querySelector('input[name="search"]');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        showIndicator();
        debounceTimer = setTimeout(submitFilters, 600);
      });
      // Enter يرسل فوراً
      searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          clearTimeout(debounceTimer);
          submitFilters();
        }
      });
    }

    // 3) إذا عاد من الصفحة السابقة، نخفي المؤشر
    window.addEventListener('pageshow', function() {
      hideIndicator();
    });
  })();



  // ═══ 📊 Modal الإحصائيات ═══
  window.STATS_DATA = @json($statsModalData ?? ['products_list' => [], 'low_stock_list' => []]);

  window.openStatModal = function(type) {
    var modal = document.getElementById('statModal');
    var title = document.getElementById('statModalTitle');
    var icon = document.getElementById('statModalIcon');
    var body = document.getElementById('statModalBody');
    var filterBtn = document.getElementById('statModalFilterBtn');

    if (!modal) {
      alert('⚠️ Modal الإحصائيات غير موجود في الصفحة');
      return;
    }

    window._currentStatFilter = null;
    filterBtn.style.display = 'none';

    if (type === 'total') {
      icon.textContent = '🎨';
      title.textContent = 'كل المتغيرات';
      body.innerHTML = '<div class="stat-empty">📊 اضغط "عرض في الجدول" لعرض كل المتغيرات.</div>';
      filterBtn.style.display = 'inline-block';
      filterBtn.textContent = '🎯 عرض الكل في الجدول';
    }
    else if (type === 'stock') {
      icon.textContent = '📦';
      title.textContent = 'إجمالي المخزون لكل منتج';
      body.innerHTML = renderProductsListJS();
    }
    else if (type === 'products') {
      icon.textContent = '🛍️';
      title.textContent = 'المنتجات التي لها متغيرات';
      body.innerHTML = renderProductsListJS();
    }
    else if (type === 'low') {
      icon.textContent = '⚠️';
      title.textContent = 'متغيرات بمخزون منخفض (≤ 3)';
      body.innerHTML = renderLowStockJS();
      filterBtn.style.display = 'inline-block';
      filterBtn.textContent = '🎯 عرض في الجدول';
      window._currentStatFilter = 'low_stock';
    }

    modal.classList.add('show');
  };

  window.renderProductsListJS = function() {
    var data = window.STATS_DATA || {};
    var list = data.products_list || [];
    if (!list.length) return '<div class="stat-empty">لا توجد منتجات لها متغيرات</div>';

    return list.map(function(p) {
      return '<div class="stat-row" onclick="window.location=\'/dashboard/products/' + p.id + '/edit\'" style="cursor:pointer;">'
        + '<div class="stat-row-thumb">' + (p.image ? '<img src="' + p.image + '">' : '📦') + '</div>'
        + '<div class="stat-row-body">'
        + '<div class="stat-row-title">' + p.name + '</div>'
        + '<div class="stat-row-meta">🎨 ' + p.variants_count + ' متغير · 📏 ' + p.sizes + ' مقاس · 🎨 ' + p.colors + ' لون</div>'
        + '</div>'
        + '<div class="stat-row-value">' + p.stock_total + '</div>'
        + '</div>';
    }).join('');
  };

  window.renderLowStockJS = function() {
    var data = window.STATS_DATA || {};
    var list = data.low_stock_list || [];
    if (!list.length) return '<div class="stat-empty">✅ لا يوجد مخزون منخفض</div>';

    return list.map(function(v) {
      var isOut = v.stock <= 0;
      var bg = isOut ? '#fef2f2' : '#fff7ed';
      var border = isOut ? '#fecaca' : '#fed7aa';
      var thumbBg = isOut ? '#fee2e2' : '#ffedd5';
      var thumbColor = isOut ? '#dc2626' : '#d97706';
      var valueColor = isOut ? '#dc2626' : '#d97706';
      var emoji = isOut ? '🚫' : '⚠️';

      return '<div class="stat-row" onclick="window.location=\'/dashboard/products/' + v.product_id + '/edit\'" '
        + 'style="cursor:pointer;border-color:' + border + ';background:' + bg + ';">'
        + '<div class="stat-row-thumb" style="background:' + thumbBg + ';color:' + thumbColor + ';">' + emoji + '</div>'
        + '<div class="stat-row-body">'
        + '<div class="stat-row-title">' + v.product_name + '</div>'
        + '<div class="stat-row-meta">' + (v.color ? '🎨 ' + v.color : '') + (v.size ? ' · 📏 ' + v.size : '') + '</div>'
        + '</div>'
        + '<div class="stat-row-value" style="color:' + valueColor + ';">' + v.stock + (isOut ? ' (نفد)' : '') + '</div>'
        + '</div>';
    }).join('');
  };

  window.closeStatModal = function() {
    var m = document.getElementById('statModal');
    if (m) m.classList.remove('show');
    window._currentStatFilter = null;
  };

  window.applyStatFilter = function() {
    if (window._currentStatFilter === 'low_stock') {
      window.location = '/dashboard/variants?low_stock=1';
    } else {
      window.location = '/dashboard/variants';
    }
  };

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      if (typeof window.closeStatModal === 'function') window.closeStatModal();
    }
  });

</script>

@endsection

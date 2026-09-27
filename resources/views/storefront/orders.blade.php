@extends('layouts.app')

@section('title', 'طلباتي')
@section('page-title', '📦 طلباتي')
@section('page-subtitle', 'تابع كل طلباتك بسهولة')

@section('content')

<style>
  .ord-wrap {
    max-width: 900px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  /* ═══ Stats Cards ═══ */
  .ord-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
  }
  .ord-stat {
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: 16px;
    padding: 16px;
    box-shadow: 0 4px 12px rgba(15,23,42,0.04);
    transition: all .3s;
  }
  .ord-stat:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(15,23,42,0.08);
  }
  .ord-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    font-size: 18px;
    margin-bottom: 10px;
  }
  .ord-stat-icon.total    { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
  .ord-stat-icon.sum      { background: linear-gradient(135deg, #fef3c7, #fde68a); }
  .ord-stat-icon.pending  { background: linear-gradient(135deg, #fed7aa, #fdba74); }
  .ord-stat-icon.delivered{ background: linear-gradient(135deg, #d1fae5, #a7f3d0); }
  .ord-stat-value {
    font-size: 20px;
    font-weight: 900;
    color: #0f172a;
    line-height: 1.1;
  }
  .ord-stat-label {
    font-size: 11px;
    font-weight: 800;
    color: #64748b;
    margin-top: 4px;
  }

  /* ═══ Filters ═══ */
  .ord-filters {
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: 16px;
    padding: 14px 16px;
    box-shadow: 0 4px 12px rgba(15,23,42,0.04);
  }
  .ord-filters-label {
    font-size: 11px;
    font-weight: 900;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
  }
  .ord-chips {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-bottom: 12px;
  }
  .ord-chip {
    padding: 8px 16px;
    border-radius: 20px;
    background: #f5f5f5;
    border: 1.5px solid transparent;
    color: #4a4a4a;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    font-family: inherit;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all .2s;
  }
  .ord-chip:hover {
    background: #fff0eb;
    color: #e96b2c;
    border-color: #ffccbc;
  }
  .ord-chip.active {
    background: linear-gradient(135deg, #e96b2c, #d9541a);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(233,107,44,0.3);
  }
  .ord-chip .count {
    background: rgba(255,255,255,0.3);
    padding: 1px 7px;
    border-radius: 99px;
    font-size: 10px;
  }
  .ord-chip.active .count {
    background: rgba(0,0,0,0.15);
  }

  /* Custom date range */
  .ord-custom {
    display: none;
    grid-template-columns: 1fr auto 1fr auto;
    gap: 8px;
    align-items: center;
    margin-top: 10px;
    padding-top: 12px;
    border-top: 1px dashed #e5e7eb;
  }
  .ord-custom.show { display: grid; }
  .ord-custom input {
    padding: 10px 12px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    outline: none;
    font-family: inherit;
  }
  .ord-custom input:focus {
    border-color: #e96b2c;
    box-shadow: 0 0 0 3px rgba(233,107,44,0.12);
  }
  .ord-custom button {
    padding: 10px 18px;
    background: #e96b2c;
    color: #fff;
    border: 0;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 900;
    cursor: pointer;
    font-family: inherit;
  }

  /* ═══ Order Card ═══ */
  .ord-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }
  .ord-card {
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: 18px;
    padding: 18px;
    box-shadow: 0 4px 12px rgba(15,23,42,0.04);
    transition: all .3s;
    position: relative;
    overflow: hidden;
  }
  .ord-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 4px;
  }
  .ord-card.status-awaiting_payment::before { background: linear-gradient(180deg, #f59e0b, #d97706); }
  .ord-card.status-delivered::before        { background: linear-gradient(180deg, #10b981, #059669); }
  .ord-card.status-cancelled::before        { background: linear-gradient(180deg, #ef4444, #dc2626); }
  .ord-card.status-shipped::before          { background: linear-gradient(180deg, #3b82f6, #1d4ed8); }
  .ord-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(15,23,42,0.1);
    border-color: #e96b2c;
  }
  .ord-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 12px;
  }
  .ord-number {
    font-size: 15px;
    font-weight: 900;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .ord-date {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 700;
    margin-top: 3px;
  }
  .ord-status {
    padding: 5px 12px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 900;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
  .ord-status.awaiting_payment { background: #fff7ed; color: #c2410c; }
  .ord-status.delivered        { background: #f0fdf4; color: #15803d; }
  .ord-status.cancelled        { background: #fef2f2; color: #b91c1c; }
  .ord-status.shipped          { background: #eff6ff; color: #1d4ed8; }
  .ord-status.processing       { background: #faf5ff; color: #7c3aed; }
  .ord-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
  }

  .ord-info {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 12px;
    padding: 12px 0;
    border-top: 1px solid #f1f5f9;
    border-bottom: 1px solid #f1f5f9;
  }
  .ord-info-item {
    display: flex;
    flex-direction: column;
    gap: 3px;
  }
  .ord-info-label {
    font-size: 10px;
    font-weight: 900;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }
  .ord-info-value {
    font-size: 13px;
    font-weight: 800;
    color: #334155;
  }
  .ord-info-value.price {
    color: #e96b2c;
    font-size: 15px;
  }

  .ord-actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
    flex-wrap: wrap;
  }
  .ord-btn {
    padding: 10px 16px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 900;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all .2s;
    cursor: pointer;
    border: 0;
    font-family: inherit;
  }
  .ord-btn.primary {
    background: linear-gradient(135deg, #e96b2c, #d9541a);
    color: #fff;
    box-shadow: 0 4px 12px rgba(233,107,44,0.3);
  }
  .ord-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(233,107,44,0.4);
  }
  .ord-btn.ghost {
    background: #f8fafc;
    color: #475569;
    border: 1.5px solid #e5e7eb;
  }
  .ord-btn.ghost:hover {
    background: #fff;
    border-color: #e96b2c;
    color: #e96b2c;
  }

  /* ═══ Empty ═══ */
  .ord-empty {
    background: #fff;
    border: 1.5px dashed #e5e7eb;
    border-radius: 24px;
    padding: 60px 30px;
    text-align: center;
  }
  .ord-empty-icon { font-size: 72px; margin-bottom: 16px; }
  .ord-empty-title { font-size: 20px; font-weight: 900; color: #0f172a; margin-bottom: 8px; }
  .ord-empty-desc { font-size: 13px; color: #64748b; margin-bottom: 24px; }
  .ord-empty-btn {
    display: inline-block;
    padding: 14px 28px;
    background: linear-gradient(135deg, #e96b2c, #d9541a);
    color: #fff;
    border-radius: 14px;
    font-weight: 900;
    text-decoration: none;
    box-shadow: 0 10px 24px rgba(233,107,44,0.3);
  }

  /* Mobile */
  @media (max-width: 640px) {
    .ord-stats { grid-template-columns: repeat(2, 1fr); }
    .ord-info { grid-template-columns: 1fr 1fr; }
    .ord-info-item:last-child { grid-column: 1 / -1; }
    .ord-actions { flex-direction: column; }
    .ord-btn { width: 100%; justify-content: center; }
  }
</style>

@php
  $currentPeriod = $period ?? 'all';
  $currentStatus = request('status');
  $total = $stats['total'] ?? 0;
  $sum = $stats['sum'] ?? 0;
  $pending = $stats['pending'] ?? 0;
  $delivered = $stats['delivered'] ?? 0;
@endphp

<div class="ord-wrap">

  {{-- 📊 بطاقات الإحصائيات --}}
  <div class="ord-stats">
    <div class="ord-stat">
      <div class="ord-stat-icon total">📦</div>
      <div class="ord-stat-value">{{ number_format($total) }}</div>
      <div class="ord-stat-label">إجمالي الطلبات</div>
    </div>
    <div class="ord-stat">
      <div class="ord-stat-icon sum">💰</div>
      <div class="ord-stat-value">{{ number_format($sum) }}</div>
      <div class="ord-stat-label">إجمالي المبالغ (ر.ي)</div>
    </div>
    <div class="ord-stat">
      <div class="ord-stat-icon pending">⏳</div>
      <div class="ord-stat-value">{{ number_format($pending) }}</div>
      <div class="ord-stat-label">بانتظار الدفع</div>
    </div>
    <div class="ord-stat">
      <div class="ord-stat-icon delivered">✅</div>
      <div class="ord-stat-value">{{ number_format($delivered) }}</div>
      <div class="ord-stat-label">تم التسليم</div>
    </div>
  </div>

  {{-- 🎯 الفلاتر --}}
  <div class="ord-filters">
    <div class="ord-filters-label">📅 فلتر الفترة</div>
    <div class="ord-chips">
      <a href="?period=all" class="ord-chip {{ $currentPeriod === 'all' ? 'active' : '' }}">الكل</a>
      <a href="?period=today" class="ord-chip {{ $currentPeriod === 'today' ? 'active' : '' }}">اليوم</a>
      <a href="?period=yesterday" class="ord-chip {{ $currentPeriod === 'yesterday' ? 'active' : '' }}">أمس</a>
      <a href="?period=week" class="ord-chip {{ $currentPeriod === 'week' ? 'active' : '' }}">آخر 7 أيام</a>
      <a href="?period=month" class="ord-chip {{ $currentPeriod === 'month' ? 'active' : '' }}">آخر 30 يوم</a>
      <a href="?period=year" class="ord-chip {{ $currentPeriod === 'year' ? 'active' : '' }}">آخر سنة</a>
      <button type="button" class="ord-chip {{ $currentPeriod === 'custom' ? 'active' : '' }}" onclick="toggleCustom()">📅 مخصص</button>
    </div>

    <div class="ord-custom {{ $currentPeriod === 'custom' ? 'show' : '' }}" id="ordCustom">
      <input type="date" id="ordFrom" value="{{ request('from') }}" placeholder="من">
      <span style="color:#94a3b8;font-weight:900;">—</span>
      <input type="date" id="ordTo" value="{{ request('to') }}" placeholder="إلى">
      <button type="button" onclick="applyCustom()">تطبيق</button>
    </div>
  </div>

  {{-- 📋 قائمة الطلبات --}}
  @if($orders->count() > 0)
    <div class="ord-list">
      @foreach($orders as $order)
        @php
          $status = $order->status ?? 'awaiting_payment';
          $statusLabels = [
            'awaiting_payment' => ['⏳', 'بانتظار الدفع'],
            'delivered' => ['✅', 'تم التسليم'],
            'cancelled' => ['❌', 'ملغى'],
            'shipped' => ['🚚', 'تم الشحن'],
            'processing' => ['⚙️', 'قيد المعالجة'],
          ];
          $s = $statusLabels[$status] ?? ['📋', $status];
        @endphp
        <div class="ord-card status-{{ $status }}">
          <div class="ord-head">
            <div>
              <div class="ord-number">🆔 {{ $order->order_number }}</div>
              <div class="ord-date">📅 {{ $order->created_at->diffForHumans() }} · {{ $order->created_at->format('Y-m-d') }}</div>
            </div>
            <span class="ord-status {{ $status }}">
              <span class="ord-status-dot"></span>
              {{ $s[0] }} {{ $s[1] }}
            </span>
          </div>

          <div class="ord-info">
            <div class="ord-info-item">
              <div class="ord-info-label">العميل</div>
              <div class="ord-info-value">{{ $order->customer_name }}</div>
            </div>
            <div class="ord-info-item">
              <div class="ord-info-label">الهاتف</div>
              <div class="ord-info-value" dir="ltr">{{ $order->customer_phone }}</div>
            </div>
            <div class="ord-info-item">
              <div class="ord-info-label">الإجمالي</div>
              <div class="ord-info-value price">{{ number_format($order->total) }} ر.ي</div>
            </div>
          </div>

          <div class="ord-actions">
            <a href="/track" class="ord-btn primary">
              📦 تتبع الطلب
            </a>
            <a href="/product" class="ord-btn ghost">
              🛒 إعادة الطلب
            </a>
          </div>
        </div>
      @endforeach
    </div>

    <div style="margin-top: 20px;">
      {{ $orders->links() }}
    </div>
  @else
    <div class="ord-empty">
      <div class="ord-empty-icon">📭</div>
      <div class="ord-empty-title">لا توجد طلبات في هذه الفترة</div>
      <div class="ord-empty-desc">جرّب تغيير الفلتر أو تصفح المتجر لإضافة طلبات جديدة</div>
      <a href="/shop" class="ord-empty-btn">🛍️ تصفّح المتجر</a>
    </div>
  @endif

</div>

<script>
  function toggleCustom() {
    document.getElementById('ordCustom').classList.toggle('show');
  }

  function applyCustom() {
    var from = document.getElementById('ordFrom').value;
    var to = document.getElementById('ordTo').value;
    if (!from && !to) {
      alert('يرجى تحديد تاريخ واحد على الأقل');
      return;
    }
    var url = new URL(window.location.href);
    url.searchParams.set('period', 'custom');
    if (from) url.searchParams.set('from', from);
    if (to) url.searchParams.set('to', to);
    window.location.href = url.toString();
  }

  // إذا الفترة مخصصة → افتح تلقائياً
  @if($currentPeriod === 'custom')
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('ordCustom').classList.add('show');
    });
  @endif
</script>

@endsection

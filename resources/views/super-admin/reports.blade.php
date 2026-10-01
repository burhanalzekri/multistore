@extends('layouts.super-admin')
@section('title', 'تقارير المنصة')
@section('page-title', '📊 تقارير المنصة')
@section('page-subtitle', 'نظرة شاملة على كل المتاجر')

@section('content')

<!-- Totals -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:20px;">
  <div class="admin-card" style="padding:18px;border-right:4px solid #10b981;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">💰 إجمالي المبيعات</div>
    <div style="font-size:22px;font-weight:900;color:#10b981;margin-top:4px;">{{ number_format($totals['sales']) }}</div>
  </div>
  <div class="admin-card" style="padding:18px;border-right:4px solid #3b82f6;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">🛒 الطلبات</div>
    <div style="font-size:22px;font-weight:900;color:#3b82f6;margin-top:4px;">{{ $totals['orders'] }}</div>
  </div>
  <div class="admin-card" style="padding:18px;border-right:4px solid #f59e0b;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">🏪 المتاجر</div>
    <div style="font-size:22px;font-weight:900;color:#f59e0b;margin-top:4px;">{{ $totals['shops'] }}</div>
  </div>
  <div class="admin-card" style="padding:18px;border-right:4px solid #8b5cf6;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">📦 المنتجات</div>
    <div style="font-size:22px;font-weight:900;color:#8b5cf6;margin-top:4px;">{{ $totals['products'] }}</div>
  </div>
  <div class="admin-card" style="padding:18px;border-right:4px solid #ec4899;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">👥 المستخدمون</div>
    <div style="font-size:22px;font-weight:900;color:#ec4899;margin-top:4px;">{{ $totals['users'] }}</div>
  </div>
  <div class="admin-card" style="padding:18px;border-right:4px solid #06b6d4;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">📱 SMS</div>
    <div style="font-size:22px;font-weight:900;color:#06b6d4;margin-top:4px;">{{ $totals['sms'] }}</div>
  </div>
</div>

<!-- Chart -->
<div class="admin-card" style="padding:22px;margin-bottom:20px;">
  <h2 style="font-size:15px;font-weight:900;margin-bottom:20px;">📈 المبيعات — آخر 7 أيام (كل المتاجر)</h2>
  @php $max = max(array_column($chart, 'sales')) ?: 1; @endphp
  <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:6px;height:140px;">
    @foreach($chart as $c)
    <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:6px;">
      <div style="font-size:9px;font-weight:900;color:var(--text-muted);">{{ $c['sales'] > 0 ? number_format($c['sales']) : '' }}</div>
      <div style="width:100%;background:linear-gradient(to top,#3b82f6,#60a5fa);border-radius:8px 8px 0 0;height:{{ max(6, ($c['sales'] / $max) * 100) }}px;"></div>
      <div style="font-size:10px;color:var(--text-muted);font-weight:800;">{{ $c['day'] }}</div>
    </div>
    @endforeach
  </div>
</div>

<!-- Shop Leaderboard -->
<div class="admin-card">
  <div style="padding:18px 20px;border-bottom:1px solid var(--border);">
    <h2 style="font-size:16px;font-weight:900;">🏆 ترتيب المتاجر حسب المبيعات</h2>
  </div>
  @foreach($shopStats as $i => $stat)
  <div style="display:flex;align-items:center;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);">
    <div style="width:44px;height:44px;border-radius:12px;background:{{ $i === 0 ? 'linear-gradient(135deg,#fbbf24,#f97316)' : ($i === 1 ? 'linear-gradient(135deg,#94a3b8,#64748b)' : ($i === 2 ? 'linear-gradient(135deg,#d97706,#b45309)' : '#f1f5f9')) }};display:flex;align-items:center;justify-content:center;font-weight:900;color:white;font-size:16px;flex-shrink:0;">
      {{ $i + 1 }}
    </div>
    <div style="flex:1;min-width:150px;">
      <div style="font-weight:900;font-size:14px;color:var(--text);">{{ $stat['shop']->name }}</div>
      <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">
        📦 {{ $stat['products'] }} منتج • 👥 {{ $stat['users'] }} مستخدم
      </div>
    </div>
    <div style="display:flex;gap:14px;">
      <div style="text-align:center;">
        <div style="font-size:16px;font-weight:900;color:#3b82f6;">{{ $stat['orders'] }}</div>
        <div style="font-size:10px;color:var(--text-muted);">طلب</div>
      </div>
      <div style="text-align:center;">
        <div style="font-size:16px;font-weight:900;color:#10b981;">{{ number_format($stat['sales']) }}</div>
        <div style="font-size:10px;color:var(--text-muted);">ريال</div>
      </div>
    </div>
  </div>
  @endforeach
</div>

@endsection

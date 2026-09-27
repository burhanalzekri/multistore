@extends('layouts.app')
@section('title', 'إدارة المتاجر')
@section('page-title', '👑 إدارة المتاجر')
@section('page-subtitle', 'كل المتاجر في المنصة — عرض، تعديل، حذف')

@section('content')

{{-- ═══ الإحصائيات العامة ═══ --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:24px;">
  <div style="background:linear-gradient(135deg,#10b981,#059669);color:white;padding:18px;border-radius:16px;box-shadow:0 4px 16px rgba(16,185,129,0.25);">
    <div style="font-size:11px;opacity:0.9;font-weight:800;">🏪 إجمالي المتاجر</div>
    <div style="font-size:28px;font-weight:900;margin-top:4px;">{{ $stats['total_shops'] }}</div>
  </div>
  <div style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);color:white;padding:18px;border-radius:16px;box-shadow:0 4px 16px rgba(59,130,246,0.25);">
    <div style="font-size:11px;opacity:0.9;font-weight:800;">✅ نشطة</div>
    <div style="font-size:28px;font-weight:900;margin-top:4px;">{{ $stats['active_shops'] }}</div>
  </div>
  <div style="background:linear-gradient(135deg,#f59e0b,#d97706);color:white;padding:18px;border-radius:16px;box-shadow:0 4px 16px rgba(245,158,11,0.25);">
    <div style="font-size:11px;opacity:0.9;font-weight:800;">⏳ تجريبية</div>
    <div style="font-size:28px;font-weight:900;margin-top:4px;">{{ $stats['trial_shops'] }}</div>
  </div>
  <div style="background:linear-gradient(135deg,#ef4444,#b91c1c);color:white;padding:18px;border-radius:16px;box-shadow:0 4px 16px rgba(239,68,68,0.25);">
    <div style="font-size:11px;opacity:0.9;font-weight:800;">🚫 معطلة</div>
    <div style="font-size:28px;font-weight:900;margin-top:4px;">{{ $stats['suspended_shops'] }}</div>
  </div>
  <div style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);color:white;padding:18px;border-radius:16px;box-shadow:0 4px 16px rgba(139,92,246,0.25);">
    <div style="font-size:11px;opacity:0.9;font-weight:800;">📦 المنتجات</div>
    <div style="font-size:28px;font-weight:900;margin-top:4px;">{{ $stats['total_products'] }}</div>
  </div>
  <div style="background:linear-gradient(135deg,#06b6d4,#0891b2);color:white;padding:18px;border-radius:16px;box-shadow:0 4px 16px rgba(6,182,212,0.25);">
    <div style="font-size:11px;opacity:0.9;font-weight:800;">🛒 الطلبات</div>
    <div style="font-size:28px;font-weight:900;margin-top:4px;">{{ $stats['total_orders'] }}</div>
  </div>
  <div style="background:linear-gradient(135deg,#ec4899,#be185d);color:white;padding:18px;border-radius:16px;box-shadow:0 4px 16px rgba(236,72,153,0.25);">
    <div style="font-size:11px;opacity:0.9;font-weight:800;">💰 الإيرادات</div>
    <div style="font-size:24px;font-weight:900;margin-top:4px;">{{ number_format($stats['total_revenue']) }}</div>
    <div style="font-size:10px;opacity:0.8;">ريال</div>
  </div>
  <div style="background:linear-gradient(135deg,#64748b,#475569);color:white;padding:18px;border-radius:16px;box-shadow:0 4px 16px rgba(100,116,139,0.25);">
    <div style="font-size:11px;opacity:0.9;font-weight:800;">👥 المستخدمون</div>
    <div style="font-size:28px;font-weight:900;margin-top:4px;">{{ $stats['total_users'] }}</div>
  </div>
</div>

{{-- ═══ شريط الأدوات ═══ --}}
<div style="background:white;padding:16px 20px;border-radius:16px;margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;box-shadow:0 2px 12px rgba(0,0,0,0.05);">
  <div style="display:flex;gap:8px;flex-wrap:wrap;">
    <a href="?status=all" style="padding:8px 16px;border-radius:10px;background:{{ request('status', 'all') === 'all' ? '#f59e0b' : '#f1f5f9' }};color:{{ request('status', 'all') === 'all' ? 'white' : '#475569' }};text-decoration:none;font-weight:900;font-size:13px;">الكل</a>
    <a href="?status=active" style="padding:8px 16px;border-radius:10px;background:{{ request('status') === 'active' ? '#10b981' : '#f1f5f9' }};color:{{ request('status') === 'active' ? 'white' : '#475569' }};text-decoration:none;font-weight:900;font-size:13px;">✅ نشط</a>
    <a href="?status=trial" style="padding:8px 16px;border-radius:10px;background:{{ request('status') === 'trial' ? '#f59e0b' : '#f1f5f9' }};color:{{ request('status') === 'trial' ? 'white' : '#475569' }};text-decoration:none;font-weight:900;font-size:13px;">⏳ تجريبي</a>
    <a href="?status=suspended" style="padding:8px 16px;border-radius:10px;background:{{ request('status') === 'suspended' ? '#ef4444' : '#f1f5f9' }};color:{{ request('status') === 'suspended' ? 'white' : '#475569' }};text-decoration:none;font-weight:900;font-size:13px;">🚫 معطل</a>
  </div>
  <a href="{{ route('owner.shops.create') }}" style="padding:10px 20px;background:linear-gradient(135deg,#fbbf24,#f97316);color:white;border-radius:12px;text-decoration:none;font-weight:900;font-size:14px;box-shadow:0 4px 12px rgba(245,158,11,0.3);">
    ➕ إضافة متجر جديد
  </a>
</div>

{{-- ═══ قائمة المتاجر ═══ --}}
@php
  $filteredShops = request('status') && request('status') !== 'all'
    ? $shops->where('status', request('status'))
    : $shops;
@endphp

@forelse($filteredShops as $shop)
<div style="background:white;border-radius:16px;padding:20px;margin-bottom:12px;box-shadow:0 2px 12px rgba(0,0,0,0.05);border-right:5px solid {{ $shop->status === 'active' ? '#10b981' : ($shop->status === 'trial' ? '#f59e0b' : '#ef4444') }};transition:all 0.2s;" onmouseover="this.style.transform='translateX(-4px)'" onmouseout="this.style.transform='translateX(0)'">

  <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;">
    <div style="flex:1;min-width:250px;">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
        <div style="width:50px;height:50px;background:linear-gradient(135deg,#fbbf24,#f97316);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;">
          @if($shop->logo)
            <img src="{{ Storage::url($shop->logo) }}" style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
          @else
            🏪
          @endif
        </div>
        <div>
          <div style="font-weight:900;font-size:17px;color:#1f2937;">{{ $shop->name }}</div>
          <div style="font-size:12px;color:#9ca3af;font-family:monospace;">#{{ $shop->id }} — {{ $shop->slug }}</div>
        </div>
        <span style="padding:4px 12px;border-radius:999px;font-size:11px;font-weight:900;
          background:{{ $shop->status === 'active' ? '#d1fae5' : ($shop->status === 'trial' ? '#fef3c7' : '#fee2e2') }};
          color:{{ $shop->status === 'active' ? '#065f46' : ($shop->status === 'trial' ? '#92400e' : '#991b1b') }};">
          {{ $shop->status === 'active' ? '✅ نشط' : ($shop->status === 'trial' ? '⏳ تجريبي' : '🚫 معطل') }}
        </span>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;font-size:12px;color:#6b7280;">
        <div>📞 {{ $shop->phone ?? '—' }}</div>
        <div>📧 {{ $shop->email ?? '—' }}</div>
        <div>📍 {{ $shop->city ?? '—' }}</div>
        <div>📦 {{ $shop->products_count ?? 0 }} منتج</div>
        <div>🛒 {{ $shop->orders_count ?? 0 }} طلب</div>
        @if($shop->trial_ends_at)
        <div style="color:#d97706;">⏰ ينتهي: {{ $shop->trial_ends_at->format('Y-m-d') }}</div>
        @endif
      </div>
    </div>

    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:flex-start;">
      <a href="{{ route('owner.shops.show', $shop->id) }}" title="عرض التفاصيل"
         style="padding:8px 14px;background:#dbeafe;color:#1e40af;border-radius:8px;text-decoration:none;font-weight:900;font-size:12px;">👁️ عرض</a>

      <a href="{{ route('owner.shops.edit', $shop->id) }}" title="تعديل"
         style="padding:8px 14px;background:#fef3c7;color:#92400e;border-radius:8px;text-decoration:none;font-weight:900;font-size:12px;">✏️ تعديل</a>

      <form method="POST" action="{{ route('owner.shops.toggleStatus', $shop->id) }}" style="display:inline;">
        @csrf
        <button type="submit" title="{{ $shop->status === 'active' ? 'تعطيل' : 'تفعيل' }}"
          style="padding:8px 14px;background:{{ $shop->status === 'active' ? '#fee2e2' : '#d1fae5' }};color:{{ $shop->status === 'active' ? '#991b1b' : '#065f46' }};border:none;border-radius:8px;cursor:pointer;font-weight:900;font-size:12px;font-family:inherit;">
          {{ $shop->status === 'active' ? '🚫 تعطيل' : '✅ تفعيل' }}
        </button>
      </form>

      @if($shop->status === 'trial')
      <form method="POST" action="{{ route('owner.shops.extendTrial', $shop->id) }}" style="display:inline;">
        @csrf
        <input type="hidden" name="days" value="30">
        <button type="submit" title="تمديد 30 يوم"
          style="padding:8px 14px;background:#ede9fe;color:#5b21b6;border:none;border-radius:8px;cursor:pointer;font-weight:900;font-size:12px;font-family:inherit;">
          ⏰ +30 يوم
        </button>
      </form>
      @endif

      <form method="POST" action="{{ route('owner.shops.destroy', $shop->id) }}" style="display:inline;"
            onsubmit="return confirm('⚠️ هل أنت متأكد من حذف «{{ $shop->name }}»؟\n\nسيتم حذف كل المنتجات والطلبات والمستخدمين!\n\nهذا الإجراء لا يمكن التراجع عنه.')">
        @csrf
        @method('DELETE')
        <button type="submit" title="حذف"
          style="padding:8px 14px;background:#fecaca;color:#991b1b;border:none;border-radius:8px;cursor:pointer;font-weight:900;font-size:12px;font-family:inherit;">
          🗑️ حذف
        </button>
      </form>
    </div>
  </div>
</div>
@empty
<div style="text-align:center;padding:60px 20px;background:white;border-radius:20px;box-shadow:0 2px 12px rgba(0,0,0,0.05);">
  <div style="font-size:64px;margin-bottom:16px;">🏪</div>
  <h3 style="font-size:18px;font-weight:900;color:#1f2937;margin-bottom:8px;">لا توجد متاجر</h3>
  <p style="color:#6b7280;font-size:14px;margin-bottom:20px;">ابدأ بإضافة أول متجر</p>
  <a href="{{ route('owner.shops.create') }}" style="display:inline-block;padding:12px 24px;background:linear-gradient(135deg,#fbbf24,#f97316);color:white;border-radius:12px;text-decoration:none;font-weight:900;box-shadow:0 4px 12px rgba(245,158,11,0.3);">
    ➕ إضافة متجر جديد
  </a>
</div>
@endforelse

@endsection

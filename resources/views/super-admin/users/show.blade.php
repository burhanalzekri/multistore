@extends('layouts.super-admin')

@section('title', 'المستخدم — ' . $user->name)
@section('page-title', '👤 ' . $user->name)
@section('page-subtitle', $user->email ?? 'بدون بريد')

@section('content')

{{-- زر رجوع --}}
<div style="margin-bottom:16px;display:flex;gap:10px;flex-wrap:wrap;">
  <a href="/super-admin/users"
     style="padding:10px 20px;border-radius:12px;background:#f1f5f9;color:#334155;text-decoration:none;font-weight:900;font-size:13px;">
    ← قائمة المستخدمين
  </a>
  @if($shop)
    <a href="/super-admin/shops/{{ $shop->id }}"
       style="padding:10px 20px;border-radius:12px;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;text-decoration:none;font-weight:900;font-size:13px;">
      🏪 عرض المتجر
    </a>
  @endif
  <a href="/super-admin/users/{{ $user->id }}/reset-password"
     style="padding:10px 20px;border-radius:12px;background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;text-decoration:none;font-weight:900;font-size:13px;box-shadow:0 6px 16px rgba(220,38,38,.3);">
    🔑 إعادة تعيين كلمة المرور
  </a>
</div>

{{-- ═══ بطاقة المستخدم ═══ --}}
<div class="admin-card" style="padding:24px;margin-bottom:20px;">
  <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">

    <div style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;display:flex;align-items:center;justify-content:center;font-size:34px;font-weight:900;flex-shrink:0;box-shadow:0 10px 30px rgba(124,58,237,.3);">
      {{ mb_substr($user->name ?? '?', 0, 1) }}
    </div>

    <div style="flex:1;min-width:200px;">
      <div style="font-size:22px;font-weight:900;color:#0f172a;">{{ $user->name }}</div>
      <div style="color:#64748b;font-size:13px;margin-top:4px;">{{ $user->email ?? '—' }}</div>
      <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap;">
        @php
          $roleColors = [
            'super_admin' => ['#ede9fe', '#6d28d9', '👑 مدير المنصة'],
            'shop_admin'  => ['#fef3c7', '#b45309', '🏪 مدير متجر'],
            'owner'       => ['#dbeafe', '#1d4ed8', '🏪 مالك'],
            'staff'       => ['#e0e7ff', '#4338ca', '👤 موظف'],
            'customer'    => ['#f1f5f9', '#64748b', '🛒 عميل'],
          ];
          $rc = $roleColors[$user->role] ?? ['#f1f5f9', '#64748b', $user->role ?? '—'];
        @endphp
        <span style="background:{{ $rc[0] }};color:{{ $rc[1] }};padding:6px 14px;border-radius:999px;font-size:12px;font-weight:900;">
          {{ $rc[2] }}
        </span>
        @if($shop)
          <span style="background:#f0fdf4;color:#15803d;padding:6px 14px;border-radius:999px;font-size:12px;font-weight:900;">
            🏪 {{ $shop->name }}
          </span>
        @else
          <span style="background:#f1f5f9;color:#94a3b8;padding:6px 14px;border-radius:999px;font-size:12px;font-weight:900;">
            — بلا متجر —
          </span>
        @endif
      </div>
    </div>
  </div>
</div>

{{-- ═══ معلومات الاتصال ═══ --}}
<div class="admin-card" style="padding:20px;margin-bottom:20px;">
  <h3 style="margin:0 0 16px;font-size:15px;font-weight:900;color:#0f172a;">📞 معلومات الاتصال</h3>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;">
    <div>
      <div style="font-size:11px;color:#94a3b8;font-weight:800;">البريد الإلكتروني</div>
      <div style="font-weight:800;font-size:14px;margin-top:4px;">{{ $user->email ?? '—' }}</div>
    </div>
    <div>
      <div style="font-size:11px;color:#94a3b8;font-weight:800;">رقم الهاتف</div>
      <div style="font-weight:800;font-size:14px;margin-top:4px;direction:ltr;text-align:right;">{{ $user->phone ?? '—' }}</div>
    </div>
    <div>
      <div style="font-size:11px;color:#94a3b8;font-weight:800;">تاريخ التسجيل</div>
      <div style="font-weight:800;font-size:14px;margin-top:4px;">{{ $user->created_at?->format('Y-m-d') ?? '—' }}</div>
    </div>
    <div>
      <div style="font-size:11px;color:#94a3b8;font-weight:800;">رقم الحساب</div>
      <div style="font-weight:800;font-size:14px;margin-top:4px;">#{{ $user->id }}</div>
    </div>
  </div>
</div>

{{-- ═══ إحصائيات الطلبات ═══ --}}
@if($stats['orders_count'] > 0)
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:20px;">
    <div class="admin-card" style="padding:16px;">
      <div style="font-size:11px;color:#64748b;font-weight:800;margin-bottom:4px;">🛒 إجمالي الطلبات</div>
      <div style="font-size:24px;font-weight:900;color:#f59e0b;">{{ $stats['orders_count'] }}</div>
    </div>
    <div class="admin-card" style="padding:16px;">
      <div style="font-size:11px;color:#64748b;font-weight:800;margin-bottom:4px;">💰 إجمالي المبيعات</div>
      <div style="font-size:20px;font-weight:900;color:#16a34a;">{{ number_format($stats['orders_total']) }} ر.ي</div>
    </div>
  </div>
@endif

{{-- ═══ آخر الطلبات ═══ --}}
@if($recentOrders->count() > 0)
  <div class="admin-card" style="margin-bottom:20px;">
    <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;">
      <h3 style="margin:0;font-size:15px;font-weight:900;">🛒 آخر الطلبات</h3>
    </div>
    @foreach($recentOrders as $order)
      <div style="display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid #f1f5f9;font-size:13px;">
        <div style="font-weight:900;color:#7c3aed;">{{ $order->order_number }}</div>
        <div style="flex:1;color:#64748b;">{{ $order->created_at?->format('Y-m-d H:i') }}</div>
        <div style="font-weight:900;color:#16a34a;">{{ number_format($order->total ?? 0) }} ر.ي</div>
      </div>
    @endforeach
  </div>
@endif

{{-- ═══ آخر النشاطات ═══ --}}
@if($activityLogs->count() > 0)
  <div class="admin-card">
    <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;">
      <h3 style="margin:0;font-size:15px;font-weight:900;">📊 آخر النشاطات</h3>
    </div>
    @foreach($activityLogs as $log)
      <div style="padding:12px 20px;border-bottom:1px solid #f1f5f9;font-size:13px;">
        <div style="font-weight:800;">{{ $log->description ?? $log->action ?? '—' }}</div>
        <div style="color:#94a3b8;font-size:11px;margin-top:3px;">{{ $log->created_at?->diffForHumans() }}</div>
      </div>
    @endforeach
  </div>
@endif

@endsection

@extends('layouts.super-admin')

@section('title', 'المستخدمون')
@section('page-title', '👥 إدارة المستخدمين')
@section('page-subtitle', $users->total() . ' مستخدم في المنصة')

@section('content')

<div style="display:flex;justify-content:flex-end;margin-bottom:16px;">
  <a href="/super-admin/users/create"
     style="padding:10px 20px;border-radius:12px;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;text-decoration:none;font-weight:900;font-size:13px;box-shadow:0 6px 16px rgba(124,58,237,.3);">
    ➕ مستخدم جديد
  </a>
</div>

{{-- ═══ إحصائيات ═══ --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:20px;">
  <div class="admin-card" style="padding:16px;">
    <div style="font-size:11px;color:#64748b;font-weight:800;margin-bottom:4px;">👥 الإجمالي</div>
    <div style="font-size:24px;font-weight:900;color:#0f172a;">{{ $stats['total'] }}</div>
  </div>
  <div class="admin-card" style="padding:16px;">
    <div style="font-size:11px;color:#64748b;font-weight:800;margin-bottom:4px;">👑 مديرو المنصة</div>
    <div style="font-size:24px;font-weight:900;color:#7c3aed;">{{ $stats['super_admin'] }}</div>
  </div>
  <div class="admin-card" style="padding:16px;">
    <div style="font-size:11px;color:#64748b;font-weight:800;margin-bottom:4px;">🏪 مديرو المتاجر</div>
    <div style="font-size:24px;font-weight:900;color:#f59e0b;">{{ $stats['shop_admin'] }}</div>
  </div>
  <div class="admin-card" style="padding:16px;">
    <div style="font-size:11px;color:#64748b;font-weight:800;margin-bottom:4px;">👤 الموظفون</div>
    <div style="font-size:24px;font-weight:900;color:#3b82f6;">{{ $stats['staff'] }}</div>
  </div>
</div>

{{-- ═══ فلاتر البحث ═══ --}}
<form method="GET" action="/super-admin/users" class="admin-card" style="padding:14px 18px;margin-bottom:16px;display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
  <div style="flex:1;min-width:200px;">
    <input name="q" type="text" value="{{ request('q') }}" placeholder="🔍 ابحث بالاسم، البريد، أو الهاتف..."
           style="width:100%;padding:10px 14px;border:2px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:13px;">
  </div>

  <select name="role" style="padding:10px 14px;border:2px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:13px;background:#fff;">
    <option value="">كل الأدوار</option>
    @foreach($roles as $r)
      <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ $r }}</option>
    @endforeach
  </select>

  <select name="shop_id" style="padding:10px 14px;border:2px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:13px;background:#fff;">
    <option value="">كل المتاجر</option>
    @foreach($shops as $s)
      <option value="{{ $s->id }}" {{ (string) request('shop_id') === (string) $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
    @endforeach
  </select>

  <button type="submit" style="padding:10px 20px;border-radius:10px;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;border:0;font-weight:900;font-size:13px;cursor:pointer;font-family:inherit;">
    تصفية
  </button>

  @if(request()->hasAny(['q', 'role', 'shop_id']))
    <a href="/super-admin/users" style="padding:10px 16px;border-radius:10px;background:#f1f5f9;color:#475569;text-decoration:none;font-weight:800;font-size:12px;">
      ✕ مسح
    </a>
  @endif
</form>

{{-- ═══ قائمة المستخدمين ═══ --}}
<div class="admin-card">
  @forelse($users as $user)
    <div onclick="window.location='/super-admin/users/{{ $user->id }}'"
         style="display:flex;align-items:center;gap:14px;padding:14px 18px;border-bottom:1px solid #f1f5f9;flex-wrap:wrap;cursor:pointer;transition:background .2s;"
         onmouseover="this.style.background='#faf5ff';this.style.borderInlineStart='3px solid #7c3aed'"
         onmouseout="this.style.background='';this.style.borderInlineStart=''">

      {{-- Avatar --}}
      <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:900;flex-shrink:0;">
        {{ mb_substr($user->name ?? '?', 0, 1) }}
      </div>

      {{-- الاسم والبريد --}}
      <div style="flex:1;min-width:180px;">
        <div style="font-weight:900;font-size:14px;color:#0f172a;">{{ $user->name }}</div>
        <div style="font-size:11px;color:#94a3b8;margin-top:3px;">{{ $user->email }}</div>
      </div>

      {{-- المتجر --}}
      <div style="min-width:130px;">
        @if($user->shop)
          <div style="font-size:12px;font-weight:800;color:#7c3aed;">🏪 {{ $user->shop->name }}</div>
        @else
          <div style="font-size:12px;color:#94a3b8;">— بلا متجر —</div>
        @endif
      </div>

      {{-- الدور --}}
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
      <span style="background:{{ $rc[0] }};color:{{ $rc[1] }};padding:5px 12px;border-radius:999px;font-size:11px;font-weight:900;">
        {{ $rc[2] }}
      </span>

      {{-- الهاتف --}}
      <div style="min-width:110px;font-size:12px;color:#64748b;direction:ltr;text-align:right;font-weight:700;">
        {{ $user->phone ?? '—' }}
      </div>

    </div>
  @empty
    <div style="padding:60px 20px;text-align:center;">
      <div style="font-size:56px;margin-bottom:12px;">👥</div>
      <div style="font-size:15px;font-weight:900;">لا توجد نتائج مطابقة</div>
      <p style="color:#94a3b8;font-size:13px;margin-top:8px;">جرّب تعديل معايير البحث</p>
    </div>
  @endforelse
</div>

@if($users->hasPages())
  <div style="margin-top:16px;">{{ $users->links() }}</div>
@endif

@endsection

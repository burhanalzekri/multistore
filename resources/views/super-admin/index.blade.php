@extends('layouts.super-admin')
@section('title', 'لوحة المشرف العام')
@section('page-title', '👑 لوحة المشرف العام')
@section('page-subtitle', 'إدارة كل المتاجر')

@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:20px;">
  <div class="admin-card" style="padding:18px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">🏪 إجمالي المتاجر</div>
    <div style="font-size:24px;font-weight:900;color:var(--primary);">{{ $stats['total_shops'] }}</div>
    <div style="font-size:11px;color:#10b981;font-weight:700;margin-top:4px;">{{ $stats['active_shops'] }} نشط</div>
  </div>

  <div class="admin-card" style="padding:18px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">👥 المستخدمون</div>
    <div style="font-size:24px;font-weight:900;color:#3b82f6;">{{ $stats['total_users'] }}</div>
  </div>

  <div class="admin-card" style="padding:18px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">📦 المنتجات</div>
    <div style="font-size:24px;font-weight:900;color:#8b5cf6;">{{ $stats['total_products'] }}</div>
  </div>

  <div class="admin-card" style="padding:18px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">🛒 الطلبات</div>
    <div style="font-size:24px;font-weight:900;color:#f59e0b;">{{ $stats['total_orders'] }}</div>
  </div>

  <div class="admin-card" style="padding:18px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">💰 إجمالي المبيعات</div>
    <div style="font-size:20px;font-weight:900;color:#10b981;">{{ number_format($stats['total_sales']) }}</div>
  </div>

  <div class="admin-card" style="padding:18px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">📱 رسائل SMS</div>
    <div style="font-size:24px;font-weight:900;color:#ec4899;">{{ $stats['total_sms'] }}</div>
  </div>
</div>

<div class="admin-card">
  <div style="padding:18px 20px;border-bottom:1px solid var(--border);">
    <h2 style="font-size:16px;font-weight:900;">🏪 قائمة المتاجر</h2>
  </div>
  @forelse($shops as $shop)
  <div style="display:flex;align-items:center;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);flex-wrap:wrap;">
    <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#fbbf24,#f97316);display:flex;align-items:center;justify-content:center;font-size:22px;color:white;">🍯</div>

    <div style="flex:1;min-width:200px;">
      <div style="font-weight:900;font-size:15px;color:var(--text);">{{ $shop->name }}</div>
      <div style="font-size:11px;color:var(--text-muted);font-family:monospace;margin-top:2px;">{{ $shop->slug }} • {{ $shop->custom_domain ?? 'لا يوجد نطاق' }}</div>
    </div>

    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <div style="text-align:center;">
        <div style="font-size:18px;font-weight:900;color:#8b5cf6;">{{ $shop->products_count }}</div>
        <div style="font-size:10px;color:var(--text-muted);">منتجات</div>
      </div>
      <div style="text-align:center;">
        <div style="font-size:18px;font-weight:900;color:#f59e0b;">{{ $shop->orders_count }}</div>
        <div style="font-size:10px;color:var(--text-muted);">طلبات</div>
      </div>
      <div style="text-align:center;">
        <div style="font-size:18px;font-weight:900;color:#3b82f6;">{{ $shop->users_count }}</div>
        <div style="font-size:10px;color:var(--text-muted);">مستخدمون</div>
      </div>
    </div>

    <div style="display:flex;gap:6px;align-items:center;">
      <span class="ui-badge" style="background:{{ $shop->status === 'active' ? '#dcfce7' : '#fee2e2' }};color:{{ $shop->status === 'active' ? '#15803d' : '#b91c1c' }};font-size:11px;font-weight:900;padding:4px 12px;border-radius:999px;">
        {{ $shop->status === 'active' ? '✅ نشط' : '⛔ موقوف' }}
      </span>
      <a href="/super-admin/shops/{{ $shop->id }}" style="background:#f1f5f9;color:#475569;padding:8px 14px;border-radius:8px;text-decoration:none;font-weight:800;font-size:12px;">عرض</a>
      <form method="POST" action="/super-admin/shops/{{ $shop->id }}/toggle" style="display:inline;">
        @csrf
        <button type="submit" style="background:{{ $shop->status === 'active' ? '#fee2e2' : '#dcfce7' }};color:{{ $shop->status === 'active' ? '#b91c1c' : '#15803d' }};border:none;padding:8px 14px;border-radius:8px;font-weight:800;font-size:12px;cursor:pointer;font-family:inherit;">
          {{ $shop->status === 'active' ? 'إيقاف' : 'تفعيل' }}
        </button>
      </form>
    </div>
  </div>
  @empty
  <div style="padding:60px 20px;text-align:center;">
    <div style="font-size:56px;margin-bottom:12px;">🏪</div>
    <div style="font-size:15px;font-weight:900;color:var(--text);">لا توجد متاجر</div>
  </div>
  @endforelse
</div>


{{-- 🔍 التحقق الفوري + تنبيهات --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('form').forEach(function(form) {
    if (form.dataset.validationReady) return;
    form.dataset.validationReady = '1';
    form.setAttribute('novalidate', 'novalidate');

    form.querySelectorAll('input, textarea, select').forEach(function(field) {
      field.addEventListener('input', function() {
        this.style.borderColor = '';
        this.style.background = '';
      });
    });

    form.addEventListener('submit', function(e) {
      let errors = [];
      let firstInvalid = null;

      form.querySelectorAll('[required]').forEach(function(field) {
        field.style.borderColor = '';
        field.style.background = '';

        if (!field.value.trim()) {
          let label = field.name;
          let labelEl = field.closest('div')?.querySelector('label');
          if (labelEl) {
            label = labelEl.textContent.replace('*', '').replace('مطلوب', '').trim();
          }
          errors.push('📌 ' + label + ' مطلوب');
          field.style.borderColor = '#ef4444';
          field.style.background = '#fef2f2';
          if (!firstInvalid) firstInvalid = field;
        }
      });

      form.querySelectorAll('input[type="number"]').forEach(function(field) {
        if (field.value && isNaN(parseFloat(field.value))) {
          errors.push('🔢 ' + (field.name === 'price' ? 'السعر' : field.name) + ' يجب أن يكون رقماً');
          field.style.borderColor = '#ef4444';
          if (!firstInvalid) firstInvalid = field;
        }
      });

      let email = form.querySelector('input[type="email"]');
      if (email && email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        errors.push('📧 البريد الإلكتروني غير صحيح');
        email.style.borderColor = '#ef4444';
        if (!firstInvalid) firstInvalid = email;
      }

      if (errors.length > 0) {
        e.preventDefault();
        e.stopPropagation();
        errors.forEach(function(err, i) {
          setTimeout(function() {
            if (window.showToast) window.showToast(err, 'error', 5000);
            else alert(err);
          }, i * 200);
        });
        if (firstInvalid) {
          firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
          setTimeout(function() { firstInvalid.focus(); }, 300);
        }
        return false;
      }
    });
  });

  @if($errors->any())
    @foreach($errors->all() as $error)
      setTimeout(function() {
        if (window.showToast) window.showToast("{{ addslashes($error) }}", 'error', 6000);
      }, {{ $loop->index * 200 }});
    @endforeach
  @endif

  @if(session('success'))
    setTimeout(function() {
      if (window.showToast) window.showToast("{{ addslashes(session('success')) }}", 'success', 5000);
    }, 300);
  @endif

  @if(session('error'))
    setTimeout(function() {
      if (window.showToast) window.showToast("{{ addslashes(session('error')) }}", 'error', 6000);
    }, 300);
  @endif
});
</script>

@endsection

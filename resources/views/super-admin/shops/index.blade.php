@extends('layouts.super-admin')
@section('title', 'المتاجر')
@section('page-title', '🏪 إدارة المتاجر')
@section('page-subtitle', $shops->total() . ' متجر')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
  <div style="color:var(--text-muted);font-size:13px;font-weight:700;">
    <strong style="color:var(--text);">{{ $shops->total() }}</strong> متجر
  </div>
  <a href="/super-admin/shops/create" style="display:inline-flex;align-items:center;gap:6px;padding:10px 18px;background:linear-gradient(135deg,#fbbf24,#f97316);color:white;border-radius:10px;font-weight:800;text-decoration:none;">
    <i data-lucide="plus" style="width:16px;height:16px;"></i>
    متجر جديد
  </a>
</div>

<div class="admin-card">
  @forelse($shops as $shop)
  <div onclick="window.location='/super-admin/shops/{{ $shop->id }}'"
       style="display:flex;align-items:center;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);flex-wrap:wrap;cursor:pointer;transition:background .2s;"
       onmouseover="this.style.background='#faf5ff';this.style.borderInlineStartColor='#7c3aed'"
       onmouseout="this.style.background='';this.style.borderInlineStartColor=''">
    <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#fbbf24,#f97316);display:flex;align-items:center;justify-content:center;font-size:22px;color:white;">🍯</div>

    <div style="flex:1;min-width:180px;">
      <div style="font-weight:900;font-size:15px;color:var(--text);">{{ $shop->name }}</div>
      <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">{{ $shop->slug }}</div>
    </div>

    <div style="display:flex;gap:12px;">
      <div style="text-align:center;">
        <div style="font-size:16px;font-weight:900;color:#8b5cf6;">{{ $shop->products_count }}</div>
        <div style="font-size:10px;color:var(--text-muted);">منتج</div>
      </div>
      <div style="text-align:center;">
        <div style="font-size:16px;font-weight:900;color:#f59e0b;">{{ $shop->orders_count }}</div>
        <div style="font-size:10px;color:var(--text-muted);">طلب</div>
      </div>
      <div style="text-align:center;">
        <div style="font-size:16px;font-weight:900;color:#3b82f6;">{{ $shop->users_count }}</div>
        <div style="font-size:10px;color:var(--text-muted);">مستخدم</div>
      </div>
    </div>

    <span style="background:{{ $shop->status === 'active' ? '#dcfce7' : '#fee2e2' }};color:{{ $shop->status === 'active' ? '#15803d' : '#b91c1c' }};padding:4px 12px;border-radius:999px;font-size:11px;font-weight:900;">
      {{ $shop->status === 'active' ? '✅ نشط' : '⛔ موقوف' }}
    </span>

    <div style="display:flex;gap:6px;" onclick="event.stopPropagation()">
      <a href="/super-admin/shops/{{ $shop->id }}/edit"
         style="background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;padding:8px 14px;border-radius:8px;text-decoration:none;font-weight:800;font-size:12px;margin-inline-start:6px;">
         ✏️ تعديل
      </a>
      <form method="POST" action="/super-admin/shops/{{ $shop->id }}" onsubmit="return confirm('حذف المتجر؟')" style="display:inline;">
        @csrf @method('DELETE')
        <button type="submit" style="background:#fee2e2;color:#b91c1c;border:none;padding:8px 14px;border-radius:8px;font-weight:800;font-size:12px;cursor:pointer;font-family:inherit;">حذف</button>
      </form>
    </div>
  </div>
  @empty
  <div style="padding:60px 20px;text-align:center;">
    <div style="font-size:56px;margin-bottom:12px;">🏪</div>
    <div style="font-size:15px;font-weight:900;">لا توجد متاجر</div>
  </div>
  @endforelse
</div>

@if($shops->hasPages())
<div style="margin-top:16px;">{{ $shops->links() }}</div>
@endif


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

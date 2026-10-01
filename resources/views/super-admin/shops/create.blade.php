@extends('layouts.super-admin')
@section('title', 'متجر جديد')
@section('page-title', '➕ متجر جديد')

@section('content')

<a href="/super-admin/shops" style="display:inline-flex;align-items:center;gap:6px;color:var(--text-muted);text-decoration:none;font-weight:800;font-size:13px;margin-bottom:20px;">← العودة للمتاجر</a>

@if($errors->any())
<div style="background:#fee2e2;color:#b91c1c;padding:14px 18px;border-radius:12px;margin-bottom:20px;font-weight:700;font-size:14px;">
  @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
</div>
@endif

<form method="POST" action="/super-admin/shops" style="max-width:640px;margin:0 auto;display:flex;flex-direction:column;gap:16px;">
  @csrf

  <div class="admin-card" style="padding:22px;display:flex;flex-direction:column;gap:16px;">
    <h2 style="font-size:15px;font-weight:900;">🏪 بيانات المتجر</h2>

    <div>
      <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;">اسم المتجر *</label>
      <input type="text" name="shop_name" value="{{ old('shop_name') }}" required placeholder="مثال: متجر البن اليمني" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);font-weight:600;outline:none;">
    </div>
  </div>

  <div class="admin-card" style="padding:22px;display:flex;flex-direction:column;gap:16px;">
    <h2 style="font-size:15px;font-weight:900;">👤 بيانات المالك</h2>

    <div>
      <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;">الاسم *</label>
      <input type="text" name="owner_name" value="{{ old('owner_name') }}" required placeholder="مثال: سعيد أحمد" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);font-weight:600;outline:none;">
    </div>

    <div>
      <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;">البريد الإلكتروني *</label>
      <input type="email" name="email" value="{{ old('email') }}" required placeholder="saeed@example.com" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);font-weight:600;outline:none;">
    </div>

    <div>
      <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;">رقم الجوال *</label>
      <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="7XXXXXXXX" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);font-weight:600;outline:none;">
    </div>

    <div>
      <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;">كلمة المرور *</label>
      <input type="text" name="password" required minlength="6" value="{{ Str::random(8) }}" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:monospace;background:var(--surface);color:var(--text);font-weight:600;outline:none;">
      <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">انسخها وأرسلها للمالك</div>
    </div>
  </div>

  <div style="display:flex;gap:10px;">
    <button type="submit" style="flex:1;background:linear-gradient(135deg,#fbbf24,#f97316);color:white;border:none;padding:14px;border-radius:12px;font-weight:900;cursor:pointer;font-family:inherit;font-size:15px;">
      🚀 إنشاء المتجر
    </button>
    <a href="/super-admin/shops" style="background:#f1f5f9;color:#475569;padding:14px 24px;border-radius:12px;text-decoration:none;font-weight:800;">إلغاء</a>
  </div>
</form>


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

@extends('layouts.app')
@section('title', 'تعديل موظف')
@section('page-title', '✏️ ' . $staff->name)

@section('content')

<a href="/dashboard/staff" style="display:inline-flex;align-items:center;gap:6px;color:var(--text-muted);text-decoration:none;font-weight:800;font-size:13px;margin-bottom:20px;">← العودة</a>

<form method="POST" action="/dashboard/staff/{{ $staff->id }}" style="max-width:640px;margin:0 auto;display:flex;flex-direction:column;gap:16px;">
  @csrf @method('PUT')

  <div class="admin-card" style="padding:22px;display:flex;flex-direction:column;gap:16px;">
    <h2 style="font-size:15px;font-weight:900;">👤 البيانات</h2>

    <div>
      <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;">الاسم *</label>
      <input type="text" name="name" value="{{ old('name', $staff->name) }}" required style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);font-weight:600;outline:none;">
    </div>

    <div>
      <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;">رقم الجوال</label>
      <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);font-weight:600;outline:none;">
    </div>

    <div>
      <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;">كلمة مرور جديدة (اتركها فارغة)</label>
      <input type="text" name="password" placeholder="••••••" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:monospace;background:var(--surface);color:var(--text);font-weight:600;outline:none;">
    </div>

    <div>
      <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;">الدور</label>
      <select name="role" required style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);font-weight:700;outline:none;">
        <option value="staff" {{ $staff->role === 'staff' ? 'selected' : '' }}>👤 موظف</option>
        <option value="shop_admin" {{ $staff->role === 'shop_admin' ? 'selected' : '' }}>👔 مدير</option>
      </select>
    </div>

    <label style="display:flex;align-items:center;gap:8px;padding:12px;background:#f8fafc;border-radius:10px;cursor:pointer;">
      <input type="checkbox" name="is_active" value="1" {{ $staff->is_active ? 'checked' : '' }} style="width:16px;height:16px;accent-color:#10b981;">
      <span style="font-size:13px;font-weight:800;">الحساب مفعّل</span>
    </label>
  </div>

  <div class="admin-card" style="padding:22px;">
    <h2 style="font-size:15px;font-weight:900;margin-bottom:16px;">🔐 الصلاحيات</h2>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
      @foreach($permissionsList as $key => $label)
      <label style="display:flex;align-items:center;gap:8px;padding:10px 12px;background:#f8fafc;border-radius:10px;cursor:pointer;">
        <input type="checkbox" name="permissions[]" value="{{ $key }}" {{ in_array($key, $staff->permissions ?? []) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:#f59e0b;">
        <span style="font-size:13px;font-weight:700;">{{ $label }}</span>
      </label>
      @endforeach
    </div>
  </div>

  <div style="display:flex;gap:10px;">
    <button type="submit" style="flex:1;background:linear-gradient(135deg,#fbbf24,#f97316);color:white;border:none;padding:14px;border-radius:12px;font-weight:900;cursor:pointer;font-family:inherit;">💾 حفظ</button>
    <a href="/dashboard/staff" style="background:#f1f5f9;color:#475569;padding:14px 24px;border-radius:12px;text-decoration:none;font-weight:800;">إلغاء</a>
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

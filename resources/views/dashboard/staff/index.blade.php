@extends('layouts.app')
@section('title', 'الموظفون')
@section('page-title', '👥 الموظفون')
@section('page-subtitle', $staff->count() . ' موظف')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
  <div style="color:var(--text-muted);font-size:13px;font-weight:700;">
    <strong style="color:var(--text);">{{ $staff->count() }}</strong> موظف
  </div>
  <a href="/dashboard/staff/create" style="display:inline-flex;align-items:center;gap:6px;padding:10px 18px;background:linear-gradient(135deg,#fbbf24,#f97316);color:white;border-radius:10px;font-weight:800;text-decoration:none;">
    <i data-lucide="user-plus" style="width:16px;height:16px;"></i>
    موظف جديد
  </a>
</div>

@if($staff->isEmpty())
<div class="admin-card">
  <div style="text-align:center;padding:60px 20px;">
    <div style="font-size:56px;margin-bottom:12px;opacity:0.5;">👥</div>
    <div style="font-size:15px;font-weight:900;margin-bottom:4px;">لا يوجد موظفون</div>
    <div style="font-size:13px;color:var(--text-muted);margin-bottom:16px;">أضف موظفين لمساعدتك في إدارة المتجر</div>
    <a href="/dashboard/staff/create" style="display:inline-flex;align-items:center;gap:6px;padding:12px 24px;background:linear-gradient(135deg,#fbbf24,#f97316);color:white;border-radius:12px;font-weight:800;text-decoration:none;">
      <i data-lucide="user-plus" style="width:18px;height:18px;"></i>
      إضافة موظف
    </a>
  </div>
</div>
@else
<div class="admin-card">
  @foreach($staff as $member)
  <div style="display:flex;align-items:center;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);">
    <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#fbbf24,#f97316);display:flex;align-items:center;justify-content:center;color:white;font-weight:900;font-size:20px;flex-shrink:0;">
      {{ mb_substr($member->name, 0, 1) }}
    </div>
    <div style="flex:1;min-width:0;">
      <div style="font-weight:900;font-size:14px;color:var(--text);">{{ $member->name }}</div>
      <div style="font-size:11px;color:var(--text-muted);">{{ $member->email }}</div>
      @if($member->permissions && count($member->permissions) > 0)
      <div style="font-size:11px;color:#10b981;font-weight:800;margin-top:4px;">
        ✅ {{ count($member->permissions) }} صلاحية
      </div>
      @endif
    </div>
    <span class="ui-badge" style="background:{{ $member->role === 'shop_admin' ? '#fef3c7' : '#dbeafe' }};color:{{ $member->role === 'shop_admin' ? '#b45309' : '#1d4ed8' }};padding:4px 12px;border-radius:999px;font-size:11px;font-weight:900;">
      {{ $member->role === 'shop_admin' ? '👔 مدير' : '👤 موظف' }}
    </span>
    <div style="display:flex;gap:6px;">
      <a href="/dashboard/staff/{{ $member->id }}/edit" style="background:#f1f5f9;color:#475569;padding:8px 14px;border-radius:8px;text-decoration:none;font-weight:800;font-size:12px;">تعديل</a>
      <form method="POST" action="/dashboard/staff/{{ $member->id }}" onsubmit="return confirm('حذف الموظف؟')" style="display:inline;">
        @csrf @method('DELETE')
        <button type="submit" style="background:#fee2e2;color:#b91c1c;border:none;padding:8px 14px;border-radius:8px;font-weight:800;font-size:12px;cursor:pointer;font-family:inherit;">حذف</button>
      </form>
    </div>
  </div>
  @endforeach
</div>
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

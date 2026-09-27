@extends('layouts.app')
@section('title', 'تصنيف جديد')
@section('page-title', 'تصنيف جديد')

@section('content')

<a href="/dashboard/categories" class="shine-btn" style="background:white;border:1px solid var(--border);color:var(--text-muted);margin-bottom:20px;">
  ← العودة للتصنيفات
</a>

<form method="POST" action="/dashboard/categories" style="max-width:640px;margin:0 auto;display:flex;flex-direction:column;gap:16px;">
  @csrf

  <div class="shine-card">
    <div style="padding:22px;display:flex;flex-direction:column;gap:16px;">
      <div>
        <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;color:var(--text);">اسم التصنيف *</label>
        <input type="text" name="name" value="{{ old('name') }}" required placeholder="مثال: عسل جبلي"
          style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:14px;background:var(--surface);color:var(--text);outline:none;font-weight:600;">
      </div>

      <div>
        <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;color:var(--text);">أيقونة (Emoji)</label>
        <input type="text" name="icon" value="{{ old('icon') }}" placeholder="🍯" maxlength="10"
          style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:24px;background:var(--surface);color:var(--text);outline:none;text-align:center;">
      </div>

      <div>
        <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;color:var(--text);">الوصف</label>
        <textarea name="description" rows="3"
          style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:14px;background:var(--surface);color:var(--text);outline:none;resize:vertical;">{{ old('description') }}</textarea>
      </div>
    </div>
  </div>

  <div style="display:flex;gap:10px;">
    <button type="submit" class="shine-btn shine-btn-primary" style="flex:1;justify-content:center;padding:14px;">
      <i data-lucide="save"></i> حفظ
    </button>
    <a href="/dashboard/categories" class="shine-btn" style="background:#f1f5f9;color:#475569;padding:14px 24px;">إلغاء</a>
  </div>
</form>



@push('scripts')
<script>
// ═══ التحقق من الحقول ═══
document.addEventListener('DOMContentLoaded', function() {
  const forms = document.querySelectorAll('form');
  forms.forEach(form => {
    if (form.dataset.validationAttached) return;
    form.dataset.validationAttached = '1';
    form.setAttribute('novalidate', 'novalidate');

    // إزالة تنسيق الخطأ عند الكتابة
    form.querySelectorAll('input, textarea, select').forEach(field => {
      field.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        this.style.borderColor = '';
      });
    });

    form.addEventListener('submit', function(e) {
      let errors = [];
      let firstInvalid = null;

      form.querySelectorAll('[required]').forEach(field => {
        field.classList.remove('is-invalid');
        field.style.borderColor = '';

        if (!field.value.trim()) {
          const label = field.closest('.input-group')?.querySelector('label')?.textContent?.trim() || field.name;
          errors.push(label.replace('*', '').trim() + ' مطلوب');
          field.style.borderColor = '#ef4444';
          field.style.background = '#fef2f2';
          if (!firstInvalid) firstInvalid = field;
        }
      });

      // فحص البريد
      const email = form.querySelector('[name="email"]');
      if (email && email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        errors.push('البريد الإلكتروني غير صحيح');
        email.style.borderColor = '#ef4444';
        if (!firstInvalid) firstInvalid = email;
      }

      // فحص رقم الجوال
      const phone = form.querySelector('[name="phone"]');
      if (phone && phone.value && !/^\d{7,15}$/.test(phone.value.replace(/\D/g, ''))) {
        errors.push('رقم الجوال غير صحيح');
        phone.style.borderColor = '#ef4444';
        if (!firstInvalid) firstInvalid = phone;
      }

      if (errors.length > 0) {
        e.preventDefault();
        errors.forEach((err, i) => {
          setTimeout(() => {
            if (window.showToast) {
              window.showToast(err, 'error', 5500);
            } else {
              alert(err);
            }
          }, i * 150);
        });
        if (firstInvalid) {
          firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
          setTimeout(() => firstInvalid.focus(), 300);
        }
        return false;
      }
    });
  });
});
</script>
@endpush


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

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>تتبع الطلب</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">
<style>body{font-family:'Cairo',sans-serif;background:#f5f7fa;}</style>
</head>
<body>

<header style="background:white;padding:14px 20px;box-shadow:0 2px 8px rgba(0,0,0,0.04);display:flex;justify-content:space-between;align-items:center;">
  <a href="/shop" style="display:flex;align-items:center;gap:10px;text-decoration:none;color:inherit;">
    <div style="width:38px;height:38px;background:linear-gradient(135deg,#fbbf24,#f97316);border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:19px;">🍯</div>
    <div style="font-weight:900;">المتجر</div>
  </a>
  <a href="/shop" style="color:#f59e0b;font-weight:800;font-size:13px;text-decoration:none;">← الرئيسية</a>
</header>

<div style="max-width:520px;margin:0 auto;padding:48px 20px;">
  
  <div style="text-align:center;margin-bottom:32px;">
    <div style="display:inline-flex;width:80px;height:80px;background:linear-gradient(135deg,#fbbf24,#f97316);border-radius:24px;align-items:center;justify-content:center;font-size:40px;margin-bottom:16px;box-shadow:0 8px 24px rgba(245,158,11,0.35);">📦</div>
    <h1 style="font-size:28px;font-weight:900;margin:0 0 8px 0;color:#1f2937;">تتبع طلبك</h1>
    <p style="color:#6b7280;margin:0;">أدخل رقم الطلب أو رقم جوالك</p>
  </div>

  @if(session('error'))
  <div style="background:#fee2e2;color:#b91c1c;padding:14px 18px;border-radius:12px;margin-bottom:20px;font-weight:700;font-size:14px;">
    ⚠️ {{ session('error') }}
  </div>
  @endif

  <form method="POST" action="/track-order" style="background:white;padding:24px;border-radius:20px;box-shadow:0 4px 24px rgba(0,0,0,0.06);">
    @csrf
    <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;color:#1f2937;">
      🔍 رقم الطلب أو رقم الجوال
    </label>
    <input type="text" name="query" value="{{ old('query') }}" required autofocus placeholder="ORD-20260922-XXXX أو 777123456"
      style="width:100%;padding:16px;border:2px solid #e5e7eb;border-radius:14px;font-family:inherit;font-size:15px;font-weight:700;outline:none;margin-bottom:16px;box-sizing:border-box;"
      onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e5e7eb'">
    
    <button type="submit" style="width:100%;padding:16px;background:linear-gradient(135deg,#fbbf24,#f97316);color:white;border:none;border-radius:14px;font-weight:900;font-size:16px;cursor:pointer;font-family:inherit;box-shadow:0 8px 20px rgba(245,158,11,0.35);">
      🔎 ابحث عن الطلب
    </button>
  </form>

  <div style="margin-top:24px;text-align:center;">
    <p style="color:#9ca3af;font-size:12px;margin:0;">💡 يمكنك البحث بـ:</p>
    <div style="display:flex;justify-content:center;gap:8px;margin-top:8px;flex-wrap:wrap;">
      <span style="background:#fef3c7;color:#92400e;font-size:11px;font-weight:800;padding:4px 12px;border-radius:999px;">رقم الطلب</span>
      <span style="background:#dbeafe;color:#1d4ed8;font-size:11px;font-weight:800;padding:4px 12px;border-radius:999px;">رقم الجوال</span>
    </div>
  </div>

</div>


  {{-- 🔔 التنبيهات --}}
  @include('components.toast')

@include('components.floating-actions')
</body>
</html>

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

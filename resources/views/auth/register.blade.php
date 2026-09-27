<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>سجّل متجرك — MultiStore</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; font-family: Cairo, sans-serif; }
  body { background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%); min-height: 100vh; margin: 0; padding: 20px; }
  .form-card { background: white; border-radius: 24px; padding: 32px; box-shadow: 0 20px 60px rgba(245,158,11,0.15); max-width: 620px; margin: 0 auto; }
  .input-group { margin-bottom: 16px; }
  .input-group label { display: block; font-weight: 800; font-size: 13px; color: #374151; margin-bottom: 6px; }
  .input-group input, .input-group textarea {
    width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px;
    font-size: 14px; font-family: inherit; transition: all 0.2s; outline: none;
  }
  .input-group input:focus, .input-group textarea:focus {
    border-color: #f59e0b; box-shadow: 0 0 0 4px rgba(245,158,11,0.1);
  }
  .input-group input.is-invalid, .input-group textarea.is-invalid {
    border-color: #ef4444; background: #fef2f2;
  }
  .btn-primary {
    width: 100%; padding: 14px; background: linear-gradient(135deg,#fbbf24,#f97316);
    color: white; border: none; border-radius: 14px; font-weight: 900; font-size: 16px;
    cursor: pointer; font-family: inherit; transition: all 0.3s;
  }
  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(245,158,11,0.4); }
  .btn-primary:active { transform: translateY(0); }
</style>
</head>
<body>

<div style="text-align:center; margin-bottom:24px;">
  <div style="width:80px;height:80px;background:linear-gradient(135deg,#fbbf24,#f97316);border-radius:24px;display:inline-flex;align-items:center;justify-content:center;font-size:40px;box-shadow:0 8px 24px rgba(245,158,11,0.3);">🏪</div>
  <h1 style="font-size:28px;font-weight:900;margin:16px 0 8px 0;">سجّل متجرك</h1>
  <p style="color:#6b7280;font-size:14px;margin:0;">ابدأ البيع خلال دقيقتين — مجاناً</p>
</div>

<div class="form-card">

  <form method="POST" action="/register" id="registerForm" novalidate>
    @csrf

    <h2 style="font-size:16px;font-weight:900;color:#1f2937;margin:0 0 14px 0;padding-bottom:8px;border-bottom:2px solid #fef3c7;">🏪 معلومات المتجر</h2>

    <div class="input-group">
      <label>اسم المتجر *</label>
      <input type="text" name="shop_name" value="{{ old('shop_name') }}" placeholder="مثال: متجر العسل الأصلي" required>
    </div>

    <div class="input-group">
      <label>وصف قصير للمتجر</label>
      <textarea name="description" rows="2" placeholder="اكتب وصفاً مختصراً لمتجرك (اختياري)">{{ old('description') }}</textarea>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
      <div class="input-group">
        <label>رقم التواصل *</label>
        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="777123456" required>
      </div>

      <div class="input-group">
        <label>واتساب</label>
        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="777123456">
      </div>
    </div>

    <div class="input-group">
      <label>عنوان المتجر *</label>
      <input type="text" name="address" value="{{ old('address') }}" placeholder="شارع الحوبان، أمام السوق المركزي" required>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
      <div class="input-group">
        <label>المدينة *</label>
        <input type="text" name="city" value="{{ old('city') }}" placeholder="تعز" required>
      </div>

      <div class="input-group">
        <label>الدولة</label>
        <input type="text" name="country" value="{{ old('country', 'اليمن') }}">
      </div>
    </div>

    <h2 style="font-size:16px;font-weight:900;color:#1f2937;margin:24px 0 14px 0;padding-bottom:8px;border-bottom:2px solid #fef3c7;">👤 بيانات المدير</h2>

    <div class="input-group">
      <label>الاسم الكامل *</label>
      <input type="text" name="name" value="{{ old('name') }}" placeholder="مثال: أحمد محمد" required>
    </div>

    <div class="input-group">
      <label>البريد الإلكتروني *</label>
      <input type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required>
    </div>

    <div class="input-group">
      <label>كلمة المرور *</label>
      <input type="password" name="password" placeholder="6 أحرف على الأقل" required minlength="6">
    </div>

    <div class="input-group">
      <label>تأكيد كلمة المرور *</label>
      <input type="password" name="password_confirmation" placeholder="أعد إدخال كلمة المرور" required>
    </div>

    <button type="submit" class="btn-primary">🚀 إنشاء متجري الآن</button>

    <div style="text-align:center;margin-top:16px;font-size:13px;color:#6b7280;">
      لديك حساب؟ <a href="/login" style="color:#f59e0b;font-weight:900;text-decoration:none;">سجّل دخول</a>
    </div>

    <div style="margin-top:16px;padding:12px;background:#f0fdf4;border-radius:10px;font-size:12px;color:#166534;font-weight:700;text-align:center;">
      ✅ 14 يوم تجربة مجانية — بدون بطاقة ائتمانية
    </div>
  </form>
</div>

{{-- 🔔 التنبيهات المنبثقة --}}
@include('components.toast')

{{-- 🔍 التحقق من الحقول قبل الإرسال --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('registerForm');
  const fields = {
    shop_name: 'اسم المتجر',
    phone: 'رقم التواصل',
    address: 'عنوان المتجر',
    city: 'المدينة',
    name: 'الاسم الكامل',
    email: 'البريد الإلكتروني',
    password: 'كلمة المرور',
    password_confirmation: 'تأكيد كلمة المرور'
  };

  // إزالة تنسيق الخطأ عند الكتابة
  form.querySelectorAll('input, textarea').forEach(field => {
    field.addEventListener('input', function() {
      this.classList.remove('is-invalid');
    });
  });

  // التحقق قبل الإرسال
  form.addEventListener('submit', function(e) {
    let errors = [];
    let firstInvalid = null;

    // مسح التنسيقات القديمة
    form.querySelectorAll('input, textarea').forEach(f => f.classList.remove('is-invalid'));

    // فحص الحقول المطلوبة
    for (const [name, label] of Object.entries(fields)) {
      const field = form.querySelector(`[name="${name}"]`);
      if (!field) continue;

      if (!field.value.trim()) {
        errors.push(`${label} مطلوب`);
        field.classList.add('is-invalid');
        if (!firstInvalid) firstInvalid = field;
      }
    }

    // فحص البريد
    const email = form.querySelector('[name="email"]');
    if (email && email.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
      errors.push('البريد الإلكتروني غير صحيح');
      email.classList.add('is-invalid');
      if (!firstInvalid) firstInvalid = email;
    }

    // فحص كلمة المرور
    const password = form.querySelector('[name="password"]');
    const confirm = form.querySelector('[name="password_confirmation"]');
    if (password && password.value && password.value.length < 6) {
      errors.push('كلمة المرور يجب أن تكون 6 أحرف على الأقل');
      password.classList.add('is-invalid');
      if (!firstInvalid) firstInvalid = password;
    }
    if (password && confirm && password.value !== confirm.value) {
      errors.push('كلمتا المرور غير متطابقتين');
      confirm.classList.add('is-invalid');
      if (!firstInvalid) firstInvalid = confirm;
    }

    // فحص رقم الهاتف
    const phone = form.querySelector('[name="phone"]');
    if (phone && phone.value.trim() && !/^\d{7,15}$/.test(phone.value.replace(/\D/g, ''))) {
      errors.push('رقم التواصل غير صحيح (7-15 رقم)');
      phone.classList.add('is-invalid');
      if (!firstInvalid) firstInvalid = phone;
    }

    // إذا كان هناك أخطاء
    if (errors.length > 0) {
      e.preventDefault();

      // أظهر كل الأخطاء كتنبيهات
      errors.forEach((err, i) => {
        setTimeout(() => {
          if (window.showToast) {
            window.showToast(err, 'error', 5500);
          } else {
            // fallback إذا لم يعمل المكوّن
            alert(err);
          }
        }, i * 150);
      });

      // مرر لأول حقل خطأ
      if (firstInvalid) {
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => firstInvalid.focus(), 300);
      }

      return false;
    }

    // تعطيل الزر أثناء الإرسال
    const btn = form.querySelector('.btn-primary');
    btn.disabled = true;
    btn.innerHTML = '⏳ جاري الإنشاء...';
    btn.style.opacity = '0.7';
  });

  // عرض أخطاء الخادم (إن وُجدت) كنوافذ منبثقة
  @if($errors->any())
    @foreach($errors->all() as $error)
      setTimeout(() => {
        if (window.showToast) {
          window.showToast("{{ $error }}", 'error', 6000);
        }
      }, {{ $loop->index * 150 }});
    @endforeach
  @endif
});
</script>

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

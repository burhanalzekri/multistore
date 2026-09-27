@extends('layouts.app')
@section('title', 'إضافة متجر جديد')
@section('page-title', '➕ إضافة متجر جديد')
@section('page-subtitle', 'أنشئ متجراً جديداً مع مالكه')

@section('content')

<div style="max-width:900px;margin:0 auto;">

  {{-- شريط التنقل --}}
  <div style="margin-bottom:16px;">
    <a href="{{ route('owner.shops.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:white;color:#475569;border-radius:10px;text-decoration:none;font-weight:900;font-size:13px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
      ← رجوع لقائمة المتاجر
    </a>
  </div>

  @if($errors->any())
  <div style="background:#fee2e2;border:2px solid #fecaca;border-radius:14px;padding:16px;margin-bottom:16px;">
    @foreach($errors->all() as $error)
      <div style="color:#b91c1c;font-size:13px;font-weight:800;margin-bottom:4px;">⚠️ {{ $error }}</div>
    @endforeach
  </div>
  @endif

  <form method="POST" action="{{ route('owner.shops.store') }}" enctype="multipart/form-data" id="createShopForm">
    @csrf

    {{-- 🏪 معلومات المتجر --}}
    <div style="background:white;border-radius:16px;padding:24px;margin-bottom:16px;box-shadow:0 2px 12px rgba(0,0,0,0.05);">
      <h2 style="font-size:16px;font-weight:900;color:#1f2937;margin-bottom:16px;padding-bottom:10px;border-bottom:2px solid #fef3c7;">
        🏪 معلومات المتجر
      </h2>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div style="grid-column:1/-1;">
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">اسم المتجر *</label>
          <input type="text" name="name" value="{{ old('name') }}" required placeholder="مثال: متجر العسل الأصلي"
            style="width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:14px;outline:none;transition:all 0.2s;"
            onfocus="this.style.borderColor='#f59e0b';this.style.boxShadow='0 0 0 4px rgba(245,158,11,0.1)'"
            onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow=''">
        </div>

        <div>
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">رقم التواصل *</label>
          <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="777123456"
            style="width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:14px;outline:none;"
            onfocus="this.style.borderColor='#f59e0b';this.style.boxShadow='0 0 0 4px rgba(245,158,11,0.1)'"
            onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow=''">
        </div>

        <div>
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">واتساب</label>
          <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="777123456"
            style="width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:14px;outline:none;"
            onfocus="this.style.borderColor='#f59e0b';this.style.boxShadow='0 0 0 4px rgba(245,158,11,0.1)'"
            onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow=''">
        </div>

        <div style="grid-column:1/-1;">
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">البريد الإلكتروني *</label>
          <input type="email" name="email" value="{{ old('email') }}" required placeholder="shop@example.com"
            style="width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:14px;outline:none;"
            onfocus="this.style.borderColor='#f59e0b';this.style.boxShadow='0 0 0 4px rgba(245,158,11,0.1)'"
            onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow=''">
        </div>

        <div style="grid-column:1/-1;">
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">العنوان *</label>
          <input type="text" name="address" value="{{ old('address') }}" required placeholder="شارع الحوبان، جوار السوق"
            style="width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:14px;outline:none;"
            onfocus="this.style.borderColor='#f59e0b';this.style.boxShadow='0 0 0 4px rgba(245,158,11,0.1)'"
            onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow=''">
        </div>

        <div>
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">المدينة *</label>
          <input type="text" name="city" value="{{ old('city') }}" required placeholder="تعز"
            style="width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:14px;outline:none;"
            onfocus="this.style.borderColor='#f59e0b';this.style.boxShadow='0 0 0 4px rgba(245,158,11,0.1)'"
            onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow=''">
        </div>

        <div>
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">الدولة</label>
          <input type="text" name="country" value="{{ old('country', 'اليمن') }}"
            style="width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:14px;outline:none;"
            onfocus="this.style.borderColor='#f59e0b';this.style.boxShadow='0 0 0 4px rgba(245,158,11,0.1)'"
            onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow=''">
        </div>

        <div>
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">الحالة *</label>
          <select name="status" required style="width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:14px;outline:none;background:white;">
            <option value="active">✅ نشط</option>
            <option value="trial" selected>⏳ تجريبي (30 يوم)</option>
            <option value="suspended">🚫 معطل</option>
          </select>
        </div>

        <div>
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">العملة</label>
          <select name="currency" style="width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:14px;outline:none;background:white;">
            <option value="YER">ريال يمني (YER)</option>
            <option value="SAR">ريال سعودي (SAR)</option>
            <option value="USD">دولار (USD)</option>
          </select>
        </div>

        <div style="grid-column:1/-1;">
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">وصف المتجر</label>
          <textarea name="description" rows="3" placeholder="وصف مختصر للمتجر (اختياري)"
            style="width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:14px;outline:none;resize:vertical;"
            onfocus="this.style.borderColor='#f59e0b';this.style.boxShadow='0 0 0 4px rgba(245,158,11,0.1)'"
            onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow=''">{{ old('description') }}</textarea>
        </div>

        <div style="grid-column:1/-1;">
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">شعار المتجر (Logo)</label>
          <input type="file" name="logo" accept="image/*"
            style="width:100%;padding:12px 16px;border:2px dashed #cbd5e1;border-radius:10px;font-family:inherit;font-size:13px;background:#f8fafc;cursor:pointer;">
          <div style="font-size:11px;color:#9ca3af;margin-top:4px;">PNG، JPG — حتى 2 ميجابايت</div>
        </div>
      </div>
    </div>

    {{-- 👤 بيانات مالك المتجر --}}
    <div style="background:white;border-radius:16px;padding:24px;margin-bottom:16px;box-shadow:0 2px 12px rgba(0,0,0,0.05);">
      <h2 style="font-size:16px;font-weight:900;color:#1f2937;margin-bottom:16px;padding-bottom:10px;border-bottom:2px solid #dbeafe;">
        👤 بيانات مالك المتجر (المدير)
      </h2>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div style="grid-column:1/-1;">
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">الاسم الكامل *</label>
          <input type="text" name="owner_name" value="{{ old('owner_name') }}" required placeholder="مثال: أحمد محمد علي"
            style="width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:14px;outline:none;"
            onfocus="this.style.borderColor='#3b82f6';this.style.boxShadow='0 0 0 4px rgba(59,130,246,0.1)'"
            onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow=''">
        </div>

        <div>
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">البريد الإلكتروني *</label>
          <input type="email" name="owner_email" value="{{ old('owner_email') }}" required placeholder="owner@example.com"
            style="width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:14px;outline:none;"
            onfocus="this.style.borderColor='#3b82f6';this.style.boxShadow='0 0 0 4px rgba(59,130,246,0.1)'"
            onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow=''">
        </div>

        <div>
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">رقم الجوال</label>
          <input type="tel" name="owner_phone" value="{{ old('owner_phone') }}" placeholder="777123456"
            style="width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:14px;outline:none;"
            onfocus="this.style.borderColor='#3b82f6';this.style.boxShadow='0 0 0 4px rgba(59,130,246,0.1)'"
            onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow=''">
        </div>

        <div style="grid-column:1/-1;">
          <label style="display:block;font-weight:800;font-size:13px;color:#374151;margin-bottom:6px;">كلمة المرور *</label>
          <input type="text" name="owner_password" value="{{ old('owner_password', 'shop' . rand(1000, 9999)) }}" required minlength="6"
            style="width:100%;padding:12px 16px;border:2px solid #e5e7eb;border-radius:10px;font-family:monospace;font-size:14px;outline:none;background:#f8fafc;"
            onfocus="this.style.borderColor='#3b82f6';this.style.boxShadow='0 0 0 4px rgba(59,130,246,0.1)'"
            onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow=''">
          <div style="font-size:11px;color:#9ca3af;margin-top:4px;">💡 يمكنك تعديل كلمة المرور المقترحة</div>
        </div>
      </div>
    </div>

    {{-- أزرار الإجراءات --}}
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
      <button type="submit" style="flex:1;min-width:200px;padding:16px;background:linear-gradient(135deg,#fbbf24,#f97316);color:white;border:none;border-radius:14px;font-weight:900;font-size:16px;cursor:pointer;font-family:inherit;box-shadow:0 4px 12px rgba(245,158,11,0.3);transition:all 0.3s;"
        onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px rgba(245,158,11,0.5)'"
        onmouseout="this.style.transform='';this.style.boxShadow='0 4px 12px rgba(245,158,11,0.3)'">
        🚀 إنشاء المتجر
      </button>
      <a href="{{ route('owner.shops.index') }}" style="padding:16px 28px;background:#f1f5f9;color:#475569;border-radius:14px;text-decoration:none;font-weight:900;font-size:16px;text-align:center;">
        إلغاء
      </a>
    </div>

  </form>

</div>

{{-- 🔔 التنبيهات --}}
@include('components.toast')

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('createShopForm');
  if (!form) return;
  form.setAttribute('novalidate', 'novalidate');

  form.addEventListener('submit', function(e) {
    let errors = [];
    let firstInvalid = null;

    form.querySelectorAll('[required]').forEach(function(field) {
      if (!field.value.trim()) {
        let label = field.name;
        let labelEl = field.closest('div')?.querySelector('label');
        if (labelEl) label = labelEl.textContent.replace('*', '').trim();
        errors.push('📌 ' + label + ' مطلوب');
        field.style.borderColor = '#ef4444';
        field.style.background = '#fef2f2';
        if (!firstInvalid) firstInvalid = field;
      }
    });

    let email = form.querySelector('input[name="owner_email"]');
    if (email && email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
      errors.push('📧 البريد الإلكتروني للمالك غير صحيح');
      email.style.borderColor = '#ef4444';
      if (!firstInvalid) firstInvalid = email;
    }

    let shopEmail = form.querySelector('input[name="email"]');
    if (shopEmail && shopEmail.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(shopEmail.value)) {
      errors.push('📧 البريد الإلكتروني للمتجر غير صحيح');
      shopEmail.style.borderColor = '#ef4444';
      if (!firstInvalid) firstInvalid = shopEmail;
    }

    let pwd = form.querySelector('input[name="owner_password"]');
    if (pwd && pwd.value && pwd.value.length < 6) {
      errors.push('🔑 كلمة المرور يجب أن تكون 6 أحرف على الأقل');
      pwd.style.borderColor = '#ef4444';
      if (!firstInvalid) firstInvalid = pwd;
    }

    if (errors.length > 0) {
      e.preventDefault();
      errors.forEach(function(err, i) {
        setTimeout(function() {
          if (window.showToast) window.showToast(err, 'error', 5000);
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
</script>

@endsection

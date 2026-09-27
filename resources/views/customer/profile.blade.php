<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>حسابي — {{ $user->name }}</title>
<script>(function(){const t=localStorage.getItem('theme')||'light';if(t==='dark')document.documentElement.classList.add('dark');})();</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { darkMode: 'class' };</script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/app.css">
</head>
<body class="bg-slate-50 min-h-screen pb-20">

<header class="bg-white shadow-sm sticky top-0 z-50 border-b border-slate-100">
  <div class="max-w-2xl mx-auto px-4 py-3 flex items-center gap-3">
    <a href="/account" class="p-2 rounded-lg hover:bg-slate-100"><i data-lucide="arrow-right" class="w-5 h-5"></i></a>
    <h1 class="font-black">👤 حسابي</h1>
  </div>
</header>

@if(session('success'))
<div class="max-w-2xl mx-auto px-4 pt-4">
  <div class="bg-green-100 text-green-700 p-3 rounded-xl font-bold text-sm">✅ {{ session('success') }}</div>
</div>
@endif

@if($errors->any())
<div class="max-w-2xl mx-auto px-4 pt-4">
  <div class="bg-red-100 text-red-700 p-3 rounded-xl text-sm">
    @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
  </div>
</div>
@endif

<form method="POST" action="/account/profile" class="max-w-2xl mx-auto px-4 py-6 space-y-4">
  @csrf

  <!-- Avatar -->
  <div class="ui-card p-6 text-center">
    <div class="w-24 h-24 mx-auto bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center text-4xl font-black text-white shadow-lg mb-3">
      {{ mb_substr($user->name, 0, 1) }}
    </div>
    <div class="font-black text-lg">{{ $user->name }}</div>
    <div class="text-sm text-slate-500">{{ $user->email }}</div>
  </div>

  <!-- Personal Info -->
  <div class="ui-card p-6 space-y-4">
    <h2 class="font-black">📝 البيانات الشخصية</h2>

    <div>
      <label class="ui-label">الاسم</label>
      <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="ui-input">
    </div>

    <div>
      <label class="ui-label">رقم الجوال</label>
      <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="ui-input">
    </div>

    <div>
      <label class="ui-label">العنوان</label>
      <input type="text" name="address" value="{{ old('address', $user->address) }}" placeholder="الشارع، الحي" class="ui-input">
    </div>

    <div>
      <label class="ui-label">المدينة</label>
      <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="صنعاء" class="ui-input">
    </div>
  </div>

  <!-- Password -->
  <div class="ui-card p-6 space-y-4">
    <h2 class="font-black">🔒 كلمة المرور</h2>
    <p class="text-xs text-slate-500">اتركها فارغة إذا لم ترد التغيير</p>

    <div>
      <label class="ui-label">كلمة المرور الحالية</label>
      <input type="password" name="current_password" class="ui-input" placeholder="••••••••">
    </div>

    <div class="grid grid-cols-2 gap-3">
      <div>
        <label class="ui-label">الجديدة</label>
        <input type="password" name="password" minlength="6" class="ui-input">
      </div>
      <div>
        <label class="ui-label">التأكيد</label>
        <input type="password" name="password_confirmation" class="ui-input">
      </div>
    </div>
  </div>

  <button type="submit" class="ui-btn ui-btn-primary w-full ui-btn-lg">
    <i data-lucide="save" class="w-5 h-5"></i> حفظ التعديلات
  </button>

</form>

<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 flex justify-around z-30 py-2 shadow-lg">
  <a href="/account" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400">
    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">الرئيسية</span>
  </a>
  <a href="/account/orders" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400">
    <i data-lucide="package" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">طلباتي</span>
  </a>
  <a href="/account/wishlist" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400">
    <i data-lucide="heart" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">المفضلة</span>
  </a>
  <a href="/shop" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400">
    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">المتجر</span>
  </a>
  <a href="/account/profile" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-amber-600 bg-amber-50">
    <i data-lucide="user" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">حسابي</span>
  </a>
</nav>

<script>lucide.createIcons();</script>

  {{-- 🔔 التنبيهات --}}
  @include('components.toast')

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

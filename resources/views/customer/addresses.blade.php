<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>عناويني</title>
<script>(function(){const t=localStorage.getItem('theme')||'light';if(t==='dark')document.documentElement.classList.add('dark');})();</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { darkMode: 'class' };</script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/app.css">
</head>
<body class="bg-slate-50 min-h-screen pb-20">

<header class="bg-white shadow-sm sticky top-0 z-50 border-b border-slate-100">
  <div class="max-w-2xl mx-auto px-4 py-3 flex items-center gap-3">
    <a href="/account" class="p-2 rounded-lg hover:bg-slate-100"><i data-lucide="arrow-right" class="w-5 h-5"></i></a>
    <h1 class="font-black">📍 عناويني</h1>
  </div>
</header>

@if(session('success'))
<div class="max-w-2xl mx-auto px-4 pt-4">
  <div style="background:#dcfce7;color:#15803d;padding:12px 16px;border-radius:12px;font-weight:700;">✅ {{ session('success') }}</div>
</div>
@endif

<div class="max-w-2xl mx-auto px-4 py-6">

  <button onclick="document.getElementById('newAddress').style.display='block'; this.style.display='none';" class="ui-btn ui-btn-primary w-full mb-4 ui-btn-lg">
    <i data-lucide="plus" class="w-5 h-5"></i> إضافة عنوان جديد
  </button>

  <!-- Add Form -->
  <div id="newAddress" style="display:none;" class="ui-card p-5 mb-5">
    <h2 class="font-black mb-4">عنوان جديد</h2>
    <form method="POST" action="/account/addresses" class="space-y-3">
      @csrf
      <div class="grid grid-cols-2 gap-3">
        <input type="text" name="label" required placeholder="الاسم (المنزل، العمل...)" class="ui-input">
        <input type="text" name="name" required placeholder="اسمك الكامل" class="ui-input">
      </div>
      <input type="text" name="phone" required placeholder="رقم الجوال" class="ui-input">
      <div class="grid grid-cols-2 gap-3">
        <input type="text" name="city" required placeholder="المدينة (صنعاء)" class="ui-input">
        <input type="text" name="area" required placeholder="الحي/المنطقة" class="ui-input">
      </div>
      <textarea name="address" required rows="2" placeholder="الشارع، علامة مميزة..." class="ui-input"></textarea>
      <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="is_default" value="1" class="w-4 h-4 accent-amber-600">
        <span class="text-sm font-bold">تعيين كعنوان افتراضي</span>
      </label>
      <div class="flex gap-2">
        <button type="submit" class="ui-btn ui-btn-primary flex-1">حفظ</button>
        <button type="button" onclick="document.getElementById('newAddress').style.display='none'; event.target.closest('form').reset();" class="ui-btn ui-btn-secondary">إلغاء</button>
      </div>
    </form>
  </div>

  <!-- Addresses -->
  @forelse($addresses as $a)
  <div class="ui-card p-5 mb-3">
    <div class="flex items-start justify-between mb-3">
      <div class="flex items-center gap-2">
        <span class="px-2 py-1 rounded-full text-xs font-black {{ $a->is_default ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600' }}">
          {{ $a->is_default ? '⭐ افتراضي' : $a->label }}
        </span>
      </div>
      <div class="flex gap-1">
        @if(!$a->is_default)
        <form method="POST" action="/account/addresses/{{ $a->id }}/default">@csrf
          <button class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center" title="تعيين افتراضي">
            <i data-lucide="star" class="w-4 h-4"></i>
          </button>
        </form>
        @endif
        <form method="POST" action="/account/addresses/{{ $a->id }}" onsubmit="return confirm('حذف العنوان؟')">@csrf @method('DELETE')
          <button class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
          </button>
        </form>
      </div>
    </div>
    <div class="text-sm space-y-1">
      <div><b>{{ $a->name }}</b> — {{ $a->phone }}</div>
      <div class="text-slate-600">{{ $a->city }}، {{ $a->area }}</div>
      <div class="text-slate-500 text-xs">{{ $a->address }}</div>
    </div>
  </div>
  @empty
  <div class="ui-card p-10 text-center">
    <div class="text-5xl mb-2">📍</div>
    <div class="font-black mb-1">لا توجد عناوين</div>
    <div class="text-sm text-slate-500">أضف عنوانك الأول للتوصيل الأسرع</div>
  </div>
  @endforelse

</div>

<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 flex justify-around z-30 py-2 shadow-lg">
  <a href="/account" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400"><i data-lucide="layout-dashboard" class="w-5 h-5"></i><span class="text-[10px] font-bold">الرئيسية</span></a>
  <a href="/account/orders" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400"><i data-lucide="package" class="w-5 h-5"></i><span class="text-[10px] font-bold">طلباتي</span></a>
  <a href="/account/loyalty" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400"><i data-lucide="gift" class="w-5 h-5"></i><span class="text-[10px] font-bold">نقاطي</span></a>
  <a href="/account/addresses" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-amber-600 bg-amber-50"><i data-lucide="map-pin" class="w-5 h-5"></i><span class="text-[10px] font-bold">عناويني</span></a>
  <a href="/account/profile" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400"><i data-lucide="user" class="w-5 h-5"></i><span class="text-[10px] font-bold">حسابي</span></a>
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

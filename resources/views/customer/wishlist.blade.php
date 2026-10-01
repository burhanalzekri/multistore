<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>مفضلتي</title>
<script>(function(){const t=localStorage.getItem('theme')||'light';if(t==='dark')document.documentElement.classList.add('dark');})();</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { darkMode: 'class' };</script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/app.css">
</head>
<body class="bg-slate-50 min-h-screen pb-20">

<header class="bg-white shadow-sm sticky top-0 z-50 border-b border-slate-100">
  <div class="max-w-4xl mx-auto px-4 py-3 flex items-center gap-3">
    <a href="/account" class="p-2 rounded-lg hover:bg-slate-100"><i data-lucide="arrow-right" class="w-5 h-5"></i></a>
    <h1 class="font-black">❤️ مفضلتي</h1>
  </div>
</header>

<div class="max-w-4xl mx-auto px-4 py-6">
  @if($products->isEmpty())
  <div class="ui-card"><div class="ui-empty">
    <div class="ui-empty-icon animate-float">❤️</div>
    <div class="ui-empty-title">المفضلة فارغة</div>
    <div class="ui-empty-desc">أضف منتجاتك المفضلة للرجوع إليها</div>
    <a href="/shop" class="ui-btn ui-btn-primary">تصفح المتجر</a>
  </div></div>
  @else
  <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
    @foreach($products as $p)
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm group">
      <a href="/product/{{ $p->id }}" class="block aspect-square bg-gradient-to-br from-amber-100 to-orange-50 overflow-hidden">
        @if($p->image)
          <img src="{{ ($p->image_url ?? Storage::url($p->image)) }}" class="w-full h-full object-cover group-hover:scale-110 transition">
        @else
          <div class="w-full h-full flex items-center justify-center text-5xl">📦</div>
        @endif
      </a>
      <div class="p-3">
        <h3 class="font-black text-xs mb-2 line-clamp-2 min-h-[2rem]">{{ $p->name }}</h3>
        <div class="text-amber-600 font-black text-sm mb-2">{{ number_format($p->price) }} ريال</div>
        <div class="flex gap-2">
          <form method="POST" action="/cart/add/{{ $p->id }}" class="flex-1">@csrf
            <button class="w-full py-2 bg-amber-600 text-white rounded-lg text-xs font-bold">أضف للسلة</button>
          </form>
          <form method="POST" action="/wishlist/toggle/{{ $p->id }}">@csrf
            <button class="px-3 py-2 bg-red-50 text-red-600 rounded-lg text-xs">❌</button>
          </form>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @endif
</div>

<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 flex justify-around z-30 py-2 shadow-lg">
  <a href="/account" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400">
    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">الرئيسية</span>
  </a>
  <a href="/account/orders" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400">
    <i data-lucide="package" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">طلباتي</span>
  </a>
  <a href="/account/wishlist" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-amber-600 bg-amber-50">
    <i data-lucide="heart" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">المفضلة</span>
  </a>
  <a href="/shop" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400">
    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">المتجر</span>
  </a>
  <a href="/account/profile" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400">
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

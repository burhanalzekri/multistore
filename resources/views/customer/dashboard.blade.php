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

<!-- Header -->
<header class="bg-gradient-to-l from-amber-500 to-orange-500 text-white sticky top-0 z-50 shadow-lg">
  <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-full flex items-center justify-center font-black text-xl">
        {{ mb_substr($user->name, 0, 1) }}
      </div>
      <div>
        <div class="font-black text-lg">{{ $user->name }}</div>
        <div class="text-xs opacity-90">{{ $user->phone }}</div>
      </div>
    </div>
    <div class="flex gap-2">
      <a href="/shop" class="p-2 bg-white/20 rounded-lg">
        <i data-lucide="home" class="w-5 h-5"></i>
      </a>
      <form method="POST" action="/account/logout">@csrf
        <button class="p-2 bg-white/20 rounded-lg">
          <i data-lucide="log-out" class="w-5 h-5"></i>
        </button>
      </form>
    </div>
  </div>
</header>

@if(session('success'))
<div class="max-w-4xl mx-auto px-4 pt-4">
  <div class="bg-green-100 text-green-700 p-3 rounded-xl font-bold text-sm">✅ {{ session('success') }}</div>
</div>
@endif

<div class="max-w-4xl mx-auto px-4 py-6">

  <!-- Stats -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
    <div class="ui-stat">
      <div class="ui-stat-icon bg-gradient-to-br from-blue-400 to-indigo-500"><i data-lucide="package" class="w-5 h-5 text-white"></i></div>
      <div class="ui-stat-label">طلباتي</div>
      <div class="ui-stat-value">{{ $stats['orders'] }}</div>
    </div>
    <div class="ui-stat">
      <div class="ui-stat-icon bg-gradient-to-br from-red-400 to-pink-500"><i data-lucide="heart" class="w-5 h-5 text-white"></i></div>
      <div class="ui-stat-label">المفضلة</div>
      <div class="ui-stat-value">{{ $stats['wishlist'] }}</div>
    </div>
    <div class="ui-stat">
      <div class="ui-stat-icon bg-gradient-to-br from-amber-400 to-orange-500"><i data-lucide="clock" class="w-5 h-5 text-white"></i></div>
      <div class="ui-stat-label">بانتظار الدفع</div>
      <div class="ui-stat-value">{{ $stats['pending'] }}</div>
    </div>
    <div class="ui-stat">
      <div class="ui-stat-icon bg-gradient-to-br from-green-400 to-emerald-500"><i data-lucide="trending-up" class="w-5 h-5 text-white"></i></div>
      <div class="ui-stat-label">إجمالي الشراء</div>
      <div class="ui-stat-value text-green-600">{{ number_format($stats['total_spent']) }}</div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="grid grid-cols-3 gap-3 mb-6">
    <a href="/account/orders" class="ui-card ui-card-hover p-4 text-center">
      <div class="text-3xl mb-2">📦</div>
      <div class="font-black text-sm">طلباتي</div>
    </a>
    <a href="/account/wishlist" class="ui-card ui-card-hover p-4 text-center">
      <div class="text-3xl mb-2">❤️</div>
      <div class="font-black text-sm">مفضلتي</div>
    </a>
    <a href="/account/profile" class="ui-card ui-card-hover p-4 text-center">
      <div class="text-3xl mb-2">⚙️</div>
      <div class="font-black text-sm">بياناتي</div>
    </a>
  </div>

  <!-- Recent Orders -->
  <div class="ui-card">
    <div class="p-5 border-b border-slate-100 flex justify-between items-center">
      <h2 class="font-black flex items-center gap-2">
        <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
        آخر طلباتي
      </h2>
      <a href="/account/orders" class="text-sm text-amber-600 font-bold">عرض الكل ←</a>
    </div>

    <div class="divide-y divide-slate-100">
      @forelse($orders as $o)
      <a href="/account/orders/{{ $o->id }}" class="flex items-center gap-3 p-4 hover:bg-slate-50">
        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center">
          <i data-lucide="package" class="w-5 h-5 text-amber-600"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="font-mono text-xs text-slate-500">{{ $o->order_number }}</div>
          <div class="text-xs text-slate-400">{{ $o->created_at->diffForHumans() }}</div>
        </div>
        <div class="text-left">
          <div class="font-black text-amber-600">{{ number_format($o->total) }}</div>
          <span class="ui-badge {{ \App\Support\StatusHelper::badge($o->status) }}">
            {{ \App\Support\StatusHelper::label($o->status) }}
          </span>
        </div>
      </a>
      @empty
      <div class="ui-empty">
        <div class="ui-empty-icon animate-float">📦</div>
        <div class="ui-empty-title">لا توجد طلبات بعد</div>
        <div class="ui-empty-desc">ابدأ التسوق الآن</div>
        <a href="/shop" class="ui-btn ui-btn-primary">تصفح المتجر</a>
      </div>
      @endforelse
    </div>
  </div>
</div>

<!-- Bottom Nav -->
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 flex justify-around z-30 py-2 shadow-lg">
  <a href="/account" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-amber-600 bg-amber-50">
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

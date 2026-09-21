<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>@yield('title', 'لوحة التحكم') — منصتي</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/app.css">
<style>
  body { font-family: 'Cairo', sans-serif; }
  [x-cloak] { display: none !important; }
</style>
</head>
<body class="bg-slate-50" x-data="{ sidebarOpen: false }">

<!-- Toast Container -->
<div id="toast-container"></div>

<!-- Overlay -->
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
     x-transition.opacity
     class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

<!-- Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
       class="fixed top-0 right-0 h-full w-72 bg-gradient-to-b from-slate-900 to-slate-800 text-white z-40 transform transition-transform duration-300 ease-out overflow-y-auto">

  <!-- Logo -->
  <div class="p-6 border-b border-slate-700/50">
    <a href="/dashboard" class="flex items-center gap-3 group">
      <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center text-2xl shadow-lg group-hover:scale-110 transition-transform">
        🍯
      </div>
      <div>
        <div class="font-black text-xl text-amber-500">منصتي</div>
        <div class="text-xs text-slate-400">لوحة التحكم</div>
      </div>
    </a>
  </div>

  <!-- Shop Info -->
  @if(isset($currentShop))
  <div class="mx-4 mt-4 p-3 bg-slate-800/50 rounded-xl border border-slate-700/50">
    <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
      <span class="pulse-dot"></span>
      متجر نشط
    </div>
    <div class="font-bold text-sm truncate">{{ $currentShop->name }}</div>
  </div>
  @endif

  <!-- Nav -->
  <nav class="p-4 space-y-1 text-sm">
    @php
      $route = request()->path();
      $items = [
        ['href' => '/dashboard', 'icon' => 'layout-dashboard', 'label' => 'الرئيسية', 'match' => $route === 'dashboard'],
        ['href' => '/dashboard/products', 'icon' => 'package', 'label' => 'المنتجات', 'match' => str_starts_with($route, 'dashboard/products')],
        ['href' => '/dashboard/orders', 'icon' => 'shopping-cart', 'label' => 'الطلبات', 'match' => str_starts_with($route, 'dashboard/orders')],
        ['href' => '/dashboard/sms', 'icon' => 'message-square', 'label' => 'رسائل SMS', 'match' => str_starts_with($route, 'dashboard/sms')],
        ['href' => '/dashboard/payments', 'icon' => 'wallet', 'label' => 'المدفوعات', 'match' => str_starts_with($route, 'dashboard/payments')],
      ];
    @endphp

    @foreach($items as $item)
    <a href="{{ $item['href'] }}"
       class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all
              {{ $item['match'] ? 'active bg-amber-500/10 text-amber-400 font-bold' : 'text-slate-300 hover:bg-slate-700/50' }}">
      <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
      <span>{{ $item['label'] }}</span>
    </a>
    @endforeach

    <div class="border-t border-slate-700/50 my-3"></div>

    <a href="/dashboard/settings" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all
              {{ str_starts_with($route, 'dashboard/settings') ? 'active bg-amber-500/10 text-amber-400 font-bold' : 'text-slate-300 hover:bg-slate-700/50' }}">
      <i data-lucide="settings" class="w-5 h-5"></i>
      <span>الإعدادات</span>
    </a>

    <a href="/shop" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-700/50 transition-all">
      <i data-lucide="external-link" class="w-5 h-5"></i>
      <span>زيارة المتجر</span>
    </a>
  </nav>

  <!-- User Section -->
  @auth
  <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-700/50 bg-slate-900/80 backdrop-blur">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center font-black text-slate-900">
        {{ mb_substr(auth()->user()->name, 0, 1) }}
      </div>
      <div class="flex-1 min-w-0">
        <div class="font-bold text-sm truncate">{{ auth()->user()->name }}</div>
        <div class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</div>
      </div>
      <form method="POST" action="/logout">
        @csrf
        <button class="p-2 text-slate-400 hover:text-red-400 transition" title="خروج">
          <i data-lucide="log-out" class="w-5 h-5"></i>
        </button>
      </form>
    </div>
  </div>
  @endauth
</aside>

<!-- Main -->
<div class="lg:mr-72 min-h-screen pb-20 lg:pb-0">

  <!-- Top Header -->
  <header class="bg-white/80 backdrop-blur-lg shadow-sm sticky top-0 z-20 border-b border-slate-100">
    <div class="px-4 py-3 flex justify-between items-center">
      <div class="flex items-center gap-3">
        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl hover:bg-slate-100 transition">
          <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        <div>
          <h1 class="font-black text-lg text-slate-800">@yield('page-title', 'لوحة التحكم')</h1>
          <p class="text-xs text-slate-400 hidden sm:block">@yield('page-subtitle', now()->translatedFormat('l، j F Y'))</p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="/dashboard/sms" class="relative p-2 rounded-xl hover:bg-slate-100 transition">
          <i data-lucide="bell" class="w-5 h-5 text-slate-600"></i>
          @php $pendingCount = \App\Models\SmsInbox::where('status','review')->count(); @endphp
          @if($pendingCount > 0)
          <span class="absolute top-1 left-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold animate-pulse">
            {{ $pendingCount }}
          </span>
          @endif
        </a>
      </div>
    </div>
  </header>

  <!-- Content -->
  <main class="p-4 lg:p-6">
    @yield('content')
  </main>
</div>

<!-- Bottom Nav (Mobile only) -->
<nav class="bottom-nav lg:hidden">
  @php
    $route = request()->path();
    $bottomItems = [
      ['href' => '/dashboard', 'icon' => 'home', 'label' => 'الرئيسية', 'match' => $route === 'dashboard'],
      ['href' => '/dashboard/products', 'icon' => 'package', 'label' => 'المنتجات', 'match' => str_starts_with($route, 'dashboard/products')],
      ['href' => '/dashboard/orders', 'icon' => 'shopping-cart', 'label' => 'الطلبات', 'match' => str_starts_with($route, 'dashboard/orders')],
      ['href' => '/dashboard/sms', 'icon' => 'message-square', 'label' => 'SMS', 'match' => str_starts_with($route, 'dashboard/sms')],
      ['href' => '/dashboard/settings', 'icon' => 'settings', 'label' => 'الإعدادات', 'match' => str_starts_with($route, 'dashboard/settings')],
    ];
  @endphp
  @foreach($bottomItems as $b)
  <a href="{{ $b['href'] }}" class="bottom-nav-item {{ $b['match'] ? 'active' : '' }}">
    <i data-lucide="{{ $b['icon'] }}" class="w-5 h-5"></i>
    <span>{{ $b['label'] }}</span>
  </a>
  @endforeach
</nav>

<script>
// ═════════ التهيئة ═════════
lucide.createIcons();

// ═════════ Toast System ═════════
window.showToast = function(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.innerHTML = `
    <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5"></i>
    <span>${message}</span>
  `;
  document.getElementById('toast-container').appendChild(toast);
  lucide.createIcons();

  setTimeout(() => toast.classList.add('show'), 100);
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 400);
  }, 3500);
};

@if(session('success'))
  showToast('{{ session('success') }}', 'success');
@endif
@if(session('error'))
  showToast('{{ session('error') }}', 'error');
@endif
</script>

@stack('scripts')
</body>
</html>

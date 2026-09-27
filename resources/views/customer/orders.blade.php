<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>طلباتي</title>
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
    <a href="/account" class="p-2 rounded-lg hover:bg-slate-100">
      <i data-lucide="arrow-right" class="w-5 h-5"></i>
    </a>
    <h1 class="font-black">📦 طلباتي</h1>
  </div>
</header>

<div class="max-w-4xl mx-auto px-4 py-6">
  @forelse($orders as $o)
  <a href="/account/orders/{{ $o->id }}" class="ui-card ui-card-hover block p-4 mb-3">
    <div class="flex justify-between items-start mb-3">
      <div>
        <div class="font-mono text-sm font-bold">{{ $o->order_number }}</div>
        <div class="text-xs text-slate-500">{{ $o->created_at->diffForHumans() }}</div>
      </div>
      <span class="ui-badge {{ \App\Support\StatusHelper::badge($o->status) }}">
        {{ \App\Support\StatusHelper::label($o->status) }}
      </span>
    </div>

    <div class="flex justify-between items-center">
      <div class="flex gap-1">
        @foreach($o->items->take(3) as $item)
        <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center text-lg">📦</div>
        @endforeach
        @if($o->items->count() > 3)
        <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-xs font-bold">+{{ $o->items->count() - 3 }}</div>
        @endif
      </div>
      <div class="text-left">
        <div class="font-black text-amber-600 text-lg">{{ number_format($o->total) }} ريال</div>
        <div class="text-xs text-slate-400">{{ $o->items->count() }} منتج</div>
      </div>
    </div>
  </a>
  @empty
  <div class="ui-card"><div class="ui-empty">
    <div class="ui-empty-icon animate-float">📦</div>
    <div class="ui-empty-title">لا توجد طلبات</div>
    <div class="ui-empty-desc">ابدأ التسوق من متجرنا</div>
    <a href="/shop" class="ui-btn ui-btn-primary">تصفح المتجر</a>
  </div></div>
  @endforelse

  @if($orders->hasPages())
  <div class="mt-6">{{ $orders->links() }}</div>
  @endif
</div>

<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 flex justify-around z-30 py-2 shadow-lg">
  <a href="/account" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400">
    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">الرئيسية</span>
  </a>
  <a href="/account/orders" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-amber-600 bg-amber-50">
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

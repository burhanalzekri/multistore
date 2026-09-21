<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $shop->name }} — تسوق الآن</title>
<script>(function(){const t=localStorage.getItem('theme')||'light';if(t==='dark')document.documentElement.classList.add('dark');})();</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { darkMode: 'class' };</script>
<script src="https://unpkg.com/lucide@latest"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Cairo', sans-serif; }
  [x-cloak] { display: none !important; }
  .no-scrollbar::-webkit-scrollbar { display: none; }
  .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  html.dark body { background: #0f172a; color: #e2e8f0; }
  html.dark .bg-white { background: #1e293b !important; }
  html.dark .bg-slate-50 { background: #0f172a !important; }
  html.dark .bg-slate-100 { background: #334155 !important; }
  html.dark .text-slate-800, html.dark .text-slate-700 { color: #f1f5f9 !important; }
  html.dark .text-slate-600 { color: #cbd5e1 !important; }
  html.dark .text-slate-500, html.dark .text-slate-400 { color: #94a3b8 !important; }
  html.dark .border-slate-100, html.dark .border-slate-200 { border-color: #334155 !important; }
  html.dark header { background: rgba(15,23,42,0.9) !important; border-color: #334155 !important; }
  html.dark input, html.dark select { background: #1e293b !important; border-color: #334155 !important; color: #f1f5f9 !important; }
  html.dark .card-product { background: #1e293b !important; }
  html.dark .filter-chip { background: #1e293b !important; border-color: #334155 !important; color: #cbd5e1 !important; }
  html.dark .filter-chip.active { background: linear-gradient(to left, #f59e0b, #f97316) !important; color: white !important; }

  .theme-toggle { position: relative; width: 52px; height: 30px; border-radius: 999px; background: linear-gradient(135deg, #60a5fa, #3b82f6); cursor: pointer; border: none; padding: 0; flex-shrink: 0; }
  html.dark .theme-toggle { background: linear-gradient(135deg, #1e293b, #334155); }
  .theme-toggle-thumb { position: absolute; top: 3px; right: 3px; width: 24px; height: 24px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; font-size: 12px; transition: transform 0.4s cubic-bezier(0.68,-0.55,0.265,1.55); box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
  html.dark .theme-toggle-thumb { transform: translateX(-22px); background: #fbbf24; }

  .marquee { overflow: hidden; white-space: nowrap; }
  .marquee-content { display: inline-block; animation: marquee 30s linear infinite; }
  @keyframes marquee { from { transform: translateX(100%); } to { transform: translateX(-100%); } }

  .card-product { background: white; border-radius: 20px; overflow: hidden; transition: all 0.35s cubic-bezier(0.4,0,0.2,1); position: relative; display: flex; flex-direction: column; }
  .card-product:hover { transform: translateY(-6px); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15); }
  .card-product:hover .card-img { transform: scale(1.1) rotate(-2deg); }
  .card-img { transition: transform 0.6s cubic-bezier(0.4,0,0.2,1); }

  .badge { position: absolute; top: 10px; right: 10px; z-index: 10; padding: 4px 10px; border-radius: 999px; font-size: 0.65rem; font-weight: 900; box-shadow: 0 4px 12px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 4px; }
  .badge-pulse { animation: badgePulse 2s ease-in-out infinite; }
  @keyframes badgePulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }

  .stock-bar { height: 6px; border-radius: 999px; background: #e2e8f0; overflow: hidden; margin-top: 8px; }
  .stock-fill { height: 100%; border-radius: 999px; transition: width 0.5s ease; }

  @keyframes cardIn { from { opacity: 0; transform: translateY(20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
  .card-animate { animation: cardIn 0.5s ease-out forwards; }

  @keyframes slideDown { from { opacity: 0; transform: translate(-50%, -100px); } to { opacity: 1; transform: translate(-50%, 0); } }
  @keyframes slideUp { from { opacity: 1; transform: translate(-50%, 0); } to { opacity: 0; transform: translate(-50%, -100px); } }
  .toast-enter { animation: slideDown 0.5s cubic-bezier(0.68,-0.55,0.265,1.55); }
  .toast-leave { animation: slideUp 0.4s ease-in forwards; }

  .carousel-wrap { --circle-size: 180px; }
  .circle-product { scroll-snap-align: center; display: flex; flex-direction: column; align-items: center; cursor: pointer; transition: transform 0.3s ease; width: var(--circle-size); flex-shrink: 0; }
  .circle-product:hover { transform: translateY(-10px); }
  .circle-wrap { position: relative; width: var(--circle-size); height: var(--circle-size); }
  .circle-img { position: absolute; inset: 0; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, #fef3c7, #fdba74, #fb923c); display: flex; align-items: center; justify-content: center; font-size: calc(var(--circle-size) * 0.4); box-shadow: 0 20px 40px -10px rgba(245,158,11,0.45), 0 8px 20px -5px rgba(0,0,0,0.1); z-index: 2; }
  .circle-img img { width: 100%; height: 100%; object-fit: cover; }
  .glow-ring { position: absolute; inset: -8px; border-radius: 50%; background: conic-gradient(from 0deg, #fbbf24, #f59e0b, #fb923c, #ef4444, #f59e0b, #fbbf24); opacity: 0; transition: opacity 0.3s; animation: spin 4s linear infinite; z-index: 1; filter: blur(10px); }
  .circle-product:hover .glow-ring { opacity: 0.8; }
  @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
  .reflection { position: absolute; top: 105%; left: 0; right: 0; height: calc(var(--circle-size) * 0.6); background: linear-gradient(to bottom, rgba(251,146,60,0.35), rgba(251,146,60,0)); border-radius: 50%; filter: blur(8px); pointer-events: none; z-index: 0; }
  .p-badge { position: absolute; top: 5px; left: 5px; z-index: 10; padding: 4px 10px; border-radius: 999px; font-size: 0.65rem; font-weight: 900; box-shadow: 0 4px 12px rgba(0,0,0,0.15); white-space: nowrap; }
  .p-info { margin-top: 30px; text-align: center; max-width: var(--circle-size); }
  .p-name { font-weight: 900; color: #1e293b; font-size: 0.85rem; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  html.dark .p-name { color: #f1f5f9; }
  .p-price { color: #f59e0b; font-weight: 900; font-size: 1.1rem; }

  .banner-slide { position: absolute; inset: 0; opacity: 0; transition: opacity 1s ease; }
  .banner-slide.active { opacity: 1; }

  .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

  .scroll-top { position: fixed; bottom: 24px; right: 24px; width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #f97316); color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgba(245,158,11,0.4); cursor: pointer; transition: all 0.3s; z-index: 40; opacity: 0; pointer-events: none; }
  .scroll-top.show { opacity: 1; pointer-events: auto; }
  .scroll-top:hover { transform: translateY(-4px); }
</style>
</head>
<body class="bg-slate-50" x-data="storeApp()" x-cloak>

<div id="toast-container" class="fixed top-4 left-1/2 -translate-x-1/2 z-[9999] pointer-events-none"></div>

<!-- Marquee -->
<div class="bg-gradient-to-l from-amber-500 via-orange-500 to-red-500 text-white text-sm font-bold py-2">
  <div class="marquee"><div class="marquee-content">
    🎉 شحن مجاني للطلبات فوق 50,000 ريال &nbsp;•&nbsp; ⚡ توصيل سريع خلال 24 ساعة &nbsp;•&nbsp; 💚 خصم 10% على أول طلب &nbsp;•&nbsp; 🔥 عروض حصرية كل يوم &nbsp;•&nbsp;
    🎉 شحن مجاني للطلبات فوق 50,000 ريال &nbsp;•&nbsp; ⚡ توصيل سريع خلال 24 ساعة &nbsp;•&nbsp; 💚 خصم 10% على أول طلب &nbsp;•&nbsp; 🔥 عروض حصرية كل يوم
  </div></div>
</div>

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-sm sticky top-0 z-50 border-b border-slate-100">
  <div class="max-w-7xl mx-auto px-3 sm:px-4 py-3">
    <div class="flex justify-between items-center gap-2 sm:gap-3">
      <a href="/shop" class="flex items-center gap-2 sm:gap-3">
        <div class="w-10 h-10 sm:w-11 sm:h-11 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center text-lg sm:text-xl shadow-lg">🏪</div>
        <div class="hidden sm:block">
          <div class="font-black text-base sm:text-lg">{{ $shop->name }}</div>
          <div class="text-xs text-slate-400 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> متجر موثوق</div>
        </div>
      </a>

      <div class="flex items-center gap-1.5 sm:gap-2">
        <button class="theme-toggle" @click="toggleTheme()"><span class="theme-toggle-thumb" x-text="isDark ? '🌙' : '☀️'"></span></button>

        <!-- Wishlist (A) -->
        <a href="/wishlist" class="relative w-10 h-10 rounded-xl bg-slate-100 hover:bg-red-50 flex items-center justify-center transition" title="المفضلة">
          <i data-lucide="heart" class="w-5 h-5 text-red-500"></i>
          @if(count($wishlist) > 0)
          <span class="absolute -top-1 -left-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-black">{{ count($wishlist) }}</span>
          @endif
        </a>

        <button @click="showNotifications = !showNotifications" class="relative w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition">
          <i data-lucide="bell" class="w-5 h-5 text-slate-600"></i>
          <span class="absolute -top-1 -left-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-black animate-pulse">3</span>
        </button>

        <a href="/cart" class="relative flex items-center gap-2 px-3 sm:px-4 py-2.5 bg-gradient-to-l from-amber-500 to-orange-500 text-white rounded-xl font-bold text-sm">
          <i data-lucide="shopping-cart" class="w-4 h-4"></i>
          <span class="hidden sm:inline">السلة</span>
          @php $cartCount = array_sum(session('cart', [])); @endphp
          @if($cartCount > 0)
          <span class="absolute -top-2 -left-2 w-6 h-6 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-black animate-pulse">{{ $cartCount }}</span>
          @endif
        </a>
      </div>
    </div>

    <!-- Notifications -->
    <div x-show="showNotifications" x-transition @click.outside="showNotifications = false" class="mt-3 bg-white rounded-2xl shadow-xl border border-slate-100 p-3 space-y-2">
      <div class="text-xs font-bold text-slate-500 px-2 pb-2 border-b">الإشعارات</div>
      <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50">
        <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center"><i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i></div>
        <div class="flex-1"><div class="font-bold text-sm">تم تأكيد طلبك</div><div class="text-xs text-slate-500">#ORD-001 قيد التحضير</div></div>
      </div>
      <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50">
        <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center"><i data-lucide="truck" class="w-5 h-5 text-amber-600"></i></div>
        <div class="flex-1"><div class="font-bold text-sm">طلبك في الطريق</div><div class="text-xs text-slate-500">سيصل خلال 2 ساعات</div></div>
      </div>
    </div>
  </div>
</header>

<!-- Hero Banner -->
<section class="max-w-7xl mx-auto px-3 sm:px-4 pt-4 sm:pt-6">
  <div class="relative rounded-3xl overflow-hidden shadow-2xl h-[220px] sm:h-[280px] lg:h-[340px]">
    <div class="banner-slide active bg-gradient-to-l from-amber-500 via-orange-500 to-red-500">
      <div class="absolute inset-0 flex items-center p-6 sm:p-10 lg:p-12">
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
        <div class="relative max-w-2xl text-white">
          <span class="inline-block bg-white/20 backdrop-blur px-3 py-1 rounded-full text-xs font-bold mb-3">🎉 خصم 20%</span>
          <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black mb-3">تسوّق بذكاء من <span class="text-yellow-200">{{ $shop->name }}</span></h1>
          <a href="#all-products" class="inline-flex items-center gap-2 px-5 py-3 bg-white text-amber-600 rounded-xl font-black text-sm sm:text-base hover:-translate-y-1 transition">
            <i data-lucide="shopping-bag" class="w-5 h-5"></i> تصفح الآن
          </a>
        </div>
      </div>
    </div>
    <div class="banner-slide bg-gradient-to-l from-blue-500 via-indigo-500 to-purple-600">
      <div class="absolute inset-0 flex items-center p-6 sm:p-10 lg:p-12">
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
        <div class="relative max-w-2xl text-white">
          <span class="inline-block bg-white/20 backdrop-blur px-3 py-1 rounded-full text-xs font-bold mb-3">🚚 شحن مجاني</span>
          <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black mb-3">توصيل <span class="text-yellow-200">مجاني</span> للطلبات الكبيرة</h1>
        </div>
      </div>
    </div>
    <div class="banner-slide bg-gradient-to-l from-green-500 via-emerald-500 to-teal-600">
      <div class="absolute inset-0 flex items-center p-6 sm:p-10 lg:p-12">
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
        <div class="relative max-w-2xl text-white">
          <span class="inline-block bg-white/20 backdrop-blur px-3 py-1 rounded-full text-xs font-bold mb-3">💚 عملاء جدد</span>
          <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black mb-3">خصم <span class="text-yellow-200">10%</span> على أول طلب</h1>
        </div>
      </div>
    </div>
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
      <button @click="bannerIndex = 0" :class="bannerIndex === 0 ? 'w-8 bg-white' : 'w-2 bg-white/50'" class="h-2 rounded-full transition-all"></button>
      <button @click="bannerIndex = 1" :class="bannerIndex === 1 ? 'w-8 bg-white' : 'w-2 bg-white/50'" class="h-2 rounded-full transition-all"></button>
      <button @click="bannerIndex = 2" :class="bannerIndex === 2 ? 'w-8 bg-white' : 'w-2 bg-white/50'" class="h-2 rounded-full transition-all"></button>
    </div>
  </div>
</section>

<!-- Features -->
<section class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">
  <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3">
    <div class="bg-white rounded-2xl p-3 sm:p-4 flex items-center gap-2 sm:gap-3 shadow-sm"><div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-green-50 flex items-center justify-center shrink-0"><i data-lucide="truck" class="w-5 h-5 text-green-600"></i></div><div><div class="font-black text-xs sm:text-sm">توصيل سريع</div><div class="text-xs text-slate-400">لجميع المناطق</div></div></div>
    <div class="bg-white rounded-2xl p-3 sm:p-4 flex items-center gap-2 sm:gap-3 shadow-sm"><div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-blue-50 flex items-center justify-center shrink-0"><i data-lucide="shield-check" class="w-5 h-5 text-blue-600"></i></div><div><div class="font-black text-xs sm:text-sm">دفع آمن</div><div class="text-xs text-slate-400">تحويل موثوق</div></div></div>
    <div class="bg-white rounded-2xl p-3 sm:p-4 flex items-center gap-2 sm:gap-3 shadow-sm"><div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0"><i data-lucide="award" class="w-5 h-5 text-amber-600"></i></div><div><div class="font-black text-xs sm:text-sm">جودة عالية</div><div class="text-xs text-slate-400">منتجات أصلية</div></div></div>
    <div class="bg-white rounded-2xl p-3 sm:p-4 flex items-center gap-2 sm:gap-3 shadow-sm"><div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-purple-50 flex items-center justify-center shrink-0"><i data-lucide="headphones" class="w-5 h-5 text-purple-600"></i></div><div><div class="font-black text-xs sm:text-sm">دعم فني</div><div class="text-xs text-slate-400">24/7 متاح</div></div></div>
  </div>
</section>

@if($products->count() > 0)
<!-- Carousel with Auto-scroll -->
<section class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-8">
  <div class="flex items-center justify-between mb-4 sm:mb-6 flex-wrap gap-3">
    <div>
      <h2 class="text-xl sm:text-2xl lg:text-3xl font-black flex items-center gap-2"><i data-lucide="sparkles" class="w-6 h-6 sm:w-7 sm:h-7 text-amber-500"></i> المنتجات المميزة</h2>
      <p class="text-xs sm:text-sm text-slate-500 mt-1">اسحب أو استخدم الأزرار للتنقل</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
      <div class="hidden sm:flex items-center gap-2 bg-white rounded-xl px-3 py-2 shadow-sm border border-slate-200">
        <button @click="decreaseZoom()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-amber-100 flex items-center justify-center"><i data-lucide="minus" class="w-4 h-4 text-slate-600"></i></button>
        <input type="range" min="140" max="300" step="20" x-model.number="zoom" class="w-20">
        <button @click="increaseZoom()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-amber-100 flex items-center justify-center"><i data-lucide="plus" class="w-4 h-4 text-slate-600"></i></button>
      </div>
      <button @click="autoScroll = !autoScroll" :class="autoScroll ? 'bg-amber-500 text-white' : 'bg-white text-slate-600'" class="w-10 h-10 rounded-xl shadow-sm border border-slate-200 flex items-center justify-center transition" :title="autoScroll ? 'إيقاف تلقائي' : 'تشغيل تلقائي'">
        <i data-lucide="{{ autoScroll ? 'pause' : 'play' }}" class="w-4 h-4"></i>
      </button>
      <button @click="scrollCarousel('right')" class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-200 flex items-center justify-center"><i data-lucide="chevron-right" class="w-5 h-5 text-slate-600"></i></button>
      <button @click="scrollCarousel('left')" class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-200 flex items-center justify-center"><i data-lucide="chevron-left" class="w-5 h-5 text-slate-600"></i></button>
    </div>
  </div>

  <div class="bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 rounded-3xl shadow-inner border-2 border-amber-100 carousel-wrap overflow-hidden" :style="`--circle-size: ${zoom}px`">
    <div id="carousel-track" class="no-scrollbar" style="overflow-x:auto;scroll-behavior:smooth;scroll-snap-type:x mandatory;padding:30px 16px 50px;">
      <div style="display:flex;gap:24px;padding:0 8px;min-width:max-content;">
        @foreach($products as $p)
        <div class="circle-product" onclick="location='/product/{{ $p->id }}'">
          <div class="circle-wrap">
            <div class="glow-ring"></div>
            <div class="circle-img">
              @if($p->image)<img src="{{ Storage::url($p->image) }}" alt="{{ $p->name }}">@else 📦 @endif
            </div>
            <div class="reflection"></div>
            @if($p->stock > 0 && $p->stock <= 5)
            <div class="p-badge bg-red-500 text-white badge-pulse">🔥 آخر {{ $p->stock }}</div>
            @elseif($p->stock > 0 && $p->stock <= 20)
            <div class="p-badge bg-amber-500 text-white">⚠️ محدودة</div>
            @elseif($p->stock > 20)
            <div class="p-badge bg-white text-green-700">✅ متوفر</div>
            @else
            <div class="p-badge bg-slate-700 text-white">❌ نفد</div>
            @endif
          </div>
          <div class="p-info">
            <div class="p-name">{{ $p->name }}</div>
            <div class="p-price">{{ number_format($p->price) }} <span class="text-xs text-slate-400">ريال</span></div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    <div class="flex justify-center gap-2 pb-4">
      @foreach($products as $i => $p)
      <button @click="goToSlide({{ $i }})" :class="activeSlide === {{ $i }} ? 'w-8 bg-amber-500' : 'w-2.5 bg-slate-300'" class="h-2.5 rounded-full transition-all"></button>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- Categories Filter (B) -->
@if($categories->count() > 0)
<section class="max-w-7xl mx-auto px-3 sm:px-4 pb-4">
  <div class="flex gap-2 overflow-x-auto pb-2 no-scrollbar">
    <a href="/shop" class="whitespace-nowrap px-4 py-2.5 rounded-xl text-sm font-bold transition {{ !request('category') ? 'bg-gradient-to-l from-amber-500 to-orange-500 text-white shadow-lg' : 'bg-white border border-slate-200 text-slate-600 hover:border-amber-500' }}">
      🏪 الكل
    </a>
    @foreach($categories as $c)
    <a href="/shop?category={{ $c->id }}" class="whitespace-nowrap px-4 py-2.5 rounded-xl text-sm font-bold transition flex items-center gap-2 {{ request('category') == $c->id ? 'bg-gradient-to-l from-amber-500 to-orange-500 text-white shadow-lg' : 'bg-white border border-slate-200 text-slate-600 hover:border-amber-500' }}">
      <span class="text-lg">{{ $c->icon ?? '📂' }}</span>
      {{ $c->name }}
    </a>
    @endforeach
  </div>
</section>
@endif

<!-- Filters Bar -->
<section id="all-products" class="max-w-7xl mx-auto px-3 sm:px-4 pt-2">
  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-3 sm:p-4">
    <div class="flex items-center justify-between mb-3">
      <h2 class="text-lg sm:text-xl font-black flex items-center gap-2"><i data-lucide="grid-3x3" class="w-5 h-5 text-amber-500"></i> كل المنتجات</h2>
      <button x-show="search || filter !== 'all' || sortBy !== 'latest'" @click="resetFilters()" class="text-amber-600 font-bold text-xs sm:text-sm flex items-center gap-1"><i data-lucide="refresh-cw" class="w-3 h-3"></i> إعادة تعيين</button>
    </div>
    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 mb-3">
      <div class="relative flex-1">
        <i data-lucide="search" class="w-5 h-5 absolute top-1/2 right-4 -translate-y-1/2 text-slate-400"></i>
        <input type="text" x-model="search" placeholder="ابحث عن منتج..." class="w-full pr-12 pl-4 py-2.5 sm:py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none text-sm">
      </div>
      <select x-model="sortBy" class="w-full sm:w-56 px-4 py-2.5 sm:py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none text-sm font-bold">
        <option value="latest">🆕 الأحدث</option><option value="cheap">💰 الأرخص</option><option value="expensive">💎 الأغلى</option><option value="name">🔤 الاسم</option><option value="stock">📦 الأكثر</option>
      </select>
    </div>
    <div class="flex gap-2 overflow-x-auto pb-1 no-scrollbar">
      <button @click="filter = 'all'" :class="filter === 'all' ? 'active' : ''" class="filter-chip whitespace-nowrap px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold">الكل (<span x-text="products.length"></span>)</button>
      <button @click="filter = 'available'" :class="filter === 'available' ? 'active' : ''" class="filter-chip whitespace-nowrap px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold">✅ متوفر</button>
      <button @click="filter = 'low'" :class="filter === 'low' ? 'active' : ''" class="filter-chip whitespace-nowrap px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold">⚠️ قليل</button>
      <button @click="filter = 'out'" :class="filter === 'out' ? 'active' : ''" class="filter-chip whitespace-nowrap px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold">❌ نفد</button>
    </div>
  </div>
</section>

<!-- Results -->
<section class="max-w-7xl mx-auto px-3 sm:px-4 pt-3">
  <div class="text-xs sm:text-sm text-slate-500">عرض <b class="text-slate-700" x-text="filteredProducts.length"></b> من <b class="text-slate-700">{{ $products->count() }}</b> منتج</div>
</section>

<!-- Products Grid -->
<section class="max-w-7xl mx-auto px-3 sm:px-4 py-4">
  <div x-show="filteredProducts.length === 0" class="bg-white rounded-3xl p-12 text-center shadow-sm">
    <div class="text-6xl mb-3">🔍</div>
    <h3 class="text-lg font-black mb-2">لا توجد نتائج</h3>
    <button @click="resetFilters()" class="mt-3 px-5 py-2 bg-amber-600 text-white rounded-xl font-bold text-sm">عرض الكل</button>
  </div>

  <div x-show="filteredProducts.length > 0" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2.5 sm:gap-3 lg:gap-4">
    @foreach($products as $index => $p)
    @php
      $stockPercent = min(100, max(3, ($p->stock / 40) * 100));
      $stockColor = $p->stock > 20 ? 'bg-green-500' : ($p->stock > 5 ? 'bg-amber-500' : ($p->stock > 0 ? 'bg-red-500' : 'bg-slate-300'));
      $stockLabel = $p->stock > 20 ? 'متوفر' : ($p->stock > 5 ? 'كمية محدودة' : ($p->stock > 0 ? 'قريبًا ينفد' : 'نفد'));
      $stockTextColor = $p->stock > 20 ? 'text-green-600' : ($p->stock > 5 ? 'text-amber-600' : ($p->stock > 0 ? 'text-red-600' : 'text-slate-400'));
      $inWish = in_array($p->id, $wishlist);
    @endphp

    <div x-show="matchesFilter({{ $p->id }})" x-transition class="card-product card-animate shadow-sm border border-slate-100" style="animation-delay: {{ $index * 0.04 }}s">
      <div class="block relative aspect-square bg-gradient-to-br from-amber-100 to-orange-50 overflow-hidden">
        <a href="/product/{{ $p->id }}" class="block absolute inset-0">
          @if($p->image)
            <img src="{{ Storage::url($p->image) }}" class="card-img absolute inset-0 w-full h-full object-cover" alt="{{ $p->name }}">
          @else
            <div class="card-img absolute inset-0 flex items-center justify-center text-6xl sm:text-7xl">📦</div>
          @endif
        </a>

        @if($p->stock > 0 && $p->stock <= 5)
        <span class="badge bg-red-500 text-white badge-pulse" style="top:8px;right:8px;">🔥 آخر {{ $p->stock }}</span>
        @elseif($p->compare_price && $p->compare_price > $p->price)
        <span class="badge bg-green-500 text-white" style="top:8px;right:8px;">💚 خصم {{ round((1 - $p->price / $p->compare_price) * 100) }}%</span>
        @elseif($p->stock > 0)
        <span class="badge bg-white/95 text-green-700" style="top:8px;right:8px;">✅ جديد</span>
        @endif

        <!-- Wishlist -->
        <form method="POST" action="/wishlist/toggle/{{ $p->id }}" class="absolute top-2 left-2">
          @csrf
          <button class="w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition">
            <i data-lucide="heart" class="w-4 h-4 {{ $inWish ? 'fill-red-500 text-red-500' : 'text-slate-600' }}"></i>
          </button>
        </form>
      </div>

      <div class="p-2.5 sm:p-3 flex-1 flex flex-col">
        <a href="/product/{{ $p->id }}"><h3 class="font-black text-xs sm:text-sm mb-1.5 line-clamp-2 hover:text-amber-600 transition min-h-[2.2rem] leading-tight">{{ $p->name }}</h3></a>

        <div class="flex items-baseline gap-1.5 mb-2">
          <div class="text-amber-600 font-black text-sm sm:text-base">{{ number_format($p->price) }}</div>
          <div class="text-xs text-slate-400">ريال</div>
          @if($p->compare_price && $p->compare_price > $p->price)
          <div class="text-xs text-slate-400 line-through mr-auto">{{ number_format($p->compare_price) }}</div>
          @endif
        </div>

        <div class="mb-2.5">
          <div class="flex justify-between items-center text-xs mb-1">
            <span class="font-bold {{ $stockTextColor }}">{{ $stockLabel }}</span>
            @if($p->stock > 0)<span class="text-slate-400">{{ $p->stock }} متبقي</span>@endif
          </div>
          <div class="stock-bar"><div class="stock-fill {{ $stockColor }}" style="width: {{ $stockPercent }}%"></div></div>
        </div>

        <div class="mt-auto">
          @if($p->stock > 0)
          <form method="POST" action="/cart/add/{{ $p->id }}">@csrf
            <button type="submit" class="w-full py-2 bg-gradient-to-l from-amber-500 to-orange-500 text-white rounded-xl font-bold text-xs sm:text-sm flex items-center justify-center gap-1 hover:shadow-lg transition">
              <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i> أضف
            </button>
          </form>
          @else
          <button disabled class="w-full py-2 bg-slate-100 text-slate-400 rounded-xl font-bold text-xs sm:text-sm cursor-not-allowed">نفد</button>
          @endif
        </div>
      </div>
    </div>
    @endforeach
  </div>
</section>

<!-- Track Order -->
<section class="max-w-7xl mx-auto px-3 sm:px-4 py-6 sm:py-8">
  <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden">
    <div class="absolute -top-20 -right-20 w-64 h-64 bg-amber-500/20 rounded-full blur-3xl"></div>
    <div class="relative grid md:grid-cols-2 gap-6 items-center">
      <div>
        <div class="inline-flex items-center gap-2 bg-amber-500/20 backdrop-blur px-3 py-1.5 rounded-full text-xs font-bold mb-3"><i data-lucide="package-search" class="w-4 h-4"></i> تتبع طلبك</div>
        <h2 class="text-2xl sm:text-3xl font-black mb-2">أين طلبي؟</h2>
        <p class="text-slate-300 text-sm mb-4">أدخل رقم الطلب لتتبع حالته.</p>
        <form action="/track-order" method="POST" class="flex gap-2">@csrf
          <input type="text" name="order_number" required placeholder="ORD-20250101-XXXXX" class="flex-1 px-4 py-3 bg-white/10 backdrop-blur border border-white/20 rounded-xl text-white placeholder-slate-400 focus:ring-2 focus:ring-amber-500 outline-none text-sm font-mono">
          <button type="submit" class="px-5 py-3 bg-gradient-to-l from-amber-500 to-orange-500 rounded-xl font-black text-sm flex items-center gap-2">
            <i data-lucide="search" class="w-4 h-4"></i><span class="hidden sm:inline">تتبع</span>
          </button>
        </form>
      </div>
      <div class="bg-white/5 backdrop-blur rounded-2xl p-4 border border-white/10">
        <div class="text-xs text-slate-400 mb-3">مراحل الطلب:</div>
        <div class="space-y-3">
          <div class="flex items-center gap-3"><div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center"><i data-lucide="check" class="w-4 h-4 text-white"></i></div><div><div class="font-bold text-sm">تم استلام الطلب</div></div></div>
          <div class="flex items-center gap-3"><div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center"><i data-lucide="check" class="w-4 h-4 text-white"></i></div><div><div class="font-bold text-sm">قيد التحضير</div></div></div>
          <div class="flex items-center gap-3"><div class="w-8 h-8 rounded-full bg-amber-500 flex items-center justify-center animate-pulse"><i data-lucide="truck" class="w-4 h-4 text-white"></i></div><div><div class="font-bold text-sm text-amber-400">في الطريق إليك</div></div></div>
          <div class="flex items-center gap-3 opacity-40"><div class="w-8 h-8 rounded-full bg-slate-600 flex items-center justify-center"><i data-lucide="home" class="w-4 h-4 text-white"></i></div><div><div class="font-bold text-sm">تم التسليم</div></div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Trust Bar -->
<section class="max-w-7xl mx-auto px-3 sm:px-4 pb-6 sm:pb-8">
  <div class="bg-slate-900 rounded-3xl p-5 sm:p-8 text-white">
    <div class="grid grid-cols-3 gap-3 text-center">
      <div><div class="text-2xl sm:text-3xl font-black text-amber-500 mb-1">100%</div><div class="text-xs text-slate-400">منتجات أصلية</div></div>
      <div><div class="text-2xl sm:text-3xl font-black text-amber-500 mb-1">24/7</div><div class="text-xs text-slate-400">دعم فني</div></div>
      <div><div class="text-2xl sm:text-3xl font-black text-amber-500 mb-1">+500</div><div class="text-xs text-slate-400">عميل سعيد</div></div>
    </div>
  </div>
</section>

<footer class="text-center py-6 sm:py-8 text-xs sm:text-sm text-slate-400 border-t border-slate-100 bg-white">
  <div class="text-3xl mb-2">🍯</div>
  <div class="font-black text-slate-700 mb-1">{{ $shop->name }}</div>
  <div>© {{ date('Y') }} — جميع الحقوق محفوظة</div>
</footer>

<a href="/cart" class="fixed bottom-6 left-6 w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-500 rounded-full flex items-center justify-center shadow-2xl z-40 sm:hidden">
  <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
</a>

<button id="scrollTop" class="scroll-top" onclick="window.scrollTo({top:0,behavior:'smooth'})"><i data-lucide="arrow-up" class="w-5 h-5"></i></button>

<script>
const PRODUCTS = @json($productsJson);
function storeApp() {
  return {
    search: '', sortBy: 'latest', filter: 'all', zoom: 180, activeSlide: 0,
    bannerIndex: 0, showNotifications: false, autoScroll: true,
    isDark: document.documentElement.classList.contains('dark'),

    get filteredProducts() {
      let list = [...PRODUCTS];
      if (this.search) { const s = this.search.toLowerCase(); list = list.filter(p => p.name.toLowerCase().includes(s) || (p.desc||'').toLowerCase().includes(s) || String(p.id).includes(s)); }
      if (this.filter === 'available') list = list.filter(p => p.stock > 20);
      else if (this.filter === 'low') list = list.filter(p => p.stock > 0 && p.stock <= 20);
      else if (this.filter === 'out') list = list.filter(p => p.stock <= 0);
      if (this.sortBy === 'cheap') list.sort((a,b) => a.price - b.price);
      else if (this.sortBy === 'expensive') list.sort((a,b) => b.price - a.price);
      else if (this.sortBy === 'name') list.sort((a,b) => a.name.localeCompare(b.name, 'ar'));
      else if (this.sortBy === 'stock') list.sort((a,b) => b.stock - a.stock);
      return list;
    },
    matchesFilter(id) { return this.filteredProducts.some(p => p.id === id); },
    resetFilters() { this.search = ''; this.filter = 'all'; this.sortBy = 'latest'; },
    toggleTheme() { this.isDark = !this.isDark; document.documentElement.classList.toggle('dark', this.isDark); localStorage.setItem('theme', this.isDark ? 'dark' : 'light'); },
    increaseZoom() { if (this.zoom < 300) this.zoom += 20; },
    decreaseZoom() { if (this.zoom > 140) this.zoom -= 20; },
    scrollCarousel(dir) { const t = document.getElementById('carousel-track'); if (t) t.scrollBy({ left: dir === 'left' ? -(this.zoom + 24) * 2 : (this.zoom + 24) * 2, behavior: 'smooth' }); },
    goToSlide(i) { const t = document.getElementById('carousel-track'); if (t) { t.scrollTo({ left: (this.zoom + 24) * i, behavior: 'smooth' }); this.activeSlide = i; } },
    initBanner() { setInterval(() => { this.bannerIndex = (this.bannerIndex + 1) % 3; document.querySelectorAll('.banner-slide').forEach((el, i) => el.classList.toggle('active', i === this.bannerIndex)); }, 5000); },
    initAutoScroll() {
      setInterval(() => {
        if (!this.autoScroll) return;
        const t = document.getElementById('carousel-track');
        if (!t) return;
        const maxScroll = t.scrollWidth - t.clientWidth;
        if (t.scrollLeft >= maxScroll - 10) t.scrollTo({ left: 0, behavior: 'smooth' });
        else t.scrollBy({ left: this.zoom + 24, behavior: 'smooth' });
      }, 3500);
    },
    init() {
      const t = document.getElementById('carousel-track');
      if (t) t.addEventListener('scroll', () => { this.activeSlide = Math.round(t.scrollLeft / (this.zoom + 24)); });
      this.initBanner(); this.initAutoScroll();
      const btn = document.getElementById('scrollTop');
      window.addEventListener('scroll', () => btn?.classList.toggle('show', window.scrollY > 400));
    }
  };
}
window.showToast = function(message, type = 'success') {
  const colors = { success: 'bg-white border-r-4 border-green-500 text-green-800', error: 'bg-white border-r-4 border-red-500 text-red-800' };
  const toast = document.createElement('div');
  toast.className = `toast-enter ${colors[type]} px-5 py-3 rounded-xl shadow-2xl font-bold text-sm flex items-center gap-2 pointer-events-auto`;
  toast.innerHTML = `<span>${type === 'success' ? '✅' : '❌'}</span><span>${message}</span>`;
  document.getElementById('toast-container').appendChild(toast);
  setTimeout(() => { toast.classList.remove('toast-enter'); toast.classList.add('toast-leave'); setTimeout(() => toast.remove(), 400); }, 3500);
};
lucide.createIcons();
</script>
</body>
</html>

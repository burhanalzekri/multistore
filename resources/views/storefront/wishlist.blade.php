<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>المفضلة — منصتي</title>
<script>(function(){const t=localStorage.getItem('theme')||'light';if(t==='dark')document.documentElement.classList.add('dark');})();</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { darkMode: 'class' };</script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>body{font-family:'Cairo',sans-serif} html.dark body{background:#0f172a;color:#e2e8f0} html.dark .bg-white{background:#1e293b!important} html.dark .text-slate-800,html.dark .text-slate-700{color:#f1f5f9!important} html.dark .text-slate-600{color:#cbd5e1!important} html.dark .text-slate-500,html.dark .text-slate-400{color:#94a3b8!important} html.dark header{background:rgba(15,23,42,0.9)!important;border-color:#334155!important}</style>
</head>
<body class="bg-slate-50">
<header class="bg-white/90 backdrop-blur-lg shadow-sm sticky top-0 z-50 border-b border-slate-100">
  <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
    <a href="/shop" class="flex items-center gap-2 text-slate-600 font-bold text-sm"><i data-lucide="arrow-right" class="w-5 h-5"></i> المتجر</a>
    <h1 class="font-black">❤️ المفضلة</h1>
    <a href="/cart" class="px-4 py-2 bg-amber-600 text-white rounded-xl font-bold text-sm">🛒 السلة</a>
  </div>
</header>

<div class="max-w-7xl mx-auto p-4 py-8">
@if($products->isEmpty())
  <div class="bg-white rounded-3xl p-16 text-center shadow-sm">
    <div class="text-7xl mb-4">❤️</div>
    <h3 class="text-xl font-black mb-2">المفضلة فارغة</h3>
    <p class="text-slate-500 text-sm mb-4">أضف منتجاتك المفضلة للرجوع إليها لاحقًا</p>
    <a href="/shop" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-600 text-white rounded-xl font-bold">
      <i data-lucide="shopping-bag" class="w-5 h-5"></i> تصفح المنتجات
    </a>
  </div>
@else
  <div class="mb-4 text-sm text-slate-500"><b class="text-slate-700">{{ $products->count() }}</b> منتج في المفضلة</div>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
    @foreach($products as $p)
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm group">
      <a href="/product/{{ $p->id }}" class="block aspect-square bg-gradient-to-br from-amber-100 to-orange-50 overflow-hidden">
        @if($p->image)
          <img src="{{ Storage::url($p->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition">
        @else
          <div class="w-full h-full flex items-center justify-center text-6xl">📦</div>
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
            <button class="px-3 py-2 bg-red-50 text-red-600 rounded-lg text-xs font-bold">❌</button>
          </form>
        </div>
      </div>
    </div>
    @endforeach
  </div>
@endif
</div>
<script>lucide.createIcons();</script>
</body>
</html>

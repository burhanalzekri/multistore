<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>السلة — منصتي</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/app.css">
<style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen">

<header class="bg-white/80 backdrop-blur-lg shadow-sm sticky top-0 z-50">
  <div class="max-w-4xl mx-auto px-4 py-3 flex justify-between items-center">
    <a href="/shop" class="flex items-center gap-2 text-slate-600 hover:text-amber-600 transition font-bold text-sm">
      <i data-lucide="arrow-right" class="w-5 h-5"></i>
      <span>المتجر</span>
    </a>
    <h1 class="font-black">🛒 السلة</h1>
  </div>
</header>

<div class="max-w-4xl mx-auto p-4 py-6">

@if(empty($items))
  <div class="bg-white rounded-3xl p-16 text-center shadow-sm animate-slideUp">
    <div class="text-7xl mb-4 animate-float">🛒</div>
    <h2 class="text-2xl font-black mb-2">السلة فارغة</h2>
    <p class="text-slate-500 mb-6 text-sm">لم تقم بإضافة أي منتج بعد</p>
    <a href="/shop" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-600 text-white rounded-xl font-bold btn-primary">
      <i data-lucide="shopping-bag" class="w-5 h-5"></i>
      تصفح المنتجات
    </a>
  </div>
@else
  <div class="space-y-3 mb-6">
    @foreach($items as $item)
    <div class="bg-white rounded-2xl p-4 flex items-center gap-3 shadow-sm card-hover animate-slideUp">
      <div class="w-20 h-20 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center text-4xl flex-shrink-0">
        📦
      </div>
      <div class="flex-1 min-w-0">
        <h3 class="font-black truncate">{{ $item['product']->name }}</h3>
        <div class="text-amber-600 font-bold text-sm mt-1">
          {{ number_format($item['product']->price) }} ريال
        </div>
        <div class="flex items-center gap-2 mt-2">
          <form method="POST" action="/cart/update/{{ $item['product']->id }}" class="flex items-center bg-slate-100 rounded-xl overflow-hidden">
            @csrf
            <button type="button" onclick="this.nextElementSibling.stepDown(); this.form.submit()" class="px-3 py-1 hover:bg-slate-200 font-black text-slate-600">−</button>
            <input type="number" name="qty" value="{{ $item['qty'] }}" min="0" max="{{ $item['product']->stock }}"
              onchange="this.form.submit()"
              class="w-12 py-1 text-center bg-transparent font-bold outline-none text-sm">
            <button type="button" onclick="this.previousElementSibling.stepUp(); this.form.submit()" class="px-3 py-1 hover:bg-slate-200 font-black text-slate-600">+</button>
          </form>
        </div>
      </div>
      <div class="text-left flex-shrink-0">
        <div class="font-black text-lg">{{ number_format($item['subtotal']) }}</div>
        <div class="text-xs text-slate-400">ريال</div>
        <form method="POST" action="/cart/remove/{{ $item['product']->id }}" class="mt-2">
          @csrf
          <button class="text-red-500 hover:text-red-700 transition">
            <i data-lucide="trash-2" class="w-5 h-5"></i>
          </button>
        </form>
      </div>
    </div>
    @endforeach
  </div>

  <!-- Summary -->
  <div class="bg-white rounded-3xl p-6 shadow-lg animate-slideUp">
    <h2 class="font-black text-lg mb-4 flex items-center gap-2">
      <i data-lucide="receipt" class="w-5 h-5 text-amber-600"></i>
      ملخص الطلب
    </h2>

    <div class="space-y-2 text-sm mb-4">
      <div class="flex justify-between"><span class="text-slate-500">عدد المنتجات</span><b>{{ count($items) }}</b></div>
      <div class="flex justify-between"><span class="text-slate-500">الشحن</span><b class="text-green-600">مجاني</b></div>
    </div>

    <div class="border-t-2 border-dashed pt-4 mb-6">
      <div class="flex justify-between items-center">
        <span class="text-lg font-bold">الإجمالي:</span>
        <div class="text-3xl font-black text-amber-600">{{ number_format($total) }} <span class="text-sm">ريال</span></div>
      </div>
    </div>

    <a href="/checkout" class="w-full flex items-center justify-center gap-2 py-4 bg-gradient-to-l from-amber-500 to-orange-500 text-white font-black rounded-2xl hover:shadow-2xl transition btn-primary text-lg">
      <i data-lucide="check-circle" class="w-5 h-5"></i>
      إتمام الطلب
    </a>
  </div>
@endif

</div>

<script>lucide.createIcons();</script>
</body>
</html>

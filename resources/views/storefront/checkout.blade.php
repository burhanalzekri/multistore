<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>إتمام الطلب</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/app.css">
<style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-slate-50">

<header class="bg-white/80 backdrop-blur-lg shadow-sm sticky top-0 z-50">
  <div class="max-w-4xl mx-auto px-4 py-3 flex justify-between items-center">
    <a href="/cart" class="flex items-center gap-2 text-slate-600 hover:text-amber-600 transition font-bold text-sm">
      <i data-lucide="arrow-right" class="w-5 h-5"></i>
      <span>السلة</span>
    </a>
    <h1 class="font-black">💳 إتمام الطلب</h1>
  </div>
</header>

<!-- Progress Steps -->
<div class="max-w-4xl mx-auto px-4 pt-6">
  <div class="flex items-center justify-center gap-2 text-xs">
    <div class="flex items-center gap-2">
      <span class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-black">✓</span>
      <span class="hidden sm:inline font-bold text-green-600">السلة</span>
    </div>
    <div class="w-8 h-0.5 bg-amber-500"></div>
    <div class="flex items-center gap-2">
      <span class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center font-black animate-pulse">2</span>
      <span class="hidden sm:inline font-bold text-amber-600">البيانات</span>
    </div>
    <div class="w-8 h-0.5 bg-slate-200"></div>
    <div class="flex items-center gap-2">
      <span class="w-8 h-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-black">3</span>
      <span class="hidden sm:inline font-bold text-slate-400">تأكيد</span>
    </div>
  </div>
</div>

<form method="POST" action="/checkout" class="max-w-4xl mx-auto p-4 py-6 space-y-4">
  @csrf

  @if($errors->any())
  <div class="bg-red-100 text-red-700 p-4 rounded-2xl text-sm flex items-start gap-2 animate-slideUp">
    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
    <div>
      @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
    </div>
  </div>
  @endif

  <!-- Order Summary -->
  <div class="bg-white rounded-2xl p-5 shadow-sm animate-slideUp">
    <h2 class="font-black mb-4 flex items-center gap-2">
      <i data-lucide="shopping-bag" class="w-5 h-5 text-amber-600"></i>
      ملخص الطلب
    </h2>
    <div class="space-y-2 text-sm">
      @foreach($items as $item)
      <div class="flex justify-between py-1">
        <span>{{ $item['product']->name }} <span class="text-slate-400">× {{ $item['qty'] }}</span></span>
        <span class="font-bold">{{ number_format($item['subtotal']) }}</span>
      </div>
      @endforeach
      <div class="border-t pt-3 mt-2 flex justify-between text-lg">
        <span class="font-bold">الإجمالي:</span>
        <span class="font-black text-amber-600">{{ number_format($total) }} ريال</span>
      </div>
    </div>
  </div>

  <!-- Customer Info -->
  <div class="bg-white rounded-2xl p-5 shadow-sm animate-slideUp delay-100">
    <h2 class="font-black mb-4 flex items-center gap-2">
      <i data-lucide="user" class="w-5 h-5 text-blue-600"></i>
      بياناتك
    </h2>

    <div class="space-y-4">
      <div>
        <label class="text-sm font-bold block mb-2">الاسم الكامل *</label>
        <div class="relative">
          <i data-lucide="user" class="w-5 h-5 absolute top-1/2 right-3 -translate-y-1/2 text-slate-400"></i>
          <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
            class="w-full pr-11 pl-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
        </div>
      </div>

      <div>
        <label class="text-sm font-bold block mb-2">رقم الجوال *</label>
        <div class="relative">
          <i data-lucide="phone" class="w-5 h-5 absolute top-1/2 right-3 -translate-y-1/2 text-slate-400"></i>
          <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required placeholder="7XXXXXXXX"
            class="w-full pr-11 pl-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
        </div>
      </div>

      <div>
        <label class="text-sm font-bold block mb-2">العنوان *</label>
        <div class="relative">
          <i data-lucide="map-pin" class="w-5 h-5 absolute top-3 right-3 text-slate-400"></i>
          <textarea name="customer_address" required rows="3" placeholder="المدينة، الحي، الشارع..."
            class="w-full pr-11 pl-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">{{ old('customer_address') }}</textarea>
        </div>
      </div>

      <div>
        <label class="text-sm font-bold block mb-2">ملاحظات (اختياري)</label>
        <textarea name="notes" rows="2" placeholder="أي تفاصيل إضافية..."
          class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">{{ old('notes') }}</textarea>
      </div>
    </div>
  </div>

  <!-- Payment -->
  <div class="bg-white rounded-2xl p-5 shadow-sm animate-slideUp delay-200">
    <h2 class="font-black mb-4 flex items-center gap-2">
      <i data-lucide="credit-card" class="w-5 h-5 text-green-600"></i>
      طريقة الدفع
    </h2>

    <div class="space-y-3">
      <label class="flex items-center gap-3 p-4 border-2 border-amber-500 bg-amber-50 rounded-2xl cursor-pointer transition">
        <input type="radio" name="payment_method" value="wallet" checked class="w-5 h-5 accent-amber-600">
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
          <i data-lucide="smartphone" class="w-5 h-5 text-amber-600"></i>
        </div>
        <div class="flex-1">
          <div class="font-black">💰 تحويل إلى محفظة</div>
          <div class="text-xs text-slate-500">أسرع خيار — يتم التأكيد تلقائيًا</div>
        </div>
      </label>

      <label class="flex items-center gap-3 p-4 border-2 border-slate-200 rounded-2xl cursor-pointer transition hover:border-amber-500">
        <input type="radio" name="payment_method" value="cod" class="w-5 h-5 accent-amber-600">
        <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
          <i data-lucide="banknote" class="w-5 h-5 text-green-600"></i>
        </div>
        <div class="flex-1">
          <div class="font-black">💵 الدفع عند الاستلام</div>
          <div class="text-xs text-slate-500">تدفع نقدًا عند وصول الطلب</div>
        </div>
      </label>
    </div>
  </div>

  <button type="submit" class="w-full py-4 bg-gradient-to-l from-amber-500 to-orange-500 text-white font-black rounded-2xl hover:shadow-2xl transition btn-primary flex items-center justify-center gap-2 text-lg animate-slideUp delay-300">
    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
    تأكيد الطلب
  </button>

</form>

<script>lucide.createIcons();</script>
</body>
</html>

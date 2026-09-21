<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $product->name }} — {{ $shop->name }}</title>
<script>
  (function() { const t = localStorage.getItem('theme') || 'light'; if (t === 'dark') document.documentElement.classList.add('dark'); })();
</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { darkMode: 'class' };</script>
<script src="https://unpkg.com/lucide@latest"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Cairo', sans-serif; }
  [x-cloak] { display: none !important; }
  html.dark body { background: #0f172a; color: #e2e8f0; }
  html.dark .bg-white { background: #1e293b !important; }
  html.dark .bg-slate-50 { background: #0f172a !important; }
  html.dark .bg-slate-100 { background: #334155 !important; }
  html.dark .text-slate-800, html.dark .text-slate-700 { color: #f1f5f9 !important; }
  html.dark .text-slate-600 { color: #cbd5e1 !important; }
  html.dark .text-slate-500, html.dark .text-slate-400 { color: #94a3b8 !important; }
  html.dark .border-slate-100, html.dark .border-slate-200 { border-color: #334155 !important; }
  html.dark header { background: rgba(15,23,42,0.9) !important; border-color: #334155 !important; }
  html.dark input, html.dark textarea { background: #1e293b !important; border-color: #334155 !important; color: #f1f5f9 !important; }

  .theme-toggle { position: relative; width: 52px; height: 30px; border-radius: 999px; background: linear-gradient(135deg, #60a5fa, #3b82f6); cursor: pointer; border: none; padding: 0; flex-shrink: 0; }
  html.dark .theme-toggle { background: linear-gradient(135deg, #1e293b, #334155); }
  .theme-toggle-thumb { position: absolute; top: 3px; right: 3px; width: 24px; height: 24px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; font-size: 12px; transition: transform 0.4s cubic-bezier(0.68,-0.55,0.265,1.55); }
  html.dark .theme-toggle-thumb { transform: translateX(-22px); background: #fbbf24; }

  .star-btn { cursor: pointer; transition: transform 0.2s; }
  .star-btn:hover { transform: scale(1.2); }
  .star-filled { color: #f59e0b; fill: #f59e0b; }
  .star-empty { color: #d1d5db; }
</style>
</head>
<body class="bg-slate-50" x-data="{ theme: localStorage.getItem('theme') || 'light', isDark: localStorage.getItem('theme') === 'dark', rating: 5, quantity: 1, toggleTheme() { this.isDark = !this.isDark; document.documentElement.classList.toggle('dark', this.isDark); localStorage.setItem('theme', this.isDark ? 'dark' : 'light'); } }" x-cloak>

<header class="bg-white/90 backdrop-blur-lg shadow-sm sticky top-0 z-50 border-b border-slate-100">
  <div class="max-w-7xl mx-auto px-3 sm:px-4 py-3 flex justify-between items-center">
    <a href="/shop" class="flex items-center gap-2 text-slate-600 font-bold text-sm">
      <i data-lucide="arrow-right" class="w-5 h-5"></i>
      <span>المتجر</span>
    </a>
    <div class="flex items-center gap-2">
      <button class="theme-toggle" @click="toggleTheme()">
        <span class="theme-toggle-thumb" x-text="isDark ? '🌙' : '☀️'"></span>
      </button>
      <a href="/cart" class="relative flex items-center gap-2 px-4 py-2 bg-gradient-to-l from-amber-500 to-orange-500 text-white rounded-xl font-bold text-sm">
        <i data-lucide="shopping-cart" class="w-4 h-4"></i>
        <span class="hidden sm:inline">السلة</span>
      </a>
    </div>
  </div>
</header>

@if(session('success'))
<div class="max-w-7xl mx-auto px-4 mt-4">
  <div class="bg-green-100 text-green-700 p-3 rounded-xl font-bold text-sm">✅ {{ session('success') }}</div>
</div>
@endif

<!-- Product -->
<div class="max-w-7xl mx-auto px-4 py-6">
  <div class="grid lg:grid-cols-2 gap-6 mb-8">

    <!-- Images -->
    <div>
      <div class="bg-gradient-to-br from-amber-100 to-orange-100 rounded-3xl aspect-square overflow-hidden shadow-xl relative">
        @if($product->image)
          <img id="mainImage" src="{{ Storage::url($product->image) }}" class="w-full h-full object-cover">
        @else
          <div class="w-full h-full flex items-center justify-center text-9xl">📦</div>
        @endif

        <!-- Wishlist -->
        <form method="POST" action="/wishlist/toggle/{{ $product->id }}" class="absolute top-4 right-4">
          @csrf
          <button class="w-12 h-12 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition">
            <i data-lucide="heart" class="w-6 h-6 {{ $inWishlist ? 'fill-red-500 text-red-500' : 'text-slate-600' }}"></i>
          </button>
        </form>

        <!-- Discount Badge -->
        @if($product->compare_price && $product->compare_price > $product->price)
        <div class="absolute top-4 left-4 bg-green-500 text-white font-black px-4 py-2 rounded-full shadow-lg">
          💚 خصم {{ round((1 - $product->price / $product->compare_price) * 100) }}%
        </div>
        @endif
      </div>

      <!-- Gallery Thumbnails -->
      @if($product->images && count($product->images) > 0)
      <div class="grid grid-cols-5 gap-2 mt-4">
        @if($product->image)
        <div class="aspect-square rounded-xl overflow-hidden cursor-pointer border-2 border-amber-500" onclick="document.getElementById('mainImage').src=this.querySelector('img').src">
          <img src="{{ Storage::url($product->image) }}" class="w-full h-full object-cover">
        </div>
        @endif
        @foreach($product->images as $img)
        <div class="aspect-square rounded-xl overflow-hidden cursor-pointer border-2 border-transparent hover:border-amber-500 transition" onclick="document.getElementById('mainImage').src=this.querySelector('img').src">
          <img src="{{ Storage::url($img) }}" class="w-full h-full object-cover">
        </div>
        @endforeach
      </div>
      @endif
    </div>

    <!-- Details -->
    <div>
      <div class="flex items-center gap-2 mb-3">
        <span class="text-xs bg-amber-100 text-amber-800 font-bold px-3 py-1 rounded-full">منتج أصلي</span>
        @if($product->stock > 20)
        <span class="text-xs bg-green-100 text-green-700 font-bold px-3 py-1 rounded-full flex items-center gap-1"><span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> متوفر</span>
        @elseif($product->stock > 0)
        <span class="text-xs bg-amber-100 text-amber-700 font-bold px-3 py-1 rounded-full flex items-center gap-1"><span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> كمية محدودة</span>
        @endif
      </div>

      <h1 class="text-3xl lg:text-4xl font-black mb-4">{{ $product->name }}</h1>

      <!-- Rating -->
      <div class="flex items-center gap-3 mb-5">
        <div class="flex">
          @for($i = 1; $i <= 5; $i++)
            <i data-lucide="star" class="w-5 h-5 {{ $i <= round($product->average_rating) ? 'fill-amber-400 text-amber-400' : 'text-slate-300' }}"></i>
          @endfor
        </div>
        <span class="text-sm font-bold">{{ $product->average_rating }}</span>
        <span class="text-sm text-slate-400">({{ $product->reviews_count }} تقييم)</span>
      </div>

      <!-- Price -->
      <div class="flex items-baseline gap-3 mb-6">
        <div class="text-4xl font-black text-amber-600">{{ number_format($product->price) }} <span class="text-lg">ريال</span></div>
        @if($product->compare_price && $product->compare_price > $product->price)
        <div class="text-xl text-slate-400 line-through">{{ number_format($product->compare_price) }}</div>
        @endif
      </div>

      @if($product->description)
      <p class="text-slate-600 mb-6 leading-relaxed">{{ $product->description }}</p>
      @endif

      <!-- Stock Bar -->
      <div class="mb-6 p-4 bg-slate-50 rounded-2xl">
        <div class="flex justify-between items-center mb-2 text-sm">
          <span class="text-slate-500">المخزون</span>
          <span class="font-black {{ $product->stock > 20 ? 'text-green-600' : ($product->stock > 0 ? 'text-amber-600' : 'text-red-600') }}">
            @if($product->stock > 20) متوفر — {{ $product->stock }} قطعة
            @elseif($product->stock > 0) كمية محدودة — {{ $product->stock }} فقط
            @else نفد @endif
          </span>
        </div>
        @php $pct = min(100, max(3, ($product->stock / 40) * 100)); @endphp
        <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
          <div class="h-full rounded-full {{ $product->stock > 20 ? 'bg-green-500' : ($product->stock > 0 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $pct }}%"></div>
        </div>
      </div>

      <!-- Actions -->
      @if($product->stock > 0)
      <form method="POST" action="/cart/add/{{ $product->id }}" class="mb-4">
        @csrf
        <div class="flex gap-2 mb-3">
          <div class="flex items-center bg-slate-100 rounded-xl overflow-hidden">
            <button type="button" onclick="document.getElementById('qty').stepDown()" class="px-4 py-4 hover:bg-slate-200 font-black">−</button>
            <input type="number" id="qty" name="qty" value="1" min="1" max="{{ $product->stock }}" class="w-16 py-4 text-center bg-transparent font-black outline-none">
            <button type="button" onclick="document.getElementById('qty').stepUp()" class="px-4 py-4 hover:bg-slate-200 font-black">+</button>
          </div>
          <button type="submit" class="flex-1 py-4 bg-gradient-to-l from-amber-500 to-orange-500 text-white rounded-xl font-black text-lg hover:shadow-2xl transition flex items-center justify-center gap-2">
            <i data-lucide="shopping-cart" class="w-6 h-6"></i>
            أضف إلى السلة
          </button>
        </div>
      </form>
      @else
      <button disabled class="w-full py-4 bg-slate-100 text-slate-400 font-black rounded-xl cursor-not-allowed mb-4">غير متوفر حاليًا</button>
      @endif

      <!-- WhatsApp -->
      @if($shop->whatsapp)
      <a href="https://wa.me/{{ $shop->whatsapp }}?text=أريد+الاستفسار+عن+{{ urlencode($product->name) }}" target="_blank"
         class="w-full flex items-center justify-center gap-2 py-3 bg-green-500 text-white rounded-xl font-bold hover:bg-green-600 transition">
        <i data-lucide="message-circle" class="w-5 h-5"></i>
        استفسر عبر واتساب
      </a>
      @endif
    </div>
  </div>

  <!-- Reviews -->
  <div class="bg-white rounded-3xl p-6 shadow-sm mb-6">
    <h2 class="text-2xl font-black mb-6 flex items-center gap-2">
      <i data-lucide="star" class="w-6 h-6 text-amber-500"></i>
      التقييمات والمراجعات
    </h2>

    <!-- Add Review -->
    <div class="bg-slate-50 rounded-2xl p-5 mb-6">
      <h3 class="font-black mb-4">أضف تقييمك</h3>
      <form method="POST" action="/product/{{ $product->id }}/review">
        @csrf
        <div class="mb-4">
          <label class="text-sm font-bold block mb-2">تقييمك</label>
          <div class="flex gap-1">
            @for($i = 1; $i <= 5; $i++)
              <button type="button" onclick="document.getElementById('ratingInput').value={{ $i }}; document.querySelectorAll('.rating-star').forEach((el, idx) => el.classList.toggle('star-filled', idx < {{ $i }}));" class="star-btn">
                <i data-lucide="star" class="w-8 h-8 rating-star {{ $i <= 5 ? 'star-filled' : 'star-empty' }}"></i>
              </button>
            @endfor
          </div>
          <input type="hidden" name="rating" id="ratingInput" value="5">
        </div>
        <div class="grid sm:grid-cols-2 gap-3 mb-3">
          <input type="text" name="customer_name" required placeholder="اسمك" class="px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
          <input type="text" name="customer_phone" placeholder="رقمك (اختياري)" class="px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
        </div>
        <textarea name="comment" rows="3" placeholder="اكتب تعليقك..." class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none mb-3"></textarea>
        <button type="submit" class="px-6 py-3 bg-amber-600 text-white rounded-xl font-bold hover:shadow-lg transition">
          إرسال التقييم ⭐
        </button>
      </form>
    </div>

    <!-- Reviews List -->
    <div class="space-y-4">
      @forelse($reviews as $r)
      <div class="border-b border-slate-100 last:border-0 pb-4">
        <div class="flex items-start gap-3">
          <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center font-black text-white shrink-0">
            {{ mb_substr($r->customer_name, 0, 1) }}
          </div>
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
              <span class="font-bold text-sm">{{ $r->customer_name }}</span>
              <div class="flex">
                @for($i = 1; $i <= 5; $i++)
                  <i data-lucide="star" class="w-3 h-3 {{ $i <= $r->rating ? 'fill-amber-400 text-amber-400' : 'text-slate-300' }}"></i>
                @endfor
              </div>
              <span class="text-xs text-slate-400">{{ $r->created_at->diffForHumans() }}</span>
            </div>
            @if($r->comment)
            <p class="text-sm text-slate-600">{{ $r->comment }}</p>
            @endif
          </div>
        </div>
      </div>
      @empty
      <div class="text-center py-8 text-slate-400">
        <div class="text-4xl mb-2">⭐</div>
        <div>لا توجد تقييمات بعد — كن أول من يُقيّم</div>
      </div>
      @endforelse
    </div>
  </div>

  <!-- Related Products -->
  @if($related->count() > 0)
  <div>
    <h2 class="text-2xl font-black mb-4">🔥 منتجات مشابهة</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
      @foreach($related as $rp)
      <a href="/product/{{ $rp->id }}" class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition group">
        <div class="aspect-square bg-gradient-to-br from-amber-100 to-orange-50 flex items-center justify-center overflow-hidden">
          @if($rp->image)
            <img src="{{ Storage::url($rp->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition">
          @else
            <div class="text-6xl">📦</div>
          @endif
        </div>
        <div class="p-3">
          <h3 class="font-bold text-xs mb-1 line-clamp-2 min-h-[2rem]">{{ $rp->name }}</h3>
          <div class="text-amber-600 font-black text-sm">{{ number_format($rp->price) }} <span class="text-xs">ريال</span></div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
  @endif
</div>

<footer class="text-center py-8 text-xs text-slate-400 border-t border-slate-100 mt-8">
  <div class="text-3xl mb-2">🍯</div>
  <div class="font-black text-slate-700">{{ $shop->name }}</div>
  <div>© {{ date('Y') }}</div>
</footer>

<script>lucide.createIcons();</script>
</body>
</html>

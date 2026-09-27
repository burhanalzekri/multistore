{{-- 🛒 زر السلة العائم --}}
@php
    $__cart = session('cart', []);
    $__cartCount = 0;
    $__cartTotal = 0;
    $__cartItems = [];

    if (is_array($__cart)) {
        foreach ($__cart as $__key => $__entry) {
            if (!is_array($__entry)) {
                $__entry = ['product_id'=>(int)$__key,'variant_id'=>null,'qty'=>(int)$__entry];
            }
            $__pid = $__entry['product_id'] ?? null;
            if (!$__pid) continue;
            $__p = \App\Models\Product::withoutGlobalScope('tenant')->find($__pid);
            if (!$__p) continue;
            $__qty = max(1, (int)($__entry['qty'] ?? 1));
            $__v = !empty($__entry['variant_id']) ? \App\Models\ProductVariant::find($__entry['variant_id']) : null;
            $__price = ($__v && $__v->price) ? (float)$__v->price : (float)$__p->price;

            $__cartCount += $__qty;
            $__cartTotal += $__price * $__qty;

            $__img = $__p->image;
            $__isExt = $__img && (str_starts_with($__img, 'http') || str_starts_with($__img, 'https'));
            $__imgUrl = $__img ? ($__isExt ? $__img : \Storage::url($__img)) : null;

            $__cartItems[] = [
                'id' => $__p->id,
                'name' => $__p->name,
                'price' => $__price,
                'qty' => $__qty,
                'image' => $__imgUrl,
                'color' => $__entry['color'] ?? ($__v->color ?? null),
                'size' => $__entry['size'] ?? ($__v->size ?? null),
            ];
        }
    }
@endphp

@if($__cartCount > 0)
<div class="floating-cart" id="floatingCart">
  <a href="/cart" class="floating-cart-btn" aria-label="السلة">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="9" cy="21" r="1"></circle>
      <circle cx="20" cy="21" r="1"></circle>
      <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
    </svg>
    <span class="floating-cart-count" id="floatingCartCount">{{ $__cartCount }}</span>
  </a>
  <div class="floating-cart-total">{{ number_format($__cartTotal) }} ر.ي</div>
</div>

<style>
  .floating-cart {
    position: fixed; bottom: 90px; left: 16px;
    z-index: 90;
    display: flex; flex-direction: column; align-items: center; gap: 4px;
    filter: drop-shadow(0 10px 25px rgba(0,0,0,0.2));
  }
  .floating-cart-btn {
    width: 56px; height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    display: grid; place-items: center;
    position: relative;
    transition: .2s;
  }
  .floating-cart-btn:hover { transform: translateY(-3px); }
  .floating-cart-count {
    position: absolute; top: -4px; right: -4px;
    min-width: 22px; height: 22px;
    padding: 0 6px;
    border-radius: 99px;
    background: #dc2626;
    color: #fff;
    font-size: 11px; font-weight: 900;
    display: grid; place-items: center;
    border: 2px solid #fff;
  }
  .floating-cart-total {
    background: rgba(15,23,42,0.85);
    backdrop-filter: blur(8px);
    color: #fff;
    font-size: 11px; font-weight: 800;
    padding: 4px 10px;
    border-radius: 99px;
    white-space: nowrap;
  }
  @media (max-width: 640px) {
    .floating-cart { bottom: 80px; left: 12px; }
    .floating-cart-btn { width: 52px; height: 52px; }
  }
</style>
@endif

{{-- 🎨 عرض المنتج ثلاثي الأبعاد — مع معرض صور متعدد --}}

@php
  $p = $product ?? null;
  if (!$p) return;

  $hasDiscount = $p->compare_price && $p->compare_price > $p->price;
  $discountPercent = $hasDiscount ? round((1 - $p->price / $p->compare_price) * 100) : 0;

  // ✅ الصورة الواحدة فقط
@endphp

@if($p)
<div class="product-3d-wrapper" data-product-id="{{ $p->id }}">

  {{-- ═══ المشهد ثلاثي الأبعاد ═══ --}}
  <div class="scene-3d">
    <div class="card-3d" id="card3d-{{ $p->id }}">

      {{-- 🌈 الهالة الخلفية --}}
      <div class="halo"></div>

      {{-- 🖼️ صورة المنتج --}}
      <div class="product-image-3d">
        @if($p->image)
          <img src="{{ $p->image_url }}" alt="{{ $p->name }}" loading="eager">
        @else
          <div class="fallback-icon">📦</div>
        @endif
        <div class="shine"></div>
      </div>

      {{-- 📋 المعلومات --}}
      <div class="product-info-3d">
        <div class="product-name-3d">{{ $p->name }}</div>

        <div class="product-price-3d">
          <span class="price-now">{{ number_format($p->price) }}</span>
          <span class="currency">ريال</span>
          @if($hasDiscount)
            <span class="price-old">{{ number_format($p->compare_price) }}</span>
          @endif
        </div>

        <div class="badges-3d">
          @if($hasDiscount)
            <span class="badge-3d badge-discount">💚 خصم {{ $discountPercent }}%</span>
          @endif
          @if($p->stock > 0 && $p->stock <= 10)
            <span class="badge-3d badge-hot">🔥 آخر {{ $p->stock }}</span>
          @elseif($p->stock > 0)
            <span class="badge-3d badge-ok">✅ متوفر</span>
          @else
            <span class="badge-3d badge-out">❌ نفد</span>
          @endif
        </div>

        {{-- 🛒 الأزرار --}}
        @if($p->stock > 0)
        <div style="display:flex;gap:6px;margin-top:8px;">
          <button class="btn-buy-3d" onclick="event.stopPropagation(); buyNow({{ $p->id }})">
            <span class="btn-icon" style="font-size:18px;">🛒</span>
            <span>أضف للسلة</span>
          </button>
          <button onclick="event.stopPropagation(); goToProduct({{ $p->id }})" style="width:52px;height:52px;background:white;border:2px solid #e2e8f0;border-radius:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:20px;transition:all 0.2s;" onmouseover="this.style.background='#fef3c7';this.style.borderColor='#f59e0b'" onmouseout="this.style.background='white';this.style.borderColor='#e2e8f0'">
            👁️
          </button>
        </div>
        @endif
      </div>

    </div>
  </div>

</div>
@endif

<style>
  .product-3d-wrapper {
    perspective: 1400px;
    perspective-origin: 50% 50%;
    padding: 20px 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 480px;
    cursor: default;
  }

  .scene-3d {
    width: 100%;
    max-width: 340px;
    transform-style: preserve-3d;
  }

  .card-3d {
    position: relative;
    width: 100%;
    background: linear-gradient(165deg, #ffffff 0%, #fef9f0 100%);
    border-radius: 32px;
    padding: 24px 20px;
    box-shadow:
      0 30px 60px -20px rgba(0,0,0,0.25),
      0 18px 36px -18px rgba(245,158,11,0.35),
      0 0 0 1px rgba(245,158,11,0.08),
      inset 0 1px 0 rgba(255,255,255,0.9);
    transform-style: preserve-3d;
    transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    animation: cardFloat 6s ease-in-out infinite;
  }

  @keyframes cardFloat {
    0%, 100% { transform: rotateX(0deg) rotateY(0deg) translateY(0) translateZ(0); }
    25% { transform: rotateX(-2deg) rotateY(3deg) translateY(-5px) translateZ(10px); }
    50% { transform: rotateX(0deg) rotateY(0deg) translateY(-8px) translateZ(15px); }
    75% { transform: rotateX(2deg) rotateY(-3deg) translateY(-5px) translateZ(10px); }
  }

  .card-3d:hover {
    animation: none;
    transform: rotateX(-5deg) rotateY(8deg) translateZ(25px) scale(1.02);
  }

  .halo {
    position: absolute;
    inset: -20px;
    border-radius: 50%;
    background:
      radial-gradient(circle at 30% 30%, rgba(251,191,36,0.4) 0%, transparent 50%),
      radial-gradient(circle at 70% 70%, rgba(249,115,22,0.3) 0%, transparent 50%);
    filter: blur(30px);
    z-index: -1;
    animation: haloPulse 4s ease-in-out infinite;
  }

  @keyframes haloPulse {
    0%, 100% { opacity: 0.5; transform: scale(1); }
    50% { opacity: 0.9; transform: scale(1.05); }
  }

  /* ═══ معرض الصور داخل البطاقة ═══ */
  .product-gallery-3d {
    position: relative;
    width: 100%;
    aspect-ratio: 1;
    border-radius: 24px;
    overflow: hidden;
    background: linear-gradient(135deg, #fef3c7, #fed7aa);
    box-shadow:
      0 20px 40px -15px rgba(245,158,11,0.5),
      inset 0 0 40px rgba(255,255,255,0.3);
    transform: translateZ(30px);
    transform-style: preserve-3d;
    user-select: none;
    touch-action: pan-y;
  }

  .gallery-viewport {
    position: absolute;
    inset: 0;
  }

  .gallery-slide-3d {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.5s ease;
    pointer-events: none;
  }

  .gallery-slide-3d.active {
    opacity: 1;
    pointer-events: auto;
  }

  .gallery-slide-3d img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  /* ✨ لمعة */
  .shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 60%;
    height: 100%;
    background: linear-gradient(120deg, transparent 0%, rgba(255,255,255,0.6) 50%, transparent 100%);
    transform: skewX(-20deg);
    animation: shineMove 5s ease-in-out infinite;
    pointer-events: none;
    z-index: 2;
  }

  @keyframes shineMove {
    0%, 70%, 100% { left: -100%; }
    30% { left: 120%; }
  }

  /* 🔍 زر التكبير */
  .gallery-zoom-btn {
    position: absolute;
    top: 10px;
    left: 10px;
    width: 36px;
    height: 36px;
    background: rgba(255,255,255,0.95);
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.25s;
    z-index: 4;
  }

  .gallery-zoom-btn:hover {
    background: #f59e0b;
    transform: scale(1.15);
  }

  /* ⬅️➡️ الأسهم */
  .gallery-arrow-3d {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 36px;
    height: 36px;
    background: rgba(255,255,255,0.95);
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 22px;
    font-weight: 900;
    color: #1f2937;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 14px rgba(0,0,0,0.2);
    transition: all 0.25s;
    z-index: 4;
    font-family: Arial;
    line-height: 1;
    padding-bottom: 3px;
  }

  .gallery-arrow-3d:hover {
    background: #f59e0b;
    color: white;
  }

  .gallery-arrow-3d.prev { left: 8px; }
  .gallery-arrow-3d.next { right: 8px; }

  /* 📊 عداد */
  .gallery-counter-3d {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(0,0,0,0.65);
    color: white;
    font-size: 11px;
    font-weight: 900;
    padding: 4px 10px;
    border-radius: 999px;
    backdrop-filter: blur(10px);
    z-index: 4;
  }

  /* 🎨 مصغرات */
  .gallery-thumbs-3d {
    display: flex;
    gap: 6px;
    margin-top: 10px;
    overflow-x: auto;
    padding-bottom: 4px;
    scrollbar-width: thin;
    justify-content: center;
    transform: translateZ(20px);
  }

  .gallery-thumbs-3d::-webkit-scrollbar {
    height: 3px;
  }

  .gallery-thumbs-3d::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
  }

  .thumb-3d {
    flex: 0 0 auto;
    width: 50px;
    height: 50px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid transparent;
    cursor: pointer;
    background: linear-gradient(135deg, #fef3c7, #fed7aa);
    transition: all 0.25s;
    padding: 0;
  }

  .thumb-3d img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .thumb-3d.active {
    border-color: #f59e0b;
    box-shadow: 0 3px 10px rgba(245,158,11,0.4);
    transform: scale(1.08);
  }

  /* 🎨 النقاط */
  .gallery-dots-3d {
    display: flex;
    justify-content: center;
    gap: 5px;
    margin-top: 10px;
    transform: translateZ(20px);
  }

  .dot-3d {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #cbd5e1;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    padding: 0;
  }

  .dot-3d.active {
    background: #f59e0b;
    width: 20px;
    border-radius: 999px;
  }

  /* 📋 المعلومات */
  .product-info-3d {
    margin-top: 16px;
    text-align: center;
    transform: translateZ(20px);
  }

  .product-name-3d {
    font-size: 16px;
    font-weight: 900;
    color: #1f2937;
    margin-bottom: 8px;
    line-height: 1.4;
  }

  .product-price-3d {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 6px;
    margin-bottom: 10px;
    flex-wrap: wrap;
  }

  .price-now {
    font-size: 24px;
    font-weight: 900;
    color: #f59e0b;
  }

  .currency {
    font-size: 12px;
    color: #9ca3af;
    font-weight: 700;
  }

  .price-old {
    font-size: 13px;
    color: #9ca3af;
    text-decoration: line-through;
    font-weight: 700;
  }

  .badges-3d {
    display: flex;
    justify-content: center;
    gap: 5px;
    flex-wrap: wrap;
    margin-bottom: 12px;
  }

  .badge-3d {
    font-size: 10px;
    font-weight: 900;
    padding: 4px 10px;
    border-radius: 999px;
  }

  .badge-discount { background: linear-gradient(135deg, #10b981, #059669); color: white; }
  .badge-hot { background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; animation: badgePulse 1.5s ease-in-out infinite; }
  .badge-ok { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; }
  .badge-out { background: linear-gradient(135deg, #e2e8f0, #cbd5e1); color: #475569; }

  @keyframes badgePulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
  }

  /* 🛒 زر الشراء */
  .btn-buy-3d {
    flex: 1;
    padding: 14px;
    background: linear-gradient(135deg, #fbbf24, #f97316);
    color: white;
    border: none;
    border-radius: 14px;
    font-weight: 900;
    font-size: 15px;
    cursor: pointer;
    font-family: inherit;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 8px 20px -5px rgba(245,158,11,0.5), inset 0 -3px 0 rgba(0,0,0,0.1);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
    height: 52px;
  }

  .btn-buy-3d:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px -5px rgba(245,158,11,0.6), inset 0 -3px 0 rgba(0,0,0,0.15);
  }

  .btn-icon { font-size: 18px; }

  /* 🔍 Modal التكبير */
  .zoom-modal-3d {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.95);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 20px;
    cursor: zoom-out;
  }

  .zoom-modal-3d.active { display: flex; animation: fadeIn 0.3s; }

  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

  .zoom-modal-3d img {
    max-width: 95vw;
    max-height: 90vh;
    object-fit: contain;
    border-radius: 16px;
    box-shadow: 0 0 60px rgba(255,255,255,0.3);
  }

  .zoom-modal-3d button {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    color: white;
    font-size: 26px;
    cursor: pointer;
    font-family: Arial;
  }

  @media (max-width: 480px) {
    .product-3d-wrapper { min-height: 440px; padding: 15px 8px; }
    .card-3d { border-radius: 28px; padding: 20px 16px; }
    .thumb-3d { width: 44px; height: 44px; }
    .gallery-arrow-3d { width: 32px; height: 32px; font-size: 18px; }
  }
</style>

<script>
  // ═══ الشراء السريع ═══
  window.buyNow = function(productId) {
    if (window.showToast) {
      window.showToast('⚡ جاري الإضافة...', 'info', 1500);
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/cart/add/' + productId;
    form.style.display = 'none';
    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
    document.body.appendChild(form);
    form.submit();
  };

  // ═══ عرض المنتج ═══
  window.goToProduct = function(productId) {
    window.location.href = '/product/' + productId;
  };

  // ═══ تكبير الصورة ═══
  window.openZoom3D = function(src) {
    let modal = document.getElementById('zoomModal3D');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'zoomModal3D';
      modal.className = 'zoom-modal-3d';
      modal.innerHTML = '<button onclick="closeZoom3D()">×</button><img id="zoomImg3D" src="">';
      modal.onclick = function(e) { if (e.target === this) closeZoom3D(); };
      document.body.appendChild(modal);
    }
    document.getElementById('zoomImg3D').src = src;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  window.closeZoom3D = function() {
    const modal = document.getElementById('zoomModal3D');
    if (modal) {
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
  };

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeZoom3D();
  });

  </script>

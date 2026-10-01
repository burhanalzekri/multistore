{{-- 🖼️ معرض صور المنتج — متعدد الصور + مصغرات + سحب --}}

@php
  $mainImage = $product->image;
  $extraImages = is_array($product->images) ? $product->images : [];
  $allImages = array_filter(array_merge([$mainImage], $extraImages));
  $hasMultiple = count($allImages) > 1;
@endphp

<div class="product-gallery" data-product-id="{{ $product->id }}">

  {{-- 🖼️ الصورة الرئيسية --}}
  <div class="gallery-main" id="galleryMain-{{ $product->id }}">
    @foreach($allImages as $idx => $img)
    <div class="gallery-slide {{ $idx === 0 ? 'active' : '' }}" data-index="{{ $idx }}">
      <img src="{{ Storage::url($img) }}" alt="{{ $product->name }} - صورة {{ $idx + 1 }}" loading="{{ $idx === 0 ? 'eager' : 'lazy' }}">
    </div>
    @endforeach

    @if(empty($allImages))
    <div class="gallery-slide active" style="background:linear-gradient(135deg,#fef3c7,#fed7aa);display:flex;align-items:center;justify-content:center;font-size:100px;">📦</div>
    @endif

    {{-- ✨ لمعة --}}
    <div class="gallery-shine"></div>

    {{-- 🏷️ شارات --}}
    <div class="gallery-badges">
      @if($product->compare_price && $product->compare_price > $product->price)
        @php $disc = round((1 - $product->price / $product->compare_price) * 100); @endphp
        <span class="gallery-badge badge-discount">💚 خصم {{ $disc }}%</span>
      @endif
      @if($product->stock > 0 && $product->stock <= 10)
        <span class="gallery-badge badge-hot">🔥 آخر {{ $product->stock }}</span>
      @elseif($product->stock > 0)
        <span class="gallery-badge badge-ok">✅ متوفر</span>
      @else
        <span class="gallery-badge badge-out">❌ نفد</span>
      @endif
    </div>

    {{-- 🔍 زر التكبير --}}
    @if(count($allImages) > 0)
    <button type="button" class="gallery-zoom" onclick="openZoom('{{ Storage::url($allImages[0]) }}', '{{ addslashes($product->name) }}')">
      🔍
    </button>
    @endif

    {{-- ⬅️➡️ أسهم التنقل --}}
    @if($hasMultiple)
    <button type="button" class="gallery-arrow gallery-arrow-prev" onclick="changeSlide({{ $product->id }}, -1)">‹</button>
    <button type="button" class="gallery-arrow gallery-arrow-next" onclick="changeSlide({{ $product->id }}, 1)">›</button>
    @endif

    {{-- 📊 عداد الصور --}}
    @if($hasMultiple)
    <div class="gallery-counter">
      <span id="galleryCounter-{{ $product->id }}">1 / {{ count($allImages) }}</span>
    </div>
    @endif
  </div>

  {{-- 🎨 مصغرات --}}
  @if($hasMultiple)
  <div class="gallery-thumbs" id="galleryThumbs-{{ $product->id }}">
    @foreach($allImages as $idx => $img)
    <button type="button" class="gallery-thumb {{ $idx === 0 ? 'active' : '' }}" data-index="{{ $idx }}" onclick="goToSlide({{ $product->id }}, {{ $idx }})">
      <img src="{{ Storage::url($img) }}" alt="مصغرة {{ $idx + 1 }}">
    </button>
    @endforeach
  </div>
  @endif

  {{-- 🎨 نقاط الملاحة للجوال --}}
  @if($hasMultiple)
  <div class="gallery-dots">
    @foreach($allImages as $idx => $img)
    <button type="button" class="gallery-dot {{ $idx === 0 ? 'active' : '' }}" data-index="{{ $idx }}" onclick="goToSlide({{ $product->id }}, {{ $idx }})"></button>
    @endforeach
  </div>
  @endif

</div>

<style>
  .product-gallery {
    background: white;
    border-radius: 24px;
    padding: 16px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
  }

  /* ═══ الصورة الرئيسية ═══ */
  .gallery-main {
    position: relative;
    width: 100%;
    aspect-ratio: 1;
    border-radius: 20px;
    overflow: hidden;
    background: linear-gradient(135deg, #fef3c7, #fed7aa);
    user-select: none;
    touch-action: pan-y;
  }

  .gallery-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.5s ease, transform 0.5s ease;
    pointer-events: none;
  }

  .gallery-slide.active {
    opacity: 1;
    pointer-events: auto;
    transform: scale(1);
  }

  .gallery-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  /* ✨ اللمعة */
  .gallery-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 60%;
    height: 100%;
    background: linear-gradient(120deg, transparent 0%, rgba(255,255,255,0.6) 50%, transparent 100%);
    transform: skewX(-20deg);
    animation: galleryShine 5s ease-in-out infinite;
    pointer-events: none;
    z-index: 2;
  }

  @keyframes galleryShine {
    0%, 70%, 100% { left: -100%; }
    30% { left: 120%; }
  }

  /* 🏷️ الشارات */
  .gallery-badges {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    z-index: 3;
  }

  .gallery-badge {
    font-size: 11px;
    font-weight: 900;
    padding: 5px 12px;
    border-radius: 999px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    backdrop-filter: blur(10px);
  }

  .badge-discount {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
  }

  .badge-hot {
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    color: white;
    animation: badgePulse 1.5s ease-in-out infinite;
  }

  @keyframes badgePulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
  }

  .badge-ok {
    background: rgba(255,255,255,0.95);
    color: #059669;
  }

  .badge-out {
    background: rgba(71,85,105,0.95);
    color: white;
  }

  /* 🔍 زر التكبير */
  .gallery-zoom {
    position: absolute;
    top: 12px;
    left: 12px;
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.95);
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.25s;
    z-index: 3;
  }

  .gallery-zoom:hover {
    background: #f59e0b;
    transform: scale(1.15);
  }

  /* ⬅️➡️ الأسهم */
  .gallery-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 42px;
    height: 42px;
    background: rgba(255,255,255,0.95);
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 26px;
    font-weight: 900;
    color: #1f2937;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    transition: all 0.25s;
    z-index: 3;
    font-family: Arial;
    line-height: 1;
    padding-bottom: 4px;
  }

  .gallery-arrow:hover {
    background: #f59e0b;
    color: white;
    transform: translateY(-50%) scale(1.1);
  }

  .gallery-arrow-prev {
    left: 12px;
  }

  .gallery-arrow-next {
    right: 12px;
  }

  /* 📊 العداد */
  .gallery-counter {
    position: absolute;
    bottom: 12px;
    left: 12px;
    background: rgba(0,0,0,0.65);
    color: white;
    font-size: 12px;
    font-weight: 900;
    padding: 5px 12px;
    border-radius: 999px;
    backdrop-filter: blur(10px);
    z-index: 3;
  }

  /* 🎨 المصغرات */
  .gallery-thumbs {
    display: flex;
    gap: 8px;
    margin-top: 12px;
    overflow-x: auto;
    padding-bottom: 4px;
    scrollbar-width: thin;
  }

  .gallery-thumbs::-webkit-scrollbar {
    height: 4px;
  }

  .gallery-thumbs::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
  }

  .gallery-thumb {
    flex: 0 0 auto;
    width: 72px;
    height: 72px;
    border-radius: 12px;
    overflow: hidden;
    border: 3px solid transparent;
    cursor: pointer;
    background: linear-gradient(135deg, #fef3c7, #fed7aa);
    transition: all 0.25s;
    padding: 0;
    position: relative;
  }

  .gallery-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .gallery-thumb:hover {
    transform: scale(1.05);
  }

  .gallery-thumb.active {
    border-color: #f59e0b;
    box-shadow: 0 4px 12px rgba(245,158,11,0.4);
    transform: scale(1.05);
  }

  .gallery-thumb.active::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(251,191,36,0.2), rgba(249,115,22,0.2));
    pointer-events: none;
  }

  /* 🎨 النقاط */
  .gallery-dots {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin-top: 12px;
  }

  .gallery-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #cbd5e1;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    padding: 0;
  }

  .gallery-dot.active {
    background: #f59e0b;
    width: 24px;
    border-radius: 999px;
  }

  /* ═══ Modal التكبير ═══ */
  .zoom-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.95);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    cursor: zoom-out;
    padding: 20px;
  }

  .zoom-modal.active {
    display: flex;
    animation: zoomFadeIn 0.3s ease-out;
  }

  @keyframes zoomFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  .zoom-modal img {
    max-width: 95vw;
    max-height: 90vh;
    object-fit: contain;
    border-radius: 16px;
    box-shadow: 0 0 60px rgba(255,255,255,0.3);
    animation: zoomScaleIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  }

  @keyframes zoomScaleIn {
    from { transform: scale(0.7); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
  }

  .zoom-modal-close {
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
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: Arial;
    backdrop-filter: blur(10px);
    transition: all 0.2s;
  }

  .zoom-modal-close:hover {
    background: #ef4444;
    transform: rotate(90deg) scale(1.1);
  }

  @media (max-width: 480px) {
    .product-gallery {
      border-radius: 20px;
      padding: 12px;
    }

    .gallery-main {
      border-radius: 16px;
    }

    .gallery-thumb {
      width: 60px;
      height: 60px;
    }

    .gallery-arrow {
      width: 36px;
      height: 36px;
      font-size: 22px;
    }

    .gallery-badge {
      font-size: 10px;
      padding: 4px 10px;
    }
  }
</style>

<script>
  // ═══ إدارة السلايدر ═══
  window.changeSlide = function(productId, direction) {
    const gallery = document.querySelector(`[data-product-id="${productId}"]`);
    if (!gallery) return;

    const slides = gallery.querySelectorAll('.gallery-slide');
    const thumbs = gallery.querySelectorAll('.gallery-thumb');
    const dots = gallery.querySelectorAll('.gallery-dot');
    const counter = gallery.querySelector(`#galleryCounter-${productId}`);

    if (!slides.length) return;

    let current = -1;
    slides.forEach((s, i) => { if (s.classList.contains('active')) current = i; });
    if (current === -1) current = 0;

    let next = current + direction;
    if (next < 0) next = slides.length - 1;
    if (next >= slides.length) next = 0;

    goToSlide(productId, next);
  };

  window.goToSlide = function(productId, index) {
    const gallery = document.querySelector(`[data-product-id="${productId}"]`);
    if (!gallery) return;

    const slides = gallery.querySelectorAll('.gallery-slide');
    const thumbs = gallery.querySelectorAll('.gallery-thumb');
    const dots = gallery.querySelectorAll('.gallery-dot');
    const counter = gallery.querySelector(`#galleryCounter-${productId}`);

    slides.forEach((s, i) => s.classList.toggle('active', i === index));
    thumbs.forEach((t, i) => t.classList.toggle('active', i === index));
    dots.forEach((d, i) => d.classList.toggle('active', i === index));
    if (counter) counter.textContent = `${index + 1} / ${slides.length}`;

    // سوايب المصغرات للظهور
    if (thumbs[index]) {
      thumbs[index].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
  };

  // ═══ السحب (Swipe) ═══
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.gallery-main').forEach(function(el) {
      let startX = 0;
      let startY = 0;
      let distX = 0;
      let distY = 0;
      let isSwiping = false;

      el.addEventListener('touchstart', function(e) {
        const touch = e.touches[0];
        startX = touch.clientX;
        startY = touch.clientY;
        distX = 0;
        distY = 0;
        isSwiping = true;
      }, { passive: true });

      el.addEventListener('touchmove', function(e) {
        if (!isSwiping) return;
        const touch = e.touches[0];
        distX = touch.clientX - startX;
        distY = touch.clientY - startY;
      }, { passive: true });

      el.addEventListener('touchend', function() {
        if (!isSwiping) return;
        isSwiping = false;

        // سحب أفقي أكثر من 50px
        if (Math.abs(distX) > 50 && Math.abs(distX) > Math.abs(distY)) {
          const productId = el.closest('[data-product-id]')?.dataset.productId;
          if (productId) {
            changeSlide(parseInt(productId), distX > 0 ? -1 : 1);
          }
        }
      });

      // ⌨️ الأسهم
      document.addEventListener('keydown', function(e) {
        if (!el.closest('body')) return;
        const productId = el.closest('[data-product-id]')?.dataset.productId;
        if (!productId) return;
        if (e.key === 'ArrowRight') changeSlide(parseInt(productId), -1);
        if (e.key === 'ArrowLeft') changeSlide(parseInt(productId), 1);
      });
    });
  });

  // ═══ Modal التكبير ═══
  window.openZoom = function(src, alt) {
    let modal = document.getElementById('zoomModal');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'zoomModal';
      modal.className = 'zoom-modal';
      modal.innerHTML = '<button class="zoom-modal-close" onclick="closeZoom()">×</button><img id="zoomImg" src="" alt="">';
      modal.onclick = function(e) { if (e.target === this) closeZoom(); };
      document.body.appendChild(modal);
    }
    document.getElementById('zoomImg').src = src;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  window.closeZoom = function() {
    const modal = document.getElementById('zoomModal');
    if (modal) {
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
  };

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeZoom();
  });
</script>

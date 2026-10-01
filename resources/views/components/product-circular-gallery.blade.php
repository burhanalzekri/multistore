{{-- 🎡 معرض صور دائري — صور دائرية فقط --}}

@php
  $p = $product ?? null;
  if (!$p) return;

  $images = is_array($p->images) ? array_values(array_filter($p->images)) : [];
  $totalImages = count($images);
@endphp

@if($totalImages > 0)
<div class="circular-gallery" data-product-id="{{ $p->id }}">

  {{-- 🌟 هالة خلفية --}}
  <div class="cg-glow"></div>

  {{-- 🎡 شبكة الصور --}}
  <div class="cg-grid">
    @foreach($images as $idx => $img)
    <button type="button" class="cg-item" onclick="cgOpenZoom('{{ Storage::url($img) }}')" style="--i: {{ $idx }};">
      <div class="cg-item-inner">
        <img src="{{ Storage::url($img) }}" alt="{{ $p->name }} - صورة {{ $idx + 1 }}" loading="lazy">
        <div class="cg-shine"></div>
      </div>
      <div class="cg-index">{{ $idx + 1 }}</div>
    </button>
    @endforeach
  </div>

  {{-- 💡 تلميح --}}
  <div class="cg-hint">
    👆 اضغط على أي صورة للتكبير
  </div>

</div>
@endif

<style>
  .circular-gallery {
    position: relative;
    width: 100%;
    padding: 10px 0;
    user-select: none;
  }

  /* 🌟 الهالة */
  .cg-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    height: 70%;
    background: radial-gradient(ellipse at center,
      rgba(251,191,36,0.2) 0%,
      rgba(249,115,22,0.1) 30%,
      transparent 70%);
    filter: blur(40px);
    z-index: 0;
    pointer-events: none;
    animation: cgGlowPulse 4s ease-in-out infinite;
  }

  @keyframes cgGlowPulse {
    0%, 100% { opacity: 0.6; transform: translate(-50%, -50%) scale(1); }
    50% { opacity: 1; transform: translate(-50%, -50%) scale(1.1); }
  }

  /* 🎡 الشبكة */
  .cg-grid {
    position: relative;
    z-index: 1;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 10px;
  }

  /* 🖼️ العنصر الدائري */
  .cg-item {
    position: relative;
    width: 140px;
    height: 140px;
    border: none;
    background: transparent;
    padding: 0;
    cursor: pointer;
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    animation: cgFloat 4s ease-in-out infinite;
    animation-delay: calc(var(--i) * 0.3s);
  }

  @keyframes cgFloat {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-8px) rotate(2deg); }
  }

  .cg-item:hover {
    transform: scale(1.15) translateY(-5px);
    z-index: 5;
  }

  .cg-item:active {
    transform: scale(0.95);
  }

  /* ⭕ داخل الدائرة */
  .cg-item-inner {
    position: relative;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    overflow: hidden;
    background: linear-gradient(135deg, #fef3c7, #fed7aa);
    box-shadow:
      0 10px 28px -5px rgba(0, 0, 0, 0.25),
      0 0 0 5px white,
      0 0 0 8px rgba(245, 158, 11, 0.3),
      inset 0 0 20px rgba(255, 255, 255, 0.3);
    transition: all 0.35s ease;
  }

  .cg-item:hover .cg-item-inner {
    box-shadow:
      0 18px 40px -5px rgba(245, 158, 11, 0.6),
      0 0 0 4px white,
      0 0 0 8px rgba(245, 158, 11, 0.5),
      inset 0 0 20px rgba(255, 255, 255, 0.3);
  }

  .cg-item-inner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    border-radius: 50%;
    pointer-events: none;
  }

  /* ✨ لمعة */
  .cg-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 50%;
    height: 100%;
    background: linear-gradient(120deg,
      transparent 0%,
      rgba(255, 255, 255, 0.7) 50%,
      transparent 100%);
    transform: skewX(-20deg);
    animation: cgShineMove 3s ease-in-out infinite;
    pointer-events: none;
    border-radius: 50%;
    animation-delay: calc(var(--i) * 0.5s);
  }

  @keyframes cgShineMove {
    0%, 70%, 100% { left: -100%; }
    30% { left: 120%; }
  }

  /* 🔢 رقم الصورة */
  .cg-index {
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #fbbf24, #f97316);
    color: white;
    font-size: 13px;
    font-weight: 900;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.5);
    border: 2px solid white;
    z-index: 2;
  }

  /* 💡 تلميح */
  .cg-hint {
    text-align: center;
    margin-top: 16px;
    font-size: 12px;
    color: #9ca3af;
    font-weight: 700;
    animation: cgHintPulse 2s ease-in-out infinite;
  }

  @keyframes cgHintPulse {
    0%, 100% { opacity: 0.6; }
    50% { opacity: 1; }
  }

  /* 🔍 Modal التكبير */
  .cg-zoom-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.95);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 20px;
    cursor: zoom-out;
  }

  .cg-zoom-modal.active {
    display: flex;
    animation: cgFadeIn 0.3s;
  }

  @keyframes cgFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  .cg-zoom-modal img {
    max-width: 95vw;
    max-height: 90vh;
    object-fit: contain;
    border-radius: 20px;
    box-shadow: 0 0 60px rgba(255, 255, 255, 0.2);
    animation: cgZoomIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  }

  @keyframes cgZoomIn {
    from { transform: scale(0.7); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
  }

  .cg-zoom-close {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    color: white;
    font-size: 26px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: Arial;
    backdrop-filter: blur(10px);
    transition: all 0.25s;
  }

  .cg-zoom-close:hover {
    background: #ef4444;
    transform: rotate(90deg) scale(1.1);
  }

  /* 📱 الجوال */
  @media (max-width: 480px) {
    .cg-grid {
      gap: 14px;
    }

    .cg-item {
      width: 110px;
      height: 110px;
    }

    .cg-index {
      width: 22px;
      height: 22px;
      font-size: 10px;
    }
  }

  @media (max-width: 360px) {
    .cg-item {
      width: 95px;
      height: 95px;
    }
  }
</style>

<script>
  // ═══ تكبير الصورة ═══
  window.cgOpenZoom = function(src) {
    let modal = document.getElementById('cgZoomModal');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'cgZoomModal';
      modal.className = 'cg-zoom-modal';
      modal.innerHTML = '<button class="cg-zoom-close" onclick="cgCloseZoom()">×</button><img id="cgZoomImg" src="">';
      modal.onclick = function(e) {
        if (e.target === this) cgCloseZoom();
      };
      document.body.appendChild(modal);
    }

    document.getElementById('cgZoomImg').src = src;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  window.cgCloseZoom = function() {
    const modal = document.getElementById('cgZoomModal');
    if (modal) {
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
  };

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cgCloseZoom();
  });
</script>

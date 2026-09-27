{{-- ═══════════════════════════════════════════════════
     🔔 نظام التنبيهات المنبثقة الشامل
     يعرض: أخطاء، نجاح، معلومة، تحذير، شكر، تعليق
     ═══════════════════════════════════════════════════ --}}

<div id="toast-overlay" onclick="if(event.target===this) hideAllToasts()"></div>

<div id="toast-container">

  {{-- ⚠️ أخطاء الـ Validation --}}
  @if($errors->any())
    @foreach($errors->all() as $error)
    <div class="toast toast-error" data-auto-hide="6000">
      <div class="toast-icon">⚠️</div>
      <div class="toast-body">{{ $error }}</div>
      <button class="toast-close" onclick="dismissToast(this)">×</button>
    </div>
    @endforeach
  @endif

  {{-- ✅ رسالة النجاح --}}
  @if(session('success'))
    <div class="toast toast-success" data-auto-hide="5000">
      <div class="toast-icon">✅</div>
      <div class="toast-body">{{ session('success') }}</div>
      <button class="toast-close" onclick="dismissToast(this)">×</button>
    </div>
  @endif

  {{-- ❌ رسالة الخطأ --}}
  @if(session('error'))
    <div class="toast toast-error" data-auto-hide="6000">
      <div class="toast-icon">❌</div>
      <div class="toast-body">{{ session('error') }}</div>
      <button class="toast-close" onclick="dismissToast(this)">×</button>
    </div>
  @endif

  {{-- ℹ️ رسالة المعلومات --}}
  @if(session('info'))
    <div class="toast toast-info" data-auto-hide="5000">
      <div class="toast-icon">ℹ️</div>
      <div class="toast-body">{{ session('info') }}</div>
      <button class="toast-close" onclick="dismissToast(this)">×</button>
    </div>
  @endif

  {{-- ⚡ رسالة التحذير --}}
  @if(session('warning'))
    <div class="toast toast-warning" data-auto-hide="5500">
      <div class="toast-icon">⚡</div>
      <div class="toast-body">{{ session('warning') }}</div>
      <button class="toast-close" onclick="dismissToast(this)">×</button>
    </div>
  @endif

  {{-- 🙏 رسالة الشكر --}}
  @if(session('thanks'))
    <div class="toast toast-thanks" data-auto-hide="5000">
      <div class="toast-icon">🙏</div>
      <div class="toast-body">{{ session('thanks') }}</div>
      <button class="toast-close" onclick="dismissToast(this)">×</button>
    </div>
  @endif

  {{-- 💬 رسالة التعليق --}}
  @if(session('comment'))
    <div class="toast toast-comment" data-auto-hide="5500">
      <div class="toast-icon">💬</div>
      <div class="toast-body">{{ session('comment') }}</div>
      <button class="toast-close" onclick="dismissToast(this)">×</button>
    </div>
  @endif

  {{-- 🎉 رسالة التهنئة --}}
  @if(session('celebrate'))
    <div class="toast toast-celebrate" data-auto-hide="6000">
      <div class="toast-icon">🎉</div>
      <div class="toast-body">{{ session('celebrate') }}</div>
      <button class="toast-close" onclick="dismissToast(this)">×</button>
    </div>
  @endif

  {{-- 📢 رسالة الإعلان --}}
  @if(session('announce'))
    <div class="toast toast-announce" data-auto-hide="7000">
      <div class="toast-icon">📢</div>
      <div class="toast-body">{{ session('announce') }}</div>
      <button class="toast-close" onclick="dismissToast(this)">×</button>
    </div>
  @endif
</div>

<style>
  /* ═══ Overlay خلفي خفيف ═══ */
  #toast-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.15);
    backdrop-filter: blur(2px);
    z-index: 99998;
    animation: fadeIn 0.3s ease-out;
  }

  #toast-overlay.active {
    display: block;
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  /* ═══ حاوية التنبيهات — في منتصف الشاشة ═══ */
  #toast-container {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 99999;
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-width: 90vw;
    width: 420px;
    pointer-events: none;
    max-height: 80vh;
    overflow-y: auto;
    padding: 10px;
  }

  #toast-container::-webkit-scrollbar {
    width: 6px;
  }
  #toast-container::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 3px;
  }

  /* ═══ التنبيه ═══ */
  .toast {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 18px;
    border-radius: 16px;
    color: white;
    box-shadow: 0 20px 60px rgba(0,0,0,0.35), 0 0 0 4px rgba(255,255,255,0.1);
    animation: toastPopIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    pointer-events: auto;
    font-family: 'Cairo', -apple-system, BlinkMacSystemFont, sans-serif;
    font-weight: 800;
    font-size: 14px;
    line-height: 1.6;
    position: relative;
    overflow: hidden;
    border: 2px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(12px);
    transform-origin: center;
  }

  /* شريط التقدم في الأسفل */
  .toast::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    height: 4px;
    background: rgba(255,255,255,0.6);
    animation: toastProgress 5s linear forwards;
    border-radius: 4px;
    box-shadow: 0 0 8px rgba(255,255,255,0.8);
  }

  @keyframes toastProgress {
    from { width: 100%; }
    to { width: 0%; }
  }

  .toast-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    flex-shrink: 0;
    backdrop-filter: blur(8px);
    animation: iconPulse 2s ease-in-out infinite;
  }

  @keyframes iconPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.08); }
  }

  .toast-body {
    flex: 1;
    min-width: 0;
    word-break: break-word;
    text-shadow: 0 1px 3px rgba(0,0,0,0.2);
    font-size: 15px;
    padding: 4px 0;
  }

  .toast-close {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.25s;
    padding: 0;
    line-height: 1;
    font-family: Arial, sans-serif;
    font-weight: bold;
  }

  .toast-close:hover {
    background: rgba(255,255,255,0.4);
    transform: scale(1.15) rotate(90deg);
  }

  /* ═══ ألوان التنبيهات ═══ */
  .toast-error {
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    box-shadow: 0 20px 60px rgba(239,68,68,0.5), 0 0 0 4px rgba(255,255,255,0.15);
  }

  .toast-success {
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 20px 60px rgba(16,185,129,0.5), 0 0 0 4px rgba(255,255,255,0.15);
  }

  .toast-info {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    box-shadow: 0 20px 60px rgba(59,130,246,0.5), 0 0 0 4px rgba(255,255,255,0.15);
  }

  .toast-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    box-shadow: 0 20px 60px rgba(245,158,11,0.5), 0 0 0 4px rgba(255,255,255,0.15);
  }

  .toast-thanks {
    background: linear-gradient(135deg, #ec4899, #be185d);
    box-shadow: 0 20px 60px rgba(236,72,153,0.5), 0 0 0 4px rgba(255,255,255,0.15);
  }

  .toast-comment {
    background: linear-gradient(135deg, #8b5cf6, #6d28d9);
    box-shadow: 0 20px 60px rgba(139,92,246,0.5), 0 0 0 4px rgba(255,255,255,0.15);
  }

  .toast-celebrate {
    background: linear-gradient(135deg, #fbbf24, #f97316);
    box-shadow: 0 20px 60px rgba(251,191,36,0.5), 0 0 0 4px rgba(255,255,255,0.15);
  }

  .toast-announce {
    background: linear-gradient(135deg, #06b6d4, #0891b2);
    box-shadow: 0 20px 60px rgba(6,182,212,0.5), 0 0 0 4px rgba(255,255,255,0.15);
  }

  /* ═══ حركات الدخول والخروج ═══ */
  @keyframes toastPopIn {
    0% {
      opacity: 0;
      transform: scale(0.5) translateY(30px);
    }
    50% {
      transform: scale(1.05) translateY(-5px);
    }
    100% {
      opacity: 1;
      transform: scale(1) translateY(0);
    }
  }

  @keyframes toastPopOut {
    0% {
      opacity: 1;
      transform: scale(1) translateY(0);
    }
    100% {
      opacity: 0;
      transform: scale(0.5) translateY(-30px);
    }
  }

  .toast.hiding {
    animation: toastPopOut 0.35s ease-in forwards;
  }

  /* ═══ الجوال ═══ */
  @media (max-width: 480px) {
    #toast-container {
      width: calc(100% - 24px) !important;
      max-width: 100%;
    }
    .toast {
      padding: 14px 16px;
      font-size: 14px;
    }
    .toast-icon {
      width: 42px;
      height: 42px;
      font-size: 22px;
    }
    .toast-body {
      font-size: 14px;
    }
  }
</style>

<script>
  // ═══ إظهار/إخفاء الـ Overlay ═══
  function updateOverlay() {
    const container = document.getElementById('toast-container');
    const overlay = document.getElementById('toast-overlay');
    if (!container || !overlay) return;

    const hasToasts = container.querySelectorAll('.toast:not(.hiding)').length > 0;
    if (hasToasts) {
      overlay.classList.add('active');
    } else {
      overlay.classList.remove('active');
    }
  }

  function hideAllToasts() {
    document.querySelectorAll('.toast').forEach(t => {
      t.classList.add('hiding');
      setTimeout(() => t.remove(), 350);
    });
    setTimeout(updateOverlay, 400);
  }

  // ═══ إغلاق التنبيه ═══
  function dismissToast(btn) {
    const toast = btn.closest('.toast');
    if (toast) {
      toast.classList.add('hiding');
      setTimeout(() => {
        toast.remove();
        updateOverlay();
      }, 350);
    }
  }

  // ═══ الإخفاء التلقائي ═══
  document.addEventListener('DOMContentLoaded', function() {
    updateOverlay();

    const toasts = document.querySelectorAll('.toast[data-auto-hide]');
    toasts.forEach(function(toast, index) {
      const delay = parseInt(toast.dataset.autoHide) + (index * 400);
      setTimeout(function() {
        toast.classList.add('hiding');
        setTimeout(() => {
          toast.remove();
          updateOverlay();
        }, 350);
      }, delay);
    });
  });

  // ═══ دالة عامة لإنشاء تنبيه من JavaScript ═══
  window.showToast = function(message, type, duration) {
    type = type || 'info';
    duration = duration || 5000;

    let container = document.getElementById('toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toast-container';
      document.body.appendChild(container);
    }

    let overlay = document.getElementById('toast-overlay');
    if (!overlay) {
      overlay = document.createElement('div');
      overlay.id = 'toast-overlay';
      overlay.onclick = function(e) {
        if (e.target === this) hideAllToasts();
      };
      document.body.appendChild(overlay);
    }

    const icons = {
      error: '❌',
      success: '✅',
      info: 'ℹ️',
      warning: '⚡',
      thanks: '🙏',
      comment: '💬',
      celebrate: '🎉',
      announce: '📢'
    };

    const toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    toast.dataset.autoHide = duration;
    toast.innerHTML = `
      <div class="toast-icon">${icons[type] || 'ℹ️'}</div>
      <div class="toast-body">${message}</div>
      <button class="toast-close" onclick="dismissToast(this)">×</button>
    `;

    container.appendChild(toast);
    updateOverlay();

    setTimeout(() => {
      toast.classList.add('hiding');
      setTimeout(() => {
        toast.remove();
        updateOverlay();
      }, 350);
    }, duration);
  };

  // ═══ ESC لإغلاق الكل ═══
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') hideAllToasts();
  });
</script>

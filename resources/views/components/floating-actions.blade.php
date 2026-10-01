{{-- 💬 WhatsApp + 🔔 Push — الأزرار العائمة (كل الصفحات) --}}

{{-- زر WhatsApp --}}
@php
  $__waShop = $shop ?? (app(\App\Services\Tenant\TenantManager::class)->currentOrFallback());
  $__waNum = preg_replace('/[^0-9]/', '', $__waShop->whatsapp ?? '');
  if ($__waNum) {
    if (str_starts_with($__waNum, '0')) {
      $__waNum = '967' . substr($__waNum, 1);
    } elseif (strlen($__waNum) === 9) {
      $__waNum = '967' . $__waNum;
    } elseif (!str_starts_with($__waNum, '967') && strlen($__waNum) <= 10) {
      $__waNum = '967' . $__waNum;
    }
  }
  $__waUrl = $__waNum ? 'https://wa.me/' . $__waNum . '?text=' . urlencode("السلام عليكم 🌟\nأريد الاستفسار عن منتجاتكم.") : null;
@endphp

@if($__waUrl)
<a href="{{ $__waUrl }}"
   target="_blank"
   rel="noopener"
   class="ms-wa-fab"
   aria-label="تواصل عبر واتساب">
  <svg viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
  <span class="ms-wa-fab-tip">تواصل عبر واتساب</span>
</a>
@endif

{{-- زر Push --}}
<button type="button"
        class="ms-push-fab"
        onclick="window.MultiStorePush ? MultiStorePush.toggle() : alert('الإشعارات غير مدعومة')"
        aria-label="تفعيل الإشعارات"
        title="تفعيل الإشعارات">
  🔔
</button>

<style>
/* ═══ WhatsApp FAB ═══ */
.ms-wa-fab {
  position: fixed !important;
  bottom: 100px !important;
  left: 50% !important;
  margin-left: -29px !important;
  width: 58px !important;
  height: 58px !important;
  border-radius: 50% !important;
  background: linear-gradient(135deg, #25D366, #128C7E) !important;
  color: #fff !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  z-index: 9999 !important;
  box-shadow: 0 12px 30px rgba(37, 211, 102, .45) !important;
  text-decoration: none !important;
  border: 0 !important;
  transition: transform .25s, box-shadow .25s !important;
}
.ms-wa-fab:hover {
  transform: scale(1.1) translateY(-3px) !important;
  box-shadow: 0 18px 40px rgba(37, 211, 102, .55) !important;
  color: #fff !important;
}
.ms-wa-fab svg {
  display: block !important;
  fill: currentColor !important;
  flex-shrink: 0;
}
.ms-wa-fab-tip {
  position: absolute;
  bottom: 50%;
  right: 100%;
  transform: translateY(50%);
  margin-left: 12px;
  background: #17202b;
  color: #fff;
  padding: 8px 14px;
  border-radius: 10px;
  font-size: 12px;
  font-weight: 800;
  white-space: nowrap;
  opacity: 0;
  visibility: hidden;
  transition: all .25s;
  pointer-events: none;
  font-family: inherit;
}
.ms-wa-fab:hover .ms-wa-fab-tip {
  opacity: 1;
  visibility: visible;
}

/* ═══ Push FAB ═══ */
.ms-push-fab {
  position: fixed !important;
  bottom: 100px !important;
  right: 20px !important;
  width: 58px !important;
  height: 58px !important;
  border-radius: 50% !important;
  background: linear-gradient(135deg, #3b82f6, #1e40af) !important;
  color: #fff !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  font-size: 26px !important;
  z-index: 9999 !important;
  box-shadow: 0 12px 30px rgba(59, 130, 246, .45) !important;
  border: 0 !important;
  cursor: pointer !important;
  transition: transform .25s, box-shadow .25s, background .25s !important;
  font-family: inherit !important;
  line-height: 1 !important;
  padding: 0 !important;
}
.ms-push-fab:hover {
  transform: scale(1.1) translateY(-3px) !important;
  box-shadow: 0 18px 40px rgba(59, 130, 246, .55) !important;
}
.ms-push-fab::after {
  content: '';
  position: absolute;
  top: -2px;
  right: -2px;
  width: 14px;
  height: 14px;
  border-radius: 50%;
  background: #ef4444;
  border: 2px solid #fff;
  animation: ms-push-pulse 2s infinite;
}
.ms-push-fab.active {
  background: linear-gradient(135deg, #16a34a, #15803d) !important;
  box-shadow: 0 12px 30px rgba(22, 163, 74, .45) !important;
}
.ms-push-fab.active::after {
  display: none;
}
@keyframes ms-push-pulse {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.3); opacity: .6; }
}

/* ═══ Mobile ═══ */
@media (max-width: 760px) {
  .ms-wa-fab,
  .ms-push-fab {
    bottom: 150px !important;
    width: 54px !important;
    height: 54px !important;
  }
  .ms-wa-fab { left: 50% !important; margin-left: -27px !important; }
  .ms-push-fab { right: 16px !important; }
  .ms-wa-fab svg { width: 24px !important; height: 24px !important; }
  .ms-push-fab { font-size: 22px !important; }
}
</style>

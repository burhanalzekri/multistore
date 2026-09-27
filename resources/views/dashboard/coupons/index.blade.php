@extends('layouts.app')
@section('title', 'الكوبونات')
@section('page-title', '🎫 الكوبونات')
@section('page-subtitle', $coupons->total() . ' كوبون')

@section('content')

{{-- ═══ Hero Bar ═══ --}}
<div class="cp-hero">
  <div class="cp-hero-info">
    <div class="cp-hero-icon">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 12v10H4V12"/><path d="M2 7h20v5H2z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
      </svg>
    </div>
    <div>
      <div class="cp-hero-title">{{ $coupons->total() }} كوبون</div>
      <div class="cp-hero-sub">أدِر كوبونات الخصم لزيادة المبيعات</div>
    </div>
  </div>
  <a href="/dashboard/coupons/create" class="cp-btn-primary">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
    كوبون جديد
  </a>
</div>

@if($coupons->isEmpty())
  <div class="cp-empty">
    <div class="cp-empty-icon">🎁</div>
    <h3>لا توجد كوبونات بعد</h3>
    <p>أنشئ أول كوبون لتبدأ بجذب العملاء</p>
    <a href="/dashboard/coupons/create" class="cp-btn-primary">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
      كوبون أول
    </a>
  </div>
@else
  {{-- ═══ Coupons Grid ═══ --}}
  <div class="cp-grid">
    @foreach($coupons as $c)
      @php
        $isActive = $c->isValid();
        $typeLabel = $c->type === 'percentage' ? 'خصم ' . $c->value . '%' : 'خصم ' . number_format($c->value) . ' ر.ي';
        $usage = $c->max_uses ? ($c->used_count . ' / ' . $c->max_uses) : ($c->used_count . ' / ∞');
        $usagePct = $c->max_uses ? min(100, ($c->used_count / max(1, $c->max_uses)) * 100) : 0;
        $expires = $c->expires_at ? $c->expires_at->format('Y-m-d') : '∞';
        $isExpiring = $c->expires_at && $c->expires_at->diffInDays(now()) <= 7 && !$c->expires_at->isPast();
      @endphp

      <article class="cp-card {{ $isActive ? '' : 'cp-inactive' }}">
        {{-- Header --}}
        <div class="cp-card-head">
          <div class="cp-card-icon">
            @if($c->type === 'percentage') %@else ر.ي @endif
          </div>
          <div class="cp-card-status {{ $isActive ? 'cp-status-active' : 'cp-status-inactive' }}">
            <span class="cp-dot"></span>
            {{ $isActive ? 'نشط' : 'منتهي' }}
          </div>
        </div>

        {{-- Code + Type --}}
        <div class="cp-card-code">{{ $c->code }}</div>
        <div class="cp-card-type">{{ $typeLabel }}</div>

        {{-- Stats --}}
        <div class="cp-stats">
          <div class="cp-stat">
            <div class="cp-stat-label">الاستخدام</div>
            <div class="cp-stat-value">{{ $usage }}</div>
            @if($c->max_uses)
              <div class="cp-progress"><div class="cp-progress-bar" style="width:{{ $usagePct }}%"></div></div>
            @endif
          </div>
          <div class="cp-stat">
            <div class="cp-stat-label">الحد الأدنى</div>
            <div class="cp-stat-value">{{ number_format($c->min_order) }}</div>
          </div>
          <div class="cp-stat {{ $isExpiring ? 'cp-expiring' : '' }}">
            <div class="cp-stat-label">ينتهي</div>
            <div class="cp-stat-value">{{ $expires }}</div>
          </div>
        </div>

        {{-- Actions --}}
        <div class="cp-actions">
          <a href="/dashboard/coupons/{{ $c->id }}/edit" class="cp-btn-edit">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            تعديل
          </a>
          <form method="POST" action="/dashboard/coupons/{{ $c->id }}" onsubmit="return confirm('حذف الكوبون {{ $c->code }}؟')" style="flex:1">
            @csrf
            @method('DELETE')
            <button type="submit" class="cp-btn-delete">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
              </svg>
              حذف
            </button>
          </form>
        </div>
      </article>
    @endforeach
  </div>

  @if($coupons->hasPages())
    <div style="margin-top:24px">{{ $coupons->links() }}</div>
  @endif
@endif

<style>
/* ═══════════════════════════════════════════
   Coupons Page — Clean Design
   ═══════════════════════════════════════════ */
.cp-hero{display:flex;justify-content:space-between;align-items:center;gap:16px;background:linear-gradient(135deg,#fef3c7,#fed7aa);border-radius:20px;padding:20px 24px;margin-bottom:24px;border:1px solid #fcd34d}
.cp-hero-info{display:flex;align-items:center;gap:14px}
.cp-hero-icon{width:48px;height:48px;border-radius:14px;background:#fff;color:#d97706;display:grid;place-items:center;box-shadow:0 4px 12px rgba(217,119,6,.15)}
.cp-hero-title{font-size:20px;font-weight:900;color:#78350f}
.cp-hero-sub{font-size:12px;color:#92400e;font-weight:700;margin-top:2px}
.cp-btn-primary{display:inline-flex;align-items:center;gap:8px;padding:12px 20px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border-radius:12px;font-weight:900;font-size:14px;text-decoration:none;box-shadow:0 6px 16px rgba(217,119,6,.25);transition:.2s;white-space:nowrap}
.cp-btn-primary:hover{transform:translateY(-2px);box-shadow:0 10px 22px rgba(217,119,6,.35)}

.cp-empty{background:#fff;border:2px dashed #e5e7eb;border-radius:20px;padding:60px 24px;text-align:center}
.cp-empty-icon{font-size:64px;margin-bottom:12px}
.cp-empty h3{font-size:20px;font-weight:900;color:#17202b;margin-bottom:6px}
.cp-empty p{color:#64748b;font-size:14px;margin-bottom:20px}

.cp-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px}

.cp-card{background:#fff;border:1px solid #e5e7eb;border-radius:20px;padding:20px;position:relative;transition:.25s;box-shadow:0 2px 8px rgba(0,0,0,.03)}
.cp-card:hover{transform:translateY(-3px);box-shadow:0 12px 32px rgba(0,0,0,.08);border-color:transparent}
.cp-inactive{opacity:.65;background:#f9fafb}
.cp-inactive:hover{opacity:1}

.cp-card-head{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px}
.cp-card-icon{width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#fbbf24,#f97316);color:#fff;display:grid;place-items:center;font-weight:900;font-size:16px;box-shadow:0 6px 16px rgba(251,146,60,.3)}
.cp-inactive .cp-card-icon{background:linear-gradient(135deg,#9ca3af,#6b7280);box-shadow:none}

.cp-card-status{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:999px;font-size:11px;font-weight:900}
.cp-status-active{background:#dcfce7;color:#166534}
.cp-status-inactive{background:#f1f5f9;color:#64748b}
.cp-dot{width:6px;height:6px;border-radius:50%;background:currentColor}
.cp-status-active .cp-dot{animation:cp-pulse 1.8s ease-in-out infinite}
@keyframes cp-pulse{0%,100%{opacity:1}50%{opacity:.4}}

.cp-card-code{font-family:'Courier New',monospace;font-size:22px;font-weight:900;color:#17202b;letter-spacing:1px;margin-bottom:4px;word-break:break-all}
.cp-card-type{font-size:13px;color:#d97706;font-weight:800;margin-bottom:16px}

.cp-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:16px;padding-top:16px;border-top:1px solid #f0eeea}
.cp-stat{text-align:center}
.cp-stat-label{font-size:10px;color:#94a3b8;font-weight:800;text-transform:uppercase;letter-spacing:.4px;margin-bottom:4px}
.cp-stat-value{font-size:13px;font-weight:900;color:#17202b}
.cp-expiring .cp-stat-value{color:#dc2626}
.cp-progress{height:3px;background:#e5e7eb;border-radius:2px;margin-top:5px;overflow:hidden}
.cp-progress-bar{height:100%;background:linear-gradient(90deg,#f59e0b,#d97706);border-radius:2px;transition:.3s}

.cp-actions{display:flex;gap:8px}
.cp-btn-edit,.cp-btn-delete{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:10px 14px;border-radius:11px;font-size:12px;font-weight:900;border:0;cursor:pointer;transition:.2s;text-decoration:none;width:100%}
.cp-btn-edit{background:#f1f5f9;color:#334155}
.cp-btn-edit:hover{background:#e2e8f0}
.cp-btn-delete{background:#fef2f2;color:#b91c1c}
.cp-btn-delete:hover{background:#fee2e2}

/* Dark Mode */
html.dark .cp-hero{background:linear-gradient(135deg,#422006,#78350f);border-color:#78350f}
html.dark .cp-hero-icon{background:#1a1d21;color:#fbbf24}
html.dark .cp-hero-title{color:#fcd34d}
html.dark .cp-hero-sub{color:#fbbf24}
html.dark .cp-card{background:#1a1d21;border-color:#2a2e33}
html.dark .cp-card-code{color:#f1f5f9}
html.dark .cp-stat-value{color:#e5e7eb}
html.dark .cp-stat-label{color:#64748b}
html.dark .cp-btn-edit{background:#24282d;color:#cbd5e1}
html.dark .cp-btn-edit:hover{background:#2a2e33}

@media(max-width:600px){
  .cp-hero{flex-direction:column;align-items:flex-start}
  .cp-btn-primary{width:100%;justify-content:center}
  .cp-grid{grid-template-columns:1fr}
}
</style>

{{-- 🔍 التحقق الفوري + التنبيهات --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  @if(session('success'))
    setTimeout(function(){ if(window.showToast) window.showToast("{{ addslashes(session('success')) }}", 'success', 4000); }, 200);
  @endif
  @if(session('error'))
    setTimeout(function(){ if(window.showToast) window.showToast("{{ addslashes(session('error')) }}", 'error', 5000); }, 200);
  @endif
});
</script>

@endsection

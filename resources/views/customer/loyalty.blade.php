@php
  $user = auth()->user();
  $tierKey = $tier['key'] ?? 'bronze';
@endphp

<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>برنامج الولاء — {{ $shop->name ?? 'المتجر' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
body{font-family:'Tajawal',system-ui,sans-serif;background:linear-gradient(180deg,#fafaf7 0%,#f5f0e8 100%);color:#17202b;line-height:1.6;min-height:100vh}
.ly-wrap{max-width:1000px;margin:0 auto;padding:20px 16px 100px}

/* ═══ Header ═══ */
.ly-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;gap:10px}
.ly-back{display:inline-flex;align-items:center;gap:6px;padding:10px 16px;background:#fff;border-radius:14px;color:#17202b;font-weight:800;font-size:13px;text-decoration:none;box-shadow:0 2px 10px rgba(0,0,0,.06);transition:.2s}
.ly-back:hover{transform:translateX(4px);box-shadow:0 4px 16px rgba(0,0,0,.1)}
.ly-header-badge{padding:8px 14px;background:#fef3c7;color:#92400e;border-radius:12px;font-size:12px;font-weight:900;display:flex;align-items:center;gap:6px}

/* ═══ Hero ═══ */
.ly-hero{background:linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#334155 100%);border-radius:28px;padding:36px 32px;color:#fff;position:relative;overflow:hidden;margin-bottom:24px;box-shadow:0 20px 60px rgba(15,23,42,.25)}
.ly-hero::before{content:'';position:absolute;top:-40%;right:-15%;width:500px;height:500px;background:radial-gradient(circle,rgba(245,158,11,.35),transparent 60%);pointer-events:none;animation:lyFloat 8s ease-in-out infinite}
.ly-hero::after{content:'';position:absolute;bottom:-50%;left:-10%;width:400px;height:400px;background:radial-gradient(circle,rgba(139,92,246,.2),transparent 65%);pointer-events:none;animation:lyFloat 10s ease-in-out infinite reverse}
@keyframes lyFloat{0%,100%{transform:translate(0,0)}50%{transform:translate(-30px,20px)}}

.ly-stars{position:absolute;inset:0;pointer-events:none;overflow:hidden}
.ly-star{position:absolute;color:#fbbf24;font-size:12px;opacity:.4;animation:lyTwinkle 3s ease-in-out infinite}
.ly-star:nth-child(1){top:15%;right:20%;animation-delay:0s}
.ly-star:nth-child(2){top:70%;right:8%;animation-delay:.5s}
.ly-star:nth-child(3){top:40%;left:10%;animation-delay:1s}
.ly-star:nth-child(4){top:85%;right:35%;animation-delay:1.5s}
.ly-star:nth-child(5){top:25%;left:40%;animation-delay:2s}
@keyframes lyTwinkle{0%,100%{opacity:.4;transform:scale(1)}50%{opacity:1;transform:scale(1.3)}}

.ly-hero-inner{position:relative;z-index:1}
.ly-hero-greeting{font-size:13px;color:#94a3b8;font-weight:700;margin-bottom:6px;display:flex;align-items:center;gap:6px}
.ly-hero-name{font-size:24px;font-weight:900;margin-bottom:28px;background:linear-gradient(135deg,#fff,#fbbf24);-webkit-background-clip:text;-webkit-text-fill-color:transparent}

.ly-points-display{display:flex;align-items:baseline;gap:12px;margin-bottom:8px}
.ly-points-num{font-size:56px;font-weight:900;color:#fbbf24;line-height:1;letter-spacing:-2px;text-shadow:0 4px 20px rgba(251,191,36,.4)}
.ly-points-label{font-size:15px;color:#cbd5e1;font-weight:700}
.ly-points-hint{font-size:13px;color:#94a3b8;margin-bottom:24px;display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:rgba(255,255,255,.05);border-radius:10px;border:1px solid rgba(255,255,255,.08)}

.ly-tier-display{display:flex;align-items:center;gap:14px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:18px;padding:16px 20px;backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);transition:.3s;cursor:pointer}
.ly-tier-display:hover{background:rgba(255,255,255,.12);transform:translateY(-2px)}
.ly-tier-medal{width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#fbbf24,#f59e0b);display:grid;place-items:center;font-size:26px;box-shadow:0 8px 20px rgba(251,191,36,.4);flex-shrink:0}
.ly-tier-info{flex:1}
.ly-tier-name-display{font-size:17px;font-weight:900;margin-bottom:3px}
.ly-tier-perks{font-size:12px;color:#fbbf24;font-weight:800}
.ly-tier-arrow{color:#94a3b8;font-size:20px;transition:.3s}
.ly-tier-display:hover .ly-tier-arrow{transform:translateX(-4px);color:#fbbf24}

/* ═══ Stats Grid ═══ */
.ly-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px}
.ly-stat{background:#fff;border-radius:18px;padding:18px 14px;text-align:center;box-shadow:0 4px 16px rgba(0,0,0,.04);transition:.25s;border:1.5px solid transparent}
.ly-stat:hover{transform:translateY(-4px);box-shadow:0 12px 30px rgba(0,0,0,.08);border-color:#fbbf24}
.ly-stat-icon{font-size:24px;margin-bottom:8px;display:block}
.ly-stat-value{font-size:22px;font-weight:900;color:#17202b;margin-bottom:2px}
.ly-stat-label{font-size:11px;color:#94a3b8;font-weight:800;text-transform:uppercase;letter-spacing:.5px}

/* ═══ Progress ═══ */
.ly-progress-card{background:#fff;border-radius:22px;padding:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);margin-bottom:24px;position:relative;overflow:hidden}
.ly-progress-card::before{content:'';position:absolute;top:0;right:0;width:150px;height:150px;background:radial-gradient(circle,rgba(245,158,11,.08),transparent 70%);pointer-events:none}
.ly-progress-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;position:relative}
.ly-progress-title{font-size:15px;font-weight:900;display:flex;align-items:center;gap:8px}
.ly-progress-next{font-size:12px;color:#d97706;font-weight:900;padding:6px 12px;background:#fef3c7;border-radius:10px}
.ly-progress-bar{height:14px;background:#f1f5f9;border-radius:999px;overflow:hidden;margin-bottom:12px;position:relative;box-shadow:inset 0 2px 4px rgba(0,0,0,.05)}
.ly-progress-fill{height:100%;background:linear-gradient(90deg,#fbbf24,#f59e0b,#d97706);border-radius:999px;transition:width 1.5s cubic-bezier(.34,1.56,.64,1);position:relative;overflow:hidden}
.ly-progress-fill::after{content:'';position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,.4),transparent);animation:lyShine 2s infinite}
@keyframes lyShine{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}
.ly-progress-info{display:flex;justify-content:space-between;font-size:12px;color:#64748b;font-weight:800}
.ly-progress-info b{color:#17202b}

/* ═══ Rewards ═══ */
.ly-section-title{font-size:16px;font-weight:900;margin-bottom:16px;display:flex;align-items:center;gap:10px;padding-right:4px}
.ly-section-title::before{content:'';width:5px;height:22px;background:linear-gradient(180deg,#fbbf24,#d97706);border-radius:3px}

.ly-rewards-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;margin-bottom:24px}
.ly-reward{background:#fff;border-radius:18px;padding:20px 16px;text-align:center;box-shadow:0 4px 16px rgba(0,0,0,.05);transition:.3s;border:2px solid transparent;position:relative;overflow:hidden}
.ly-reward:hover{transform:translateY(-6px);box-shadow:0 16px 40px rgba(0,0,0,.12);border-color:#fbbf24}
.ly-reward-icon{font-size:44px;margin-bottom:12px;display:block;transition:.3s}
.ly-reward:hover .ly-reward-icon{transform:scale(1.15) rotate(-5deg)}
.ly-reward-name{font-size:13px;font-weight:900;margin-bottom:6px}
.ly-reward-cost{font-size:11px;color:#d97706;font-weight:900;padding:4px 10px;background:#fef3c7;border-radius:8px;display:inline-block;margin-bottom:12px}
.ly-reward-btn{width:100%;padding:10px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:0;border-radius:11px;font-size:12px;font-weight:900;cursor:pointer;transition:.2s}
.ly-reward-btn:hover{transform:scale(1.03);box-shadow:0 8px 20px rgba(217,119,6,.35)}
.ly-reward-btn:disabled{background:#f1f5f9;color:#94a3b8;cursor:not-allowed;transform:none;box-shadow:none}

/* ═══ Tiers Interactive ═══ */
.ly-tiers-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-bottom:24px}
@media(min-width:700px){.ly-tiers-grid{grid-template-columns:repeat(4,1fr)}}
.ly-tier-card{background:#fff;border-radius:18px;padding:20px 16px;text-align:center;border:2px solid #f1f5f9;transition:.3s;cursor:pointer;position:relative;overflow:hidden}
.ly-tier-card:hover{transform:translateY(-5px);border-color:#fbbf24;box-shadow:0 12px 30px rgba(245,158,11,.15)}
.ly-tier-card.active{border-color:#f59e0b;background:linear-gradient(180deg,#fffbeb,#fef3c7);box-shadow:0 12px 30px rgba(245,158,11,.25);transform:scale(1.02)}
.ly-tier-card.active::before{content:'الحالي';position:absolute;top:8px;left:8px;background:#f59e0b;color:#fff;font-size:9px;font-weight:900;padding:3px 8px;border-radius:6px}
.ly-tier-medal-lg{font-size:36px;margin-bottom:8px;display:block;transition:.3s}
.ly-tier-card:hover .ly-tier-medal-lg{transform:scale(1.2) rotate(10deg)}
.ly-tier-name-sm{font-size:13px;font-weight:900;margin-bottom:4px;color:#17202b}
.ly-tier-min{font-size:10px;color:#94a3b8;font-weight:700;margin-bottom:6px}
.ly-tier-disc{font-size:11px;color:#16a34a;font-weight:900}

/* ═══ Empty State ═══ */
.ly-empty{text-align:center;padding:50px 20px;background:#fff;border-radius:20px;box-shadow:0 4px 16px rgba(0,0,0,.04)}
.ly-empty-icon{font-size:64px;margin-bottom:12px;display:block;animation:lyBob 2s ease-in-out infinite}
@keyframes lyBob{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
.ly-empty-title{font-size:15px;font-weight:900;color:#17202b;margin-bottom:6px}
.ly-empty-text{font-size:13px;color:#94a3b8;margin-bottom:18px}
.ly-empty-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border-radius:12px;font-size:13px;font-weight:900;text-decoration:none;transition:.2s}
.ly-empty-btn:hover{transform:translateY(-2px);box-shadow:0 10px 25px rgba(217,119,6,.3)}

/* ═══ History ═══ */
.ly-history-card{background:#fff;border-radius:20px;padding:8px 20px;box-shadow:0 4px 20px rgba(0,0,0,.05)}
.ly-history-head{display:flex;justify-content:space-between;align-items:center;padding:16px 0;border-bottom:1px solid #f0eeea}
.ly-history-title{font-size:15px;font-weight:900;display:flex;align-items:center;gap:8px}
.ly-history-link{font-size:12px;color:#d97706;font-weight:900;text-decoration:none}
.ly-tx{display:flex;align-items:center;gap:12px;padding:14px 0;border-bottom:1px solid #f0eeea;transition:.2s}
.ly-tx:last-child{border:0}
.ly-tx:hover{background:#fefbf6;padding-right:4px;border-radius:8px}
.ly-tx-icon{width:40px;height:40px;border-radius:12px;display:grid;place-items:center;font-size:16px;flex-shrink:0;font-weight:900}
.ly-tx-icon.earn{background:linear-gradient(135deg,#dcfce7,#bbf7d0);color:#15803d}
.ly-tx-icon.redeem{background:linear-gradient(135deg,#fee2e2,#fecaca);color:#b91c1c}
.ly-tx-body{flex:1;min-width:0}
.ly-tx-reason{font-size:13px;font-weight:800;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#17202b}
.ly-tx-date{font-size:11px;color:#94a3b8;font-weight:700;margin-top:2px}
.ly-tx-amount{font-size:16px;font-weight:900;white-space:nowrap}
.ly-tx-amount.earn{color:#16a34a}
.ly-tx-amount.redeem{color:#dc2626}

/* ═══ Toast ═══ */
.ly-toast{position:fixed;bottom:24px;left:50%;transform:translate(-50%,100px);background:#17202b;color:#fff;padding:14px 24px;border-radius:14px;font-size:14px;font-weight:800;box-shadow:0 15px 40px rgba(0,0,0,.3);transition:transform .4s cubic-bezier(.34,1.56,.64,1);z-index:999;max-width:90vw;display:flex;align-items:center;gap:10px}
.ly-toast.show{transform:translate(-50%,0)}
.ly-toast.success{background:linear-gradient(135deg,#16a34a,#15803d)}
.ly-toast.error{background:linear-gradient(135deg,#dc2626,#991b1b)}

/* ═══ Modal ═══ */
.ly-modal{position:fixed;inset:0;background:rgba(15,23,42,.6);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;z-index:1000;padding:20px;opacity:0;transition:.3s}
.ly-modal.show{display:flex;opacity:1}
.ly-modal-content{background:#fff;border-radius:24px;padding:32px;max-width:400px;width:100%;text-align:center;transform:scale(.9);transition:.3s}
.ly-modal.show .ly-modal-content{transform:scale(1)}
.ly-modal-icon{font-size:64px;margin-bottom:16px;display:block}
.ly-modal-title{font-size:18px;font-weight:900;margin-bottom:8px}
.ly-modal-text{font-size:13px;color:#64748b;margin-bottom:20px}
.ly-modal-input{width:100%;padding:14px;border:2px solid #e2e8f0;border-radius:14px;font-size:16px;font-weight:900;text-align:center;margin-bottom:16px;outline:none;font-family:inherit}
.ly-modal-input:focus{border-color:#f59e0b;box-shadow:0 0 0 4px rgba(245,158,11,.1)}
.ly-modal-actions{display:flex;gap:10px}
.ly-modal-btn{flex:1;padding:12px;border-radius:12px;font-size:13px;font-weight:900;cursor:pointer;border:0;transition:.2s}
.ly-modal-btn.confirm{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff}
.ly-modal-btn.cancel{background:#f1f5f9;color:#475569}

@media(max-width:600px){
  .ly-hero{padding:28px 22px;border-radius:22px}
  .ly-points-num{font-size:44px}
  .ly-hero-name{font-size:20px}
  .ly-stats{grid-template-columns:1fr;gap:10px}
  .ly-stat{display:flex;align-items:center;gap:14px;text-align:right;padding:16px}
  .ly-stat-icon{margin-bottom:0}
  .ly-rewards-grid{grid-template-columns:repeat(2,1fr)}
  .ly-reward{padding:16px 12px}
  .ly-reward-icon{font-size:36px}
}
</style>
</head>
<body>

<div class="ly-wrap">
  <div class="ly-header">
    <a href="/" class="ly-back">← العودة للمتجر</a>
    <div class="ly-header-badge">⭐ عضوية مميزة</div>
  </div>

  {{-- Hero --}}
  <div class="ly-hero">
    <div class="ly-stars">
      <span class="ly-star">✦</span>
      <span class="ly-star">✦</span>
      <span class="ly-star">✦</span>
      <span class="ly-star">✦</span>
      <span class="ly-star">✦</span>
    </div>
    <div class="ly-hero-inner">
      <div class="ly-hero-greeting">👋 مرحباً بك في برنامج الولاء</div>
      <div class="ly-hero-name">{{ $user->name ?? 'عميلنا العزيز' }}</div>
      
      <div class="ly-points-display">
        <span class="ly-points-num" id="pointsDisplay">0</span>
        <span class="ly-points-label">نقطة</span>
      </div>
      <div class="ly-points-hint">💰 تساوي {{ number_format(floor($points->balance / 100) * 10) }} ر.ي عند الاستبدال</div>

      <div class="ly-tier-display" onclick="scrollToTiers()">
        <div class="ly-tier-medal">
          @php
            $medals = ['bronze'=>'🥉','silver'=>'🥈','gold'=>'🥇','platinum'=>'💎'];
          @endphp
          {{ $medals[$tierKey] ?? '🥉' }}
        </div>
        <div class="ly-tier-info">
          <div class="ly-tier-name-display">عضوية {{ $tier['name'] }}</div>
          <div class="ly-tier-perks">✦ خصم دائم {{ $tier['discount'] }}% على كل طلب</div>
        </div>
        <div class="ly-tier-arrow">←</div>
      </div>
    </div>
  </div>

  {{-- Stats --}}
  <div class="ly-stats">
    <div class="ly-stat">
      <span class="ly-stat-icon">📈</span>
      <div>
        <div class="ly-stat-value">{{ number_format($points->total_earned) }}</div>
        <div class="ly-stat-label">إجمالي المكتسب</div>
      </div>
    </div>
    <div class="ly-stat">
      <span class="ly-stat-icon">🎁</span>
      <div>
        <div class="ly-stat-value">{{ number_format($points->total_redeemed) }}</div>
        <div class="ly-stat-label">إجمالي المستبدل</div>
      </div>
    </div>
    <div class="ly-stat">
      <span class="ly-stat-icon">💎</span>
      <div>
        <div class="ly-stat-value">{{ number_format($points->balance) }}</div>
        <div class="ly-stat-label">الرصيد الحالي</div>
      </div>
    </div>
  </div>

  {{-- Progress --}}
  @if($nextTier)
    @php
      $currentMin = $tier['min'] ?? 0;
      $nextMin = $nextTier['min'];
      $pct = $nextMin > $currentMin ? (($points->balance - $currentMin) / ($nextMin - $currentMin)) * 100 : 100;
      $pct = max(0, min(100, $pct));
    @endphp
    <div class="ly-progress-card">
      <div class="ly-progress-head">
        <div class="ly-progress-title">🎯 المستوى التالي: {{ $nextTier['name'] }}</div>
        <div class="ly-progress-next">تحتاج {{ number_format($nextTier['needed']) }} نقطة</div>
      </div>
      <div class="ly-progress-bar">
        <div class="ly-progress-fill" style="width:0%" data-target="{{ $pct }}"></div>
      </div>
      <div class="ly-progress-info">
        <span>الآن: <b>{{ number_format($points->balance) }}</b> نقطة</span>
        <span>الهدف: <b>{{ number_format($nextMin) }}</b> نقطة</span>
      </div>
    </div>
  @else
    <div class="ly-progress-card" style="text-align:center;background:linear-gradient(135deg,#fef3c7,#fde68a);border:0">
      <div style="font-size:40px;margin-bottom:8px">🎉</div>
      <div style="font-size:17px;font-weight:900;color:#78350f">مبروك! وصلت لأعلى مستوى</div>
      <div style="font-size:13px;color:#92400e;margin-top:6px;font-weight:700">تستمتع بخصم {{ $tier['discount'] }}% على كل طلب</div>
    </div>
  @endif

  {{-- Rewards --}}
  <div class="ly-section-title">🎁 المكافآت المتاحة</div>
  <div class="ly-rewards-grid">
    @php
      $rewards = [
        ['icon'=>'💰','name'=>'خصم 10 ر.ي','cost'=>100,'action'=>'redeem'],
        ['icon'=>'💵','name'=>'خصم 25 ر.ي','cost'=>250,'action'=>'redeem'],
        ['icon'=>'🚚','name'=>'شحن مجاني','cost'=>500,'action'=>'redeem'],
        ['icon'=>'🎉','name'=>'خصم 50 ر.ي','cost'=>500,'action'=>'redeem'],
        ['icon'=>'🌟','name'=>'خصم 100 ر.ي','cost'=>1000,'action'=>'redeem'],
        ['icon'=>'🎊','name'=>'هدية مجانية','cost'=>2000,'action'=>'redeem'],
      ];
    @endphp
    @foreach($rewards as $r)
      <div class="ly-reward">
        <span class="ly-reward-icon">{{ $r['icon'] }}</span>
        <div class="ly-reward-name">{{ $r['name'] }}</div>
        <div class="ly-reward-cost">{{ number_format($r['cost']) }} نقطة</div>
        <button class="ly-reward-btn" 
                {{ $points->balance >= $r['cost'] ? '' : 'disabled' }}
                onclick="openRedeemModal({{ $r['cost'] }}, '{{ $r['name'] }}')">
          {{ $points->balance >= $r['cost'] ? 'استبدال الآن' : 'رصيد غير كافٍ' }}
        </button>
      </div>
    @endforeach
  </div>

  {{-- Tiers --}}
  <div id="tiersSection">
    <div class="ly-section-title">🏆 مستويات العضوية</div>
    <div class="ly-tiers-grid">
      @foreach(\App\Services\Loyalty\LoyaltyService::TIERS as $key => $t)
        <div class="ly-tier-card {{ $tierKey === $key ? 'active' : '' }}" onclick="showTierInfo('{{ $key }}')">
          <span class="ly-tier-medal-lg">{{ $medals[$key] ?? '⭐' }}</span>
          <div class="ly-tier-name-sm">{{ $t['name'] }}</div>
          <div class="ly-tier-min">من {{ number_format($t['min']) }} نقطة</div>
          <div class="ly-tier-disc">خصم {{ $t['discount'] }}%</div>
        </div>
      @endforeach
    </div>
  </div>

  {{-- History --}}
  <div class="ly-history-card">
    <div class="ly-history-head">
      <div class="ly-history-title">📜 سجل النقاط</div>
      @if($history->count() > 0)
        <a href="/loyalty/history" class="ly-history-link">عرض الكل ←</a>
      @endif
    </div>

    @if($history->count() > 0)
      @foreach($history as $tx)
        <div class="ly-tx">
          <div class="ly-tx-icon {{ $tx->type }}">
            {{ $tx->type === 'earn' ? '↑' : '↓' }}
          </div>
          <div class="ly-tx-body">
            <div class="ly-tx-reason">{{ $tx->reason }}</div>
            <div class="ly-tx-date">{{ $tx->created_at->diffForHumans() }}</div>
          </div>
          <div class="ly-tx-amount {{ $tx->type }}">
            {{ $tx->points > 0 ? '+' : '' }}{{ number_format($tx->points) }}
          </div>
        </div>
      @endforeach
    @else
      <div class="ly-empty">
        <span class="ly-empty-icon">🎯</span>
        <div class="ly-empty-title">ابدأ رحلتك الآن</div>
        <div class="ly-empty-text">كل طلب يمنحك نقاطاً — اجمعها واستبدلها بمكافآت مميزة</div>
        <a href="/demo-shop" class="ly-empty-btn">🛍️ تسوق الآن</a>
      </div>
    @endif
  </div>
</div>

{{-- Modal --}}
<div class="ly-modal" id="redeemModal" onclick="closeRedeemModal(event)">
  <div class="ly-modal-content" onclick="event.stopPropagation()">
    <span class="ly-modal-icon" id="modalIcon">🎁</span>
    <div class="ly-modal-title" id="modalTitle">تأكيد الاستبدال</div>
    <div class="ly-modal-text" id="modalText">هل تريد استبدال النقاط؟</div>
    <div class="ly-modal-actions">
      <button class="ly-modal-btn cancel" onclick="closeRedeemModal()">إلغاء</button>
      <button class="ly-modal-btn confirm" id="modalConfirm">تأكيد</button>
    </div>
  </div>
</div>

<div class="ly-toast" id="toast"></div>

<script>
// ═══ Counter Animation ═══
var pointsEl = document.getElementById('pointsDisplay');
var targetPoints = {{ $points->balance }};
var currentPoints = 0;
var duration = 1200;
var startTime = null;

function animatePoints(timestamp) {
  if (!startTime) startTime = timestamp;
  var progress = Math.min((timestamp - startTime) / duration, 1);
  var eased = 1 - Math.pow(1 - progress, 3);
  currentPoints = Math.floor(targetPoints * eased);
  pointsEl.textContent = currentPoints.toLocaleString('ar-EG');
  if (progress < 1) requestAnimationFrame(animatePoints);
  else pointsEl.textContent = targetPoints.toLocaleString('ar-EG');
}
if (targetPoints > 0) requestAnimationFrame(animatePoints);
else pointsEl.textContent = '0';

// ═══ Progress Bar Animation ═══
setTimeout(function() {
  var fill = document.querySelector('.ly-progress-fill');
  if (fill) fill.style.width = fill.dataset.target + '%';
}, 300);

// ═══ Scroll to Tiers ═══
function scrollToTiers() {
  document.getElementById('tiersSection').scrollIntoView({behavior: 'smooth', block: 'start'});
}

// ═══ Show Tier Info ═══
var tierInfo = {
  bronze: {name: 'برونزي', min: 0, disc: 5, perks: ['خصم 5% دائم', 'نقاط على كل طلب', 'دعم أساسي']},
  silver: {name: 'فضي', min: 1000, disc: 10, perks: ['خصم 10% دائم', 'شحن مخفض', 'دعم أولوي', 'عروض خاصة']},
  gold: {name: 'ذهبي', min: 5000, disc: 15, perks: ['خصم 15% دائم', 'شحن مجاني', 'دعم VIP', 'هدايا موسمية']},
  platinum: {name: 'بلاتيني', min: 20000, disc: 20, perks: ['خصم 20% دائم', 'شحن مجاني كامل', 'دعم فوري', 'هدايا حصرية', 'أولوية الشراء']}
};

function showTierInfo(key) {
  var t = tierInfo[key];
  if (!t) return;
  showToast('✨ ' + t.name + ' — من ' + t.min.toLocaleString('ar-EG') + ' نقطة | خصم ' + t.disc + '%', 'success');
}

// ═══ Toast ═══
function showToast(msg, type) {
  var t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'ly-toast show ' + (type || '');
  clearTimeout(window.__t);
  window.__t = setTimeout(function(){t.classList.remove('show');}, 3500);
}

// ═══ Redeem Modal ═══
var pendingPoints = 0;
var pendingReward = '';

function openRedeemModal(points, rewardName) {
  pendingPoints = points;
  pendingReward = rewardName;
  document.getElementById('modalIcon').textContent = '🎁';
  document.getElementById('modalTitle').textContent = 'تأكيد الاستبدال';
  document.getElementById('modalText').innerHTML = 
    'هل تريد استبدال <b style="color:#d97706">' + points.toLocaleString('ar-EG') + '</b> نقطة<br>مقابل <b>' + rewardName + '</b>?';
  
  var modal = document.getElementById('redeemModal');
  modal.classList.add('show');
  
  document.getElementById('modalConfirm').onclick = function() {
    doRedeem(pendingPoints);
  };
}

function closeRedeemModal(e) {
  if (e && e.target.id !== 'redeemModal') return;
  document.getElementById('redeemModal').classList.remove('show');
}

function doRedeem(points) {
  var btn = document.getElementById('modalConfirm');
  btn.disabled = true;
  btn.textContent = 'جاري المعالجة...';

  fetch('/loyalty/redeem', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    },
    body: JSON.stringify({ points: points })
  })
  .then(r => r.json())
  .then(d => {
    btn.disabled = false;
    btn.textContent = 'تأكيد';
    document.getElementById('redeemModal').classList.remove('show');

    if (d.ok) {
      showToast('🎉 ' + d.message + ' — قيمة ' + d.value + ' ر.ي', 'success');
      setTimeout(function() { location.reload(); }, 1800);
    } else {
      showToast('❌ ' + d.message, 'error');
    }
  })
  .catch(e => {
    btn.disabled = false;
    btn.textContent = 'تأكيد';
    document.getElementById('redeemModal').classList.remove('show');
    showToast('❌ تعذر الاتصال', 'error');
  });
}
</script>
</body>
</html>

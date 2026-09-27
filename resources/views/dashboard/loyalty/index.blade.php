@extends('layouts.app')
@section('title', 'برنامج الولاء')
@section('page-title', '🎁 برنامج الولاء')
@section('page-subtitle', 'إدارة نقاط العملاء والمكافآت')

@section('content')

<div class="loy-hero">
  <div class="loy-hero-content">
    <div class="loy-hero-icon">🏆</div>
    <div>
      <div class="loy-hero-title">لوحة برنامج الولاء</div>
      <div class="loy-hero-sub">تابع أداء البرنامج وأدر أعضاءه</div>
    </div>
  </div>
</div>

<div class="loy-stats">
  <div class="loy-stat">
    <div class="loy-stat-icon">👥</div>
    <div class="loy-stat-body">
      <div class="loy-stat-value">{{ number_format($stats['members']) }}</div>
      <div class="loy-stat-label">عضو مسجّل</div>
    </div>
  </div>
  <div class="loy-stat">
    <div class="loy-stat-icon" style="background:#dcfce7;color:#15803d">📈</div>
    <div class="loy-stat-body">
      <div class="loy-stat-value" style="color:#16a34a">{{ number_format($stats['total_issued']) }}</div>
      <div class="loy-stat-label">نقاط ممنوحة</div>
    </div>
  </div>
  <div class="loy-stat">
    <div class="loy-stat-icon" style="background:#fee2e2;color:#b91c1c">🎁</div>
    <div class="loy-stat-body">
      <div class="loy-stat-value" style="color:#dc2626">{{ number_format($stats['total_redeemed']) }}</div>
      <div class="loy-stat-label">نقاط مستبدلة</div>
    </div>
  </div>
  <div class="loy-stat">
    <div class="loy-stat-icon" style="background:#dbeafe;color:#1e40af">💎</div>
    <div class="loy-stat-body">
      <div class="loy-stat-value" style="color:#2563eb">{{ number_format($stats['total_issued'] - $stats['total_redeemed']) }}</div>
      <div class="loy-stat-label">رصيد متداول</div>
    </div>
  </div>
</div>

<div class="loy-card">
  <h2 class="loy-card-title">⚙️ إعدادات البرنامج</h2>
  <div class="loy-settings-grid">
    <div class="loy-setting">
      <div class="loy-setting-label">نقاط لكل ريال</div>
      <div class="loy-setting-value">{{ $settings['points_per_currency'] }}</div>
      <div class="loy-setting-hint">1 ريال = {{ $settings['points_per_currency'] }} نقطة</div>
    </div>
    <div class="loy-setting">
      <div class="loy-setting-label">نقاط للاستبدال</div>
      <div class="loy-setting-value">{{ $settings['points_per_redeem'] }}</div>
      <div class="loy-setting-hint">الحد الأدنى للاستبدال</div>
    </div>
    <div class="loy-setting">
      <div class="loy-setting-label">قيمة الاستبدال</div>
      <div class="loy-setting-value">{{ $settings['redeem_value'] }} ر.ي</div>
      <div class="loy-setting-hint">{{ $settings['points_per_redeem'] }} نقطة = {{ $settings['redeem_value'] }} ريال</div>
    </div>
  </div>
</div>

<div class="loy-card">
  <h2 class="loy-card-title">🏆 المستويات</h2>
  <div class="loy-tiers-grid">
    @php
      $medals = ['bronze'=>'🥉','silver'=>'🥈','gold'=>'🥇','platinum'=>'💎'];
      $bgColors = [
        'bronze' => 'linear-gradient(135deg,#cd7f32,#92400e)',
        'silver' => 'linear-gradient(135deg,#94a3b8,#475569)',
        'gold' => 'linear-gradient(135deg,#fbbf24,#d97706)',
        'platinum' => 'linear-gradient(135deg,#8b5cf6,#6d28d9)',
      ];
    @endphp
    @foreach($tiers as $key => $t)
      <div class="loy-tier-card" style="background:{{ $bgColors[$key] }}">
        <div class="loy-tier-medal">{{ $medals[$key] }}</div>
        <div class="loy-tier-name">{{ $t['name'] }}</div>
        <div class="loy-tier-min">من {{ number_format($t['min']) }} نقطة</div>
        <div class="loy-tier-disc">خصم {{ $t['discount'] }}%</div>
      </div>
    @endforeach
  </div>
</div>

<div class="loy-card">
  <h2 class="loy-card-title">⭐ أفضل الأعضاء</h2>
  @if($stats['top_members']->count() > 0)
    <div class="loy-members">
      @foreach($stats['top_members'] as $i => $m)
        <div class="loy-member">
          <div class="loy-member-rank loy-rank-{{ $i+1 <= 3 ? $i+1 : 'n' }}">
            @if($i === 0) 🥇
            @elseif($i === 1) 🥈
            @elseif($i === 2) 🥉
            @else {{ $i + 1 }}
            @endif
          </div>
          <div class="loy-member-avatar">{{ mb_substr($m->user->name ?? 'ع', 0, 1) }}</div>
          <div class="loy-member-info">
            <div class="loy-member-name">{{ $m->user->name ?? 'عميل' }}</div>
            <div class="loy-member-email">{{ $m->user->email ?? '—' }}</div>
          </div>
          <div class="loy-member-points">
            <div class="loy-member-points-value">{{ number_format($m->balance) }}</div>
            <div class="loy-member-points-label">نقطة</div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="loy-empty">
      <div style="font-size:48px;margin-bottom:12px">👥</div>
      <div style="font-weight:900;margin-bottom:6px">لا يوجد أعضاء بعد</div>
      <div style="color:#94a3b8;font-size:13px">عندما يسجّل العملاء ويطلبون، سيظهرون هنا</div>
    </div>
  @endif
</div>

<style>
.loy-hero{background:linear-gradient(135deg,#17202b,#2a3540);border-radius:22px;padding:28px;color:#fff;margin-bottom:20px;position:relative;overflow:hidden}
.loy-hero::before{content:'';position:absolute;top:-50%;right:-15%;width:400px;height:400px;background:radial-gradient(circle,rgba(245,158,11,.3),transparent 65%);pointer-events:none}
.loy-hero-content{position:relative;z-index:1;display:flex;align-items:center;gap:16px}
.loy-hero-icon{font-size:44px}
.loy-hero-title{font-size:20px;font-weight:900}
.loy-hero-sub{font-size:13px;color:#94a3b8;font-weight:700;margin-top:4px}

.loy-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px}
@media(max-width:900px){.loy-stats{grid-template-columns:repeat(2,1fr)}}
.loy-stat{background:#fff;border:1px solid #e9edf2;border-radius:18px;padding:18px;display:flex;align-items:center;gap:14px;box-shadow:0 4px 16px rgba(0,0,0,.04);transition:.25s}
.loy-stat:hover{transform:translateY(-3px);box-shadow:0 12px 28px rgba(0,0,0,.08)}
.loy-stat-icon{width:48px;height:48px;border-radius:14px;background:#fef3c7;color:#d97706;display:grid;place-items:center;font-size:22px;flex-shrink:0}
.loy-stat-value{font-size:22px;font-weight:900;color:#17202b;line-height:1}
.loy-stat-label{font-size:11px;color:#94a3b8;font-weight:800;text-transform:uppercase;letter-spacing:.4px;margin-top:4px}

.loy-card{background:#fff;border:1px solid #e9edf2;border-radius:20px;padding:22px;margin-bottom:20px;box-shadow:0 4px 16px rgba(0,0,0,.04)}
.loy-card-title{font-size:15px;font-weight:900;margin-bottom:18px;display:flex;align-items:center;gap:10px}
.loy-card-title::before{content:'';width:4px;height:20px;background:#d97706;border-radius:2px}

.loy-settings-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
@media(max-width:700px){.loy-settings-grid{grid-template-columns:1fr}}
.loy-setting{background:#f8fafc;border-radius:14px;padding:16px;text-align:center}
.loy-setting-label{font-size:11px;color:#94a3b8;font-weight:800;margin-bottom:8px;text-transform:uppercase;letter-spacing:.4px}
.loy-setting-value{font-size:24px;font-weight:900;color:#d97706;margin-bottom:4px}
.loy-setting-hint{font-size:11px;color:#64748b;font-weight:700}

.loy-tiers-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
@media(max-width:700px){.loy-tiers-grid{grid-template-columns:repeat(2,1fr)}}
.loy-tier-card{border-radius:16px;padding:18px;color:#fff;text-align:center;position:relative;overflow:hidden}
.loy-tier-medal{font-size:32px;margin-bottom:8px;display:block}
.loy-tier-name{font-size:14px;font-weight:900;margin-bottom:4px}
.loy-tier-min{font-size:10px;opacity:.85;font-weight:700;margin-bottom:6px}
.loy-tier-disc{font-size:12px;font-weight:900;background:rgba(255,255,255,.2);padding:4px 10px;border-radius:8px;display:inline-block}

.loy-members{display:flex;flex-direction:column;gap:8px}
.loy-member{display:flex;align-items:center;gap:12px;padding:12px;border-radius:14px;background:#f8fafc;transition:.2s}
.loy-member:hover{background:#fff7ed;transform:translateX(-4px)}
.loy-member-rank{width:36px;height:36px;border-radius:10px;display:grid;place-items:center;font-weight:900;font-size:14px;background:#e2e8f0;color:#475569;flex-shrink:0}
.loy-rank-1{background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#fff}
.loy-rank-2{background:linear-gradient(135deg,#cbd5e1,#94a3b8);color:#fff}
.loy-rank-3{background:linear-gradient(135deg,#cd7f32,#92400e);color:#fff}
.loy-member-avatar{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;display:grid;place-items:center;font-weight:900;font-size:16px;flex-shrink:0}
.loy-member-info{flex:1;min-width:0}
.loy-member-name{font-size:13px;font-weight:900;color:#17202b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.loy-member-email{font-size:11px;color:#94a3b8;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.loy-member-points{text-align:center;flex-shrink:0}
.loy-member-points-value{font-size:17px;font-weight:900;color:#d97706}
.loy-member-points-label{font-size:10px;color:#94a3b8;font-weight:800}

.loy-empty{text-align:center;padding:40px 20px;color:#94a3b8}
</style>


{{-- Modal: منح نقاط --}}
<div id="grantPointsModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.65);backdrop-filter:blur(6px);z-index:99999;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:24px;padding:28px;max-width:520px;width:100%;box-shadow:0 25px 70px rgba(0,0,0,0.3);max-height:90vh;overflow-y:auto;">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
      <h3 style="font-size:18px;font-weight:900;color:#0f172a;margin:0;">🎁 منح نقاط لعميل</h3>
      <button type="button" onclick="closeGrantModal()" style="width:32px;height:32px;border-radius:50%;background:#f1f5f9;border:0;font-size:18px;cursor:pointer;">×</button>
    </div>
    <p style="font-size:12px;color:#64748b;margin:0 0 18px 0;">اختر العميل وأدخل عدد النقاط + السبب</p>

    <div style="margin-bottom:14px;">
      <label style="font-size:12px;font-weight:900;color:#334155;display:block;margin-bottom:8px;">👤 العميل <span style="color:#dc2626;">*</span></label>
      <select id="grantUserId" style="width:100%;padding:12px 14px;border:1.5px solid #e2e8f0;border-radius:12px;font-size:14px;font-weight:700;font-family:inherit;outline:none;background:#fff;">
        <option value="">— اختر عميل —</option>
        @foreach(\App\Models\User::whereNotNull('shop_id')->orWhere('id', 1)->get() as $u)
          <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
        @endforeach
      </select>
    </div>

    <div style="margin-bottom:14px;">
      <label style="font-size:12px;font-weight:900;color:#334155;display:block;margin-bottom:8px;">➕ عدد النقاط <span style="color:#dc2626;">*</span></label>
      <input type="number" id="grantPoints" min="1" step="1" placeholder="100" style="width:100%;padding:12px 14px;border:1.5px solid #e2e8f0;border-radius:12px;font-size:14px;font-weight:700;font-family:inherit;outline:none;">
      <div style="font-size:11px;color:#94a3b8;margin-top:6px;">💡 100 نقطة = 10 ر.ي خصم · 1000 نقطة = 100 ر.ي</div>
    </div>

    <div style="margin-bottom:18px;">
      <label style="font-size:12px;font-weight:900;color:#334155;display:block;margin-bottom:8px;">📝 السبب (اختياري)</label>
      <input type="text" id="grantReason" placeholder="مكافأة، تعويض، شكر..." style="width:100%;padding:12px 14px;border:1.5px solid #e2e8f0;border-radius:12px;font-size:14px;font-weight:700;font-family:inherit;outline:none;">
    </div>

    <div id="grantError" style="display:none;padding:10px 14px;background:#fef2f2;color:#dc2626;border-radius:10px;font-size:12px;font-weight:800;margin-bottom:14px;"></div>

    <div style="display:flex;gap:10px;">
      <button type="button" onclick="closeGrantModal()" style="flex:1;padding:12px;background:#f1f5f9;color:#475569;border:0;border-radius:12px;font-size:13px;font-weight:800;cursor:pointer;font-family:inherit;">إلغاء</button>
      <button type="button" id="grantSubmitBtn" onclick="submitGrantPoints()" style="flex:2;padding:12px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:0;border-radius:12px;font-size:13px;font-weight:900;cursor:pointer;font-family:inherit;box-shadow:0 8px 20px rgba(16,185,129,0.35);">🎁 منح النقاط</button>
    </div>

  </div>
</div>

<script>
window.openGrantModal = function() {
  document.getElementById('grantPointsModal').style.display = 'flex';
  document.getElementById('grantError').style.display = 'none';
};
window.closeGrantModal = function() {
  document.getElementById('grantPointsModal').style.display = 'none';
  document.getElementById('grantUserId').value = '';
  document.getElementById('grantPoints').value = '';
  document.getElementById('grantReason').value = '';
};
window.submitGrantPoints = async function() {
  var userId = document.getElementById('grantUserId').value;
  var points = parseInt(document.getElementById('grantPoints').value);
  var reason = document.getElementById('grantReason').value.trim();
  var err = document.getElementById('grantError');
  var btn = document.getElementById('grantSubmitBtn');

  if (!userId || !points || points <= 0) {
    err.textContent = '⚠️ يرجى اختيار العميل وإدخال عدد صحيح من النقاط';
    err.style.display = 'block';
    return;
  }

  btn.disabled = true;
  btn.textContent = '⏳ جاري المنح...';
  err.style.display = 'none';

  try {
    var resp = await fetch('/dashboard/loyalty/grant', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      },
      body: JSON.stringify({ user_id: parseInt(userId), points: points, reason: reason || 'منح يدوي من المدير' })
    });

    var data = await resp.json();
    if (data.ok) {
      window.location.reload();
    } else {
      err.textContent = '⚠️ ' + (data.message || 'خطأ في المنح');
      err.style.display = 'block';
    }
  } catch(e) {
    err.textContent = '⚠️ خطأ في الاتصال';
    err.style.display = 'block';
  }

  btn.disabled = false;
  btn.textContent = '🎁 منح النقاط';
};
document.addEventListener('keydown', function(e){
  if (e.key === 'Escape') closeGrantModal();
});
</script>

@endsection

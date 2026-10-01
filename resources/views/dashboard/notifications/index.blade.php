@extends('layouts.app')
@section('title', 'الإشعارات')
@section('page-title', '🔔 الإشعارات')
@section('page-subtitle', 'كل التنبيهات والطلبات الجديدة')

@section('content')

<style>
  .nt-wrap {
    max-width: 900px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  /* ═══ Stats Bar ═══ */
  .nt-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
  }
  .nt-stat {
    background: #fff;
    border-radius: 16px;
    padding: 16px 18px;
    border: 1.5px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(15,23,42,0.04), 0 8px 20px -6px rgba(15,23,42,0.06);
    display: flex;
    align-items: center;
    gap: 12px;
    transition: .25s;
  }
  .nt-stat:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(15,23,42,0.06), 0 12px 28px -8px rgba(15,23,42,0.12);
  }
  .nt-stat-icon {
    width: 44px; height: 44px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    font-size: 20px;
    flex-shrink: 0;
  }
  .nt-stat-icon.total { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #1d4ed8; }
  .nt-stat-icon.unread { background: linear-gradient(135deg, #fef2f2, #fee2e2); color: #dc2626; }
  .nt-stat-icon.today { background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #16a34a; }
  .nt-stat-value {
    font-size: 22px;
    font-weight: 900;
    color: #0f172a;
    line-height: 1.1;
  }
  .nt-stat-label {
    font-size: 11px;
    font-weight: 800;
    color: #64748b;
    margin-top: 2px;
  }

  /* ═══ Toolbar ═══ */
  .nt-toolbar {
    background: #fff;
    border-radius: 16px;
    padding: 12px 16px;
    border: 1.5px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(15,23,42,0.04), 0 8px 20px -6px rgba(15,23,42,0.06);
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    justify-content: space-between;
  }
  .nt-filters {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
  }
  .nt-filter {
    padding: 8px 14px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    color: #475569;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    font-family: inherit;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: .2s;
  }
  .nt-filter:hover {
    border-color: #fbbf24;
    color: #d97706;
    background: #fffbeb;
  }
  .nt-filter.active {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-color: transparent;
    color: #fff;
    box-shadow: 0 4px 12px rgba(217,119,6,0.25);
  }
  .nt-filter-badge {
    background: rgba(255,255,255,0.25);
    padding: 1px 7px;
    border-radius: 99px;
    font-size: 10px;
    font-weight: 900;
  }
  .nt-filter.active .nt-filter-badge {
    background: rgba(0,0,0,0.15);
  }

  .nt-actions {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
  }
  .nt-btn-action {
    padding: 8px 14px;
    border-radius: 10px;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    color: #475569;
    font-size: 11px;
    font-weight: 900;
    cursor: pointer;
    font-family: inherit;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: .2s;
  }
  .nt-btn-action:hover {
    border-color: #fbbf24;
    color: #d97706;
    background: #fffbeb;
  }
  .nt-btn-action.green {
    background: linear-gradient(135deg, #10b981, #059669);
    border-color: transparent;
    color: #fff;
    box-shadow: 0 4px 12px rgba(16,185,129,0.25);
  }
  .nt-btn-action.green:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(16,185,129,0.35);
    color: #fff;
  }

  /* ═══ Notifications List ═══ */
  .nt-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .nt-date-group {
    font-size: 12px;
    font-weight: 900;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 6px 4px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .nt-date-group::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(to left, #e5e7eb, transparent);
  }

  .nt-item {
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: 18px;
    padding: 16px 18px;
    display: flex;
    gap: 14px;
    align-items: flex-start;
    position: relative;
    box-shadow: 0 1px 3px rgba(15,23,42,0.04), 0 6px 16px -6px rgba(15,23,42,0.08);
    transition: .25s;
    overflow: hidden;
  }
  .nt-item::before {
    content: '';
    position: absolute;
    top: 0; right: 0; bottom: 0;
    width: 4px;
    background: #e5e7eb;
    transition: .25s;
  }
  .nt-item.unread {
    background: linear-gradient(135deg, #fffdf5, #fff);
    border-color: #fde68a;
  }
  .nt-item.unread::before {
    background: linear-gradient(180deg, #f59e0b, #d97706);
  }
  .nt-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(15,23,42,0.06), 0 12px 28px -8px rgba(15,23,42,0.12);
    border-color: #fbbf24;
  }

  .nt-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    font-size: 22px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(15,23,42,0.08);
  }
  .nt-icon.order    { background: linear-gradient(135deg, #fef3c7, #fde68a); }
  .nt-icon.payment  { background: linear-gradient(135deg, #d1fae5, #a7f3d0); }
  .nt-icon.shipping { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
  .nt-icon.cancel   { background: linear-gradient(135deg, #fee2e2, #fecaca); }
  .nt-icon.default  { background: linear-gradient(135deg, #f1f5f9, #e2e8f0); }

  .nt-body {
    flex: 1;
    min-width: 0;
  }
  .nt-head {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 6px;
  }
  .nt-title {
    font-size: 14px;
    font-weight: 900;
    color: #17202b;
    line-height: 1.3;
  }
  .nt-badge-new {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: #fff;
    padding: 2px 8px;
    border-radius: 99px;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: 0.3px;
    animation: ntPulse 2s infinite;
  }
  @keyframes ntPulse {
    0%, 100% { opacity: 1; }
    50%      { opacity: .7; }
  }

  .nt-message {
    font-size: 13px;
    color: #475569;
    line-height: 1.6;
    font-weight: 600;
    margin-bottom: 8px;
    word-wrap: break-word;
  }

  .nt-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 11px;
    color: #94a3b8;
    font-weight: 700;
    flex-wrap: wrap;
  }
  .nt-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }

  .nt-actions-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex-shrink: 0;
    align-items: flex-end;
  }
  .nt-action-btn {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    border: 0;
    display: grid;
    place-items: center;
    cursor: pointer;
    font-size: 14px;
    transition: .2s;
    font-family: inherit;
  }
  .nt-action-btn.read {
    background: #f0fdf4;
    color: #16a34a;
  }
  .nt-action-btn.read:hover {
    background: #16a34a;
    color: #fff;
    transform: scale(1.08);
  }
  .nt-action-btn.del {
    background: #fef2f2;
    color: #dc2626;
  }
  .nt-action-btn.del:hover {
    background: #dc2626;
    color: #fff;
    transform: scale(1.08);
  }
  .nt-action-btn.view {
    background: #eff6ff;
    color: #1d4ed8;
  }
  .nt-action-btn.view:hover {
    background: #1d4ed8;
    color: #fff;
    transform: scale(1.08);
  }

  /* ═══ Empty State ═══ */
  .nt-empty {
    background: #fff;
    border-radius: 24px;
    padding: 70px 30px;
    text-align: center;
    border: 1.5px solid #e5e7eb;
    box-shadow: 0 8px 24px -8px rgba(15,23,42,0.08);
  }
  .nt-empty-icon {
    font-size: 88px;
    margin-bottom: 20px;
    filter: drop-shadow(0 12px 24px rgba(217,119,6,0.15));
  }
  .nt-empty-title {
    font-size: 22px;
    font-weight: 900;
    color: #17202b;
    margin-bottom: 8px;
  }
  .nt-empty-desc {
    font-size: 14px;
    color: #64748b;
    font-weight: 700;
    margin-bottom: 22px;
  }
  .nt-empty-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 26px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    text-decoration: none;
    border-radius: 14px;
    font-weight: 900;
    font-size: 14px;
    box-shadow: 0 10px 24px rgba(217,119,6,0.3);
    transition: .25s;
  }
  .nt-empty-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 32px rgba(217,119,6,0.4);
    color: #fff;
  }

  /* ═══ Toast ═══ */
  .nt-toast {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translate(-50%, 100px);
    background: #17202b;
    color: #fff;
    padding: 14px 24px;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 800;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    transition: transform .4s cubic-bezier(.34,1.56,.64,1);
    z-index: 999;
    max-width: 90vw;
  }
  .nt-toast.show { transform: translate(-50%, 0); }
  .nt-toast.success { background: linear-gradient(135deg, #16a34a, #15803d); }
  .nt-toast.error { background: linear-gradient(135deg, #dc2626, #991b1b); }

  @media (max-width: 640px) {
    .nt-stats { grid-template-columns: 1fr 1fr; }
    .nt-stat { padding: 12px 14px; }
    .nt-stat-icon { width: 38px; height: 38px; font-size: 18px; }
    .nt-stat-value { font-size: 18px; }
    .nt-item { padding: 14px; border-radius: 16px; }
    .nt-icon { width: 42px; height: 42px; font-size: 18px; }
    .nt-toolbar { padding: 10px 12px; }
    .nt-filter { padding: 7px 11px; font-size: 11px; }
    .nt-actions { width: 100%; justify-content: flex-start; }
    .nt-empty { padding: 50px 20px; }
    .nt-empty-icon { font-size: 68px; }
  }
</style>

@php
  $notifs = $notifications ?? collect();
  $totalCount = $notifs->count();
  $unreadCount = $notifs->where('is_read', false)->count();
  $todayCount = $notifs->filter(fn($n) => $n->created_at && $n->created_at->isToday())->count();

  // تجميع حسب التاريخ
  $grouped = $notifs->groupBy(function($n) {
      if (!$n->created_at) return 'أقدم';
      $d = $n->created_at;
      if ($d->isToday()) return 'اليوم';
      if ($d->isYesterday()) return 'أمس';
      if ($d->diffInDays(now()) <= 7) return 'هذا الأسبوع';
      return 'أقدم';
  });

  $currentFilter = request('filter', 'all');

  // فلترة
  $filtered = $notifs;
  if ($currentFilter === 'unread') $filtered = $notifs->where('is_read', false);
  elseif ($currentFilter === 'orders') $filtered = $notifs->where('type', 'order');
  elseif ($currentFilter === 'read') $filtered = $notifs->where('is_read', true);

  $filteredGrouped = $filtered->groupBy(function($n) {
      if (!$n->created_at) return 'أقدم';
      $d = $n->created_at;
      if ($d->isToday()) return 'اليوم';
      if ($d->isYesterday()) return 'أمس';
      if ($d->diffInDays(now()) <= 7) return 'هذا الأسبوع';
      return 'أقدم';
  });
@endphp

<div class="nt-wrap">

  {{-- 📊 إحصائيات --}}
  <div class="nt-stats">
    <div class="nt-stat">
      <div class="nt-stat-icon total">🔔</div>
      <div>
        <div class="nt-stat-value">{{ number_format($totalCount) }}</div>
        <div class="nt-stat-label">إجمالي الإشعارات</div>
      </div>
    </div>
    <div class="nt-stat">
      <div class="nt-stat-icon unread">🔴</div>
      <div>
        <div class="nt-stat-value">{{ number_format($unreadCount) }}</div>
        <div class="nt-stat-label">غير مقروءة</div>
      </div>
    </div>
    <div class="nt-stat">
      <div class="nt-stat-icon today">📅</div>
      <div>
        <div class="nt-stat-value">{{ number_format($todayCount) }}</div>
        <div class="nt-stat-label">اليوم</div>
      </div>
    </div>
  </div>

  {{-- 🎯 Toolbar --}}
  <div class="nt-toolbar">
    <div class="nt-filters">
      <a href="?filter=all" class="nt-filter {{ $currentFilter === 'all' ? 'active' : '' }}">
        الكل <span class="nt-filter-badge">{{ $totalCount }}</span>
      </a>
      <a href="?filter=unread" class="nt-filter {{ $currentFilter === 'unread' ? 'active' : '' }}">
        🔴 غير مقروء <span class="nt-filter-badge">{{ $unreadCount }}</span>
      </a>
      <a href="?filter=orders" class="nt-filter {{ $currentFilter === 'orders' ? 'active' : '' }}">
        🛒 الطلبات
      </a>
      <a href="?filter=read" class="nt-filter {{ $currentFilter === 'read' ? 'active' : '' }}">
        ✓ المقروءة
      </a>
    </div>

    <div class="nt-actions">
      @if($unreadCount > 0)
        <button type="button" class="nt-btn-action green" onclick="markAllRead(this)">
          ✓ تعليم الكل كمقروء
        </button>
      @endif
      <button type="button" class="nt-btn-action" onclick="clearRead(this)">
        🗑️ مسح المقروءة
      </button>
    </div>
  </div>

  {{-- 📋 قائمة الإشعارات --}}
  @if($filtered->count() > 0)
    <div class="nt-list">
      @foreach($filteredGrouped as $group => $items)
        <div class="nt-date-group">{{ $group }}</div>

        @foreach($items as $notif)
          @php
            $type = $notif->type ?? 'default';
            $iconMap = [
              'order' => '🛒',
              'payment' => '💰',
              'shipping' => '🚚',
              'cancel' => '❌',
              'price' => '🏷️',
            ];
            $icon = $iconMap[$type] ?? '🔔';
            $iconClass = in_array($type, ['order','payment','shipping','cancel']) ? $type : 'default';

            // بيانات من JSON
            $data = is_array($notif->data) ? $notif->data : (json_decode($notif->data ?? '[]', true) ?: []);
            $url = $data['url'] ?? null;
            if (!$url && !empty($data['order_id'])) {
              $url = '/dashboard/orders/' . $data['order_id'];
            }
          @endphp
          <div class="nt-item {{ $notif->is_read ? '' : 'unread' }}" data-id="{{ $notif->id }}">
            <div class="nt-icon {{ $iconClass }}">{{ $icon }}</div>

            <div class="nt-body">
              <div class="nt-head">
                <span class="nt-title">{{ $notif->title }}</span>
                @if(!$notif->is_read)
                  <span class="nt-badge-new">جديد</span>
                @endif
              </div>

              @if($notif->message)
                <div class="nt-message">{{ $notif->message }}</div>
              @endif

              <div class="nt-meta">
                <span class="nt-meta-item">
                  🕐 {{ $notif->created_at ? $notif->created_at->diffForHumans() : '—' }}
                </span>
                @if(!empty($data['order_number']))
                  <span class="nt-meta-item">📋 {{ $data['order_number'] }}</span>
                @endif
                @if(!empty($data['total']))
                  <span class="nt-meta-item">💰 {{ number_format($data['total']) }} ر.ي</span>
                @endif
              </div>
            </div>

            <div class="nt-actions-item">
              @if($url)
                <a href="{{ $url }}" class="nt-action-btn view" title="عرض">
                  👁️
                </a>
              @endif
              @if(!$notif->is_read)
                <button type="button" class="nt-action-btn read" title="تعليم كمقروء"
                  onclick="markRead({{ $notif->id }}, this)">
                  ✓
                </button>
              @endif
              <button type="button" class="nt-action-btn del" title="حذف"
                onclick="deleteNotif({{ $notif->id }}, this)">
                🗑️
              </button>
            </div>
          </div>
        @endforeach
      @endforeach
    </div>
  @else
    <div class="nt-empty">
      <div class="nt-empty-icon">
        @if($currentFilter === 'unread') 🔕
        @elseif($currentFilter === 'read') 📭
        @else 🔔
        @endif
      </div>
      <div class="nt-empty-title">
        @if($currentFilter === 'unread') لا توجد إشعارات غير مقروءة
        @elseif($currentFilter === 'read') لا توجد إشعارات مقروءة
        @else لا توجد إشعارات بعد
        @endif
      </div>
      <div class="nt-empty-desc">
        @if($currentFilter === 'all')
          ستظهر هنا التنبيهات عند وصول طلبات جديدة
        @else
          جرّب فلتر آخر
        @endif
      </div>
      @if($currentFilter !== 'all')
        <a href="?" class="nt-empty-btn">← عرض الكل</a>
      @endif
    </div>
  @endif

</div>

<div id="ntToast" class="nt-toast"></div>

<script>
  const CSRF = '{{ csrf_token() }}';

  function showToast(msg, type) {
    var t = document.getElementById('ntToast');
    t.textContent = msg;
    t.className = 'nt-toast show ' + (type || '');
    clearTimeout(window._nt);
    window._nt = setTimeout(function() { t.classList.remove('show'); }, 2500);
  }

  // ═══ تعليم إشعار كمقروء ═══
  async function markRead(id, btn) {
    if (!confirm('تعليم كمقروء؟')) return;
    btn.disabled = true;
    btn.textContent = '...';

    try {
      var resp = await fetch('/dashboard/notifications/' + id + '/read', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': CSRF,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
      });

      if (resp.ok) {
        var item = document.querySelector('.nt-item[data-id="' + id + '"]');
        if (item) {
          item.classList.remove('unread');
          var badge = item.querySelector('.nt-badge-new');
          if (badge) badge.remove();
          btn.remove();
        }
        showToast('✅ تم التعليم كمقروء', 'success');
        // تحديث العدادات في الـ sidebar إذا وجدت
        setTimeout(function() { location.reload(); }, 800);
      } else {
        showToast('خطأ', 'error');
        btn.disabled = false;
        btn.textContent = '✓';
      }
    } catch(e) {
      showToast('خطأ في الاتصال', 'error');
      btn.disabled = false;
      btn.textContent = '✓';
    }
  }

  // ═══ تعليم الكل كمقروء ═══
  async function markAllRead(btn) {
    if (!confirm('تعليم كل الإشعارات كمقروءة؟')) return;
    btn.disabled = true;
    var orig = btn.innerHTML;
    btn.innerHTML = '⏳ جاري...';

    try {
      var resp = await fetch('/dashboard/notifications/read-all', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': CSRF,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
      });

      if (resp.ok) {
        showToast('✅ تم تعليم الكل كمقروء', 'success');
        setTimeout(function() { location.reload(); }, 800);
      } else {
        showToast('خطأ', 'error');
        btn.disabled = false;
        btn.innerHTML = orig;
      }
    } catch(e) {
      showToast('خطأ في الاتصال', 'error');
      btn.disabled = false;
      btn.innerHTML = orig;
    }
  }

  // ═══ حذف إشعار ═══
  async function deleteNotif(id, btn) {
    if (!confirm('حذف هذا الإشعار؟')) return;
    var item = document.querySelector('.nt-item[data-id="' + id + '"]');
    if (item) { item.style.opacity = '0.4'; item.style.pointerEvents = 'none'; }

    try {
      var resp = await fetch('/dashboard/notifications/' + id, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': CSRF,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
      });

      if (resp.ok) {
        showToast('✅ تم الحذف', 'success');
        if (item) {
          item.style.transition = '.3s';
          item.style.transform = 'translateX(-100px)';
          item.style.opacity = '0';
          setTimeout(function() { item.remove(); }, 300);
        }
      } else {
        showToast('خطأ', 'error');
        if (item) { item.style.opacity = '1'; item.style.pointerEvents = 'auto'; }
      }
    } catch(e) {
      showToast('خطأ في الاتصال', 'error');
      if (item) { item.style.opacity = '1'; item.style.pointerEvents = 'auto'; }
    }
  }

  // ═══ مسح المقروءة ═══
  async function clearRead(btn) {
    if (!confirm('حذف كل الإشعارات المقروءة؟')) return;
    btn.disabled = true;
    var orig = btn.innerHTML;
    btn.innerHTML = '⏳ جاري...';

    try {
      var resp = await fetch('/dashboard/notifications-clear/read', {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': CSRF,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
      });

      if (resp.ok) {
        showToast('✅ تم المسح', 'success');
        setTimeout(function() { location.reload(); }, 800);
      } else {
        showToast('خطأ', 'error');
        btn.disabled = false;
        btn.innerHTML = orig;
      }
    } catch(e) {
      showToast('خطأ في الاتصال', 'error');
      btn.disabled = false;
      btn.innerHTML = orig;
    }
  }
</script>

@endsection

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>الإشعارات — MultiStore</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
  * { font-family: Cairo, sans-serif; box-sizing: border-box; margin: 0; padding: 0; }
  body { background: #f5f7fa; padding: 20px; }
  .card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    border-right: 5px solid #f59e0b;
    transition: all 0.2s;
  }
  .card:hover { transform: translateX(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
  .card.unread { border-right-color: #ef4444; background: #fef2f2; }
  .card.read { opacity: 0.65; }
  .badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
  }
</style>
</head>
<body>

<div style="max-width:900px;margin:0 auto;">

  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <div>
      <h1 style="font-size:28px;font-weight:900;margin-bottom:4px;">🔔 الإشعارات</h1>
      <p style="color:#6b7280;font-size:14px;">كل إشعارات المنصة</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <form method="POST" action="{{ route('admin.notifications.readAll') }}" style="display:inline;">
        @csrf
        <button type="submit" style="padding:10px 18px;background:#10b981;color:white;border:none;border-radius:10px;font-weight:900;font-size:13px;cursor:pointer;font-family:inherit;">
          ✅ تعليم الكل كمقروء
        </button>
      </form>
      <a href="/dashboard" style="padding:10px 18px;background:#f1f5f9;color:#475569;border-radius:10px;text-decoration:none;font-weight:900;font-size:13px;">
        ← رجوع
      </a>
    </div>
  </div>

  @php
    $unreadCount = $notifications->where('is_read', false)->count();
  @endphp

  @if($unreadCount > 0)
  <div style="background:#fee2e2;padding:14px 18px;border-radius:12px;margin-bottom:16px;font-weight:900;color:#b91c1c;">
    📬 لديك {{ $unreadCount }} إشعار غير مقروء
  </div>
  @endif

  @forelse($notifications as $notif)
  <div class="card {{ $notif->is_read ? 'read' : 'unread' }}">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;">
      <div style="flex:1;min-width:250px;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
          <span style="font-size:24px;">
            @if($notif->type === 'new_shop') 🏪
            @elseif($notif->type === 'new_order') 🛒
            @elseif($notif->type === 'new_payment') 💰
            @elseif($notif->type === 'new_user') 👤
            @else 📬 @endif
          </span>
          <div style="font-weight:900;font-size:16px;color:#1f2937;">{{ $notif->title }}</div>
          @if(!$notif->is_read)
            <span class="badge" style="background:#ef4444;color:white;">جديد</span>
          @endif
        </div>
        <div style="font-size:14px;color:#4b5563;line-height:1.7;white-space:pre-line;">{{ $notif->message }}</div>

        @if($notif->data)
        <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap;">
          @if(isset($notif->data['shop_id']))
            <a href="{{ route('owner.shops.show', $notif->data['shop_id']) }}" style="padding:6px 12px;background:#dbeafe;color:#1e40af;border-radius:8px;text-decoration:none;font-weight:900;font-size:12px;">
              👁️ عرض المتجر
            </a>
          @endif
          @if(isset($notif->data['order_id']))
            <a href="/dashboard/orders/{{ $notif->data['order_id'] }}" style="padding:6px 12px;background:#fef3c7;color:#92400e;border-radius:8px;text-decoration:none;font-weight:900;font-size:12px;">
              👁️ عرض الطلب
            </a>
          @endif
        </div>
        @endif

        <div style="margin-top:10px;font-size:11px;color:#9ca3af;font-weight:800;">
          🕐 {{ $notif->created_at ? $notif->created_at->diffForHumans() : '—' }}
          @if($notif->read_at)
            • ✅ قُرئ {{ $notif->read_at->diffForHumans() }}
          @endif
        </div>
      </div>

      @if(!$notif->is_read)
      <form method="POST" action="{{ route('admin.notifications.read', $notif->id) }}" style="display:inline;">
        @csrf
        <button type="submit" style="padding:8px 14px;background:#d1fae5;color:#065f46;border:none;border-radius:8px;cursor:pointer;font-weight:900;font-size:12px;font-family:inherit;">
          ✅ قرأت
        </button>
      </form>
      @endif
    </div>
  </div>
  @empty
  <div style="text-align:center;padding:60px 20px;background:white;border-radius:20px;box-shadow:0 2px 12px rgba(0,0,0,0.05);">
    <div style="font-size:64px;margin-bottom:16px;">🔔</div>
    <h3 style="font-size:18px;font-weight:900;color:#1f2937;margin-bottom:8px;">لا توجد إشعارات</h3>
    <p style="color:#6b7280;font-size:14px;">ستظهر هنا الإشعارات الجديدة عند تسجيل متاجر أو إجراء عمليات</p>
  </div>
  @endforelse

  @if($notifications->hasPages())
  <div style="display:flex;justify-content:center;margin-top:20px;">
    {{ $notifications->links() }}
  </div>
  @endif

</div>

{{-- 🔔 التنبيهات --}}
@include('components.toast')

</body>
</html>

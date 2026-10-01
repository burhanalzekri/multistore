<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>الطلب {{ $order->order_number }} — التتبع</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Cairo', sans-serif; background: #f5f7fa; }
  .timeline-dot { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; position: relative; z-index: 2; }
  .timeline-line { position: absolute; right: 21px; top: 44px; bottom: 0; width: 2px; background: #e5e7eb; z-index: 1; }
  .step-active .timeline-line { background: linear-gradient(180deg, #10b981, #e5e7eb); }
  .pulse-green { animation: pulseGreen 2s infinite; }
  @keyframes pulseGreen {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.6); }
    50% { box-shadow: 0 0 0 12px rgba(16,185,129,0); }
  }
</style>
</head>
<body>

<header style="background:white;padding:16px 20px;box-shadow:0 2px 8px rgba(0,0,0,0.05);display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:50;">
  <a href="/shop" style="display:flex;align-items:center;gap:10px;text-decoration:none;color:inherit;">
    <div style="width:40px;height:40px;background:linear-gradient(135deg,#fbbf24,#f97316);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;">🍯</div>
    <div style="font-weight:900;">{{ $order->shop->name ?? 'المتجر' }}</div>
  </a>
  <a href="/track" style="color:#f59e0b;font-weight:800;font-size:13px;text-decoration:none;">🔍 تتبع آخر</a>
</header>

<div style="max-width:640px;margin:0 auto;padding:24px 20px;">

  <!-- رأس الطلب -->
  <div style="background:linear-gradient(135deg,#fbbf24,#f97316);border-radius:24px;padding:24px;color:white;margin-bottom:20px;box-shadow:0 8px 24px rgba(245,158,11,0.35);">
    <div style="font-size:13px;opacity:0.9;margin-bottom:4px;">رقم الطلب</div>
    <div style="font-size:24px;font-weight:900;font-family:monospace;margin-bottom:12px;">{{ $order->order_number }}</div>
    <div style="display:flex;gap:16px;font-size:13px;">
      <div>
        <div style="opacity:0.8;">التاريخ</div>
        <div style="font-weight:900;">{{ $order->created_at->format('Y-m-d') }}</div>
      </div>
      <div>
        <div style="opacity:0.8;">الإجمالي</div>
        <div style="font-weight:900;">{{ number_format($order->total) }} ريال</div>
      </div>
    </div>
  </div>

  <!-- الحالة الحالية -->
  @php
    $steps = [
      ['awaiting_payment', 'بانتظار الدفع', '⏳', '#f59e0b'],
      ['processing', 'قيد التحضير', '⚙️', '#3b82f6'],
      ['shipped', 'قيد الشحن', '🚚', '#8b5cf6'],
      ['delivered', 'تم التسليم', '✅', '#10b981'],
    ];
    $currentIndex = array_search($order->status, array_column($steps, 0));
    if ($currentIndex === false) $currentIndex = -1;
  @endphp

  <div style="background:white;border-radius:24px;padding:24px;margin-bottom:20px;box-shadow:0 4px 20px rgba(0,0,0,0.06);">
    <h2 style="font-size:16px;font-weight:900;margin:0 0 20px 0;color:#1f2937;">📊 حالة الطلب</h2>

    <div style="position:relative;">
      @foreach($steps as $i => $step)
      @php
        $isActive = $i <= $currentIndex;
        $isCurrent = $i === $currentIndex;
      @endphp
      <div style="display:flex;gap:16px;position:relative;padding-bottom:24px;" class="{{ $isActive ? 'step-active' : '' }}">
        
        <!-- الخط الرأسي -->
        @if($i < count($steps) - 1)
        <div style="position:absolute;right:22px;top:44px;bottom:0;width:2px;background:{{ $isActive && $i < $currentIndex ? '#10b981' : '#e5e7eb' }};z-index:1;"></div>
        @endif

        <!-- الدائرة -->
        <div class="timeline-dot {{ $isCurrent ? 'pulse-green' : '' }}"
             style="background:{{ $isActive ? $step[3] : '#e5e7eb' }};color:white;box-shadow:{{ $isActive ? '0 4px 12px rgba(0,0,0,0.15)' : 'none' }};">
          @if($isActive) {{ $step[2] }} @else ○ @endif
        </div>

        <!-- المحتوى -->
        <div style="flex:1;padding-top:8px;">
          <div style="font-weight:900;font-size:15px;color:{{ $isActive ? '#1f2937' : '#9ca3af' }};">
            {{ $step[1] }}
          </div>
          @if($isCurrent)
          <div style="font-size:12px;color:#10b981;font-weight:800;margin-top:4px;">← الحالة الحالية</div>
          @endif
          @if($order->shipped_at && $step[0] === 'shipped')
          <div style="font-size:12px;color:#9ca3af;margin-top:4px;">{{ $order->shipped_at->format('Y-m-d H:i') }}</div>
          @endif
          @if($order->delivered_at && $step[0] === 'delivered')
          <div style="font-size:12px;color:#9ca3af;margin-top:4px;">{{ $order->delivered_at->format('Y-m-d H:i') }}</div>
          @endif
        </div>
      </div>
      @endforeach
    </div>

    @if($order->tracking_number)
    <div style="margin-top:16px;padding:14px;background:#dbeafe;border-radius:12px;">
      <div style="font-size:11px;color:#1d4ed8;font-weight:800;margin-bottom:4px;">رقم التتبع</div>
      <div style="font-family:monospace;font-weight:900;font-size:16px;color:#1e40af;">{{ $order->tracking_number }}</div>
      @if($order->carrier)
      <div style="font-size:11px;color:#1d4ed8;margin-top:4px;">{{ $order->carrier }}</div>
      @endif
    </div>
    @endif
  </div>

  <!-- المنتجات -->
  <div style="background:white;border-radius:24px;padding:24px;margin-bottom:20px;box-shadow:0 4px 20px rgba(0,0,0,0.06);">
    <h2 style="font-size:16px;font-weight:900;margin:0 0 16px 0;color:#1f2937;">🛍️ المنتجات</h2>
    @foreach($order->items as $item)
    <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f3f4f6;font-size:14px;">
      <span style="color:#1f2937;">{{ $item->product_name }} × {{ $item->quantity }}</span>
      <span style="font-weight:900;color:#f59e0b;">{{ number_format($item->line_total) }} ريال</span>
    </div>
    @endforeach
    <div style="margin-top:12px;padding-top:12px;border-top:2px dashed #e5e7eb;display:flex;justify-content:space-between;font-size:18px;font-weight:900;">
      <span>الإجمالي:</span>
      <span style="color:#f59e0b;">{{ number_format($order->total) }} ريال</span>
    </div>
  </div>

  <!-- السجل -->
  @if($order->statusHistory->count() > 0)
  <div style="background:white;border-radius:24px;padding:24px;box-shadow:0 4px 20px rgba(0,0,0,0.06);">
    <h2 style="font-size:16px;font-weight:900;margin:0 0 16px 0;color:#1f2937;">📋 سجل التحديثات</h2>
    @foreach($order->statusHistory as $h)
    <div style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid #f3f4f6;font-size:13px;">
      <div style="font-size:18px;">•</div>
      <div style="flex:1;">
        <div style="font-weight:800;color:#1f2937;">
          {{ $h->from_status }} → {{ $h->to_status }}
        </div>
        @if($h->note)
        <div style="color:#6b7280;margin-top:2px;">{{ $h->note }}</div>
        @endif
        <div style="color:#9ca3af;font-size:11px;margin-top:4px;">
          {{ $h->changed_by_name }} — {{ $h->created_at->diffForHumans() }}
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @endif

  <div style="margin-top:24px;text-align:center;">
    <a href="/shop" style="display:inline-block;padding:12px 24px;background:#1f2937;color:white;border-radius:12px;text-decoration:none;font-weight:900;">
      ← العودة للمتجر
    </a>
  </div>

</div>


  {{-- 🔔 التنبيهات --}}
  @include('components.toast')

</body>
</html>

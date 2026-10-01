<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>نتائج البحث</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">
<style>body{font-family:'Cairo',sans-serif;background:#f5f7fa;}</style>
</head>
<body>

<header style="background:white;padding:14px 20px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
  <a href="/track" style="color:#f59e0b;font-weight:800;text-decoration:none;">← ابحث مرة أخرى</a>
</header>

<div style="max-width:640px;margin:0 auto;padding:24px 16px;">
  <h1 style="font-size:22px;font-weight:900;margin:0 0 6px 0;">📋 نتائج البحث</h1>
  <p style="color:#6b7280;font-size:13px;margin:0 0 20px 0;">وجدنا {{ $orders->count() }} طلب</p>

  @foreach($orders as $order)
  <a href="/track/{{ $order->order_number }}" style="background:white;border-radius:20px;padding:20px;margin-bottom:12px;display:block;text-decoration:none;color:inherit;box-shadow:0 2px 12px rgba(0,0,0,0.04);transition:all 0.2s;"
    onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,0.08)'"
    onmouseout="this.style.transform='';this.boxShadow=''">
    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:10px;">
      <div>
        <div style="font-family:monospace;font-weight:900;font-size:15px;">{{ $order->order_number }}</div>
        <div style="font-size:12px;color:#6b7280;margin-top:3px;">{{ $order->customer_name }}</div>
      </div>
      <span style="background:{{ $order->status === 'delivered' ? '#dcfce7' : ($order->status === 'cancelled' ? '#fee2e2' : '#fef3c7') }};color:{{ $order->status === 'delivered' ? '#15803d' : ($order->status === 'cancelled' ? '#b91c1c' : '#b45309') }};font-size:11px;font-weight:900;padding:4px 12px;border-radius:999px;">
        {{ ['awaiting_payment'=>'بانتظار الدفع','processing'=>'قيد التحضير','shipped'=>'قيد الشحن','delivered'=>'تم التسليم','cancelled'=>'ملغى'][$order->status] ?? $order->status }}
      </span>
    </div>
    <div style="display:flex;justify-content:space-between;font-size:13px;">
      <span style="color:#6b7280;">{{ $order->created_at->format('Y-m-d') }}</span>
      <span style="font-weight:900;color:#f59e0b;">{{ number_format($order->total) }} ريال</span>
    </div>
  </a>
  @endforeach
</div>


  {{-- 🔔 التنبيهات --}}
  @include('components.toast')

</body>
</html>

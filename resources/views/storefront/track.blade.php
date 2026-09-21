<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تتبع الطلب</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>body{font-family:'Cairo',sans-serif}</style>
</head>
<body class="bg-slate-50 min-h-screen p-4">
<div class="max-w-2xl mx-auto py-8">
  <a href="/shop" class="text-sm text-slate-500">← المتجر</a>

  @if(!$order)
  <div class="bg-white rounded-3xl p-12 text-center shadow-lg mt-6">
    <div class="text-7xl mb-4">🔍</div>
    <h2 class="text-xl font-black mb-2">لم نجد الطلب</h2>
    <p class="text-slate-500 text-sm">رقم الطلب: {{ $number }}</p>
  </div>
  @else
  <div class="bg-white rounded-3xl p-6 shadow-lg mt-6">
    <div class="text-center mb-6 pb-6 border-b border-dashed">
      <div class="text-xs text-slate-500">رقم الطلب</div>
      <div class="text-2xl font-black font-mono">{{ $order->order_number }}</div>
      <div class="text-sm text-slate-500 mt-1">{{ $order->customer_name }}</div>
    </div>

    <div class="space-y-4">
      @php
        $steps = [
          ['icon' => 'check-circle', 'title' => 'تم استلام الطلب', 'done' => true],
          ['icon' => 'settings', 'title' => 'قيد التحضير', 'done' => in_array($order->status, ['processing','shipped','delivered'])],
          ['icon' => 'truck', 'title' => 'في الطريق', 'done' => in_array($order->status, ['shipped','delivered'])],
          ['icon' => 'home', 'title' => 'تم التسليم', 'done' => $order->status === 'delivered'],
        ];
      @endphp

      @foreach($steps as $i => $step)
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full {{ $step['done'] ? 'bg-green-500' : 'bg-slate-200' }} flex items-center justify-center shrink-0">
          <i data-lucide="{{ $step['icon'] }}" class="w-5 h-5 {{ $step['done'] ? 'text-white' : 'text-slate-400' }}"></i>
        </div>
        <div class="flex-1">
          <div class="font-bold text-sm {{ $step['done'] ? 'text-slate-800' : 'text-slate-400' }}">{{ $step['title'] }}</div>
        </div>
        @if($step['done'])<i data-lucide="check" class="w-4 h-4 text-green-600"></i>@endif
      </div>
      @endforeach
    </div>

    <div class="mt-6 pt-6 border-t">
      <div class="flex justify-between text-sm mb-2"><span class="text-slate-500">الإجمالي:</span><b class="text-amber-600">{{ number_format($order->total) }} ريال</b></div>
      <div class="flex justify-between text-sm"><span class="text-slate-500">حالة الدفع:</span><b>{{ $order->payment_status }}</b></div>
    </div>
  </div>
  @endif
</div>
<script>lucide.createIcons();</script>
</body>
</html>

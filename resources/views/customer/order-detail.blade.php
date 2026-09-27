<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>تفاصيل الطلب</title>
<script>(function(){const t=localStorage.getItem('theme')||'light';if(t==='dark')document.documentElement.classList.add('dark');})();</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { darkMode: 'class' };</script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/app.css">
</head>
<body class="bg-slate-50 min-h-screen pb-20">

<header class="bg-white shadow-sm sticky top-0 z-50 border-b border-slate-100">
  <div class="max-w-4xl mx-auto px-4 py-3 flex items-center gap-3">
    <a href="/account/orders" class="p-2 rounded-lg hover:bg-slate-100">
      <i data-lucide="arrow-right" class="w-5 h-5"></i>
    </a>
    <h1 class="font-black">📦 {{ $order->order_number }}</h1>
  </div>
</header>

<div class="max-w-4xl mx-auto px-4 py-6 space-y-4">

  <!-- Status Timeline -->
  <div class="ui-card p-5">
    <h2 class="font-black mb-4">حالة الطلب</h2>
    @php
      $steps = [
        ['icon'=>'check-circle', 'title'=>'تم الاستلام', 'done'=>true],
        ['icon'=>'settings', 'title'=>'قيد التحضير', 'done'=>in_array($order->status,['processing','shipped','delivered'])],
        ['icon'=>'truck', 'title'=>'قيد الشحن', 'done'=>in_array($order->status,['shipped','delivered'])],
        ['icon'=>'home', 'title'=>'تم التسليم', 'done'=>$order->status === 'delivered'],
      ];
    @endphp
    <div class="space-y-3">
      @foreach($steps as $step)
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full {{ $step['done'] ? 'bg-green-500' : 'bg-slate-200' }} flex items-center justify-center">
          <i data-lucide="{{ $step['icon'] }}" class="w-5 h-5 {{ $step['done'] ? 'text-white' : 'text-slate-400' }}"></i>
        </div>
        <div class="font-bold text-sm {{ $step['done'] ? 'text-slate-800' : 'text-slate-400' }}">{{ $step['title'] }}</div>
        @if($step['done'])<i data-lucide="check" class="w-4 h-4 text-green-600"></i>@endif
      </div>
      @endforeach
    </div>
  </div>

  <!-- Items -->
  <div class="ui-card">
    <div class="p-4 border-b border-slate-100">
      <h2 class="font-black">🛍️ المنتجات</h2>
    </div>
    <div class="divide-y divide-slate-100">
      @foreach($order->items as $item)
      <div class="p-4 flex items-center gap-3">
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-2xl">📦</div>
        <div class="flex-1">
          <div class="font-bold text-sm">{{ $item->product_name }}</div>
          <div class="text-xs text-slate-500">{{ $item->quantity }} × {{ number_format($item->unit_price) }} ريال</div>
        </div>
        <div class="font-black">{{ number_format($item->line_total) }}</div>
      </div>
      @endforeach
    </div>
    <div class="p-4 bg-slate-50 border-t">
      <div class="flex justify-between text-sm mb-1"><span class="text-slate-500">المجموع:</span><span class="font-bold">{{ number_format($order->subtotal) }}</span></div>
      <div class="flex justify-between text-sm mb-1"><span class="text-slate-500">الشحن:</span><span class="font-bold">{{ number_format($order->shipping) }}</span></div>
      <div class="flex justify-between text-lg pt-2 border-t mt-2"><span class="font-black">الإجمالي:</span><span class="font-black text-amber-600">{{ number_format($order->total) }} ريال</span></div>
    </div>
  </div>

  <!-- Info -->
  <div class="ui-card p-5">
    <h2 class="font-black mb-3">📍 معلومات التوصيل</h2>
    <div class="space-y-2 text-sm">
      <div class="flex justify-between"><span class="text-slate-500">الاسم:</span> <b>{{ $order->customer_name }}</b></div>
      <div class="flex justify-between"><span class="text-slate-500">الجوال:</span> <b class="font-mono">{{ $order->customer_phone }}</b></div>
      <div class="flex justify-between"><span class="text-slate-500">العنوان:</span> <b class="text-xs text-left max-w-xs">{{ $order->customer_address }}</b></div>
    </div>
  </div>

  <!-- Payment -->
  <div class="ui-card p-5">
    <h2 class="font-black mb-3">💳 الدفع</h2>
    <div class="space-y-2 text-sm">
      <div class="flex justify-between"><span class="text-slate-500">الطريقة:</span> <b>{{ \App\Support\StatusHelper::label($order->payment_method) }}</b></div>
      <div class="flex justify-between"><span class="text-slate-500">الحالة:</span>
        <span class="ui-badge {{ \App\Support\StatusHelper::badge($order->payment_status) }}">{{ \App\Support\StatusHelper::label($order->payment_status) }}</span>
      </div>
      @if($order->paid_at)
      <div class="flex justify-between"><span class="text-slate-500">مدفوع في:</span> <b>{{ $order->paid_at->format('Y-m-d H:i') }}</b></div>
      @endif
    </div>
  </div>

</div>

<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 flex justify-around z-30 py-2 shadow-lg">
  <a href="/account" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400">
    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">الرئيسية</span>
  </a>
  <a href="/account/orders" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-amber-600 bg-amber-50">
    <i data-lucide="package" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">طلباتي</span>
  </a>
  <a href="/account/wishlist" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400">
    <i data-lucide="heart" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">المفضلة</span>
  </a>
  <a href="/shop" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400">
    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">المتجر</span>
  </a>
  <a href="/account/profile" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg text-slate-400">
    <i data-lucide="user" class="w-5 h-5"></i>
    <span class="text-[10px] font-bold">حسابي</span>
  </a>
</nav>

<script>lucide.createIcons();</script>

  {{-- 🔔 التنبيهات --}}
  @include('components.toast')

</body>
</html>

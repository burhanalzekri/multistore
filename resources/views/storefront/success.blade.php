<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>تم الطلب بنجاح</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/app.css">
<style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-50 min-h-screen">

<div class="max-w-2xl mx-auto p-4 py-8">

  <!-- Success Animation -->
  <div class="text-center mb-8 animate-slideUp">
    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full shadow-2xl mb-4 animate-float">
      <i data-lucide="check" class="w-14 h-14 text-white" stroke-width="3"></i>
    </div>
    <h1 class="text-3xl font-black text-green-700 mb-2">تم استلام طلبك! 🎉</h1>
    <p class="text-slate-600">شكرًا لك — سيتواصل معك المتجر قريبًا</p>
  </div>

  <!-- Order Card -->
  <div class="bg-white rounded-3xl p-6 shadow-xl mb-4 animate-slideUp delay-100">
    <div class="text-center mb-6 pb-6 border-b border-dashed border-slate-200">
      <div class="text-xs text-slate-500 mb-1">رقم الطلب</div>
      <div class="text-3xl font-black font-mono text-slate-800">{{ $order->order_number }}</div>
    </div>

    <div class="space-y-3 text-sm">
      <div class="flex items-center justify-between">
        <span class="text-slate-500 flex items-center gap-2">
          <i data-lucide="user" class="w-4 h-4"></i> الاسم
        </span>
        <b>{{ $order->customer_name }}</b>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-slate-500 flex items-center gap-2">
          <i data-lucide="phone" class="w-4 h-4"></i> الجوال
        </span>
        <b class="font-mono">{{ $order->customer_phone }}</b>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-slate-500 flex items-center gap-2">
          <i data-lucide="wallet" class="w-4 h-4"></i> الإجمالي
        </span>
        <b class="text-amber-600 text-lg">{{ number_format($order->total) }} ريال</b>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-slate-500 flex items-center gap-2">
          <i data-lucide="info" class="w-4 h-4"></i> الحالة
        </span>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
          بانتظار الدفع
        </span>
      </div>
    </div>
  </div>

  @if($order->payment_method === 'wallet')
  <!-- Payment Info -->
  <div class="bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-200 rounded-3xl p-6 mb-4 animate-slideUp delay-200">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-12 h-12 bg-amber-500 rounded-2xl flex items-center justify-center shadow-lg">
        <i data-lucide="smartphone" class="w-6 h-6 text-white"></i>
      </div>
      <div>
        <div class="font-black text-amber-800">💰 معلومات الدفع</div>
        <div class="text-xs text-amber-700">حوّل المبلغ لتأكيد الطلب</div>
      </div>
    </div>

    <p class="text-sm text-slate-600 mb-4">حوّل المبلغ إلى:</p>

    <div class="bg-white rounded-2xl p-5 mb-4 text-center shadow-sm">
      <div class="text-xs text-slate-500 mb-1">محفظة بنك الكريمي</div>
      <div class="text-2xl font-black font-mono tracking-wider text-slate-800">777000000</div>
    </div>

    <div class="bg-white/50 rounded-xl p-3 text-xs text-slate-600 flex items-start gap-2">
      <i data-lucide="info" class="w-4 h-4 flex-shrink-0 mt-0.5 text-amber-600"></i>
      <span>بعد التحويل، سيتم تأكيد طلبك <b>تلقائيًا خلال دقائق</b> عبر رسالة SMS.</span>
    </div>
  </div>
  @endif

  <!-- Items -->
  <div class="bg-white rounded-3xl p-6 shadow-xl mb-4 animate-slideUp delay-300">
    <h2 class="font-black mb-4 flex items-center gap-2">
      <i data-lucide="package" class="w-5 h-5 text-slate-600"></i>
      المنتجات المطلوبة
    </h2>
    <div class="space-y-2 text-sm">
      @foreach($order->items as $item)
      <div class="flex justify-between py-2 border-b border-slate-100 last:border-0">
        <span>{{ $item->product_name }} <span class="text-slate-400">× {{ $item->quantity }}</span></span>
        <span class="font-bold">{{ number_format($item->line_total) }}</span>
      </div>
      @endforeach
    </div>
  </div>

  <a href="/shop" class="flex items-center justify-center gap-2 w-full py-4 bg-slate-900 text-white rounded-2xl font-black hover:bg-slate-800 transition btn-primary animate-slideUp delay-400">
    <i data-lucide="arrow-right" class="w-5 h-5"></i>
    العودة للمتجر
  </a>

</div>

<script>lucide.createIcons();</script>
</body>
</html>

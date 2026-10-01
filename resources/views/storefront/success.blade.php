<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>فاتورة الطلب {{ $order->order_number }}</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Cairo', sans-serif; }
  
  /* أزرار العمليات */
  .action-bar {
    position: fixed;
    top: 16px;
    left: 16px;
    z-index: 100;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }
  .action-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 11px 18px;
    border-radius: 12px;
    font-weight: 800;
    font-size: 13px;
    border: 0;
    cursor: pointer;
    font-family: inherit;
    transition: all .25s;
    box-shadow: 0 6px 18px rgba(15,23,42,.15);
    text-decoration: none;
  }
  .action-btn.print { background: linear-gradient(135deg, #f59e0b, #f97316); color: #fff; }
  .action-btn.print:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(245,158,11,.35); }
  .action-btn.screenshot { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: #fff; }
  .action-btn.screenshot:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(59,130,246,.35); }
  .action-btn.back { background: #fff; color: #374151; border: 1px solid #e5e7eb; }
  .action-btn.back:hover { background: #f9fafb; }

  /* الفاتورة */
  .invoice-wrapper {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(15,23,42,.12);
  }
  .invoice-header {
    background: linear-gradient(135deg, #f59e0b, #f97316);
    color: #fff;
    padding: 24px;
    text-align: center;
  }
  .invoice-logo {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: rgba(255,255,255,.25);
    backdrop-filter: blur(10px);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 900;
    margin-bottom: 12px;
  }

  /* الطباعة */
  @media print {
    .no-print { display: none !important; }
    body { background: #fff !important; margin: 0; padding: 0; }
    .print-wrapper { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
    .invoice-wrapper { box-shadow: none !important; border-radius: 0 !important; }
    .invoice-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .invoice-row { page-break-inside: avoid; }
    @page { margin: 12mm; size: A4 portrait; }
  }
</style>
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-50 min-h-screen">

<!-- ═══ أزرار العمليات ═══ -->
<div class="action-bar no-print">
  <button type="button" class="action-btn print" onclick="printInvoice()">
    🖨️ طباعة الفاتورة
  </button>
  <button type="button" class="action-btn screenshot" onclick="downloadScreenshot()" id="screenshotBtn">
    📸 حفظ كصورة
  </button>
  <a href="/shop" class="action-btn back">← العودة للمتجر</a>
</div>

<div class="max-w-3xl mx-auto p-4 py-8 pt-24 print-wrapper">

  <!-- رسالة النجاح -->
  <div class="text-center mb-6 no-print">
    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full shadow-2xl mb-3">
      <i data-lucide="check" class="w-12 h-12 text-white" stroke-width="3"></i>
    </div>
    <h1 class="text-2xl font-black text-green-700 mb-1">تم استلام طلبك بنجاح! 🎉</h1>
    <p class="text-slate-500 text-sm">شكرًا لك — سيتواصل معك المتجر قريبًا</p>
  </div>

  <!-- ═══ الفاتورة ═══ -->
  <div id="invoiceContent" class="invoice-wrapper">

    <!-- رأس الفاتورة -->
    <div class="invoice-header">
      <div class="invoice-logo">M</div>
      <div style="font-size:22px;font-weight:900;letter-spacing:-0.5px;">MultiStore</div>
      <div style="font-size:12px;opacity:.9;margin-top:4px;">فاتورة إلكترونية</div>
    </div>

    <!-- معلومات الفاتورة -->
    <div class="p-6 bg-slate-50 border-b border-slate-200">
      <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
          <div class="text-xs text-slate-500 mb-1">رقم الفاتورة</div>
          <div class="font-black font-mono text-slate-800">{{ $order->order_number }}</div>
        </div>
        <div>
          <div class="text-xs text-slate-500 mb-1">التاريخ</div>
          <div class="font-black text-slate-800">{{ $order->created_at->format('Y-m-d H:i') }}</div>
        </div>
      </div>
    </div>

    <!-- بيانات العميل -->
    <div class="p-6 border-b border-slate-200">
      <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2">
        <i data-lucide="user" class="w-5 h-5 text-amber-600"></i>
        بيانات العميل
      </h2>
      <div class="space-y-3 text-sm">
        <div class="flex items-center justify-between">
          <span class="text-slate-500">الاسم</span>
          <b class="text-slate-800">{{ $order->customer_name }}</b>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-slate-500">الجوال</span>
          <b class="font-mono text-slate-800" dir="ltr">{{ $order->customer_phone }}</b>
        </div>
        @if($order->customer_address)
        <div class="flex items-center justify-between">
          <span class="text-slate-500">العنوان</span>
          <b class="text-slate-800 text-left max-w-[60%]">{{ $order->customer_address }}</b>
        </div>
        @endif
        <div class="flex items-center justify-between">
          <span class="text-slate-500">حالة الطلب</span>
          <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
            {{ $order->status === 'awaiting_payment' ? 'بانتظار الدفع' : $order->status }}
          </span>
        </div>
      </div>
    </div>

    <!-- المنتجات -->
    <div class="p-6 border-b border-slate-200">
      <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2">
        <i data-lucide="package" class="w-5 h-5 text-amber-600"></i>
        المنتجات المطلوبة
      </h2>
      <div class="space-y-2">
        <div class="grid grid-cols-12 gap-2 pb-2 border-b-2 border-slate-200 text-xs font-black text-slate-500">
          <div class="col-span-6">المنتج</div>
          <div class="col-span-2 text-center">الكمية</div>
          <div class="col-span-2 text-center">السعر</div>
          <div class="col-span-2 text-left">الإجمالي</div>
        </div>
        @foreach($order->items as $item)
          <div class="grid grid-cols-12 gap-2 py-2.5 text-sm border-b border-slate-100 last:border-0 invoice-row">
            <div class="col-span-6 font-bold text-slate-800">{{ $item->product_name }}</div>
            <div class="col-span-2 text-center text-slate-600">× {{ $item->quantity }}</div>
            <div class="col-span-2 text-center text-slate-600">{{ number_format($item->unit_price) }}</div>
            <div class="col-span-2 text-left font-black text-slate-800">{{ number_format($item->line_total) }}</div>
          </div>
        @endforeach
      </div>
    </div>

    <!-- ملخص التكلفة -->
    <div class="p-6 bg-slate-50">
      <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2">
        <i data-lucide="receipt" class="w-5 h-5 text-amber-600"></i>
        ملخص التكلفة
      </h2>

      <div class="space-y-3 text-sm">
        <div class="flex justify-between items-center">
          <span class="text-slate-600">🛍️ المجموع الفرعي</span>
          <b class="text-slate-800">{{ number_format($order->subtotal) }} ر.ي</b>
        </div>

        @if($order->shipping > 0)
          <div class="flex justify-between items-center">
            <span class="text-slate-600">🚚 رسوم الشحن</span>
            <b class="text-blue-600">+{{ number_format($order->shipping) }} ر.ي</b>
          </div>
        @else
          <div class="flex justify-between items-center">
            <span class="text-slate-600">🚚 رسوم الشحن</span>
            <b class="text-green-600">🎁 مجاني</b>
          </div>
        @endif

        @if(isset($order->discount) && $order->discount > 0)
          <div class="flex justify-between items-center">
            <span class="text-slate-600">
              🎁 خصم
              @if($order->coupon_code)
                <span class="text-xs font-mono bg-amber-100 text-amber-700 px-2 py-0.5 rounded">{{ $order->coupon_code }}</span>
              @endif
            </span>
            <b class="text-red-600">-{{ number_format($order->discount) }} ر.ي</b>
          </div>
        @endif

        @if(isset($order->points_value) && $order->points_value > 0)
          <div class="flex justify-between items-center">
            <span class="text-slate-600">
              ⭐ نقاط الولاء
              @if($order->points_used)
                <span class="text-xs font-bold bg-purple-100 text-purple-700 px-2 py-0.5 rounded">{{ number_format($order->points_used) }} نقطة</span>
              @endif
            </span>
            <b class="text-red-600">-{{ number_format($order->points_value) }} ر.ي</b>
          </div>
        @endif

        <div class="border-t-2 border-dashed border-slate-300 my-3"></div>

        <div class="flex justify-between items-center py-2">
          <span class="font-black text-slate-800 flex items-center gap-2 text-base">
            <i data-lucide="wallet" class="w-5 h-5 text-amber-600"></i>
            الإجمالي النهائي
          </span>
          <b class="text-amber-600 text-2xl font-black">{{ number_format($order->total) }} ر.ي</b>
        </div>
      </div>
    </div>

    <!-- معلومات الدفع (إذا كانت محفظة) -->
    @if($order->payment_method === 'wallet')
      <div class="p-6 bg-gradient-to-br from-amber-50 to-orange-50 border-t-2 border-amber-200">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 bg-amber-500 rounded-2xl flex items-center justify-center shadow-lg">
            <i data-lucide="smartphone" class="w-6 h-6 text-white"></i>
          </div>
          <div>
            <div class="font-black text-amber-800">💰 معلومات الدفع</div>
            <div class="text-xs text-amber-700">حوّل المبلغ لتأكيد الطلب</div>
          </div>
        </div>
        <p class="text-sm text-slate-600 mb-3">حوّل المبلغ إلى:</p>
        <div class="bg-white rounded-2xl p-5 mb-3 text-center shadow-sm">
          <div class="text-xs text-slate-500 mb-1">محفظة بنك الكريمي</div>
          <div class="text-2xl font-black font-mono tracking-wider text-slate-800" dir="ltr">777000000</div>
        </div>
        <div class="bg-white/50 rounded-xl p-3 text-xs text-slate-600 flex items-start gap-2">
          <i data-lucide="info" class="w-4 h-4 flex-shrink-0 mt-0.5 text-amber-600"></i>
          <span>بعد التحويل، سيتم تأكيد طلبك <b>تلقائيًا خلال دقائق</b> عبر رسالة SMS.</span>
        </div>
      </div>
    @endif

    <!-- تذييل الفاتورة -->
    <div class="p-5 bg-slate-900 text-white text-center">
      <div class="text-xs opacity-75">شكراً لتعاملك معنا — MultiStore</div>
      <div class="text-[10px] opacity-60 mt-1">هذه فاتورة إلكترونية صادرة تلقائياً</div>
    </div>

  </div>
  {{-- نهاية الفاتورة --}}

</div>

<script>
if (window.lucide) lucide.createIcons();

// ═══ طباعة الفاتورة ═══
window.printInvoice = function() {
    window.print();
};

// ═══ لقطة شاشة (PNG) ═══
window.downloadScreenshot = function() {
    const btn = document.getElementById('screenshotBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ جاري التصدير...';
    btn.disabled = true;

    const element = document.getElementById('invoiceContent');

    // نستخدم html2canvas لتصدير الفاتورة كـ PNG
    html2canvas(element, {
        scale: 2,
        backgroundColor: '#ffffff',
        useCORS: true,
        logging: false,
        windowWidth: element.scrollWidth,
        windowHeight: element.scrollHeight
    }).then(function(canvas) {
        // تحويل إلى PNG
        const link = document.createElement('a');
        link.download = 'invoice-{{ $order->order_number }}.png';
        link.href = canvas.toDataURL('image/png', 1.0);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        btn.innerHTML = '✅ تم الحفظ';
        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 2000);
    }).catch(function(err) {
        console.error('Screenshot error:', err);
        alert('تعذر حفظ الصورة — حاول مرة أخرى');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
};
</script>

{{-- 🔔 التنبيهات --}}
@include('components.toast')
@include('components.floating-actions')
</body>
</html>

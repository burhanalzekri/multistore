<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>فاتورة {{ $order->order_number }}</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'Cairo', sans-serif;
    background: #f5f7fa;
    color: #1f2937;
    padding: 20px;
    min-height: 100vh;
  }
  
  .toolbar {
    max-width: 800px;
    margin: 0 auto 20px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }
  
  .btn {
    padding: 12px 20px;
    border: none;
    border-radius: 10px;
    font-family: inherit;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s;
  }
  
  .btn-primary { background: linear-gradient(135deg, #fbbf24, #f97316); color: white; }
  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(245,158,11,0.4); }
  .btn-secondary { background: white; color: #475569; border: 1px solid #e2e8f0; }
  .btn-secondary:hover { background: #f1f5f9; }
  
  .invoice {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
  }
  
  .header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 20px;
    border-bottom: 3px solid #f59e0b;
    margin-bottom: 25px;
  }
  .shop-info h1 { font-size: 26px; color: #f59e0b; margin-bottom: 6px; font-weight: 900; }
  .shop-info p { font-size: 12px; color: #6b7280; margin: 3px 0; }
  .invoice-info { text-align: left; }
  .invoice-info h2 { font-size: 32px; color: #f59e0b; margin-bottom: 6px; font-weight: 900; }
  .invoice-info p { font-size: 12px; color: #6b7280; margin: 3px 0; }
  .invoice-info .number { font-family: monospace; font-size: 15px; color: #1f2937; font-weight: 900; }
  
  .parties { display: flex; gap: 20px; margin-bottom: 25px; }
  .party { flex: 1; padding: 16px; background: #f8fafc; border-radius: 12px; }
  .party h3 { font-size: 13px; color: #f59e0b; margin-bottom: 10px; font-weight: 900; }
  .party p { font-size: 12px; margin: 5px 0; color: #4b5563; }
  .party strong { color: #1f2937; }
  
  table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
  thead { background: #fef3c7; }
  thead th { padding: 14px 10px; text-align: right; font-size: 12px; color: #92400e; font-weight: 900; }
  tbody td { padding: 14px 10px; border-bottom: 1px solid #f3f4f6; font-size: 13px; }
  tbody tr:last-child td { border-bottom: none; }
  .center { text-align: center; }
  .price { color: #f59e0b; font-weight: 900; }
  
  .totals { margin-top: 20px; margin-right: auto; width: 320px; }
  .totals-row { display: flex; justify-content: space-between; padding: 10px 0; font-size: 13px; border-bottom: 1px dashed #e5e7eb; }
  .totals-row.final { border-top: 2px solid #f59e0b; border-bottom: none; padding-top: 14px; font-size: 18px; font-weight: 900; color: #f59e0b; }
  
  .payment-info { background: #fef3c7; border: 2px solid #fcd34d; border-radius: 12px; padding: 18px; margin-top: 25px; }
  .payment-info h3 { font-size: 14px; color: #92400e; margin-bottom: 10px; font-weight: 900; }
  .payment-info p { font-size: 12px; margin: 5px 0; color: #78350f; }
  .payment-info .wallet { background: white; padding: 12px 16px; border-radius: 10px; text-align: center; font-family: monospace; font-size: 20px; font-weight: 900; margin: 10px 0; letter-spacing: 3px; color: #1f2937; }
  
  .status-badge { display: inline-block; padding: 5px 14px; border-radius: 999px; font-size: 11px; font-weight: 900; }
  .status-confirmed { background: #d1fae5; color: #065f46; }
  .status-pending { background: #fef3c7; color: #92400e; }
  .status-rejected { background: #fee2e2; color: #991b1b; }
  
  .footer { margin-top: 40px; padding-top: 20px; border-top: 2px dashed #e5e7eb; text-align: center; font-size: 11px; color: #9ca3af; }
  .footer .thanks { font-size: 15px; color: #f59e0b; font-weight: 900; margin-bottom: 10px; }
  
  /* ═══ للطباعة ═══ */
  @media print {
    body { background: white; padding: 0; }
    .toolbar { display: none !important; }
    .invoice { box-shadow: none; border-radius: 0; max-width: 100%; padding: 15px; }
    @page { margin: 1cm; size: A4; }
  }
</style>
</head>
<body>

<!-- شريط الأدوات (يختفي عند الطباعة) -->
<div class="toolbar">
  <button onclick="window.print()" class="btn btn-primary">
    🖨️ طباعة / حفظ PDF
  </button>
  <a href="/dashboard/orders/{{ $order->id }}" class="btn btn-secondary">
    ← العودة للطلب
  </a>
</div>

<!-- الفاتورة -->
<div class="invoice">

  <div class="header">
    <div class="shop-info">
      <h1>{{ $shop->name }}</h1>
      @if($shop->phone) <p>📞 {{ $shop->phone }}</p> @endif
      @if($shop->whatsapp) <p>💬 واتساب: {{ $shop->whatsapp }}</p> @endif
    </div>
    <div class="invoice-info">
      <h2>فاتورة</h2>
      <p>رقم الفاتورة</p>
      <p class="number">{{ $order->order_number }}</p>
      <p>{{ $order->created_at->format('Y-m-d H:i') }}</p>
    </div>
  </div>

  <div class="parties">
    <div class="party">
      <h3>👤 بيانات العميل</h3>
      <p><strong>الاسم:</strong> {{ $order->customer_name }}</p>
      <p><strong>الجوال:</strong> {{ $order->customer_phone }}</p>
      @if($order->customer_address)
      <p><strong>العنوان:</strong> {{ $order->customer_address }}</p>
      @endif
    </div>
    <div class="party">
      <h3>📋 حالة الطلب</h3>
      <p><strong>الحالة:</strong>
        <span class="status-badge {{ $order->payment_status === 'confirmed' ? 'status-confirmed' : ($order->payment_status === 'rejected' ? 'status-rejected' : 'status-pending') }}">
          {{ $order->payment_status === 'confirmed' ? 'مدفوع' : ($order->payment_status === 'rejected' ? 'مرفوض' : 'بانتظار الدفع') }}
        </span>
      </p>
      <p><strong>طريقة الدفع:</strong>
        @if($order->payment_method === 'cod') الدفع عند الاستلام
        @elseif($order->payment_method === 'wallet') محفظة إلكترونية
        @elseif($order->payment_method === 'bank') حوالة بنكية
        @else {{ $order->payment_method }} @endif
      </p>
      @if($order->paid_at)
      <p><strong>تاريخ الدفع:</strong> {{ $order->paid_at->format('Y-m-d H:i') }}</p>
      @endif
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width: 40px;">#</th>
        <th>المنتج</th>
        <th class="center" style="width: 70px;">الكمية</th>
        <th class="center" style="width: 100px;">السعر</th>
        <th class="center" style="width: 110px;">الإجمالي</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->items as $i => $item)
      <tr>
        <td class="center">{{ $i + 1 }}</td>
        <td>{{ $item->product_name }}</td>
        <td class="center">{{ $item->quantity }}</td>
        <td class="center">{{ number_format($item->unit_price) }}</td>
        <td class="center price">{{ number_format($item->line_total) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="totals">
    <div class="totals-row">
      <span>المجموع الفرعي:</span>
      <span>{{ number_format($order->subtotal) }} ريال</span>
    </div>
    <div class="totals-row">
      <span>الشحن:</span>
      <span>{{ $order->shipping > 0 ? number_format($order->shipping) . ' ريال' : 'مجاني' }}</span>
    </div>
    <div class="totals-row final">
      <span>الإجمالي:</span>
      <span>{{ number_format($order->total) }} ريال</span>
    </div>
  </div>

  @if($order->payment_method === 'wallet' && $order->payment_status !== 'confirmed' && $wallet)
  <div class="payment-info">
    <h3>💰 تعليمات الدفع</h3>
    <p>حوّل المبلغ إلى أحد الأرقام التالية:</p>
    <div class="wallet">{{ $wallet->wallet_number }}</div>
    <p>الاسم: {{ $wallet->holder_name }}</p>
    <p>بعد التحويل، سيُؤكَّد الطلب تلقائيًا خلال دقائق.</p>
  </div>
  @endif

  <div class="footer">
    <p class="thanks">شكرًا لتعاملك معنا! 🌟</p>
    <p>هذه الفاتورة صادرة إلكترونيًا — {{ $shop->name }} © {{ date('Y') }}</p>
  </div>

</div>

</body>
</html>

@component('mail::message')
# ✅ تم تأكيد طلبك!

مرحبًا **{{ $order->customer_name }}**،

تم تأكيد طلبك بنجاح من **{{ $shop->name }}**. شكرًا لك! 🎉

@component('mail::panel')
**رقم الطلب:** {{ $order->order_number }}
**الإجمالي:** {{ number_format($order->total) }} ريال
**حالة الدفع:** ✅ مدفوع
@endcomponent

**المنتجات المطلوبة:**

@foreach($order->items as $item)
- {{ $item->product_name }} × {{ $item->quantity }} — {{ number_format($item->line_total) }} ريال
@endforeach

@component('mail::button', ['url' => $url, 'color' => 'success'])
عرض تفاصيل الطلب
@endcomponent

سنتواصل معك قريبًا لتأكيد التوصيل.

شكرًا لك،  
**{{ $shop->name }}**

@if($shop->phone)
📞 {{ $shop->phone }}
@endif
@endcomponent

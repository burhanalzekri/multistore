@extends('layouts.app')

@section('title', 'تفاصيل الطلب')
@section('page-title', '🛒 ' . $order->order_number)

@section('content')

<a href="/dashboard/orders" class="text-sm text-slate-500">← الطلبات</a>

<div class="grid md:grid-cols-3 gap-4 mt-4">

  <div class="md:col-span-2 space-y-4">
    <!-- المنتجات -->
    <div class="bg-white rounded-2xl p-4 shadow-sm">
      <h2 class="font-bold mb-3">📦 المنتجات</h2>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="text-slate-500 border-b text-right">
            <tr><th class="py-2">المنتج</th><th>الكمية</th><th>السعر</th><th>الإجمالي</th></tr>
          </thead>
          <tbody>
            @forelse($order->items as $item)
            <tr class="border-b">
              <td class="py-2">{{ $item->product_name }}</td>
              <td>{{ $item->quantity }}</td>
              <td>{{ number_format($item->unit_price) }}</td>
              <td class="font-bold">{{ number_format($item->line_total) }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center py-4 text-slate-400">لا توجد منتجات مسجلة</td></tr>
            @endforelse
          </tbody>
          <tfoot class="border-t-2">
            <tr><td colspan="3" class="py-2 text-right text-slate-500">المجموع الفرعي:</td><td class="font-bold">{{ number_format($order->subtotal) }}</td></tr>
            <tr><td colspan="3" class="py-2 text-right text-slate-500">الشحن:</td><td class="font-bold">{{ number_format($order->shipping) }}</td></tr>
            <tr><td colspan="3" class="py-2 text-right text-amber-600 font-bold">الإجمالي:</td><td class="font-black text-amber-600">{{ number_format($order->total) }} ريال</td></tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- تغيير الحالة -->
    <div class="bg-white rounded-2xl p-4 shadow-sm">
      <h2 class="font-bold mb-3">⚙️ تغيير حالة الطلب</h2>
      <form method="POST" action="/dashboard/orders/{{ $order->id }}/status" class="flex gap-2 flex-wrap">
        @csrf
        <select name="status" class="flex-1 px-3 py-2 border rounded-lg text-sm">
          @foreach(['awaiting_payment'=>'بانتظار الدفع','processing'=>'قيد المعالجة','shipped'=>'تم الشحن','delivered'=>'تم التوصيل','cancelled'=>'ملغاة'] as $k=>$v)
          <option value="{{ $k }}" {{ $order->status===$k?'selected':'' }}>{{ $v }}</option>
          @endforeach
        </select>
        <button class="px-4 py-2 bg-amber-600 text-white rounded-lg font-bold text-sm">تحديث</button>
      </form>
    </div>
  </div>

  <!-- معلومات العميل والدفع -->
  <div class="space-y-4">
    <div class="bg-white rounded-2xl p-4 shadow-sm">
      <h2 class="font-bold mb-3">👤 العميل</h2>
      <div class="space-y-2 text-sm">
        <div><span class="text-slate-500">الاسم:</span> <b>{{ $order->customer_name }}</b></div>
        <div><span class="text-slate-500">الجوال:</span> <span class="font-mono">{{ $order->customer_phone }}</span></div>
        <div><span class="text-slate-500">العنوان:</span> {{ $order->customer_address ?? '—' }}</div>
        @if($order->notes)<div><span class="text-slate-500">ملاحظات:</span> {{ $order->notes }}</div>@endif
      </div>
    </div>

    <div class="bg-white rounded-2xl p-4 shadow-sm">
      <h2 class="font-bold mb-3">💰 الدفع</h2>
      <div class="space-y-2 text-sm">
        <div><span class="text-slate-500">الطريقة:</span> {{ \App\Support\StatusHelper::label($order->payment_method) }}</div>
        <div><span class="text-slate-500">الحالة:</span>
          <span class="px-2 py-1 rounded-full text-xs font-bold {{ \App\Support\StatusHelper::badge($order->payment_status) }}">
            {{ \App\Support\StatusHelper::label($order->payment_status) }}
          </span>
        </div>
        @if($order->payment_reference)
        <div><span class="text-slate-500">المرجع:</span> <span class="font-mono text-xs">{{ $order->payment_reference }}</span></div>
        @endif
        @if($order->paid_at)
        <div><span class="text-slate-500">مدفوع:</span> {{ $order->paid_at->format('Y-m-d H:i') }}</div>
        @endif
      </div>
    </div>
  </div>

</div>

@endsection

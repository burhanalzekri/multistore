@extends('layouts.app')

@section('title', 'الطلبات')
@section('page-title', '🛒 الطلبات')

@section('content')

<div class="flex flex-wrap gap-2 mb-4 text-sm">
  <a href="/dashboard/orders" class="px-3 py-1.5 rounded-lg font-bold {{ !request('status') ? 'bg-amber-600 text-white' : 'bg-white' }}">الكل</a>
  <a href="/dashboard/orders?status=awaiting_payment" class="px-3 py-1.5 rounded-lg font-bold {{ request('status')==='awaiting_payment' ? 'bg-amber-600 text-white' : 'bg-white' }}">⏳ بانتظار الدفع</a>
  <a href="/dashboard/orders?status=processing" class="px-3 py-1.5 rounded-lg font-bold {{ request('status')==='processing' ? 'bg-amber-600 text-white' : 'bg-white' }}">⚙️ قيد المعالجة</a>
  <a href="/dashboard/orders?status=shipped" class="px-3 py-1.5 rounded-lg font-bold {{ request('status')==='shipped' ? 'bg-amber-600 text-white' : 'bg-white' }}">📦 مشحون</a>
  <a href="/dashboard/orders?status=delivered" class="px-3 py-1.5 rounded-lg font-bold {{ request('status')==='delivered' ? 'bg-amber-600 text-white' : 'bg-white' }}">✅ موصّل</a>
</div>

<div class="bg-white rounded-2xl p-4 shadow-sm">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="text-slate-500 border-b text-right">
        <tr><th class="py-2">الرقم</th><th>العميل</th><th>الجوال</th><th>المبلغ</th><th>الدفع</th><th>الحالة</th><th>التاريخ</th></tr>
      </thead>
      <tbody>
        @forelse($orders as $o)
        <tr class="border-b hover:bg-slate-50 cursor-pointer" onclick="location='/dashboard/orders/{{ $o->id }}'">
          <td class="py-3 font-mono text-xs">{{ $o->order_number }}</td>
          <td class="font-bold">{{ $o->customer_name }}</td>
          <td class="font-mono text-xs">{{ $o->customer_phone }}</td>
          <td class="font-bold text-amber-600">{{ number_format($o->total) }}</td>
          <td>
            <span class="px-2 py-1 rounded-full text-xs font-bold {{ \App\Support\StatusHelper::badge($o->payment_status) }}">
              {{ \App\Support\StatusHelper::label($o->payment_status) }}
            </span>
          </td>
          <td>
            <span class="px-2 py-1 rounded-full text-xs font-bold {{ \App\Support\StatusHelper::badge($o->status) }}">
              {{ \App\Support\StatusHelper::label($o->status) }}
            </span>
          </td>
          <td class="text-slate-400 text-xs">{{ $o->created_at->diffForHumans() }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center py-12 text-slate-400">
            <div class="text-4xl mb-3">🛒</div>
            <div class="font-bold">لا توجد طلبات بعد</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($orders->hasPages())<div class="mt-4">{{ $orders->links() }}</div>@endif
</div>

@endsection

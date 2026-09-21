@extends('layouts.app')

@section('title', 'تفاصيل SMS')
@section('page-title', '📱 تفاصيل الرسالة')

@section('content')

<a href="/dashboard/sms" class="text-sm text-slate-500">← الرسائل</a>

<div class="grid md:grid-cols-2 gap-4 mt-4">

  <div class="space-y-4">
    <div class="bg-white rounded-2xl p-4 shadow-sm">
      <h2 class="font-bold mb-3">📄 الرسالة الأصلية</h2>
      <div class="bg-slate-50 rounded-lg p-4 font-mono text-sm leading-relaxed">{{ $sms->raw_body }}</div>
    </div>

    <div class="bg-white rounded-2xl p-4 shadow-sm">
      <h2 class="font-bold mb-3">🔍 البيانات المُحلَّلة</h2>
      <div class="space-y-2 text-sm">
        <div><span class="text-slate-500">المزود:</span> <b>{{ $sms->provider ?? '—' }}</b></div>
        <div><span class="text-slate-500">المبلغ:</span> <b class="text-amber-600">{{ $sms->parsed_amount ? number_format($sms->parsed_amount) . ' ريال' : '—' }}</b></div>
        <div><span class="text-slate-500">المرسل:</span> <span class="font-mono">{{ $sms->parsed_sender ?? '—' }}</span></div>
        <div><span class="text-slate-500">المرجع:</span> <span class="font-mono">{{ $sms->parsed_reference ?? '—' }}</span></div>
        <div><span class="text-slate-500">الثقة:</span> <b>{{ $sms->confidence }}%</b></div>
        <div><span class="text-slate-500">الحالة:</span>
          <span class="px-2 py-1 rounded-full text-xs font-bold {{ \App\Support\StatusHelper::badge($sms->status) }}">
            {{ \App\Support\StatusHelper::label($sms->status) }}
          </span>
        </div>
      </div>
    </div>
  </div>

  <div class="space-y-4">
    <div class="bg-white rounded-2xl p-4 shadow-sm">
      <h2 class="font-bold mb-3">🔗 ربط يدوي بطلب</h2>
      <p class="text-xs text-slate-500 mb-3">اختر الطلب الذي تريد تأكيده بهذه الرسالة</p>
      <form method="POST" action="/dashboard/sms/{{ $sms->id }}/confirm">
        @csrf
        <select name="order_id" required class="w-full px-3 py-2 border rounded-lg text-sm mb-3">
          <option value="">— اختر الطلب —</option>
          @foreach($orders as $o)
          <option value="{{ $o->id }}">
            {{ $o->order_number }} — {{ $o->customer_name }} — {{ number_format($o->total) }} ريال
          </option>
          @endforeach
        </select>
        <button class="w-full py-2 bg-green-600 text-white rounded-lg font-bold">✅ تأكيد الدفع</button>
      </form>

      <form method="POST" action="/dashboard/sms/{{ $sms->id }}/reject" class="mt-2">
        @csrf
        <button class="w-full py-2 bg-red-100 text-red-700 rounded-lg font-bold">❌ رفض الرسالة</button>
      </form>
    </div>

    @if($sms->matched_order_id)
    <div class="bg-green-50 border-2 border-green-200 rounded-2xl p-4">
      <h2 class="font-bold mb-2 text-green-700">✅ مرتبطة بطلب</h2>
      <a href="/dashboard/orders/{{ $sms->matched_order_id }}" class="text-sm text-green-700 font-bold">عرض الطلب #{{ $sms->matched_order_id }} ←</a>
    </div>
    @endif
  </div>

</div>

@endsection

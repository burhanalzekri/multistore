@extends('layouts.app')

@section('title', 'رسائل SMS')
@section('page-title', '📱 رسائل SMS')

@section('content')

<div class="flex flex-wrap gap-2 mb-4 text-sm">
  <a href="/dashboard/sms" class="px-3 py-1.5 rounded-lg font-bold {{ !request('status') ? 'bg-amber-600 text-white' : 'bg-white' }}">الكل</a>
  <a href="/dashboard/sms?status=matched" class="px-3 py-1.5 rounded-lg font-bold {{ request('status')==='matched' ? 'bg-amber-600 text-white' : 'bg-white' }}">✅ مُطابَقة</a>
  <a href="/dashboard/sms?status=review" class="px-3 py-1.5 rounded-lg font-bold {{ request('status')==='review' ? 'bg-amber-600 text-white' : 'bg-white' }}">⚠️ للمراجعة</a>
  <a href="/dashboard/sms?status=rejected" class="px-3 py-1.5 rounded-lg font-bold {{ request('status')==='rejected' ? 'bg-amber-600 text-white' : 'bg-white' }}">❌ مرفوضة</a>
</div>

<div class="bg-white rounded-2xl p-4 shadow-sm">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="text-slate-500 border-b text-right">
        <tr><th class="py-2">المرسل</th><th>المبلغ</th><th>المرجع</th><th>الثقة</th><th>الحالة</th><th>التاريخ</th></tr>
      </thead>
      <tbody>
        @forelse($smsList as $s)
        <tr class="border-b hover:bg-slate-50 cursor-pointer" onclick="location='/dashboard/sms/{{ $s->id }}'">
          <td class="py-3 font-mono text-xs">{{ $s->sender_phone }}</td>
          <td class="font-bold text-amber-600">{{ $s->parsed_amount ? number_format($s->parsed_amount) : '—' }}</td>
          <td class="font-mono text-xs">{{ $s->parsed_reference ?? '—' }}</td>
          <td>
            <span class="text-xs font-bold {{ $s->confidence >= 80 ? 'text-green-600' : ($s->confidence >= 50 ? 'text-amber-600' : 'text-red-600') }}">
              {{ $s->confidence }}%
            </span>
          </td>
          <td>
            <span class="px-2 py-1 rounded-full text-xs font-bold {{ \App\Support\StatusHelper::badge($s->status) }}">
              {{ \App\Support\StatusHelper::label($s->status) }}
            </span>
          </td>
          <td class="text-slate-400 text-xs">{{ $s->received_at?->diffForHumans() ?? '—' }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center py-12 text-slate-400">
            <div class="text-4xl mb-3">📱</div>
            <div class="font-bold">لا توجد رسائل بعد</div>
            <div class="text-xs mt-2">جرّب إرسال SMS عبر Webhook</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($smsList->hasPages())<div class="mt-4">{{ $smsList->links() }}</div>@endif
</div>

@endsection

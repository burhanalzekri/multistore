@extends('layouts.app')

@section('title', 'المدفوعات')
@section('page-title', '💰 المدفوعات')

@section('content')

<div class="bg-white rounded-2xl p-4 shadow-sm">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="text-slate-500 border-b text-right">
        <tr><th class="py-2">المزود</th><th>المبلغ</th><th>المرسل</th><th>المرجع</th><th>الطلب</th><th>التأكيد</th><th>التاريخ</th></tr>
      </thead>
      <tbody>
        @forelse($payments as $p)
        <tr class="border-b">
          <td class="py-3 font-bold">{{ $p->provider }}</td>
          <td class="font-black text-green-600">{{ number_format($p->amount) }}</td>
          <td class="font-mono text-xs">{{ $p->sender_phone }}</td>
          <td class="font-mono text-xs">{{ $p->reference_number ?? '—' }}</td>
          <td>
            @if($p->order_id)
            <a href="/dashboard/orders/{{ $p->order_id }}" class="text-amber-600 font-bold text-xs">#{{ $p->order_id }} ←</a>
            @else — @endif
          </td>
          <td>
            <span class="px-2 py-1 rounded-full text-xs font-bold {{ \App\Support\StatusHelper::badge($p->status) }}">
              {{ \App\Support\StatusHelper::label($p->status) }}
            </span>
            <span class="text-xs text-slate-400 mr-1">({{ \App\Support\StatusHelper::label($p->verified_by) }})</span>
          </td>
          <td class="text-slate-400 text-xs">{{ $p->verified_at?->diffForHumans() ?? $p->created_at->diffForHumans() }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center py-12 text-slate-400">
            <div class="text-4xl mb-3">💰</div>
            <div class="font-bold">لا توجد معاملات دفع بعد</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($payments->hasPages())<div class="mt-4">{{ $payments->links() }}</div>@endif
</div>

@endsection

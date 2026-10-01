@extends('layouts.storefront')

@section('title', 'سجل النقاط')

@section('content')
@php
$primary = $shop->primary_color ?? '#F59E0B';
@endphp

<div class="min-h-screen bg-slate-50 py-8" dir="rtl">
    <div class="mx-auto max-w-4xl px-4">    <div class="mb-6 overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100">
        <div
            class="p-6 text-white"
            style="background: linear-gradient(135deg, {{ $primary }}, #f97316);"
        >
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">سجل النقاط</h1>
                    <p class="mt-1 text-sm text-white/80">
                        تابع جميع عمليات كسب واستبدال نقاط الولاء.
                    </p>
                </div>

                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 text-2xl">
                    ⭐
                </div>
            </div>
        </div>
    </div>

    @if(empty($history) || count($history) === 0)

        <div class="rounded-3xl bg-white p-10 text-center shadow-sm ring-1 ring-slate-100">
            <div
                class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-amber-50 text-4xl"
            >
                ⭐
            </div>

            <h2 class="text-xl font-bold text-slate-800">
                لا توجد عمليات نقاط حتى الآن
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                ستظهر هنا جميع عمليات كسب واستبدال نقاط الولاء.
            </p>

            <a
                href="{{ url('/shop') }}"
                class="mt-6 inline-flex items-center justify-center rounded-xl px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90"
                style="background-color: {{ $primary }};"
            >
                العودة إلى المتجر
            </a>
        </div>

    @else

        <div class="space-y-3">
            @foreach($history as $item)

                @php
                    $type = data_get($item, 'type', data_get($item, 'transaction_type', ''));
                    $points = (int) data_get($item, 'points', 0);
                    $reason = data_get($item, 'description',
                        data_get($item, 'reason',
                        data_get($item, 'note', 'عملية نقاط')
                    ));

                    $createdAt = data_get($item, 'created_at');

                    $isEarn = in_array(
                        strtolower((string) $type),
                        ['earn', 'earned', 'credit', 'add', 'plus', 'كسب', 'إضافة']
                    ) || $points > 0;

                    $amount = abs($points);
                @endphp

                <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center justify-between gap-4">

                        <div class="flex min-w-0 items-center gap-3">
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl text-xl
                                {{ $isEarn ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}"
                            >
                                {{ $isEarn ? '↗' : '↘' }}
                            </div>

                            <div class="min-w-0">
                                <h3 class="truncate font-bold text-slate-800">
                                    {{ $reason ?: ($isEarn ? 'كسب نقاط' : 'استبدال نقاط') }}
                                </h3>

                                @if($createdAt)
                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ \Illuminate\Support\Carbon::parse($createdAt)->format('Y-m-d H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="shrink-0 text-left">
                            <div
                                class="text-lg font-black {{ $isEarn ? 'text-emerald-600' : 'text-red-600' }}"
                            >
                                {{ $isEarn ? '+' : '-' }}{{ number_format($amount) }}
                            </div>

                            <div class="text-xs text-slate-400">
                                نقطة
                            </div>
                        </div>

                    </div>
                </div>

            @endforeach
        </div>

        <div class="mt-6">
            <a
                href="{{ url('/shop') }}"
                class="flex w-full items-center justify-center rounded-2xl bg-white px-5 py-4 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-100 transition hover:bg-slate-50"
            >
                ← العودة إلى المتجر
            </a>
        </div>

    @endif

</div>

</div>
@endsection

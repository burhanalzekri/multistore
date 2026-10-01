@extends('layouts.super-admin')
@section('content')
<div class="max-w-5xl mx-auto p-4 md:p-8" dir="rtl"><div class="mb-6"><h1 class="text-2xl font-black">صحة النظام</h1><p class="text-sm text-slate-500 mt-1">فحص سريع للخدمات الأساسية دون عرض أي أسرار.</p></div><div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">@foreach($checks as $name=>$check)<div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100"><div class="flex items-center justify-between"><b>{{ $name }}</b><span class="text-xl">{{ $check['ok'] ? '🟢' : '🔴' }}</span></div><div class="mt-2 text-sm text-slate-500">{{ $check['detail'] }}</div></div>@endforeach</div></div>
@endsection

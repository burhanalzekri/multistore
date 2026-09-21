@extends('layouts.app')
@section('title', 'التصنيفات')
@section('page-title', '📂 التصنيفات')

@section('content')
<div class="flex justify-between items-center mb-4">
  <div class="text-sm text-slate-500"><b class="text-slate-700">{{ $categories->total() }}</b> تصنيف</div>
  <a href="/dashboard/categories/create" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-xl text-sm font-bold">
    <i data-lucide="plus" class="w-4 h-4"></i> تصنيف جديد
  </a>
</div>

@if($categories->isEmpty())
<div class="bg-white rounded-3xl p-16 text-center shadow-sm">
  <div class="text-7xl mb-4">📂</div>
  <h3 class="text-xl font-black mb-2">لا توجد تصنيفات</h3>
  <a href="/dashboard/categories/create" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-600 text-white rounded-xl font-bold mt-4">
    <i data-lucide="plus" class="w-5 h-5"></i> تصنيف أول
  </a>
</div>
@else
<div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
  @foreach($categories as $c)
  <div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition group">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center text-2xl">{{ $c->icon ?? '📂' }}</div>
      <div class="flex-1 min-w-0">
        <div class="font-black truncate">{{ $c->name }}</div>
        <div class="text-xs text-slate-400">{{ $c->products_count }} منتج</div>
      </div>
    </div>
    <div class="flex gap-2">
      <a href="/dashboard/categories/{{ $c->id }}/edit" class="flex-1 text-center py-2 bg-slate-100 rounded-lg text-xs font-bold">تعديل</a>
      <form method="POST" action="/dashboard/categories/{{ $c->id }}" onsubmit="return confirm('حذف التصنيف؟')" class="flex-1">
        @csrf @method('DELETE')
        <button class="w-full py-2 bg-red-50 text-red-700 rounded-lg text-xs font-bold">حذف</button>
      </form>
    </div>
  </div>
  @endforeach
</div>
@if($categories->hasPages())<div class="mt-6">{{ $categories->links() }}</div>@endif
@endif
@endsection

@extends('layouts.app')
@section('title', 'المنتجات')
@section('page-title', '📦 المنتجات')
@section('page-subtitle', $products->total() . ' منتج')

@section('content')

<div class="flex justify-between items-center mb-4">
  <div class="text-sm text-slate-500"><b class="text-slate-700">{{ $products->total() }}</b> منتج</div>
  <a href="/dashboard/products/create" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-xl text-sm font-bold btn-primary">
    <i data-lucide="plus" class="w-4 h-4"></i> منتج جديد
  </a>
</div>

@if($products->isEmpty())
<div class="bg-white rounded-3xl p-16 text-center shadow-sm">
  <div class="text-7xl mb-4">📦</div>
  <h3 class="text-xl font-black mb-2">لا توجد منتجات بعد</h3>
  <a href="/dashboard/products/create" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-600 text-white rounded-xl font-bold mt-4">
    <i data-lucide="plus" class="w-5 h-5"></i> أضف منتجك الأول
  </a>
</div>
@else
<div class="grid grid-cols-2 lg:grid-cols-3 gap-3 lg:gap-4">
  @foreach($products as $p)
  <div class="bg-white rounded-2xl overflow-hidden shadow-sm card-hover group">
    <div class="aspect-square bg-gradient-to-br from-amber-100 to-orange-50 overflow-hidden relative">
      @if($p->image)
        <img src="{{ Storage::url($p->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
      @else
        <div class="w-full h-full flex items-center justify-center text-5xl">📦</div>
      @endif

      @if($p->images && count($p->images) > 0)
      <div class="absolute top-2 left-2 bg-black/60 backdrop-blur text-white text-xs font-bold px-2 py-1 rounded-full">
        🖼️ {{ count($p->images) + 1 }}
      </div>
      @endif

      @if($p->stock == 0)
      <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
        <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">نفد</span>
      </div>
      @endif
    </div>

    <div class="p-4">
      <h3 class="font-black text-sm mb-2 line-clamp-2">{{ $p->name }}</h3>
      <div class="flex items-center justify-between mb-3">
        <div class="text-amber-600 font-black">{{ number_format($p->price) }}</div>
        <span class="text-xs px-2 py-0.5 rounded-full font-bold {{ $p->stock > 10 ? 'bg-green-100 text-green-700' : ($p->stock > 0 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
          {{ $p->stock }}
        </span>
      </div>
      <div class="flex gap-2">
        <a href="/dashboard/products/{{ $p->id }}/edit" class="flex-1 text-center py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-xs font-bold">
          <i data-lucide="edit-2" class="w-3 h-3 inline"></i> تعديل
        </a>
        <form method="POST" action="/dashboard/products/{{ $p->id }}" onsubmit="return confirm('حذف المنتج؟')" class="flex-1">
          @csrf @method('DELETE')
          <button class="w-full py-2 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-xs font-bold">
            <i data-lucide="trash-2" class="w-3 h-3 inline"></i> حذف
          </button>
        </form>
      </div>
    </div>
  </div>
  @endforeach
</div>

@if($products->hasPages())
<div class="mt-6">{{ $products->links() }}</div>
@endif
@endif

@endsection

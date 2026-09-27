@extends('dashboard.layouts.app')
@section('title', $product->name ?? 'تفاصيل المنتج')

@section('content')
<div style="max-width:900px;margin:0 auto;">
  <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.05);">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
      <h1 style="font-size:22px;font-weight:900;margin:0;">{{ $product->name }}</h1>
      <a href="{{ route('dashboard.products.edit', $product) }}" style="padding:10px 20px;background:#f59e0b;color:white;border-radius:10px;text-decoration:none;font-weight:900;">تعديل</a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
      <div>
        @if($product->image)
          <img src="{{ Storage::url($product->image) }}" style="width:100%;border-radius:12px;">
        @endif
      </div>
      <div>
        <div style="margin-bottom:12px;"><b>السعر:</b> {{ number_format($product->price) }} ريال</div>
        <div style="margin-bottom:12px;"><b>المخزون:</b> {{ $product->stock }}</div>
        <div style="margin-bottom:12px;"><b>التصنيف:</b> {{ $product->category->name ?? '—' }}</div>
        <div style="margin-bottom:12px;"><b>الحالة:</b> {{ $product->is_active ? 'نشط' : 'معطل' }}</div>
        <div style="margin-bottom:12px;"><b>الوصف:</b><br>{{ $product->description }}</div>
      </div>
    </div>

    <div style="margin-top:24px;padding-top:16px;border-top:1px solid #e5e7eb;">
      <a href="{{ route('dashboard.products.index') }}" style="padding:10px 20px;background:#f1f5f9;color:#475569;border-radius:10px;text-decoration:none;font-weight:900;">← رجوع</a>
    </div>
  </div>
</div>
@endsection

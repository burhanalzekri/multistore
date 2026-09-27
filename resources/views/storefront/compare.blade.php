@extends('storefront.layouts.app')
@section('title', 'مقارنة المنتجات')

@section('content')
<div style="max-width:1200px;margin:0 auto;padding:20px;">
  <h1 style="font-size:24px;font-weight:900;margin-bottom:20px;">⚖️ مقارنة المنتجات</h1>

  @if(!isset($products) || $products->count() < 2)
    <div style="text-align:center;padding:60px 20px;background:white;border-radius:20px;">
      <div style="font-size:64px;margin-bottom:16px;">🛒</div>
      <p style="font-size:16px;color:#6b7280;">أضف منتجين على الأقل للمقارنة</p>
      <a href="/shop" style="display:inline-block;margin-top:16px;padding:12px 24px;background:#f59e0b;color:white;border-radius:12px;text-decoration:none;font-weight:900;">تصفح المنتجات</a>
    </div>
  @else
    <div style="overflow-x:auto;">
      <table style="width:100%;background:white;border-radius:16px;overflow:hidden;border-collapse:collapse;box-shadow:0 2px 12px rgba(0,0,0,0.05);">
        <thead>
          <tr style="background:#f9fafb;">
            <th style="padding:16px;text-align:right;font-weight:900;border-bottom:2px solid #e5e7eb;">المواصفة</th>
            @foreach($products as $p)
              <th style="padding:16px;text-align:center;border-bottom:2px solid #e5e7eb;min-width:200px;">
                <a href="/product/{{ $p->id }}" style="text-decoration:none;color:inherit;">
                  @if($p->image)
                    <img src="{{ Storage::url($p->image) }}" style="width:100px;height:100px;object-fit:cover;border-radius:12px;margin-bottom:8px;">
                  @endif
                  <div style="font-weight:900;font-size:14px;">{{ $p->name }}</div>
                </a>
              </th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="padding:12px;font-weight:900;background:#f9fafb;">السعر</td>
            @foreach($products as $p)
              <td style="padding:12px;text-align:center;color:#f59e0b;font-weight:900;">{{ number_format($p->price) }} ريال</td>
            @endforeach
          </tr>
          <tr>
            <td style="padding:12px;font-weight:900;background:#f9fafb;">التصنيف</td>
            @foreach($products as $p)
              <td style="padding:12px;text-align:center;">{{ $p->category->name ?? '—' }}</td>
            @endforeach
          </tr>
          <tr>
            <td style="padding:12px;font-weight:900;background:#f9fafb;">المخزون</td>
            @foreach($products as $p)
              <td style="padding:12px;text-align:center;">
                @if($p->stock > 0)
                  <span style="color:#10b981;font-weight:900;">متوفر ({{ $p->stock }})</span>
                @else
                  <span style="color:#ef4444;font-weight:900;">نفد</span>
                @endif
              </td>
            @endforeach
          </tr>
          <tr>
            <td style="padding:12px;font-weight:900;background:#f9fafb;">إجراء</td>
            @foreach($products as $p)
              <td style="padding:12px;text-align:center;">
                <a href="/product/{{ $p->id }}" style="display:inline-block;padding:8px 16px;background:#f59e0b;color:white;border-radius:8px;text-decoration:none;font-weight:900;font-size:13px;">عرض</a>
              </td>
            @endforeach
          </tr>
        </tbody>
      </table>
    </div>
  @endif
</div>
@endsection

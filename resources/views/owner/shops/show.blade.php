@extends('layouts.app')
@section('title', 'تفاصيل المتجر')
@section('page-title', '🏪 {{ $shop->name }}')
@section('page-subtitle', 'تفاصيل المتجر والإحصائيات والعمليات الأخيرة')
@section('content')
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin-bottom:20px;">
@foreach([['📦','المنتجات',$stats['products']],['🛒','الطلبات',$stats['orders']],['💰','الإيرادات',number_format($stats['revenue']).' ريال'],['⏳','طلبات معلقة',$stats['pending']],['👥','المستخدمون',$stats['staff']]] as $stat)
<div style="background:var(--surface);border:1px solid var(--border);border-radius:18px;padding:18px;box-shadow:var(--shadow-sm);"><div style="font-size:22px;">{{ $stat[0] }}</div><div style="font-size:12px;color:var(--text-muted);margin-top:8px;">{{ $stat[1] }}</div><div style="font-size:22px;font-weight:900;margin-top:3px;">{{ $stat[2] }}</div></div>
@endforeach
</div>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:16px;">
<div style="background:var(--surface);border:1px solid var(--border);border-radius:18px;padding:20px;"><h3 style="font-weight:900;margin-bottom:14px;">بيانات المتجر</h3><div style="line-height:2;font-size:13px;color:var(--text-muted);">الاسم: <b style="color:var(--text);">{{ $shop->name }}</b><br>الرابط: <b style="color:var(--text);">{{ $shop->slug }}</b><br>الهاتف: {{ $shop->phone ?? '—' }}<br>البريد: {{ $shop->email ?? '—' }}<br>المدينة: {{ $shop->city ?? '—' }}<br>الحالة: {{ $shop->status }}</div><a href="{{ route('owner.shops.edit',$shop->id) }}" style="display:inline-block;margin-top:16px;padding:10px 16px;background:#f59e0b;color:#fff;border-radius:10px;text-decoration:none;font-weight:900;font-size:12px;">✏️ تعديل البيانات</a></div>
<div style="background:var(--surface);border:1px solid var(--border);border-radius:18px;padding:20px;"><h3 style="font-weight:900;margin-bottom:14px;">آخر الطلبات</h3>@forelse($recentOrders as $order)<div style="padding:10px 0;border-bottom:1px solid var(--border);font-size:12px;display:flex;justify-content:space-between;gap:8px;"><span>#{{ $order->id }}</span><b>{{ number_format($order->total ?? 0) }} ريال</b></div>@empty<p style="color:var(--text-muted);font-size:13px;">لا توجد طلبات.</p>@endforelse</div>
<div style="background:var(--surface);border:1px solid var(--border);border-radius:18px;padding:20px;"><h3 style="font-weight:900;margin-bottom:14px;">آخر المنتجات</h3>@forelse($recentProducts as $product)<div style="padding:10px 0;border-bottom:1px solid var(--border);font-size:12px;display:flex;justify-content:space-between;gap:8px;"><span>{{ $product->name }}</span><b>{{ number_format($product->price ?? 0) }}</b></div>@empty<p style="color:var(--text-muted);font-size:13px;">لا توجد منتجات.</p>@endforelse</div>
</div>
@endsection

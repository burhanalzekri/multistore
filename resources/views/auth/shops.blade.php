@extends('layouts.app')
@section('title', 'متاجري')
@section('page-title', '🏪 متاجر المنصة')
@section('page-subtitle', 'اختيار المتجر الذي تريد إدارته')
@section('content')
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;">
@forelse($shops as $shop)
<a href="{{ route('switch.shop', $shop->id) }}" style="display:block;text-decoration:none;color:inherit;background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:20px;box-shadow:var(--shadow-sm);transition:.2s;">
  <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
    <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#fbbf24,#f97316);display:grid;place-items:center;color:white;font-size:25px;overflow:hidden;">
      @if($shop->logo)<img src="{{ Storage::url($shop->logo) }}" alt="" style="width:100%;height:100%;object-fit:cover;">@else🏪@endif
    </div>
    <div style="min-width:0;flex:1;"><div style="font-weight:900;font-size:17px;">{{ $shop->name }}</div><div style="font-size:12px;color:var(--text-muted);">{{ $shop->city ?? 'اليمن' }}</div></div>
  </div>
  <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;">
    <span style="font-size:12px;color:var(--text-muted);">{{ $shop->slug }}</span>
    <span style="padding:8px 14px;border-radius:10px;background:#f59e0b;color:white;font-weight:900;font-size:12px;">دخول إلى المتجر ←</span>
  </div>
</a>
@empty
<div style="grid-column:1/-1;text-align:center;padding:60px 20px;background:var(--surface);border-radius:20px;border:1px solid var(--border);"><div style="font-size:56px;margin-bottom:12px;">🏪</div><h3 style="font-weight:900;margin-bottom:8px;">لا توجد متاجر</h3><p style="color:var(--text-muted);">لا توجد متاجر متاحة لهذا الحساب.</p></div>
@endforelse
</div>
@endsection

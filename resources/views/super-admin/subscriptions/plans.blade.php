@extends('super-admin.layouts.app')
@section('title', 'خطط الاشتراك')

@section('content')
<div style="padding:20px;">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <h1 style="font-size:24px;font-weight:900;margin:0;">💎 خطط الاشتراك</h1>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;">
    @forelse($plans ?? [] as $plan)
    <div style="background:white;border-radius:16px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.05);border:2px solid {{ $plan->is_featured ?? false ? '#f59e0b' : '#f1f5f9' }};">
      @if($plan->is_featured ?? false)
        <div style="background:#f59e0b;color:white;font-size:11px;font-weight:900;padding:4px 12px;border-radius:999px;display:inline-block;margin-bottom:8px;">⭐ مميزة</div>
      @endif
      <h3 style="font-size:20px;font-weight:900;margin:0 0 8px 0;">{{ $plan->name }}</h3>
      <div style="font-size:32px;font-weight:900;color:#f59e0b;margin-bottom:16px;">
        {{ number_format($plan->price) }} <span style="font-size:14px;color:#9ca3af;">ريال/شهر</span>
      </div>
      <ul style="list-style:none;padding:0;margin:0 0 16px 0;font-size:13px;color:#4b5563;">
        @if(!empty($plan->features))
          @foreach((array)$plan->features as $f)
            <li style="padding:6px 0;">✅ {{ $f }}</li>
          @endforeach
        @endif
      </ul>
    </div>
    @empty
    <div style="text-align:center;padding:60px 20px;background:white;border-radius:16px;grid-column:1/-1;">
      <div style="font-size:64px;margin-bottom:16px;">💎</div>
      <p style="color:#6b7280;">لا توجد خطط بعد</p>
    </div>
    @endforelse
  </div>
</div>
@endsection

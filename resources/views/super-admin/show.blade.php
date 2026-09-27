@extends('layouts.app')
@section('title', $shop->name)
@section('page-title', '🏪 ' . $shop->name)

@section('content')

<a href="/super-admin" style="display:inline-flex;align-items:center;gap:8px;color:var(--text-muted);text-decoration:none;font-weight:800;font-size:13px;margin-bottom:16px;">← العودة للمتاجر</a>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:20px;">
  <div class="admin-card" style="padding:18px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">📦 المنتجات</div>
    <div style="font-size:24px;font-weight:900;color:#8b5cf6;margin-top:4px;">{{ $stats['products'] }}</div>
  </div>
  <div class="admin-card" style="padding:18px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">🛒 الطلبات</div>
    <div style="font-size:24px;font-weight:900;color:#f59e0b;margin-top:4px;">{{ $stats['orders'] }}</div>
  </div>
  <div class="admin-card" style="padding:18px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">💰 المبيعات</div>
    <div style="font-size:20px;font-weight:900;color:#10b981;margin-top:4px;">{{ number_format($stats['sales']) }}</div>
  </div>
  <div class="admin-card" style="padding:18px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">📱 SMS</div>
    <div style="font-size:24px;font-weight:900;color:#ec4899;margin-top:4px;">{{ $stats['sms'] }}</div>
  </div>
</div>

<div class="admin-card">
  <div style="padding:18px 20px;border-bottom:1px solid var(--border);">
    <h2 style="font-size:15px;font-weight:900;">👥 المستخدمون ({{ $users->count() }})</h2>
  </div>
  @foreach($users as $u)
  <div style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border);">
    <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#fbbf24,#f97316);display:flex;align-items:center;justify-content:center;color:white;font-weight:900;">
      {{ mb_substr($u->name, 0, 1) }}
    </div>
    <div style="flex:1;">
      <div style="font-weight:900;font-size:13px;color:var(--text);">{{ $u->name }}</div>
      <div style="font-size:11px;color:var(--text-muted);">{{ $u->email }}</div>
    </div>
    <span class="ui-badge" style="background:#f1f5f9;color:#475569;font-size:11px;font-weight:900;padding:4px 12px;border-radius:999px;">{{ $u->role }}</span>
  </div>
  @endforeach
</div>

@endsection

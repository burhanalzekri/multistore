@extends('layouts.app')
@section('title', 'سجل النشاطات')
@section('page-title', '📝 سجل النشاطات')
@section('page-subtitle', $logs->total() . ' نشاط')

@section('content')

<div class="admin-card">
  @forelse($logs as $log)
  <div style="display:flex;align-items:start;gap:14px;padding:14px 20px;border-bottom:1px solid var(--border);">
    <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#60a5fa,#3b82f6);display:flex;align-items:center;justify-content:center;color:white;font-weight:900;font-size:14px;flex-shrink:0;">
      {{ mb_substr($log->user_name ?? 'N', 0, 1) }}
    </div>
    <div style="flex:1;min-width:0;">
      <div style="font-weight:800;font-size:13px;color:var(--text);">
        <span style="color:#3b82f6;">{{ $log->user_name ?? 'النظام' }}</span>
        — {{ $log->description ?? $log->action }}
      </div>
      <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">
        <code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:10px;">{{ $log->action }}</code>
        @if($log->ip_address)
        • {{ $log->ip_address }}
        @endif
      </div>
    </div>
    <div style="font-size:11px;color:var(--text-muted);white-space:nowrap;">
      {{ $log->created_at->diffForHumans() }}
    </div>
  </div>
  @empty
  <div style="padding:60px 20px;text-align:center;">
    <div style="font-size:56px;margin-bottom:12px;opacity:0.5;">📝</div>
    <div style="font-size:15px;font-weight:900;">لا يوجد نشاطات</div>
  </div>
  @endforelse
</div>

@if($logs->hasPages())
<div style="margin-top:16px;">{{ $logs->links() }}</div>
@endif

@endsection

@extends('layouts.app')
@section('title', 'رسائل التواصل')
@section('page-title', '📥 رسائل التواصل')
@section('page-subtitle', $messages->total() . ' رسالة')

@section('content')

{{-- إحصائيات --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:20px;">
  <div class="admin-card" style="padding:16px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">📬 الإجمالي</div>
    <div style="font-size:24px;font-weight:900;color:var(--primary);">{{ $stats['total'] }}</div>
  </div>
  <div class="admin-card" style="padding:16px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">🔴 غير مقروءة</div>
    <div style="font-size:24px;font-weight:900;color:#dc2626;">{{ $stats['unread'] }}</div>
  </div>
  <div class="admin-card" style="padding:16px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">📅 اليوم</div>
    <div style="font-size:24px;font-weight:900;color:#3b82f6;">{{ $stats['today'] }}</div>
  </div>
</div>

{{-- فلاتر --}}
<div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
  <a href="/dashboard/contact-messages"
     style="padding:8px 16px;border-radius:10px;{{ !request('filter') ? 'background:linear-gradient(135deg,#fbbf24,#f97316);color:#fff;' : 'background:#f1f5f9;color:#475569;' }}text-decoration:none;font-weight:800;font-size:12px;">
    الكل
  </a>
  <a href="/dashboard/contact-messages?filter=unread"
     style="padding:8px 16px;border-radius:10px;{{ request('filter') === 'unread' ? 'background:linear-gradient(135deg,#fbbf24,#f97316);color:#fff;' : 'background:#f1f5f9;color:#475569;' }}text-decoration:none;font-weight:800;font-size:12px;">
    🔴 غير مقروءة
  </a>
</div>

{{-- القائمة --}}
<div class="admin-card">
  @forelse($messages as $msg)
    <div onclick="window.location='/dashboard/contact-messages/{{ $msg->id }}'"
         style="padding:16px 20px;border-bottom:1px solid var(--border);cursor:pointer;transition:background .2s;{{ !$msg->is_read ? 'background:#eff6ff;' : '' }}"
         onmouseover="this.style.background='#f8fafc'"
         onmouseout="this.style.background='{{ !$msg->is_read ? '#eff6ff' : '' }}'">

      <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <div style="width:44px;height:44px;border-radius:12px;background:{{ !$msg->is_read ? 'linear-gradient(135deg,#3b82f6,#1d4ed8)' : 'linear-gradient(135deg,#94a3b8,#64748b)' }};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:17px;flex-shrink:0;">
          {{ mb_substr($msg->name, 0, 1) }}
        </div>

        <div style="flex:1;min-width:200px;">
          <div style="font-weight:900;font-size:14px;color:var(--text);">
            {{ $msg->name }}
            @if(!$msg->is_read)
              <span style="background:#dc2626;color:#fff;padding:2px 8px;border-radius:99px;font-size:10px;margin-inline-start:6px;">جديد</span>
            @endif
          </div>
          <div style="font-size:12px;color:var(--text-muted);margin-top:3px;">{{ $msg->email ?? $msg->phone ?? '—' }}</div>
        </div>

        <div style="flex:1;min-width:180px;">
          <div style="font-size:13px;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:300px;">
            {{ Str::limit($msg->message, 70) }}
          </div>
        </div>

        <div style="font-size:11px;color:var(--text-muted);white-space:nowrap;">
          {{ $msg->created_at->diffForHumans() }}
        </div>
      </div>
    </div>
  @empty
    <div style="padding:60px 20px;text-align:center;">
      <div style="font-size:56px;margin-bottom:12px;">📭</div>
      <div style="font-size:15px;font-weight:900;">لا توجد رسائل</div>
      <p style="color:var(--text-muted);font-size:13px;margin-top:8px;">ستظهر هنا رسائل العملاء من صفحة "تواصل معنا"</p>
    </div>
  @endforelse
</div>

@if($messages->hasPages())
  <div style="margin-top:16px;">{{ $messages->links() }}</div>
@endif

@endsection

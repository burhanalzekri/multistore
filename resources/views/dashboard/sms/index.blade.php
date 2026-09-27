@extends('layouts.app')
@section('title', 'رسائل SMS')
@section('page-title', 'رسائل SMS')
@section('page-subtitle', $smsList->total() . ' رسالة')

@section('content')

<!-- Filters -->
<div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:12px;margin-bottom:20px;" class="no-scrollbar">
  <a href="/dashboard/sms" class="shine-btn" style="{{ !request('status') ? 'background:linear-gradient(135deg,#fbbf24,#f97316);color:white;box-shadow:0 4px 12px rgba(245,158,11,0.3);' : 'background:white;border:1px solid var(--border);color:var(--text-muted);' }}">
    <i data-lucide="list"></i> الكل
  </a>
  <a href="/dashboard/sms?status=matched" class="shine-btn" style="{{ request('status')==='matched' ? 'background:linear-gradient(135deg,#fbbf24,#f97316);color:white;box-shadow:0 4px 12px rgba(245,158,11,0.3);' : 'background:white;border:1px solid var(--border);color:var(--text-muted);' }}">
    ✅ مُطابَقة
  </a>
  <a href="/dashboard/sms?status=review" class="shine-btn" style="{{ request('status')==='review' ? 'background:linear-gradient(135deg,#fbbf24,#f97316);color:white;box-shadow:0 4px 12px rgba(245,158,11,0.3);' : 'background:white;border:1px solid var(--border);color:var(--text-muted);' }}">
    ⚠️ للمراجعة
  </a>
  <a href="/dashboard/sms?status=rejected" class="shine-btn" style="{{ request('status')==='rejected' ? 'background:linear-gradient(135deg,#fbbf24,#f97316);color:white;box-shadow:0 4px 12px rgba(245,158,11,0.3);' : 'background:white;border:1px solid var(--border);color:var(--text-muted);' }}">
    ❌ مرفوضة
  </a>
</div>

<div class="shine-card">
  @forelse($smsList as $s)
  <a href="/dashboard/sms/{{ $s->id }}" class="shine-list-item">
    <div class="shine-item-avatar" style="background:{{ $s->status === 'matched' ? '#dcfce7' : ($s->status === 'rejected' ? '#fee2e2' : '#fef3c7') }};color:{{ $s->status === 'matched' ? '#15803d' : ($s->status === 'rejected' ? '#b91c1c' : '#b45309') }};">
      <i data-lucide="smartphone"></i>
    </div>
    <div class="shine-item-content">
      <h4 style="font-family:monospace;">{{ $s->sender_phone }}</h4>
      <p>{{ \Illuminate\Support\Str::limit($s->raw_body, 60) }}</p>
      <p style="margin-top:3px;">
        المرجع: <strong style="font-family:monospace;">{{ $s->parsed_reference ?? '—' }}</strong>
      </p>
    </div>
    <div class="shine-item-meta">
      <div class="shine-item-amount">{{ $s->parsed_amount ? number_format($s->parsed_amount) : '—' }}</div>
      <span class="shine-badge-status {{ $s->status === 'matched' ? 'success' : ($s->status === 'rejected' ? 'danger' : 'warning') }}">
        {{ \App\Support\StatusHelper::label($s->status) }}
      </span>
      <span class="shine-item-time">{{ $s->received_at?->diffForHumans() ?? '—' }}</span>
    </div>
  </a>
  @empty
  <div class="shine-empty">
    <div class="shine-empty-icon">📱</div>
    <h3>لا توجد رسائل بعد</h3>
    <p>الرسائل الواردة ستظهر هنا تلقائيًا</p>
  </div>
  @endforelse
</div>

@if($smsList->hasPages())
<div style="margin-top:20px;">{{ $smsList->links() }}</div>
@endif

@endsection

@extends('layouts.app')
@section('title', 'المدفوعات')
@section('page-title', 'المدفوعات')
@section('page-subtitle', $payments->total() . ' معاملة')

@section('content')

<div class="shine-card">
  @forelse($payments as $p)
  <div class="shine-list-item">
    <div class="shine-item-avatar" style="background:#dcfce7;color:#15803d;">
      <i data-lucide="wallet"></i>
    </div>
    <div class="shine-item-content">
      <h4>{{ $p->provider }}</h4>
      <p style="font-family:monospace;">{{ $p->sender_phone }}</p>
      <p style="margin-top:3px;">المرجع: <strong style="font-family:monospace;">{{ $p->reference_number ?? '—' }}</strong></p>
    </div>
    <div class="shine-item-meta">
      <div class="shine-item-amount" style="color:#15803d;">{{ number_format($p->amount) }}</div>
      <span class="shine-badge-status {{ $p->status === 'confirmed' ? 'success' : ($p->status === 'rejected' ? 'danger' : 'warning') }}">
        {{ \App\Support\StatusHelper::label($p->status) }}
      </span>
      <span class="shine-item-time">{{ $p->verified_at?->diffForHumans() ?? $p->created_at->diffForHumans() }}</span>
    </div>
  </div>
  @empty
  <div class="shine-empty">
    <div class="shine-empty-icon">💰</div>
    <h3>لا توجد معاملات</h3>
    <p>ستظهر هنا تلقائيًا عند تأكيد الطلبات</p>
  </div>
  @endforelse
</div>

@if($payments->hasPages())
<div style="margin-top:20px;">{{ $payments->links() }}</div>
@endif

@endsection

@extends('layouts.app')
@section('title', 'الطلبات')
@section('page-title', 'الطلبات')
@section('page-subtitle', $orders->total() . ' طلب')

@section('content')

<!-- Filter Chips -->
<div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:12px;margin-bottom:20px;" class="no-scrollbar">
  <a href="/dashboard/orders" class="{{ !request('status') ? 'shine-btn shine-btn-primary' : 'shine-btn' }}" style="{{ !request('status') ? '' : 'background:white;border:1px solid var(--border);color:var(--text-muted);' }}">
    <i data-lucide="list"></i> الكل ({{ $orders->total() }})
  </a>
  <a href="/dashboard/orders?status=awaiting_payment" class="{{ request('status')==='awaiting_payment' ? 'shine-btn shine-btn-primary' : 'shine-btn' }}" style="{{ request('status')!=='awaiting_payment' ? 'background:white;border:1px solid var(--border);color:var(--text-muted);' : '' }}">
    ⏳ بانتظار الدفع
  </a>
  <a href="/dashboard/orders?status=processing" class="{{ request('status')==='processing' ? 'shine-btn shine-btn-primary' : 'shine-btn' }}" style="{{ request('status')!=='processing' ? 'background:white;border:1px solid var(--border);color:var(--text-muted);' : '' }}">
    ⚙️ قيد المعالجة
  </a>
  <a href="/dashboard/orders?status=shipped" class="{{ request('status')==='shipped' ? 'shine-btn shine-btn-primary' : 'shine-btn' }}" style="{{ request('status')!=='shipped' ? 'background:white;border:1px solid var(--border);color:var(--text-muted);' : '' }}">
    📦 مشحون
  </a>
  <a href="/dashboard/orders?status=delivered" class="{{ request('status')==='delivered' ? 'shine-btn shine-btn-primary' : 'shine-btn' }}" style="{{ request('status')!=='delivered' ? 'background:white;border:1px solid var(--border);color:var(--text-muted);' : '' }}">
    ✅ موصّل
  </a>
</div>

<div class="shine-card">
  @forelse($orders as $o)
  <a href="/dashboard/orders/{{ $o->id }}" class="shine-list-item">
    <div class="shine-item-avatar">
      <i data-lucide="package"></i>
    </div>
    <div class="shine-item-content">
      <h4>{{ $o->customer_name }}</h4>
      <p style="font-family:monospace;font-size:11px;">{{ $o->order_number }}</p>
      <p style="margin-top:2px;">📞 {{ $o->customer_phone }}</p>
    </div>
    <div class="shine-item-meta">
      <div class="shine-item-amount">{{ number_format($o->total) }}</div>
      <span class="shine-badge-status {{ 
        $o->status === 'delivered' ? 'success' :
        ($o->status === 'cancelled' ? 'danger' :
        ($o->status === 'awaiting_payment' ? 'warning' :
        ($o->status === 'shipped' ? 'info' : 'neutral'))) 
      }}">
        {{ \App\Support\StatusHelper::label($o->status) }}
      </span>
      <span class="shine-item-time">{{ $o->created_at->diffForHumans() }}</span>
    </div>
  </a>
  @empty
  <div class="shine-empty">
    <div class="shine-empty-icon">🛒</div>
    <h3>لا توجد طلبات بعد</h3>
    <p>ستظهر هنا عندما يُنشئ العملاء طلبات</p>
  </div>
  @endforelse
</div>

@if($orders->hasPages())
<div style="margin-top:20px;">{{ $orders->links() }}</div>
@endif

@endsection

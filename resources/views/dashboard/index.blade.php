@extends('layouts.app')
@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')

<!-- Welcome -->
<div class="shine-welcome">
  <div class="shine-welcome-content">
    <h2>مرحبًا بك، {{ auth()->user()->name ?? 'مدير المتجر' }} 👋</h2>
    <p>
      لديك <strong>{{ $stats['orders'] }}</strong> طلب و <strong>{{ $stats['sms'] }}</strong> رسالة SMS
      @if($stats['pending'] > 0) — <strong>{{ $stats['pending'] }}</strong> بانتظار الدفع @endif
    </p>
  </div>
</div>

<!-- Stats Grid -->
<div class="shine-stats">
  <div class="shine-stat s-green">
    <div class="shine-stat-top">
      <div class="shine-stat-icon"><i data-lucide="trending-up"></i></div>
      <span class="shine-stat-trend up">+12%</span>
    </div>
    <div class="shine-stat-label">المبيعات</div>
    <div class="shine-stat-value">{{ number_format($stats['sales']) }} <small>ريال</small></div>
  </div>

  <div class="shine-stat s-blue">
    <div class="shine-stat-top">
      <div class="shine-stat-icon"><i data-lucide="shopping-bag"></i></div>
      <span class="shine-stat-trend up">+8%</span>
    </div>
    <div class="shine-stat-label">الطلبات</div>
    <div class="shine-stat-value">{{ $stats['orders'] }}</div>
  </div>

  <div class="shine-stat s-amber">
    <div class="shine-stat-top">
      <div class="shine-stat-icon"><i data-lucide="clock"></i></div>
    </div>
    <div class="shine-stat-label">بانتظار الدفع</div>
    <div class="shine-stat-value">{{ $stats['pending'] }}</div>
  </div>

  <div class="shine-stat s-purple">
    <div class="shine-stat-top">
      <div class="shine-stat-icon"><i data-lucide="message-square"></i></div>
    </div>
    <div class="shine-stat-label">رسائل SMS</div>
    <div class="shine-stat-value">{{ $stats['sms'] }}</div>
  </div>
</div>

<!-- Quick Actions -->
<div class="shine-actions">
  <a href="/dashboard/products/create" class="shine-action">
    <div class="shine-action-icon" style="background:linear-gradient(135deg,#60a5fa,#3b82f6);">
      <i data-lucide="plus"></i>
    </div>
    <h4>منتج جديد</h4>
    <p>أضف منتجًا للمتجر</p>
  </a>

  <a href="/dashboard/orders" class="shine-action">
    <div class="shine-action-icon" style="background:linear-gradient(135deg,#fbbf24,#f59e0b);">
      <i data-lucide="list"></i>
    </div>
    <h4>الطلبات</h4>
    <p>تابع طلباتك</p>
  </a>

  <a href="/dashboard/categories" class="shine-action">
    <div class="shine-action-icon" style="background:linear-gradient(135deg,#a78bfa,#8b5cf6);">
      <i data-lucide="folder"></i>
    </div>
    <h4>التصنيفات</h4>
    <p>نظّم منتجاتك</p>
  </a>

  <a href="/shop" target="_blank" class="shine-action">
    <div class="shine-action-icon" style="background:linear-gradient(135deg,#34d399,#10b981);">
      <i data-lucide="store"></i>
    </div>
    <h4>المتجر</h4>
    <p>شاهد متجرك</p>
  </a>
</div>

<!-- Recent Orders + SMS -->
<div class="shine-grid-2">
  <div class="shine-card">
    <div class="shine-card-header">
      <div class="shine-card-title">
        <i data-lucide="clock"></i>
        آخر الطلبات
      </div>
      <a href="/dashboard/orders" class="shine-card-link">عرض الكل ←</a>
    </div>
    @forelse($recentOrders as $o)
    <a href="/dashboard/orders/{{ $o->id }}" class="shine-list-item">
      <div class="shine-item-avatar"><i data-lucide="package"></i></div>
      <div class="shine-item-content">
        <h4>{{ $o->customer_name }}</h4>
        <p style="font-family:monospace;">{{ $o->order_number }}</p>
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
      </div>
    </a>
    @empty
    <div class="shine-empty" style="padding:30px 20px;">
      <div class="shine-empty-icon" style="font-size:40px;">🛒</div>
      <h3 style="font-size:14px;">لا توجد طلبات بعد</h3>
    </div>
    @endforelse
  </div>

  <div class="shine-card">
    <div class="shine-card-header">
      <div class="shine-card-title">
        <i data-lucide="message-circle"></i>
        آخر رسائل SMS
      </div>
      <a href="/dashboard/sms" class="shine-card-link">عرض الكل ←</a>
    </div>
    @forelse($smsList as $s)
    <a href="/dashboard/sms/{{ $s->id }}" class="shine-list-item">
      <div class="shine-item-avatar" style="background:#ede9fe;color:#7c3aed;">
        <i data-lucide="smartphone"></i>
      </div>
      <div class="shine-item-content">
        <h4 style="font-family:monospace;">{{ $s->sender_phone }}</h4>
        <p>{{ \Illuminate\Support\Str::limit($s->raw_body, 40) }}</p>
      </div>
      <div class="shine-item-meta">
        <div class="shine-item-amount" style="color:#7c3aed;">{{ $s->parsed_amount ? number_format($s->parsed_amount) : '—' }}</div>
        <span class="shine-badge-status {{ $s->status === 'matched' ? 'success' : ($s->status === 'rejected' ? 'danger' : 'warning') }}">
          {{ \App\Support\StatusHelper::label($s->status) }}
        </span>
      </div>
    </a>
    @empty
    <div class="shine-empty" style="padding:30px 20px;">
      <div class="shine-empty-icon" style="font-size:40px;">📱</div>
      <h3 style="font-size:14px;">لا توجد رسائل بعد</h3>
    </div>
    @endforelse
  </div>
</div>

@endsection

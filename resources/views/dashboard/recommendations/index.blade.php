@extends('layouts.app')
@section('title', 'الذكاء')
@section('page-title', '🧠 الذكاء والإحصائيات')
@section('page-subtitle', 'سلوك العملاء في متجرك')

@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:12px;margin-bottom:20px;">
  @php
    $cards = [
      ['👁️ المشاهدات', $stats['views'], '#f59e0b', '#fef3c7'],
      ['🛒 إضافات للسلة', $stats['cart_adds'], '#3b82f6', '#dbeafe'],
      ['💰 المشتريات', $stats['purchases'], '#10b981', '#dcfce7'],
      ['🔍 البحث', $stats['searches'], '#8b5cf6', '#ede9fe'],
      ['❤️ المفضلة', $stats['wishlists'], '#ec4899', '#fce7f3'],
      ['👥 الملفات', $stats['profiles'], '#06b6d4', '#cffafe'],
    ];
  @endphp
  @foreach($cards as [$label, $value, $color, $bg])
  <div class="admin-card" style="padding:16px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
      <div style="width:36px;height:36px;border-radius:10px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">{{ explode(' ', $label)[0] }}</div>
      <div style="font-size:11px;color:var(--text-muted);font-weight:800;">{{ implode(' ', array_slice(explode(' ', $label), 1)) }}</div>
    </div>
    <div style="font-size:24px;font-weight:900;color:{{ $color }};">{{ number_format($value) }}</div>
  </div>
  @endforeach
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
  <div class="admin-card">
    <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;">
      <span style="font-size:18px;">🔥</span>
      <h2 style="font-size:15px;font-weight:900;color:var(--text);margin:0;">الأكثر مشاهدة</h2>
    </div>
    @forelse($topViewed as $i => $item)
    <a href="{{ $item['url'] }}" class="admin-list-item">
      <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#fbbf24,#f97316);display:flex;align-items:center;justify-content:center;color:white;font-weight:900;font-size:13px;flex-shrink:0;">{{ $i + 1 }}</div>
      <div style="flex:1;min-width:0;">
        <div style="font-weight:800;font-size:13px;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item['name'] }}</div>
        <div style="font-size:11px;color:var(--text-muted);">{{ number_format($item['price']) }} ريال</div>
      </div>
      <div style="font-weight:900;color:#f59e0b;font-size:15px;">{{ $item['views'] }}</div>
    </a>
    @empty
    <div style="padding:40px 20px;text-align:center;color:var(--text-muted);font-size:13px;">لا توجد بيانات</div>
    @endforelse
  </div>

  <div class="admin-card">
    <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;">
      <span style="font-size:18px;">🔍</span>
      <h2 style="font-size:15px;font-weight:900;color:var(--text);margin:0;">الأكثر بحثًا</h2>
    </div>
    @forelse($topSearches as $s)
    <div class="admin-list-item">
      <div style="width:32px;height:32px;border-radius:8px;background:#ede9fe;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">🔍</div>
      <div style="flex:1;font-weight:800;font-size:13px;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $s->search_query }}</div>
      <div style="font-weight:900;color:#8b5cf6;font-size:15px;">{{ $s->count }}</div>
    </div>
    @empty
    <div style="padding:40px 20px;text-align:center;color:var(--text-muted);font-size:13px;">لا توجد بيانات</div>
    @endforelse
  </div>
</div>

<div class="admin-card" style="margin-bottom:16px;">
  <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;">
    <span style="font-size:18px;">👑</span>
    <h2 style="font-size:15px;font-weight:900;color:var(--text);margin:0;">الأكثر تفاعلًا</h2>
  </div>
  @forelse($topCustomers as $i => $c)
  <div class="admin-list-item">
    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#8b5cf6,#6d28d9);display:flex;align-items:center;justify-content:center;color:white;font-weight:900;font-size:13px;flex-shrink:0;">{{ $i + 1 }}</div>
    <div style="flex:1;min-width:0;">
      <div style="font-weight:800;font-size:12px;color:var(--text);">
        جلسة #{{ substr($c->session_id ?? 'N/A', 0, 8) }}
        @if($c->user_id)<span style="background:#dcfce7;color:#15803d;font-size:10px;padding:2px 8px;border-radius:999px;font-weight:900;margin-right:6px;">مسجّل</span>@endif
      </div>
    </div>
    <div style="display:flex;gap:14px;font-size:11px;">
      <div style="text-align:center;"><div style="font-weight:900;color:#f59e0b;font-size:14px;">{{ $c->total_views }}</div><div style="color:var(--text-muted);font-weight:700;">👁️</div></div>
      <div style="text-align:center;"><div style="font-weight:900;color:#3b82f6;font-size:14px;">{{ $c->total_cart_adds }}</div><div style="color:var(--text-muted);font-weight:700;">🛒</div></div>
      <div style="text-align:center;"><div style="font-weight:900;color:#10b981;font-size:14px;">{{ $c->total_purchases }}</div><div style="color:var(--text-muted);font-weight:700;">💰</div></div>
    </div>
  </div>
  @empty
  <div style="padding:40px 20px;text-align:center;color:var(--text-muted);font-size:13px;">لا يوجد عملاء بعد</div>
  @endforelse
</div>

<div class="admin-card">
  <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;">
    <span style="font-size:18px;">📊</span>
    <h2 style="font-size:15px;font-weight:900;color:var(--text);margin:0;">آخر الأحداث</h2>
  </div>
  @forelse($recentEvents as $e)
  <div class="admin-list-item" style="padding:10px 20px;">
    <div style="width:32px;height:32px;border-radius:8px;background:var(--bg);display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">
      @if($e->event_type === 'view') 👁️
      @elseif($e->event_type === 'add_to_cart') 🛒
      @elseif($e->event_type === 'purchase') 💰
      @elseif($e->event_type === 'search') 🔍
      @elseif($e->event_type === 'wishlist') ❤️
      @else 📌 @endif
    </div>
    <div style="flex:1;min-width:0;">
      <div style="font-weight:800;font-size:12px;color:var(--text);">
        {{ $e->event_type }}
        @if($e->product_id)<span style="color:var(--text-muted);"> — منتج #{{ $e->product_id }}</span>@endif
        @if($e->search_query)<span style="color:var(--text-muted);"> — "{{ $e->search_query }}"</span>@endif
      </div>
    </div>
    <div style="font-size:11px;color:var(--text-muted);font-weight:700;">{{ $e->created_at->diffForHumans() }}</div>
  </div>
  @empty
  <div style="padding:40px 20px;text-align:center;color:var(--text-muted);font-size:13px;">لا توجد أحداث</div>
  @endforelse
</div>

<style>
  .admin-list-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    border-bottom: 1px solid var(--border);
    text-decoration: none;
    color: inherit;
    transition: background 0.2s;
  }
  .admin-list-item:last-child { border-bottom: none; }
  .admin-list-item:hover { background: var(--bg); }
  @media (max-width: 900px) {
    div[style*="grid-template-columns:1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }
</style>

@endsection

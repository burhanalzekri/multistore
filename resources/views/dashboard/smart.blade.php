@extends('layouts.app')
@section('title', 'لوحة التحكم الذكية')
@section('page-title', 'لوحة التحكم')
@section('page-subtitle', now()->translatedFormat('l، j F Y'))

@section('content')

<!-- ═══ Welcome + Today Stats ═══ -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
  <div style="background:linear-gradient(135deg,#fbbf24,#f97316);border-radius:20px;padding:22px;color:white;box-shadow:0 8px 24px rgba(245,158,11,0.25);position:relative;overflow:hidden;">
    <div style="position:absolute;top:-20px;left:-20px;width:100px;height:100px;background:rgba(255,255,255,0.1);border-radius:50%;"></div>
    <div style="position:relative;">
      <div style="font-size:12px;opacity:0.9;margin-bottom:4px;">مرحبًا بك 👋</div>
      <div style="font-size:20px;font-weight:900;margin-bottom:12px;">{{ auth()->user()->name ?? 'مدير المتجر' }}</div>
      <div style="font-size:12px;opacity:0.9;">إليك ملخص يومك</div>
    </div>
  </div>

  <a href="/dashboard/reports" style="text-decoration:none;color:inherit;">
    <div class="admin-card" style="padding:20px;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='';this.style.boxShadow='var(--shadow-sm)'">
      <div style="font-size:11px;color:var(--text-muted);font-weight:900;margin-bottom:8px;">💰 مبيعات اليوم</div>
      <div style="font-size:26px;font-weight:900;color:#10b981;">{{ number_format($todayStats['sales']) }}</div>
      <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">ريال يمني</div>
      <div style="font-size:12px;color:var(--text-muted);margin-top:8px;padding-top:8px;border-top:1px dashed var(--border);">
        🛒 {{ $todayStats['orders'] }} طلب اليوم
      </div>
    </div>
  </a>
</div>

<!-- ═══ Smart Alerts ═══ -->
@if(count($alerts) > 0)
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:12px;margin-bottom:16px;">
  @foreach($alerts as $alert)
  <a href="{{ $alert['link'] }}" style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:14px 16px;text-decoration:none;color:inherit;display:flex;align-items:center;gap:12px;transition:all 0.2s;border-right:4px solid {{ $alert['type'] === 'danger' ? '#ef4444' : ($alert['type'] === 'warning' ? '#f59e0b' : '#3b82f6') }};"
     onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='';this.style.boxShadow='none'">
    <div style="width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $alert['type'] === 'danger' ? '#fee2e2' : ($alert['type'] === 'warning' ? '#fef3c7' : '#dbeafe') }};color:{{ $alert['type'] === 'danger' ? '#dc2626' : ($alert['type'] === 'warning' ? '#b45309' : '#1d4ed8') }};">
      <i data-lucide="{{ $alert['icon'] }}" style="width:20px;height:20px;"></i>
    </div>
    <div style="flex:1;min-width:0;">
      <div style="font-weight:900;font-size:13px;color:var(--text);">{{ $alert['title'] }}</div>
      <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">{{ $alert['message'] }}</div>
    </div>
    <i data-lucide="chevron-left" style="width:16px;height:16px;color:var(--text-muted);"></i>
  </a>
  @endforeach
</div>
@endif

<!-- ═══ Main Stats - 4 بطاقات قابلة للنقر ═══ -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:16px;">

  <a href="/dashboard/reports" class="admin-card" style="padding:18px;position:relative;text-decoration:none;color:inherit;display:block;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='';this.style.boxShadow='var(--shadow-sm)'">
    <div style="position:absolute;top:0;right:0;width:4px;height:100%;background:linear-gradient(180deg,#34d399,#10b981);border-radius:0 18px 18px 0;"></div>
    <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#34d399,#10b981);display:flex;align-items:center;justify-content:center;margin-bottom:10px;box-shadow:0 4px 12px rgba(16,185,129,0.3);">
      <i data-lucide="trending-up" style="width:22px;height:22px;color:white;"></i>
    </div>
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">إجمالي المبيعات</div>
    <div style="font-size:22px;font-weight:900;color:var(--text);">{{ number_format($stats['sales']) }}</div>
    <div style="font-size:11px;color:var(--text-muted);">ريال</div>
  </a>

  <a href="/dashboard/orders" class="admin-card" style="padding:18px;position:relative;text-decoration:none;color:inherit;display:block;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='';this.style.boxShadow='var(--shadow-sm)'">
    <div style="position:absolute;top:0;right:0;width:4px;height:100%;background:linear-gradient(180deg,#60a5fa,#3b82f6);border-radius:0 18px 18px 0;"></div>
    <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#60a5fa,#3b82f6);display:flex;align-items:center;justify-content:center;margin-bottom:10px;box-shadow:0 4px 12px rgba(59,130,246,0.3);">
      <i data-lucide="shopping-bag" style="width:22px;height:22px;color:white;"></i>
    </div>
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">الطلبات</div>
    <div style="font-size:22px;font-weight:900;color:var(--text);">{{ $stats['orders'] }}</div>
    <div style="font-size:11px;color:var(--text-muted);">طلب</div>
  </a>

  <a href="/dashboard/orders?status=awaiting_payment" class="admin-card" style="padding:18px;position:relative;text-decoration:none;color:inherit;display:block;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='';this.style.boxShadow='var(--shadow-sm)'">
    <div style="position:absolute;top:0;right:0;width:4px;height:100%;background:linear-gradient(180deg,#fbbf24,#f59e0b);border-radius:0 18px 18px 0;"></div>
    <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#fbbf24,#f59e0b);display:flex;align-items:center;justify-content:center;margin-bottom:10px;box-shadow:0 4px 12px rgba(245,158,11,0.3);">
      <i data-lucide="clock" style="width:22px;height:22px;color:white;"></i>
    </div>
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">بانتظار الدفع</div>
    <div style="font-size:22px;font-weight:900;color:var(--text);">{{ $stats['pending'] }}</div>
    <div style="font-size:11px;color:var(--text-muted);">طلب</div>
  </a>

  <a href="/dashboard/sms" class="admin-card" style="padding:18px;position:relative;text-decoration:none;color:inherit;display:block;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='';this.style.boxShadow='var(--shadow-sm)'">
    <div style="position:absolute;top:0;right:0;width:4px;height:100%;background:linear-gradient(180deg,#a78bfa,#8b5cf6);border-radius:0 18px 18px 0;"></div>
    <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#a78bfa,#8b5cf6);display:flex;align-items:center;justify-content:center;margin-bottom:10px;box-shadow:0 4px 12px rgba(139,92,246,0.3);">
      <i data-lucide="message-square" style="width:22px;height:22px;color:white;"></i>
    </div>
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">رسائل SMS</div>
    <div style="font-size:22px;font-weight:900;color:var(--text);">{{ $stats['sms'] }}</div>
    <div style="font-size:11px;color:var(--text-muted);">رسالة</div>
  </a>
</div>

<!-- ═══ Chart — قابل للنقر ═══ -->
<a href="/dashboard/reports" class="admin-card" style="padding:22px;margin-bottom:16px;text-decoration:none;color:inherit;display:block;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.transform='';this.style.boxShadow='var(--shadow-sm)'">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <div style="display:flex;align-items:center;gap:10px;">
      <i data-lucide="activity" style="width:20px;height:20px;color:var(--primary);"></i>
      <h2 style="font-size:15px;font-weight:900;color:var(--text);">المبيعات — آخر 7 أيام</h2>
    </div>
    <span style="font-size:11px;color:var(--primary);font-weight:800;">عرض التقارير ←</span>
  </div>
  @php $max = max(array_column($chart, 'sales')) ?: 1; @endphp
  <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:6px;height:140px;">
    @foreach($chart as $c)
    <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:6px;">
      <div style="font-size:9px;font-weight:900;color:var(--text-muted);">{{ $c['sales'] > 0 ? number_format($c['sales']) : '' }}</div>
      <div style="width:100%;background:linear-gradient(to top,#f59e0b,#fbbf24);border-radius:8px 8px 0 0;height:{{ max(6, ($c['sales'] / $max) * 100) }}px;"></div>
      <div style="font-size:10px;color:var(--text-muted);font-weight:800;">{{ $c['day'] }}</div>
    </div>
    @endforeach
  </div>
</a>

<!-- ═══ Best Sellers + Low Stock ═══ -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">

  <div class="admin-card">
    <div style="padding:18px 20px;border-bottom:1px solid var(--border);">
      <div style="display:flex;align-items:center;gap:10px;">
        <i data-lucide="flame" style="width:20px;height:20px;color:#ef4444;"></i>
        <h2 style="font-size:15px;font-weight:900;color:var(--text);">🔥 الأكثر مبيعًا</h2>
      </div>
    </div>
    @forelse($bestSellers as $i => $b)
    <div style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border);">
      <div style="width:36px;height:36px;border-radius:10px;background:{{ $i === 0 ? 'linear-gradient(135deg,#fbbf24,#f97316)' : ($i === 1 ? 'linear-gradient(135deg,#94a3b8,#64748b)' : 'linear-gradient(135deg,#d97706,#b45309)') }};display:flex;align-items:center;justify-content:center;font-weight:900;color:white;flex-shrink:0;">
        {{ $i + 1 }}
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-weight:900;font-size:13px;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $b->product_name }}</div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">{{ $b->total_sold }} قطعة مبيعة</div>
      </div>
      <div style="text-align:left;">
        <div style="font-weight:900;font-size:14px;color:#10b981;">{{ number_format($b->total_revenue) }}</div>
        <div style="font-size:10px;color:var(--text-muted);">ريال</div>
      </div>
    </div>
    @empty
    <div style="padding:40px 20px;text-align:center;">
      <div style="font-size:40px;margin-bottom:8px;opacity:0.5;">📊</div>
      <div style="font-size:13px;color:var(--text-muted);">لا توجد مبيعات بعد</div>
    </div>
    @endforelse
  </div>

  <div class="admin-card">
    <div style="padding:18px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
      <div style="display:flex;align-items:center;gap:10px;">
        <i data-lucide="alert-triangle" style="width:20px;height:20px;color:#f59e0b;"></i>
        <h2 style="font-size:15px;font-weight:900;color:var(--text);">⚠️ مخزون منخفض</h2>
      </div>
      <a href="/dashboard/products" style="font-size:12px;color:var(--primary);font-weight:800;text-decoration:none;">عرض الكل ←</a>
    </div>
    @forelse($lowStock as $p)
    <a href="/dashboard/products/{{ $p->id }}/edit" style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border);text-decoration:none;color:inherit;">
      <div style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,#fef3c7,#fed7aa);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;overflow:hidden;">
        @if($p->image)
        <img src="{{ ($p->image_url ?? Storage::url($p->image)) }}" style="width:100%;height:100%;object-fit:cover;">
        @else
        📦
        @endif
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-weight:900;font-size:13px;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $p->name }}</div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">{{ number_format($p->price) }} ريال</div>
      </div>
      <div style="text-align:left;">
        <div style="font-weight:900;font-size:18px;color:#f59e0b;">{{ $p->stock }}</div>
        <div style="font-size:10px;color:var(--text-muted);">متبقي</div>
      </div>
    </a>
    @empty
    <div style="padding:40px 20px;text-align:center;">
      <div style="font-size:40px;margin-bottom:8px;opacity:0.5;">✅</div>
      <div style="font-size:13px;color:var(--text-muted);">كل المنتجات بمخزون جيد</div>
    </div>
    @endforelse
  </div>
</div>

<!-- ═══ Out of Stock + Repeat Products ═══ -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">

  <div class="admin-card">
    <div style="padding:18px 20px;border-bottom:1px solid var(--border);">
      <div style="display:flex;align-items:center;gap:10px;">
        <i data-lucide="x-circle" style="width:20px;height:20px;color:#ef4444;"></i>
        <h2 style="font-size:15px;font-weight:900;color:var(--text);">❌ نفد المخزون</h2>
      </div>
    </div>
    @forelse($outOfStock as $p)
    <a href="/dashboard/products/{{ $p->id }}/edit" style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border);text-decoration:none;color:inherit;">
      <div style="width:44px;height:44px;border-radius:10px;background:#fee2e2;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;overflow:hidden;">
        @if($p->image)
        <img src="{{ ($p->image_url ?? Storage::url($p->image)) }}" style="width:100%;height:100%;object-fit:cover;">
        @else
        📦
        @endif
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-weight:900;font-size:13px;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $p->name }}</div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">مبيع: {{ $p->sold_count ?? 0 }} قطعة</div>
      </div>
      <span style="background:#fee2e2;color:#b91c1c;font-size:11px;font-weight:900;padding:4px 10px;border-radius:999px;">نفد</span>
    </a>
    @empty
    <div style="padding:40px 20px;text-align:center;">
      <div style="font-size:40px;margin-bottom:8px;opacity:0.5;">✅</div>
      <div style="font-size:13px;color:var(--text-muted);">لا يوجد منتجات نافذة</div>
    </div>
    @endforelse
  </div>

  <div class="admin-card">
    <div style="padding:18px 20px;border-bottom:1px solid var(--border);">
      <div style="display:flex;align-items:center;gap:10px;">
        <i data-lucide="repeat" style="width:20px;height:20px;color:#8b5cf6;"></i>
        <h2 style="font-size:15px;font-weight:900;color:var(--text);">🔄 مطلوب باستمرار</h2>
      </div>
    </div>
    @forelse($repeatProducts as $r)
    <div style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border);">
      <div style="width:44px;height:44px;border-radius:10px;background:#ede9fe;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">
        🔄
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-weight:900;font-size:13px;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $r->product_name }}</div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">{{ $r->customer_count }} عميل مختلف</div>
      </div>
      <div style="text-align:left;">
        <div style="font-weight:900;font-size:16px;color:#8b5cf6;">{{ $r->order_count }}</div>
        <div style="font-size:10px;color:var(--text-muted);">طلب</div>
      </div>
    </div>
    @empty
    <div style="padding:40px 20px;text-align:center;">
      <div style="font-size:40px;margin-bottom:8px;opacity:0.5;">📊</div>
      <div style="font-size:13px;color:var(--text-muted);">لا توجد بيانات كافية</div>
    </div>
    @endforelse
  </div>
</div>

<!-- ═══ Recent Orders + SMS ═══ -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

  <div class="admin-card">
    <div style="padding:18px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
      <div style="display:flex;align-items:center;gap:10px;">
        <i data-lucide="clock" style="width:20px;height:20px;color:var(--primary);"></i>
        <h2 style="font-size:15px;font-weight:900;color:var(--text);">📋 آخر الطلبات</h2>
      </div>
      <a href="/dashboard/orders" style="font-size:12px;color:var(--primary);font-weight:800;text-decoration:none;">عرض الكل ←</a>
    </div>
    @forelse($recentOrders as $o)
    <a href="/dashboard/orders/{{ $o->id }}" style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border);text-decoration:none;color:inherit;">
      <div style="width:44px;height:44px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i data-lucide="package" style="width:20px;height:20px;color:var(--primary);"></i>
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-weight:900;font-size:13px;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $o->customer_name }}</div>
        <div style="font-size:10px;color:var(--text-muted);font-family:monospace;margin-top:2px;">{{ $o->order_number }}</div>
      </div>
      <div style="text-align:left;">
        <div style="font-weight:900;font-size:14px;color:var(--primary);">{{ number_format($o->total) }}</div>
        <div style="font-size:10px;color:var(--text-muted);">{{ $o->created_at->diffForHumans() }}</div>
      </div>
    </a>
    @empty
    <div style="padding:40px 20px;text-align:center;">
      <div style="font-size:40px;margin-bottom:8px;opacity:0.5;">📦</div>
      <div style="font-size:13px;color:var(--text-muted);">لا توجد طلبات</div>
    </div>
    @endforelse
  </div>

  <div class="admin-card">
    <div style="padding:18px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
      <div style="display:flex;align-items:center;gap:10px;">
        <i data-lucide="message-square" style="width:20px;height:20px;color:#8b5cf6;"></i>
        <h2 style="font-size:15px;font-weight:900;color:var(--text);">📱 آخر رسائل SMS</h2>
      </div>
      <a href="/dashboard/sms" style="font-size:12px;color:var(--primary);font-weight:800;text-decoration:none;">عرض الكل ←</a>
    </div>
    @forelse($smsList as $s)
    <a href="/dashboard/sms/{{ $s->id }}" style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border);text-decoration:none;color:inherit;">
      <div style="width:44px;height:44px;border-radius:10px;background:#ede9fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i data-lucide="smartphone" style="width:20px;height:20px;color:#8b5cf6;"></i>
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-weight:900;font-size:13px;color:var(--text);font-family:monospace;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $s->sender_phone }}</div>
        <div style="font-size:10px;color:var(--text-muted);margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ \Illuminate\Support\Str::limit($s->raw_body, 40) }}</div>
      </div>
      <div style="text-align:left;">
        <div style="font-weight:900;font-size:14px;color:#8b5cf6;">{{ $s->parsed_amount ? number_format($s->parsed_amount) : '—' }}</div>
        <div style="font-size:10px;color:var(--text-muted);">{{ $s->received_at?->diffForHumans() ?? '' }}</div>
      </div>
    </a>
    @empty
    <div style="padding:40px 20px;text-align:center;">
      <div style="font-size:40px;margin-bottom:8px;opacity:0.5;">📱</div>
      <div style="font-size:13px;color:var(--text-muted);">لا توجد رسائل</div>
    </div>
    @endforelse
  </div>
</div>

<style>
@media (max-width: 900px) {
  div[style*="grid-template-columns:1fr 1fr"] {
    grid-template-columns: 1fr !important;
  }
}
</style>

<script>
// ═══ التحديث التلقائي + الإشعارات الصوتية ═══
let lastOrderCount = 0;
let lastReviewSmsCount = 0;

function smartAutoRefresh() {
    fetch("/api/notifications/check")
        .then(r => r.json())
        .then(data => {
            if (data.new_orders > 0 && data.new_orders > lastOrderCount) {
                playNotificationSound();
                if (Notification.permission === "granted") {
                    new Notification("🛒 طلب جديد!", { body: "لديك " + data.new_orders + " طلب جديد", icon: "/icon-192.png", tag: "new-order" });
                }
            }
            if (data.review_sms > 0 && data.review_sms > lastReviewSmsCount) {
                playNotificationSound();
            }
            lastOrderCount = data.new_orders;
            lastReviewSmsCount = data.review_sms;
        })
        .catch(() => {});
}

function playNotificationSound() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain); gain.connect(ctx.destination);
        osc.frequency.value = 800; gain.gain.value = 0.3;
        osc.start();
        gain.gain.exponentialRampToValueAtTime(0.00001, ctx.currentTime + 0.3);
        osc.stop(ctx.currentTime + 0.3);
        setTimeout(() => {
            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.connect(gain2); gain2.connect(ctx.destination);
            osc2.frequency.value = 1200; gain2.gain.value = 0.3;
            osc2.start();
            gain2.gain.exponentialRampToValueAtTime(0.00001, ctx.currentTime + 0.3);
            osc2.stop(ctx.currentTime + 0.3);
        }, 150);
    } catch (e) {}
}

setInterval(smartAutoRefresh, 30000);
setTimeout(smartAutoRefresh, 3000);
</script>

@include("dashboard._maintenance")

{{-- ═══ Maintenance + AJAX + Push ═══ --}}
@include('dashboard._maintenance')

<script>
function smartAjaxUpdate() {
    fetch("/api/dashboard/stats").then(r => r.json()).then(res => {
        if (!res.success) return;
        const d = res.data;
        ["stat-sales", d.sales.toLocaleString()],
        ["stat-orders", d.orders],
        ["stat-pending", d.pending],
        ["stat-sms", d.sms]
        .forEach(([id, val]) => {
            const el = document.getElementById(id);
            if (el && el.textContent !== String(val)) {
                el.style.transition = "background 0.3s";
                el.style.background = "rgba(16,185,129,0.15)";
                el.textContent = val;
                setTimeout(() => el.style.background = "", 800);
            }
        });
    }).catch(() => {});
}
setInterval(smartAjaxUpdate, 15000);

function showPushNotification(title, body, url) {
    if (!("Notification" in window)) return;
    if (Notification.permission !== "granted") return;
    navigator.serviceWorker.ready.then(reg => {
        reg.showNotification(title, {
            body: body, icon: "/icon-192.png", badge: "/icon-192.png",
            vibrate: [200, 100, 200], tag: "smart-" + Date.now(),
            data: { url: url || "/dashboard" }
        });
    }).catch(() => new Notification(title, { body: body, icon: "/icon-192.png" }));
}
</script>


@endsection

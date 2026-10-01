@extends('layouts.app')

@section('title', 'مناطق الشحن')

@push('styles')
<style>
  .sz-wrap{padding:24px;max-width:1400px;margin:0 auto}
  .sz-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:24px}
  .sz-title{font-size:26px;font-weight:900;color:#111827;margin:0 0 6px;display:flex;align-items:center;gap:10px}
  .sz-sub{font-size:13px;color:#6b7280}
  .sz-btn{display:inline-flex;align-items:center;gap:7px;padding:11px 20px;border-radius:12px;font-size:13px;font-weight:800;text-decoration:none;border:0;cursor:pointer;font-family:inherit;transition:all .25s}
  .sz-btn-primary{background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;box-shadow:0 8px 20px rgba(245,158,11,.3)}
  .sz-btn-primary:hover{transform:translateY(-2px);box-shadow:0 12px 28px rgba(245,158,11,.4)}
  .sz-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-bottom:24px}
  .sz-stat{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:18px;display:flex;align-items:center;gap:14px;box-shadow:0 4px 12px rgba(15,23,42,.04)}
  .sz-stat-icon{width:48px;height:48px;border-radius:14px;display:grid;place-items:center;font-size:22px}
  .sz-stat-icon.orange{background:#fff7ed}
  .sz-stat-icon.green{background:#f0fdf4}
  .sz-stat-icon.blue{background:#eff6ff}
  .sz-stat-icon.purple{background:#faf5ff}
  .sz-stat-label{font-size:12px;color:#6b7280;font-weight:700}
  .sz-stat-value{font-size:22px;font-weight:900;color:#111827;margin-top:2px}
  .sz-card{background:#fff;border:1px solid #e5e7eb;border-radius:18px;overflow:hidden;box-shadow:0 4px 16px rgba(15,23,42,.05)}
  .sz-table{width:100%;border-collapse:collapse;font-size:13px}
  .sz-table th{background:#f9fafb;padding:14px 12px;text-align:right;font-weight:900;color:#374151;border-bottom:2px solid #e5e7eb;font-size:12px;white-space:nowrap}
  .sz-table td{padding:14px 12px;border-bottom:1px solid #f3f4f6;color:#111827}
  .sz-table tr:hover td{background:#fafafa}
  .sz-badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:99px;font-size:11px;font-weight:800}
  .sz-badge.active{background:#dcfce7;color:#166534}
  .sz-badge.inactive{background:#f3f4f6;color:#6b7280}
  .sz-badge.free{background:#dbeafe;color:#1e40af}
  .sz-actions{display:flex;gap:6px}
  .sz-icon-btn{width:34px;height:34px;border-radius:10px;border:1px solid #e5e7eb;background:#fff;display:grid;place-items:center;cursor:pointer;transition:all .2s;color:#6b7280;text-decoration:none}
  .sz-icon-btn:hover{background:#f9fafb;color:#111827;transform:translateY(-2px)}
  .sz-icon-btn.edit:hover{border-color:#3b82f6;color:#3b82f6;background:#eff6ff}
  .sz-icon-btn.delete:hover{border-color:#ef4444;color:#ef4444;background:#fef2f2}
  .sz-icon-btn.toggle:hover{border-color:#f59e0b;color:#f59e0b;background:#fff7ed}
  .sz-empty{text-align:center;padding:60px 20px;color:#9ca3af}
  .sz-empty-icon{font-size:60px;margin-bottom:12px}
  .sz-copy{display:inline-flex;align-items:center;gap:6px;font-size:11px;color:#6b7280;font-family:monospace;background:#f9fafb;padding:4px 8px;border-radius:6px;cursor:pointer;border:1px solid #e5e7eb}
  .sz-copy:hover{background:#f3f4f6;color:#111827}
  .sz-copy-code{font-family:monospace;font-size:11px;color:#9ca3af}
  @media (max-width:768px){
    .sz-wrap{padding:16px}
    .sz-table{font-size:11px}
    .sz-table th,.sz-table td{padding:10px 8px}
    .sz-title{font-size:20px}
  }
</style>
@endpush

@section('content')
<div class="sz-wrap">

  {{-- Header --}}
  <div class="sz-header">
    <div>
      <h1 class="sz-title">🚚 مناطق الشحن</h1>
      <div class="sz-sub">إدارة مناطق التوصيل وأسعارها ومدد التوصيل</div>
    </div>
    <a href="{{ route('shipping-zones.create') }}" class="sz-btn sz-btn-primary">
      <i data-lucide="plus" style="width:16px;height:16px;"></i>
      إضافة منطقة
    </a>
  </div>

  {{-- Flash Message --}}
  @if(session('status'))
    <div style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);border:2px solid #4ade80;color:#166534;padding:14px 18px;border-radius:14px;font-weight:800;font-size:13.5px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
      <i data-lucide="check-circle" style="width:20px;height:20px;"></i>
      {{ session('status') }}
    </div>
  @endif

  {{-- Stats --}}
  <div class="sz-stats">
    <div class="sz-stat">
      <div class="sz-stat-icon orange">📍</div>
      <div>
        <div class="sz-stat-label">إجمالي المناطق</div>
        <div class="sz-stat-value">{{ $stats['total'] }}</div>
      </div>
    </div>
    <div class="sz-stat">
      <div class="sz-stat-icon green">✅</div>
      <div>
        <div class="sz-stat-label">المناطق النشطة</div>
        <div class="sz-stat-value">{{ $stats['active'] }}</div>
      </div>
    </div>
    <div class="sz-stat">
      <div class="sz-stat-icon blue">🎁</div>
      <div>
        <div class="sz-stat-label">مناطق مجانية</div>
        <div class="sz-stat-value">{{ $stats['free'] }}</div>
      </div>
    </div>
    <div class="sz-stat">
      <div class="sz-stat-icon purple">💰</div>
      <div>
        <div class="sz-stat-label">متوسط السعر</div>
        <div class="sz-stat-value">{{ number_format($stats['avg_fee']) }} ر.ي</div>
      </div>
    </div>
    @if(isset($stats['with_quote']) && $stats['with_quote'] > 0)
    <div class="sz-stat">
      <div class="sz-stat-icon orange">📞</div>
      <div>
        <div class="sz-stat-label">تسعير يدوي</div>
        <div class="sz-stat-value">{{ $stats['with_quote'] }}</div>
      </div>
    </div>
    @endif
  </div>

  {{-- Table --}}
  <div class="sz-card">
    @if($zones->count() > 0)
      <div style="overflow-x:auto;">
        <table class="sz-table">
          <thead>
            <tr>
              <th>#</th>
              <th>المنطقة</th>
              <th>الرمز</th>
              <th>السعر</th>
              <th>شحن مجاني</th>
              <th>الحد الأقصى</th>
              <th>المدة</th>
              <th>الحالة</th>
              <th>الإجراءات</th>
            </tr>
          </thead>
          <tbody>
            @foreach($zones as $i => $zone)
            <tr>
              <td style="color:#9ca3af;font-weight:700;">{{ $i + 1 }}</td>
              <td><strong>{{ $zone->name }}</strong></td>
              <td>
                @if($zone->code)
                  <span class="sz-copy" onclick="copyText('{{ $zone->code }}', this)">
                    <i data-lucide="copy" style="width:12px;height:12px;"></i>
                    {{ $zone->code }}
                  </span>
                @else
                  <span style="color:#9ca3af;">—</span>
                @endif
              </td>
              <td>
                @if((float)$zone->fee === 0.0)
                  <span class="sz-badge free">🎁 مجاني</span>
                @else
                  <strong>{{ number_format($zone->fee) }}</strong> ر.ي
                @endif
              </td>
              <td>
                @if($zone->free_over)
                  {{ number_format($zone->free_over) }} ر.ي
                @else
                  <span style="color:#9ca3af;">—</span>
                @endif
              </td>
              <td>
                @if($zone->max_order_amount)
                  <span style="background:#fff7ed;color:#c2410c;padding:3px 8px;border-radius:6px;font-size:11px;font-weight:800;display:inline-flex;align-items:center;gap:4px;" title="الطلبات فوق هذا المبلغ تحتاج تسعير يدوي">
                    📞 حتى {{ number_format($zone->max_order_amount) }}
                  </span>
                @else
                  <span style="background:#f0fdf4;color:#166534;padding:3px 8px;border-radius:6px;font-size:11px;font-weight:800;display:inline-flex;align-items:center;gap:4px;" title="التسعير تلقائي — لا حد أقصى">
                    ✅ تلقائي
                  </span>
                @endif
              </td>
              <td>{{ $zone->eta ?: '—' }}</td>
              <td>
                @if($zone->is_active)
                  <span class="sz-badge active">✅ نشطة</span>
                @else
                  <span class="sz-badge inactive">⏸️ معطلة</span>
                @endif
              </td>
              <td>
                <div class="sz-actions">
                  <a href="{{ route('shipping-zones.edit', $zone->id) }}" class="sz-icon-btn edit" title="تعديل">
                    <i data-lucide="edit-2" style="width:14px;height:14px;"></i>
                  </a>
                  <button onclick="toggleZone({{ $zone->id }}, this)" class="sz-icon-btn toggle" title="تفعيل/تعطيل">
                    <i data-lucide="{{ $zone->is_active ? 'eye-off' : 'eye' }}" style="width:14px;height:14px;"></i>
                  </button>
                  <form method="POST" action="{{ route('shipping-zones.destroy', $zone->id) }}" onsubmit="return confirm('حذف المنطقة؟');" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="sz-icon-btn delete" title="حذف">
                      <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="sz-empty">
        <div class="sz-empty-icon">🚚</div>
        <h3 style="font-size:18px;font-weight:900;color:#374151;margin:0 0 8px;">لا توجد مناطق شحن بعد</h3>
        <p style="font-size:13px;margin:0 0 16px;">أضف أول منطقة لتوصيل طلباتك</p>
        <a href="{{ route('shipping-zones.create') }}" class="sz-btn sz-btn-primary">
          <i data-lucide="plus" style="width:16px;height:16px;"></i>
          إضافة منطقة
        </a>
      </div>
    @endif
  </div>

</div>
@endsection

@push('scripts')
<script>
  if (window.lucide) lucide.createIcons();

  // نسخ النص
  window.copyText = function(text, el) {
    navigator.clipboard.writeText(text).then(function() {
      var original = el.innerHTML;
      el.innerHTML = '<i data-lucide="check" style="width:12px;height:12px;"></i> تم';
      if (window.lucide) lucide.createIcons();
      setTimeout(function() {
        el.innerHTML = original;
        if (window.lucide) lucide.createIcons();
      }, 1500);
    });
  };

  // تبديل الحالة
  window.toggleZone = function(id, btn) {
    btn.style.opacity = '0.5';
    fetch('/dashboard/shipping-zones/' + id + '/toggle', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
      btn.style.opacity = '1';
      if (d.success) {
        window.location.reload();
      }
    })
    .catch(function() {
      btn.style.opacity = '1';
      alert('حدث خطأ');
    });
  };
</script>
@endpush

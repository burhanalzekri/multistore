@extends('layouts.app')
@section('title', 'تفاصيل الطلب')
@section('page-title', $order->order_number)
@section('page-subtitle', 'تفاصيل الطلب')

@section('content')

<a href="/dashboard/orders" class="shine-btn" style="background:white;border:1px solid var(--border);color:var(--text-muted);margin-bottom:20px;">
  ← العودة للطلبات
</a>

<div class="shine-grid-2">
  <div>
    <!-- Items -->
    <div class="shine-card">
      <div class="shine-card-header">
        <div class="shine-card-title"><i data-lucide="package"></i> المنتجات</div>
      </div>
      @foreach($order->items as $item)
      <div class="shine-list-item">
        <div class="shine-item-avatar">📦</div>
        <div class="shine-item-content">
          <h4>{{ $item->product_name }}</h4>
          <p>{{ $item->quantity }} × {{ number_format($item->unit_price) }} ريال</p>
        </div>
        <div class="shine-item-meta">
          <div class="shine-item-amount">{{ number_format($item->line_total) }}</div>
        </div>
      </div>
      @endforeach
      <div style="padding:16px 22px;background:#fafbfc;border-top:1px solid var(--border);">
        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;color:var(--text-muted);">
          <span>المجموع:</span><span>{{ number_format($order->subtotal) }} ريال</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;color:var(--text-muted);">
          <span>الشحن:</span><span>{{ number_format($order->shipping) }} ريال</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:16px;padding-top:10px;border-top:1px dashed var(--border);font-weight:900;">
          <span>الإجمالي:</span>
          <span style="color:var(--primary);">{{ number_format($order->total) }} ريال</span>
        </div>
      </div>
    </div>

    <!-- Status Update -->
    <div class="shine-card">
      <div class="shine-card-header">
        <div class="shine-card-title"><i data-lucide="settings"></i> تحديث الحالة</div>
      </div>
      <div style="padding:20px;">
        <form method="POST" action="/dashboard/orders/{{ $order->id }}/status" style="display:flex;flex-direction:column;gap:12px;">
          @csrf
          <div>
            <label style="display:block;font-size:13px;font-weight:800;margin-bottom:6px;">الحالة الجديدة</label>
            <select name="status" required style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-family:inherit;font-weight:700;outline:none;">
              <option value="awaiting_payment" {{ $order->status === "awaiting_payment" ? "selected" : "" }}>⏳ بانتظار الدفع</option>
              <option value="processing" {{ $order->status === "processing" ? "selected" : "" }}>⚙️ قيد التحضير</option>
              <option value="shipped" {{ $order->status === "shipped" ? "selected" : "" }}>🚚 قيد الشحن</option>
              <option value="delivered" {{ $order->status === "delivered" ? "selected" : "" }}>✅ تم التسليم</option>
              <option value="cancelled" {{ $order->status === "cancelled" ? "selected" : "" }}>❌ ملغى</option>
            </select>
          </div>
          <div>
            <label style="display:block;font-size:13px;font-weight:800;margin-bottom:6px;">رقم التتبع (اختياري)</label>
            <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="TRK123456789" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-family:monospace;font-weight:700;outline:none;">
          </div>
          <div>
            <label style="display:block;font-size:13px;font-weight:800;margin-bottom:6px;">شركة الشحن (اختياري)</label>
            <input type="text" name="carrier" value="{{ $order->carrier }}" placeholder="اليمن السعيد" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-family:inherit;font-weight:700;outline:none;">
          </div>
          <div>
            <label style="display:block;font-size:13px;font-weight:800;margin-bottom:6px;">ملاحظة للعميل (اختياري)</label>
            <textarea name="note" rows="2" placeholder="سيتم إرسالها للعميل عبر SMS" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-family:inherit;font-weight:600;outline:none;"></textarea>
          </div>
          <button type="submit" style="padding:14px;background:linear-gradient(135deg,#fbbf24,#f97316);color:white;border:none;border-radius:12px;font-weight:900;cursor:pointer;font-family:inherit;font-size:14px;">🔄 تحديث الحالة وإرسال SMS</button>
        </form>
      </div>
    </div>
  </div>

  <div>
    <!-- Customer -->
    <div class="shine-card">
      <div class="shine-card-header">
        <div class="shine-card-title"><i data-lucide="user"></i> العميل</div>
      </div>
      <div style="padding:20px;">
        <div style="display:flex;flex-direction:column;gap:12px;font-size:14px;">
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-muted);">الاسم</span>
            <strong>{{ $order->customer_name }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-muted);">الجوال</span>
            <strong style="font-family:monospace;">{{ $order->customer_phone }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;gap:12px;">
            <span style="color:var(--text-muted);">العنوان</span>
            <strong style="text-align:left;font-size:12px;">{{ $order->customer_address ?? '—' }}</strong>
          </div>
          @if($order->notes)
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-muted);">ملاحظات</span>
            <span>{{ $order->notes }}</span>
          </div>
          @endif
        </div>
      </div>
    </div>

    <a href="/dashboard/orders/{{ $order->id }}/invoice" target="_blank" class="shine-btn" style="width:100%;justify-content:center;background:#dbeafe;color:#1d4ed8;margin-bottom:16px;"><i data-lucide="file-text"></i> تحميل الفاتورة (PDF)</a>

<!-- Payment -->
    <div class="shine-card">
      <div class="shine-card-header">
        <div class="shine-card-title"><i data-lucide="wallet"></i> الدفع</div>
      </div>
      <div style="padding:20px;">
        <div style="display:flex;flex-direction:column;gap:12px;font-size:14px;">
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-muted);">الطريقة</span>
            <strong>{{ \App\Support\StatusHelper::label($order->payment_method) }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="color:var(--text-muted);">الحالة</span>
            <span class="shine-badge-status {{ $order->payment_status === 'confirmed' ? 'success' : ($order->payment_status === 'rejected' ? 'danger' : 'warning') }}">
              {{ \App\Support\StatusHelper::label($order->payment_status) }}
            </span>
          </div>
          @if($order->payment_reference)
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-muted);">المرجع</span>
            <strong style="font-family:monospace;font-size:12px;">{{ $order->payment_reference }}</strong>
          </div>
          @endif
          @if($order->paid_at)
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-muted);">مدفوع</span>
            <strong>{{ $order->paid_at->format('Y-m-d H:i') }}</strong>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>


{{-- 🔍 التحقق الفوري + تنبيهات --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('form').forEach(function(form) {
    if (form.dataset.validationReady) return;
    form.dataset.validationReady = '1';
    form.setAttribute('novalidate', 'novalidate');

    form.querySelectorAll('input, textarea, select').forEach(function(field) {
      field.addEventListener('input', function() {
        this.style.borderColor = '';
        this.style.background = '';
      });
    });

    form.addEventListener('submit', function(e) {
      let errors = [];
      let firstInvalid = null;

      form.querySelectorAll('[required]').forEach(function(field) {
        field.style.borderColor = '';
        field.style.background = '';

        if (!field.value.trim()) {
          let label = field.name;
          let labelEl = field.closest('div')?.querySelector('label');
          if (labelEl) {
            label = labelEl.textContent.replace('*', '').replace('مطلوب', '').trim();
          }
          errors.push('📌 ' + label + ' مطلوب');
          field.style.borderColor = '#ef4444';
          field.style.background = '#fef2f2';
          if (!firstInvalid) firstInvalid = field;
        }
      });

      form.querySelectorAll('input[type="number"]').forEach(function(field) {
        if (field.value && isNaN(parseFloat(field.value))) {
          errors.push('🔢 ' + (field.name === 'price' ? 'السعر' : field.name) + ' يجب أن يكون رقماً');
          field.style.borderColor = '#ef4444';
          if (!firstInvalid) firstInvalid = field;
        }
      });

      let email = form.querySelector('input[type="email"]');
      if (email && email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        errors.push('📧 البريد الإلكتروني غير صحيح');
        email.style.borderColor = '#ef4444';
        if (!firstInvalid) firstInvalid = email;
      }

      if (errors.length > 0) {
        e.preventDefault();
        e.stopPropagation();
        errors.forEach(function(err, i) {
          setTimeout(function() {
            if (window.showToast) window.showToast(err, 'error', 5000);
            else alert(err);
          }, i * 200);
        });
        if (firstInvalid) {
          firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
          setTimeout(function() { firstInvalid.focus(); }, 300);
        }
        return false;
      }
    });
  });

  @if($errors->any())
    @foreach($errors->all() as $error)
      setTimeout(function() {
        if (window.showToast) window.showToast("{{ addslashes($error) }}", 'error', 6000);
      }, {{ $loop->index * 200 }});
    @endforeach
  @endif

  @if(session('success'))
    setTimeout(function() {
      if (window.showToast) window.showToast("{{ addslashes(session('success')) }}", 'success', 5000);
    }, 300);
  @endif

  @if(session('error'))
    setTimeout(function() {
      if (window.showToast) window.showToast("{{ addslashes(session('error')) }}", 'error', 6000);
    }, 300);
  @endif
});
</script>

@endsection

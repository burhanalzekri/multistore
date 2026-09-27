@extends('layouts.app')
@section('title', 'عرض فلاش جديد')
@section('page-title', 'عرض فلاش جديد')

@section('content')

<a href="/dashboard/flash-sales" class="shine-btn" style="background:white;border:1px solid var(--border);color:var(--text-muted);margin-bottom:20px;">
  ← العودة
</a>

<form method="POST" action="/dashboard/flash-sales" style="max-width:640px;margin:0 auto;">
  @csrf

  <div class="shine-card">
    <div style="padding:22px;display:flex;flex-direction:column;gap:16px;">
      <div>
        <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;">المنتج *</label>
        <select name="product_id" required style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-family:inherit;font-weight:700;outline:none;">
          <option value="">— اختر المنتج —</option>
          @foreach($products as $p)
          <option value="{{ $p->id }}">{{ $p->name }} ({{ number_format($p->price) }} ريال)</option>
          @endforeach
        </select>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
          <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;">السعر بعد الخصم *</label>
          <input type="number" name="discount_price" required min="0" step="0.01" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-weight:700;outline:none;">
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;">الكمية القصوى</label>
          <input type="number" name="max_qty" value="0" min="0" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-weight:700;outline:none;">
          <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">0 = بدون حد</div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
          <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;">يبدأ في *</label>
          <input type="datetime-local" name="starts_at" required value="{{ now()->format('Y-m-d\TH:i') }}" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-weight:700;outline:none;">
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;">ينتهي في *</label>
          <input type="datetime-local" name="ends_at" required value="{{ now()->addDays(3)->format('Y-m-d\TH:i') }}" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-weight:700;outline:none;">
        </div>
      </div>
    </div>
  </div>

  <div style="display:flex;gap:10px;margin-top:16px;">
    <button type="submit" class="shine-btn shine-btn-primary" style="flex:1;justify-content:center;padding:14px;">
      <i data-lucide="zap"></i> إنشاء العرض
    </button>
    <a href="/dashboard/flash-sales" class="shine-btn" style="background:#f1f5f9;color:#475569;padding:14px 24px;">إلغاء</a>
  </div>
</form>


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

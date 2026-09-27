@extends('layouts.app')
@section('title', 'عروض فلاش')
@section('page-title', 'عروض فلاش')
@section('page-subtitle', $sales->total() . ' عرض')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
  <div style="color:var(--text-muted);font-size:13px;font-weight:700;">
    <strong style="color:var(--text);">{{ $sales->total() }}</strong> عرض
  </div>
  <a href="/dashboard/flash-sales/create" class="shine-btn shine-btn-primary">
    <i data-lucide="plus"></i> عرض جديد
  </a>
</div>

@if($sales->isEmpty())
<div class="shine-card">
  <div class="shine-empty">
    <div class="shine-empty-icon">⚡</div>
    <h3>لا توجد عروض فلاش</h3>
    <p>أنشئ عرضًا لزيادة المبيعات</p>
    <a href="/dashboard/flash-sales/create" class="shine-btn shine-btn-primary" style="display:inline-flex;">
      <i data-lucide="plus"></i> عرض أول
    </a>
  </div>
</div>
@else
<div class="shine-card">
  @foreach($sales as $s)
  <div class="shine-list-item">
    <div class="shine-item-avatar" style="background:{{ $s->isLive() ? '#fee2e2' : '#f1f5f9' }};color:{{ $s->isLive() ? '#dc2626' : '#64748b' }};">
      <i data-lucide="{{ $s->isLive() ? 'zap' : 'clock' }}"></i>
    </div>
    <div class="shine-item-content">
      <h4>{{ $s->product->name ?? '—' }}</h4>
      <p>
        {{ number_format($s->discount_price) }} ريال
        <span style="text-decoration:line-through;color:#94a3b8;margin-right:8px;">
          {{ number_format($s->product->price ?? 0) }}
        </span>
      </p>
      <p style="margin-top:4px;font-size:11px;">
        📅 {{ $s->starts_at->format('Y-m-d') }} → {{ $s->ends_at->format('Y-m-d') }}
      </p>
    </div>
    <div class="shine-item-meta">
      <span class="shine-badge-status {{ $s->isLive() ? 'danger' : 'neutral' }}">
        {{ $s->isLive() ? '⚡ نشط' : 'انتهى' }}
      </span>
      <form method="POST" action="/dashboard/flash-sales/{{ $s->id }}" onsubmit="return confirm('حذف العرض؟')" style="margin-top:6px;">
        @csrf @method('DELETE')
        <button class="shine-btn" style="background:#fee2e2;color:#b91c1c;padding:6px 10px;font-size:11px;">
          <i data-lucide="trash-2" style="width:12px;height:12px;"></i> حذف
        </button>
      </form>
    </div>
  </div>
  @endforeach
</div>
@endif


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

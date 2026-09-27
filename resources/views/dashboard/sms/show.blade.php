@extends('layouts.app')
@section('title', 'تفاصيل SMS')
@section('page-title', 'تفاصيل الرسالة')
@section('page-subtitle', $sms->sender_phone)

@section('content')

<a href="/dashboard/sms" class="shine-btn" style="background:white;border:1px solid var(--border);color:var(--text-muted);margin-bottom:20px;">
  ← العودة للرسائل
</a>

<div class="shine-grid-2">
  <div>
    <div class="shine-card">
      <div class="shine-card-header">
        <div class="shine-card-title"><i data-lucide="file-text"></i> الرسالة الأصلية</div>
      </div>
      <div style="padding:20px;">
        <div style="background:#f8fafc;padding:18px;border-radius:12px;font-family:monospace;font-size:13px;line-height:1.7;color:var(--text);word-break:break-word;">
          {{ $sms->raw_body }}
        </div>
      </div>
    </div>

    <div class="shine-card">
      <div class="shine-card-header">
        <div class="shine-card-title"><i data-lucide="search"></i> البيانات المُحلَّلة</div>
      </div>
      <div style="padding:20px;">
        <div style="display:flex;flex-direction:column;gap:12px;font-size:14px;">
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-muted);">المزود</span>
            <strong>{{ $sms->provider ?? '—' }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-muted);">المبلغ</span>
            <strong style="color:var(--primary);">{{ $sms->parsed_amount ? number_format($sms->parsed_amount) . ' ريال' : '—' }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-muted);">المرسل</span>
            <strong style="font-family:monospace;">{{ $sms->parsed_sender ?? '—' }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-muted);">المرجع</span>
            <strong style="font-family:monospace;">{{ $sms->parsed_reference ?? '—' }}</strong>
          </div>
          <div style="display:flex;justify-content:space-between;">
            <span style="color:var(--text-muted);">الثقة</span>
            <strong>{{ $sms->confidence }}%</strong>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="color:var(--text-muted);">الحالة</span>
            <span class="shine-badge-status {{ $sms->status === 'matched' ? 'success' : ($sms->status === 'rejected' ? 'danger' : 'warning') }}">
              {{ \App\Support\StatusHelper::label($sms->status) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="shine-card">
      <div class="shine-card-header">
        <div class="shine-card-title"><i data-lucide="link"></i> ربط يدوي بطلب</div>
      </div>
      <div style="padding:20px;display:flex;flex-direction:column;gap:14px;">
        <p style="margin:0;font-size:13px;color:var(--text-muted);">اختر الطلب لتأكيده بهذه الرسالة</p>

        <form method="POST" action="/dashboard/sms/{{ $sms->id }}/confirm" style="display:flex;flex-direction:column;gap:12px;">
          @csrf
          <select name="order_id" required style="padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-family:inherit;font-weight:700;font-size:13px;outline:none;">
            <option value="">— اختر الطلب —</option>
            @foreach($orders as $o)
            <option value="{{ $o->id }}">{{ $o->order_number }} — {{ $o->customer_name }} — {{ number_format($o->total) }} ريال</option>
            @endforeach
          </select>
          <button type="submit" class="shine-btn shine-btn-primary" style="justify-content:center;padding:12px;">
            <i data-lucide="check-circle"></i> تأكيد الدفع
          </button>
        </form>

        <div style="height:1px;background:var(--border);margin:8px 0;"></div>

        <form method="POST" action="/dashboard/sms/{{ $sms->id }}/reject">
          @csrf
          <button type="submit" class="shine-btn" style="width:100%;justify-content:center;background:#fee2e2;color:#b91c1c;padding:12px;">
            <i data-lucide="x"></i> رفض الرسالة
          </button>
        </form>
      </div>
    </div>

    @if($sms->matched_order_id)
    <div class="shine-card" style="border-color:#86efac;">
      <div style="padding:20px;background:#dcfce7;">
        <div style="display:flex;align-items:center;gap:8px;color:#15803d;font-weight:900;margin-bottom:12px;">
          <i data-lucide="check-circle" style="width:20px;height:20px;"></i>
          مرتبطة بطلب
        </div>
        <a href="/dashboard/orders/{{ $sms->matched_order_id }}" class="shine-btn shine-btn-primary" style="width:100%;justify-content:center;padding:12px;">
          <i data-lucide="package"></i> عرض الطلب #{{ $sms->matched_order_id }}
        </a>
      </div>
    </div>
    @endif
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

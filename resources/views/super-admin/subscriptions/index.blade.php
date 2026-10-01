@extends('layouts.super-admin')
@section('title', 'الاشتراكات')
@section('page-title', '💰 إدارة الاشتراكات')
@section('page-subtitle', 'كل اشتراكات المتاجر')

@section('content')

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:20px;">
  <div class="admin-card" style="padding:18px;border-right:4px solid #10b981;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">✅ نشطة</div>
    <div style="font-size:24px;font-weight:900;color:#10b981;margin-top:4px;">{{ $stats['active'] }}</div>
  </div>
  <div class="admin-card" style="padding:18px;border-right:4px solid #f59e0b;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">⏳ تجريبية</div>
    <div style="font-size:24px;font-weight:900;color:#f59e0b;margin-top:4px;">{{ $stats['trial'] }}</div>
  </div>
  <div class="admin-card" style="padding:18px;border-right:4px solid #ef4444;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">❌ منتهية</div>
    <div style="font-size:24px;font-weight:900;color:#ef4444;margin-top:4px;">{{ $stats['expired'] }}</div>
  </div>
  <div class="admin-card" style="padding:18px;border-right:4px solid #3b82f6;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">💰 الإيرادات</div>
    <div style="font-size:20px;font-weight:900;color:#3b82f6;margin-top:4px;">{{ number_format($stats['revenue']) }}</div>
  </div>
</div>

<!-- Assign Form -->
<div class="admin-card" style="padding:20px;margin-bottom:20px;">
  <h2 style="font-size:16px;font-weight:900;margin-bottom:16px;">➕ تعيين اشتراك جديد</h2>
  <form method="POST" action="/super-admin/subscriptions/assign" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
    @csrf
    <select name="shop_id" required style="padding:12px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);font-weight:700;">
      <option value="">— اختر المتجر —</option>
      @foreach(\App\Models\Shop::all() as $s)
      <option value="{{ $s->id }}">{{ $s->name }}</option>
      @endforeach
    </select>
    <select name="plan_id" required style="padding:12px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);font-weight:700;">
      <option value="">— اختر الخطة —</option>
      @foreach($plans as $p)
      <option value="{{ $p->id }}">{{ $p->name }} — {{ number_format($p->price) }} ريال</option>
      @endforeach
    </select>
    <select name="status" required style="padding:12px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);font-weight:700;">
      <option value="active">نشط</option>
      <option value="trial">تجريبي</option>
    </select>
    <input type="date" name="starts_at" required value="{{ now()->format('Y-m-d') }}" style="padding:12px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);">
    <input type="date" name="ends_at" required value="{{ now()->addMonth()->format('Y-m-d') }}" style="padding:12px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);">
    <input type="number" name="amount_paid" placeholder="المبلغ المدفوع" style="padding:12px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);">
    <button type="submit" style="background:linear-gradient(135deg,#fbbf24,#f97316);color:white;border:none;padding:12px;border-radius:10px;font-weight:900;cursor:pointer;font-family:inherit;">حفظ</button>
  </form>
</div>

<!-- Subscriptions List -->
<div class="admin-card">
  <div style="padding:18px 20px;border-bottom:1px solid var(--border);">
    <h2 style="font-size:16px;font-weight:900;">📋 كل الاشتراكات</h2>
  </div>
  @forelse($subscriptions as $sub)
  <div style="display:flex;align-items:center;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);flex-wrap:wrap;">
    <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#fbbf24,#f97316);display:flex;align-items:center;justify-content:center;color:white;font-weight:900;">🍯</div>
    <div style="flex:1;min-width:200px;">
      <div style="font-weight:900;font-size:14px;">{{ $sub->shop->name }}</div>
      <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">{{ $sub->plan->name }} — {{ $sub->plan->price > 0 ? number_format($sub->plan->price) . ' ريال' : 'مجاني' }}</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <span style="background:{{ $sub->status === 'active' ? '#dcfce7' : ($sub->status === 'trial' ? '#fef3c7' : '#fee2e2') }};color:{{ $sub->status === 'active' ? '#15803d' : ($sub->status === 'trial' ? '#b45309' : '#b91c1c') }};padding:4px 10px;border-radius:999px;font-size:11px;font-weight:900;">
        {{ $sub->status === 'active' ? '✅ نشط' : ($sub->status === 'trial' ? '⏳ تجريبي' : ($sub->status === 'expired' ? '❌ منتهي' : '⛔ ملغى')) }}
      </span>
      @if($sub->ends_at)
      <span style="background:#f1f5f9;color:#475569;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:800;">
        📅 {{ $sub->ends_at->format('Y-m-d') }} ({{ $sub->daysRemaining() }} يوم)
      </span>
      @endif
    </div>
    @if($sub->status !== 'cancelled')
    <form method="POST" action="/super-admin/subscriptions/{{ $sub->id }}/cancel" style="display:inline;">
      @csrf
      <button type="submit" onclick="return confirm('إلغاء الاشتراك؟')" style="background:#fee2e2;color:#b91c1c;border:none;padding:8px 14px;border-radius:8px;font-weight:800;font-size:12px;cursor:pointer;font-family:inherit;">إلغاء</button>
    </form>
    @endif
  </div>
  @empty
  <div style="padding:60px 20px;text-align:center;">
    <div style="font-size:56px;margin-bottom:12px;">💰</div>
    <div style="font-size:15px;font-weight:900;">لا توجد اشتراكات</div>
  </div>
  @endforelse
</div>

@if($subscriptions->hasPages())
<div style="margin-top:16px;">{{ $subscriptions->links() }}</div>
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

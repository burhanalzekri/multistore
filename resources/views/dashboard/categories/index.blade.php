@extends('layouts.app')
@section('title', 'التصنيفات')
@section('page-title', 'التصنيفات')
@section('page-subtitle', $categories->total() . ' تصنيف')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
  <div style="color:var(--text-muted);font-size:13px;font-weight:700;">
    <strong style="color:var(--text);">{{ $categories->total() }}</strong> تصنيف
  </div>
  <a href="/dashboard/categories/create" class="shine-btn shine-btn-primary">
    <i data-lucide="plus"></i> تصنيف جديد
  </a>
</div>

@if($categories->isEmpty())
<div class="shine-card">
  <div class="shine-empty">
    <div class="shine-empty-icon">📂</div>
    <h3>لا توجد تصنيفات</h3>
    <p>أنشئ تصنيفات لتنظيم منتجاتك</p>
    <a href="/dashboard/categories/create" class="shine-btn shine-btn-primary" style="display:inline-flex;">
      <i data-lucide="plus"></i> تصنيف أول
    </a>
  </div>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;">
  @foreach($categories as $c)
  <div class="shine-card" style="margin:0;">
    <div style="padding:18px;">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
        <div style="width:52px;height:52px;background:linear-gradient(135deg,#fef3c7,#fed7aa);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:26px;">
          {{ $c->icon ?? '📂' }}
        </div>
        <div style="flex:1;min-width:0;">
          <h3 style="margin:0 0 2px;font-size:15px;font-weight:900;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $c->name }}</h3>
          <p style="margin:0;font-size:12px;color:var(--text-muted);">{{ $c->products_count }} منتج</p>
        </div>
      </div>
      <div style="display:flex;gap:6px;">
        <a href="/dashboard/categories/{{ $c->id }}/edit" class="shine-btn" style="flex:1;background:#f1f5f9;color:#475569;justify-content:center;padding:9px;">
          <i data-lucide="edit-2" style="width:14px;height:14px;"></i> تعديل
        </a>
        <form method="POST" action="/dashboard/categories/{{ $c->id }}" onsubmit="return confirm('حذف التصنيف؟')" style="flex:1;">
          @csrf @method('DELETE')
          <button type="submit" class="shine-btn" style="width:100%;background:#fee2e2;color:#b91c1c;justify-content:center;padding:9px;">
            <i data-lucide="trash-2" style="width:14px;height:14px;"></i> حذف
          </button>
        </form>
      </div>
    </div>
  </div>
  @endforeach
</div>

@if($categories->hasPages())
<div style="margin-top:24px;">{{ $categories->links() }}</div>
@endif
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

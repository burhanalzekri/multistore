@extends('layouts.app')
@section('title', 'المنتجات')
@section('page-title', 'المنتجات')
@section('page-subtitle', $products->total() . ' منتج')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
  <div style="color:var(--text-muted);font-size:13px;font-weight:700;">
    <strong style="color:var(--text);">{{ $products->total() }}</strong> منتج
  </div>
  <a href="/dashboard/products/create" style="display:inline-flex;align-items:center;gap:6px;padding:10px 18px;background:linear-gradient(135deg,#fbbf24,#f97316);color:white;border-radius:10px;font-weight:800;font-size:13.5px;text-decoration:none;box-shadow:0 4px 12px rgba(245,158,11,0.3);">
    <i data-lucide="plus" style="width:16px;height:16px;"></i>
    منتج جديد
  </a>
</div>

@if($products->isEmpty())
<div class="admin-card">
  <div style="text-align:center;padding:50px 20px;">
    <div style="font-size:56px;margin-bottom:12px;opacity:0.7;">📦</div>
    <h3 style="font-size:16px;font-weight:900;margin-bottom:6px;">لا توجد منتجات بعد</h3>
    <p style="color:var(--text-muted);font-size:13px;margin-bottom:16px;">أضف منتجك الأول لتبدأ البيع</p>
    <a href="/dashboard/products/create" style="display:inline-flex;align-items:center;gap:6px;padding:12px 24px;background:linear-gradient(135deg,#fbbf24,#f97316);color:white;border-radius:12px;font-weight:800;text-decoration:none;">
      <i data-lucide="plus" style="width:18px;height:18px;"></i>
      أضف منتجًا
    </a>
  </div>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:16px;">
  @foreach($products as $p)
  <div class="admin-card" style="overflow:hidden;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)';" onmouseout="this.style.transform='';this.style.boxShadow='var(--shadow-sm)';">

    <!-- ⭐ الصورة — قابلة للضغط -->
    <div onclick="location='/dashboard/products/{{ $p->id }}/edit'"
         style="aspect-ratio:1;background:linear-gradient(135deg,#fef3c7,#fed7aa);position:relative;overflow:hidden;cursor:pointer;transition:opacity 0.3s;"
         onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">

      @if($p->image)
        <img src="{{ Storage::url($p->image) }}" alt="{{ $p->name }}" style="width:100%;height:100%;object-fit:cover;">
      @else
        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:64px;">📦</div>
      @endif

      @if($p->stock == 0)
      <div style="position:absolute;inset:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;">
        <span style="background:#ef4444;color:white;font-size:13px;font-weight:900;padding:6px 14px;border-radius:999px;">نفد</span>
      </div>
      @endif

      <!-- Hover hint -->
      <div style="position:absolute;inset:0;background:rgba(245,158,11,0.9);display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity 0.3s;color:white;font-weight:900;font-size:13px;"
           onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
        <div style="text-align:center;">
          <i data-lucide="edit-3" style="width:32px;height:32px;margin-bottom:4px;"></i>
          <div>تعديل المنتج</div>
        </div>
      </div>
    </div>

    <!-- ⭐ الاسم — قابل للضغط -->
    <div style="padding:16px;">
      <h3 onclick="location='/dashboard/products/{{ $p->id }}/edit'"
          style="margin:0 0 8px;font-size:14px;font-weight:900;color:var(--text);overflow:hidden;text-overflow:ellipsis;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;min-height:2.6rem;cursor:pointer;transition:color 0.2s;"
          onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='var(--text)'">
        {{ $p->name }}
      </h3>

      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
        <div style="color:var(--primary);font-weight:900;font-size:16px;">{{ number_format($p->price) }}</div>
        <span style="font-size:11px;font-weight:900;padding:3px 10px;border-radius:999px;{{ $p->stock > 10 ? 'background:#dcfce7;color:#15803d;' : ($p->stock > 0 ? 'background:#fef3c7;color:#b45309;' : 'background:#fee2e2;color:#b91c1c;') }}">
          {{ $p->stock }} قطعة
        </span>
      </div>

      <div style="display:flex;gap:6px;">
        <a href="/dashboard/products/{{ $p->id }}/edit"
           style="flex:1;background:#f1f5f9;color:#475569;text-align:center;padding:9px;border-radius:8px;font-weight:800;font-size:12px;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:4px;">
          <i data-lucide="edit-2" style="width:13px;height:13px;"></i>
          تعديل
        </a>
        <form method="POST" action="/dashboard/products/{{ $p->id }}" onsubmit="return confirm('حذف المنتج؟')" style="flex:1;">
          @csrf @method('DELETE')
          <button type="submit" style="width:100%;background:#fee2e2;color:#b91c1c;padding:9px;border-radius:8px;font-weight:800;font-size:12px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:4px;font-family:inherit;">
            <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
            حذف
          </button>
        </form>
      </div>
    </div>

  </div>
  @endforeach
</div>

@if($products->hasPages())
<div style="margin-top:24px;">{{ $products->links() }}</div>
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

@extends('layouts.app')

@section('title', 'إضافة منطقة شحن')

@push('styles')
<style>
  .szc-wrap{padding:24px;max-width:900px;margin:0 auto}
  .szc-header{display:flex;align-items:center;gap:16px;margin-bottom:24px;flex-wrap:wrap}
  .szc-back{display:inline-flex;align-items:center;gap:6px;padding:9px 15px;background:#fff;border:1px solid #e5e7eb;border-radius:11px;color:#374151;text-decoration:none;font-size:13px;font-weight:800}
  .szc-back:hover{background:#f9fafb}
  .szc-title{font-size:22px;font-weight:900;color:#111827;margin:0}
  .szc-sub{font-size:13px;color:#6b7280;margin-top:4px}
  .szc-card{background:#fff;border:1px solid #e5e7eb;border-radius:18px;padding:24px;box-shadow:0 4px 16px rgba(15,23,42,.05)}
  .szc-group{margin-bottom:20px}
  .szc-label{display:block;font-size:13px;font-weight:800;color:#374151;margin-bottom:8px}
  .szc-required{color:#ef4444;margin-right:4px}
  .szc-input{width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:11px;font-size:13.5px;font-family:inherit;font-weight:700;background:#fff;color:#111827;transition:all .2s;box-sizing:border-box}
  .szc-input:focus{outline:0;border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.15)}
  .szc-help{font-size:11.5px;color:#9ca3af;margin-top:6px;font-weight:600}
  .szc-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
  .szc-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:24px;padding-top:20px;border-top:1px solid #f3f4f6;flex-wrap:wrap}
  .szc-btn{padding:12px 24px;border-radius:12px;font-size:13px;font-weight:800;border:0;cursor:pointer;font-family:inherit;text-decoration:none;display:inline-flex;align-items:center;gap:7px;transition:all .2s}
  .szc-btn-save{background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;box-shadow:0 8px 20px rgba(245,158,11,.3)}
  .szc-btn-save:hover{transform:translateY(-2px);box-shadow:0 12px 28px rgba(245,158,11,.4)}
  .szc-btn-cancel{background:#f9fafb;color:#374151;border:1px solid #e5e7eb}
  .szc-btn-cancel:hover{background:#f3f4f6}
  .szc-toggle{display:flex;align-items:center;gap:10px;padding:12px;background:#f9fafb;border-radius:11px;cursor:pointer}
  .szc-toggle input{width:20px;height:20px;accent-color:#f59e0b;cursor:pointer}
  .szc-error{background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:10px;font-size:12.5px;font-weight:700;margin-bottom:16px;border:1px solid #fca5a5}
  .szc-error ul{margin:0;padding-right:16px}
  .szc-icon{font-size:16px}
  @media (max-width:640px){
    .szc-wrap{padding:16px}
    .szc-row{grid-template-columns:1fr}
    .szc-title{font-size:18px}
  }
</style>
@endpush

@section('content')
<div class="szc-wrap">

  {{-- Header --}}
  <div class="szc-header">
    <a href="{{ route('shipping-zones.index') }}" class="szc-back">→ عودة للقائمة</a>
    <div>
      <h1 class="szc-title">🚚 إضافة منطقة شحن</h1>
      <div class="szc-sub">أضف منطقة جديدة مع السعر والمدة المتوقعة</div>
    </div>
  </div>

  {{-- Errors --}}
  @if($errors->any())
    <div class="szc-error">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Form --}}
  <form method="POST" action="{{ route('shipping-zones.store') }}">
    @csrf

    <div class="szc-card">

      <div class="szc-row">
        <div class="szc-group">
          <label class="szc-label">
            <span class="szc-required">*</span>
            اسم المنطقة
          </label>
          <input type="text" name="name" class="szc-input" value="{{ old('name') }}" placeholder="مثال: صنعاء" required>
          <div class="szc-help">اسم المنطقة أو المدينة</div>
        </div>

        <div class="szc-group">
          <label class="szc-label">الرمز (Code)</label>
          <input type="text" name="code" class="szc-input" value="{{ old('code') }}" placeholder="مثال: sanaa" dir="ltr">
          <div class="szc-help">معرف فريد (اختياري)</div>
        </div>
      </div>

      <div class="szc-row">
        <div class="szc-group">
          <label class="szc-label">
            <span class="szc-required">*</span>
            سعر الشحن (ر.ي)
          </label>
          <input type="number" name="fee" class="szc-input" value="{{ old('fee', 0) }}" min="0" step="1" required>
          <div class="szc-help">اتركه 0 للمناطق المجانية</div>
        </div>

        <div class="szc-group">
          <label class="szc-label">شحن مجاني عند تجاوز (ر.ي)</label>
          <input type="number" name="free_over" class="szc-input" value="{{ old('free_over') }}" min="0" step="1" placeholder="مثال: 100000">
          <div class="szc-help">اتركه فارغاً إذا لا يوجد شحن مجاني</div>
        </div>
      </div>

      <div class="szc-group" style="background:#fff7ed;border:2px dashed #fdba74;border-radius:14px;padding:16px;">
        <label class="szc-label" style="color:#9a3412;">
          🚛 الحد الأقصى للطلب التلقائي (ر.ي)
        </label>
        <input type="number" name="max_order_amount" class="szc-input" value="{{ old('max_order_amount') }}" min="0" step="1" placeholder="مثال: 100000">
        <div class="szc-help" style="color:#c2410c;">
          <strong>📞 عند ترك الحقل فارغاً:</strong> التسعير تلقائي (السعر العادي لكل الطلبات)<br>
          <strong>✅ عند إدخال مبلغ:</strong> الطلبات التي تتجاوز هذا المبلغ — سيتواصل معك فريقك لحساب تكلفة الشحن الإضافية
        </div>
      </div>

      <div class="szc-row">
        <div class="szc-group">
          <label class="szc-label">أدنى مبلغ للطلب (ر.ي)</label>
          <input type="number" name="min_order" class="szc-input" value="{{ old('min_order', 0) }}" min="0" step="1">
          <div class="szc-help">أدنى قيمة للطلب في هذه المنطقة</div>
        </div>

        <div class="szc-group">
          <label class="szc-label">المدة المتوقعة</label>
          <input type="text" name="eta" class="szc-input" value="{{ old('eta') }}" placeholder="مثال: 1-2 يوم">
          <div class="szc-help">مدة التوصيل التقريبية</div>
        </div>
      </div>

      <div class="szc-row">
        <div class="szc-group">
          <label class="szc-label">ترتيب العرض</label>
          <input type="number" name="sort_order" class="szc-input" value="{{ old('sort_order', 999) }}" min="0" step="1">
          <div class="szc-help">الأصغر يظهر أولاً</div>
        </div>

        <div class="szc-group">
          <label class="szc-label">الحالة</label>
          <label class="szc-toggle">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <span style="font-size:13px;font-weight:800;color:#374151;">✅ المنطقة نشطة</span>
          </label>
        </div>
      </div>

    </div>

    <div class="szc-actions">
      <a href="{{ route('shipping-zones.index') }}" class="szc-btn szc-btn-cancel">إلغاء</a>
      <button type="submit" class="szc-btn szc-btn-save">
        <i data-lucide="save" style="width:16px;height:16px;"></i>
        حفظ المنطقة
      </button>
    </div>

  </form>

</div>
@endsection

@push('scripts')
<script>
  if (window.lucide) lucide.createIcons();
</script>
@endpush

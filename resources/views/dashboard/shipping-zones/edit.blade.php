@extends('layouts.app')

@section('title', 'تعديل منطقة شحن')

@push('styles')
<style>
  .sze-wrap{padding:24px;max-width:900px;margin:0 auto}
  .sze-header{display:flex;align-items:center;gap:16px;margin-bottom:24px;flex-wrap:wrap}
  .sze-back{display:inline-flex;align-items:center;gap:6px;padding:9px 15px;background:#fff;border:1px solid #e5e7eb;border-radius:11px;color:#374151;text-decoration:none;font-size:13px;font-weight:800}
  .sze-back:hover{background:#f9fafb}
  .sze-title{font-size:22px;font-weight:900;color:#111827;margin:0}
  .sze-sub{font-size:13px;color:#6b7280;margin-top:4px}
  .sze-card{background:#fff;border:1px solid #e5e7eb;border-radius:18px;padding:24px;box-shadow:0 4px 16px rgba(15,23,42,.05)}
  .sze-group{margin-bottom:20px}
  .sze-label{display:block;font-size:13px;font-weight:800;color:#374151;margin-bottom:8px}
  .sze-required{color:#ef4444;margin-right:4px}
  .sze-input{width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:11px;font-size:13.5px;font-family:inherit;font-weight:700;background:#fff;color:#111827;transition:all .2s;box-sizing:border-box}
  .sze-input:focus{outline:0;border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.15)}
  .sze-help{font-size:11.5px;color:#9ca3af;margin-top:6px;font-weight:600}
  .sze-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
  .sze-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:24px;padding-top:20px;border-top:1px solid #f3f4f6;flex-wrap:wrap;align-items:center}
  .sze-btn{padding:12px 24px;border-radius:12px;font-size:13px;font-weight:800;border:0;cursor:pointer;font-family:inherit;text-decoration:none;display:inline-flex;align-items:center;gap:7px;transition:all .2s}
  .sze-btn-save{background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;box-shadow:0 8px 20px rgba(245,158,11,.3)}
  .sze-btn-save:hover{transform:translateY(-2px);box-shadow:0 12px 28px rgba(245,158,11,.4)}
  .sze-btn-cancel{background:#f9fafb;color:#374151;border:1px solid #e5e7eb}
  .sze-btn-cancel:hover{background:#f3f4f6}
  .sze-btn-delete{background:#fef2f2;color:#991b1b;border:1px solid #fca5a5;margin-left:auto}
  .sze-btn-delete:hover{background:#fee2e2}
  .sze-toggle{display:flex;align-items:center;gap:10px;padding:12px;background:#f9fafb;border-radius:11px;cursor:pointer}
  .sze-toggle input{width:20px;height:20px;accent-color:#f59e0b;cursor:pointer}
  .sze-error{background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:10px;font-size:12.5px;font-weight:700;margin-bottom:16px;border:1px solid #fca5a5}
  .sze-error ul{margin:0;padding-right:16px}
  .sze-info{background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1px solid #93c5fd;color:#1e40af;padding:12px 16px;border-radius:12px;font-size:12.5px;font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:8px}
  @media (max-width:640px){
    .sze-wrap{padding:16px}
    .sze-row{grid-template-columns:1fr}
    .sze-title{font-size:18px}
  }
</style>
@endpush

@section('content')
<div class="sze-wrap">

  {{-- Header --}}
  <div class="sze-header">
    <a href="{{ route('shipping-zones.index') }}" class="sze-back">→ عودة للقائمة</a>
    <div>
      <h1 class="sze-title">✏️ تعديل منطقة: {{ $zone->name }}</h1>
      <div class="sze-sub">تحديث بيانات المنطقة والسعر</div>
    </div>
  </div>

  {{-- Info --}}
  <div class="sze-info">
    <i data-lucide="info" style="width:16px;height:16px;"></i>
    المنطقة الحالية: <strong>{{ $zone->name }}</strong> — {{ $zone->fee > 0 ? number_format($zone->fee) . ' ر.ي' : 'مجاني' }}
  </div>

  {{-- Errors --}}
  @if($errors->any())
    <div class="sze-error">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Form --}}
  <form method="POST" action="{{ route('shipping-zones.update', $zone->id) }}">
    @csrf
    @method('PUT')

    <div class="sze-card">

      <div class="sze-row">
        <div class="sze-group">
          <label class="sze-label">
            <span class="sze-required">*</span>
            اسم المنطقة
          </label>
          <input type="text" name="name" class="sze-input" value="{{ old('name', $zone->name) }}" required>
          <div class="sze-help">اسم المنطقة أو المدينة</div>
        </div>

        <div class="sze-group">
          <label class="sze-label">الرمز (Code)</label>
          <input type="text" name="code" class="sze-input" value="{{ old('code', $zone->code) }}" dir="ltr">
          <div class="sze-help">معرف فريد (اختياري)</div>
        </div>
      </div>

      <div class="sze-row">
        <div class="sze-group">
          <label class="sze-label">
            <span class="sze-required">*</span>
            سعر الشحن (ر.ي)
          </label>
          <input type="number" name="fee" class="sze-input" value="{{ old('fee', (float)$zone->fee) }}" min="0" step="1" required>
          <div class="sze-help">اتركه 0 للمناطق المجانية</div>
        </div>

        <div class="sze-group">
          <label class="sze-label">شحن مجاني عند تجاوز (ر.ي)</label>
          <input type="number" name="free_over" class="sze-input" value="{{ old('free_over', $zone->free_over ? (float)$zone->free_over : '') }}" min="0" step="1" placeholder="مثال: 100000">
          <div class="sze-help">اتركه فارغاً إذا لا يوجد شحن مجاني</div>
        </div>
      </div>

      <div class="sze-group" style="background:#fff7ed;border:2px dashed #fdba74;border-radius:14px;padding:16px;">
        <label class="sze-label" style="color:#9a3412;">
          🚛 الحد الأقصى للطلب التلقائي (ر.ي)
        </label>
        <input type="number" name="max_order_amount" class="sze-input" value="{{ old('max_order_amount', $zone->max_order_amount ? (float)$zone->max_order_amount : '') }}" min="0" step="1" placeholder="مثال: 100000">
        <div class="sze-help" style="color:#c2410c;">
          <strong>📞 للطلبات الكبيرة:</strong> عندما يتجاوز الطلب هذا المبلغ — سيتواصل معك فريقك لحساب تكلفة الشحن الإضافية
        </div>
      </div>

      <div class="sze-row">
        <div class="sze-group">
          <label class="sze-label">أدنى مبلغ للطلب (ر.ي)</label>
          <input type="number" name="min_order" class="sze-input" value="{{ old('min_order', (float)($zone->min_order ?? 0)) }}" min="0" step="1">
          <div class="sze-help">أدنى قيمة للطلب في هذه المنطقة</div>
        </div>

        <div class="sze-group">
          <label class="sze-label">المدة المتوقعة</label>
          <input type="text" name="eta" class="sze-input" value="{{ old('eta', $zone->eta) }}" placeholder="مثال: 1-2 يوم">
          <div class="sze-help">مدة التوصيل التقريبية</div>
        </div>
      </div>

      <div class="sze-row">
        <div class="sze-group">
          <label class="sze-label">ترتيب العرض</label>
          <input type="number" name="sort_order" class="sze-input" value="{{ old('sort_order', $zone->sort_order) }}" min="0" step="1">
          <div class="sze-help">الأصغر يظهر أولاً</div>
        </div>

        <div class="sze-group">
          <label class="sze-label">الحالة</label>
          <label class="sze-toggle">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $zone->is_active) ? 'checked' : '' }}>
            <span style="font-size:13px;font-weight:800;color:#374151;">✅ المنطقة نشطة</span>
          </label>
        </div>
      </div>

    </div>

    <div class="sze-actions">
      <a href="{{ route('shipping-zones.index') }}" class="sze-btn sze-btn-cancel">إلغاء</a>
      <button type="submit" class="sze-btn sze-btn-save">
        <i data-lucide="save" style="width:16px;height:16px;"></i>
        حفظ التعديلات
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

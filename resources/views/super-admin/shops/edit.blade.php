@extends('layouts.super-admin')

@section('title', 'تعديل متجر — ' . $shop->name)
@section('page-title', '✏️ تعديل المتجر')
@section('page-subtitle', $shop->name)

@section('content')

@if($errors->any())
  <div style="background:#fef2f2;color:#991b1b;padding:14px 18px;border-radius:14px;margin-bottom:16px;font-weight:700;font-size:13px;border:1px solid #fecaca;">
    @foreach($errors->all() as $e)
      <div>• {{ $e }}</div>
    @endforeach
  </div>
@endif

<form method="POST" action="{{ route('super-admin.shops.update', $shop->id) }}" enctype="multipart/form-data" class="admin-card" style="padding:24px;">
  @csrf
  @method('PUT')

  <div style="margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #e2e8f0;">
    <h3 style="margin:0;font-size:16px;font-weight:900;color:#0f172a;">📋 المعلومات الأساسية</h3>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;margin-bottom:24px;">

    <label style="font-size:12px;font-weight:800;color:#475569;">
      اسم المتجر *
      <input name="name" type="text" value="{{ old('name', $shop->name) }}" required
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;">
    </label>

    <label style="font-size:12px;font-weight:800;color:#475569;">
      الرابط (slug)
      <input name="slug" type="text" value="{{ old('slug', $shop->slug) }}"
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;">
    </label>

    <label style="font-size:12px;font-weight:800;color:#475569;">
      رقم الهاتف
      <input name="phone" type="text" value="{{ old('phone', $shop->phone) }}"
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;">
    </label>

    <label style="font-size:12px;font-weight:800;color:#475569;">
      واتساب
      <input name="whatsapp" type="text" value="{{ old('whatsapp', $shop->whatsapp) }}"
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;">
    </label>

    <label style="font-size:12px;font-weight:800;color:#475569;">
      البريد الإلكتروني
      <input name="email" type="email" value="{{ old('email', $shop->email) }}"
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;">
    </label>

    <label style="font-size:12px;font-weight:800;color:#475569;">
      الحالة *
      <select name="status" required
              style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;background:#fff;">
        <option value="active"    {{ old('status', $shop->status) === 'active' ? 'selected' : '' }}>🟢 نشط</option>
        <option value="trial"     {{ old('status', $shop->status) === 'trial' ? 'selected' : '' }}>🟡 تجريبي</option>
        <option value="suspended" {{ old('status', $shop->status) === 'suspended' ? 'selected' : '' }}>🔴 موقوف</option>
      </select>
    </label>

  </div>

  <div style="margin-bottom:20px;padding-bottom:16px;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;padding-top:20px;">
    <h3 style="margin:0;font-size:16px;font-weight:900;color:#0f172a;">📍 العنوان والموقع</h3>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;margin-bottom:24px;">

    <label style="font-size:12px;font-weight:800;color:#475569;">
      العنوان
      <input name="address" type="text" value="{{ old('address', $shop->address) }}"
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;">
    </label>

    <label style="font-size:12px;font-weight:800;color:#475569;">
      المدينة
      <input name="city" type="text" value="{{ old('city', $shop->city) }}"
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;">
    </label>

    <label style="font-size:12px;font-weight:800;color:#475569;">
      الدولة
      <input name="country" type="text" value="{{ old('country', $shop->country ?? 'اليمن') }}"
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;">
    </label>

    <label style="font-size:12px;font-weight:800;color:#475569;">
      اللون الأساسي
      <input name="primary_color" type="color" value="{{ old('primary_color', $shop->primary_color ?? '#f59e0b') }}"
             style="display:block;width:100%;height:46px;margin-top:6px;padding:4px;border:2px solid #e2e8f0;border-radius:12px;">
    </label>

  </div>

  <div style="margin-bottom:20px;padding-bottom:16px;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;padding-top:20px;">
    <h3 style="margin:0;font-size:16px;font-weight:900;color:#0f172a;">🖼️ الشعار والوصف</h3>
  </div>

  <div style="display:grid;gap:16px;margin-bottom:24px;">

    <label style="font-size:12px;font-weight:800;color:#475569;">
      الوصف
      <textarea name="description" rows="3"
                style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;resize:vertical;">{{ old('description', $shop->description) }}</textarea>
    </label>

    <div>
      <label style="font-size:12px;font-weight:800;color:#475569;">
        الشعار (اختياري — يرفع على السيرفر)
        <input name="logo" type="file" accept="image/*"
               style="display:block;width:100%;margin-top:6px;padding:9px 12px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:13px;">
      </label>

      @if($shop->logo)
        <div style="margin-top:10px;display:flex;align-items:center;gap:10px;font-size:12px;color:#64748b;">
          <span>الشعار الحالي:</span>
          <img src="{{ Storage::url($shop->logo) }}" alt="logo"
               style="width:56px;height:56px;border-radius:12px;object-fit:cover;border:1px solid #e2e8f0;">
        </div>
      @endif
    </div>

  </div>

  {{-- أزرار --}}
  <div style="display:flex;gap:10px;padding-top:20px;border-top:1px solid #e2e8f0;">
    <a href="/super-admin/shops"
       style="padding:12px 24px;border-radius:12px;background:#f1f5f9;color:#334155;text-decoration:none;font-weight:900;font-size:14px;">
      إلغاء
    </a>
    <button type="submit"
            style="flex:1;padding:12px 24px;border-radius:12px;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;border:0;font-weight:900;font-size:14px;cursor:pointer;font-family:inherit;box-shadow:0 8px 20px rgba(124,58,237,.3);">
      💾 حفظ التعديلات
    </button>
  </div>

</form>

@endsection

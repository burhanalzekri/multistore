@extends('layouts.super-admin')
@section('title', 'إضافة مستخدم')
@section('page-title', '➕ إضافة مستخدم جديد')
@section('page-subtitle', 'أنشئ حساباً جديداً للوصول إلى المنصة')

@section('content')

<div style="margin-bottom:16px;">
  <a href="/super-admin/users" style="padding:10px 18px;border-radius:12px;background:#f1f5f9;color:#334155;text-decoration:none;font-weight:900;font-size:13px;">← رجوع</a>
</div>

@if($errors->any())
  <div style="background:#fef2f2;color:#991b1b;padding:14px 18px;border-radius:14px;margin-bottom:16px;font-size:13px;font-weight:700;border:1px solid #fecaca;">
    @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
  </div>
@endif

<form method="POST" action="/super-admin/users" class="admin-card" style="padding:24px;max-width:700px;">
  @csrf

  <div style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #e2e8f0;">
    <h3 style="margin:0;font-size:15px;font-weight:900;color:#0f172a;">📋 معلومات المستخدم</h3>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;margin-bottom:24px;">

    <label style="font-size:12px;font-weight:800;color:#475569;">
      الاسم الكامل *
      <input name="name" type="text" value="{{ old('name') }}" required
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;">
    </label>

    <label style="font-size:12px;font-weight:800;color:#475569;">
      البريد الإلكتروني *
      <input name="email" type="email" value="{{ old('email') }}" required
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;direction:ltr;">
    </label>

    <label style="font-size:12px;font-weight:800;color:#475569;">
      رقم الهاتف
      <input name="phone" type="text" value="{{ old('phone') }}"
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;direction:ltr;">
    </label>

    <label style="font-size:12px;font-weight:800;color:#475569;">
      الدور *
      <select name="role" id="role-select" required onchange="toggleShopField()"
              style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;background:#fff;">
        <option value="">— اختر —</option>
        <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>👑 مدير المنصة</option>
        <option value="shop_admin"  {{ old('role') === 'shop_admin' ? 'selected' : '' }}>🏪 مدير متجر</option>
        <option value="staff"       {{ old('role') === 'staff' ? 'selected' : '' }}>👤 موظف</option>
      </select>
    </label>

    <label id="shop-field" style="font-size:12px;font-weight:800;color:#475569;grid-column:1/-1;display:none;">
      المتجر *
      <select name="shop_id"
              style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;background:#fff;">
        <option value="">— اختر المتجر —</option>
        @foreach($shops as $s)
          <option value="{{ $s->id }}" {{ old('shop_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
        @endforeach
      </select>
    </label>

  </div>

  <div style="margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #e2e8f0;padding-top:8px;">
    <h3 style="margin:0;font-size:15px;font-weight:900;color:#0f172a;">🔐 كلمة المرور</h3>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;margin-bottom:24px;">

    <label style="font-size:12px;font-weight:800;color:#475569;">
      كلمة المرور *
      <input name="password" type="password" required minlength="6"
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;">
    </label>

    <label style="font-size:12px;font-weight:800;color:#475569;">
      تأكيد كلمة المرور *
      <input name="password_confirmation" type="password" required minlength="6"
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;">
    </label>

  </div>

  <div style="display:flex;gap:10px;padding-top:16px;border-top:1px solid #e2e8f0;">
    <a href="/super-admin/users"
       style="padding:12px 24px;border-radius:12px;background:#f1f5f9;color:#334155;text-decoration:none;font-weight:900;font-size:14px;">
      إلغاء
    </a>
    <button type="submit"
            style="flex:1;padding:12px;border-radius:12px;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;border:0;font-weight:900;font-size:14px;cursor:pointer;font-family:inherit;box-shadow:0 8px 20px rgba(124,58,237,.3);">
      💾 إنشاء المستخدم
    </button>
  </div>

</form>

<script>
function toggleShopField() {
  const role = document.getElementById('role-select').value;
  const field = document.getElementById('shop-field');
  field.style.display = (role === 'shop_admin' || role === 'staff') ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', toggleShopField);
</script>

@endsection

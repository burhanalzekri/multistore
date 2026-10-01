@extends('layouts.super-admin')
@section('title', 'إعادة تعيين كلمة المرور')
@section('page-title', '🔑 إعادة تعيين كلمة المرور')
@section('page-subtitle', $user->name)

@section('content')

<div style="margin-bottom:16px;">
  <a href="/super-admin/users/{{ $user->id }}" style="padding:10px 18px;border-radius:12px;background:#f1f5f9;color:#334155;text-decoration:none;font-weight:900;font-size:13px;">← رجوع</a>
</div>

<div class="admin-card" style="padding:20px;margin-bottom:16px;background:#fffbeb;border:1px solid #fde68a;">
  <div style="display:flex;align-items:center;gap:12px;">
    <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:900;flex-shrink:0;">
      {{ mb_substr($user->name, 0, 1) }}
    </div>
    <div style="flex:1;">
      <div style="font-weight:900;font-size:15px;color:#0f172a;">{{ $user->name }}</div>
      <div style="font-size:12px;color:#64748b;">{{ $user->email }}</div>
    </div>
  </div>
</div>

@if($errors->any())
  <div style="background:#fef2f2;color:#991b1b;padding:14px 18px;border-radius:14px;margin-bottom:16px;font-size:13px;font-weight:700;border:1px solid #fecaca;">
    @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
  </div>
@endif

<form method="POST" action="/super-admin/users/{{ $user->id }}/reset-password" class="admin-card" style="padding:24px;max-width:600px;">
  @csrf

  <div style="background:#fef2f2;color:#991b1b;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:13px;font-weight:700;">
    ⚠️ تنبيه: سيتم تغيير كلمة مرور هذا المستخدم فوراً. أبلغه بالكلمة الجديدة بطريقة آمنة.
  </div>

  <div style="display:grid;gap:16px;margin-bottom:24px;">

    <label style="font-size:12px;font-weight:800;color:#475569;">
      كلمة المرور الجديدة *
      <input name="password" type="text" required minlength="6"
             value="{{ old('password') }}"
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;direction:ltr;">
      <div style="font-size:11px;color:#94a3b8;margin-top:4px;">6 أحرف على الأقل</div>
    </label>

    <label style="font-size:12px;font-weight:800;color:#475569;">
      تأكيد كلمة المرور *
      <input name="password_confirmation" type="text" required minlength="6"
             value="{{ old('password_confirmation') }}"
             style="display:block;width:100%;margin-top:6px;padding:11px 14px;border:2px solid #e2e8f0;border-radius:12px;font-family:inherit;font-size:14px;direction:ltr;">
    </label>

  </div>

  <div style="display:flex;gap:10px;padding-top:16px;border-top:1px solid #e2e8f0;">
    <a href="/super-admin/users/{{ $user->id }}"
       style="padding:12px 24px;border-radius:12px;background:#f1f5f9;color:#334155;text-decoration:none;font-weight:900;font-size:14px;">
      إلغاء
    </a>
    <button type="submit"
            style="flex:1;padding:12px;border-radius:12px;background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;border:0;font-weight:900;font-size:14px;cursor:pointer;font-family:inherit;box-shadow:0 8px 20px rgba(220,38,38,.3);"
            onclick="return confirm('هل أنت متأكد؟ سيتم تغيير كلمة المرور فوراً.')">
      🔑 إعادة تعيين كلمة المرور
    </button>
  </div>

</form>

@endsection

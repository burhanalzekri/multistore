@extends('layouts.app')
@section('title', 'تغيير كلمة المرور')
@section('page-title', '🔒 تغيير كلمة المرور')
@section('page-subtitle', 'حدّث كلمة مرور حسابك')

@section('content')

<div style="max-width:640px;margin:0 auto;">

  {{-- ═══ بطاقة معلومات الحساب ═══ --}}
  <div class="admin-card" style="padding:22px;margin-bottom:16px;">
    <div style="display:flex;align-items:center;gap:14px;">

      <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#fbbf24,#f97316);color:#fff;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:900;flex-shrink:0;box-shadow:0 8px 20px rgba(249,115,22,.3);">
        {{ mb_substr(auth()->user()->name ?? '?', 0, 1) }}
      </div>

      <div style="flex:1;min-width:0;">
        <div style="font-weight:900;font-size:15px;color:var(--text);">{{ auth()->user()->name }}</div>
        <div style="font-size:12px;color:var(--text-muted);margin-top:3px;direction:ltr;text-align:right;">{{ auth()->user()->email }}</div>
      </div>

    </div>
  </div>

  @if($errors->any())
    <div style="background:#fef2f2;color:#991b1b;padding:14px 18px;border-radius:14px;margin-bottom:16px;font-size:13px;font-weight:700;border:1px solid #fecaca;">
      @foreach($errors->all() as $e)
        <div style="display:flex;align-items:center;gap:8px;">
          <i data-lucide="alert-circle" style="width:16px;height:16px;"></i>
          {{ $e }}
        </div>
      @endforeach
    </div>
  @endif

  {{-- ═══ نموذج التغيير ═══ --}}
  <form method="POST" action="{{ route('profile.password.update') }}" class="admin-card" style="padding:24px;">
    @csrf

    <div style="margin-bottom:20px;padding-bottom:14px;border-bottom:1px solid var(--border);">
      <h3 style="margin:0;font-size:15px;font-weight:900;color:var(--text);">🔐 بيانات كلمة المرور</h3>
      <p style="margin:6px 0 0;font-size:12px;color:var(--text-muted);">يجب إدخال كلمة المرور الحالية للتأكد من هويتك</p>
    </div>

    <div style="display:grid;gap:18px;margin-bottom:24px;">

      <label style="font-size:12px;font-weight:800;color:var(--text-muted);">
        🔐 كلمة المرور الحالية *
        <input name="current_password" type="password" required autocomplete="current-password"
               placeholder="أدخل كلمة المرور الحالية"
               style="display:block;width:100%;margin-top:8px;padding:12px 14px;border:2px solid var(--border);border-radius:12px;font-family:inherit;font-size:14px;background:var(--surface);color:var(--text);transition:border-color .2s;"
               onfocus="this.style.borderColor='#f97316'"
               onblur="this.style.borderColor='var(--border)'">
      </label>

      <label style="font-size:12px;font-weight:800;color:var(--text-muted);">
        🆕 كلمة المرور الجديدة *
        <input name="password" type="password" required minlength="6" autocomplete="new-password"
               placeholder="6 أحرف على الأقل"
               style="display:block;width:100%;margin-top:8px;padding:12px 14px;border:2px solid var(--border);border-radius:12px;font-family:inherit;font-size:14px;background:var(--surface);color:var(--text);transition:border-color .2s;"
               onfocus="this.style.borderColor='#f97316'"
               onblur="this.style.borderColor='var(--border)'">
        <div style="font-size:11px;color:var(--text-muted);margin-top:6px;">الحد الأدنى: 6 أحرف</div>
      </label>

      <label style="font-size:12px;font-weight:800;color:var(--text-muted);">
        ✅ تأكيد كلمة المرور الجديدة *
        <input name="password_confirmation" type="password" required minlength="6" autocomplete="new-password"
               placeholder="أعد إدخال كلمة المرور الجديدة"
               style="display:block;width:100%;margin-top:8px;padding:12px 14px;border:2px solid var(--border);border-radius:12px;font-family:inherit;font-size:14px;background:var(--surface);color:var(--text);transition:border-color .2s;"
               onfocus="this.style.borderColor='#f97316'"
               onblur="this.style.borderColor='var(--border)'">
      </label>

    </div>

    <div style="display:flex;gap:10px;padding-top:16px;border-top:1px solid var(--border);">
      <button type="submit"
              style="flex:1;padding:14px;border-radius:14px;background:linear-gradient(135deg,#fbbf24,#f97316);color:#fff;border:0;font-weight:900;font-size:14px;cursor:pointer;font-family:inherit;box-shadow:0 8px 20px rgba(249,115,22,.3);display:flex;align-items:center;justify-content:center;gap:8px;">
        <i data-lucide="save" style="width:18px;height:18px;"></i>
        حفظ كلمة المرور الجديدة
      </button>
    </div>

    <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);text-align:center;">
      <div style="font-size:12px;color:var(--text-muted);">
        نسيت كلمة المرور الحالية؟
        <a href="/contact" style="color:#7c3aed;font-weight:800;text-decoration:none;margin-inline-start:4px;">تواصل مع الدعم</a>
      </div>
    </div>

  </form>

</div>

@endsection

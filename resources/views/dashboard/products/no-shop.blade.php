@extends('layouts.dashboard')

@section('title', 'اختر متجراً أولاً')

@section('content')
<div style="max-width:640px;margin:60px auto;padding:20px;font-family:system-ui,-apple-system,sans-serif;direction:rtl;">
  <div style="background:linear-gradient(135deg,#fff 0%,#fff8f0 100%);border-radius:20px;padding:40px 30px;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,.08);border:1px solid #ffe0b2;">
    
    <div style="font-size:72px;line-height:1;margin-bottom:16px;">🎭</div>
    
    <h2 style="margin:0 0 12px;color:#1a1a1a;font-size:22px;font-weight:800;">
      يجب اختيار متجر أولاً
    </h2>
    
    <p style="margin:0 0 8px;color:#666;font-size:15px;line-height:1.7;">
      أنت الآن كمدير منصة. لإضافة منتجات أو إدارة المحتوى،
      يجب أن تدخل إلى متجر محدد.
    </p>
    
    <p style="margin:0 0 28px;color:#999;font-size:13px;">
      💡 اختر متجراً من قائمة المتاجر، ثم اضغط زر <strong style="color:#f97316;">"🎭 ادخل كتاجر"</strong>
    </p>
    
    <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
      <a href="/super-admin/shops" style="background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;text-decoration:none;padding:14px 26px;border-radius:12px;font-weight:700;font-size:15px;box-shadow:0 4px 14px rgba(249,115,22,.35);">
        🏪 اختر متجراً الآن
      </a>
      <a href="/super-admin/shops/create" style="background:#fff;color:#f97316;text-decoration:none;padding:14px 26px;border-radius:12px;font-weight:700;font-size:15px;border:2px solid #f97316;">
        ➕ إنشاء متجر جديد
      </a>
    </div>
    
    <div style="margin-top:30px;padding-top:20px;border-top:1px dashed #ffe0b2;">
      <a href="/dashboard" style="color:#666;text-decoration:none;font-size:14px;">← رجوع للوحة التحكم</a>
    </div>
    
  </div>
</div>
@endsection

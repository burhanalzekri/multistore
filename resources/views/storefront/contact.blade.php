@extends('layouts.app')

@section('title', 'تواصل معنا')
@section('page-title', '💬 تواصل معنا')
@section('page-subtitle', 'نحن هنا لمساعدتك')

@section('content')

@php
  $__shop = $shop ?? null;
  $__settings = ($__shop && is_array($__shop->settings ?? null)) ? $__shop->settings : [];
  $__wa = preg_replace('/[^0-9]/','', $__shop->whatsapp ?? '');
  if ($__wa && !str_starts_with($__wa, '967') && strlen($__wa) <= 10) {
      $__wa = '967' . ltrim($__wa, '0');
  }
  $__phone = $__shop->phone ?? null;
  $__email = $__shop->email ?? null;
  $__fb = $__settings['facebook'] ?? null;
  $__tg = $__settings['telegram'] ?? null;
@endphp

<div style="max-width:640px;margin:0 auto;display:flex;flex-direction:column;gap:20px;">

  {{-- Header --}}
  <div style="background:linear-gradient(135deg,#e96b2c,#f59e0b);color:#fff;border-radius:24px;padding:32px;text-align:center;box-shadow:0 20px 45px -12px rgba(233,107,44,.4);">
    <div style="width:72px;height:72px;border-radius:50%;background:rgba(255,255,255,.2);display:grid;place-items:center;margin:0 auto 16px;font-size:36px;">💬</div>
    <h1 style="font-size:24px;font-weight:900;margin:0 0 8px;">نسعد بخدمتك</h1>
    <p style="font-size:14px;opacity:.9;margin:0;line-height:1.7;">تواصل معنا عبر أي وسيلة تناسبك — نرد عليك في أسرع وقت.</p>
  </div>

  {{-- Contact Methods --}}
  <div style="display:grid;gap:12px;">

    @if($__wa)
      <a href="https://wa.me/{{ $__wa }}" target="_blank" rel="noopener"
         style="background:#fff;border:1.5px solid #e5e7eb;border-radius:18px;padding:20px;display:flex;align-items:center;gap:16px;text-decoration:none;color:inherit;transition:.25s;box-shadow:0 4px 12px rgba(0,0,0,.04);">
        <div style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,#25D366,#128C7E);color:#fff;display:grid;place-items:center;font-size:24px;flex-shrink:0;box-shadow:0 8px 20px rgba(37,211,102,.3);">💬</div>
        <div style="flex:1;">
          <div style="font-size:15px;font-weight:900;margin-bottom:2px;">واتساب</div>
          <div style="font-size:12px;color:#666;">تواصل فوري عبر واتساب</div>
        </div>
        <div style="color:#999;font-size:20px;">‹</div>
      </a>
    @endif

    @if($__phone)
      <a href="tel:{{ $__phone }}"
         style="background:#fff;border:1.5px solid #e5e7eb;border-radius:18px;padding:20px;display:flex;align-items:center;gap:16px;text-decoration:none;color:inherit;transition:.25s;box-shadow:0 4px 12px rgba(0,0,0,.04);">
        <div style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,#e96b2c,#d9541a);color:#fff;display:grid;place-items:center;font-size:24px;flex-shrink:0;box-shadow:0 8px 20px rgba(233,107,44,.3);">📞</div>
        <div style="flex:1;">
          <div style="font-size:15px;font-weight:900;margin-bottom:2px;">اتصال هاتفي</div>
          <div style="font-size:12px;color:#666;" dir="ltr">{{ $__phone }}</div>
        </div>
        <div style="color:#999;font-size:20px;">‹</div>
      </a>
    @endif

    @if($__email)
      <a href="mailto:{{ $__email }}"
         style="background:#fff;border:1.5px solid #e5e7eb;border-radius:18px;padding:20px;display:flex;align-items:center;gap:16px;text-decoration:none;color:inherit;transition:.25s;box-shadow:0 4px 12px rgba(0,0,0,.04);">
        <div style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);color:#fff;display:grid;place-items:center;font-size:24px;flex-shrink:0;box-shadow:0 8px 20px rgba(59,130,246,.3);">📧</div>
        <div style="flex:1;">
          <div style="font-size:15px;font-weight:900;margin-bottom:2px;">البريد الإلكتروني</div>
          <div style="font-size:12px;color:#666;" dir="ltr">{{ $__email }}</div>
        </div>
        <div style="color:#999;font-size:20px;">‹</div>
      </a>
    @endif

    @if($__fb)
      <a href="{{ $__fb }}" target="_blank" rel="noopener"
         style="background:#fff;border:1.5px solid #e5e7eb;border-radius:18px;padding:20px;display:flex;align-items:center;gap:16px;text-decoration:none;color:inherit;transition:.25s;box-shadow:0 4px 12px rgba(0,0,0,.04);">
        <div style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,#1877F2,#0d5dbf);color:#fff;display:grid;place-items:center;font-size:24px;flex-shrink:0;box-shadow:0 8px 20px rgba(24,119,242,.3);">f</div>
        <div style="flex:1;">
          <div style="font-size:15px;font-weight:900;margin-bottom:2px;">فيسبوك</div>
          <div style="font-size:12px;color:#666;">تابعنا على فيسبوك</div>
        </div>
        <div style="color:#999;font-size:20px;">‹</div>
      </a>
    @endif

    @if($__tg)
      <a href="{{ $__tg }}" target="_blank" rel="noopener"
         style="background:#fff;border:1.5px solid #e5e7eb;border-radius:18px;padding:20px;display:flex;align-items:center;gap:16px;text-decoration:none;color:inherit;transition:.25s;box-shadow:0 4px 12px rgba(0,0,0,.04);">
        <div style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,#229ED9,#0088cc);color:#fff;display:grid;place-items:center;font-size:24px;flex-shrink:0;box-shadow:0 8px 20px rgba(34,158,217,.3);">✈️</div>
        <div style="flex:1;">
          <div style="font-size:15px;font-weight:900;margin-bottom:2px;">تليجرام</div>
          <div style="font-size:12px;color:#666;">انضم لقناتنا على تليجرام</div>
        </div>
        <div style="color:#999;font-size:20px;">‹</div>
      </a>
    @endif

    {{-- Track Order --}}
    <a href="/track"
       style="background:#fff;border:1.5px solid #e5e7eb;border-radius:18px;padding:20px;display:flex;align-items:center;gap:16px;text-decoration:none;color:inherit;transition:.25s;box-shadow:0 4px 12px rgba(0,0,0,.04);">
      <div style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,#8b5cf6,#6d28d9);color:#fff;display:grid;place-items:center;font-size:24px;flex-shrink:0;box-shadow:0 8px 20px rgba(139,92,246,.3);">📦</div>
      <div style="flex:1;">
        <div style="font-size:15px;font-weight:900;margin-bottom:2px;">تتبع طلبك</div>
        <div style="font-size:12px;color:#666;">تحقق من حالة طلبك</div>
      </div>
      <div style="color:#999;font-size:20px;">‹</div>
    </a>

  </div>

  {{-- Store Info --}}
  <div style="background:#ffffff;border:1.5px solid #e9e7e2;border-radius:20px;padding:24px;text-align:center;box-shadow:0 4px 12px rgba(0,0,0,.04);">
    <div style="font-size:12px;color:#999;font-weight:800;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">المتجر</div>
    <div style="font-size:18px;font-weight:900;margin-bottom:6px;">{{ $__shop->name ?? 'متجرنا' }}</div>
    @if($shop->description ?? false)
      <div style="font-size:13px;color:#666;line-height:1.7;">{{ $__shop->description }}</div>
    @endif
  </div>

  {{-- Back Button --}}
  <a href="/shop" style="display:flex;align-items:center;justify-content:center;gap:6px;padding:16px;background:linear-gradient(135deg,#171717,#000);color:#fff;border-radius:16px;text-decoration:none;font-weight:900;font-size:14px;box-shadow:0 8px 20px rgba(0,0,0,.15);">
    ← العودة للمتجر
  </a>

</div>


  {{-- ═══ نموذج التواصل ═══ --}}
  <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:22px;padding:28px;box-shadow:0 10px 30px rgba(0,0,0,.05);">

    <div style="text-align:center;margin-bottom:22px;">
      <div style="font-size:38px;margin-bottom:8px;">📝</div>
      <h2 style="font-size:19px;font-weight:900;margin:0 0 6px;color:#1f2937;">أرسل لنا رسالة</h2>
      <p style="font-size:13px;color:#6b7280;margin:0;">سنرد عليك في أقرب وقت ممكن</p>
    </div>

    @if(session('contact_success'))
      <div style="background:#dcfce7;color:#166534;padding:14px 18px;border-radius:14px;margin-bottom:16px;font-weight:800;font-size:13px;border:1px solid #bbf7d0;text-align:center;">
        ✅ {{ session('contact_success') }}
      </div>
    @endif

    @if($errors->any())
      <div style="background:#fef2f2;color:#991b1b;padding:14px 18px;border-radius:14px;margin-bottom:16px;font-size:13px;font-weight:700;border:1px solid #fecaca;">
        @foreach($errors->all() as $e)
          <div>• {{ $e }}</div>
        @endforeach
      </div>
    @endif

    <form method="POST" action="/contact" style="display:grid;gap:14px;">
      @csrf

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;">

        <label style="font-size:12px;font-weight:800;color:#4b5563;">
          الاسم *
          <input name="name" type="text" value="{{ old('name') }}" required maxlength="120"
                 placeholder="اسمك الكامل"
                 style="display:block;width:100%;margin-top:6px;padding:12px 14px;border:2px solid #e5e7eb;border-radius:12px;font-family:inherit;font-size:14px;">
        </label>

        <label style="font-size:12px;font-weight:800;color:#4b5563;">
          الهاتف
          <input name="phone" type="text" value="{{ old('phone') }}" maxlength="40"
                 placeholder="7XXXXXXXX"
                 style="display:block;width:100%;margin-top:6px;padding:12px 14px;border:2px solid #e5e7eb;border-radius:12px;font-family:inherit;font-size:14px;direction:ltr;">
        </label>

      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;">

        <label style="font-size:12px;font-weight:800;color:#4b5563;">
          البريد الإلكتروني
          <input name="email" type="email" value="{{ old('email') }}" maxlength="190"
                 placeholder="email@example.com"
                 style="display:block;width:100%;margin-top:6px;padding:12px 14px;border:2px solid #e5e7eb;border-radius:12px;font-family:inherit;font-size:14px;direction:ltr;">
        </label>

        <label style="font-size:12px;font-weight:800;color:#4b5563;">
          الموضوع
          <input name="subject" type="text" value="{{ old('subject') }}" maxlength="200"
                 placeholder="عنوان الرسالة"
                 style="display:block;width:100%;margin-top:6px;padding:12px 14px;border:2px solid #e5e7eb;border-radius:12px;font-family:inherit;font-size:14px;">
        </label>

      </div>

      <label style="font-size:12px;font-weight:800;color:#4b5563;">
        الرسالة *
        <textarea name="message" rows="5" required minlength="5" maxlength="2000"
                  placeholder="اكتب رسالتك هنا..."
                  style="display:block;width:100%;margin-top:6px;padding:12px 14px;border:2px solid #e5e7eb;border-radius:12px;font-family:inherit;font-size:14px;resize:vertical;">{{ old('message') }}</textarea>
      </label>

      <button type="submit"
              style="padding:14px;border-radius:14px;background:linear-gradient(135deg,#e96b2c,#f59e0b);color:#fff;border:0;font-weight:900;font-size:15px;cursor:pointer;font-family:inherit;box-shadow:0 8px 20px rgba(233,107,44,.3);">
        📤 إرسال الرسالة
      </button>

    </form>
  </div>

@endsection

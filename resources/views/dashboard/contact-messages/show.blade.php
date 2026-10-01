@extends('layouts.app')
@section('title', 'رسالة من ' . $message->name)
@section('page-title', '📩 رسالة من ' . $message->name)
@section('page-subtitle', $message->created_at->format('Y-m-d H:i'))

@section('content')

<div style="max-width:800px;margin:0 auto;">

  @if(session('success'))
    <div style="background:#dcfce7;color:#166534;padding:14px 18px;border-radius:14px;margin-bottom:16px;font-weight:800;font-size:13px;border:1px solid #bbf7d0;">
      {{ session('success') }}
    </div>
  @endif

  {{-- بطاقة المرسل --}}
  <div class="admin-card" style="padding:20px;margin-bottom:16px;">
    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
      <div style="width:64px;height:64px;border-radius:18px;background:linear-gradient(135deg,#fbbf24,#f97316);color:#fff;display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:900;flex-shrink:0;">
        {{ mb_substr($message->name, 0, 1) }}
      </div>

      <div style="flex:1;min-width:200px;">
        <div style="font-size:18px;font-weight:900;color:var(--text);">{{ $message->name }}</div>
        @if($message->subject)
          <div style="font-size:13px;color:var(--text-muted);margin-top:4px;">الموضوع: {{ $message->subject }}</div>
        @endif
        <div style="margin-top:8px;display:flex;gap:8px;flex-wrap:wrap;">
          @if($message->email)
            <a href="mailto:{{ $message->email }}"
               style="padding:6px 12px;background:#dbeafe;color:#1d4ed8;border-radius:999px;font-size:11px;font-weight:800;text-decoration:none;">
              📧 {{ $message->email }}
            </a>
          @endif
          @if($message->phone)
            <a href="tel:{{ $message->phone }}"
               style="padding:6px 12px;background:#dcfce7;color:#166534;border-radius:999px;font-size:11px;font-weight:800;text-decoration:none;direction:ltr;">
              📞 {{ $message->phone }}
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- محتوى الرسالة --}}
  <div class="admin-card" style="padding:24px;margin-bottom:16px;">
    <h3 style="margin:0 0 16px;font-size:14px;font-weight:900;color:var(--text-muted);">💬 نص الرسالة</h3>
    <div style="font-size:15px;line-height:1.9;color:var(--text);white-space:pre-wrap;">{{ $message->message }}</div>
  </div>

  {{-- أزرار --}}
  <div style="display:flex;gap:10px;flex-wrap:wrap;">
    <a href="/dashboard/contact-messages"
       style="padding:12px 24px;border-radius:12px;background:#f1f5f9;color:#334155;text-decoration:none;font-weight:900;font-size:13px;">
      ← القائمة
    </a>

    @if($message->email)
      <a href="mailto:{{ $message->email }}?subject=رد على: {{ urlencode($message->subject ?? 'رسالتك') }}"
         style="padding:12px 24px;border-radius:12px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);color:#fff;text-decoration:none;font-weight:900;font-size:13px;">
        📧 رد بالبريد
      </a>
    @endif

    @if($message->phone)
      <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}"
         target="_blank"
         style="padding:12px 24px;border-radius:12px;background:linear-gradient(135deg,#25D366,#128C7E);color:#fff;text-decoration:none;font-weight:900;font-size:13px;">
        💬 رد بواتساب
      </a>
    @endif

    <form method="POST" action="/dashboard/contact-messages/{{ $message->id }}"
          onsubmit="return confirm('حذف الرسالة نهائياً؟')"
          style="margin:0;margin-inline-start:auto;">
      @csrf
      @method('DELETE')
      <button type="submit"
              style="padding:12px 24px;border-radius:12px;background:#fee2e2;color:#991b1b;border:0;font-weight:900;font-size:13px;cursor:pointer;font-family:inherit;">
        🗑️ حذف
      </button>
    </form>
  </div>

</div>

@endsection

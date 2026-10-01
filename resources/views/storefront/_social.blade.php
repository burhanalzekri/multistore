@php
  $s = $shop->settings ?? [];
  $wa = $s["whatsapp"] ?? $shop->whatsapp ?? "";
  $hasSocial = !empty($s["facebook"]) || !empty($s["instagram"]) || !empty($s["tiktok"]) || !empty($s["twitter"]) || !empty($s["youtube"]) || !empty($wa);
@endphp

@if($hasSocial)
<div style="max-width:1280px;margin:0 auto;padding:24px 16px;">
  <div style="background:linear-gradient(to left,#f59e0b,#ea580c);border-radius:24px;padding:28px;color:white;text-align:center;box-shadow:0 8px 32px rgba(245,158,11,0.3);">
    <div style="font-size:32px;margin-bottom:8px;">📱</div>
    <h3 style="font-size:22px;font-weight:900;margin:0 0 4px 0;">تابعنا على</h3>
    <p style="font-size:13px;opacity:0.95;margin:0 0 20px 0;">شاهد أحدث العروض والمنتجات</p>

    <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:14px;">

      @if(!empty($s["facebook"]))
      <a href="{{ $s['facebook'] }}" target="_blank" rel="noopener" title="Facebook" style="width:56px;height:56px;background:#1877f2;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(24,119,242,0.4);transition:all 0.3s;" onmouseover="this.style.transform='translateY(-4px) scale(1.05)'" onmouseout="this.style.transform=''">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
      </a>
      @endif

      @if(!empty($wa))
      <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" title="WhatsApp" style="width:56px;height:56px;background:#25d366;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(37,211,102,0.4);transition:all 0.3s;" onmouseover="this.style.transform='translateY(-4px) scale(1.05)'" onmouseout="this.style.transform=''">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
      </a>
      @endif

      @if(!empty($s["instagram"]))
      <a href="{{ $s['instagram'] }}" target="_blank" rel="noopener" title="Instagram" style="width:56px;height:56px;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);border-radius:16px;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(220,39,67,0.4);transition:all 0.3s;" onmouseover="this.style.transform='translateY(-4px) scale(1.05)'" onmouseout="this.style.transform=''">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
          <rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke="white" stroke-width="2.2" fill="none"/>
          <circle cx="12" cy="12" r="4.5" stroke="white" stroke-width="2.2" fill="none"/>
          <circle cx="17.5" cy="6.5" r="1.4" fill="white"/>
        </svg>
      </a>
      @endif

      @if(!empty($s["tiktok"]))
      <a href="{{ $s['tiktok'] }}" target="_blank" rel="noopener" title="TikTok" style="width:56px;height:56px;background:#000;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(0,0,0,0.4);transition:all 0.3s;position:relative;overflow:hidden;" onmouseover="this.style.transform='translateY(-4px) scale(1.05)'" onmouseout="this.style.transform=''">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="white"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5.8 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.84-.1z"/></svg>
      </a>
      @endif

      @if(!empty($s["twitter"]))
      <a href="{{ $s['twitter'] }}" target="_blank" rel="noopener" title="Twitter / X" style="width:56px;height:56px;background:#000;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(0,0,0,0.4);transition:all 0.3s;" onmouseover="this.style.transform='translateY(-4px) scale(1.05)'" onmouseout="this.style.transform=''">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="white"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
      </a>
      @endif

      @if(!empty($s["youtube"]))
      <a href="{{ $s['youtube'] }}" target="_blank" rel="noopener" title="YouTube" style="width:56px;height:56px;background:#ff0000;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 6px 16px rgba(255,0,0,0.4);transition:all 0.3s;" onmouseover="this.style.transform='translateY(-4px) scale(1.05)'" onmouseout="this.style.transform=''">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="white"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
      </a>
      @endif

    </div>
  </div>
</div>
@endif
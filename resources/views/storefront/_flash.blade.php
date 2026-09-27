@php
  $flashSales = \App\Models\FlashSale::with("product")->get()->filter(fn($f) => $f->isLive());
@endphp

@if($flashSales->count() > 0)
<div style="max-width:1280px;margin:0 auto;padding:24px 16px;">
  <div style="background:linear-gradient(to left,#ef4444,#f97316);border-radius:24px;padding:24px;color:white;box-shadow:0 8px 32px rgba(239,68,68,0.3);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:12px;">
      <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:24px;">⚡</div>
        <div>
          <h2 style="font-size:22px;font-weight:900;margin:0;">عروض فلاش</h2>
          <p style="font-size:12px;opacity:0.9;margin:4px 0 0 0;">خصومات حصرية — لفترة محدودة</p>
        </div>
      </div>
      <span style="background:rgba(0,0,0,0.3);padding:6px 14px;border-radius:8px;font-weight:900;font-size:14px;">🔥 {{ $flashSales->count() }} عرض نشط</span>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;">
      @foreach($flashSales as $fs)
      <a href="/product/{{ $fs->product->id }}" style="background:white;border-radius:16px;overflow:hidden;text-decoration:none;color:inherit;box-shadow:0 4px 16px rgba(0,0,0,0.15);">
        <div style="aspect-ratio:1;background:linear-gradient(135deg,#fef3c7,#fed7aa);position:relative;overflow:hidden;">
          @if($fs->product->image)
          <img src="{{ ($fs->product->image_url ?? Storage::url($fs->product->image)) }}" style="width:100%;height:100%;object-fit:cover;">
          @else
          <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:56px;">📦</div>
          @endif
          <div style="position:absolute;top:8px;right:8px;background:#ef4444;color:white;font-size:11px;font-weight:900;padding:4px 10px;border-radius:999px;">
            ⚡ خصم {{ round((1 - $fs->discount_price / $fs->product->price) * 100) }}%
          </div>
          <div style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,0.7);color:white;font-size:11px;padding:6px;text-align:center;font-weight:700;font-family:monospace;" class="countdown" data-seconds="{{ $fs->remainingSeconds() }}">
            ⏰ جاري الحساب...
          </div>
        </div>
        <div style="padding:12px;">
          <h3 style="font-weight:900;font-size:13px;margin:0 0 6px 0;color:#1f2937;">{{ $fs->product->name }}</h3>
          <div style="display:flex;align-items:center;gap:8px;">
            <span style="color:#ef4444;font-weight:900;">{{ number_format($fs->discount_price) }}</span>
            <span style="color:#9ca3af;font-size:12px;text-decoration:line-through;">{{ number_format($fs->product->price) }}</span>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</div>

<script>
document.querySelectorAll(".countdown").forEach(el => {
  const total = parseInt(el.dataset.seconds);
  const start = Date.now();
  const tick = () => {
    const elapsed = Math.floor((Date.now() - start) / 1000);
    const t = total - elapsed;
    if (t <= 0) { el.textContent = "⏰ انتهى العرض"; return; }
    const h = String(Math.floor(t / 3600)).padStart(2, "0");
    const m = String(Math.floor((t % 3600) / 60)).padStart(2, "0");
    const s = String(t % 60).padStart(2, "0");
    el.textContent = "⏰ " + h + ":" + m + ":" + s;
    setTimeout(tick, 1000);
  };
  tick();
});
</script>
@endif

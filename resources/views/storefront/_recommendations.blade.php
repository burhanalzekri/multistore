@php
  use App\Services\Recommendation\RecommendationEngine;
  $recommended = RecommendationEngine::forCustomer(8);
@endphp

@if($recommended->count() > 0)
<section style="max-width:1280px;margin:0 auto;padding:24px 16px;">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
    <div style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,#8b5cf6,#6d28d9);display:flex;align-items:center;justify-content:center;font-size:22px;box-shadow:0 4px 12px rgba(139,92,246,0.4);">✨</div>
    <div>
      <h2 style="font-size:22px;font-weight:900;color:var(--text);margin:0;">مختار لك</h2>
      <p style="font-size:13px;color:var(--text-muted);margin:2px 0 0 0;">منتجات قد تناسبك بناءً على اهتماماتك</p>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;">
    @foreach($recommended as $p)
    <a href="/product/{{ $p->id }}" class="admin-card" style="text-decoration:none;color:inherit;display:block;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 20px 40px rgba(139,92,246,0.15)';" onmouseout="this.style.transform='';this.style.boxShadow='var(--shadow-sm)';">
      <div style="aspect-ratio:1;background:linear-gradient(135deg,#ede9fe,#ddd6fe);position:relative;overflow:hidden;">
        @if($p->image)
          <img src="{{ Storage::url($p->image) }}" style="width:100%;height:100%;object-fit:cover;">
        @else
          <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:56px;">📦</div>
        @endif
        <div style="position:absolute;top:8px;right:8px;background:linear-gradient(135deg,#8b5cf6,#6d28d9);color:white;font-size:10px;font-weight:900;padding:4px 10px;border-radius:999px;">✨ لك</div>
      </div>
      <div style="padding:12px;">
        <h3 style="font-weight:900;font-size:13px;margin:0 0 6px 0;color:var(--text);overflow:hidden;text-overflow:ellipsis;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;min-height:2.2rem;">{{ $p->name }}</h3>
        <div style="color:#8b5cf6;font-weight:900;font-size:15px;">{{ number_format($p->price) }} <span style="font-size:11px;color:var(--text-muted);">ريال</span></div>
      </div>
    </a>
    @endforeach
  </div>
</section>
@endif

{{-- 🤝 عملاء آخرون اشتروا هذا أيضاً — محسّن --}}

@php
  $alsoBought = collect();
  if (isset($product) && isset($shop)) {
    $alsoBought = \App\Models\Product::withoutGlobalScope('tenant')
      ->where('shop_id', $shop->id)
      ->where('id', '!=', $product->id)
      ->where('is_active', true)
      ->inRandomOrder()
      ->take(4)
      ->get();
  }
@endphp

@if($alsoBought->count() > 0)
<div style="background:white;border-radius:24px;padding:24px;margin-bottom:20px;box-shadow:0 4px 20px rgba(0,0,0,0.05);">
  <h2 style="font-size:20px;font-weight:900;margin-bottom:20px;display:flex;align-items:center;gap:10px;color:#1f2937;">
    <span style="width:36px;height:36px;background:linear-gradient(135deg,#8b5cf6,#6d28d9);border-radius:12px;display:inline-flex;align-items:center;justify-content:center;font-size:18px;">🤝</span>
    عملاء آخرون اشتروا هذا أيضاً
  </h2>

  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px;">
    @foreach($alsoBought as $rp)
    <a href="/product/{{ $rp->id }}" style="background:white;border-radius:18px;overflow:hidden;text-decoration:none;color:inherit;border:1px solid #f1f5f9;transition:all 0.3s;display:block;"
      onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 28px rgba(139,92,246,0.2)';this.style.borderColor='#ddd6fe'"
      onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='#f1f5f9'">

      <div style="position:relative;aspect-ratio:1;background:linear-gradient(135deg,#ede9fe,#ddd6fe);overflow:hidden;">
        @if($rp->image)
          <img src="{{ Storage::url($rp->image) }}" alt="{{ $rp->name }}" style="width:100%;height:100%;object-fit:cover;">
        @else
          <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:48px;">📦</div>
        @endif
      </div>

      <div style="padding:12px;">
        <div style="font-weight:900;font-size:13px;color:#1f2937;margin-bottom:8px;min-height:36px;line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
          {{ $rp->name }}
        </div>
        <div style="font-weight:900;font-size:15px;color:#8b5cf6;">
          {{ number_format($rp->price) }} <span style="font-size:10px;color:#9ca3af;">ريال</span>
        </div>
      </div>
    </a>
    @endforeach
  </div>
</div>
@endif

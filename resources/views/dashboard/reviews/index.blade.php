@extends('layouts.app')
@section('title', 'التقييمات')
@section('page-title', '⭐ التقييمات')
@section('page-subtitle', $reviews->total() . ' تقييم')

@section('content')

<div class="rv-hero">
  <div class="rv-hero-info">
    <div class="rv-hero-icon">⭐</div>
    <div>
      <div class="rv-hero-title">{{ $reviews->total() }} تقييم</div>
      <div class="rv-hero-sub">آراء العملاء وتجاربهم</div>
    </div>
  </div>
</div>

@if($reviews->isEmpty())
  <div class="rv-empty">
    <div style="font-size:64px;margin-bottom:12px">💬</div>
    <h3>لا توجد تقييمات بعد</h3>
    <p>عندما يقيّم العملاء المنتجات، ستظهر هنا</p>
  </div>
@else
  <div class="rv-grid">
    @foreach($reviews as $r)
      <article class="rv-card">
        <div class="rv-head">
          <div class="rv-avatar">{{ mb_substr($r->customer_name, 0, 1) }}</div>
          <div class="rv-info">
            <div class="rv-name">{{ $r->customer_name }}</div>
            <div class="rv-date">{{ $r->created_at->diffForHumans() }}</div>
          </div>
          <div class="rv-badge {{ $r->is_approved ? 'ok' : 'pending' }}">
            {{ $r->is_approved ? '✓ معتمد' : '⏳ مراجعة' }}
          </div>
        </div>

        <div class="rv-stars">
          @for($i = 1; $i <= 5; $i++){{ $i <= $r->rating ? '★' : '☆' }}@endfor
        </div>

        @if($r->product)
          <div class="rv-product">
            <span>📦</span>
            <span>{{ $r->product->name }}</span>
          </div>
        @endif

        @if($r->comment)
          <div class="rv-comment">{{ $r->comment }}</div>
        @endif

        @if($r->image)
          <div class="rv-image">
            <img src="{{ ($r->image_url ?? Storage::url($r->image)) }}" alt="صورة التقييم" loading="lazy">
          </div>
        @endif

        <div class="rv-actions">
          @if(!$r->is_approved)
            <form method="POST" action="/dashboard/reviews/{{ $r->id }}/approve" style="flex:1">
              @csrf
              <button type="submit" class="rv-btn approve">✓ اعتماد</button>
            </form>
          @endif
          <form method="POST" action="/dashboard/reviews/{{ $r->id }}" onsubmit="return confirm('حذف التقييم؟')" style="flex:1">
            @csrf
            @method('DELETE')
            <button type="submit" class="rv-btn delete">حذف</button>
          </form>
        </div>
      </article>
    @endforeach
  </div>

  @if($reviews->hasPages())
    <div style="margin-top:24px">{{ $reviews->links() }}</div>
  @endif
@endif

<style>
.rv-hero{background:linear-gradient(135deg,#fef3c7,#fed7aa);border-radius:20px;padding:20px 24px;margin-bottom:24px;border:1px solid #fcd34d}
.rv-hero-info{display:flex;align-items:center;gap:14px}
.rv-hero-icon{width:48px;height:48px;border-radius:14px;background:#fff;color:#d97706;display:grid;place-items:center;font-size:24px;box-shadow:0 4px 12px rgba(217,119,6,.15)}
.rv-hero-title{font-size:20px;font-weight:900;color:#78350f}
.rv-hero-sub{font-size:12px;color:#92400e;font-weight:700;margin-top:2px}

.rv-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px}
.rv-card{background:#fff;border:1px solid #e9edf2;border-radius:20px;padding:20px;box-shadow:0 2px 10px rgba(0,0,0,.03);transition:.25s}
.rv-card:hover{transform:translateY(-3px);box-shadow:0 12px 32px rgba(0,0,0,.08)}

.rv-head{display:flex;align-items:center;gap:12px;margin-bottom:14px}
.rv-avatar{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;display:grid;place-items:center;font-weight:900;font-size:16px;flex-shrink:0}
.rv-info{flex:1;min-width:0}
.rv-name{font-size:13px;font-weight:900;color:#17202b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.rv-date{font-size:11px;color:#94a3b8;font-weight:700}
.rv-badge{font-size:10px;font-weight:900;padding:4px 10px;border-radius:8px;white-space:nowrap}
.rv-badge.ok{background:#dcfce7;color:#15803d}
.rv-badge.pending{background:#fef3c7;color:#92400e}

.rv-stars{color:#f59e0b;font-size:18px;letter-spacing:2px;margin-bottom:10px}

.rv-product{display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:800;color:#d97706;background:#fef3c7;padding:5px 10px;border-radius:8px;margin-bottom:10px}

.rv-comment{font-size:13px;line-height:1.7;color:#475569;margin-bottom:12px;padding:10px 12px;background:#f8fafc;border-radius:10px;border-right:3px solid #fbbf24}

.rv-image{border-radius:12px;overflow:hidden;margin-bottom:12px;background:#f1f5f9}
.rv-image img{width:100%;height:160px;object-fit:cover;display:block}

.rv-actions{display:flex;gap:8px}
.rv-btn{width:100%;padding:10px;border-radius:11px;font-size:12px;font-weight:900;border:0;cursor:pointer;transition:.2s}
.rv-btn.approve{background:#dcfce7;color:#15803d}
.rv-btn.approve:hover{background:#bbf7d0}
.rv-btn.delete{background:#fee2e2;color:#b91c1c}
.rv-btn.delete:hover{background:#fecaca}

.rv-empty{background:#fff;border:2px dashed #e5e7eb;border-radius:20px;padding:60px 24px;text-align:center}
.rv-empty h3{font-size:20px;font-weight:900;color:#17202b;margin-bottom:6px}
.rv-empty p{color:#64748b;font-size:14px}

html.dark .rv-card,html.dark .rv-empty{background:#1a1d21;border-color:#2a2e33}
html.dark .rv-name{color:#e5e7eb}
html.dark .rv-comment{background:#24282d;color:#cbd5e1}
</style>

@endsection

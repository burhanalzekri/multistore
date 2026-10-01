@extends('layouts.app')
@section('title', 'آراء العملاء')
@section('page-title', '⭐ آراء العملاء')
@section('page-subtitle', $testimonials->total() . ' رأي')

@section('content')

{{-- إحصائيات --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:20px;">
  <div class="admin-card" style="padding:16px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">📊 الإجمالي</div>
    <div style="font-size:24px;font-weight:900;color:var(--primary);">{{ $stats['total'] }}</div>
  </div>
  <div class="admin-card" style="padding:16px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">👁️ منشورة</div>
    <div style="font-size:24px;font-weight:900;color:#16a34a;">{{ $stats['visible'] }}</div>
  </div>
  <div class="admin-card" style="padding:16px;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;margin-bottom:4px;">🙈 مخفية</div>
    <div style="font-size:24px;font-weight:900;color:#dc2626;">{{ $stats['hidden'] }}</div>
  </div>
</div>

{{-- فلاتر --}}
<div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
  <a href="/dashboard/testimonials" style="padding:8px 16px;border-radius:10px;{{ !request('filter') ? 'background:linear-gradient(135deg,#fbbf24,#f97316);color:#fff;' : 'background:#f1f5f9;color:#475569;' }}text-decoration:none;font-weight:800;font-size:12px;">الكل</a>
  <a href="/dashboard/testimonials?filter=visible" style="padding:8px 16px;border-radius:10px;{{ request('filter') === 'visible' ? 'background:linear-gradient(135deg,#fbbf24,#f97316);color:#fff;' : 'background:#f1f5f9;color:#475569;' }}text-decoration:none;font-weight:800;font-size:12px;">👁️ منشورة</a>
  <a href="/dashboard/testimonials?filter=hidden" style="padding:8px 16px;border-radius:10px;{{ request('filter') === 'hidden' ? 'background:linear-gradient(135deg,#fbbf24,#f97316);color:#fff;' : 'background:#f1f5f9;color:#475569;' }}text-decoration:none;font-weight:800;font-size:12px;">🙈 مخفية</a>
</div>

{{-- قائمة الآراء --}}
<div class="admin-card">
  @forelse($testimonials as $t)
    <div style="padding:18px 20px;border-bottom:1px solid var(--border);{{ !$t->is_visible ? 'background:#fef2f2;' : '' }}">

      <div style="display:flex;align-items:flex-start;gap:14px;flex-wrap:wrap;">

        <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#fbbf24,#f97316);color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:900;flex-shrink:0;">
          {{ mb_substr($t->name, 0, 1) }}
        </div>

        <div style="flex:1;min-width:200px;">
          <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <div style="font-weight:900;font-size:14px;color:var(--text);">{{ $t->name }}</div>
            @if($t->role)
              <span style="font-size:11px;color:var(--text-muted);">{{ $t->role }}</span>
            @endif
            <span style="color:#f59e0b;font-size:14px;">
              @for($i = 0; $i < 5; $i++){{ $i < $t->rating ? '★' : '☆' }}@endfor
            </span>
            @if($t->is_visible)
              <span style="background:#dcfce7;color:#166534;padding:3px 10px;border-radius:99px;font-size:10px;font-weight:800;">👁️ منشور</span>
            @else
              <span style="background:#fee2e2;color:#991b1b;padding:3px 10px;border-radius:99px;font-size:10px;font-weight:800;">🙈 مخفي</span>
            @endif
          </div>

          <div style="font-size:13px;color:var(--text);line-height:1.8;margin-top:8px;">{{ $t->comment }}</div>

          <div style="font-size:11px;color:var(--text-muted);margin-top:8px;">{{ $t->created_at->diffForHumans() }}</div>
        </div>

        <div style="display:flex;gap:6px;flex-shrink:0;">
          <form method="POST" action="/dashboard/testimonials/{{ $t->id }}/toggle" style="margin:0;">
            @csrf
            <button type="submit"
                    style="padding:8px 14px;border-radius:10px;{{ $t->is_visible ? 'background:#fef3c7;color:#92400e;' : 'background:#dcfce7;color:#166534;' }}border:0;font-weight:900;font-size:12px;cursor:pointer;font-family:inherit;">
              {{ $t->is_visible ? '🙈 إخفاء' : '👁️ إظهار' }}
            </button>
          </form>

          <form method="POST" action="/dashboard/testimonials/{{ $t->id }}" onsubmit="return confirm('حذف الرأي؟')" style="margin:0;">
            @csrf @method('DELETE')
            <button type="submit"
                    style="padding:8px 14px;border-radius:10px;background:#fee2e2;color:#991b1b;border:0;font-weight:900;font-size:12px;cursor:pointer;font-family:inherit;">
              🗑️
            </button>
          </form>
        </div>

      </div>
    </div>
  @empty
    <div style="padding:60px 20px;text-align:center;">
      <div style="font-size:56px;margin-bottom:12px;">⭐</div>
      <div style="font-size:15px;font-weight:900;">لا توجد آراء بعد</div>
      <p style="color:var(--text-muted);font-size:13px;margin-top:8px;">ستظهر هنا آراء عملائك من صفحة الهبوط</p>
    </div>
  @endforelse
</div>

@if($testimonials->hasPages())
  <div style="margin-top:16px;">{{ $testimonials->links() }}</div>
@endif

@endsection

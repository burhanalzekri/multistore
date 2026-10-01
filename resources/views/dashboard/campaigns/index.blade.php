@extends('layouts.app')
@section('title', 'الحملات البريدية')
@section('page-title', '📧 الحملات البريدية')
@section('page-subtitle', 'أنشئ وأدر حملاتك التسويقية')

@section('content')

<style>
  .cmp-wrap { max-width: 1100px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; }
  .cmp-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
  .cmp-stat { background: #fff; border-radius: 16px; padding: 16px 18px; border: 1.5px solid #e2e8f0; box-shadow: 0 4px 12px rgba(15,23,42,.04); display: flex; align-items: center; gap: 12px; transition: .2s; }
  .cmp-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(15,23,42,.08); }
  .cmp-stat-icon { width: 44px; height: 44px; border-radius: 12px; display: grid; place-items: center; font-size: 20px; flex-shrink: 0; }
  .cmp-stat-icon.blue { background: #eff6ff; }
  .cmp-stat-icon.green { background: #f0fdf4; }
  .cmp-stat-icon.amber { background: #fffbeb; }
  .cmp-stat-icon.purple { background: #faf5ff; }
  .cmp-stat-value { font-size: 22px; font-weight: 900; color: #0f172a; line-height: 1; }
  .cmp-stat-label { font-size: 11px; font-weight: 800; color: #64748b; margin-top: 4px; }

  .cmp-actions { display: flex; justify-content: flex-end; }
  .cmp-new-btn { padding: 12px 22px; background: linear-gradient(135deg, #e96b2c, #d9541a); color: #fff; border: 0; border-radius: 12px; font-size: 13px; font-weight: 900; cursor: pointer; font-family: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 8px 20px rgba(233,107,44,.35); }
  .cmp-new-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(233,107,44,.45); }

  .cmp-card { background: #fff; border-radius: 18px; border: 1.5px solid #e2e8f0; box-shadow: 0 4px 12px rgba(15,23,42,.04); padding: 18px; display: flex; align-items: center; gap: 14px; transition: .25s; }
  .cmp-card:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(15,23,42,.1); border-color: #e96b2c; }
  .cmp-card-icon { width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, #eff6ff, #dbeafe); display: grid; place-items: center; font-size: 22px; flex-shrink: 0; }
  .cmp-card-body { flex: 1; min-width: 0; }
  .cmp-card-title { font-size: 15px; font-weight: 900; color: #0f172a; margin-bottom: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .cmp-card-sub { font-size: 12px; color: #64748b; font-weight: 700; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .cmp-card-meta { display: flex; gap: 12px; font-size: 11px; color: #94a3b8; font-weight: 700; margin-top: 6px; flex-wrap: wrap; }
  .cmp-card-status { padding: 4px 10px; border-radius: 99px; font-size: 10px; font-weight: 900; }
  .cmp-card-actions { display: flex; gap: 6px; }
  .cmp-btn-icon { width: 36px; height: 36px; border-radius: 10px; border: 0; display: grid; place-items: center; cursor: pointer; font-size: 15px; font-family: inherit; transition: .2s; }
  .cmp-btn-icon.view { background: #eff6ff; color: #1d4ed8; }
  .cmp-btn-icon.view:hover { background: #1d4ed8; color: #fff; }
  .cmp-btn-icon.del { background: #fef2f2; color: #dc2626; }
  .cmp-btn-icon.del:hover { background: #dc2626; color: #fff; }

  .cmp-empty { background: #fff; border: 1.5px dashed #e2e8f0; border-radius: 20px; padding: 60px 30px; text-align: center; }
  .cmp-empty-icon { font-size: 72px; margin-bottom: 16px; }
  .cmp-empty-title { font-size: 20px; font-weight: 900; color: #0f172a; margin-bottom: 8px; }
  .cmp-empty-desc { font-size: 13px; color: #64748b; margin-bottom: 22px; }

  @media (max-width: 640px) {
    .cmp-stats { grid-template-columns: repeat(2, 1fr); }
    .cmp-card { flex-wrap: wrap; }
  }

  /* ═══ 🎯 Filters Bar ═══ */
  .cmp-filters {
    background: #fff;
    border-radius: 16px;
    padding: 14px 18px;
    border: 1.5px solid #e2e8f0;
    box-shadow: 0 4px 12px rgba(15,23,42,.04);
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    justify-content: space-between;
  }
  .cmp-chips {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
  }
  .cmp-chip {
    padding: 8px 14px;
    border-radius: 20px;
    background: #f5f5f5;
    border: 1.5px solid transparent;
    color: #4a4a4a;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    font-family: inherit;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all .2s;
  }
  .cmp-chip:hover {
    background: #fff0eb;
    color: #e96b2c;
    border-color: #ffccbc;
  }
  .cmp-chip.active {
    background: linear-gradient(135deg, #e96b2c, #d9541a);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(233,107,44,.3);
  }
  .cmp-status-select {
    padding: 8px 12px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    font-size: 12px;
    font-weight: 800;
    color: #334155;
    font-family: inherit;
    cursor: pointer;
    outline: none;
  }
  .cmp-status-select:focus { border-color: #e96b2c; }
  .cmp-custom-range {
    display: none;
    grid-template-columns: 1fr auto 1fr auto;
    gap: 8px;
    align-items: center;
    width: 100%;
    margin-top: 10px;
    padding-top: 12px;
    border-top: 1px dashed #e2e8f0;
  }
  .cmp-custom-range.show { display: grid; }
  .cmp-date-input {
    padding: 10px 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    font-family: inherit;
    outline: none;
    width: 100%;
  }
  .cmp-date-input:focus { border-color: #e96b2c; box-shadow: 0 0 0 3px rgba(233,107,44,.12); }
  .cmp-apply-btn {
    padding: 10px 18px;
    background: linear-gradient(135deg, #e96b2c, #d9541a);
    color: #fff;
    border: 0;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 900;
    cursor: pointer;
    font-family: inherit;
  }

  /* ═══ 📜 Scrollable List ═══ */
  .cmp-scroll-list {
    max-height: calc(100vh - 420px);
    min-height: 300px;
    overflow-y: auto;
    padding-right: 4px;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
  }
  .cmp-scroll-list::-webkit-scrollbar { width: 8px; }
  .cmp-scroll-list::-webkit-scrollbar-track { background: #f8fafc; border-radius: 4px; }
  .cmp-scroll-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
  }
  .cmp-scroll-list::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

  @media (max-width: 640px) {
    .cmp-scroll-list { max-height: none; overflow-y: visible; }
    .cmp-custom-range { grid-template-columns: 1fr 1fr; gap: 6px; }
    .cmp-custom-range .cmp-sep { display: none; }
    .cmp-custom-range .cmp-apply-btn { grid-column: 1 / -1; }
  }

</style>

<div class="cmp-wrap">

  {{-- الإحصائيات --}}
  <div class="cmp-stats">
    <div class="cmp-stat">
      <div class="cmp-stat-icon blue">📧</div>
      <div>
        <div class="cmp-stat-value">{{ number_format($stats['total'] ?? 0) }}</div>
        <div class="cmp-stat-label">إجمالي الحملات</div>
      </div>
    </div>
    <div class="cmp-stat">
      <div class="cmp-stat-icon green">✅</div>
      <div>
        <div class="cmp-stat-value">{{ number_format($stats['sent'] ?? 0) }}</div>
        <div class="cmp-stat-label">تم الإرسال</div>
      </div>
    </div>
    <div class="cmp-stat">
      <div class="cmp-stat-icon amber">📝</div>
      <div>
        <div class="cmp-stat-value">{{ number_format($stats['draft'] ?? 0) }}</div>
        <div class="cmp-stat-label">مسودات</div>
      </div>
    </div>
    <div class="cmp-stat">
      <div class="cmp-stat-icon purple">📤</div>
      <div>
        <div class="cmp-stat-value">{{ number_format($stats['total_sent_emails'] ?? 0) }}</div>
        <div class="cmp-stat-label">إجمالي الرسائل المُرسلة</div>
      </div>
    </div>
  </div>

  {{-- زر إنشاء --}}
  <div class="cmp-actions">
    <a href="/dashboard/campaigns/create" class="cmp-new-btn">
      ➕ حملة جديدة
    </a>
  </div>

  {{-- القائمة --}}
  @if($campaigns->count() > 0)
    <div data-cmp-filters style="display:flex;flex-direction:column;gap:0;padding:10px 14px;margin-bottom:14px;background:#fff;border:1.5px solid #e8edf3;border-radius:14px;box-shadow:0 2px 8px rgba(15,23,42,.04);">

      {{-- الصف الرئيسي: صف واحد --}}
      <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">

        {{-- الفترة --}}
        <div style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;">
          <a href="?period=all&status={{ request('status') }}" class="cmp-chip {{ $period==='all'?'active':'' }}">📅 الكل</a>
          <a href="?period=today&status={{ request('status') }}" class="cmp-chip {{ $period==='today'?'active':'' }}">☀️ اليوم</a>
          <a href="?period=7days&status={{ request('status') }}" class="cmp-chip {{ $period==='7days'?'active':'' }}">🗓️ 7 أيام</a>
          <a href="?period=30days&status={{ request('status') }}" class="cmp-chip {{ $period==='30days'?'active':'' }}">📆 30 يوم</a>
          <a href="?period=year&status={{ request('status') }}" class="cmp-chip {{ $period==='year'?'active':'' }}">🎯 السنة</a>
          <a href="#" onclick="var el=document.getElementById('cmpCustom');el.style.display=(el.style.display==='flex'?'none':'flex');return false;" class="cmp-chip {{ $period==='custom'?'active':'' }}">⚙️ مخصص</a>
        </div>

        {{-- فاصل --}}
        <div style="width:1.5px;height:24px;background:linear-gradient(180deg,transparent,#dbe3ee,transparent);"></div>

        {{-- الحالة --}}
        <div style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;">
          <a href="?period={{ $period }}&status=" class="cmp-chip {{ !request('status')?'active':'' }}">كل الحالات</a>
          <a href="?period={{ $period }}&status=sent" class="cmp-chip {{ request('status')==='sent'?'active':'' }}">✅ مرسلة</a>
          <a href="?period={{ $period }}&status=draft" class="cmp-chip {{ request('status')==='draft'?'active':'' }}">📝 مسودات</a>
          <a href="?period={{ $period }}&status=failed" class="cmp-chip {{ request('status')==='failed'?'active':'' }}">❌ فشل</a>
        </div>

      </div>

      {{-- نموذج التاريخ المخصص --}}
      <form method="GET" id="cmpCustom" style="display:{{ $period==='custom'?'flex':'none' }};align-items:center;gap:8px;flex-wrap:wrap;margin-top:10px;padding-top:10px;border-top:1px dashed #e8edf3;">
        <input type="hidden" name="period" value="custom">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <span style="font-size:12px;font-weight:800;color:#64748b;">من:</span>
        <input type="date" name="from" value="{{ request('from') }}" style="padding:6px 10px;border-radius:8px;border:1.5px solid #e2e8f0;font-size:12px;font-family:inherit;background:#f8fafc;">
        <span style="font-size:12px;font-weight:800;color:#64748b;">إلى:</span>
        <input type="date" name="to" value="{{ request('to') }}" style="padding:6px 10px;border-radius:8px;border:1.5px solid #e2e8f0;font-size:12px;font-family:inherit;background:#f8fafc;">
        <button type="submit" class="cmp-chip active" style="border:none;cursor:pointer;font-family:inherit;">🔍 تطبيق</button>
        <a href="?period=all&status={{ request('status') }}" style="font-size:11px;color:#94a3b8;text-decoration:none;font-weight:700;">إلغاء</a>
      </form>

    </div>

    <div class="cmp-scroll-list">
      @foreach($campaigns as $c)
        <div class="cmp-card">
          <div class="cmp-card-icon">
            @if($c->status === 'sent') ✅
            @elseif($c->status === 'sending') 📤
            @elseif($c->status === 'scheduled') 📅
            @elseif($c->status === 'failed') ❌
            @else 📝 @endif
          </div>
          <div class="cmp-card-body">
            <div class="cmp-card-title">{{ $c->name }}</div>
            <div class="cmp-card-sub">📧 {{ $c->subject }}</div>
            <div class="cmp-card-meta">
              <span>👥 {{ number_format($c->recipients_count) }} مستلم</span>
              <span>📤 {{ number_format($c->sent_count) }} أُرسل</span>
              @if($c->failed_count > 0)
                <span style="color:#dc2626;">❌ {{ number_format($c->failed_count) }} فشل</span>
              @endif
              <span>🕒 {{ $c->created_at->diffForHumans() }}</span>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;gap:8px;align-items:flex-end;">
            <span class="cmp-card-status" style="background: {{ $c->statusColor() }}20; color: {{ $c->statusColor() }};">
              {{ $c->statusLabel() }}
            </span>
            <div class="cmp-card-actions">
              <a href="/dashboard/campaigns/{{ $c->id }}" class="cmp-btn-icon view" title="عرض">👁️</a>
              <form method="POST" action="/dashboard/campaigns/{{ $c->id }}" style="margin:0;" onsubmit="return confirm('حذف هذه الحملة؟');">
                @csrf
                @method('DELETE')
                <button type="submit" class="cmp-btn-icon del" title="حذف">🗑️</button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div style="margin-top:20px;">{{ $campaigns->links() }}</div>
  @else
    <div class="cmp-empty">
      <div class="cmp-empty-icon">📧</div>
      <div class="cmp-empty-title">لا توجد حملات بعد</div>
      <div class="cmp-empty-desc">ابدأ بإنشاء أول حملة بريدية لعملائك</div>
      <a href="/dashboard/campaigns/create" class="cmp-new-btn">➕ حملة جديدة</a>
    </div>
  @endif

</div>

@endsection

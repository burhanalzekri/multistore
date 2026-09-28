{{-- Partial: _logs.blade.php --}}
{{-- ═══ Hero Stats ═══ --}}
<div class="sl-stats">
  <div class="sl-stat">
    <div class="sl-stat-icon" style="background:#dbeafe;color:#1e40af">📨</div>
    <div>
      <div class="sl-stat-value">{{ number_format($stats['total']) }}</div>
      <div class="sl-stat-label">الإجمالي</div>
    </div>
  </div>
  <div class="sl-stat">
    <div class="sl-stat-icon" style="background:#dcfce7;color:#15803d">✅</div>
    <div>
      <div class="sl-stat-value" style="color:#16a34a">{{ number_format($stats['sent']) }}</div>
      <div class="sl-stat-label">مُرسل</div>
    </div>
  </div>
  <div class="sl-stat">
    <div class="sl-stat-icon" style="background:#fee2e2;color:#b91c1c">❌</div>
    <div>
      <div class="sl-stat-value" style="color:#dc2626">{{ number_format($stats['failed']) }}</div>
      <div class="sl-stat-label">فشل</div>
    </div>
  </div>
  <div class="sl-stat">
    <div class="sl-stat-icon" style="background:#fef3c7;color:#d97706">📅</div>
    <div>
      <div class="sl-stat-value" style="color:#d97706">{{ number_format($stats['today']) }}</div>
      <div class="sl-stat-label">اليوم</div>
    </div>
  </div>
</div>

{{-- ═══ Provider Badge ═══ --}}
<div class="sl-provider">
  <div class="sl-provider-info">
    <span class="sl-provider-label">المزود الحالي:</span>
    <span class="sl-provider-badge">{{ strtoupper($provider) }}</span>
  </div>
  <div class="sl-provider-actions">
    @if($stats['failed'] > 0)
      <form method="POST" action="/dashboard/sms-logs/clear/failed" onsubmit="return confirm('حذف كل السجل الفاشل؟')" style="display:inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="sl-btn sl-btn-danger">🗑️ حذف الفاشلة ({{ $stats['failed'] }})</button>
      </form>
    @endif
  </div>
</div>

{{-- ═══ Filters ═══ --}}
<div class="sl-filters">
  <a href="/dashboard/sms-logs" class="sl-chip {{ !request('status') && !request('q') ? 'active' : '' }}">الكل</a>
  <a href="/dashboard/sms-logs?status=sent" class="sl-chip {{ request('status') === 'sent' ? 'active' : '' }}">✅ مُرسل</a>
  <a href="/dashboard/sms-logs?status=failed" class="sl-chip {{ request('status') === 'failed' ? 'active' : '' }}">❌ فاشل</a>
  <a href="/dashboard/sms-logs?status=pending" class="sl-chip {{ request('status') === 'pending' ? 'active' : '' }}">⏳ معلق</a>
  <form method="GET" class="sl-search">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="🔍 بحث في الرقم أو الرسالة..." class="sl-search-input">
    <button type="submit" class="sl-search-btn">بحث</button>
  </form>
</div>

{{-- ═══ Logs ═══ --}}
@if($logs->isEmpty())
  <div class="sl-empty">
    <div style="font-size:64px;margin-bottom:12px">📭</div>
    <h3>لا توجد رسائل</h3>
    <p>ستظهر هنا كل رسائل SMS المرسلة</p>
  </div>
@else
  <div class="sl-logs">
    @foreach($logs as $log)
      <div class="sl-log {{ $log->status }}">
        <div class="sl-log-status">
          @if($log->status === 'sent') ✅
          @elseif($log->status === 'failed') ❌
          @else ⏳
          @endif
        </div>
        <div class="sl-log-body">
          <div class="sl-log-head">
            <div class="sl-log-to">📱 {{ $log->to }}</div>
            <div class="sl-log-badge {{ $log->status }}">{{ $log->status }}</div>
          </div>
          <div class="sl-log-message">{{ $log->message }}</div>
          <div class="sl-log-meta">
            <span>🕐 {{ $log->created_at->diffForHumans() }}</span>
            @if($log->external_id)<span>🆔 {{ $log->external_id }}</span>@endif
            @if($log->error)<span style="color:#dc2626">⚠️ {{ substr($log->error, 0, 50) }}</span>@endif
          </div>
        </div>
        <div class="sl-log-actions">
          @if($log->status !== 'sent')
            <form method="POST" action="/dashboard/sms-logs/{{ $log->id }}/retry" style="display:inline">
              @csrf
              <button type="submit" class="sl-action sl-action-retry" title="إعادة الإرسال">🔄</button>
            </form>
          @endif
          <form method="POST" action="/dashboard/sms-logs/{{ $log->id }}" onsubmit="return confirm('حذف السجل؟')" style="display:inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="sl-action sl-action-delete" title="حذف">🗑️</button>
          </form>
        </div>
      </div>
    @endforeach
  </div>

  @if($logs->hasPages())
    <div style="margin-top:24px">{{ $logs->links() }}</div>
  @endif
@endif

<style>
.sl-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px}
@media(max-width:900px){.sl-stats{grid-template-columns:repeat(2,1fr)}}
.sl-stat{background:#fff;border:1px solid #e9edf2;border-radius:18px;padding:18px;display:flex;align-items:center;gap:14px;box-shadow:0 4px 16px rgba(0,0,0,.04);transition:.25s}
.sl-stat:hover{transform:translateY(-3px);box-shadow:0 12px 28px rgba(0,0,0,.08)}
.sl-stat-icon{width:48px;height:48px;border-radius:14px;display:grid;place-items:center;font-size:22px;flex-shrink:0}
.sl-stat-value{font-size:22px;font-weight:900;color:#17202b;line-height:1}
.sl-stat-label{font-size:11px;color:#94a3b8;font-weight:800;text-transform:uppercase;letter-spacing:.4px;margin-top:4px}

.sl-provider{background:linear-gradient(135deg,#f0f9ff,#e0f2fe);border:1px solid #bae6fd;border-radius:16px;padding:14px 20px;margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;gap:14px;flex-wrap:wrap}
.sl-provider-info{display:flex;align-items:center;gap:10px}
.sl-provider-label{font-size:13px;font-weight:800;color:#0c4a6e}
.sl-provider-badge{background:#0284c7;color:#fff;padding:5px 12px;border-radius:8px;font-size:12px;font-weight:900;letter-spacing:.5px}
.sl-btn{padding:9px 16px;border-radius:11px;font-size:12px;font-weight:900;border:0;cursor:pointer;transition:.2s;font-family:inherit}
.sl-btn-danger{background:#fee2e2;color:#b91c1c}
.sl-btn-danger:hover{background:#fecaca}

.sl-filters{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px;align-items:center}
.sl-chip{padding:8px 14px;background:#fff;border:1.5px solid #e2e8f0;border-radius:10px;font-size:12px;font-weight:800;color:#475569;text-decoration:none;transition:.2s}
.sl-chip:hover{border-color:#0284c7;color:#0284c7}
.sl-chip.active{background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;border-color:transparent;box-shadow:0 4px 12px rgba(2,132,199,.25)}
.sl-search{display:flex;gap:6px;margin-inline-start:auto}
.sl-search-input{padding:8px 14px;border:1.5px solid #e2e8f0;border-radius:11px;font-size:12px;font-weight:700;outline:none;font-family:inherit;min-width:220px;background:#fff}
.sl-search-input:focus{border-color:#0284c7;box-shadow:0 0 0 4px rgba(2,132,199,.1)}
.sl-search-btn{padding:8px 16px;background:#17202b;color:#fff;border:0;border-radius:11px;font-size:12px;font-weight:900;cursor:pointer;font-family:inherit}

.sl-logs{display:flex;flex-direction:column;gap:10px}
.sl-log{background:#fff;border:1px solid #e9edf2;border-radius:16px;padding:14px;display:flex;gap:14px;align-items:flex-start;transition:.25s;position:relative}
.sl-log:hover{transform:translateX(-3px);box-shadow:0 8px 22px rgba(0,0,0,.06)}
.sl-log.failed{background:linear-gradient(90deg,#fef2f2 0%,#fff 25%);border-color:#fecaca}
.sl-log.pending{background:linear-gradient(90deg,#fffbeb 0%,#fff 25%);border-color:#fcd34d}
.sl-log-status{font-size:24px;flex-shrink:0;width:40px;text-align:center}
.sl-log-body{flex:1;min-width:0}
.sl-log-head{display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:6px;flex-wrap:wrap}
.sl-log-to{font-size:13px;font-weight:900;color:#17202b;font-family:monospace}
.sl-log-badge{font-size:10px;font-weight:900;padding:4px 10px;border-radius:8px;letter-spacing:.5px}
.sl-log-badge.sent{background:#dcfce7;color:#15803d}
.sl-log-badge.failed{background:#fee2e2;color:#b91c1c}
.sl-log-badge.pending{background:#fef3c7;color:#d97706}
.sl-log-message{font-size:13px;color:#475569;line-height:1.6;margin-bottom:8px;word-break:break-word}
.sl-log-meta{display:flex;gap:14px;font-size:11px;color:#94a3b8;font-weight:700;flex-wrap:wrap}
.sl-log-actions{display:flex;gap:6px;flex-shrink:0;flex-direction:column}
.sl-action{width:36px;height:36px;border-radius:10px;background:#f8fafc;border:0;font-size:15px;cursor:pointer;transition:.2s;display:grid;place-items:center;font-family:inherit}
.sl-action:hover{background:#e2e8f0;transform:scale(1.08)}
.sl-action-retry:hover{background:#fef3c7}
.sl-action-delete:hover{background:#fee2e2}

.sl-empty{background:#fff;border:2px dashed #e5e7eb;border-radius:20px;padding:60px 24px;text-align:center}
.sl-empty h3{font-size:20px;font-weight:900;color:#17202b;margin-bottom:6px}
.sl-empty p{color:#64748b;font-size:14px}

html.dark .sl-stat,html.dark .sl-log,html.dark .sl-empty{background:#1a1d21;border-color:#2a2e33}
html.dark .sl-log-to,html.dark .sl-stat-value{color:#e5e7eb}
html.dark .sl-chip{background:#1a1d21;border-color:#2a2e33;color:#94a3b8}
html.dark .sl-search-input{background:#1a1d21;border-color:#2a2e33;color:#e5e7eb}
html.dark .sl-log-message{color:#cbd5e1}

@media(max-width:600px){
  .sl-search{width:100%;margin-inline-start:0}
  .sl-search-input{flex:1;min-width:0}
  .sl-log-actions{flex-direction:row}
}
</style>

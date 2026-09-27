@extends('layouts.app')
@section('title', $campaign->name)
@section('page-title', '📧 ' . $campaign->name)
@section('page-subtitle', $campaign->subject)

@section('content')

<style>
  .cs-wrap { max-width: 1000px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; }
  .cs-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
  .cs-stat { background: #fff; border-radius: 16px; padding: 16px; border: 1.5px solid #e2e8f0; text-align: center; }
  .cs-stat-value { font-size: 24px; font-weight: 900; line-height: 1; }
  .cs-stat-label { font-size: 11px; color: #64748b; font-weight: 800; margin-top: 4px; }

  .cs-card { background: #fff; border-radius: 18px; padding: 20px; border: 1.5px solid #e2e8f0; box-shadow: 0 4px 12px rgba(15,23,42,.04); }
  .cs-card-title { font-size: 14px; font-weight: 900; color: #0f172a; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px dashed #e2e8f0; }

  .cs-table { width: 100%; border-collapse: collapse; font-size: 13px; }
  .cs-table th { padding: 10px; text-align: right; font-size: 11px; font-weight: 900; color: #475569; background: #f8fafc; }
  .cs-table td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; }
  .cs-table tr:hover { background: #fafbfc; }
  .cs-status { padding: 3px 10px; border-radius: 99px; font-size: 10px; font-weight: 900; }
</style>

<div class="cs-wrap">

  {{-- الإحصائيات --}}
  <div class="cs-stats">
    <div class="cs-stat">
      <div class="cs-stat-value" style="color:#0f172a;">{{ number_format($stats['total']) }}</div>
      <div class="cs-stat-label">إجمالي المستلمين</div>
    </div>
    <div class="cs-stat">
      <div class="cs-stat-value" style="color:#10b981;">{{ number_format($stats['sent']) }}</div>
      <div class="cs-stat-label">تم الإرسال</div>
    </div>
    <div class="cs-stat">
      <div class="cs-stat-value" style="color:#dc2626;">{{ number_format($stats['failed']) }}</div>
      <div class="cs-stat-label">فشل</div>
    </div>
    <div class="cs-stat">
      <div class="cs-stat-value" style="color:#f59e0b;">{{ number_format($stats['pending']) }}</div>
      <div class="cs-stat-label">في الانتظار</div>
    </div>
  </div>

  {{-- معلومات --}}
  <div class="cs-card">
    <div class="cs-card-title">📋 معلومات الحملة</div>
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;font-size:13px;">
      <div><strong style="color:#64748b;">الحالة:</strong> <span style="padding:3px 10px;border-radius:99px;font-size:11px;font-weight:900;background:{{ $campaign->statusColor() }}20;color:{{ $campaign->statusColor() }};">{{ $campaign->statusLabel() }}</span></div>
      <div><strong style="color:#64748b;">الاستهداف:</strong> {{ $campaign->target_type }} @if($campaign->target_value) ({{ $campaign->target_value }}) @endif</div>
      <div><strong style="color:#64748b;">تاريخ الإنشاء:</strong> {{ $campaign->created_at->format('Y-m-d H:i') }}</div>
      <div><strong style="color:#64748b;">تاريخ الإرسال:</strong> {{ $campaign->sent_at ? $campaign->sent_at->format('Y-m-d H:i') : '—' }}</div>
    </div>

    @if($campaign->status !== 'sent')
      <form id="sendCampaignForm" method="POST" action="/dashboard/campaigns/{{ $campaign->id }}/send" style="margin-top:18px;">
        @csrf
        <button type="button" onclick="openSendConfirm()" style="padding:14px 24px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:0;border-radius:12px;font-size:13px;font-weight:900;cursor:pointer;font-family:inherit;box-shadow:0 8px 20px rgba(16,185,129,.35);display:inline-flex;align-items:center;gap:8px;transition:.2s;">
          🚀 إرسال الحملة الآن
        </button>
      </form>
    @endif
  </div>

  {{-- نص البريد --}}
  <div class="cs-card">
    <div class="cs-card-title">📄 نص البريد</div>
    <div style="background:#f8fafc;border-radius:12px;padding:16px;font-family:monospace;font-size:12px;white-space:pre-wrap;color:#334155;max-height:300px;overflow-y:auto;">{{ $campaign->body }}</div>
  </div>

  {{-- المستلمون --}}
  <div class="cs-card">
    <div class="cs-card-title">👥 المستلمون ({{ $recipients->total() }})</div>
    @if($recipients->count() > 0)
      <div style="overflow-x:auto;">
        <table class="cs-table">
          <thead>
            <tr>
              <th>#</th>
              <th>الاسم</th>
              <th>البريد</th>
              <th>الحالة</th>
              <th>التاريخ</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recipients as $i => $r)
              <tr>
                <td>{{ $recipients->firstItem() + $i }}</td>
                <td>{{ $r->name ?? '—' }}</td>
                <td dir="ltr" style="font-size:12px;">{{ $r->email }}</td>
                <td>
                  <span class="cs-status" style="background:{{ $r->statusColor() }}20;color:{{ $r->statusColor() }};">
                    {{ $r->statusLabel() }}
                  </span>
                </td>
                <td style="font-size:11px;color:#94a3b8;">{{ $r->sent_at ? $r->sent_at->format('Y-m-d H:i') : '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div style="margin-top:14px;">{{ $recipients->links() }}</div>
    @else
      <div style="text-align:center;padding:30px;color:#94a3b8;">لا يوجد مستلمون بعد</div>
    @endif
  </div>

  <div style="text-align:center;padding-top:10px;">
    <a href="/dashboard/campaigns" style="font-size:13px;font-weight:900;color:#64748b;text-decoration:none;">← العودة للقائمة</a>
  </div>

</div>


{{-- Modal: تأكيد إرسال الحملة --}}
<div id="sendConfirmModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.7);backdrop-filter:blur(8px);z-index:99999;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:24px;padding:32px;max-width:440px;width:100%;box-shadow:0 30px 80px rgba(0,0,0,0.35);text-align:center;animation:modalIn 0.3s cubic-bezier(.2,.9,.3,1.05);">

    <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#d1fae5,#a7f3d0);display:grid;place-items:center;margin:0 auto 18px;font-size:40px;box-shadow:0 10px 25px rgba(16,185,129,0.3);">
      🚀
    </div>

    <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin:0 0 10px;">إرسال الحملة الآن؟</h3>
    <p style="font-size:14px;color:#64748b;font-weight:700;line-height:1.7;margin:0 0 8px;">
      سيتم إرسال هذه الحملة إلى
    </p>
    <div style="display:inline-block;padding:8px 20px;background:linear-gradient(135deg,#fef3c7,#fde68a);border-radius:99px;font-size:15px;font-weight:900;color:#92400e;margin-bottom:20px;">
      👥 {{ $stats['total'] }} مستلم
    </div>

    <div style="padding:14px;background:#f8fafc;border-radius:14px;font-size:12px;color:#64748b;font-weight:700;line-height:1.7;margin-bottom:22px;text-align:right;">
      📧 <strong style="color:#334155;">العنوان:</strong> {{ $campaign->subject }}<br>
      🎯 <strong style="color:#334155;">الاستهداف:</strong> {{ $campaign->target_type }}
    </div>

    <div style="display:flex;gap:10px;">
      <button type="button" onclick="closeSendConfirm()" style="flex:1;padding:14px;background:#f1f5f9;color:#475569;border:0;border-radius:14px;font-size:14px;font-weight:900;cursor:pointer;font-family:inherit;">
        إلغاء
      </button>
      <button type="button" onclick="confirmSend()" id="confirmSendBtn" style="flex:2;padding:14px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:0;border-radius:14px;font-size:14px;font-weight:900;cursor:pointer;font-family:inherit;box-shadow:0 8px 20px rgba(16,185,129,0.35);display:inline-flex;align-items:center;justify-content:center;gap:8px;">
        ✅ نعم، إرسال الآن
      </button>
    </div>

  </div>
</div>

<style>
  @keyframes modalIn {
    from { opacity: 0; transform: scale(0.9) translateY(-20px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
  }
</style>

<script>
window.openSendConfirm = function() {
  document.getElementById('sendConfirmModal').style.display = 'flex';
};
window.closeSendConfirm = function() {
  document.getElementById('sendConfirmModal').style.display = 'none';
};
window.confirmSend = function() {
  var btn = document.getElementById('confirmSendBtn');
  btn.disabled = true;
  btn.innerHTML = '⏳ جاري الإرسال...';
  btn.style.background = 'linear-gradient(135deg,#64748b,#475569)';
  document.getElementById('sendCampaignForm').submit();
};
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeSendConfirm();
});
document.addEventListener('click', function(e) {
  if (e.target.id === 'sendConfirmModal') closeSendConfirm();
});
</script>

@endsection

@extends("layouts.app")
@section("title", "مركز SMS")
@section("page-title", "📱 مركز SMS")
@section("page-subtitle", "استقبل، أرسل، وخصّص كل رسائلك من مكان واحد")

@section("content")

<style>
  .sms-center-layout { display: grid; grid-template-columns: 240px 1fr; gap: 20px; }
  .sms-center-side { background: #fff; border-radius: 18px; padding: 14px; box-shadow: var(--shadow-sm); align-self: start; position: sticky; top: 20px; }
  .sms-center-side-title { font-size: 11px; font-weight: 900; color: var(--text-muted); letter-spacing: .5px; padding: 6px 12px; text-transform: uppercase; }
  .sms-tab { display: flex; align-items: center; gap: 10px; padding: 12px 14px; border-radius: 12px; text-decoration: none; color: var(--text); font-weight: 800; font-size: 13px; transition: all .2s; margin-bottom: 4px; }
  .sms-tab:hover { background: var(--bg); }
  .sms-tab.active { background: linear-gradient(135deg,#fbbf24,#f97316); color: #fff; box-shadow: 0 8px 20px rgba(249,115,22,.25); }
  .sms-tab.active .sms-tab-badge { background: rgba(255,255,255,.25); color: #fff; }
  .sms-tab-icon { width: 32px; height: 32px; border-radius: 10px; background: var(--bg); display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
  .sms-tab.active .sms-tab-icon { background: rgba(255,255,255,.2); }
  .sms-tab-label { flex: 1; }
  .sms-tab-badge { background: #f97316; color: #fff; border-radius: 99px; padding: 2px 8px; font-size: 10px; font-weight: 900; min-width: 20px; text-align: center; }
  .sms-tab-badge.muted { background: var(--bg); color: var(--text-muted); }
  .sms-center-main { min-width: 0; }
  @media (max-width: 900px) {
    .sms-center-layout { grid-template-columns: 1fr; gap: 12px; }
    .sms-center-side {
      position: static;
      padding: 10px;
      overflow-x: auto;
    }
    .sms-center-side-title { display: none; }
    .sms-tab {
      display: inline-flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 8px 12px;
      margin: 0 4px 0 0;
      gap: 2px;
      min-width: 80px;
      flex-shrink: 0;
    }
    .sms-tab-label { font-size: 11px; font-weight: 900; }
    .sms-tab-icon { width: 28px; height: 28px; font-size: 14px; }
    .sms-tab-badge { position: absolute; top: -4px; right: -4px; font-size: 9px; padding: 1px 5px; min-width: 16px; }
    .sms-center-side {
      display: flex;
      align-items: center;
      background: transparent;
      box-shadow: none;
      padding: 0 0 8px 0;
      gap: 6px;
    }
    .sms-tab { position: relative; }
    .sms-tab.active .sms-tab-badge { background: #fff; color: #f97316; }
  }
</style>

<div class="sms-center-layout">
  <aside class="sms-center-side">
    <div class="sms-center-side-title">صندوق SMS</div>

    <a href="{{ route('sms-center.index', ['tab' => 'inbox']) }}"
       class="sms-tab {{ $tab === 'inbox' ? 'active' : '' }}">
      <span class="sms-tab-icon">📥</span>
      <span class="sms-tab-label">الوارد</span>
      @if($inboxCount > 0)
        <span class="sms-tab-badge">{{ $inboxCount }}</span>
      @endif
    </a>

    <a href="{{ route('sms-center.index', ['tab' => 'logs']) }}"
       class="sms-tab {{ $tab === 'logs' ? 'active' : '' }}">
      <span class="sms-tab-icon">📤</span>
      <span class="sms-tab-label">السجل</span>
      @if($logsCount > 0)
        <span class="sms-tab-badge muted">{{ $logsCount }}</span>
      @endif
    </a>

    <a href="{{ route('sms-center.index', ['tab' => 'templates']) }}"
       class="sms-tab {{ $tab === 'templates' ? 'active' : '' }}">
      <span class="sms-tab-icon">✏️</span>
      <span class="sms-tab-label">القوالب</span>
      @if($templatesCount > 0)
        <span class="sms-tab-badge muted">{{ $templatesCount }}</span>
      @endif
    </a>
  </aside>

  <main class="sms-center-main">
    @if($tab === 'inbox')
      @include('dashboard.sms-center.partials._inbox')
    @elseif($tab === 'logs')
      @include('dashboard.sms-center.partials._logs')
    @elseif($tab === 'templates')
      @include('dashboard.sms-center.partials._templates')
    @endif
  </main>
</div>

@endsection

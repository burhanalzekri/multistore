<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'لوحة مدير المنصة') — MultiStore</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
  * { font-family: Cairo, sans-serif; box-sizing: border-box; }
  body { margin: 0; background: #f8fafc; }

  :root {
    --admin-primary: #7c3aed;
    --admin-primary-dark: #6d28d9;
    --admin-bg: #1e1b4b;
    --admin-sidebar-bg: #0f0d2e;
    --admin-sidebar-item: rgba(255,255,255,.7);
    --admin-sidebar-item-active: #fff;
  }

  /* ═══ Sidebar ═══ */
  .admin-super-sidebar {
    position: fixed;
    top: 0; right: 0;
    height: 100vh;
    width: 268px;
    background: var(--admin-sidebar-bg);
    display: flex;
    flex-direction: column;
    z-index: 40;
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    overflow-y: auto;
  }
  @media (max-width: 1024px) {
    .admin-super-sidebar { transform: translateX(100%); }
    .admin-super-sidebar.open { transform: translateX(0); }
  }

  .admin-super-header {
    padding: 20px 22px;
    border-bottom: 1px solid rgba(255,255,255,.08);
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .admin-super-logo {
    width: 46px; height: 46px;
    background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 22px;
    font-weight: 900;
    box-shadow: 0 8px 20px rgba(124,58,237,.4);
  }
  .admin-super-title { color: #fff; font-weight: 900; font-size: 15px; }
  .admin-super-subtitle { color: rgba(255,255,255,.5); font-size: 11px; margin-top: 3px; }

  .admin-super-nav { flex: 1; padding: 12px 0; }
  .admin-super-label {
    font-size: 10px;
    font-weight: 900;
    color: rgba(255,255,255,.35);
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: 14px 24px 6px;
  }
  .admin-super-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 22px;
    color: var(--admin-sidebar-item);
    text-decoration: none;
    font-weight: 700;
    font-size: 13px;
    border-right: 3px solid transparent;
    transition: all .2s;
  }
  .admin-super-item:hover {
    background: rgba(124,58,237,.15);
    color: #fff;
  }
  .admin-super-item.active {
    background: linear-gradient(90deg, rgba(124,58,237,.25), transparent);
    border-right-color: var(--admin-primary);
    color: #fff;
  }
  .admin-super-item i, .admin-super-item span.icon { width: 20px; text-align: center; font-size: 16px; }

  /* ═══ Main area ═══ */
  .admin-super-main {
    margin-right: 268px;
    min-height: 100vh;
    transition: margin-right 0.35s;
  }
  @media (max-width: 1024px) {
    .admin-super-main { margin-right: 0; }
  }

  .admin-super-topbar {
    background: #fff;
    padding: 14px 24px;
    display: flex;
    align-items: center;
    gap: 14px;
    border-bottom: 1px solid #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 30;
  }
  .admin-super-menu-btn {
    display: none;
    width: 40px; height: 40px;
    border-radius: 12px;
    background: #f1f5f9;
    border: 0;
    cursor: pointer;
  }
  @media (max-width: 1024px) {
    .admin-super-menu-btn { display: flex; align-items: center; justify-content: center; }
  }

  .admin-super-page-title { flex: 1; }
  .admin-super-page-title h1 { margin: 0; font-size: 18px; font-weight: 900; color: #0f172a; }
  .admin-super-page-title p { margin: 3px 0 0; font-size: 12px; color: #64748b; }

  .admin-super-user {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 12px;
    background: #faf5ff;
    border-radius: 12px;
    border: 1px solid #e9d5ff;
  }
  .admin-super-avatar {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 14px;
  }
  .admin-super-user-name { font-weight: 800; font-size: 13px; color: #0f172a; }
  .admin-super-user-role { font-size: 10px; color: var(--admin-primary); font-weight: 700; }

  .admin-super-content { padding: 24px; }

  .admin-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(15,23,42,.04);
    border: 1px solid #e2e8f0;
  }

  /* Overlay for mobile */
  .admin-super-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,.5);
    z-index: 35;
  }
  .admin-super-overlay.show { display: block; }
</style>
@stack('styles')
</head>
<body>

{{-- ═══ Sidebar ═══ --}}
<aside class="admin-super-sidebar" id="adminSuperSidebar">
  <div class="admin-super-header">
    <div class="admin-super-logo">M</div>
    <div>
      <div class="admin-super-title">MultiStore</div>
      <div class="admin-super-subtitle">لوحة مدير المنصة</div>
    </div>
  </div>

  <nav class="admin-super-nav">
    <div class="admin-super-label">الرئيسية</div>
    <a href="/super-admin" class="admin-super-item {{ request()->is('super-admin') ? 'active' : '' }}">
      <span class="icon">🏠</span>
      <span>نظرة عامة</span>
    </a>

    <div class="admin-super-label">إدارة المنصة</div>
    <a href="/super-admin/shops" class="admin-super-item {{ request()->is('super-admin/shops*') ? 'active' : '' }}">
      <span class="icon">🏪</span>
      <span>المتاجر</span>
    </a>
    <a href="/super-admin/users" class="admin-super-item {{ request()->is('super-admin/users*') ? 'active' : '' }}">
      <span class="icon">👥</span>
      <span>المستخدمون</span>
    </a>
    <a href="/super-admin/subscriptions" class="admin-super-item {{ request()->is('super-admin/subscriptions*') ? 'active' : '' }}">
      <span class="icon">💰</span>
      <span>الاشتراكات</span>
    </a>
    <a href="/super-admin/reports" class="admin-super-item {{ request()->is('super-admin/reports*') ? 'active' : '' }}">
      <span class="icon">📊</span>
      <span>تقارير المنصة</span>
    </a>

    <div class="admin-super-label">المحتوى</div>
    <a href="/dashboard/landing-settings" class="admin-super-item {{ request()->is('dashboard/landing-settings*') ? 'active' : '' }}">
      <span class="icon">🖼️</span>
      <span>الصفحة الرئيسية</span>
    </a>

    <div class="admin-super-label">النظام</div>
    <a href="/super-admin/system-health" class="admin-super-item {{ request()->is('super-admin/system-health*') ? 'active' : '' }}">
      <span class="icon">🔧</span>
      <span>صحة النظام</span>
    </a>

    <div class="admin-super-label">حسابي</div>
    <form method="POST" action="/logout" style="margin:0">
      @csrf
      <button type="submit" class="admin-super-item" style="width:100%;text-align:right;cursor:pointer;border:0;background:transparent;">
        <span class="icon">🚪</span>
        <span>تسجيل الخروج</span>
      </button>
    </form>
  </nav>
</aside>

<div class="admin-super-overlay" id="adminSuperOverlay" onclick="toggleAdminSuperSidebar()"></div>

{{-- ═══ Main ═══ --}}
<main class="admin-super-main">
  <header class="admin-super-topbar">
    <button class="admin-super-menu-btn" onclick="toggleAdminSuperSidebar()">☰</button>

    @unless(request()->is('super-admin') || request()->is('super-admin/'))
    <button type="button" onclick="history.back()" title="رجوع"
            style="width:40px;height:40px;border-radius:12px;background:#faf5ff;color:#7c3aed;border:1px solid #e9d5ff;cursor:pointer;font-size:16px;font-weight:900;font-family:inherit;flex-shrink:0;transition:all .2s;"
            onmouseover="this.style.background='#7c3aed';this.style.color='#fff'"
            onmouseout="this.style.background='#faf5ff';this.style.color='#7c3aed'">
      →
    </button>
    @endunless

    <div class="admin-super-page-title">
      <h1>@yield('page-title', 'لوحة مدير المنصة')</h1>
      @hasSection('page-subtitle')
        <p>@yield('page-subtitle')</p>
      @endif
    </div>
    <div class="admin-super-user">
      <div class="admin-super-avatar">{{ mb_substr(auth()->user()?->name ?? 'A', 0, 1) }}</div>
      <div style="text-align:right">
        <div class="admin-super-user-name">{{ auth()->user()?->name ?? 'Admin' }}</div>
        <div class="admin-super-user-role">مدير المنصة</div>
      </div>
    </div>
  </header>

  <div class="admin-super-content">
    @if(session('success'))
      <div style="background:#f0fdf4;color:#166534;padding:14px 18px;border-radius:14px;margin-bottom:16px;font-weight:700;font-size:13px;border:1px solid #bbf7d0;">
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div style="background:#fef2f2;color:#991b1b;padding:14px 18px;border-radius:14px;margin-bottom:16px;font-weight:700;font-size:13px;border:1px solid #fecaca;">
        {{ session('error') }}
      </div>
    @endif

    @yield('content')
  </div>
</main>

@stack('scripts')
<script>
  function toggleAdminSuperSidebar() {
    document.getElementById('adminSuperSidebar').classList.toggle('open');
    document.getElementById('adminSuperOverlay').classList.toggle('show');
  }
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'لوحة التحكم') — {{ $currentShop->name ?? 'منصتي' }}</title>
<script>(function(){const t=localStorage.getItem('theme')||'light';if(t==='dark')document.documentElement.classList.add('dark');})();</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { darkMode: 'class' };</script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#f59e0b">
<link rel="stylesheet" href="/css/app.css">
<link rel="stylesheet" href="/css/print.css" media="print">
<style>
  :root {
    --primary: #f59e0b;
    --primary-dark: #d97706;
    --primary-light: #fef3c7;
    --bg: #f5f7fa;
    --surface: #ffffff;
    --border: #e8ecf1;
    --text: #1f2937;
    --text-muted: #8899a8;
    --shadow-sm: 0 1px 2px rgba(15,23,42,0.04);
    --shadow-md: 0 4px 16px rgba(15,23,42,0.08);
    --shadow-lg: 0 12px 32px rgba(15,23,42,0.12);
    --radius: 18px;
  }
  html.dark {
    --bg: #0f172a;
    --surface: #1e293b;
    --border: #334155;
    --text: #f1f5f9;
    --text-muted: #94a3b8;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'Cairo', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    transition: background 0.3s, color 0.3s;
  }

  /* ═══ Sidebar ═══ */
  .admin-sidebar {
    position: fixed;
    top: 0; right: 0;
    height: 100vh;
    width: 268px;
    background: var(--surface);
    border-left: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    z-index: 40;
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    overflow-y: auto;
    overflow-x: hidden;
  }
  @media (max-width: 1024px) {
    .admin-sidebar { transform: translateX(100%); }
    .admin-sidebar.open { transform: translateX(0); }
  }

  .admin-sidebar-header {
    padding: 20px 22px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 12px;
    min-height: 80px;
  }
  .admin-logo {
    width: 46px; height: 46px;
    background: linear-gradient(135deg, #fbbf24, #f97316);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    box-shadow: 0 6px 16px rgba(245,158,11,0.35);
    flex-shrink: 0;
  }
  .admin-shop-info h1 {
    font-size: 15px;
    font-weight: 900;
    color: var(--text);
    line-height: 1.2;
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .admin-shop-info p {
    font-size: 11px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 3px;
  }
  .pulse-dot {
    width: 7px; height: 7px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 2s infinite;
    box-shadow: 0 0 0 0 rgba(16,185,129,0.5);
  }
  @keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(16,185,129,0.5); }
    70% { box-shadow: 0 0 0 8px rgba(16,185,129,0); }
    100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
  }

  .admin-nav {
    flex: 1;
    padding: 14px 12px;
    display: flex;
    flex-direction: column;
    gap: 2px;
    overflow-y: auto;
  }
  .admin-nav-label {
    font-size: 10px;
    font-weight: 900;
    color: var(--text-muted);
    letter-spacing: 1px;
    padding: 14px 12px 6px;
    text-transform: uppercase;
  }
  .admin-nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    border-radius: 12px;
    color: #64748b;
    text-decoration: none;
    font-weight: 700;
    font-size: 13.5px;
    transition: all 0.2s;
    position: relative;
    white-space: nowrap;
  }
  .admin-nav-item svg { width: 19px; height: 19px; flex-shrink: 0; }
  .admin-nav-item:hover {
    background: #f8fafc;
    color: var(--primary);
  }
  html.dark .admin-nav-item:hover { background: rgba(255,255,255,0.05); }
  .admin-nav-item.active {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    font-weight: 900;
  }
  html.dark .admin-nav-item.active {
    background: rgba(245,158,11,0.15);
    color: #fbbf24;
  }
  .admin-nav-item.active::before {
    content: '';
    position: absolute;
    right: -12px;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 60%;
    background: var(--primary);
    border-radius: 4px 0 0 4px;
  }
  .admin-nav-badge {
    margin-right: auto;
    background: #ef4444;
    color: white;
    font-size: 10px;
    font-weight: 900;
    padding: 2px 8px;
    border-radius: 999px;
    min-width: 20px;
    text-align: center;
    line-height: 16px;
  }

  .admin-user { margin-top: auto;
    padding: 14px 18px;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--surface);
  }
  .admin-user-avatar {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, #fbbf24, #f97316);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    color: white;
    font-size: 16px;
    flex-shrink: 0;
  }
  .admin-user-info { flex: 1; min-width: 0; }
  .admin-user-info h3 {
    font-size: 13px;
    font-weight: 800;
    color: var(--text);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .admin-user-info p {
    font-size: 11px;
    color: var(--text-muted);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  /* ═══ Main ═══ */
  .admin-main {
    margin-right: 268px;
    min-height: 100vh;
    padding-bottom: 20px;
  }
  @media (max-width: 1024px) {
    .admin-main { margin-right: 0; padding-bottom: 90px; }
  }

  /* ═══ Header ═══ */
  .admin-header {
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border);
    padding: 0 20px;
    height: 76px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 30;
    gap: 16px;
  }
  html.dark .admin-header {
    background: rgba(30,41,59,0.85);
  }
  .admin-header-left { display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0; }
  .admin-header-title h2 {
    font-size: 19px;
    font-weight: 900;
    color: var(--text);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .admin-header-title p {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 2px;
  }
  @media (max-width: 640px) {
    .admin-header { padding: 0 14px; height: 68px; }
    .admin-header-title h2 { font-size: 16px; }
    .admin-header-title p { display: none; }
  }
  .admin-header-actions { display: flex; align-items: center; gap: 8px; }

  .admin-icon-btn {
    width: 42px; height: 42px;
    border-radius: 12px;
    background: #f8fafc;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
    text-decoration: none;
  }
  .admin-icon-btn:hover {
    background: var(--primary-light);
    color: var(--primary);
    transform: translateY(-1px);
  }
  html.dark .admin-icon-btn { background: rgba(255,255,255,0.05); }
  html.dark .admin-icon-btn:hover { background: rgba(245,158,11,0.15); }
  .admin-icon-btn svg { width: 20px; height: 20px; }

  .admin-theme-toggle {
    width: 56px; height: 30px;
    background: linear-gradient(135deg, #60a5fa, #3b82f6);
    border-radius: 999px;
    position: relative;
    cursor: pointer;
    border: none;
    padding: 0;
    transition: background 0.3s;
  }
  html.dark .admin-theme-toggle {
    background: linear-gradient(135deg, #1e293b, #334155);
  }
  .admin-theme-thumb {
    position: absolute;
    top: 3px; right: 3px;
    width: 24px; height: 24px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: transform 0.4s cubic-bezier(0.68,-0.55,0.265,1.55);
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
  }
  html.dark .admin-theme-thumb {
    transform: translateX(-26px);
    background: #fbbf24;
  }

  /* ═══ Content ═══ */
  .admin-content {
    padding: 22px;
  }
  @media (max-width: 640px) {
    .admin-content { padding: 14px; }
  }

  /* ═══ Cards ═══ */
  .admin-card {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
  }

  /* ═══ Mobile Bottom Nav ═══ */
  .admin-bottom-nav {
    display: none;
    position: fixed;
    bottom: 0; left: 0; right: 0;
    background: var(--surface);
    border-top: 1px solid var(--border);
    padding: 8px 0;
    z-index: 35;
    box-shadow: 0 -4px 16px rgba(0,0,0,0.06);
  }
  @media (max-width: 1024px) {
    .admin-bottom-nav { display: flex; justify-content: space-around; }
  }
  .admin-bottom-nav a {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
    padding: 6px 10px;
    border-radius: 10px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 10px;
    font-weight: 800;
    transition: 0.2s;
    min-width: 56px;
  }
  .admin-bottom-nav a.active {
    color: var(--primary);
    background: var(--primary-light);
  }
  html.dark .admin-bottom-nav a.active {
    background: rgba(245,158,11,0.15);
  }
  .admin-bottom-nav svg { width: 20px; height: 20px; }

  /* ═══ Overlay ═══ */
  .admin-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 39;
    backdrop-filter: blur(2px);
  }
  .admin-overlay.show { display: block; }

  /* ═══ Scrollbar ═══ */
  .admin-sidebar::-webkit-scrollbar { width: 4px; }
  .admin-sidebar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 2px; }
  html.dark .admin-sidebar::-webkit-scrollbar-thumb { background: #475569; }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- DEBUG: زر اختبار -->
<button onclick="alert('JS يعمل: ' + typeof showToast); if(window.showToast) showToast('اختبار ناجح!', 'error'); else alert('showToast مفقود')" style="position:fixed;bottom:20px;left:20px;z-index:99999;padding:12px;background:red;color:white;border:none;border-radius:8px;font-weight:900;cursor:pointer;font-family:inherit;">
  🧪 اختبار التنبيه
</button>

  @stack('styles')
</head>
<body>

<!-- Overlay -->
<div class="admin-overlay" id="adminOverlay" onclick="toggleAdminSidebar()"></div>

<!-- ═══ Sidebar ═══ -->
<aside class="admin-sidebar" id="adminSidebar">
  <div class="admin-sidebar-header">
    <div class="admin-logo" style="background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;font-family:Cairo,sans-serif;font-weight:900;font-size:20px;letter-spacing:-1px;box-shadow:0 4px 12px rgba(245,158,11,0.3);">M</div>
    <div class="admin-shop-info">
      <h1>{{ $currentShop->name ?? 'منصتي' }}</h1>
      <p><span class="pulse-dot"></span> متجر نشط</p>
    </div>
  </div>

  @php
    $route = request()->path();
    $pendingOrders = \App\Models\Order::where('status','awaiting_payment')->count();
    $reviewSms = \App\Models\SmsInbox::where('status','review')->count();
  @endphp

  <nav class="admin-nav">
    <div class="admin-nav-label">الرئيسية</div>
    <a href="/dashboard/smart" class="admin-nav-item {{ $route === 'dashboard' ? 'active' : '' }}">
      <i data-lucide="home"></i>
      <span>لوحة التحكم</span>
    </a>

    <div class="admin-nav-label">المتجر</div>
    <a href="/dashboard/products" class="admin-nav-item {{ str_starts_with($route, 'dashboard/products') ? 'active' : '' }}">
      <i data-lucide="package"></i>
      <span>المنتجات</span>
    </a>

    <a href="/dashboard/landing-settings" class="admin-nav-item {{ str_starts_with($route, 'dashboard/landing-settings') ? 'active' : '' }}">
      <i data-lucide="layout-template"></i>
      <span>إعدادات الصفحة الرئيسية</span>
    </a>

      <a href="{{ route('variants.index') }}" class="admin-nav-item {{ request()->routeIs('variants.*') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
        <span>المتغيرات</span>
      </a>
    <a href="/dashboard/categories" class="admin-nav-item {{ str_starts_with($route, 'dashboard/categories') ? 'active' : '' }}">
      <i data-lucide="folder"></i>
      <span>التصنيفات</span>
    </a>
    <a href="/dashboard/coupons" class="admin-nav-item {{ str_starts_with($route, 'dashboard/coupons') ? 'active' : '' }}">
      <i data-lucide="ticket"></i>
      <span>الكوبونات</span>
    </a>
    <a href="/dashboard/notifications" class="admin-nav-item {{ str_starts_with($route, 'dashboard/notifications') ? 'active' : '' }}">
      <span>🔔</span>
      <span>الإشعارات</span>
      @php $__unread = \App\Models\AdminNotification::where('is_read', false)->count(); @endphp
      @if($__unread > 0)
        <span style="margin-inline-start:auto;background:#dc2626;color:#fff;font-size:10px;font-weight:900;padding:2px 7px;border-radius:999px">{{ $__unread }}</span>
      @endif
    </a>
    <a href="/dashboard/scanner" class="admin-nav-item {{ str_starts_with($route, 'dashboard/scanner') ? 'active' : '' }}">
      <span>🔍</span>
      <span>ماسح الباركوود</span>
    </a>
    <a href="/dashboard/analytics" class="admin-nav-item {{ str_starts_with($route, 'dashboard/analytics') && !str_starts_with($route, 'dashboard/analytics/variants') ? 'active' : '' }}">
      <i data-lucide="bar-chart-3"></i>
      <span>التحليلات</span>
    </a>
    <a href="/dashboard/analytics/variants" class="admin-nav-item {{ str_starts_with($route, 'dashboard/analytics/variants') ? 'active' : '' }}">
      <i data-lucide="pie-chart"></i>
      <span>تحليلات المتغيرات</span>
    </a>
    <a href="/dashboard/loyalty" class="admin-nav-item {{ str_starts_with($route, 'dashboard/loyalty') ? 'active' : '' }}">
      <i data-lucide="award"></i>
      <span>برنامج الولاء</span>
    </a>
    <a href="/dashboard/flash-sales" class="admin-nav-item {{ str_starts_with($route, 'dashboard/flash-sales') ? 'active' : '' }}">
      <i data-lucide="zap"></i>
      <span>عروض فلاش</span>
    </a>

    <div class="admin-nav-label">المبيعات</div>
    @if(auth()->check() && (auth()->user()->role === "super_admin" || auth()->user()->role === "shop_admin" || in_array("orders.view", auth()->user()->permissions ?? []) || in_array("orders.edit", auth()->user()->permissions ?? [])))
    <a href="/dashboard/orders" class="admin-nav-item {{ str_starts_with($route, 'dashboard/orders') ? 'active' : '' }}">
      <i data-lucide="shopping-bag"></i>
      <span>الطلبات</span>
      @if($pendingOrders > 0)
      <span class="admin-nav-badge">{{ $pendingOrders }}</span>
      @endif
    </a>

    <a href="/dashboard/shipping-zones" class="admin-nav-item {{ str_starts_with($route, 'dashboard/shipping-zones') ? 'active' : '' }}">
      <i data-lucide="truck"></i>
      <span>مناطق الشحن</span>
    </a>
    @endif
    <a href="/dashboard/payments" class="admin-nav-item {{ str_starts_with($route, 'dashboard/payments') ? 'active' : '' }}">
      <i data-lucide="wallet"></i>
      <span>المدفوعات</span>
    </a>
    <a href="/dashboard/reports" class="admin-nav-item {{ str_starts_with($route, 'dashboard/reports') ? 'active' : '' }}">
      <i data-lucide="bar-chart-3"></i>
      <span>التقارير</span>
    </a>

    <div class="admin-nav-label">التواصل</div>
    @php
      $smsActive = str_starts_with($route, 'dashboard/sms-center')
        || str_starts_with($route, 'dashboard/sms')
        || str_starts_with($route, 'dashboard/sms-logs')
        || str_starts_with($route, 'dashboard/sms-templates');
    @endphp
    <a href="/dashboard/sms-center" class="admin-nav-item {{ $smsActive ? 'active' : '' }}">
      <i data-lucide="message-square"></i>
      <span>مركز SMS</span>
      @if($reviewSms > 0)
      <span class="admin-nav-badge">{{ $reviewSms }}</span>
      @endif
    </a>
    <a href="/dashboard/contact-messages" class="admin-nav-item {{ str_starts_with($route, 'dashboard/contact-messages') ? 'active' : '' }}">
      <i data-lucide="mail"></i>
      <span>الرسائل</span>
    </a>
    <a href="/dashboard/testimonials" class="admin-nav-item {{ str_starts_with($route, 'dashboard/testimonials') ? 'active' : '' }}">
      <i data-lucide="quote"></i>
      <span>آراء العملاء</span>
    </a>
    <a href="/dashboard/reviews" class="admin-nav-item {{ str_starts_with($route, 'dashboard/reviews') ? 'active' : '' }}">
      <i data-lucide="star"></i>
      <span>المراجعات</span>
    </a>

    <div class="admin-nav-label">الإعدادات</div>
    <a href="/dashboard/tasks" class="admin-nav-item {{ str_starts_with($route, "dashboard/tasks") ? "active" : "" }}"><i data-lucide="check-square"></i><span>المهام</span></a>
    <a href="/dashboard/staff" class="admin-nav-item {{ str_starts_with($route, "dashboard/staff") ? "active" : "" }}"><i data-lucide="users"></i><span>الموظفون</span></a>
    <a href="/dashboard/activity-logs" class="admin-nav-item {{ str_starts_with($route, "dashboard/activity-logs") ? "active" : "" }}"><i data-lucide="history"></i><span>سجل النشاطات</span></a>
    <a href="/dashboard/recommendations" class="admin-nav-item {{ str_starts_with($route, "dashboard/recommendations") ? "active" : "" }}"><i data-lucide="brain"></i><span>الذكاء والإحصائيات</span></a>
    <a href="/dashboard/profile/password" class="admin-nav-item {{ request()->is('dashboard/profile/password') ? 'active' : '' }}">
      <i data-lucide="key"></i>
      <span>كلمة المرور</span>
    </a>
    <a href="/dashboard/settings" class="admin-nav-item {{ str_starts_with($route, 'dashboard/settings') ? 'active' : '' }}">
      <i data-lucide="settings"></i>
      <span>الإعدادات</span>
    </a>
    <a href="/shop" target="_blank" class="admin-nav-item">
      <i data-lucide="external-link"></i>
      <span>عرض المتجر</span>
    </a>
  
    @if(auth()->check() && auth()->user()->role === "super_admin")
    <div class="admin-nav-label">👑 المشرف العام</div>
    <a href="/super-admin" class="admin-nav-item {{ $route === "super-admin" ? "active" : "" }}">
      <i data-lucide="crown"></i>
      <span>لوحة المشرف</span>
    </a>
    <a href="/super-admin/shops" class="admin-nav-item {{ str_starts_with($route, "super-admin/shops") ? "active" : "" }}">
      <i data-lucide="store"></i>
      <span>المتاجر</span>
    </a>
    <a href="/super-admin/subscriptions" class="admin-nav-item {{ str_starts_with($route, "super-admin/subscriptions") ? "active" : "" }}">
      <i data-lucide="dollar-sign"></i>
      <span>الاشتراكات</span>
    </a>
    <a href="/super-admin/reports" class="admin-nav-item {{ str_starts_with($route, "super-admin/reports") ? "active" : "" }}">
      <i data-lucide="bar-chart-3"></i>
      <span>تقارير المنصة</span>
    </a>
    @endif

</nav>

  @auth
  <div class="admin-user">
    <div class="admin-user-avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</div>
    <div class="admin-user-info">
      <h3>{{ auth()->user()?->name }}</h3>
      <p>{{ auth()->user()?->email }}</p>
    </div>
    <form method="POST" action="/logout">
      @csrf
      <button type="submit" class="admin-icon-btn" title="خروج" style="width:36px;height:36px;">
        <i data-lucide="log-out" style="width:16px;height:16px;"></i>
      </button>
    </form>
  </div>
  @endauth
</aside>

<!-- ═══ Main ═══ -->
<div class="admin-main">

@if(session('impersonate'))
  @php $imp = session('impersonate'); @endphp
  <div id="impersonate-banner" style="background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;padding:12px 20px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;position:sticky;top:0;z-index:50;box-shadow:0 4px 12px rgba(124,58,237,.3);">
    <div style="display:flex;align-items:center;gap:10px;font-weight:800;font-size:13px;">
      <span style="font-size:20px;">🎭</span>
      <span>أنت تتصفح كتاجر: <b>{{ $imp['shop_name'] ?? 'متجر' }}</b></span>
    </div>
    <a href="{{ route('super-admin.stop-impersonating') }}"
       style="background:rgba(255,255,255,.2);color:#fff;padding:8px 16px;border-radius:10px;text-decoration:none;font-weight:900;font-size:12px;border:1px solid rgba(255,255,255,.3);">
      🚪 العودة للإدارة
    </a>
  </div>
@endif

  <header class="admin-header">
    <div class="admin-header-left">
      <button class="admin-icon-btn" onclick="toggleAdminSidebar()" style="display:none;" id="adminMenuBtn">
        <i data-lucide="menu"></i>
      </button>
      @unless(request()->is('dashboard') || request()->is('dashboard/'))
      <button type="button" class="admin-icon-btn" onclick="history.back()" title="رجوع"
              style="background:#fff7ed;color:#ea580c;border:1px solid #fed7aa;">
        <i data-lucide="arrow-right"></i>
      </button>
      @endunless
      <div class="admin-header-title">
        <h2>@yield('page-title', 'لوحة التحكم')</h2>
        <p>@yield('page-subtitle', now()->translatedFormat('l، j F Y'))</p>
      </div>
    </div>

    <div class="admin-header-actions">
      <form method="POST" action="/logout" style="display:inline;margin:0;">
        @csrf
        <button type="submit" class="admin-icon-btn" title="خروج" id="logout-quick-btn" style="background:#fee2e2;color:#dc2626;">
          <i data-lucide="log-out"></i>
        </button>
      </form>
      <a href="/dashboard/sms-center?tab=inbox" class="admin-icon-btn" title="الإشعارات">
        <i data-lucide="bell"></i>
        @if($reviewSms > 0)
        <span style="position:absolute;top:-4px;left:-4px;background:#ef4444;color:white;font-size:9px;font-weight:900;min-width:18px;height:18px;border-radius:999px;display:flex;align-items:center;justify-content:center;padding:0 4px;">{{ $reviewSms }}</span>
        @endif
      </a>

      <button class="admin-icon-btn" onclick="enableSmartNotifications ? enableSmartNotifications() : enableNotifications()" title="تفعيل الإشعارات الصوتية">
        <i data-lucide="bell-ring"></i>
      </button>

      <button class="admin-theme-toggle" onclick="toggleAdminTheme()">
        <span class="admin-theme-thumb" id="adminThemeThumb">☀️</span>
      </button>
    </div>
  </header>

  <main class="admin-content">
    @if(session('success'))
    <div style="background:#dcfce7;color:#15803d;padding:14px 18px;border-radius:14px;margin-bottom:20px;font-weight:800;display:flex;align-items:center;gap:8px;font-size:14px;">
      <i data-lucide="check-circle" style="width:20px;height:20px;"></i>
      {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="background:#fee2e2;color:#b91c1c;padding:14px 18px;border-radius:14px;margin-bottom:20px;font-weight:800;display:flex;align-items:center;gap:8px;font-size:14px;">
      <i data-lucide="alert-circle" style="width:20px;height:20px;"></i>
      {{ session('error') }}
    </div>
    @endif

    @yield('content')
  </main>
</div>

<!-- ═══ Bottom Nav ═══ -->
<nav class="admin-bottom-nav">
  <a href="/dashboard" class="{{ $route === 'dashboard' ? 'active' : '' }}">
    <i data-lucide="home"></i>
    <span>الرئيسية</span>
  </a>
  <a href="/dashboard/products" class="{{ str_starts_with($route, 'dashboard/products') ? 'active' : '' }}">
    <i data-lucide="package"></i>
    <span>المنتجات</span>
  </a>
  <a href="/dashboard/landing-settings" class="{{ str_starts_with($route, 'dashboard/landing-settings') ? 'active' : '' }}">
    <i data-lucide="layout-template"></i>
    <span>الرئيسية</span>
  </a>
  <a href="/dashboard/orders" class="{{ str_starts_with($route, 'dashboard/orders') ? 'active' : '' }}">
    <i data-lucide="shopping-bag"></i>
    <span>الطلبات</span>
  </a>
  @php
    $mobSmsActive = str_starts_with($route, 'dashboard/sms-center')
      || str_starts_with($route, 'dashboard/sms')
      || str_starts_with($route, 'dashboard/sms-logs')
      || str_starts_with($route, 'dashboard/sms-templates');
  @endphp
  <a href="/dashboard/sms-center" class="{{ $mobSmsActive ? 'active' : '' }}">
    <i data-lucide="message-square"></i>
    <span>SMS</span>
  </a>
  <a href="/dashboard/tasks" class="admin-nav-item {{ str_starts_with($route, "dashboard/tasks") ? "active" : "" }}"><i data-lucide="check-square"></i><span>المهام</span></a>
    <a href="/dashboard/staff" class="admin-nav-item {{ str_starts_with($route, "dashboard/staff") ? "active" : "" }}"><i data-lucide="users"></i><span>الموظفون</span></a>
    <a href="/dashboard/activity-logs" class="admin-nav-item {{ str_starts_with($route, "dashboard/activity-logs") ? "active" : "" }}"><i data-lucide="history"></i><span>سجل النشاطات</span></a>
    <a href="/dashboard/recommendations" class="admin-nav-item {{ str_starts_with($route, "dashboard/recommendations") ? "active" : "" }}"><i data-lucide="brain"></i><span>الذكاء والإحصائيات</span></a>
    <a href="/dashboard/settings" class="{{ str_starts_with($route, 'dashboard/settings') ? 'active' : '' }}">
    <i data-lucide="settings"></i>
    <span>الإعدادات</span>
  </a>
</nav>

<script>
function toggleAdminSidebar() {
  const sb = document.getElementById('adminSidebar');
  const ov = document.getElementById('adminOverlay');
  sb.classList.toggle('open');
  ov.classList.toggle('show');
}

function toggleAdminTheme() {
  const isDark = document.documentElement.classList.toggle('dark');
  localStorage.setItem('theme', isDark ? 'dark' : 'light');
  document.getElementById('adminThemeThumb').textContent = isDark ? '🌙' : '☀️';
}

function enableNotifications() {
  if (!('Notification' in window)) { alert('المتصفح لا يدعم الإشعارات'); return; }
  Notification.requestPermission().then(p => {
    if (p === 'granted') {
      new Notification('✅ تم تفعيل الإشعارات', { body: 'ستصلك إشعارات فورية', icon: '/icon-192.png' });
    }
  });
}

// الوضع الليلي
if (document.documentElement.classList.contains('dark')) {
  document.getElementById('adminThemeThumb').textContent = '🌙';
}

// إظهار زر القائمة على الجوال
function checkAdminMobile() {
  const btn = document.getElementById('adminMenuBtn');
  if (window.innerWidth <= 1024) {
    btn.style.display = 'flex';
  } else {
    btn.style.display = 'none';
  }
}
checkAdminMobile();
window.addEventListener('resize', checkAdminMobile);

// polling للإشعارات
setInterval(() => {
  fetch('/api/notifications/check').then(r => r.json()).then(d => {
    if (d.new_orders > 0 && Notification.permission === 'granted') {
      new Notification('🛒 طلب جديد!', { body: `لديك ${d.new_orders} طلب`, icon: '/icon-192.png' });
    }
  }).catch(() => {});
}, 20000);

lucide.createIcons();
</script>

<script>
// ═══ شارة التنبيهات الذكية في Header ═══
(function() {
    function updateSmartBadge() {
        fetch("/api/notifications/check")
            .then(r => r.json())
            .then(data => {
                const total = (data.review_sms || 0) + (data.pending_orders || 0);

                let badge = document.getElementById("adminSmartBadge");
                if (total > 0) {
                    if (!badge) {
                        const notifBtn = document.querySelector(".admin-header-actions .admin-icon-btn");
                        if (notifBtn) {
                            badge = document.createElement("span");
                            badge.id = "adminSmartBadge";
                            badge.className = "admin-smart-badge";
                            badge.style.cssText = "position:absolute;top:-4px;left:-4px;background:#ef4444;color:white;font-size:9px;font-weight:900;min-width:18px;height:18px;border-radius:999px;display:flex;align-items:center;justify-content:center;padding:0 4px;";
                            notifBtn.style.position = "relative";
                            notifBtn.appendChild(badge);
                        }
                    }
                    if (badge) badge.textContent = total;
                } else if (badge) {
                    badge.remove();
                }
            })
            .catch(() => {});
    }

    setInterval(updateSmartBadge, 20000);
    setTimeout(updateSmartBadge, 2000);
})();
</script>

  {{-- 🔔 التنبيهات --}}
  @include('components.toast')

    <!-- Alpine.js for interactive UI -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

<style>
.notif-toast-wrap{position:fixed;top:20px;left:20px;right:20px;z-index:2147483646;display:flex;flex-direction:column;gap:10px;pointer-events:none;max-width:420px}
@media(min-width:640px){.notif-toast-wrap{left:auto;right:20px;max-width:400px}}
.notif-toast{background:#fff;border-radius:18px;padding:14px 16px;box-shadow:0 8px 20px rgba(15,23,42,.12),0 20px 48px -12px rgba(15,23,42,.25);border:1.5px solid #fde68a;display:flex;align-items:flex-start;gap:12px;transform:translateY(-20px);opacity:0;transition:all .4s cubic-bezier(.34,1.56,.64,1);pointer-events:auto;position:relative;overflow:hidden;cursor:pointer}
.notif-toast::before{content:'';position:absolute;top:0;right:0;bottom:0;width:4px;background:linear-gradient(180deg,#f59e0b,#d97706)}
.notif-toast.show{transform:translateY(0);opacity:1}
.notif-toast-icon{width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;display:grid;place-items:center;font-size:20px;flex-shrink:0;box-shadow:0 6px 16px rgba(217,119,6,.35);animation:notifBell 1s infinite alternate}
@keyframes notifBell{0%{transform:rotate(-8deg) scale(1)}100%{transform:rotate(8deg) scale(1.05)}}
.notif-toast-body{flex:1;min-width:0}
.notif-toast-title{font-size:13px;font-weight:900;color:#17202b;margin-bottom:3px;line-height:1.3}
.notif-toast-msg{font-size:12px;font-weight:700;color:#64748b;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.notif-toast-close{width:24px;height:24px;border-radius:50%;border:0;background:#f1f5f9;color:#64748b;font-size:14px;cursor:pointer;display:grid;place-items:center;flex-shrink:0;font-weight:900;transition:.2s}
.notif-toast-close:hover{background:#e2e8f0;color:#dc2626}
</style>

<div id="notifToastWrap" class="notif-toast-wrap"></div>

<script>
(function(){
  var LAST_ID = 0;
  var POLL_MS = 15000;
  var FIRST = true;

  // 🔊 صوت تنبيه عبر Web Audio API — بدون ملف
  function playChime(){
    try {
      var AC = window.AudioContext || window.webkitAudioContext;
      if (!AC) return;
      var ctx = new AC();
      var now = ctx.currentTime;
      // نغمة ثلاثية (C5 - E5 - G5)
      [523.25, 659.25, 783.99].forEach(function(freq, i){
        var osc = ctx.createOscillator();
        var gain = ctx.createGain();
        osc.type = 'sine';
        osc.frequency.value = freq;
        gain.gain.setValueAtTime(0, now + i*0.12);
        gain.gain.linearRampToValueAtTime(0.15, now + i*0.12 + 0.02);
        gain.gain.exponentialRampToValueAtTime(0.001, now + i*0.12 + 0.35);
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start(now + i*0.12);
        osc.stop(now + i*0.12 + 0.4);
      });
      setTimeout(function(){ try{ ctx.close(); }catch(e){} }, 1200);
    } catch(e){}
  }

  function showToast(notif){
    var wrap = document.getElementById('notifToastWrap');
    if (!wrap) return;
    var el = document.createElement('div');
    el.className = 'notif-toast';
    var url = notif.data && notif.data.url ? notif.data.url : '/dashboard/notifications';
    el.innerHTML =
      '<div class="notif-toast-icon">🔔</div>' +
      '<div class="notif-toast-body">' +
        '<div class="notif-toast-title">' + (notif.title || 'إشعار جديد') + '</div>' +
        '<div class="notif-toast-msg">' + (notif.message || '') + '</div>' +
      '</div>' +
      '<button class="notif-toast-close" type="button">×</button>';
    el.querySelector('.notif-toast-close').onclick = function(e){
      e.stopPropagation(); el.remove();
    };
    el.onclick = function(){ window.location.href = url; };
    wrap.appendChild(el);
    setTimeout(function(){ el.classList.add('show'); }, 50);
    setTimeout(function(){
      el.classList.remove('show');
      setTimeout(function(){ el.remove(); }, 500);
    }, 8000);
    playChime();
  }

  function check(){
    fetch('/api/notifications/check', {
      headers: {'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}
    })
    .then(function(r){ return r.json(); })
    .then(function(d){
      if (!d || !d.notifications) return;
      var items = d.notifications;
      if (FIRST) {
        // أول تحميل — احفظ آخر ID بدون عرض
        if (items.length) LAST_ID = items[0].id || 0;
        FIRST = false;
        return;
      }
      var newOnes = items.filter(function(n){ return (n.id||0) > LAST_ID; });
      if (newOnes.length){
        newOnes.forEach(function(n){ showToast(n); });
        LAST_ID = newOnes[0].id || LAST_ID;
      }
    })
    .catch(function(){});
  }

  setTimeout(function(){
    check();
    setInterval(check, POLL_MS);
  }, 3000);
})();
</script>
  @stack('scripts')
<script src="{{ asset('js/ui-feedback.js') }}" defer></script>
</body>
</html>

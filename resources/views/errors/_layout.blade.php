<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield("title", "خطأ") — MultiStore</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
  * { font-family: Cairo, sans-serif; box-sizing: border-box; }
  html, body { margin: 0; min-height: 100vh; }
  body {
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .modal-card {
    animation: slideUp 0.4s cubic-bezier(0.2, 0.9, 0.3, 1.1);
    max-width: 420px;
    width: 100%;
  }
  @keyframes slideUp {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
  }
  .code-grad {
    background: linear-gradient(135deg, #f59e0b, #ea580c);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 900;
  }
  .btn-primary {
    background: linear-gradient(135deg, #f59e0b, #f97316);
    transition: all 0.2s;
  }
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(249,115,22,0.4);
  }
</style>
</head>
<body>

<div class="modal-card">
  <div style="background: white; border-radius: 24px; padding: 32px 24px; text-align: center; box-shadow: 0 30px 80px -20px rgba(0,0,0,0.5); position: relative;">

    {{-- Close X --}}
    <button onclick="history.back()"
            style="position: absolute; top: 16px; left: 16px; width: 36px; height: 36px; border-radius: 50%; border: 0; background: #f1f5f9; color: #64748b; font-size: 18px; font-weight: 700; cursor: pointer;">
      ✕
    </button>

    {{-- Logo M --}}
    <div style="width: 56px; height: 56px; border-radius: 16px; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; color: white; font-size: 26px; font-weight: 900; background: linear-gradient(135deg, #fbbf24, #f97316); box-shadow: 0 10px 25px -5px rgba(249,115,22,0.5);">
      M
    </div>

    {{-- Icon --}}
    <div style="font-size: 44px; margin-bottom: 12px; line-height: 1;">@yield("icon", "⚠️")</div>

    {{-- Code --}}
    <div style="font-size: 52px; line-height: 1; margin-bottom: 12px;" class="code-grad">
      @yield("code", "?!")
    </div>

    {{-- Title --}}
    <h1 style="font-size: 20px; font-weight: 900; color: #0f172a; margin: 0 0 10px; letter-spacing: -0.5px;">
      @yield("title", "حدث خطأ")
    </h1>

    {{-- Message --}}
    <p style="font-size: 14px; color: #64748b; line-height: 1.7; margin: 0 0 24px;">
      @yield("message", "")
    </p>

    {{-- Buttons --}}
    <div style="display: flex; gap: 10px;">
      <button onclick="history.back()"
              style="flex: 1; padding: 12px; border-radius: 14px; background: white; border: 2px solid #e2e8f0; color: #475569; font-weight: 700; font-size: 13px; cursor: pointer; font-family: inherit;">
        → رجوع
      </button>
      <a href="/"
         style="flex: 1; padding: 12px; border-radius: 14px; color: white; font-weight: 700; font-size: 13px; text-decoration: none; text-align: center;"
         class="btn-primary">
        🏠 الرئيسية
      </a>
    </div>

    {{-- Footer --}}
    <div style="margin-top: 20px; font-size: 11px; color: #94a3b8;">
      MultiStore
    </div>
  </div>
</div>

</body>
</html>

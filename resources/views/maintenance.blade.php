<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>الموقع تحت الصيانة</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'Cairo', sans-serif;
    min-height: 100vh;
    background: linear-gradient(135deg, #fef3c7, #fed7aa, #fbbf24);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .maintenance-box {
    background: white;
    border-radius: 24px;
    padding: 48px 32px;
    max-width: 500px;
    width: 100%;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  @keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .icon {
    font-size: 80px;
    margin-bottom: 20px;
    animation: bounce 2s infinite;
  }
  @keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-12px); }
  }
  h1 {
    font-size: 28px;
    font-weight: 900;
    color: #1f2937;
    margin-bottom: 12px;
  }
  p {
    font-size: 16px;
    color: #6b7280;
    margin-bottom: 24px;
    line-height: 1.7;
  }
  .end-time {
    background: #fef3c7;
    color: #92400e;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 800;
    font-size: 14px;
    display: inline-block;
    margin-bottom: 24px;
  }
  .brand {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid #f1f5f9;
    color: #9ca3af;
    font-size: 13px;
    font-weight: 700;
  }
  .brand span { font-size: 20px; }
</style>
</head>
<body>
<div class="maintenance-box">
  <div class="icon">🛠️</div>
  <h1>الموقع تحت الصيانة</h1>
  <p>{{ $message }}</p>
  @if($ends_at)
  <div class="end-time">⏰ سيعود قريبًا: {{ \Carbon\Carbon::parse($ends_at)->diffForHumans() }}</div>
  @endif
  <div class="brand">
    <span>🍯</span>
    <span>منصتي</span>
  </div>
</div>
</body>
</html>

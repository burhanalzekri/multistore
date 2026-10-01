<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>غير متصل — MultiStore</title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;900&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Tajawal',system-ui,sans-serif;background:linear-gradient(135deg,#fef3c7 0%,#fed7aa 100%);min-height:100vh;display:grid;place-items:center;padding:20px;color:#17202b}
.off{max-width:440px;text-align:center;background:#fff;border-radius:28px;padding:50px 32px;box-shadow:0 25px 70px rgba(0,0,0,.15)}
.off-icon{font-size:90px;margin-bottom:20px;display:block;animation:off-bob 2.5s ease-in-out infinite}
@keyframes off-bob{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.off h1{font-size:26px;font-weight:900;margin-bottom:12px}
.off p{color:#64748b;font-size:15px;line-height:1.7;margin-bottom:28px}
.off-btn{display:inline-flex;align-items:center;gap:10px;padding:14px 32px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:0;border-radius:14px;font-size:15px;font-weight:900;cursor:pointer;box-shadow:0 12px 30px rgba(217,119,6,.35);transition:.2s;font-family:inherit}
.off-btn:hover{transform:translateY(-2px);box-shadow:0 18px 40px rgba(217,119,6,.45)}
.off-links{margin-top:28px;padding-top:22px;border-top:1px solid #f0eeea;display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.off-link{font-size:13px;font-weight:800;color:#d97706;text-decoration:none;padding:8px 14px;background:#fef3c7;border-radius:10px}
.off-link:hover{background:#fcd34d}
</style>
</head>
<body>
<div class="off">
  <span class="off-icon">📡</span>
  <h1>لا يوجد اتصال بالإنترنت</h1>
  <p>تحقق من اتصالك بالشبكة ثم حاول مرة أخرى</p>
  <button class="off-btn" onclick="location.reload()">
    🔄 إعادة المحاولة
  </button>
  <div class="off-links">
    <a href="/cart" class="off-link">🛒 السلة</a>
    <a href="/wishlist" class="off-link">❤️ المفضلة</a>
    <a href="/" class="off-link">🏠 الرئيسية</a>
  </div>
</div>

<script>
  // إعادة تلقائية عند العودة للإنترنت
  window.addEventListener('online', function() {
    setTimeout(function() { location.reload(); }, 1000);
  });
</script>
</body>
</html>

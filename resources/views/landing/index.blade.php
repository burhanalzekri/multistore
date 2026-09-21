<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>منصتي — متاجر إلكترونية يمنية</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">
<style>body{font-family:'Cairo',sans-serif}</style>
</head>
<body class="bg-slate-50 text-slate-800">

<header class="bg-white shadow-sm sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
    <a href="/" class="text-xl font-black text-amber-600">🍯 منصتي</a>
    <div class="flex gap-2">
      <a href="/login" class="px-3 py-2 text-sm font-semibold">دخول</a>
      <a href="/dashboard" class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm font-bold">لوحة التحكم</a>
    </div>
  </div>
</header>

<section class="max-w-7xl mx-auto px-4 py-12 md:py-24 grid md:grid-cols-2 gap-8 items-center">
  <div>
    <span class="inline-block bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-bold mb-4">
      🤖 تحقق آلي من الدفع عبر SMS
    </span>
    <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">
      أنشئ متجرك الإلكتروني <span class="text-amber-600">في دقيقتين</span>
    </h1>
    <p class="text-base md:text-lg text-slate-600 mb-6 leading-relaxed">
      منصة متكاملة للمتاجر اليمنية: منتجات، طلبات، دفع عبر بنك الكريمي ومحافظ إلكترونية،
      تحقق تلقائي من التحويلات، وإشعارات SMS فورية.
    </p>
    <a href="/dashboard" class="inline-block px-6 py-3 bg-amber-600 text-white rounded-xl font-bold shadow-lg hover:bg-amber-700">
      ابدأ متجرك الآن →
    </a>
  </div>
  <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-3xl p-6 shadow-2xl">
    <div class="bg-white rounded-2xl p-5">
      <div class="flex items-center gap-2 mb-3 font-bold">💰 دفعة جديدة وصلت</div>
      <div class="bg-slate-50 rounded-lg p-3 text-xs font-mono mb-3 leading-relaxed">
        تم استلام مبلغ <b>15,000</b> ريال<br>
        من: 777123456<br>
        رقم العملية: 882134567
      </div>
      <div class="text-green-600 text-sm font-bold">✅ تم تأكيد الطلب #1001 آليًا</div>
    </div>
  </div>
</section>

<section class="py-12 bg-white">
  <div class="max-w-7xl mx-auto px-4">
    <h2 class="text-2xl md:text-3xl font-black text-center mb-8">كل ما تحتاجه</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="p-5 rounded-2xl border border-slate-100">
        <div class="text-3xl mb-2">🤖</div>
        <h3 class="font-bold mb-1">تحقق آلي</h3>
        <p class="text-sm text-slate-600">يقرأ رسائل SMS ويؤكد الطلب فورًا</p>
      </div>
      <div class="p-5 rounded-2xl border border-slate-100">
        <div class="text-3xl mb-2">📱</div>
        <h3 class="font-bold mb-1">إشعارات SMS</h3>
        <p class="text-sm text-slate-600">للعميل والمدير عند كل طلب</p>
      </div>
      <div class="p-5 rounded-2xl border border-slate-100">
        <div class="text-3xl mb-2">🏪</div>
        <h3 class="font-bold mb-1">متاجر متعددة</h3>
        <p class="text-sm text-slate-600">إدارة عشرات المتاجر من لوحة واحدة</p>
      </div>
      <div class="p-5 rounded-2xl border border-slate-100">
        <div class="text-3xl mb-2">💳</div>
        <h3 class="font-bold mb-1">بوابات متعددة</h3>
        <p class="text-sm text-slate-600">الكريمي، جوالي، الدفع عند الاستلام</p>
      </div>
      <div class="p-5 rounded-2xl border border-slate-100">
        <div class="text-3xl mb-2">📊</div>
        <h3 class="font-bold mb-1">تقارير ذكية</h3>
        <p class="text-sm text-slate-600">مبيعات وعملاء وأفضل المنتجات</p>
      </div>
      <div class="p-5 rounded-2xl border border-slate-100">
        <div class="text-3xl mb-2">🌐</div>
        <h3 class="font-bold mb-1">نطاق مخصص</h3>
        <p class="text-sm text-slate-600">اربط نطاقك مع SSL مجاني</p>
      </div>
    </div>
  </div>
</section>

<footer class="bg-slate-900 text-slate-400 py-8 text-center text-sm">
  © {{ date('Y') }} منصتي — جميع الحقوق محفوظة
</footer>
</body>
</html>
